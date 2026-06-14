<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Canteen;
use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ═══════════════════════════════════════════
        // AKUN ADMIN
        // ═══════════════════════════════════════════
        User::create([
            'name'     => 'Administrator',
            'username' => 'admin',
            'email'    => 'admin@hulahup.com',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
            'phone'    => '081234567890',
            'address'  => 'Kantor Pusat Hulahup, Telkom University',
        ]);

        // ═══════════════════════════════════════════
        // IBU KANTIN 1 — Kantin Barokah
        // ═══════════════════════════════════════════
        $ibuKantin1 = User::create([
            'name'     => 'Bu Sari',
            'username' => 'busari_kantin',
            'email'    => 'busari@hulahup.com',
            'password' => Hash::make('kantin123'),
            'role'     => 'ibu_kantin',
            'phone'    => '082111222333',
            'address'  => 'Kantin Barokah, Gedung A Telkom University',
        ]);

        $kantin1 = Canteen::create([
            'ibu_kantin_id' => $ibuKantin1->id,
            'name'          => 'Kantin Barokah',
            'description'   => 'Kantin favorit mahasiswa dengan menu lengkap dan harga terjangkau',
            'status'        => 'active',
            'balance'       => 0,
        ]);

        // Update user dengan canteen_id
        $ibuKantin1->update(['canteen_id' => $kantin1->id]);

        // Menu Kantin Barokah
        $menusKantin1 = [
            ['name' => 'Bakso Malang',             'category' => 'heavy',    'price' => 15000, 'image_url' => '/images/baksomalang.png',                    'rating' => 4.5],
            ['name' => 'Soto Ayam',                'category' => 'heavy',    'price' => 13000, 'image_url' => '/images/sotoayam.png',                       'rating' => 4.3],
            ['name' => 'Ayam Katsu',               'category' => 'heavy',    'price' => 18000, 'image_url' => '/images/katsu.png',                          'rating' => 4.7],
            ['name' => 'Ayam Goreng',              'category' => 'heavy',    'price' => 16000, 'image_url' => '/images/chicken.png',                        'rating' => 4.4],
            ['name' => 'Es Teh Manis',             'category' => 'beverage', 'price' => 5000,  'image_url' => '/images/esteh.png',                          'rating' => 4.6],
            ['name' => 'Ice Americano',            'category' => 'beverage', 'price' => 12000, 'image_url' => '/images/ice-americano.png',                  'rating' => 4.5],
            ['name' => 'Lumpia Goreng',            'category' => 'snack',    'price' => 8000,  'image_url' => '/images/lumpiagoreng.png',                   'rating' => 4.2],
            ['name' => 'Tahu Goreng',              'category' => 'snack',    'price' => 6000,  'image_url' => '/images/tahugoreng.png',                     'rating' => 4.0],
        ];

        foreach ($menusKantin1 as $menu) {
            Menu::create(array_merge($menu, [
                'canteen_id'  => $kantin1->id,
                'description' => 'Menu andalan ' . $kantin1->name,
            ]));
        }

        // ═══════════════════════════════════════════
        // IBU KANTIN 2 — Kantin Segar
        // ═══════════════════════════════════════════
        $ibuKantin2 = User::create([
            'name'     => 'Bu Dewi',
            'username' => 'budewi_kantin',
            'email'    => 'budewi@hulahup.com',
            'password' => Hash::make('kantin123'),
            'role'     => 'ibu_kantin',
            'phone'    => '083222333444',
            'address'  => 'Kantin Segar, Gedung B Telkom University',
        ]);

        $kantin2 = Canteen::create([
            'ibu_kantin_id' => $ibuKantin2->id,
            'name'          => 'Kantin Segar',
            'description'   => 'Minuman segar dan snack kekinian untuk mahasiswa Tel-U',
            'status'        => 'active',
            'balance'       => 0,
        ]);

        $ibuKantin2->update(['canteen_id' => $kantin2->id]);

        // Menu Kantin Segar
        $menusKantin2 = [
            ['name' => 'Bubble Ice Taro',          'category' => 'beverage', 'price' => 18000, 'image_url' => '/images/Bubble Ice Taro.png',               'rating' => 4.8],
            ['name' => 'Caramel Frappuccino',      'category' => 'beverage', 'price' => 22000, 'image_url' => '/images/caramelfrappuccino.png',             'rating' => 4.7],
            ['name' => 'Strawberry Smoothie',      'category' => 'beverage', 'price' => 20000, 'image_url' => '/images/strawberrysmoothie.png',             'rating' => 4.6],
            ['name' => 'Ice Lemon Tea',            'category' => 'beverage', 'price' => 10000, 'image_url' => '/images/ice-lemontea.png',                   'rating' => 4.4],
            ['name' => 'Watermelon Juice',         'category' => 'beverage', 'price' => 15000, 'image_url' => '/images/watermelon.png',                     'rating' => 4.5],
            ['name' => 'Seblak',                   'category' => 'snack',    'price' => 14000, 'image_url' => '/images/seblak.png',                         'rating' => 4.3],
            ['name' => 'Siomay',                   'category' => 'snack',    'price' => 12000, 'image_url' => '/images/siomay.png',                         'rating' => 4.4],
            ['name' => 'Donat Coklat',             'category' => 'snack',    'price' => 8000,  'image_url' => '/images/donatcoklat.png',                    'rating' => 4.2],
        ];

        foreach ($menusKantin2 as $menu) {
            Menu::create(array_merge($menu, [
                'canteen_id'  => $kantin2->id,
                'description' => 'Menu andalan ' . $kantin2->name,
            ]));
        }

        // ═══════════════════════════════════════════
        // IBU KANTIN 3 — Kantin Nusantara
        // ═══════════════════════════════════════════
        $ibuKantin3 = User::create([
            'name'     => 'Bu Rina',
            'username' => 'burina_kantin',
            'email'    => 'burina@hulahup.com',
            'password' => Hash::make('kantin123'),
            'role'     => 'ibu_kantin',
            'phone'    => '084333444555',
            'address'  => 'Kantin Nusantara, Gedung C Telkom University',
        ]);

        $kantin3 = Canteen::create([
            'ibu_kantin_id' => $ibuKantin3->id,
            'name'          => 'Kantin Nusantara',
            'description'   => 'Masakan nusantara yang lezat dan mengenyangkan',
            'status'        => 'active',
            'balance'       => 0,
        ]);

        $ibuKantin3->update(['canteen_id' => $kantin3->id]);

        // Menu Kantin Nusantara
        $menusKantin3 = [
            ['name' => 'Chicken Noodle Bakso',     'category' => 'heavy',    'price' => 16000, 'image_url' => '/images/Chicken Noodle with Meatball.png',  'rating' => 4.5],
            ['name' => 'Roti Bakar Coklat',        'category' => 'snack',    'price' => 10000, 'image_url' => '/images/rotibakarcoklat.png',                'rating' => 4.3],
            ['name' => 'Taichan',                  'category' => 'heavy',    'price' => 19000, 'image_url' => '/images/taichan.png',                        'rating' => 4.6],
        ];

        foreach ($menusKantin3 as $menu) {
            Menu::create(array_merge($menu, [
                'canteen_id'  => $kantin3->id,
                'description' => 'Menu andalan ' . $kantin3->name,
            ]));
        }

        // ═══════════════════════════════════════════
        // AKUN MAHASISWA CONTOH
        // ═══════════════════════════════════════════
        User::create([
            'name'     => 'Budi Mahasiswa',
            'username' => 'budi_mhs',
            'email'    => 'budi@student.telkomuniversity.ac.id',
            'password' => Hash::make('budi12345'),
            'role'     => 'mahasiswa',
            'nim'      => '103112430001',
            'phone'    => '085666777888',
            'address'  => 'Jl. Telekomunikasi No.1, Bandung',
            'balance'  => 50000,
        ]);
    }
}
