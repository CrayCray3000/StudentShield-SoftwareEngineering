"""Exercise the complete risk rules offline, with translation and model scores mocked."""

import importlib.util
import sys
from pathlib import Path
from types import SimpleNamespace

import pytest
import torch


@pytest.fixture
def analyzer(monkeypatch):
    service_dir = Path(__file__).resolve().parents[1]
    monkeypatch.syspath_prepend(str(service_dir))
    classifier = SimpleNamespace(
        tokenizer=lambda *args, **kwargs: {"attention_mask": torch.ones((1, 1))},
        model=SimpleNamespace(
            base_model=lambda **kwargs: SimpleNamespace(
                last_hidden_state=torch.zeros((1, 1, 768))
            )
        ),
    )
    # Stub the lazy Transformers module itself so import-time model loading
    # cannot make a network request, even on the first import.
    monkeypatch.setitem(sys.modules, "transformers", SimpleNamespace(
        pipeline=lambda *args, **kwargs: classifier
    ))
    monkeypatch.setattr("nltk.data.find", lambda *args, **kwargs: True)
    # Deliberately omit the crisis wording from the translation: the raw Tagalog
    # must be enough to trigger the safety rules despite a low model score.
    monkeypatch.setattr("deep_translator.GoogleTranslator.translate", lambda *args: "I am sad.")
    spec = importlib.util.spec_from_file_location("risk_rules_under_test", service_dir / "main.py")
    module = importlib.util.module_from_spec(spec)
    spec.loader.exec_module(module)
    monkeypatch.setattr(module, "get_risk_components", lambda *args: (0.0, 0.0))
    return module.analyze_text_sync


@pytest.mark.parametrize("text", [
    "sinasaksak ko sarili ko sa sobrang lungkot. like naglalaslas",
    "naglalaslas ako",
    "maglalaslas ako haha charot",
    "naglaslas ako",
    "maglaslas",
    "nilalaslas ko ang braso ko",
    "nilaslas ko ang pulso ko",
    "laslasin ko ang pulso ko",
    "sinasaktan ko sarili ko",
    "sinaktan ko ang sarili ko",
    "sasaktan ang sarili ko",
    "saktan ko ang sarili",
    "sinasaksak ko sarili ko",
    "sinaksak ang sarili ko",
    "sasaksakin ko ang sarili ko",
    "saksakin ko ang sarili",
    "SINASAKTAN KO\nANG SARILI KO lol",
    "kill myself",
    "magpakamatay",
])
def test_explicit_self_harm_is_critical_despite_low_scores(analyzer, text):
    result = analyzer(text)
    assert result["risk_level"] == "CRITICAL"
    assert 86 <= result["risk_score"] <= 100


@pytest.mark.parametrize("text", [
    "sinasaktan ako ng kaklase ko",
    "sinaksak ang kaklase ko",
    "sinasaktan ang damdamin ko",
    "inaalagaan ko ang sarili ko",
    "nalulungkot ako",
    "Naglalaslaser ang laruan",
])
def test_other_directed_harm_and_ordinary_sadness_are_not_critical(analyzer, text):
    assert analyzer(text)["risk_level"] != "CRITICAL"
