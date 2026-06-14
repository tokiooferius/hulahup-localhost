<?php $__env->startSection('title', 'Kelola Kantin'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-8">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="mb-8 flex justify-between items-center">
            <div>
                <p class="text-slate-500 font-medium text-sm">Manajemen semua kantin Tel-U dan akun pemilik kantin</p>
            </div>
            <button onclick="openAddCanteenModal()"
                class="px-5 py-2.5 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition flex items-center gap-2 text-sm shadow-sm hover:shadow active:scale-95">
                <i class="fas fa-plus"></i> Tambah Kantin
            </button>
        </div>

        <!-- Success & Error Alerts -->
        <?php if(session('success')): ?>
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl font-bold text-sm flex items-center gap-2">
                <i class="fas fa-circle-check text-green-500"></i>
                <span><?php echo e(session('success')); ?></span>
            </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl font-bold text-sm flex items-center gap-2">
                <i class="fas fa-exclamation-circle text-red-500"></i>
                <span><?php echo e(session('error')); ?></span>
            </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
            <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl font-bold text-sm">
                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-circle-exclamation text-red-500"></i>
                    <span>Terdapat kesalahan penginputan data:</span>
                </div>
                <ul class="list-disc pl-6 space-y-0.5 font-medium text-xs">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Canteens Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__empty_1 = true; $__currentLoopData = $canteens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $canteen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $canteenImg = $canteen->image ? asset($canteen->image) : null;
                    $fallbackImg = 'https://ui-avatars.com/api/?name='.urlencode($canteen->name).'&background=122C4F&color=fff&size=200&bold=true';
                ?>
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 hover:shadow-md transition duration-300 overflow-hidden flex flex-col justify-between">
                    <div>
                        <!-- Card Cover Image or Gradient -->
                        <div class="h-28 bg-gradient-to-r from-blue-600 to-indigo-700 relative overflow-hidden">
                            <?php if($canteenImg): ?>
                                <img src="<?php echo e($canteenImg); ?>" class="w-full h-full object-cover opacity-75">
                            <?php endif; ?>
                            <div class="absolute inset-0 bg-black/20"></div>
                            <div class="absolute bottom-4 left-6 right-6 text-white">
                                <h2 class="text-xl font-black truncate leading-tight"><?php echo e($canteen->name); ?></h2>
                                <p class="text-white/80 text-[10px] font-bold uppercase tracking-wider mt-0.5">
                                    <i class="fa-solid fa-location-dot mr-1"></i><?php echo e($canteen->location ?? 'Telkom University'); ?>

                                </p>
                            </div>
                        </div>

                        <div class="p-6 space-y-4">
                            <!-- Owner Info -->
                            <div class="flex items-center gap-3.5 pb-4 border-b border-slate-100">
                                <?php if($canteen->ibuKantin && $canteen->ibuKantin->avatar): ?>
                                    <img src="<?php echo e(asset('storage/avatars/' . $canteen->ibuKantin->avatar)); ?>" 
                                         class="w-12 h-12 rounded-full object-cover border-2 border-blue-50 shadow-sm">
                                <?php else: ?>
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-black text-lg shadow-sm">
                                        <?php echo e(strtoupper(substr($canteen->ibuKantin->name ?? 'IK', 0, 1))); ?>

                                    </div>
                                <?php endif; ?>
                                <div class="overflow-hidden">
                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Pemilik / Pengelola</p>
                                    <p class="text-sm font-bold text-slate-800 truncate"><?php echo e($canteen->ibuKantin->name ?? 'N/A'); ?></p>
                                    <p class="text-xs text-slate-500 truncate"><?php echo e($canteen->ibuKantin->email ?? 'N/A'); ?></p>
                                </div>
                            </div>

                            <!-- Canteen Stats -->
                            <div class="grid grid-cols-3 gap-3">
                                <div class="text-center p-2.5 bg-slate-50 rounded-2xl">
                                    <div class="text-xl font-black text-blue-600"><?php echo e($canteen->orders_count); ?></div>
                                    <div class="text-[9px] font-bold text-slate-500 uppercase tracking-wide">Pesanan</div>
                                </div>
                                <div class="text-center p-2.5 bg-slate-50 rounded-2xl">
                                    <div class="text-xl font-black text-green-600"><?php echo e($canteen->menus_count); ?></div>
                                    <div class="text-[9px] font-bold text-slate-500 uppercase tracking-wide">Menu</div>
                                </div>
                                <div class="text-center p-2.5 bg-slate-50 rounded-2xl">
                                    <div class="text-xl font-black text-purple-600"><?php echo e($canteen->vouchers_count); ?></div>
                                    <div class="text-[9px] font-bold text-slate-500 uppercase tracking-wide">Voucher</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer / Actions -->
                    <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between bg-slate-50/50 rounded-b-3xl">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-bold">
                            <i class="fa-solid fa-circle-check text-[10px]"></i> Aktif
                        </span>
                        <div class="flex gap-4">
                            <button onclick="openEditCanteenModal(
                                <?php echo e($canteen->id); ?>, 
                                '<?php echo e(addslashes($canteen->name)); ?>',
                                '<?php echo e(addslashes($canteen->location)); ?>',
                                '<?php echo e(addslashes($canteen->ibuKantin->name ?? '')); ?>',
                                '<?php echo e(addslashes($canteen->ibuKantin->username ?? '')); ?>',
                                '<?php echo e(addslashes($canteen->ibuKantin->email ?? '')); ?>',
                                '<?php echo e(addslashes($canteen->ibuKantin->phone ?? '')); ?>'
                            )" class="text-blue-600 hover:text-blue-800 font-bold text-xs transition flex items-center gap-1">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button onclick="openDetailModal(<?php echo e($canteen->id); ?>, '<?php echo e(addslashes($canteen->name)); ?>', '<?php echo e(addslashes($canteen->ibuKantin->name ?? 'N/A')); ?>', <?php echo e($canteen->orders_count); ?>, <?php echo e($canteen->menus_count); ?>, <?php echo e($canteen->vouchers_count); ?>)"
                                class="text-slate-500 hover:text-slate-800 font-bold text-xs transition flex items-center gap-1">
                                <i class="fas fa-eye"></i> Detail
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full text-center py-16 bg-white rounded-3xl border border-slate-100 shadow-sm">
                    <i class="fas fa-store text-6xl text-slate-300 mb-4"></i>
                    <p class="text-slate-500 font-bold">Belum ada kantin terdaftar</p>
                    <button onclick="openAddCanteenModal()" class="mt-4 px-5 py-2.5 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition">
                        + Tambah Kantin Pertama
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-12">
            <div class="bg-white rounded-3xl shadow-sm p-6 border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl shadow-sm"><i class="fas fa-store"></i></div>
                <div>
                    <p class="text-slate-400 font-bold text-[10px] uppercase tracking-wider">Total Kantin</p>
                    <p class="text-2xl font-black text-slate-800"><?php echo e($canteens->count()); ?></p>
                </div>
            </div>
            <div class="bg-white rounded-3xl shadow-sm p-6 border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-xl shadow-sm"><i class="fas fa-utensils"></i></div>
                <div>
                    <p class="text-slate-400 font-bold text-[10px] uppercase tracking-wider">Total Menu</p>
                    <p class="text-2xl font-black text-slate-800"><?php echo e($canteens->sum('menus_count')); ?></p>
                </div>
            </div>
            <div class="bg-white rounded-3xl shadow-sm p-6 border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-xl shadow-sm"><i class="fas fa-ticket"></i></div>
                <div>
                    <p class="text-slate-400 font-bold text-[10px] uppercase tracking-wider">Total Voucher</p>
                    <p class="text-2xl font-black text-slate-800"><?php echo e($canteens->sum('vouchers_count')); ?></p>
                </div>
            </div>
            <div class="bg-white rounded-3xl shadow-sm p-6 border border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-xl shadow-sm"><i class="fas fa-shopping-bag"></i></div>
                <div>
                    <p class="text-slate-400 font-bold text-[10px] uppercase tracking-wider">Total Pesanan</p>
                    <p class="text-2xl font-black text-slate-800"><?php echo e($canteens->sum('orders_count')); ?></p>
                </div>
            </div>
        </div>

        <!-- Table View -->
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mt-12">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-lg font-black text-slate-800">📊 Tabel Rangkuman Kantin</h3>
            </div>
            <table class="w-full">
                <thead class="bg-slate-50 text-slate-500 border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Kantin</th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">Pemilik Kantin</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">Pesanan</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">Menu</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">Voucher</th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php $__currentLoopData = $canteens; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $canteen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-6 py-4 font-bold text-slate-800"><?php echo e($canteen->name); ?></td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-slate-800"><?php echo e($canteen->ibuKantin->name ?? '-'); ?></div>
                                <div class="text-xs text-slate-500"><?php echo e($canteen->ibuKantin->email ?? '-'); ?></div>
                            </td>
                            <td class="px-6 py-4 text-center"><span class="inline-block px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-bold"><?php echo e($canteen->orders_count); ?></span></td>
                            <td class="px-6 py-4 text-center"><span class="inline-block px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-bold"><?php echo e($canteen->menus_count); ?></span></td>
                            <td class="px-6 py-4 text-center"><span class="inline-block px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-xs font-bold"><?php echo e($canteen->vouchers_count); ?></span></td>
                            <td class="px-6 py-4 text-center"><span class="inline-flex items-center gap-1 px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-bold">✅ Aktif</span></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- ============== MODAL TAMBAH KANTIN ============== -->
