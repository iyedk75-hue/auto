# S01: Resource model and transition layer

**Goal:** Introduce a child-resource model for courses and a compatibility layer so existing single-resource courses still resolve cleanly during the transition.
**Demo:** The backend can represent many ordered course resources, and a legacy course with only `media_path` / `pdf_path` still exposes a normalized resource list without breaking.

## Must-Haves

- A `course_resources` storage contract exists with one row per support item.
- The resource model supports the first-class types `video`, `pdf`, and `note`.
- Resource ordering is explicit and deterministic.
- Existing courses that still rely on `media_path` / `pdf_path` expose a compatibility resource list during the transition.
- Slice outputs match the S01 → S02 and S01 → S03 boundary contracts.

## Proof Level

- This slice proves: contract
- Real runtime required: yes
- Human/UAT required: no

## Verification

- `php artisan test --filter=CourseResourceModelTest`
- `php artisan test --filter=CourseTransitionTest`
- `php artisan test --filter=MilestoneIntegrationTest`

## Observability / Diagnostics

- Runtime signals: resolved course resource collections expose explicit type, order, and origin information for both child resources and legacy fallbacks
- Inspection surfaces: model-level feature tests, seeded course records, normalized resource arrays returned by helpers
- Failure visibility: transition bugs appear as missing resource items, wrong sort order, or unresolved legacy file entries in tests
- Redaction constraints: no secret file paths beyond the app’s normal protected-resource abstraction

## Integration Closure

- Upstream surfaces consumed: M001 bilingual course model, protected asset helpers, and same-page viewer foundation
- New wiring introduced in this slice: `CourseResource` model/table, course-to-resources relation, normalized resource resolution contract, legacy fallback resource generation
- What remains before the milestone is truly usable end-to-end: admin resource management UI, student stacked resource list UI, and per-resource protected rendering

## Tasks

- [x] **T01: Add the child resource schema and model contract** `est:1h`
  - Why: Later admin/student work needs a real repeated resource model instead of more one-off fields on `courses`.
  - Files: `database/migrations/*_create_course_resources_table.php`, `app/Models/CourseResource.php`, `app/Models/Course.php`, `tests/Feature/CourseResourceModelTest.php`
  - Do: Create the `course_resources` table and model, define supported types and ordering fields, add the course relation, and write tests proving typed ordered resources persist correctly.
  - Verify: `php artisan test --filter=CourseResourceModelTest`
  - Done when: Courses can own many ordered resources of type video/pdf/note and tests prove the contract.
- [ ] **T02: Add normalized resource resolution with legacy compatibility** `est:1h`
  - Why: Existing courses still use single `media_path` / `pdf_path` fields and must remain usable while the new model rolls out.
  - Files: `app/Models/Course.php`, `app/Models/CourseResource.php`, `tests/Feature/CourseTransitionTest.php`, `database/seeders/DatabaseSeeder.php`
  - Do: Add helpers that resolve a course’s effective resource list from child resources first and legacy file fields second, include stable ordering/origin metadata, and update seeds or fixtures as needed for transition coverage.
  - Verify: `php artisan test --filter=CourseTransitionTest`
  - Done when: A legacy course with no child resources still resolves into a usable normalized resource list, and a new multi-resource course resolves from child records only.
- [ ] **T03: Expose a downstream-ready resource shape for later slices** `est:45m`
  - Why: Admin and student UIs in S02/S03 should consume one stable resource shape instead of rebuilding mapping logic.
  - Files: `app/Models/CourseResource.php`, `app/Models/Course.php`, `tests/Feature/CourseTransitionTest.php`
  - Do: Add locale-aware title/body helpers and normalized data output that downstream admin/student views can use, including support for note resources and file-resource metadata.
  - Verify: `php artisan test --filter=CourseTransitionTest && php artisan test --filter=MilestoneIntegrationTest`
  - Done when: Downstream slices can consume a consistent ordered resource contract for both note and file resources.

## Files Likely Touched

- `database/migrations/*_create_course_resources_table.php`
- `app/Models/Course.php`
- `app/Models/CourseResource.php`
- `database/seeders/DatabaseSeeder.php`
- `tests/Feature/CourseResourceModelTest.php`
- `tests/Feature/CourseTransitionTest.php`
