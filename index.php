<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

// Ambil 20 buku terbaru
$stmt = $pdo->query("SELECT * FROM books ORDER BY created_at DESC LIMIT 20");
$recentBooks = $stmt->fetchAll();

// Ambil 8 Kategori (untuk icon section)
$stmt = $pdo->query("SELECT * FROM categories ORDER BY name LIMIT 8");
$featuredCategories = $stmt->fetchAll();

// Ambil Pengaturan
$hero_title = function_exists('getSetting') ? getSetting($pdo, 'hero_title', "Jelajahi Dunia Ilmu Pengetahuan") : "Jelajahi Dunia Ilmu Pengetahuan";
$hero_subtitle = function_exists('getSetting') ? getSetting($pdo, 'hero_subtitle', "Koleksi buku fisik dan digital lengkap untuk kebutuhan belajar Anda.") : "Koleksi buku fisik dan digital lengkap untuk kebutuhan belajar Anda.";
$hero_image = function_exists('getSetting') ? getSetting($pdo, 'hero_image', '') : '';
?>

<!-- Hero Section Modern -->
<div class="relative bg-mauve-100 overflow-hidden">
    <!-- Background Image with Overlay -->
    <?php if ($hero_image): ?>
        <div class="absolute inset-0 z-0">
            <img src="uploads/hero/<?= htmlspecialchars($hero_image) ?>" alt="Library Background" class="w-full h-full object-cover opacity-30">
        </div>
    <?php else: ?>
        <div class="absolute inset-0 bg-gradient-to-r from-mauve-50 to-mauve-200 z-0"></div>
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?ixlib=rb-1.2.1&auto=format&fit=crop&w=1950&q=80')] bg-cover bg-center opacity-10 mix-blend-overlay"></div>
    <?php endif; ?>

    <!-- Content -->
    <div class="relative z-10 container mx-auto px-4 py-32 md:py-48 flex flex-col items-center text-center">
        <span class="inline-block py-1 px-3 rounded-full bg-lemon_chiffon-400 text-mauve-900 text-sm font-bold mb-6 animate-fade-in-up">
            Selamat Datang di <?= isset($site_name) ? htmlspecialchars($site_name) : 'Perpustakaan Digital' ?>
        </span>
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-extrabold text-mauve-900 mb-6 leading-tight max-w-4xl tracking-tight">
            <?= htmlspecialchars($hero_title) ?>
        </h1>
        <p class="text-lg md:text-2xl text-mauve-800 mb-10 max-w-2xl font-light">
            <?= htmlspecialchars($hero_subtitle) ?>
        </p>
        
        <!-- Search Bar Modern -->
        <div class="w-full max-w-3xl bg-white/40 backdrop-blur-md p-2 rounded-2xl shadow-2xl border border-white/50">
            <form action="catalog.php" method="GET" class="flex flex-col md:flex-row gap-2">
                <div class="relative flex-grow group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-6 w-6 text-mauve-700 group-focus-within:text-baby_pink-600 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input type="text" name="q" 
                           class="w-full bg-white/70 text-mauve-900 placeholder-mauve-700 border-none rounded-xl py-4 pl-12 pr-4 focus:ring-2 focus:ring-baby_pink-400 focus:bg-white transition text-lg" 
                           placeholder="Cari judul buku, penulis, atau topik...">
                </div>
                <button type="submit" class="bg-baby_pink-600 hover:bg-baby_pink-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg transform hover:scale-105 transition duration-200 flex items-center justify-center">
                    <span>Cari Buku</span>
                    <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </button>
            </form>
        </div>
    </div>
    
    <!-- Decorative Shapes -->
    <div class="absolute bottom-0 left-0 right-0">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" class="text-white fill-current">
            <path fill-opacity="1" d="M0,96L48,112C96,128,192,160,288,160C384,160,480,128,576,112C672,96,768,96,864,112C960,128,1056,160,1152,160C1248,160,1344,128,1392,112L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>
</div>

