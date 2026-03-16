# Project

## What This Is

Masar is a Laravel-based auto-école platform with three surfaces in one codebase: a public marketing site, a candidate learning space, and an admin back office for the driving school. M001 delivered the bilingual protected learning foundation, and M002 completed the multi-resource classroom flow so each course can now contain ordered videos, PDFs, and admin-written notes.

## Core Value

A student can open one course page and consume an ordered list of supports — notes, videos, and PDFs — inside the same page, while the driving school keeps protected delivery for file resources and full control over support order.

## Current State

The repository contains a Laravel 12 application with authentication, bilingual French/Arabic UI, RTL shell support, localized public/admin/candidate surfaces, bilingual course and resource text fields, private protected lesson asset storage, authenticated inline media/PDF/resource delivery, and visible deterrence on protected lesson pages. Courses now support repeated ordered child resources through `course_resources`, admins can manage those resources through nested CRUD screens, and candidates consume them through a Classroom-style stacked list with a same-page viewer below the list. Legacy single-resource courses still resolve through the same candidate flow during the transition.

## Architecture / Key Patterns

The app is server-rendered Laravel 12 with Blade views, Tailwind CSS, and light Alpine.js interactions. Public, candidate, and admin flows are split through route groups and role-aware navigation. Course management now spans `App\Http\Controllers\AdminCourseController` and `App\Http\Controllers\AdminCourseResourceController`, while candidate viewing runs through `App\Http\Controllers\CandidateCourseController` and Blade templates under `resources/views/candidate/courses/`. Protected lesson files live on the private `local` disk and are consumed only through authenticated routes. Candidate resource selection stays on `courses.show` via `?resource=` so the selected support remains shareable, server-rendered, and resilient without client-only state.

## Capability Contract

See `.gsd/REQUIREMENTS.md` for the explicit capability contract, requirement status, and coverage mapping.

## Milestone Sequence

- [x] M001: Bilingual protected learning experience — Add French/Arabic UI and course content, protected inline lesson viewing, and landing-page cleanup.
- [x] M002: Multi-resource classroom course flow — Replace one-file courses with ordered supports, same-page resource viewing, and admin note/resource management.
