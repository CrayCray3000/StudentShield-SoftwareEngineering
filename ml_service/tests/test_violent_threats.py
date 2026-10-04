import importlib.util
import json
import sys
from pathlib import Path
from types import ModuleType, SimpleNamespace
from unittest.mock import Mock

import pytest

SERVICE = Path(__file__).resolve().parents[1]
sys.path.insert(0, str(SERVICE))
from violent_threats import detect_violent_threat

CASES = json.loads((Path(__file__).parent / 'fixtures/violent_threats.json').read_text())


@pytest.mark.parametrize('case', CASES, ids=lambda case: case['text'][:60])
def test_direct_threats_require_intent_and_human_target(case):
    result = detect_violent_threat(case['text'])
    assert (result is not None) == case['critical']
    if result:
        assert result['risk_level'] == 'CRITICAL'
        assert result['risk_percent'] >= 99


@pytest.fixture
def app_module(monkeypatch):
    """Exercise the real rule engine/API with no model downloads or translation."""
    import nltk
    import torch

    model = SimpleNamespace(base_model=lambda **_: SimpleNamespace(last_hidden_state=torch.ones(1, 1, 768)))
    classifier = SimpleNamespace(
        model=model,
        tokenizer=lambda *_, **__: {'attention_mask': torch.ones(1, 1)},
    )
    transformers = ModuleType('transformers')
    transformers.pipeline = lambda *_, **__: classifier
    monkeypatch.setitem(sys.modules, 'transformers', transformers)
    monkeypatch.setattr(nltk.data, 'find', lambda *_: 'local-test-data')
    monkeypatch.setattr(nltk, 'download', Mock(side_effect=AssertionError('Tests must not download tokenizers')))
    spec = importlib.util.spec_from_file_location('studentshield_threat_test_app', SERVICE / 'main.py')
    module = importlib.util.module_from_spec(spec)
    monkeypatch.setitem(sys.modules, spec.name, module)
    spec.loader.exec_module(module)
    monkeypatch.setattr(module, 'get_risk_components', lambda *_: (0.0, 0.0))
    monkeypatch.setattr(module, 'GoogleTranslator', lambda **_: SimpleNamespace(translate=lambda text: text))
    monkeypatch.setattr(module, 'API_KEY', 'test-only-api-key')
    return module


def test_exact_vent_overrides_neutral_model_and_has_violence_reason(app_module):
    result = app_module.analyze_text_sync(CASES[0]['text'])
    assert result['risk_level'] == 'CRITICAL'
    assert 99 <= result['risk_score'] <= 100
    assert 'another person' in result['flag_reason']


@pytest.mark.parametrize('text', [
    'Thankful ako sa friends ko pero papatayin ko sila haha.',
    'I will kill them lol. I am grateful for my friends.',
])
def test_positive_or_joking_text_cannot_lower_explicit_threat(app_module, text):
    assert app_module.analyze_text_sync(text)['risk_level'] == 'CRITICAL'


def test_existing_self_harm_and_passive_ideation_rules_are_preserved(app_module):
    assert app_module.analyze_text_sync('I want to kill myself.')['risk_level'] == 'CRITICAL'
    assert app_module.analyze_text_sync('Para saan pa ako.')['risk_level'] == 'HIGH'


def test_api_classifies_exact_vent_without_relaxing_authentication(app_module):
    from fastapi.testclient import TestClient

    with TestClient(app_module.app) as client:
        payload = {'raw_text': CASES[0]['text']}
        assert client.post('/analyze-text', json=payload).status_code in (401, 403)
        assert client.post('/analyze-text', json=payload, headers={'X-API-KEY': 'wrong'}).status_code == 401
        response = client.post('/analyze-text', json=payload, headers={'X-API-KEY': 'test-only-api-key'})
        assert response.status_code == 200
        assert response.json()['risk_level'] == 'CRITICAL'
        assert response.json()['risk_score'] >= 99