<div id="addCanteenModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="bg-white rounded-[30px] shadow-2xl w-full max-w-lg my-8">
        <div class="bg-gradient-to-r from-green-500 to-green-700 text-white p-6 rounded-t-[30px] flex justify-between items-center">
            <div>
                <h2 class="text-xl font-black">🏪 Tambah Kantin Baru</h2>
                <p class="text-green-100 text-sm mt-0.5">Daftarkan kantin & akun pemilik kantin baru</p>
            </div>
            <button onclick="closeAddCanteenModal()" class="w-9 h-9 bg-white/20 hover:bg-white/40 rounded-full flex items-center justify-center transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form action="<?php echo e(route('admin.canteens.store')); ?>" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
            <?php echo csrf_field(); ?>
            
            <h3 class="font-bold text-[#122C4F] border-b pb-2 text-sm uppercase tracking-wider">🏪 Informasi Kantin</h3>
            
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Nama Kantin</label>
                <input type="text" name="canteen_name" placeholder="Contoh: Kantin Gedung B" required
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-green-400 outline-none text-sm font-medium">
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Lokasi / Gedung</label>
                <select name="location" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-green-400 outline-none text-sm font-medium">
                    <option value="">-- Pilih Lokasi --</option>
                    <option>Halaman Utama Kampus</option>
                    <option>Gedung A – Lantai 1</option>
                    <option>Gedung B – Lantai 1</option>
                    <option>Gedung C – Lantai Dasar</option>
                    <option>Gedung D – Basement</option>
                    <option>Area Parkir Selatan</option>
                    <option>Ruang Makan Mahasiswa</option>
                    <option>Kelas TK – Lantai 2</option>
                    <option>Lab Informatika – Lantai 3</option>
                    <option>Perpustakaan – Lantai 1</option>
                </select>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Foto Kantin (Optional)</label>
                <input type="file" name="photo" accept="image/*"
                    class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-green-400 outline-none text-sm font-medium file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
            </div>

            <h3 class="font-bold text-[#122C4F] border-b pb-2 pt-2 text-sm uppercase tracking-wider">👤 Akun Pemilik Kantin</h3>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Nama Pemilik Kantin</label>
                <input type="text" name="owner_name" placeholder="Contoh: Bu Sri" required
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-green-400 outline-none text-sm font-medium">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Username</label>
                    <input type="text" name="username" placeholder="Contoh: busri_kantin" required
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-green-400 outline-none text-sm font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">No. Telepon</label>
                    <input type="text" name="phone" placeholder="Contoh: 0812345678" required
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-green-400 outline-none text-sm font-medium">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Email</label>
                <input type="email" name="email" placeholder="ibukantin@example.com" required
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-green-400 outline-none text-sm font-medium">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Password</label>
                <input type="password" name="password" placeholder="Minimal 6 karakter" required
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-green-400 outline-none text-sm font-medium">
            </div>

            <div class="flex gap-3 pt-4 border-t">
                <button type="button" onclick="closeAddCanteenModal()"
                    class="flex-1 py-3 bg-gray-100 text-gray-700 font-bold rounded-2xl hover:bg-gray-200 transition text-sm">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-3 bg-green-600 text-white font-bold rounded-2xl hover:bg-green-700 transition text-sm flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============== MODAL EDIT KANTIN ============== -->
