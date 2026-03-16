---
estimated_steps: 6
estimated_files: 3
---

# T01: Add candidate resource-selection contract and feature proof

**Slice:** S03 — Student classroom-style resource list
**Milestone:** M002

## Description

Move the candidate course page off direct single-resource assumptions by giving it a stable selected-resource contract based on `resolvedResources()`.

## Steps

1. Add a candidate-facing resource selection view model in `CandidateCourseController` that orders resources, computes the selected resource from the query string, and falls back safely to the first available item.
2. Map each resolved resource to downstream-ready viewer metadata such as localized type label, localized date label, and the correct viewer URL.
3. Add any route wiring needed for candidate resource file viewing so child resources can render through authenticated URLs.
4. Create a focused feature test covering default selection, explicit selection, and legacy-resource fallback.
5. Verify no direct storage URLs are required by the candidate view model.
6. Rerun the focused feature test until the contract is stable.

## Must-Haves

- [ ] Selected-resource resolution is deterministic and safe for invalid query-string values.
- [ ] The candidate page gets one normalized list contract for notes, videos, PDFs, and legacy fallback items.

## Verification

- `php artisan test --filter=CandidateCourseResourceViewTest`
- The test proves first-resource default selection, explicit selection, and legacy-resource rendering.

## Observability Impact

- Signals added/changed: selected resource key becomes explicit in rendered candidate page state
- How a future agent inspects this: load `courses.show` with and without `?resource=...` and compare the rendered selected viewer state
- Failure state exposed: invalid selection falls back incorrectly, resource URLs are missing, or legacy resources disappear

## Inputs

- `app/Models/Course.php` — normalized `resolvedResources()` output and legacy precedence behavior
- `.gsd/milestones/M002/slices/S01/S01-SUMMARY.md` — downstream rendering must consume the normalized display fields instead of rebuilding locale logic

## Expected Output

- `app/Http/Controllers/CandidateCourseController.php` — candidate resource selection contract and viewer metadata mapping
- `routes/web.php` — candidate resource file route wiring if needed for child resources
- `tests/Feature/CandidateCourseResourceViewTest.php` — feature proof for selection and legacy fallback
