# GSD State

**Active Milestone:** M001 — Bilingual protected learning experience
**Active Slice:** S01 — Language foundation and RTL shell
**Active Task:** T03 — Localize core dashboards and classroom shell
**Phase:** Executing

## Recent Decisions
- Shared shell labels use `lang/*/ui.php`, while existing Breeze-style `__()` strings are translated through locale JSON files.
- The language switcher is a reusable Blade component used in guest and authenticated navigation.
- Role-specific login validation messages are translated through UI keys instead of hard-coded French strings.
- Verification continues through the Windows-side repo copy because this WSL environment lacks native PHP tooling.

## Blockers
- None

## Next Action
Translate the public landing shell, admin dashboard, candidate dashboard, and course-shell labels, add any remaining RTL shell fixes, then verify with dashboard tests and browser checks.
