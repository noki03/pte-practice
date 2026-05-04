# Active Task List

This file tracks the active implementation plan. 
**Claude:** Execute these tasks sequentially. When finished with a step, mark it as `[x]`.

## Phase 2: Authentication & User Dashboard

### 1. Backend Authentication Setup
- [x] Verify `backend/routes/api.php` has functional `login`, `register`, `logout`, and `/user` endpoints using Laravel Sanctum.
- [x] Verify or create the Auth Controllers (`LoginController`, `RegisterController`) to handle these requests and validate payloads.
- [x] Ensure the endpoints are documented via Scramble.

### 2. Frontend State & API Client
- [x] Configure `axios` in `frontend/src/api/client.ts` (or similar) to handle CSRF cookie requests (`/sanctum/csrf-cookie`) automatically.
- [x] Set up axios interceptors to handle 401 Unauthorized responses (clear local state).
- [x] Create a Zustand store `frontend/src/stores/useAuthStore.ts` to manage the `user` object and `isAuthenticated` state.

### 3. Frontend Authentication Views
- [x] Build `frontend/src/pages/auth/LoginPage.tsx` (email, password, validation errors).
- [x] Build `frontend/src/pages/auth/RegisterPage.tsx` (name, email, password, confirmation).
- [x] Apply premium Tailwind CSS styling to both forms (dark mode support, smooth transitions).

### 4. Routing & Protected Dashboard
- [x] Implement a `ProtectedRoute` wrapper component in React Router that redirects unauthenticated users to `/login`.
- [x] Build `frontend/src/pages/dashboard/DashboardLayout.tsx` (Sidebar + Top Navbar).
- [x] Build `frontend/src/pages/dashboard/DashboardHome.tsx` (Welcome screen, placeholder stats).

### 5. Verification
- [ ] Run full E2E manual test: Register -> Login -> View Dashboard -> Refresh Page -> Logout.
