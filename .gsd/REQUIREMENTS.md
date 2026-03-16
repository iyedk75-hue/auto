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

None.

## Validated

### R012 — Courses support multiple learning resources
- Class: core-capability
- Status: validated
- Description: A single course can contain multiple learning resources instead of only one video/image slot and one PDF slot.
- Why it matters: The requested classroom experience depends on an ordered list of many supports inside one course.
- Source: user
- Primary owning slice: M002/S01
- Supporting slices: M002/S02, M002/S03, M002/S04
- Validation: validated
- Notes: Proven through `course_resources`, transition compatibility, and the final assembled multi-resource flow.

### R013 — Admin can add and manage many resources per course
- Class: admin/support
- Status: validated
- Description: Admin users can create, edit, remove, and manage multiple resources for a course.
- Why it matters: Multi-resource courses are useless unless the school can author and maintain them.
- Source: user
- Primary owning slice: M002/S02
- Supporting slices: M002/S04
- Validation: validated
- Notes: Proven through nested admin resource CRUD, management pages, and end-to-end milestone coverage.

### R014 — Supported resource types include video, PDF, and admin note
- Class: core-capability
- Status: validated
- Description: Course resources can be videos, PDFs, or note entries written directly by the admin.
- Why it matters: The user explicitly wants files plus written notes within the same course support system.
- Source: user
- Primary owning slice: M002/S02
- Supporting slices: M002/S03, M002/S04
- Validation: validated
- Notes: Notes are first-class resources, not an afterthought or fallback field.

### R015 — Student sees a Classroom-style stacked support list inside the course
- Class: primary-user-loop
- Status: validated
- Description: The course page shows a stacked support list inspired by the provided Classroom screenshot.
- Why it matters: The requested student experience is list-first, not a single hero viewer with one file.
- Source: user
- Primary owning slice: M002/S03
- Supporting slices: M002/S04
- Validation: validated
- Notes: Proven by the candidate feed UI, browser verification, and feature coverage.

### R016 — Clicking a support opens it below the list on the same course page
- Class: primary-user-loop
- Status: validated
- Description: When a student selects a resource, it opens inside the same course page below the list rather than on a separate page.
- Why it matters: The user explicitly rejected separate pages and wants one continuous course-viewing surface.
- Source: user
- Primary owning slice: M002/S03
- Supporting slices: M002/S04
- Validation: validated
- Notes: This applies to video, PDF, and note resources.

### R017 — Resource order is controlled manually by admin
- Class: admin/support
- Status: validated
- Description: The admin decides the exact order in which resources appear to the student.
- Why it matters: The support list needs to behave like a chapter/lesson sequence, not an accidental upload order.
- Source: user
- Primary owning slice: M002/S02
- Supporting slices: M002/S03
- Validation: validated
- Notes: The first iteration uses explicit numeric ordering rather than drag-and-drop UI.

### R018 — Each support item shows type and date
- Class: quality-attribute
- Status: validated
- Description: Each resource item in the student list shows at least its type and date in addition to its title.
- Why it matters: The list needs enough context to feel like a real classroom feed rather than a plain filename list.
- Source: user
- Primary owning slice: M002/S03
- Supporting slices: none
- Validation: validated
- Notes: Date is currently sourced from resource creation time in the normalized resource contract.

### R019 — File-resource protection still applies per resource
- Class: compliance/security
- Status: validated
- Description: Videos and PDFs in the new multi-resource system still use protected storage, authenticated delivery, and the existing deterrence behavior.
- Why it matters: The multi-resource redesign must not regress the protection work already delivered in M001.
- Source: inferred
- Primary owning slice: M002/S04
- Supporting slices: M002/S01, M002/S03
- Validation: validated
- Notes: Proven through protected child-resource routes, regression tests, and milestone integration coverage.

### R020 — Existing single-resource courses remain usable during transition
- Class: continuity
- Status: validated
- Description: Existing courses created under the single-media/PDF model still work while the new resource model is introduced.
- Why it matters: The project already has a running course system; the transition cannot strand existing records.
- Source: inferred
- Primary owning slice: M002/S01
- Supporting slices: M002/S04
- Validation: validated
- Notes: Proven through legacy resource synthesis and live/browser legacy-course rendering.

