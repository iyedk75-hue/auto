---
id: T02
parent: S03
milestone: M002
provides:
  - Classroom-style stacked support list on the candidate course page
  - Same-page viewer below the list for note, video, PDF, and legacy-image states
  - French and Arabic UI labels for the new classroom feed
  - Visual selected-state treatment and support count summary
requires:
  - slice: S03
    provides: selected-resource contract from T01
affects: [S04]
key_files:
  - resources/views/candidate/courses/show.blade.php
  - resources/css/app.css
  - lang/fr/ui.php
  - lang/ar/ui.php
key_decisions:
  - "The classroom feed is a stacked list with explicit selected-state styling rather than a grid or hidden accordion."
patterns_established:
  - "Course context remains on the page, but the support list drives the primary viewer state."
drill_down_paths:
  - .gsd/milestones/M002/slices/S03/tasks/T02-PLAN.md
duration: 55m
verification_result: pass
completed_at: 2026-03-15T18:38:00Z
---

# T02: Build the classroom-style stacked list and same-page viewer

**The candidate course page now reads like a classroom feed: stacked support cards first, active support viewer below.**

## What Happened

Rebuilt `candidate/courses/show.blade.php` around a support stream and selected viewer instead of the older one-media/one-PDF layout. The new page keeps the course hero and context blocks, but the main interaction is now a stacked list of supports with icon, title, type, and date metadata.

The selected support renders below the list in the same page. Notes render inline, videos use the protected inline player, PDFs render in an embedded frame, and legacy image-like media stays viewable without breaking transition compatibility.

New classroom CSS primitives were added to support the feed layout, selected-state styling, and viewer presentation. French and Arabic labels were extended for the new stream/viewer copy.

## Deviations

The first version uses server-rendered selection through links back to the same route instead of a heavier client-side switcher. The page still remains one in-page viewing surface and keeps the state explicit.

## Files Created/Modified

- `resources/views/candidate/courses/show.blade.php` — classroom-style feed and viewer
- `resources/css/app.css` — feed, viewer, and selected-state styling
- `lang/fr/ui.php` / `lang/ar/ui.php` — classroom feed labels
