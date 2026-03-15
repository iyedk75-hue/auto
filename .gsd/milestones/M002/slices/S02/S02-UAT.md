# S02: Admin multi-resource management — UAT

This is non-blocking. The agent has already completed automated verification.

## What to check

1. Log in as an admin.
2. Open the courses list.
3. Confirm each course now has a “manage resources” entry point.
4. Open a legacy course and confirm it is visibly marked as inherited/legacy support state.
5. Open a course using the new resource flow.
6. Add a note resource with French and Arabic text.
7. Add a video resource.
8. Add a PDF resource.
9. Change the manual order values.
10. Edit one resource and confirm the updated values reload correctly.
11. Delete one file resource and confirm it disappears from the list.

## If something feels wrong

Note whether the issue is:
- cannot reach the resource manager from the course screens
- note/video/PDF types are not saving correctly
- order changes are ignored
- deleted file resources remain visible or broken
- legacy courses are not clearly distinguishable from new multi-resource courses