### R021 — Final integrated multi-resource course flow is proven end-to-end
- Class: integration
- Status: validated
- Description: The milestone is complete only when admin multi-resource authoring and student same-page resource viewing are exercised together end-to-end.
- Why it matters: This milestone crosses data model, admin UI, student UI, and protected file delivery boundaries.
- Source: inferred
- Primary owning slice: M002/S04
- Supporting slices: M002/S01, M002/S02, M002/S03
- Validation: validated
- Notes: Proven through `MilestoneIntegrationTest` with note, video, and PDF resources plus browser verification of the candidate viewer.

### R001 — Bilingual interface across public, candidate, and admin areas
- Class: core-capability
- Status: validated
- Description: Users can switch the application interface between French and Arabic on the public site, candidate area, and admin area.
- Why it matters: The requested product experience is bilingual across the whole site, not only on one page.
- Source: user
- Primary owning slice: M001/S01
- Supporting slices: M001/S02, M001/S03, M001/S04, M001/S05
- Validation: validated
- Notes: Proven through locale switching, translated shell surfaces, localized admin/course views, and milestone integration coverage.

### R002 — Arabic mode renders with proper RTL behavior and persists during browsing
- Class: quality-attribute
- Status: validated
- Description: Arabic mode uses correct RTL layout treatment and the selected language persists while the user navigates through the app.
- Why it matters: Arabic support is incomplete if the text changes but the layout still behaves like French.
- Source: inferred
- Primary owning slice: M001/S01
- Supporting slices: M001/S05
- Validation: validated
- Notes: Proven through locale middleware, session/cookie persistence, `lang`/`dir` assertions, and Arabic feature coverage.

### R003 — Admin can manage Arabic course text alongside French
- Class: admin/support
- Status: validated
- Description: Admin users can create and edit Arabic course title, description, and lesson text alongside the existing French content.
- Why it matters: Student Arabic mode needs real managed content, not just translated buttons.
- Source: user
- Primary owning slice: M001/S02
- Supporting slices: M001/S05
- Validation: validated
- Notes: Proven by bilingual admin course forms and passing `AdminCourseTest` plus integration coverage.

### R004 — Student course page provides inline protected viewing on one page
- Class: primary-user-loop
- Status: validated
- Description: A student can open one course page and read the PDF or watch the video directly inside the page without exposed download links.
- Why it matters: This is the core learning flow the user explicitly requested.
- Source: user
- Primary owning slice: M001/S03
- Supporting slices: M001/S05
- Validation: validated
- Notes: Proven by protected media/PDF routes, route-based lesson HTML, and milestone integration coverage.

### R005 — Protected lesson assets use authenticated/private delivery
- Class: compliance/security
- Status: validated
- Description: Protected lesson PDFs and videos are served through authenticated/private access instead of public storage URLs when protection matters.
- Why it matters: Public URLs undermine the requested deterrence and make asset exposure trivial.
- Source: research
- Primary owning slice: M001/S03
- Supporting slices: M001/S05
- Validation: validated
- Notes: Proven by local-disk storage, legacy compatibility logic, guest denial, and authenticated inline route tests.

### R006 — Missing Arabic course content shows a clear unavailable state
- Class: failure-visibility
- Status: validated
- Description: In Arabic mode, if a course does not have Arabic text content, the student sees “Arabic not available yet” instead of a silent French fallback.
- Why it matters: The user explicitly rejected silent fallback and wants a visible content-gap state.
- Source: user
- Primary owning slice: M001/S02
- Supporting slices: M001/S03, M001/S05
- Validation: validated
- Notes: Proven by candidate lesson rendering and milestone integration coverage.

### R007 — Public landing page removes the anti-piracy marketing block
- Class: launchability
- Status: validated
- Description: The public landing page no longer shows the red-circled anti-piracy marketing section.
- Why it matters: The landing page should reflect the updated product message and the explicit cleanup request.
- Source: user
- Primary owning slice: M001/S04
- Supporting slices: M001/S05
- Validation: validated
- Notes: Proven by home-page regression assertions in French and Arabic.

