---
estimated_steps: 5
estimated_files: 6
---

# T03: Browser-check the final classroom flow and close the milestone

**Slice:** S04 — Protected resource delivery and final integration
**Milestone:** M002

## Description

Run the final browser pass against the live app, record any non-blocking observations, and leave the milestone artifacts in a finished state.

## Steps

1. Load the running candidate course page in the browser.
2. Verify the stacked support list and same-page viewer still behave correctly on the live app.
3. Record any runtime observations that are outside M002 scope but worth preserving.
4. Write slice and milestone summaries/UAT files.
5. Update project, roadmap, decisions, and state to reflect M002 completion.

## Must-Haves

- [x] The milestone has at least one live browser proof, not only backend tests.
- [x] All M002 artifacts truthfully reflect completion.

## Verification

- Browser assertions against `http://172.28.224.1:8000`
- `npm run build`

## Observability Impact

- Signals added/changed: milestone closure now points future agents to the final browser proof and assembled test suite
- How a future agent inspects this: `.gsd/milestones/M002/*` summaries plus the running candidate page
- Failure state exposed: mismatch between artifact state and runtime behavior

## Inputs

- `.gsd/milestones/M002/M002-ROADMAP.md` — milestone slice completion contract
- live `laravel-winhost-server` browser session — final classroom flow verification

## Expected Output

- `.gsd/milestones/M002/slices/S04/S04-SUMMARY.md` — final slice summary
- `.gsd/milestones/M002/M002-SUMMARY.md` — completed milestone summary
- `.gsd/PROJECT.md` / `.gsd/STATE.md` — current finished project state
