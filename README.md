# Tunisie Auto-École (Masar) — Platform README

Smart Driving Theory Learning System for Tunisia 🇹🇳

Tunisie Auto-École (Masar) is a digital platform designed to modernize driving theory education in Tunisia. The system connects driving school owners (Admin) and driving theory candidates (Students) while delivering a structured learning path, a realistic exam simulator, and strong anti-piracy protection.

Goal: Become the national standard platform for learning driving theory in Tunisia.

## 1. Design System

Color palette

| Element | Hex | Tailwind Class | Usage |
| --- | --- | --- | --- |
| Primary Orange | `#ec5b13` | `bg-primary` | Buttons, active links |
| Royal Blue | `#1e3a8a` | `bg-royal-blue` | Hero sections, sidebar |
| Luxury Gold | `#d4af37` | `text-gold-accent` | Icons, highlights |
| Off-White | `#f8f6f6` | `bg-background-light` | Main background |
| Dark Chocolate | `#221610` | `bg-background-dark` | Footer / dark mode |

## 2. Typography

| Type | Font |
| --- | --- |
| Arabic text | Noto Kufi Arabic |
| Numbers / Stats | Lexend |

Example Tailwind usage

`font-['Noto_Kufi_Arabic']`

`font-['Lexend']`

## 3. Global UI Effects

Cards

- `shadow-md`
- `hover:shadow-2xl`
- `transition-all`
- `duration-300`
- `hover:-translate-y-2`

Gradient background

- `bg-gradient-to-br`
- `from-royal-blue`
- `to-background-dark`

## 4. Platform Structure

The platform contains three main application areas:

- Landing Page
- Admin Dashboard
- Candidate Dashboard

## 5. Landing Page

Route

`/`

Layout

Full-screen hero section.

Headline

Text size: `text-5xl` → `text-7xl`

Main title: Pass the driving theory exam on the first attempt.

Subtitle (text-xl)

Masar provides the most advanced simulation for the Tunisian driving theory exam (A / B / C) with real-time follow-up from your driving school.

CTA buttons

Size: `px-8 py-4 text-lg`

Primary CTA (Orange): Register via WhatsApp

Secondary CTA (Blue outline): Driving School Login

Stats bar

Displayed below hero. Example metrics:

- 50+ Partner Driving Schools
- 98% Success Rate
- 5000+ Candidates

Floating badge

Floating card displaying: 98% Success Rate

## 6. Login Page

Route

`/login`

Layout

Centered glassmorphism card.

Role switcher

User selects login type:

- Candidate
- Driving School

Input fields

Height: `h-14`

Fields:

- Phone Number
- Password

Candidate marketing message

Don’t have an account? Activate your account instantly through WhatsApp.

Button: WhatsApp activation

## 7. Admin Dashboard

Route

`/admin`

Layout

Right sidebar layout.

Structure

- Sidebar width: `w-64`
- Main content: `flex-1`

Sections

- Candidates
- Accounting
- Add Candidate

## 8. Candidates Gallery

The system emphasizes visual student profiles rather than simple tables.

Layout

Grid of candidate cards.

Card specifications

- `min-h-[350px]`
- `rounded-2xl`
- `shadow-lg`

Candidate image

- `h-48`
- `w-full`
- `object-cover`
- `rounded-t-xl`

Images must be visually dominant.

Card information

Each card displays:

- Candidate name
- Phone number
- Registration date
- Learning progress
- Financial status

Actions

- Edit
- View results
- Send payment reminder

## 9. Smart Accountant

Financial overview page.

Displays:

- Monthly revenue
- Outstanding payments
- Platform balance

Transactions table fields:

- Candidate Name
- Amount Paid
- Date
- Remaining Balance

## 10. Add Candidate Page

Form for creating a new candidate.

Fields:

- Name
- Phone
- Teacher Notes

AI language assistant

Next to the Name and Teacher Notes fields, an icon allows:

- Arabic grammar correction
- Text formatting assistance

Platform fee notice

Footer alert: Candidate registration fee: 15 TND

## 11. Candidate Dashboard

Route

`/candidate`

Focus: learning + exam success motivation.

## 12. Login Challenge

After login, a pop-up challenge appears.

Title: Verify your knowledge before starting.

Content: Random traffic sign question with 3 options.

Example format:

- A
- B
- C

The student must answer before continuing.

## 13. Classroom (Lesson System)

Lessons are organized by categories:

- Priority rules
- Traffic signs
- Driving safety
- Vehicle basics

Lesson layout

Images:

- `aspect-video`
- full width

Text explanation: `text-2xl`

Displayed below each image.

## 14. Anti-Piracy Watermark

To prevent screenshot sharing, a moving watermark appears over lessons and exam screens.

Content:

- Candidate Name
- Phone Number

Opacity: `opacity-10`

## 15. Exam Simulator

Simulates the official Tunisian driving theory exam.

Timer

- `text-4xl`
- `font-mono`
- `text-primary`

Answer buttons

Large buttons optimized for mobile interaction.

- `h-20`
- full width

## 16. Mobile-First Design

The UI should feel like a native mobile app.

Recommended layout principles:

- Large tap areas
- Minimal text density
- Strong visual hierarchy
- Optimized for smartphones

## 17. Candidate Photo Format

Portrait ratio for candidate photos:

- `aspect-[3/4]`
- `w-full`

Used in the admin dashboard for professional profile cards.

## 18. Core Platform Philosophy

For candidates: We don’t sell accounts — we build confident drivers.

For driving schools: Manage students, payments, and progress in one system and elevate your school’s professional image.

## 19. Suggested Tech Stack

Frontend

- Blade (Laravel views)
- TailwindCSS
- Alpine.js
- Vite

Backend

- Laravel

Database

- MySQL or PostgreSQL

Authentication

- Laravel auth + device session control

Media storage

- Cloud storage with CDN delivery

If you want, I can also generate the next critical file for Codex developers, which is `exam_questions_schema.json`. It defines how A/B/C exam questions are stored in the database, including traffic sign images, correct answers, difficulty levels, and lesson categories. This schema is essential to build the exam simulator engine.
