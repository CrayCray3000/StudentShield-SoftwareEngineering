import os
import time
import pytest
import asyncio
from httpx import AsyncClient, ASGITransport

# Ensure ML_API_KEY is set before importing app
os.environ["ML_API_KEY"] = "test-secret-key"

from ml_service.main import app

@pytest.mark.asyncio
async def test_analyze_text_concurrency():
    transport = ASGITransport(app=app)
    async with AsyncClient(transport=transport, base_url="http://test") as client:
        headers = {"X-API-KEY": "test-secret-key"}
        payload = {"raw_text": "I feel sad today", "student_id": "std_123"}

        # Test basic request response correctness
        response = await client.post("/analyze-text", json=payload, headers=headers)
        assert response.status_code == 200
        data = response.json()
        assert "risk_score" in data
        assert "risk_level" in data

        # Benchmark concurrent requests
        concurrent_count = 5
        start_time = time.perf_counter()
        tasks = [
            client.post("/analyze-text", json={"raw_text": f"I feel sad today {i}", "student_id": f"std_{i}"}, headers=headers)
            for i in range(concurrent_count)
        ]
        responses = await asyncio.gather(*tasks)
        end_time = time.perf_counter()

        for r in responses:
            assert r.status_code == 200

        elapsed = end_time - start_time
        print(f"\n[BENCHMARK] Total time for {concurrent_count} concurrent requests: {elapsed:.4f}s")
