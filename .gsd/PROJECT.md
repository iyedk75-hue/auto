# Project

## What This Is

Masar is a Laravel-based auto-école platform with three surfaces in one codebase: a public marketing site, a candidate learning space, and an admin back office for the driving school. The current milestone focuses on turning the existing course area into a bilingual French/Arabic online learning experience with protected in-browser viewing.

## Core Value

A student must be able to log in, open a course, and read the PDF or watch the lesson video online in French or Arabic without being pushed toward file downloads.

## Current State

The repository already contains a Laravel 12 application with authentication, candidate and admin dashboards, course CRUD, candidate course listing/detail pages, inline PDF/video support in rough form, and single-device binding for candidate logins. S01 is now complete: French/Arabic locale switching, RTL shell behavior, translated shared navigation, translated auth entry screens, and localized public/admin/candidate dashboard and classroom shell surfaces are in place. The public landing page still contains the anti-piracy marketing block the user wants removed, bilingual course data fields are not added yet, and protected lesson assets are still uploaded to public storage.

## Architecture / Key Patterns

The app is server-rendered Laravel 12 with Blade views, Tailwind CSS, and light Alpine.js interactions. Public, candidate, and admin flows are split through route groups and role-aware navigation. Course management currently runs through `App\Http\Controllers\AdminCourseController`, while candidate viewing runs through `App\Http\Controllers\CandidateCourseController` and Blade templates under `resources/views/candidate/courses/`. Candidate single-device enforcement already exists in the authentication controllers. The milestone should preserve this Laravel/Blade structure and implement protected delivery through authenticated Laravel responses instead of public asset URLs.

## Capability Contract

See `.gsd/REQUIREMENTS.md` for the explicit capability contract, requirement status, and coverage mapping.

## Milestone Sequence

- [ ] M001: Bilingual protected learning experience — Add French/Arabic UI and course content, protected inline lesson viewing, and landing-page cleanup.
