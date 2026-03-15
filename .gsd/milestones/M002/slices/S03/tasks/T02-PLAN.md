---
estimated_steps: 7
estimated_files: 4
---

# T02: Build the classroom-style stacked list and same-page viewer

**Slice:** S03 — Student classroom-style resource list
**Milestone:** M002

## Description

Replace the old single-media candidate layout with a stacked support feed and an in-page viewer below it, following the Classroom reference while staying within Blade, Tailwind, and Alpine.

## Steps

1. Rework `candidate/courses/show.blade.php` around a resource stream and viewer instead of isolated media/PDF blocks.
2. Add stacked support cards with icon, title, type, date, and selected-state styling.
3. Keep the viewer below the list and render note, video, PDF, and legacy-image states appropriately.
4. Use Alpine only for local in-page switching and URL state updates; keep server-rendered fallback behavior intact.
5. Add or adjust classroom CSS primitives for the stream cards, selected state, and viewer shell.
6. Add translated UI labels in French and Arabic for the new list/viewer copy.
7. Rebuild frontend assets and rerun the focused candidate view test.

## Must-Haves

- [ ] The resource list is visibly stacked and readable like a classroom feed, not a grid of generic cards.
- [ ] Clicking a support changes the viewer below the list without leaving the course page.

## Verification

- `php artisan test --filter=CandidateCourseResourceViewTest && npm run build`
- Manual inspection shows the list item type/date metadata and the viewer below the list.

## Inputs

- `app/Http/Controllers/CandidateCourseController.php` — selected-resource view model from T01
- `/home/youss/auto ecole/classroom liste of video or pdf .png` — reference for stacked classroom feed feel

## Expected Output

- `resources/views/candidate/courses/show.blade.php` — classroom-style stacked list and in-page viewer
- `resources/css/app.css` — supporting list/viewer styling
- `lang/fr/ui.php` — French list/viewer labels
- `lang/ar/ui.php` — Arabic list/viewer labels
