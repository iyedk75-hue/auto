# Project

## What This Is

Masar is a Laravel-based auto-école platform with three surfaces in one codebase: a public marketing site, a candidate learning space, and an admin back office for the driving school. M001 completed the bilingual protected learning foundation. The next milestone extends courses from a single protected file pair into a multi-resource classroom experience with ordered videos, PDFs, and admin-written notes inside each course.

## Core Value

A student must be able to open a course and consume an ordered list of supports — videos, PDFs, and notes — inside the same course page without leaving the learning flow or losing the existing content-protection behavior for file resources.

## Current State

The repository contains a Laravel 12 application with authentication, bilingual French/Arabic UI, RTL shell support, localized public/admin/candidate surfaces, bilingual course text fields, private protected lesson asset storage, authenticated inline media/PDF delivery, and visible deterrence on protected lesson pages. Each course still only supports one `media_path` and one `pdf_path`, so the learning model is still too narrow for a Classroom-style support list.

## Architecture / Key Patterns

The app is server-rendered Laravel 12 with Blade views, Tailwind CSS, and light Alpine.js interactions. Public, candidate, and admin flows are split through route groups and role-aware navigation. Course management currently runs through `App\Http\Controllers\AdminCourseController`, while candidate viewing runs through `App\Http\Controllers\CandidateCourseController` and Blade templates under `resources/views/candidate/courses/`. Protected lesson files live on the private `local` disk and are consumed only through authenticated routes. New milestone work should preserve this Laravel/Blade structure and evolve the course model toward a child-resource architecture instead of bolting more fields onto `courses`.

## Capability Contract

See `.gsd/REQUIREMENTS.md` for the explicit capability contract, requirement status, and coverage mapping.

## Milestone Sequence

- [x] M001: Bilingual protected learning experience — Add French/Arabic UI and course content, protected inline lesson viewing, and landing-page cleanup.
- [ ] M002: Multi-resource classroom course flow — Replace one-file courses with ordered supports, same-page resource viewing, and admin note/resource management.
