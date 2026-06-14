@extends('layouts.pembeli')

@section('title', $canteen->name)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">
        <!-- Breadcrumb -->
        <div class="mb-6 flex items-center gap-2 text-sm">
            <a href="/canteens" class="text-orange-500 hover:text-orange-600">
                <i class="fas fa-store"></i> Daftar Kantin
            </a>
            <span class="text-slate-400">/</span>
            <span class="text-slate-600">{{ $canteen->name }}</span>
        </div>

        <!-- Canteen Header -->
        @php
            $bannerSrc = $canteen->image ?? $canteen->logo_url ?? null;
            // Jika path menyimpan 'storage/canteens/xxx' → asset() langsung
            // Jika path berupa URL eksternal (http/https) → langsung pakai
            if ($bannerSrc) {
                $bannerUrl = (str_starts_with($bannerSrc, 'http') || str_starts_with($bannerSrc, '//'))
                    ? $bannerSrc
                    : asset($bannerSrc);
            } else {
                $bannerUrl = null;
            }
            $bannerFallback = 'https://ui-avatars.com/api/?name=' . urlencode($canteen->name) . '&background=F97316&color=fff&size=600&bold=true&font-size=0.25&length=2';
        @endphp
        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="relative h-56 bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center overflow-hidden">
                <img src="{{ $bannerUrl ?? $bannerFallback }}"
                     alt="{{ $canteen->name }}"
                     class="w-full h-full object-cover"
                     onerror="this.src='{{ $bannerFallback }}'">
                <div class="absolute inset-0 bg-black bg-opacity-20"></div>
            </div>

            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h1 class="text-3xl font-bold text-slate-900 mb-2">{{ $canteen->name }}</h1>
                        <p class="text-slate-600">
                            <i class="fas fa-user-tie text-orange-500"></i>
                            Pemilik: <span class="font-semibold">{{ $canteen->ibuKantin->name ?? 'Admin' }}</span>
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold text-yellow-500 mb-1">{{ number_format($canteen->rating, 1) }}/5</div>
                        <div class="flex items-center justify-end gap-1 mb-2">
                            @php
                                $rating = floor($canteen->rating);
                                $hasHalf = ($canteen->rating - $rating) >= 0.5;
                            @endphp
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $rating)
                                    <i class="fas fa-star text-yellow-400"></i>
                                @elseif($i == $rating + 1 && $hasHalf)
                                    <i class="fas fa-star-half-alt text-yellow-400"></i>
                                @else
                                    <i class="far fa-star text-slate-300"></i>
                                @endif
                            @endfor
                        </div>
                        <p class="text-sm text-slate-600">
                            <i class="fas fa-check-circle text-green-500"></i>
                            {{ $canteen->completed_orders }} pesanan selesai
                        </p>
                    </div>
                </div>

                @if($canteen->description)
                    <p class="text-slate-700 bg-slate-50 p-4 rounded-lg">
                        {{ $canteen->description }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Menu Section -->
        @if($menusByCategory->isEmpty())
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <i class="fas fa-inbox text-4xl text-slate-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-slate-900 mb-2">Menu Belum Tersedia</h3>
                <p class="text-slate-600">Kantin sedang menyiapkan menu. Nantikan segera! 🍽️</p>
            </div>
        @else
            @foreach($menusByCategory as $category => $menus)
                <div class="mb-8">
                    <!-- Category Title -->
                    <h2 class="text-2xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                        @php
                            $categoryIcons = [
                                'heavy' => 'fas fa-utensils text-orange-500',
                                'beverage' => 'fas fa-glass-water text-blue-500',
                                'snack' => 'fas fa-cookie text-amber-500',
                            ];
                        @endphp
                        <i class="{{ $categoryIcons[$category] ?? 'fas fa-box text-slate-500' }}"></i>
                        {{ ucfirst($category === 'heavy' ? 'Makanan Berat' : ($category === 'beverage' ? 'Minuman' : 'Snack')) }}
                    </h2>

                    <!-- Menu Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach($menus as $menu)
                        @php
                            // Buat URL absolut: jika sudah http/https pakai langsung, jika tidak pakai asset()
                            $menuImgUrl = (str_starts_with($menu->image_url ?? '', 'http') || str_starts_with($menu->image_url ?? '', '//')) 
                                ? $menu->image_url 
                                : asset($menu->image_url ?? '');
                            $menuImgFallback = 'https://ui-avatars.com/api/?name='.urlencode($menu->name).'&background=F97316&color=fff&size=200&bold=true';
                        @endphp
                            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden group"
                                 onclick="showMenuDetail({{ json_encode($menu) }}, '{{ $menuImgUrl }}')">
                                
                                <!-- Image -->
                                <div class="relative h-40 bg-slate-200 overflow-hidden cursor-pointer">
                                    <img src="{{ $menuImgUrl }}" 
                                         alt="{{ $menu->name }}"
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                         onerror="this.src='{{ $menuImgFallback }}'">
                                    <div class="absolute top-2 right-2 bg-orange-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                        Rp {{ number_format($menu->price, 0, ',', '.') }}
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-4">
                                    <h3 class="text-lg font-bold text-slate-900 mb-2 line-clamp-2">{{ $menu->name }}</h3>
                                    
                                    @if($menu->description)
                                        <p class="text-sm text-slate-600 mb-3 line-clamp-2">{{ $menu->description }}</p>
                                    @endif

                                    <!-- Rating (jika ada) -->
                                    @if($menu->rating)
                                        <div class="flex items-center gap-1 mb-3">
                                            <i class="fas fa-star text-yellow-400"></i>
                                            <span class="text-sm font-semibold text-slate-900">{{ $menu->rating }}/5</span>
                                        </div>
                                    @endif

                                    <!-- CTA Buttons -->
                                    <div class="flex gap-2">
                                        <button class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-3 rounded-lg transition flex items-center justify-center gap-1 text-sm"
                                                onclick="event.stopPropagation(); showMenuDetail({{ json_encode($menu) }}, '{{ $menuImgUrl }}')">
                                            <i class="fas fa-info-circle"></i> Detail
                                        </button>
                                        <button class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-3 rounded-lg transition flex items-center justify-center gap-1 text-sm"
                                                onclick="event.stopPropagation(); addMenuToCart({{ $menu->id }}, '{{ $menu->name }}', {{ $menu->price }}, '{{ $menuImgUrl }}', {{ $canteen->id }})">
                                            <i class="fas fa-shopping-cart"></i> Pesan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<!-- Menu Detail Modal -->
<div id="menuDetailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
    <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-auto">
        <!-- Close Button -->
        <div class="sticky top-0 flex items-center justify-between p-4 border-b border-slate-200 bg-white">
            <h2 class="text-xl font-bold text-slate-900">Detail Menu</h2>
            <button onclick="closeMenuDetail()" class="text-slate-500 hover:text-slate-700 text-2xl">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <!-- Content -->
        <div id="menuDetailContent" class="p-6">
            <!-- Loading -->
            <div class="text-center py-12">
                <i class="fas fa-spinner fa-spin text-orange-500 text-3xl"></i>
            </div>
        </div>
    </div>
</div>

<script>
    function showMenuDetail(menu, resolvedImgUrl) {
        const modal = document.getElementById('menuDetailModal');
        const content = document.getElementById('menuDetailContent');
        // Gunakan resolvedImgUrl yang sudah di-resolve pakai asset() dari server
        const imgSrc = resolvedImgUrl || menu.image_url || '';

        let categoryLabel = '';
        if (menu.category === 'heavy') categoryLabel = 'Makanan Berat';
        else if (menu.category === 'beverage') categoryLabel = 'Minuman';
        else if (menu.category === 'snack') categoryLabel = 'Snack';

        const html = `
            <div class="space-y-4">
                <!-- Image -->
                <div class="w-full h-60 bg-slate-200 rounded-lg overflow-hidden">
                    <img src="${imgSrc}" alt="${menu.name}" class="w-full h-full object-cover"
                         onerror="this.src='https://ui-avatars.com/api/?name='+encodeURIComponent('${menu.name}')+'&background=F97316&color=fff&size=200&bold=true'">
                </div>

                <!-- Basic Info -->
                <div>
                    <span class="inline-block bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-semibold mb-2">
                        ${categoryLabel}
                    </span>
                    <h3 class="text-2xl font-bold text-slate-900 mb-2">${menu.name}</h3>
                    <p class="text-3xl font-bold text-orange-500">Rp ${new Intl.NumberFormat('id-ID').format(menu.price)}</p>
                </div>

                <!-- Description -->
                ${menu.description ? `
                    <div class="bg-slate-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-slate-900 mb-2">Deskripsi</h4>
                        <p class="text-slate-700 leading-relaxed">${menu.description}</p>
                    </div>
                ` : ''}

                <!-- Rating -->
                ${menu.rating ? `
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="flex items-center gap-2 mb-1">
                            <i class="fas fa-star text-yellow-400"></i>
                            <span class="text-lg font-bold text-slate-900">${menu.rating}/5</span>
                        </div>
                        <p class="text-sm text-slate-600">Rating dari pembeli</p>
                    </div>
                ` : ''}

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-4 border-t border-slate-200">
                    <button onclick="closeMenuDetail()" class="flex-1 bg-slate-200 hover:bg-slate-300 text-slate-900 font-semibold py-3 px-4 rounded-lg transition">
                        <i class="fas fa-times"></i> Tutup
                    </button>
                    <button onclick="event.stopPropagation(); addMenuToCart(${menu.id}, '${menu.name}', ${menu.price}, '${imgSrc}', ${menu.canteen_id}); closeMenuDetail();" 
                            class="flex-1 bg-orange-500 hover:bg-orange-600 text-white font-semibold py-3 px-4 rounded-lg transition flex items-center justify-center gap-2">
                        <i class="fas fa-shopping-cart"></i> Tambah ke Keranjang
                    </button>
                </div>
            </div>
        `;

        content.innerHTML = html;
        modal.classList.remove('hidden');
    }

    function closeMenuDetail() {
        document.getElementById('menuDetailModal').classList.add('hidden');
    }

    // Close modal on outside click
    document.getElementById('menuDetailModal').addEventListener('click', (e) => {
        if (e.target === document.getElementById('menuDetailModal')) {
            closeMenuDetail();
        }
    });

    // Add to cart from canteen detail
    function addMenuToCart(menuId, name, price, img, canteenId) {
        if (typeof addToCart === 'function') {
            addToCart(name, price, img, canteenId);
            alert('✓ ' + name + ' ditambahkan ke keranjang!');
        } else {
            alert('Silakan pergi ke Home untuk menambah menu ke keranjang');
        }
    }
</script>
@endsection
