"""Deterministic trajectory scoring over durable database-backed risk history."""

from __future__ import annotations

from datetime import datetime, timedelta, timezone
from typing import Iterable, Mapping, Any


RISK_VALUES = {"LOW": 1.0, "MEDIUM": 2.0, "HIGH": 3.0, "CRITICAL": 4.0}


def _parse_timestamp(value: Any) -> datetime | None:
    if isinstance(value, datetime):
        parsed = value
    elif isinstance(value, str) and value.strip():
        try:
            parsed = datetime.fromisoformat(value.strip().replace("Z", "+00:00"))
        except ValueError:
            return None
    else:
        return None

    if parsed.tzinfo is None:
        return parsed.replace(tzinfo=timezone.utc)
    return parsed.astimezone(timezone.utc)


def _slope(values: list[float]) -> float:
    count = len(values)
    if count < 2:
        return 0.0

    mean_x = (count - 1) / 2.0
    mean_y = sum(values) / count
    denominator = sum((index - mean_x) ** 2 for index in range(count))
    if denominator == 0:
        return 0.0

    return sum(
        (index - mean_x) * (value - mean_y)
        for index, value in enumerate(values)
    ) / denominator


def evaluate_risk_history(
    history: Iterable[Mapping[str, Any]],
    current_risk: str,
    *,
    now: datetime | None = None,
) -> tuple[bool, str, float]:
    """Return repeated-severe flag, trajectory, and escalation probability.

    The caller supplies prior risk records from the durable application database.
    The current preliminary classification is appended in memory, so no ML-service
    filesystem state is required and Space rebuilds cannot erase student history.
    """

    reference_time = now or datetime.now(timezone.utc)
    if reference_time.tzinfo is None:
        reference_time = reference_time.replace(tzinfo=timezone.utc)
    else:
        reference_time = reference_time.astimezone(timezone.utc)

    cutoff = reference_time - timedelta(days=14)
    records: list[tuple[datetime, str]] = []

    for item in history:
        risk = str(item.get("risk_level", "")).upper()
        timestamp = _parse_timestamp(item.get("created_at"))
        if risk in RISK_VALUES and timestamp is not None and cutoff <= timestamp <= reference_time:
            records.append((timestamp, risk))

    normalized_current = str(current_risk).upper()
    if normalized_current not in RISK_VALUES:
        normalized_current = "LOW"
    records.append((reference_time, normalized_current))
    records.sort(key=lambda record: record[0])

    recent_severe = sum(1 for _, risk in records if risk in {"HIGH", "CRITICAL"})
    if len(records) < 3:
        return recent_severe >= 3, "INSUFFICIENT_DATA", 0.0

    scores = [RISK_VALUES[risk] for _, risk in records]
    trend_slope = _slope(scores)
    if trend_slope > 0.05:
        trajectory = "ESCALATING"
    elif trend_slope < -0.05:
        trajectory = "IMPROVING"
    else:
        trajectory = "STABLE"

    base_probability = 0.2
    slope_factor = max(0.0, min(1.0, trend_slope * 2.0))
    severity_factor = min(1.0, recent_severe / 3.0) * 0.3
    frequency_factor = min(1.0, len(records) / 10.0) * 0.2
    escalation_probability = round(
        min(0.99, base_probability + slope_factor + severity_factor + frequency_factor),
        2,
    )

    return recent_severe >= 3, trajectory, escalation_probability
