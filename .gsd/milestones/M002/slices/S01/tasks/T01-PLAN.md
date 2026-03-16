---
estimated_steps: 6
estimated_files: 4
---

# T01: Add the child resource schema and model contract

**Slice:** S01 — Resource model and transition layer
**Milestone:** M002

## Description

Create the new repeated course-resource model so one course can own many ordered supports of type video, PDF, or note.

## Steps

1. Add a migration creating `course_resources` with course relation, resource type, ordering, and content/file fields.
2. Create the `CourseResource` model with supported type constants and any basic casts/fillable definitions.
3. Add the `resources()` relation on `Course`.
4. Decide and encode the minimum content shape needed for note resources and localized titles.
5. Write focused tests proving ordered typed resources persist and relate to a course.
6. Run the focused resource-model suite and fix any schema/model issues.

## Must-Haves

- [ ] Courses can own many ordered child resources.
- [ ] The resource model supports `video`, `pdf`, and `note` as first-class types.

## Verification

- `php artisan test --filter=CourseResourceModelTest`
- Confirm the migration applies cleanly in the verification copy.

## Inputs

- `.gsd/milestones/M002/M002-ROADMAP.md` — S01 boundary-map contract
- `app/Models/Course.php` — current single-resource course model
- `.gsd/milestones/M001/M001-SUMMARY.md` — validated bilingual/protected course foundation

## Expected Output

- `database/migrations/*_create_course_resources_table.php` — new resource storage contract
- `app/Models/CourseResource.php` — typed child resource model
- `app/Models/Course.php` — course-to-resources relation
- `tests/Feature/CourseResourceModelTest.php` — resource model verification
