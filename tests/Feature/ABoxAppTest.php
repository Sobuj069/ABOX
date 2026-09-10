<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\VoiceSeeder;
use Database\Seeders\TargetAppSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\VipPlanSeeder;
use Database\Seeders\SiteSettingSeeder;
use App\Models\User;
use App\Models\Voice;
use App\Models\TargetApp;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ABoxAppTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([
            UserSeeder::class,
            VoiceSeeder::class,
            TargetAppSeeder::class,
            VipPlanSeeder::class,
            SiteSettingSeeder::class,
        ]);
    }

    public function test_home_page_loads_with_voices_and_apps(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('ABox');
        $response->assertSee('Voice preview');
        $response->assertSee('AI Male4');
        $response->assertSee('imo HD');
    }

    public function test_api_can_fetch_voices(): void
    {
        $response = $this->get('/voices');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'voices' => [
                '*' => ['id', 'name', 'gender', 'avatar', 'is_vip', 'pitch_shift']
            ]
        ]);
    }

    public function test_user_can_apply_free_voice(): void
    {
        $freeVoice = Voice::where('is_vip', false)->first();
        $response = $this->post('/voice/apply/' . $freeVoice->id);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    public function test_non_vip_user_cannot_apply_vip_voice(): void
    {
        $user = User::where('role', 'user')->first();
        $vipVoice = Voice::where('is_vip', true)->first();

        $response = $this->actingAs($user)->post('/voice/apply/' . $vipVoice->id);
        $response->assertStatus(403);
        $response->assertJson(['is_vip_required' => true]);
    }

    public function test_user_can_toggle_target_app(): void
    {
        $user = User::where('role', 'user')->first();
        $app = TargetApp::first();

        $response = $this->actingAs($user)->post('/app/toggle/' . $app->id);
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    public function test_vip_plans_can_be_retrieved(): void
    {
        $response = $this->get('/vip/plans');
        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::where('role', 'admin')->first();
        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard Overview');
    }

    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        $user = User::where('role', 'user')->first();
        $response = $this->actingAs($user)->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_audio_recording_upload(): void
    {
        Storage::fake('public');
        $user = User::where('role', 'user')->first();
        $voice = Voice::where('is_vip', false)->first();

        $fakeAudio = UploadedFile::fake()->create('test.wav', 100, 'audio/wav');

        $response = $this->actingAs($user)->post('/recording/upload', [
            'audio' => $fakeAudio,
            'voice_id' => $voice->id,
            'duration' => 2.5,
            'title' => 'My Test Recording'
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('recordings', [
            'user_id' => $user->id,
            'voice_id' => $voice->id,
            'title' => 'My Test Recording',
        ]);
    }
}
