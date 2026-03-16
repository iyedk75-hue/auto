# S01: Language foundation and RTL shell — UAT

This is non-blocking. The agent has already completed automated verification.

## What to check

1. Open the public home page.
2. Switch from French to Arabic.
3. Confirm the page direction becomes RTL and the main hero/navigation copy is Arabic.
4. Open the candidate login page and admin login page.
5. Confirm both pages switch language correctly and Arabic labels are readable.
6. Log in as a candidate and open the dashboard.
7. Confirm the dashboard header, summary cards, and next-step blocks are localized.
8. Log in as an admin and open the admin dashboard.
9. Confirm the dashboard cards, section buttons, and quick action are localized.
10. Open the course list and one course detail page as a candidate.
11. Confirm the classroom shell labels and metadata switch between French and Arabic cleanly.

## If something feels wrong

Note the exact page, language, and visible issue:
- untranslated label
- broken RTL alignment
- text overlapping or clipped
- switcher not persisting after navigation