<div id="editCanteenModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4 overflow-y-auto">
    <div class="bg-white rounded-[30px] shadow-2xl w-full max-w-lg my-8">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6 rounded-t-[30px] flex justify-between items-center">
            <div>
                <h2 class="text-xl font-black">✏️ Edit Data Kantin</h2>
                <p class="text-blue-100 text-sm mt-0.5">Ubah informasi kantin & akun pemilik kantin</p>
            </div>
            <button onclick="closeEditCanteenModal()" class="w-9 h-9 bg-white/20 hover:bg-white/40 rounded-full flex items-center justify-center transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="editCanteenForm" action="" method="POST" enctype="multipart/form-data" class="p-6 space-y-4 max-h-[75vh] overflow-y-auto">
            <?php echo csrf_field(); ?>
            
            <h3 class="font-bold text-blue-800 border-b pb-2 text-sm uppercase tracking-wider">🏪 Informasi Kantin</h3>
            
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Nama Kantin</label>
                <input type="text" id="editCanteenName" name="canteen_name" required
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-400 outline-none text-sm font-medium">
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Lokasi / Gedung</label>
                <select id="editCanteenLocation" name="location" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-400 outline-none text-sm font-medium">
                    <option value="">-- Pilih Lokasi --</option>
                    <option>Halaman Utama Kampus</option>
                    <option>Gedung A – Lantai 1</option>
                    <option>Gedung B – Lantai 1</option>
                    <option>Gedung C – Lantai Dasar</option>
                    <option>Gedung D – Basement</option>
                    <option>Area Parkir Selatan</option>
                    <option>Ruang Makan Mahasiswa</option>
                    <option>Kelas TK – Lantai 2</option>
                    <option>Lab Informatika – Lantai 3</option>
                    <option>Perpustakaan – Lantai 1</option>
                </select>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Ganti Foto Kantin (Optional)</label>
                <input type="file" name="photo" accept="image/*"
                    class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-400 outline-none text-sm font-medium file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <h3 class="font-bold text-blue-800 border-b pb-2 pt-2 text-sm uppercase tracking-wider">👤 Akun Pemilik Kantin</h3>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Nama Pemilik Kantin</label>
                <input type="text" id="editOwnerName" name="owner_name" required
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-400 outline-none text-sm font-medium">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Username</label>
                    <input type="text" id="editUsername" name="username" required
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-400 outline-none text-sm font-medium">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">No. Telepon</label>
                    <input type="text" id="editPhone" name="phone" required
                        class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-400 outline-none text-sm font-medium">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Email</label>
                <input type="email" id="editEmail" name="email" required
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-400 outline-none text-sm font-medium">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Ganti Foto Profil Pemilik (Optional)</label>
                <input type="file" name="owner_avatar" accept="image/*"
                    class="w-full px-4 py-2 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-400 outline-none text-sm font-medium file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Ganti Password (Optional)</label>
                <input type="password" name="password" placeholder="Kosongkan jika tidak ingin diubah"
                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-400 outline-none text-sm font-medium">
            </div>

            <div class="flex gap-3 pt-4 border-t">
                <button type="button" onclick="closeEditCanteenModal()"
                    class="flex-1 py-3 bg-gray-100 text-gray-700 font-bold rounded-2xl hover:bg-gray-200 transition text-sm">
                    Batal
                </button>
                <button type="submit"
                    class="flex-1 py-3 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition text-sm flex items-center justify-center gap-2">
                    <i class="fas fa-save"></i> Update
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ============== MODAL DETAIL KANTIN ============== -->
<div id="detailCanteenModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="bg-white rounded-[30px] shadow-2xl w-full max-w-md">
        <div class="bg-gradient-to-r from-[#122C4F] to-[#1e4a78] text-white p-6 rounded-t-[30px] flex justify-between items-center">
            <h2 class="text-xl font-black" id="detailCanteenTitle">—</h2>
            <button onclick="closeDetailModal()" class="w-9 h-9 bg-white/20 hover:bg-white/40 rounded-full flex items-center justify-center transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="p-6 space-y-4">
            <div class="bg-gray-50 rounded-2xl p-4 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 font-medium">Pemilik Kantin</span>
                    <span class="font-bold text-gray-800" id="detailIbuKantin">—</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 font-medium">Total Pesanan</span>
                    <span class="font-bold text-blue-600" id="detailOrders">—</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 font-medium">Total Menu</span>
                    <span class="font-bold text-green-600" id="detailMenus">—</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 font-medium">Total Voucher</span>
                    <span class="font-bold text-purple-600" id="detailVouchers">—</span>
                </div>
            </div>
            <button onclick="closeDetailModal()" class="w-full py-3 bg-[#122C4F] text-white font-bold rounded-2xl hover:bg-[#1e4a78] transition">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
