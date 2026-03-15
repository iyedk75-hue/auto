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
