# GSD State

**Active Milestone:** M001 — Bilingual protected learning experience
**Active Slice:** S01 — Language foundation and RTL shell
**Active Task:** T02 — Translate shared layouts, navigation, and auth entry screens
**Phase:** Executing

## Recent Decisions
- Locale switching uses Laravel middleware with shared session/cookie persistence.
- Default app locale is now French, with Arabic as the second supported locale.
- Layouts expose locale state through `lang` and `dir` so UI verification can assert shell behavior directly.
- Verification runs through a Windows-side repo copy because this WSL environment lacks native PHP tooling.

## Blockers
- None

## Next Action
Translate the shared layouts, guest/auth navigation, and login entry surfaces using French/Arabic language files, then rerun auth-related verification.
