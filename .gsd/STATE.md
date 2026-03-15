# GSD State

**Active Milestone:** M002 — Multi-resource classroom course flow
**Active Slice:** S01 — Resource model and transition layer
**Active Task:** T02 — Add normalized resource resolution with legacy compatibility
**Phase:** Executing

## Recent Decisions
- Multi-resource courses now use a real `course_resources` child model.
- Resource titles and note bodies use French primary fields with optional Arabic companion fields.
- Selected resources must open below the list inside the same course page.
- The first version uses explicit admin-controlled manual ordering.

## Blockers
- None

## Next Action
Add normalized resource resolution helpers so legacy single-resource courses still expose a usable ordered support list while new courses resolve from child resources first.
