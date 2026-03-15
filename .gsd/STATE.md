# GSD State

**Active Milestone:** M002 — Multi-resource classroom course flow
**Active Slice:** S02 — Admin multi-resource management
**Active Task:** None — slice plan not written yet
**Phase:** Ready for slice planning

## Recent Decisions
- Multi-resource courses now use a real `course_resources` child model with ordered supports.
- Resource titles and note bodies use French primary fields with optional Arabic companion fields.
- Child resources take precedence over legacy course file fields when both exist.
- Legacy single-resource courses resolve into synthetic ordered resource items during the transition.

## Blockers
- None

## Next Action
Read `.gsd/milestones/M002/M002-CONTEXT.md`, `.gsd/milestones/M002/M002-ROADMAP.md`, `.gsd/DECISIONS.md`, and `.gsd/milestones/M002/slices/S01/S01-SUMMARY.md`, then create `.gsd/milestones/M002/slices/S02/S02-PLAN.md` for admin multi-resource management.