// ---- Add Canteen ----
function openAddCanteenModal() {
    document.getElementById('addCanteenModal').classList.remove('hidden');
    document.getElementById('addCanteenModal').classList.add('flex');
}
function closeAddCanteenModal() {
    document.getElementById('addCanteenModal').classList.add('hidden');
    document.getElementById('addCanteenModal').classList.remove('flex');
}

// ---- Edit Canteen ----
function openEditCanteenModal(id, name, location, ownerName, username, email, phone) {
    document.getElementById('editCanteenForm').action = '/admin/canteens/' + id + '/update';
    document.getElementById('editCanteenName').value = name;
    
    // Set select location
    const selectLoc = document.getElementById('editCanteenLocation');
    selectLoc.value = '';
    for(let option of selectLoc.options) {
        if(option.value === location) {
            selectLoc.value = location;
            break;
        }
    }
    
    document.getElementById('editOwnerName').value = ownerName;
    document.getElementById('editUsername').value = username;
    document.getElementById('editEmail').value = email;
    document.getElementById('editPhone').value = phone;
    
    document.getElementById('editCanteenModal').classList.remove('hidden');
    document.getElementById('editCanteenModal').classList.add('flex');
}
function closeEditCanteenModal() {
    document.getElementById('editCanteenModal').classList.add('hidden');
    document.getElementById('editCanteenModal').classList.remove('flex');
}

// ---- Detail Modal ----
function openDetailModal(id, name, ibuKantin, orders, menus, vouchers) {
    document.getElementById('detailCanteenTitle').textContent = '🏪 ' + name;
    document.getElementById('detailIbuKantin').textContent = ibuKantin;
    document.getElementById('detailOrders').textContent   = orders + ' pesanan';
    document.getElementById('detailMenus').textContent    = menus  + ' menu';
    document.getElementById('detailVouchers').textContent = vouchers + ' voucher';
    document.getElementById('detailCanteenModal').classList.remove('hidden');
    document.getElementById('detailCanteenModal').classList.add('flex');
}
function closeDetailModal() {
    document.getElementById('detailCanteenModal').classList.add('hidden');
    document.getElementById('detailCanteenModal').classList.remove('flex');
}

// Backdrop klik
['addCanteenModal','editCanteenModal','detailCanteenModal'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden'), this.classList.remove('flex');
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\PROJECT WEB\WEB KANTIN\hulahup-localhost\resources\views/admin/canteens/index.blade.php ENDPATH**/ ?>