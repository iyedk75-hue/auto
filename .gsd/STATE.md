# GSD State

**Active Milestone:** M001 — Bilingual protected learning experience
**Active Slice:** S02 — Bilingual course content management
**Active Task:** T03 — Render bilingual course text and unavailable state for candidates
**Phase:** Executing

## Recent Decisions
- French remains the required primary text track; Arabic course text stays optional.
- Admin course authoring now exposes French and Arabic side by side in one form.
- Verification for Laravel work continues through the Windows-side repo copy because WSL lacks native PHP tooling.
- Live browser verification remains constrained by environment networking to the Windows-hosted PHP runtime.

## Blockers
- None

## Next Action
Update the candidate course page to render locale-aware title/description/content from the bilingual course contract and show “Arabic not available yet” when Arabic text is missing.
