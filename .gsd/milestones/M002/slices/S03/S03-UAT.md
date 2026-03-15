# S03: Student classroom-style resource list — UAT

This is non-blocking. The agent has already completed automated verification and a live browser pass.

## What to check

1. Log in as a candidate.
2. Open a course.
3. Confirm the course page now shows a stacked support list instead of isolated media/PDF sections.
4. Confirm each support item shows a type and date line.
5. Click another support in the list.
6. Confirm the same course page reloads to the selected support and the viewer remains below the list.
7. For note resources, confirm the note body renders inline.
8. For video/PDF resources, confirm the content opens inline through the protected viewer area.
9. Switch the interface to Arabic and confirm the list labels still read correctly in RTL.

## If something feels wrong

Note whether the issue is:
- the selected support is not clearly marked
- the viewer does not change below the list
- type/date metadata is missing
- Arabic/RTL layout breaks in the list
- file resources expose public storage URLs instead of protected routes
