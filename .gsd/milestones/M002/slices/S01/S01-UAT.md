# S01: Resource model and transition layer — UAT

This is non-blocking. The agent has already completed automated verification.

## What to check

1. No direct UI change is expected yet for the final student experience.
2. The main thing to verify later, once S02/S03 land, is that older courses created before the multi-resource model still continue to work.
3. If you inspect data or follow-up UI later, confirm that courses with no child resources still expose their older media/PDF content through the new flow.

## If something feels wrong

Note whether the issue looks like:
- old courses disappeared from the new flow
- old media/PDF content no longer resolves after the new model was introduced
- new child resources and old fields are both rendering at once for the same course
