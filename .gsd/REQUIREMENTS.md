# Requirements

This file is the explicit capability and coverage contract for the project.

Use it to track what is actively in scope, what has been validated by completed work, what is intentionally deferred, and what is explicitly out of scope.

Guidelines:
- Keep requirements capability-oriented, not a giant feature wishlist.
- Requirements should be atomic, testable, and stated in plain language.
- Every **Active** requirement should be mapped to a slice, deferred, blocked with reason, or moved out of scope.
- Each requirement should have one accountable primary owner and may have supporting slices.
- Research may suggest requirements, but research does not silently make them binding.
- Validation means the requirement was actually proven by completed work and verification, not just discussed.

## Active

### R001 — Bilingual interface across public, candidate, and admin areas
- Class: core-capability
- Status: active
- Description: Users can switch the application interface between French and Arabic on the public site, candidate area, and admin area.
- Why it matters: The requested product experience is bilingual across the whole site, not only on one page.
- Source: user
- Primary owning slice: M001/S01
- Supporting slices: M001/S02, M001/S03, M001/S04, M001/S05
- Validation: mapped
- Notes: The switch must affect shared layout chrome, navigation, and page-level labels.

### R002 — Arabic mode renders with proper RTL behavior and persists during browsing
- Class: quality-attribute
- Status: active
- Description: Arabic mode uses correct RTL layout treatment and the selected language persists while the user navigates through the app.
- Why it matters: Arabic support is incomplete if the text changes but the layout still behaves like French.
- Source: inferred
- Primary owning slice: M001/S01
- Supporting slices: none
- Validation: mapped
- Notes: Persistence is required across page navigation; later per-user persistence can be revisited if needed.

### R003 — Admin can manage Arabic course text alongside French
- Class: admin/support
- Status: active
- Description: Admin users can create and edit Arabic course title, description, and lesson text alongside the existing French content.
- Why it matters: Student Arabic mode needs real managed content, not just translated buttons.
- Source: user
- Primary owning slice: M001/S02
- Supporting slices: M001/S05
- Validation: mapped
- Notes: This milestone covers text content fields, not separate Arabic video/PDF files.

### R004 — Student course page provides inline protected viewing on one page
- Class: primary-user-loop
- Status: active
- Description: A student can open one course page and read the PDF or watch the video directly inside the page without exposed download links.
- Why it matters: This is the core learning flow the user explicitly requested.
- Source: user
- Primary owning slice: M001/S03
- Supporting slices: M001/S05
- Validation: mapped
- Notes: The viewer should keep PDF/video embedded in the course detail experience instead of sending the student elsewhere.

### R005 — Protected lesson assets use authenticated/private delivery
- Class: compliance/security
- Status: active
- Description: Protected lesson PDFs and videos are served through authenticated/private access instead of public storage URLs when protection matters.
- Why it matters: Public URLs undermine the requested deterrence and make asset exposure trivial.
- Source: research
- Primary owning slice: M001/S03
- Supporting slices: none
- Validation: mapped
- Notes: This is still browser-viewable content; the goal is controlled access and deterrence, not impossible DRM.

### R006 — Missing Arabic course content shows a clear unavailable state
- Class: failure-visibility
- Status: active
- Description: In Arabic mode, if a course does not have Arabic text content, the student sees “Arabic not available yet” instead of a silent French fallback.
- Why it matters: The user explicitly rejected silent fallback and wants a visible content-gap state.
- Source: user
- Primary owning slice: M001/S02
- Supporting slices: M001/S03, M001/S05
- Validation: mapped
- Notes: The unavailable state applies to course text content selection rules in Arabic mode.

### R007 — Public landing page removes the anti-piracy marketing block
- Class: launchability
- Status: active
- Description: The public landing page no longer shows the red-circled anti-piracy marketing section.
- Why it matters: The landing page should reflect the updated product message and the explicit cleanup request.
- Source: user
- Primary owning slice: M001/S04
- Supporting slices: M001/S05
- Validation: mapped
- Notes: Removal must not break spacing, bilingual rendering, or navigation flow.

### R008 — Final integrated flow is proven across public, admin, and student surfaces
- Class: integration
- Status: active
- Description: The milestone is only considered complete when the bilingual public/admin/student experience and protected learning flow are exercised together end-to-end.
- Why it matters: The requested outcome spans multiple surfaces and runtime concerns, so slice-local proof is not enough.
- Source: inferred
- Primary owning slice: M001/S05
- Supporting slices: M001/S01, M001/S02, M001/S03, M001/S04
- Validation: mapped
- Notes: This includes proving protected access behavior and the missing-Arabic state in the assembled app.

## Validated

None yet. This file is being created at bootstrap time before any slice has been executed and verified.

## Deferred

### R009 — Courses may later support separate Arabic PDF/video files
- Class: admin/support
- Status: deferred
- Description: The system may later allow an auto-école to upload Arabic-specific lesson media in addition to French assets.
- Why it matters: Some schools may eventually want language-specific media, not only translated text.
- Source: inferred
- Primary owning slice: none
- Supporting slices: none
- Validation: unmapped
- Notes: Deferred because the current confirmed scope only requires Arabic UI plus Arabic course text.

## Out of Scope

### R010 — Guaranteed prevention of Inspect Element, screen capture, or extraction
- Class: constraint
- Status: out-of-scope
- Description: The app will not claim guaranteed prevention of browser inspection, screen recording, or file extraction.
- Why it matters: This prevents false promises about what browsers can technically enforce.
- Source: user
- Primary owning slice: none
- Supporting slices: none
- Validation: n/a
- Notes: The accepted scope is strong deterrence, not impossible DRM.

### R011 — Automatic machine translation of course content
- Class: anti-feature
- Status: out-of-scope
- Description: The milestone will not auto-translate French course content into Arabic.
- Why it matters: It keeps the scope on managed bilingual content instead of introducing translation quality and workflow issues.
- Source: inferred
- Primary owning slice: none
- Supporting slices: none
- Validation: n/a
- Notes: Arabic content is expected to be provided and managed explicitly by the admin.

## Traceability

| ID | Class | Status | Primary owner | Supporting | Proof |
|---|---|---|---|---|---|
| R001 | core-capability | active | M001/S01 | M001/S02, M001/S03, M001/S04, M001/S05 | mapped |
| R002 | quality-attribute | active | M001/S01 | none | mapped |
| R003 | admin/support | active | M001/S02 | M001/S05 | mapped |
| R004 | primary-user-loop | active | M001/S03 | M001/S05 | mapped |
| R005 | compliance/security | active | M001/S03 | none | mapped |
| R006 | failure-visibility | active | M001/S02 | M001/S03, M001/S05 | mapped |
| R007 | launchability | active | M001/S04 | M001/S05 | mapped |
| R008 | integration | active | M001/S05 | M001/S01, M001/S02, M001/S03, M001/S04 | mapped |
| R009 | admin/support | deferred | none | none | unmapped |
| R010 | constraint | out-of-scope | none | none | n/a |
| R011 | anti-feature | out-of-scope | none | none | n/a |

## Coverage Summary

- Active requirements: 8
- Mapped to slices: 8
- Validated: 0
- Unmapped active requirements: 0
