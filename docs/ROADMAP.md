# PTE-Practice Roadmap

## Phase 1: Foundation (✅ Completed)
- Setup React + TypeScript + Vite frontend.
- Setup Laravel 12 backend.
- Dockerize environment (MySQL, Redis, Nginx, PHP).
- Implement basic audio recording utilities (microphone release, dark mode).
- Setup UserSeeder (test@gmail.com).

## Phase 2: Authentication & Dashboard (🚀 Current)
- Integrate Laravel Sanctum for API authentication.
- Create Login and Registration flows in React.
- Implement Zustand auth store to persist sessions.
- Build a protected User Dashboard layout.

## Phase 3: Core PTE Modules
- **Speaking Module:** Read Aloud, Repeat Sentence, Describe Image.
  - Implement real-time audio waveform visualization.
  - Save audio blobs to the backend via Spatie MediaLibrary.
- **Listening Module:** Dictation, Summarize Spoken Text.
  - Audio playback with specialized controls.

## Phase 4: Grading & Analytics
- Integrate AI grading API (e.g., OpenAI Whisper for transcription, LLMs for scoring).
- Build the analytics dashboard for users to track their PTE score progression.
