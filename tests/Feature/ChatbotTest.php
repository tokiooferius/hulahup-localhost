<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Models\User;
use App\Models\Canteen;
use App\Models\Menu;

class ChatbotTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test bahwa guest (belum login) tidak bisa akses chatbot API
     */
    public function test_chatbot_requires_authentication(): void
    {
        $response = $this->postJson('/api/chatbot/chat', [
            'message' => 'Halo, mau makan apa ya?',
        ]);

        $response->assertStatus(401);
    }

    /**
     * Test chatbot mengembalikan pesan fallback ketika GEMINI_API_KEY belum diisi
     */
    public function test_chatbot_returns_fallback_when_api_key_missing(): void
    {
        // Override config agar API key kosong
        config(['services.gemini.key' => null]);

        $user = User::factory()->create([
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->postJson('/api/chatbot/chat', [
            'message' => 'Halo, rekomendasi makanan dong!',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure(['reply']);

        // Pastikan reply mengandung pesan fallback / konten alternatif
        $reply = $response->json('reply');
        $this->assertNotEmpty($reply, 'Reply harus berisi teks');
    }

    /**
     * Test chatbot berhasil memanggil Gemini API dan mengembalikan balasan
     */
    public function test_chatbot_calls_gemini_api_when_configured(): void
    {
        // Override config dengan API key palsu
        config(['services.gemini.key' => 'mock_api_key_12345']);

        // Mock HTTP request ke Gemini API
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Halo kak! Kamu harus banget cobain Nasi Goreng Gila dari Kantin Barokah. Murah dan kenyang banget!']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $user = User::factory()->create([
            'role' => 'user',
        ]);

        // Buat data kantin dan menu aktif di in-memory DB
        $canteen = Canteen::create([
            'name'          => 'Kantin Barokah',
            'ibu_kantin_id' => $user->id,
            'status'        => 'buka',
        ]);

        Menu::create([
            'canteen_id'   => $canteen->id,
            'name'         => 'Nasi Goreng Gila',
            'price'        => 15000,
            'category'     => 'heavy',
            'is_available' => true,
            'description'  => 'Nasi goreng super pedas dan gila',
        ]);

        $response = $this->actingAs($user)->postJson('/api/chatbot/chat', [
            'message' => 'Rekomendasiin makanan yang pedes dong',
            'history' => [
                ['role' => 'user', 'text' => 'Hi'],
                ['role' => 'model', 'text' => 'Halo! Ada yang bisa kubantu?']
            ]
        ]);

        $response->assertStatus(200)
                 ->assertExactJson([
                     'reply' => 'Halo kak! Kamu harus banget cobain Nasi Goreng Gila dari Kantin Barokah. Murah dan kenyang banget!'
                 ]);

        // Verifikasi bahwa request HTTP memang dikirimkan ke endpoint Gemini
        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'generativelanguage.googleapis.com') &&
                   $request->method() === 'POST' &&
                   isset($request['contents']) &&
                   isset($request['systemInstruction']);
        });
    }

    /**
     * Test validasi input — pesan tidak boleh kosong
     */
    public function test_chatbot_validates_required_message(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this->actingAs($user)->postJson('/api/chatbot/chat', [
            'message' => '',
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['message']);
    }
}
