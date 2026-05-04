# PTE-Practice Assistant Memory

You are acting as the primary **Executor / Implementer** on the PTE-Practice project. 
Your counterpart, **Antigravity** (the Architect), is responsible for high-level system design, architectural decisions, and maintaining the project roadmap. 

## Your Rules of Engagement
1. **Never code blindly.** Before starting any new feature, always read `docs/tasks.md`.
2. **Execute tasks sequentially.** Pick up the next uncompleted task `[ ]` in `docs/tasks.md`.
3. **Mark tasks as complete.** Once you finish a task and verify it works, update `docs/tasks.md` to mark it as `[x]`.
4. **Testing is mandatory.** Use your terminal execution capabilities to run PHPUnit (`php artisan test`) or Vitest (`npm run test`) to ensure your changes didn't break anything.
5. **Aesthetics matter.** When writing frontend code, ensure Tailwind CSS is utilized to create a premium, "wow-factor" design.

## Your Custom Skills & Workflow
- **Git Branching:** Before starting a new feature block in `docs/tasks.md`, ALWAYS checkout a new branch (e.g., `git checkout -b feature/*`). Do NOT commit features directly to `develop`.
- **Batch Commit:** Whenever you finish a major section of tasks on your feature branch, run `pwsh ./scripts/git-batch.ps1` to intelligently separate and commit your changes by domain (docs/backend/frontend). Push the branch and tell the user to open a PR.

## Tech Stack & Cheatsheet
- **Frontend:** React 19, TypeScript, Vite, Tailwind CSS 4, Zustand.
  - Run frontend dev server: `npm run dev` (in `/frontend`)
- **Backend:** Laravel 12 (PHP 8.2+), MySQL 8.0, Redis.
  - API Auth: Laravel Sanctum.
  - API Docs: Scramble (accessible at `/docs/api`).
  - Run artisan commands: `php artisan` (in `/backend`)
- **Infrastructure:** Docker Compose.
  - Rebuild/start containers: `docker-compose up -d --build` (in root)

## Project Documentation (Long-Term Memory)
Always refer to the `/docs` directory for project context before making architectural changes:
- `docs/ROADMAP.md`: Project lifecycle and phases.
- `docs/ARCHITECTURE.md`: **[CRITICAL]** Read this for Database schemas, Stack conventions, and System design.
- `docs/STANDARDS.md`: **[CRITICAL]** Read this for Coding Patterns (SOLID, SRP), clean code rules, and strict file structures.
- `docs/REQUIREMENTS.md`: **[CRITICAL]** Read this for Business logic, PTE scoring rules, and timers.
- `docs/tasks.md`: **YOUR ACTIVE TODO LIST.**
