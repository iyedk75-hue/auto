# S03: Protected inline learning viewer — UAT

This is non-blocking. The agent has already completed automated verification.

## What to check

1. Log in as a candidate.
2. Open a course with a video and/or PDF.
3. Confirm the content opens directly on the course page.
4. Confirm there is no visible download button or download link on the page.
5. Right-click inside the protected lesson page.
6. Confirm the page blocks the action and shows a protection message.
7. Try common shortcuts like save or copy while focused on the page.
8. Confirm the page blocks the action and shows feedback.
9. Log out and try opening a media/PDF asset route directly if you have the URL.
10. Confirm access is denied.
11. Log in as an admin and open a course edit form with existing assets.
12. Confirm the current media/PDF preview links still open correctly.

## If something feels wrong

Note the exact route and symptom:
- public `/storage/...` asset URL visible in HTML
- protected route opens for guest
- right-click/save/copy not blocked on the lesson page
- admin preview link broken after private-storage move
