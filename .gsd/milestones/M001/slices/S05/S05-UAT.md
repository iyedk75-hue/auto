# S05: End-to-end integration and polish — UAT

This is non-blocking. The agent has already completed automated verification.

## What to check

1. Open the public home page in French and Arabic.
2. Confirm the removed anti-piracy block is still gone.
3. Log in as an admin.
4. Create or edit a course with French + Arabic lesson text and protected media/PDF files.
5. Log in as a candidate.
6. Open the same course in Arabic mode.
7. Confirm the Arabic lesson text appears and the media/PDF open inline.
8. Confirm right-click and common save/copy shortcuts are blocked on the lesson page.
9. Open a course that has only French lesson text and switch to Arabic.
10. Confirm the explicit unavailable-state message appears.
11. Log out and confirm protected lesson asset routes are no longer accessible.

## If something feels wrong

Note the exact route and issue:
- wrong language or RTL behavior
- Arabic course text not appearing
- French lesson text leaking into Arabic mode without warning
- protected asset route reachable by guest
- right-click/save/copy deterrence not firing
