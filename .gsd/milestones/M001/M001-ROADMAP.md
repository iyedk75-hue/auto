# M001: Bilingual protected learning experience

**Vision:** Students and staff use the same Laravel platform in French or Arabic, admins can manage Arabic course text, and candidates can read PDFs and watch lesson videos online through a protected inline viewer without exposed download links.

## Success Criteria

- Public, admin, and candidate pages can be switched between French and Arabic, and Arabic pages render with correct RTL direction.
- Admin can create and update Arabic course title, description, and lesson text, and candidates see Arabic course content or a clear “Arabic not available yet” state.
- Candidates can open a course and read the PDF or watch the video inline on one page through authenticated/private delivery with no download CTA and deterrence protections active.
- The public landing page no longer shows the removed anti-piracy marketing block.

## Key Risks / Unknowns

- Global localization touches every shared shell and text surface — a partial pass will leave the app in a mixed-language state.
- Protected lesson assets currently live on public storage — moving them behind authenticated/private delivery changes both upload and view paths.
- Browser protection can deter casual copying but cannot guarantee true DRM — the product behavior and messaging must stay aligned with that limit.

## Proof Strategy

- Global localization and RTL shell risk → retire in S01 by proving a user can switch between French and Arabic across public, admin, and candidate shells and keep the choice while navigating.
- Bilingual course data risk → retire in S02 by proving admin can save Arabic course text and the candidate course page shows Arabic content or the missing-Arabic state.
- Protected viewer and private asset risk → retire in S03 by proving candidate PDF/video are delivered through authenticated routes on one page with deterrence protections and no download links.
- Public landing cleanup risk → retire in S04 by proving the marked section is removed without breaking layout or bilingual rendering.
- Cross-surface assembly risk → retire in S05 by rechecking the real guest/admin/candidate scenarios end-to-end in the assembled app.

## Verification Classes

- Contract verification: route wiring, migrations, translation resources, layout direction handling, protected storage paths, and substantive Blade/JS/CSS implementations
- Integration verification: guest language switching, admin bilingual course editing, candidate bilingual protected course viewing, and unauthorized protected-asset denial
- Operational verification: locale persistence during browsing, authenticated protected asset access, and deterrence handling that does not break normal playback/viewing
- UAT / human verification: Arabic readability/RTL polish and the perceived feel of the protected learning page

## Milestone Definition of Done

This milestone is complete only when all are true:

- all five slices are complete and their deliverables are present with real implementations
- shared bilingual and RTL shell behavior is actually wired across public, admin, and candidate surfaces
- the admin course workflow and candidate course viewer use the same bilingual course data contract
- protected lesson assets are served through authenticated/private access instead of exposed public URLs where protection matters
- success criteria are re-checked against live behavior and the final integrated acceptance scenarios pass

## Requirement Coverage

- Covers: R001, R002, R003, R004, R005, R006, R007, R008
- Partially covers: none
- Leaves for later: R009
- Orphan risks: none

## Slices

- [ ] **S01: Language foundation and RTL shell** `risk:high` `depends:[]`
  > After this: a user can switch French/Arabic across public, admin, and candidate chrome, and Arabic layouts render in RTL consistently.

- [ ] **S02: Bilingual course content management** `risk:medium` `depends:[S01]`
  > After this: admin can manage Arabic course text and the course page shows Arabic content or “Arabic not available yet”.

- [ ] **S03: Protected inline learning viewer** `risk:high` `depends:[S01,S02]`
  > After this: a candidate opens one course page and reads PDF / watches video inline through authenticated routes with deterrence protections and no download links.

- [ ] **S04: Public landing cleanup** `risk:low` `depends:[S01]`
  > After this: the red-circled anti-piracy marketing block is gone and the landing page still reads cleanly in both languages.

- [ ] **S05: End-to-end integration and polish** `risk:medium` `depends:[S02,S03,S04]`
  > After this: the bilingual public/admin/student flows work together, and the milestone’s real end-to-end scenarios are rechecked.

## Boundary Map

### S01 → S02

Produces:
- locale middleware and switch action that set the active language from session/cookie state on every web request
- translated guest/app layout shells with `<html lang>` and `dir` handling for French versus Arabic
- shared language-switch UI pattern reusable in public, admin, and candidate navigation

Consumes:
- nothing (first slice)

### S01 → S03

Produces:
- locale-aware course page shell and translated viewer chrome labels
- RTL-safe CSS utilities and shared warning/notice presentation patterns for protected content

Consumes:
- nothing (first slice)

### S01 → S04

Produces:
- bilingual public-site shell, navigation labels, and landing-page translation surface

Consumes:
- nothing (first slice)

### S02 → S03

Produces:
- extended `courses` record shape with French and Arabic title/description/content fields
- course content selection rules: Arabic mode uses Arabic fields when present, otherwise shows “Arabic not available yet”
- admin course form contract that persists bilingual text content without breaking existing course CRUD

Consumes from S01:
- locale middleware and shared switch state
- translated/RTL-aware layout shell

### S03 → S05

Produces:
- authenticated course asset endpoints for inline PDF/video delivery from non-public storage
- protected viewer JS/CSS behavior that blocks right-click and common save/copy shortcuts and shows visible deterrence feedback
- candidate course page contract: one page contains translated text, inline PDF/video, and protected viewer chrome

Consumes from S01:
- translated viewer shell and RTL utilities

Consumes from S02:
- locale-aware bilingual course text contract and missing-Arabic state

### S04 → S05

Produces:
- cleaned public landing layout without the anti-piracy marketing block in either language

Consumes from S01:
- public bilingual shell and navigation
