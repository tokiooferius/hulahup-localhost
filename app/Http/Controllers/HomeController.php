<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Canteen;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Ambil semua menu dari kantin yang aktif
        $allMenus = Menu::with(['canteen' => function($query) {
                $query->with('ibuKantin');
            }])
            ->whereHas('canteen', function ($query) {
                $query->where('status', 'active');
            })
            ->get();

        // Ambil semua kantin yang aktif
        $canteens = Canteen::with('ibuKantin')
            ->where('status', 'active')
            ->get();

        return view('home', [
            'menus'    => $allMenus,
            'canteens' => $canteens,
        ]);
    }
}
