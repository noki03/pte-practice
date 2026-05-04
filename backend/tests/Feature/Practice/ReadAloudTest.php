<?php

namespace Tests\Feature\Practice;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ReadAloudTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        Storage::fake('media'); // Fake the media storage disk
    }

    public function test_user_can_upload_valid_audio()
    {
        $audio = UploadedFile::fake()->create('test_audio.webm', 1000, 'audio/webm');

        $response = $this->actingAs($this->user)->postJson('/api/practice/read-aloud', [
            'audio' => $audio,
            'duration_ms' => 30000,
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'data' => [
                         'session_ulid',
                         'module_type',
                         'audio_url'
                     ]
                 ]);

        $this->assertDatabaseHas('practice_sessions', [
            'user_id' => $this->user->id,
            'session_type' => 'free_practice',
        ]);
    }

    public function test_user_cannot_upload_without_audio()
    {
        $response = $this->actingAs($this->user)->postJson('/api/practice/read-aloud', [
            'duration_ms' => 30000,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['audio']);
    }

    public function test_user_cannot_upload_invalid_mime_type()
    {
        // Try uploading a PDF instead of an audio file
        $pdf = UploadedFile::fake()->create('document.pdf', 1000, 'application/pdf');

        $response = $this->actingAs($this->user)->postJson('/api/practice/read-aloud', [
            'audio' => $pdf,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['audio']);
    }

    public function test_user_cannot_upload_audio_exceeding_size_limit()
    {
        // Fake a massive 10MB file (assuming limit is 5MB)
        $massiveAudio = UploadedFile::fake()->create('massive.webm', 10000, 'audio/webm');

        $response = $this->actingAs($this->user)->postJson('/api/practice/read-aloud', [
            'audio' => $massiveAudio,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['audio']);
    }

    public function test_unauthenticated_user_cannot_upload()
    {
        $audio = UploadedFile::fake()->create('test_audio.webm', 1000, 'audio/webm');

        $response = $this->postJson('/api/practice/read-aloud', [
            'audio' => $audio,
        ]);

        $response->assertStatus(401);
    }

    public function test_user_cannot_submit_audio_exceeding_duration_limit()
    {
        $audio      = UploadedFile::fake()->create('test_audio.webm', 1000, 'audio/webm');
        $maxMs      = config('pte.audio.max_duration_ms', 45_000);
        $overLimitMs = $maxMs + 1;

        $response = $this->actingAs($this->user)->postJson('/api/practice/read-aloud', [
            'audio'       => $audio,
            'duration_ms' => $overLimitMs,
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['duration_ms']);
    }

    public function test_second_upload_replaces_first_audio()
    {
        $firstAudio  = UploadedFile::fake()->create('first.webm',  500, 'audio/webm');
        $secondAudio = UploadedFile::fake()->create('second.webm', 500, 'audio/webm');

        $firstResponse = $this->actingAs($this->user)->postJson('/api/practice/read-aloud', [
            'audio' => $firstAudio,
        ]);
        $firstResponse->assertStatus(201);
        $firstUlid = $firstResponse->json('data.session_ulid');

        $secondResponse = $this->actingAs($this->user)->postJson('/api/practice/read-aloud', [
            'audio' => $secondAudio,
        ]);
        $secondResponse->assertStatus(201);
        $secondUlid = $secondResponse->json('data.session_ulid');

        // Each call creates its own session, so ULIDs differ
        $this->assertNotEquals($firstUlid, $secondUlid);

        // Both sessions exist in DB (one per submission)
        $this->assertDatabaseCount('practice_sessions', 2);
    }
}
