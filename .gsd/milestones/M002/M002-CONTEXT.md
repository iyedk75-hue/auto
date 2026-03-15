# M002: Multi-resource classroom course flow — Context

**Gathered:** 2026-03-15
**Status:** Ready for planning

## Project Description

This milestone transforms the current course system from a single-file lesson model into a multi-resource classroom flow. Each course should contain an ordered list of supports such as videos, PDFs, and admin-written notes. Students should see a Classroom-style stacked list inside the course page and open any selected resource below that list without leaving the page.

## Why This Milestone

M001 proved bilingual protected lesson delivery, but the course model is still too narrow: each course only knows one `media_path` and one `pdf_path`. The user now wants real course chapters/supports, where a single course can contain many materials and note entries. This is a structural change in the learning model, not a cosmetic UI tweak.

## User-Visible Outcome

### When this milestone is complete, the user can:

- create a course with many ordered supports instead of only one media file and one PDF
- add supports of type video, PDF, and written note from the admin side
- open a course as a student and see a Classroom-style stacked support list with type and date metadata
- click any support and view it below the list on the same course page
- keep protected viewing behavior for file resources within the new multi-resource structure

### Entry point / environment

- Entry point: `/admin/courses/*`, `/courses/{course}`
- Environment: browser
- Live dependencies involved: database, session/cookies, authenticated filesystem access

## Completion Class

- Contract complete means: a resource model exists for many course supports, admin CRUD persists it, student rendering consumes it, and compatibility with existing single-resource courses is defined in real code
- Integration complete means: an admin can create a course with multiple supports and a candidate can open that course and consume different support types inside the same page
- Operational complete means: protected file resources still require authenticated access and existing course records continue to function during the transition

## Final Integrated Acceptance

To call this milestone complete, we must prove:

- an admin can create a course with at least one note, one video, and one PDF resource in a chosen order
- a candidate can open that course, see the support list in Classroom-style stacked form, and switch between resources inside the same course page
- file resources still use protected delivery and deterrence while note resources render inline correctly
- an older course created under the single-resource model still remains usable during the transition

## Risks and Unknowns

- The current `courses` table is still single-resource oriented — introducing a child resource model must not break existing records or the already-validated protection flow
- Same-page resource switching changes the course-detail page from a static document viewer into a stateful multi-resource view
- Admin multi-resource management can sprawl quickly if the first version tries to do too much UI sophistication at once

## Existing Codebase / Prior Art

- `app/Models/Course.php` — current single-resource course contract with bilingual text and protected asset helpers
- `app/Http/Controllers/AdminCourseController.php` — current course CRUD still built around one media file and one PDF
- `app/Http/Controllers/CandidateCourseController.php` — current student viewer assumes one active media resource and one PDF per course
- `resources/views/admin/courses/partials/form.blade.php` — current admin course form with single media/PDF upload inputs
- `resources/views/candidate/courses/show.blade.php` — current same-page course viewer that will evolve into list + inline resource display
- `tests/Feature/CourseProtectionTest.php` — existing protection proof that must remain true for file resources after the model changes

> See `.gsd/DECISIONS.md` for all architectural and pattern decisions — it is an append-only register; read it during planning, append to it during execution.

## Relevant Requirements

- R012 — introduces true multi-resource courses
- R013 — adds admin management for many supports per course
- R014 — establishes the support type set: video, PDF, note
- R015 — adds the Classroom-style stacked support list
- R016 — keeps all support viewing inside the same course page
- R017 — gives admin explicit control over support order
- R018 — adds type and date metadata to each support item
- R019 — preserves protected file-resource behavior inside the new model
- R020 — requires transition compatibility for existing courses
- R021 — requires milestone-level integration proof for the new assembled flow

## Scope

### In Scope

- many resources per course
- resource types: video, PDF, note
- admin-side creation, editing, ordering, and removal of resources
- student support list UI inside the course page
- same-page resource viewer below the list
- type/date metadata in the resource list
- preserved protected delivery for file resources
- compatibility path for existing single-resource courses during the transition

### Out of Scope / Non-Goals

- separate page per resource
- per-resource comments/discussion
- drag-and-drop ordering as a required first version
- replacing the existing protection model with stronger-than-browser DRM

## Technical Constraints

- Stay within the existing Laravel 12 + Blade + Tailwind + Alpine architecture
- Preserve M001’s validated protected-delivery behavior for file resources
- Keep the course page as one in-page experience instead of introducing a new navigation layer per resource
- Avoid breaking existing course records during the transition from the single-resource model

## Integration Points

- database schema — likely new child-resource records related to `courses`
- admin course workflow — create/edit/manage many supports per course
- candidate course detail view — list plus same-page viewer behavior
- protected asset routes — per-resource file delivery for videos and PDFs
- translation layer — list labels and metadata in French/Arabic

## Open Questions

- The first version likely uses explicit numeric ordering instead of drag-and-drop — revisit only if the simpler workflow proves inadequate
- Existing single-resource courses may be migrated into child resources or adapted through compatibility logic first; the implementation should choose the safer path during execution
