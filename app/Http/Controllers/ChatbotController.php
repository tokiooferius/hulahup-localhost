<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Menu;
use App\Models\Canteen;

class ChatbotController extends Controller
{
    /**
     * Handle chatbot conversation using Google Gemini API
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'history' => 'nullable|array',
        ]);

        $userMessage = $request->input('message');
        $chatHistory = $request->input('history', []);

        // 1. Ambil semua menu makanan/minuman yang aktif dari database untuk referensi/konteks chatbot
        $menus = Menu::where('is_available', true)->with('canteen')->get();
        
        $menuListText = "";
        foreach ($menus as $menu) {
            $canteenName = $menu->canteen->name ?? 'Kantin';
            $menuListText .= "- {$menu->name} (Kategori: {$menu->category}, Harga: Rp " . number_format($menu->price, 0, ',', '.') . ") dari {$canteenName}. Deskripsi: " . ($menu->description ?? 'Tidak ada deskripsi') . "\n";
        }

        // 2. Susun system instruction untuk Gemini
        $systemInstruction = "Kamu adalah Food-TYU Assistant, sebuah chatbot rekomendasi kuliner pintar yang ramah dan interaktif di aplikasi pemesanan makanan kampus 'Food-TYU' untuk mahasiswa Telkom University (Tel-U).\n\n"
            . "Tugas utamamu adalah membantu mahasiswa/pembeli yang bingung ingin makan atau minum apa hari ini dengan memberikan rekomendasi hidangan terbaik yang tersedia di database kami.\n\n"
            . "Gunakan bahasa yang santai, bersahabat, peduli, dan sedikit gaul khas mahasiswa (bisa pakai kata 'kamu', 'kak', 'bro', 'sis', dll.) agar terasa asyik diajak mengobrol.\n\n"
            . "PENTING: Hanya rekomendasikan makanan/minuman yang terdapat pada daftar menu aktif di aplikasi kita berikut ini:\n"
            . $menuListText . "\n"
            . "Jika pengguna mencari makanan pedas, manis, segar, kopi, cemilan, porsi kenyang, atau ramah dompet, filter dan pilihkan menu yang paling cocok dari daftar di atas. Sebutkan nama menu, harga, dan kantin asalnya agar mereka tahu harus memesan ke mana.\n"
            . "Jika mereka bertanya tentang makanan di luar daftar ini, kamu boleh menyarankan saran umum tetapi ingatkan dengan sopan bahwa menu tersebut belum tersedia di aplikasi Food-TYU saat ini.\n"
            . "Ingatkan juga mereka bahwa mereka bisa langsung memesan menu-menu lezat ini melalui aplikasi Food-TYU!";

        // 3. Susun parameter input untuk Gemini API
        $contents = [];
        
        // Batasi riwayat percakapan maks 10 turn terakhir agar payload tidak terlalu besar
        $chatHistory = array_slice($chatHistory, -10);
        
        foreach ($chatHistory as $turn) {
            $contents[] = [
                'role' => $turn['role'] === 'user' ? 'user' : 'model',
                'parts' => [
                    ['text' => $turn['text']]
                ]
            ];
        }

        // Tambah pesan baru
        $contents[] = [
            'role' => 'user',
            'parts' => [
                ['text' => $userMessage]
            ]
        ];

        // 4. Kirim request ke Google Gemini API
        $apiKey = config('services.gemini.key');
        if (!$apiKey) {
            // Jika API key belum disetting, berikan respons alternatif yang cerdas
            $mockReplies = [
                "Halo! Saya Asisten Food-TYU. Sepertinya kunci API Gemini (`GEMINI_API_KEY`) belum dipasang di file `.env` server.\n\nTapi tenang! Hari ini rekomendasi terbaik dari saya adalah **Ayam Geprek** di Kantin Barokah yang pedesnya nampol, atau **Es Teh Manis** seger untuk menemani kuliahmu! 😋",
                "Hi! Kunci API Gemini belum diatur di server, tapi sebagai asisten kulinermu, aku saranin cobain menu best seller kita: **Ayam Geprek** atau cemilan manis yang ada di Kantin Barokah! Buruan pesan sebelum kehabisan! 🛍️"
            ];
            return response()->json([
                'reply' => $mockReplies[array_rand($mockReplies)]
            ]);
        }

        try {
            // Gunakan HTTP client Laravel
            $response = Http::timeout(15)->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}", [
                'contents' => $contents,
                'systemInstruction' => [
                    'parts' => [
                        ['text' => $systemInstruction]
                    ]
                ]
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $reply = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                
                if ($reply) {
                    return response()->json(['reply' => $reply]);
                }
            }
            
            throw new \Exception("Gemini API Error: " . $response->body());
        } catch (\Exception $e) {
            \Log::error("Gemini Chatbot Error: " . $e->getMessage());
            return response()->json([
                'reply' => "Aduh, sepertinya saya sedang kekenyangan nih dan otak saya agak lemot. 🤯\nBoleh coba tanya lagi sebentar lagi? Sementara itu, coba cek **Ayam Geprek** di Kantin Barokah, itu menu andalan yang hampir selalu cocok di segala suasana!"
            ]);
        }
    }
}