<!-- Categories Section -->
<section class="pb-20 -mt-24 relative z-20">
    <div class="container mx-auto px-4">
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8 max-w-6xl mx-auto">
            <?php 
            // Mapping kategori ke icon SVG yang lebih menarik
            $icons = [
                'Fiksi' => '<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>',
                'Sains' => '<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>',
                'Sejarah' => '<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
                'Teknologi' => '<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
                'Biografi' => '<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>',
                'Bisnis' => '<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
                'Seni' => '<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>',
                'Agama' => '<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>',
                'Default' => '<svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>'
            ];
            
            $colors = [
                'bg-blue-100 text-blue-600',
                'bg-green-100 text-green-600',
                'bg-purple-100 text-purple-600',
                'bg-orange-100 text-orange-600',
                'bg-pink-100 text-pink-600',
                'bg-indigo-100 text-indigo-600',
                'bg-teal-100 text-teal-600',
                'bg-red-100 text-red-600',
            ];

            $i = 0;
            foreach ($featuredCategories as $cat): 
                $icon = isset($icons[$cat['name']]) ? $icons[$cat['name']] : $icons['Default'];
                // Cycle colors
                $colorClass = $colors[$i % count($colors)];
                $i++;
            ?>
            <a href="catalog.php?category_id=<?= $cat['id'] ?>" class="group block h-full">
                <div class="bg-white rounded-2xl p-6 transition duration-300 shadow-sm hover:shadow-xl border border-gray-100 hover:border-blue-200 group-hover:-translate-y-2 flex flex-col items-center justify-center text-center h-full relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-gray-50 rounded-full opacity-50 group-hover:scale-150 transition duration-500"></div>
                    
                    <div class="w-20 h-20 rounded-2xl <?= $colorClass ?> flex items-center justify-center mb-5 group-hover:scale-110 transition duration-300 shadow-inner">
                        <?= $icon ?>
                    </div>
                    
                    <h3 class="font-bold text-gray-800 text-xl group-hover:text-blue-600 transition"><?= htmlspecialchars($cat['name']) ?></h3>
                    <p class="text-sm text-gray-400 mt-2 group-hover:text-blue-500 transition flex items-center">
                        Jelajahi 
                        <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </p>
                </div>
            </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- Recent Books Modern -->
<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex flex-col md:flex-row justify-between items-end mb-12">
            <div>
                <span class="text-icy_blue-600 font-bold tracking-wider uppercase text-sm">Terbaru Ditambahkan</span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mt-2">Koleksi Pilihan Minggu Ini</h2>
            </div>
            <a href="catalog.php" class="mt-4 md:mt-0 inline-flex items-center font-semibold text-icy_blue-600 hover:text-icy_blue-800 transition">
                Lihat Semua Koleksi
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
        
        <?php if (count($recentBooks) > 0): ?>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-8 gap-4">
            <?php foreach ($recentBooks as $book): ?>
            <div class="group bg-white rounded-xl shadow-md overflow-hidden hover:shadow-2xl transition duration-300 transform hover:-translate-y-1 flex flex-col h-full">
                <div class="aspect-[2/3] bg-gray-100 overflow-hidden relative">
                    <?php if ($book['cover_image']): ?>
                        <img src="uploads/covers/<?= htmlspecialchars($book['cover_image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center text-gray-400 bg-gray-200">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Status Ribbon -->
                    <?php if ($book['type'] == 'physical' && $book['stock'] <= 0): ?>
                         <div class="absolute top-0 left-0 z-10">
                             <div class="bg-red-600 text-white text-[10px] font-bold px-6 py-1 shadow-md transform -rotate-45 -translate-x-7 translate-y-4 w-32 text-center border-2 border-white">
                                 DIPINJAM
                             </div>
                         </div>
                    <?php endif; ?>

                    <div class="absolute top-0 right-0 p-3">
                        <?php if ($book['type'] == 'digital'): ?>
                            <span class="bg-green-500/90 backdrop-blur-sm text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">E-Book</span>
                        <?php else: ?>
                            <span class="bg-mauve-800/90 backdrop-blur-sm text-white text-xs font-bold px-3 py-1 rounded-full shadow-sm">Fisik</span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Hover Action -->
                    <div class="absolute inset-0 bg-black/60 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                        <a href="detail.php?id=<?= $book['id'] ?>" class="bg-white text-gray-900 font-bold py-2 px-6 rounded-full transform scale-90 group-hover:scale-100 transition duration-300">
                            Lihat Detail
                        </a>
                    </div>
                </div>
                <div class="p-3 flex-grow flex flex-col">
                    <h3 class="font-bold text-sm mb-1 text-gray-900 line-clamp-2 leading-tight group-hover:text-baby_pink-600 transition" title="<?= htmlspecialchars($book['title']) ?>"><?= htmlspecialchars($book['title']) ?></h3>
                    <p class="text-xs text-gray-500 line-clamp-1" title="<?= htmlspecialchars($book['author']) ?>"><?= htmlspecialchars($book['author']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <div class="text-center py-12 text-gray-500 bg-white rounded-xl shadow-sm border border-gray-100">
                <p>Belum ada buku yang ditambahkan.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Call to Action -->
<section class="py-20 bg-mauve-900 text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
    <div class="container mx-auto px-4 text-center relative z-10">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">Bergabunglah dengan Komunitas Pembaca Kami</h2>
        <p class="text-xl text-lemon_chiffon-200 mb-8 max-w-2xl mx-auto">Dapatkan akses ke ribuan koleksi buku dan jurnal terbaru. Tingkatkan pengetahuan Anda bersama kami.</p>
        <a href="catalog.php" class="bg-baby_pink-600 hover:bg-baby_pink-700 text-white font-bold py-3 px-8 rounded-full shadow-lg transition transform hover:scale-105 inline-block">
            Mulai Membaca Sekarang
        </a>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
