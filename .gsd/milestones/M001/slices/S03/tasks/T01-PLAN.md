---
estimated_steps: 6
estimated_files: 4
---

# T01: Move lesson asset persistence to a protected storage contract

**Slice:** S03 — Protected inline learning viewer
**Milestone:** M001

## Description

Switch course media and PDF persistence from public storage to a protected storage contract while keeping a compatibility path for legacy public assets.

## Steps

1. Decide and encode where new protected course files live on the local/private disk.
2. Update admin course create/update flows to store media and PDF files to protected storage.
3. Add helper logic that can resolve whether a course asset lives on local or legacy public storage.
4. Ensure file replacement deletes old assets from the correct disk.
5. Add feature tests using fake storage for create/update persistence behavior.
6. Run the protection-focused test suite and fix storage regressions.

## Must-Haves

- [ ] New lesson media and PDF files are persisted on protected storage.
- [ ] Legacy public lesson assets remain resolvable through the compatibility contract.

## Verification

- `php artisan test --filter=CourseProtectionTest`
- Confirm create/update tests assert the expected disk paths and file presence.

## Inputs

- `.gsd/milestones/M001/slices/S02/S02-SUMMARY.md` — bilingual course contract already in place
- `app/Http/Controllers/AdminCourseController.php` — current public-disk upload behavior
- `config/filesystems.php` — available local/private disk configuration

## Expected Output

- `app/Http/Controllers/AdminCourseController.php` — protected storage persistence path
- `app/Models/Course.php` or controller helper — disk resolution compatibility logic
- `tests/Feature/CourseProtectionTest.php` — protected storage verification
