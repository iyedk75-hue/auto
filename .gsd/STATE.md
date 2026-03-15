# GSD State

**Active Milestone:** M001 — Bilingual protected learning experience
**Active Slice:** S02 — Bilingual course content management
**Active Task:** T02 — Update admin course create/edit flow for bilingual authoring
**Phase:** Executing

## Recent Decisions
- Existing `title` / `description` / `content` remain the French track; Arabic content uses parallel `_ar` columns.
- Locale-aware course text selection lives on the `Course` model through dedicated helper methods.
- Verification for Laravel work continues through the Windows-side repo copy because WSL lacks native PHP tooling.
- Live browser verification remains constrained by environment networking to the Windows-hosted PHP runtime.

## Blockers
- None

## Next Action
Update admin course validation, persistence, and the course form so French and Arabic lesson text can be authored side by side, then verify with admin course feature tests.
