# Coding Standards & Patterns

This document defines the strict coding guidelines, architectural patterns, and file structure rules that both AI Agents (Claude Code, Antigravity) and human developers MUST adhere to. 

Our goal is a highly maintainable, scalable, and organized codebase.

## 1. Architectural Patterns
We strictly adhere to **SOLID** principles, with a heavy emphasis on the **Single Responsibility Principle (SRP)**.

### Backend (Laravel) Guidelines
- **Thin Controllers, Fat Services:** Controllers should NEVER contain business logic. Their ONLY responsibilities are to receive the request, pass it to a Service or Action class, and return a response.
- **Form Requests:** Always use Laravel FormRequest classes for input validation. Do not validate in the controller.
- **Service/Action Classes:** Business logic (e.g., grading an exam, handling audio uploads) must live in `app/Services/` or single-action classes in `app/Actions/`.
- **Early Returns:** Avoid deep nesting (arrow code). Use guard clauses and return early.
- **Dependency Injection:** Inject dependencies via constructors or method arguments; avoid using Facades for core business logic when possible, to keep code testable.

### Frontend (React/TypeScript) Guidelines
- **Component Segregation:** Keep components small and focused. If a component is over 150 lines, it is likely doing too much and should be split.
- **Custom Hooks:** Extract complex `useEffect` logic, state transformations, or API data fetching into custom hooks (e.g., `usePracticeSession.ts`) inside `frontend/src/hooks/`.
- **Strict Typing:** Never use `any`. Always define proper interfaces or types in `frontend/src/types/`.

## 2. Proper File Structure
A strictly organized file structure prevents the codebase from becoming a monolith.

### Backend Directory Rules
```text
backend/
├── app/
│   ├── Actions/        # (NEW) Single-action classes (e.g., GradeReadAloudAction)
│   ├── Http/
│   │   ├── Controllers/ # Thin entry points
│   │   ├── Requests/    # Validation logic
│   ├── Models/         # Eloquent Models
│   ├── Services/       # (NEW) Complex multi-step business logic
```

### Frontend Directory Rules
```text
frontend/src/
├── api/          # Axios instances and API contracts
├── components/   
│   ├── layout/   # Global layout components (Sidebar, Navbar)
│   ├── shared/   # Reusable non-UI logic components
│   ├── ui/       # Dumb/Presentational components (Buttons, Inputs)
├── hooks/        # Custom React hooks (logic extraction)
├── pages/        # Route entry points (keep these thin, compose using components)
├── router/       # React Router configuration
├── stores/       # Zustand global state stores
├── types/        # Global TypeScript interfaces
```

## 3. General Clean Code Rules
1. **Meaningful Names:** Variables should be descriptive (`audioBlob` instead of `data`, `handleRecordingStop` instead of `stop`).
2. **No Magic Numbers:** Extract timers (e.g., 30s prep, 40s record) into named constants (e.g., `PREPARATION_TIME_SEC = 30`).
3. **DRY (Don't Repeat Yourself):** If you use the same Tailwind class string or PHP snippet three times, extract it to a shared component or trait.
## 4. Testing Requirements (Strictly Enforced)
Writing code without tests is strictly prohibited. Every new feature or endpoint must be accompanied by comprehensive tests.

### PHPUnit / Backend Tests
- **Location:** Place feature tests in `backend/tests/Feature/`.
- **Edge Cases:** You MUST test edge cases, not just the happy path. If you build an upload endpoint, you must test:
  - Valid payloads (Happy Path).
  - Missing payloads / empty bodies.
  - Invalid types (e.g., uploading a PDF instead of an Audio file).
  - Unauthorized access (no Sanctum token).
- **Format:** Use descriptive test method names (e.g., `test_user_cannot_upload_pdf_to_read_aloud`).

### Frontend Tests (Vitest)
- Ensure critical UI components and Zustand state logic have corresponding `.test.tsx` or `.test.ts` files covering edge state transitions.

## 5. Git Workflow & Version Control
Directly committing to the `develop` or `main` branches is **strictly prohibited** for feature work. 

### Branching Strategy
- **Feature Branches:** Before beginning work on a new checklist section or feature, you must create a new branch from `develop`.
- **Naming Convention:** Use `feature/<phase-name>-<feature-name>` (e.g., `feature/phase-3-read-aloud-frontend`).
- **Small Changes:** Only minor documentation typos or tiny bug fixes may be pushed directly to `develop`.

### Pull Requests (PRs)
- All feature branches must be merged into `develop` via a Pull Request.
- **Claude Code Instruction:** When executing tasks, checkout a new branch first. When the feature and its tests are complete, use the `git-batch.ps1` skill to commit, push the branch to origin, and instruct the user to open a PR.
