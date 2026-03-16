# S04: Protected resource delivery and final integration — UAT

This is non-blocking. The agent has already completed automated verification and a live browser pass.

## What to check

1. Log in as an admin.
2. Create or open a course.
3. Add one note resource, one video resource, and one PDF resource in a clear manual order.
4. Log in as a candidate.
5. Open that course.
6. Confirm the support list shows all three resources in the chosen order.
7. Open the note and confirm it renders inline below the list.
8. Open the video and confirm it plays inline without a public file URL.
9. Open the PDF and confirm it renders inline below the list.
10. Log out and confirm direct file-resource URLs no longer load.
11. Open an older legacy course and confirm it still uses the same list/viewer page shape.

## If something feels wrong

Note whether the issue is:
- child file resources load only when publicly accessible
- note/video/PDF order is wrong
- selected support does not stay inside the same course page
- logout does not block direct file-resource URLs
- legacy courses stop rendering through the list/viewer flow
