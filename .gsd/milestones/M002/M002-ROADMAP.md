# M002: Multi-resource classroom course flow

**Vision:** Courses evolve from one protected media/PDF pair into ordered classroom-style support lists containing videos, PDFs, and admin-written notes, all consumable inside the same course page.

## Success Criteria

- A single course can contain multiple ordered supports instead of only one media file and one PDF.
- Admin can create and manage video, PDF, and note resources within a course.
- Students see a Classroom-style stacked support list with type and date metadata.
- Clicking any support opens it below the list inside the same course page.
- File resources keep the existing protected delivery and deterrence behavior.
- Existing single-resource courses remain usable during the transition.

## Key Risks / Unknowns

- The current course data model is single-resource oriented, so the transition path can easily break existing course records or the already-validated file-protection work.
- Same-page resource switching introduces more page state and rendering complexity than the current one-resource viewer.
- Admin support management can become overcomplicated if the first version overreaches on UI sophistication.

## Proof Strategy

- Data-model transition risk → retire in S01 by proving multi-resource storage exists and existing single-resource courses still resolve through a compatibility path.
- Admin authoring risk → retire in S02 by proving admins can create, edit, order, and remove mixed resource types inside a course.
- Student classroom-list risk → retire in S03 by proving students can see the stacked list and switch resources in-page below it.
- Protection/integration risk → retire in S04 by proving videos and PDFs still use protected delivery and the full admin-to-student multi-resource flow works end-to-end.

## Verification Classes

- Contract verification: migrations, models, relations, resource ordering fields, translated UI labels, and route wiring
- Integration verification: admin multi-resource authoring, student in-page resource switching, protected file-resource access, and compatibility for existing courses
- Operational verification: protected file resources still require authenticated access and note resources render inline without breaking the same-page flow
- UAT / human verification: list feel and readability compared to the Classroom-style reference

## Milestone Definition of Done

This milestone is complete only when all are true:

- all four slices are complete and their deliverables are present with real implementations
- the course system supports many resources per course instead of only one file pair
- admin resource management and student in-page viewing use the same resource contract
- file resources still use protected delivery and deterrence after the model change
- success criteria are re-checked against assembled behavior and the final integrated acceptance scenarios pass

## Requirement Coverage

- Covers: R012, R013, R014, R015, R016, R017, R018, R019, R020, R021
- Partially covers: none
- Leaves for later: R022, R023
- Orphan risks: none

## Slices

- [x] **S01: Resource model and transition layer** `risk:high` `depends:[]`
  > After this: courses can represent many resources, and older single-resource courses still remain usable during the transition.

- [x] **S02: Admin multi-resource management** `risk:medium` `depends:[S01]`
  > After this: admin can add, edit, order, and remove many video, PDF, and note resources inside one course.

- [x] **S03: Student classroom-style resource list** `risk:medium` `depends:[S01,S02]`
  > After this: a student opens a course, sees a stacked support list with type/date, and opens any selected resource below the list on the same page.

- [x] **S04: Protected resource delivery and final integration** `risk:high` `depends:[S02,S03]`
  > After this: file resources remain protected, notes render inline, and the full admin-to-student multi-resource course flow works end-to-end.

## Boundary Map

### S01 → S02

Produces:
- `course_resources` storage contract with one row per course support
- relation/invariant that a course may own many ordered resources of type `video`, `pdf`, or `note`
- compatibility path so existing `courses.media_path` / `courses.pdf_path` data still resolves during transition

Consumes:
- M001 bilingual course shell and protected asset patterns

### S01 → S03

Produces:
- normalized resource shape for student rendering: id, title, type, order, date, protected file path or note body
- stable ordering contract used by both admin and candidate views

Consumes:
- M001 bilingual shell and same-page course viewer foundation

### S02 → S03

Produces:
- admin-authored resource records with explicit order and type metadata
- note-resource content contract for inline same-page rendering

Consumes from S01:
- multi-resource schema and compatibility layer

### S03 → S04

Produces:
- Classroom-style stacked list UI in the course page
- same-page resource-selection behavior that opens the selected support below the list
- translated type/date list metadata in French and Arabic

Consumes from S01:
- normalized resource contract and ordering rules

Consumes from S02:
- admin-authored multi-resource records

### S04 → Milestone completion

Produces:
- protected file-resource delivery per resource item
- end-to-end proof that admin multi-resource authoring and student same-page viewing work together

Consumes from S02:
- mixed resource types including note, PDF, and video

Consumes from S03:
- in-page list and resource-selection UI