### R008 — Final integrated flow is proven across public, admin, and student surfaces
- Class: integration
- Status: validated
- Description: The milestone is only considered complete when the bilingual public/admin/student experience and protected learning flow are exercised together end-to-end.
- Why it matters: The requested outcome spans multiple surfaces and runtime concerns, so slice-local proof is not enough.
- Source: inferred
- Primary owning slice: M001/S05
- Supporting slices: M001/S01, M001/S02, M001/S03, M001/S04
- Validation: validated
- Notes: Proven by `MilestoneIntegrationTest` plus the full milestone verification suite and production build.

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
- Notes: Deferred because the confirmed M001 scope only required Arabic UI plus Arabic course text.

### R022 — Per-resource comments/discussion
- Class: admin/support
- Status: deferred
- Description: Course resources may later support comments or discussion threads like Classroom.
- Why it matters: Classroom-style feeds often imply discussion, but it is not required for the current support-list milestone.
- Source: inferred
- Primary owning slice: none
- Supporting slices: none
- Validation: unmapped
- Notes: Deferred to keep M002 focused on resource structure, not communication features.

### R023 — Drag-and-drop ordering UI for resources
- Class: admin/support
- Status: deferred
- Description: Resource ordering may later use drag-and-drop instead of a simpler explicit order field.
- Why it matters: It would improve admin ergonomics, but it is not necessary to prove the ordered resource experience.
- Source: inferred
- Primary owning slice: none
- Supporting slices: none
- Validation: unmapped
- Notes: Manual ordering is enough for the first implementation.

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

### R024 — Separate page per resource
- Class: anti-feature
- Status: out-of-scope
- Description: Clicking a support will not navigate to a dedicated standalone resource page.
- Why it matters: This prevents the implementation from drifting away from the requested same-page course experience.
- Source: user
- Primary owning slice: none
- Supporting slices: none
- Validation: n/a
- Notes: Resources must open below the list inside the same course page.

### R025 — Reverting to a one-file-per-course model
- Class: anti-feature
- Status: out-of-scope
- Description: The new milestone will not keep the course model limited to one media slot and one PDF slot.
- Why it matters: It protects the intended direction against partial implementations that only reskin the existing single-file model.
- Source: user
- Primary owning slice: none
- Supporting slices: none
- Validation: n/a
- Notes: The target is a true multi-resource course model.

## Traceability

| ID | Class | Status | Primary owner | Supporting | Proof |
|---|---|---|---|---|---|
| R012 | core-capability | validated | M002/S01 | M002/S02, M002/S03, M002/S04 | validated |
| R013 | admin/support | validated | M002/S02 | M002/S04 | validated |
| R014 | core-capability | validated | M002/S02 | M002/S03, M002/S04 | validated |
| R015 | primary-user-loop | validated | M002/S03 | M002/S04 | validated |
| R016 | primary-user-loop | validated | M002/S03 | M002/S04 | validated |
| R017 | admin/support | validated | M002/S02 | M002/S03 | validated |
| R018 | quality-attribute | validated | M002/S03 | none | validated |
| R019 | compliance/security | validated | M002/S04 | M002/S01, M002/S03 | validated |
| R020 | continuity | validated | M002/S01 | M002/S04 | validated |
| R021 | integration | validated | M002/S04 | M002/S01, M002/S02, M002/S03 | validated |
| R001 | core-capability | validated | M001/S01 | M001/S02, M001/S03, M001/S04, M001/S05 | validated |
| R002 | quality-attribute | validated | M001/S01 | M001/S05 | validated |
| R003 | admin/support | validated | M001/S02 | M001/S05 | validated |
| R004 | primary-user-loop | validated | M001/S03 | M001/S05 | validated |
| R005 | compliance/security | validated | M001/S03 | M001/S05 | validated |
| R006 | failure-visibility | validated | M001/S02 | M001/S03, M001/S05 | validated |
| R007 | launchability | validated | M001/S04 | M001/S05 | validated |
| R008 | integration | validated | M001/S05 | M001/S01, M001/S02, M001/S03, M001/S04 | validated |
| R009 | admin/support | deferred | none | none | unmapped |
| R022 | admin/support | deferred | none | none | unmapped |
| R023 | admin/support | deferred | none | none | unmapped |
| R010 | constraint | out-of-scope | none | none | n/a |
| R011 | anti-feature | out-of-scope | none | none | n/a |
| R024 | anti-feature | out-of-scope | none | none | n/a |
| R025 | anti-feature | out-of-scope | none | none | n/a |

## Coverage Summary

- Active requirements: 0
- Mapped to slices: 0
- Validated: 18
- Unmapped active requirements: 0
