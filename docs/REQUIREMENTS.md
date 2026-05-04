# Product Requirements (PTE-Practice)

This document outlines the business logic, testing rules, and specific product requirements for the PTE mock testing platform.

## Core Objective
Provide a highly realistic simulation of the Pearson Test of English (PTE) Academic exam, specifically focusing on the interactive Audio/Speaking/Listening modules.

## Module 1: Read Aloud (Phase 3)
### Description
A text prompt appears on screen. The user has a set time to read it silently and prepare, followed by a set time to read it out loud into the microphone.

### Technical & UX Requirements
1. **Preparation Timer:** Exactly 30 seconds. A progress bar or countdown timer must be clearly visible.
2. **Audio Cue:** A short beep plays when the preparation timer hits 0.
3. **Recording Timer:** Exactly 40 seconds. 
4. **Recording State:** 
   - A visual waveform or active audio indicator must show the user that their microphone is picking up sound.
   - If the user finishes early, they must be able to click a "Stop Recording" or "Submit" button.
5. **Submission:**
   - The recorded audio blob MUST be packaged as a `.webm` or `.wav` file.
   - It is sent via Axios to the Laravel Backend (`POST /api/practice/read-aloud`).
   - The backend attaches this file to a `PracticeSession` model via Spatie MediaLibrary.

## Grading & Analytics (Phase 4 - Future)
- Submitted audio will be sent to an AI transcription service (e.g., OpenAI Whisper).
- The transcription will be compared against the original `practice_questions.content` using NLP metrics (Pronunciation, Oral Fluency, Content) to generate a PTE-equivalent score (10 - 90 scale).
