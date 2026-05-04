# System Architecture (PTE-Practice)

This document serves as the architectural Long Term Memory for AI Agents (Antigravity & Claude Code) and Developers.

## 1. High-Level Architecture
- **Frontend:** React 19 Single Page Application (SPA), served via Vite.
- **Backend:** Laravel 12 Headless REST API.
- **Infrastructure:** Docker Compose (Nginx, PHP-FPM, MySQL 8, Redis 7, Mailpit).

## 2. Frontend Conventions
- **Language:** TypeScript (Strict Mode enabled).
- **Styling:** Tailwind CSS 4 using canonical classes (e.g., `w-37.5` instead of `w-[150px]`). We utilize `lucide-react` for icons and `clsx`/`tailwind-merge` (`cn` utility) for conditional classes.
- **State Management:** 
  - **Local/UI State:** `useState` or `useReducer`.
  - **Global/Auth State:** Zustand (`useAuthStore`).
  - **Server State / Data Fetching:** TanStack React Query (v5).
- **Authentication Flow:** 
  - Uses Laravel Sanctum SPA Cookie Auth.
  - Requires `axios` to fetch `/sanctum/csrf-cookie` before `POST /login`.

## 3. Backend Conventions
- **Language:** PHP 8.2+.
- **Framework Pattern:** Standard MVC + FormRequests for validation. Keep Controllers thin; push heavy logic to Action classes or Services.
- **Database:** MySQL. Migrations must utilize foreign key constraints.
- **Media Storage:** `spatie/laravel-medialibrary` is used to attach audio recordings (`.webm`, `.wav`) to Eloquent Models.
- **API Documentation:** `dedoc/scramble` is installed. Ensure routes and controllers are properly typed so OpenAPI specs generate correctly at `/docs/api`.

## 4. Proposed Database Schema (Phase 3+)
- **users:** `id`, `name`, `email`, `password`.
- **practice_questions:** `id`, `module_type` (enum: read_aloud, repeat_sentence, etc.), `content` (text or JSON), `audio_url`.
- **practice_sessions (Attempts):** `id`, `user_id`, `practice_question_id`, `status` (pending_grading, graded), `score`, `transcription`.
  - *MediaLibrary Collection:* `user_audio` (where the actual spoken audio blob is attached).
