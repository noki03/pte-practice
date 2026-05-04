# Active Task List

This file tracks the active implementation plan. 
**Claude:** Execute these tasks sequentially. When finished with a step, mark it as `[x]`.

## Phase 3: Core PTE Modules - "Read Aloud"

### 1. Backend Infrastructure (Audio Uploads)
- [x] Create a `PracticeSession` Eloquent model and migration. Fields should include `user_id` (foreign key), `module_type` (string), and `status` (string, default: 'pending').
- [x] Configure `spatie/laravel-medialibrary` on the `PracticeSession` model so we can attach audio files to it (implement `HasMedia` interface and `InteractsWithMedia` trait).
- [x] Create an API endpoint (`POST /api/practice/read-aloud`) in a new controller (e.g., `PracticeController`).
- [x] Ensure the endpoint accepts an audio file upload (`.webm` or `.wav`), creates a `PracticeSession` record, and attaches the file to the record via MediaLibrary.
- [x] **TESTING:** Write a comprehensive PHPUnit Feature test for this endpoint (`tests/Feature/Practice/ReadAloudTest.php`). You must test edge cases as defined in `STANDARDS.md`. (Note: Currently returning a 404, please debug the route registration or cache!).
- [x] **COMMIT:** Once the tests pass, run the custom skill `pwsh ./scripts/git-batch.ps1` to neatly commit the backend work.

### 2. Frontend UI (Read Aloud Component)
- [ ] Create `frontend/src/pages/practice/ReadAloudPage.tsx`.
- [ ] Implement the classic PTE timer system: 30 seconds to "Prepare" (count down), followed automatically by a 40-second "Recording" phase.
- [ ] Integrate a visual waveform or audio pulse indicator during the recording phase so the user knows their mic is working.
- [ ] Build a sleek, premium Tailwind CSS layout matching the Dashboard aesthetic.

### 3. Frontend Integration
- [ ] Hook up the existing recording utility (or implement standard `MediaRecorder` API) to capture the audio blob upon completion or manual stop.
- [ ] Submit the recorded blob via Axios as `multipart/form-data` to the new Laravel Sanctum-protected endpoint.
- [ ] Add a new route in React Router to access this page (e.g., `/practice/read-aloud`).

### 4. Verification
- [ ] Complete a full run of the "Read Aloud" module in the browser.
- [ ] Check the Laravel storage directory / database to verify the audio blob was successfully saved via MediaLibrary.
