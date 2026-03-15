# Decisions Register

<!-- Append-only. Never edit or remove existing rows.
     To reverse a decision, add a new row that supersedes it.
     Read this file at the start of any planning or research phase. -->

| # | When | Scope | Decision | Choice | Rationale | Revisable? |
|---|------|-------|----------|--------|-----------|------------|
| D001 | M001 | arch | Locale handling | Use Laravel language files with middleware-backed session/cookie locale switching and shared layout `lang` / `dir` wiring | Fits the existing Blade app, keeps switching global across public/admin/candidate surfaces, and avoids a frontend rewrite | Yes — if later replaced with durable per-user preference storage |
| D002 | M001 | data | Bilingual course content storage | Add explicit French and Arabic text fields on `courses` for title, description, and lesson body | Matches the existing CRUD model and keeps admin editing simple without introducing a translation package or extra tables | Yes — if languages or content types expand later |
| D003 | M001 | arch | Protected lesson asset delivery | Move protected course PDFs/videos behind private storage and authenticated Laravel responses | Removes public asset URLs for sensitive lesson files and allows authorization checks before inline viewing/streaming | No |
| D004 | M001 | scope | Browser protection promise | Implement deterrence protections only and do not claim guaranteed anti-inspect, anti-capture, or anti-download security | Browsers cannot truthfully guarantee DRM-level protection; honest scope avoids false promises while still improving casual-copy deterrence | No |
| D005 | M001/S03 | arch | Protected lesson storage | Store lesson media and PDFs on the local/private disk while keeping cover images public | Sensitive lesson assets need authenticated delivery; cover images do not | No |
| D006 | M001/S03 | pattern | Legacy asset compatibility | Resolve course asset disk dynamically so old public paths still work while new uploads use private storage | Avoids breaking existing course records during the storage cutover | Yes — if a later migration fully rewrites legacy asset paths |
| D007 | M001/S03 | auth | Asset preview access | Allow both candidates and admins to access authenticated lesson asset routes, but keep inactive-course restriction for non-admins | Admin course management still needs file preview after private storage is introduced | Yes — if admin previews move to dedicated admin-only routes later |
| D008 | M002 | data | Multi-resource course model | Introduce a child resource model per course instead of expanding `courses` with more single-resource columns | The new requirement is many supports per course, so the model must represent repeated ordered items cleanly | No |
| D009 | M002 | product | Resource viewer location | Open selected resources below the list inside the same course page | Matches the requested Classroom-like same-page flow and avoids navigation churn | No |
| D010 | M002 | product | Supported resource types | First-class resource types are `video`, `pdf`, and `note` | These are the exact support types the user requested | Yes — if later milestones add quizzes, links, or downloads |
| D011 | M002 | pattern | Resource ordering | Use explicit admin-controlled manual ordering in the first version | Satisfies the requirement without forcing drag-and-drop complexity into the first implementation | Yes — if later replaced with drag-and-drop ordering |
| D012 | M002/S01 | data | Resource localization | Store resource titles and note bodies with French primary fields and optional Arabic companion fields | Keeps the new child-resource model consistent with the bilingual course model already established in M001 | Yes — if later generalized into a broader translation system |
| D013 | M002/S03 | pattern | Candidate resource selection state | Keep resource selection on the existing `courses.show` route via a `?resource=` query parameter and same-page viewer anchor | Makes selection shareable, server-rendered, testable, and resilient even without client-side state | Yes — if later replaced with richer Alpine-only state management |
