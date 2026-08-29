# 🤖 GitHub & Jules Assistant Instructions

Please refer to [`AGENTS.md`](../AGENTS.md) and [`AI_CONTEXT.md`](../AI_CONTEXT.md) in the repository root for full architectural constraints, production guardrails, and coding guidelines.

### Quick Rules:
1. **PHP/CI4 Web App**: Hosted on InfinityFree (no SSH/CLI). Keep changes compatible with standard shared hosting PHP 8.1+.
2. **FastAPI ML Service**: Deployed on Hugging Face Spaces. Auto-synced via `.github/workflows/deploy_hf.yml`.
3. **Encryption**: Maintain CI4 `AES-256-CTR` application-level encryption on student vent logs.
4. **Safety Net**: Never delete or strip out local Taglish/Filipino crisis keyword lists in `VentController.php` or `ml_service/main.py`.
