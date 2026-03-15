# S02: Bilingual course content management — UAT

This is non-blocking. The agent has already completed automated verification.

## What to check

1. Log in as an admin.
2. Open the course creation page.
3. Confirm you can enter French and Arabic title, description, and lesson text separately.
4. Save a course with both French and Arabic text.
5. Edit the same course and confirm both language fields reload correctly.
6. Log in as a candidate.
7. Open that course in French mode and confirm the French lesson text appears.
8. Switch to Arabic and confirm the Arabic lesson text appears.
9. Create or edit another course with only French text and no Arabic lesson text.
10. Open that course in Arabic mode.
11. Confirm the page shows the explicit "Arabic not available yet" message instead of silently showing the French lesson body.

## If something feels wrong

Note the exact page and language, plus whether the issue is:
- missing Arabic field in admin
- saved Arabic text not reloading
- Arabic mode showing French lesson text without warning
- untranslated label on the course admin or candidate lesson page
