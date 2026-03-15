# M001: Bilingual protected learning experience — Context

**Gathered:** 2026-03-15
**Status:** Ready for planning

## Project Description

This milestone upgrades the existing Laravel auto-école platform so students can study online instead of downloading lesson files. The experience must become bilingual in French and Arabic across the public site, the candidate area, and the admin area. Admins need to manage Arabic course text, candidates need to read PDFs and watch videos directly inside the course page, and the public landing page must remove the anti-piracy block marked by the user.

## Why This Milestone

The course feature already exists in rough form, but it does not yet deliver the requested product experience. Localization is missing, protected lesson delivery is weak because course assets are public, and the student viewer still feels like a basic attachment page rather than a controlled online classroom. This milestone turns the existing foundation into the first coherent bilingual learning experience.

## User-Visible Outcome

### When this milestone is complete, the user can:

- switch the public site, candidate area, and admin area between French and Arabic with proper RTL presentation in Arabic mode
- create Arabic course text in admin and see students receive Arabic course content or a clear “Arabic not available yet” state
- open a course and read the PDF or watch the video inline on one protected page without download links
- browse the public landing page without the removed anti-piracy marketing section

### Entry point / environment

- Entry point: `/`, `/courses/{course}`, `/admin/courses`, shared navigation language switcher
- Environment: browser
- Live dependencies involved: database, session/cookies, authenticated filesystem access

## Completion Class

- Contract complete means: translation resources, locale middleware, RTL shell wiring, bilingual course fields, protected asset endpoints, and deterrence UI behaviors exist and are wired with real code
- Integration complete means: an admin can save bilingual course content, a candidate can switch language and consume the same course through the protected inline viewer, and the public landing page remains coherent in both languages
- Operational complete means: locale choice persists during browsing, unauthorized access to protected lesson assets is denied, and the inline viewer still works under authenticated session flow

## Final Integrated Acceptance

To call this milestone complete, we must prove:

- a guest can switch the public site language and browse the cleaned landing page in French and Arabic
- an admin can create or update a course with Arabic text and a candidate can later view that course in Arabic mode on the protected course page
- an authenticated candidate can view inline PDF/video without public download links while right-click and common save/copy shortcuts are intercepted with visible deterrence messaging
- missing Arabic course content shows “Arabic not available yet” instead of silently falling back to French

## Risks and Unknowns

- Course lesson assets are currently stored on the public disk — protected viewing requires moving sensitive files behind authenticated/private delivery without breaking existing course records
- The app has no language files or locale middleware yet — global bilingual support touches layouts, shared navigation, auth views, admin views, candidate views, and validation strings
- Browser protection is inherently limited — the implementation must deter casual copying without pretending to provide guaranteed anti-inspect or anti-capture security

## Existing Codebase / Prior Art

- `routes/web.php` — existing public, candidate, and admin route structure, including course detail and PDF viewing endpoints
- `app/Http/Controllers/AdminCourseController.php` — current course CRUD and file upload path using public storage
- `app/Http/Controllers/CandidateCourseController.php` — current inline candidate course page and PDF file response
- `resources/views/candidate/courses/show.blade.php` — current one-page course detail layout that already matches the requested direction in rough form
- `resources/views/marketing/massar.blade.php` — public landing page containing the anti-piracy block to remove
- `app/Http/Controllers/Auth/AuthenticatedSessionController.php` — existing single-device enforcement for candidate logins

> See `.gsd/DECISIONS.md` for all architectural and pattern decisions — it is an append-only register; read it during planning, append to it during execution.

## Relevant Requirements

- R001 — adds bilingual interface coverage across all three app surfaces
- R002 — establishes RTL and locale persistence as part of the shared shell
- R003 — adds Arabic authoring capability for course text in admin
- R004 — upgrades the candidate course page into the requested single protected learning surface
- R005 — replaces public lesson asset exposure with authenticated/private delivery
- R006 — defines the visible missing-Arabic behavior for course content
- R007 — removes the anti-piracy marketing block from the public landing page
- R008 — requires real end-to-end proof across public, admin, and student flows

## Scope

### In Scope

- French/Arabic switching for public, candidate, and admin interfaces
- RTL handling for Arabic mode
- bilingual course text fields and admin editing workflow
- protected inline PDF/video viewing on the course page
- deterrence protections such as blocked right-click and common save/copy shortcuts with visible feedback
- landing-page cleanup for the red-circled anti-piracy block

### Out of Scope / Non-Goals

- guaranteed prevention of browser inspection, capture, or extraction
- automatic machine translation of course content
- separate Arabic-specific PDF/video uploads in this milestone
- a frontend SPA rewrite or a platform-wide architecture change away from Blade

## Technical Constraints

- Stay within the existing Laravel 12 + Blade + Tailwind + Alpine architecture
- Preserve current admin/candidate route separation and single-device candidate login behavior
- Protected lesson assets should not remain exposed through public storage URLs when viewer protection matters
- Protection language in the UI must stay honest about deterrence limits

## Integration Points

- session/cookies — locale persistence and existing candidate device binding
- filesystem storage — public covers versus protected lesson media/PDF assets
- course CRUD — admin course creation/update flow and persisted record shape
- Blade layouts and navigation — language switcher, translated chrome, and RTL direction control
- candidate classroom view — inline media/PDF rendering and deterrence interaction handling

## Open Questions

- Locale persistence is planned through middleware-backed session/cookie state now — if the product later needs long-term per-user language preference, that can supersede the current choice
- Separate Arabic-specific PDF/video assets are deferred unless the schools later require language-specific media, not just language-specific text
