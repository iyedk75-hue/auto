---
id: T02
parent: S01
milestone: M002
provides:
  - `resolvedResources()` contract on Course
  - synthetic legacy resource fallback for `media_path` / `pdf_path` courses
  - precedence rule where persisted child resources override legacy fields
  - focused transition tests for legacy and child-resource-backed courses
requires:
  - slice: S01
    provides: child resource schema/model from T01
affects: [S02, S03, S04]
key_files:
  - app/Models/Course.php
  - app/Models/CourseResource.php
  - tests/Feature/CourseTransitionTest.php
key_decisions:
  - "Persisted child resources win over legacy fields when both are present."
  - "Legacy single-resource courses are represented as synthetic resolved resource items during transition instead of being broken or forced through an immediate destructive migration."
patterns_established:
  - "Course-level resource consumption should go through `resolvedResources()` rather than reading child relations or legacy fields directly."
drill_down_paths:
  - .gsd/milestones/M002/slices/S01/tasks/T02-PLAN.md
duration: 35m
verification_result: pass
completed_at: 2026-03-15T17:00:00Z
---

# T02: Add normalized resource resolution with legacy compatibility

**Courses now expose one effective resource list whether they use the new child model or the legacy single-file fields.**

## What Happened

Added `resolvedResources()` to `Course` so downstream code can consume one ordered resource list without caring whether the course is backed by real child resources or older single-file fields. When child resources exist, they are used exclusively. When they do not, the course generates synthetic legacy resource items from `media_path` and `pdf_path`.

This locks in the transition rule that later slices can rely on: no duplicate mixing of child and legacy resources, and no broken old courses while the UI migrates. The synthetic items also carry stable keys and origin metadata, which gives later slices a deterministic way to render and select legacy resources.

`CourseTransitionTest` now proves legacy-resource resolution, child-resource precedence, and deterministic order/origin behavior.

## Deviations

None.

## Files Created/Modified

- `app/Models/Course.php` — normalized resource resolution and legacy fallback
- `app/Models/CourseResource.php` — resolved-array support for child resources
- `tests/Feature/CourseTransitionTest.php` — legacy compatibility verification
