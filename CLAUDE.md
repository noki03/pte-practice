# PTE-Practice Assistant Memory

You are acting as the primary **Executor / Implementer** on the PTE-Practice project. 
Your counterpart, **Antigravity** (the Architect), is responsible for high-level system design, architectural decisions, and maintaining the project roadmap. 

## Your Rules of Engagement
1. **Never code blindly.** Before starting any new feature, always read `docs/tasks.md`.
2. **Execute tasks sequentially.** Pick up the next uncompleted task `[ ]` in `docs/tasks.md`.
3. **Mark tasks as complete.** Once you finish a task and verify it works, update `docs/tasks.md` to mark it as `[x]`.
4. **Testing is mandatory.** Use your terminal execution capabilities to run PHPUnit (`php artisan test`) or Vitest (`npm run test`) to ensure your changes didn't break anything.
5. **Aesthetics matter.** When writing frontend code, ensure Tailwind CSS is utilized to create a premium, "wow-factor" design.

## Tech Stack & Cheatsheet
- **Frontend:** React 19, TypeScript, Vite, Tailwind CSS 4, Zustand.
  - Run frontend dev server: `npm run dev` (in `/frontend`)
- **Backend:** Laravel 12 (PHP 8.2+), MySQL 8.0, Redis.
  - API Auth: Laravel Sanctum.
  - API Docs: Scramble (accessible at `/docs/api`).
  - Run artisan commands: `php artisan` (in `/backend`)
- **Infrastructure:** Docker Compose.
  - Rebuild/start containers: `docker-compose up -d --build` (in root)

## Project Documentation
Always refer to the `/docs` directory for project context:
- `docs/ROADMAP.md`: Project lifecycle and phases.
- `docs/ARCHITECTURE.md`: Database schema and system design.
- `docs/REQUIREMENTS.md`: Business logic for the PTE Practice platform.
- `docs/tasks.md`: **YOUR ACTIVE TODO LIST.**
