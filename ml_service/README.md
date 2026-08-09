# 🧠 StudentShield ML Microservice Management Guide

The **StudentShield ML Service** is a Python FastAPI microservice that processes multilingual student vents (Tagalog, Taglish, English) to detect emotions, calculate suicide/self-harm risk scores, and detect bullying incidents using deep learning models and FAISS vector memory.

---

## 📂 Microservice File Overview

| File / Folder | Purpose |
| :--- | :--- |
| `main.py` | Core FastAPI application server (endpoints for `/analyze`, `/health`, RAG memory, rate limiting). |
| `requirements.txt` | Python dependencies required by the service (FastAPI, PyTorch, Transformers, FAISS, etc.). |
| `generate_dataset.py` | Script to build synthetic Tagalog/English dataset for training and evaluation. |
| `train_models.py` | Fine-tuning script for mDeBERTa-v3 / Transformer text classification models. |
| `evaluate_val.py` | Validation script to evaluate classification accuracy, precision, recall, and F1 score. |
| `StudentShield_Colab_Trainer.ipynb` | Google Colab Notebook for training models on cloud GPUs. |
| `faiss_memory/` | Vector store database for similarity search and context-aware risk analysis. |
| `models/` | Saved model checkpoints and tokenizer files. |
| `Dockerfile` | Container configuration for cloud deployment (Hugging Face Spaces, Docker, Cloud Run). |

---

## 🚀 1. Operating the Service

### A. Run Locally
```bash
# Navigate to ml_service directory
cd ml_service

# Activate virtual environment
# Windows:
.venv\Scripts\activate
# Linux/macOS:
source .venv/bin/activate

# Start server (Development mode with auto-reload)
uvicorn main:app --host 0.0.0.0 --port 8000 --reload
```

### B. Production Deployment (Docker)
```bash
# Build Docker image
docker build -t studentshield-ml .

# Run Container on Port 8000
docker run -d -p 8000:7860 --name studentshield-ml-container studentshield-ml
```

---

## 🧪 2. Testing Endpoints & API Documentation

Interactive OpenAPI documentation is available when the service is running:
- **Interactive Swagger UI**: [http://localhost:8000/docs](http://localhost:8000/docs)
- **ReDoc Documentation**: [http://localhost:8000/redoc](http://localhost:8000/redoc)
- **Health Check Endpoint**: `GET http://localhost:8000/health`

### Sample Request (`POST /analyze`):
```json
{
  "text": "Pakiramdam ko wala na akong magagawang tama, ayoko na talaga magpatuloy.",
  "user_id": 1
}
```

---

## 🏋️ 3. Retraining & Updating Models

If you need to retrain or fine-tune models with new student vent data:

1. **Prepare Dataset**:
   ```bash
   python generate_dataset.py
   ```
2. **Train Model**:
   ```bash
   python train_models.py
   ```
3. **Evaluate Performance**:
   ```bash
   python evaluate_val.py
   ```
4. **Train on Cloud GPU (Optional)**:
   Upload `StudentShield_Colab_Trainer.ipynb` to Google Colab and run with a free T4 GPU backend.

---

## 🔒 4. Maintenance & Monitoring

- **Rate Limiting**: Built-in `slowapi` rate limits protect API endpoints from denial-of-service.
- **Model Storage**: Model files stored in `models/` can be swapped or updated without changing the API contract.
- **FAISS Vector Memory**: The `faiss_memory/` index is updated dynamically as vents are analyzed to maintain contextual similarity lookup.
