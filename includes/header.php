<?php
$site_name = function_exists('getSetting') && isset($pdo) ? getSetting($pdo, 'library_name', 'Perpustakaan Hybrid') : 'Perpustakaan Hybrid';
$site_logo = function_exists('getSetting') && isset($pdo) ? getSetting($pdo, 'library_logo') : '';
$logo_mode = function_exists('getSetting') && isset($pdo) ? getSetting($pdo, 'logo_display_mode', 'logo_text') : 'logo_text';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        molten_lava: { DEFAULT: '#780000', 100: '#180000', 200: '#310000', 300: '#490000', 400: '#620000', 500: '#780000', 600: '#c80000', 700: '#ff1616', 800: '#ff6464', 900: '#ffb1b1' },
                        brick_red: { DEFAULT: '#c1121f', 100: '#260406', 200: '#4d070c', 300: '#730b12', 400: '#990e17', 500: '#c1121f', 600: '#eb2330', 700: '#f05a64', 800: '#f59198', 900: '#fac8cb' },
                        papaya_whip: { DEFAULT: '#fdf0d5', 100: '#593c04', 200: '#b17908', 300: '#f5ae22', 400: '#f9cf7b', 500: '#fdf0d5', 600: '#fdf2dc', 700: '#fef5e5', 800: '#fef9ed', 900: '#fffcf6' },
                        deep_space_blue: { DEFAULT: '#003049', 100: '#00090e', 200: '#00131d', 300: '#001c2b', 400: '#002539', 500: '#003049', 600: '#00679f', 700: '#00a0f7', 800: '#50c2ff', 900: '#a7e0ff' },
                        steel_blue: { DEFAULT: '#669bbc', 100: '#122028', 200: '#233f51', 300: '#355f79', 400: '#477fa2', 500: '#669bbc', 600: '#85afc9', 700: '#a4c3d7', 800: '#c2d7e4', 900: '#e1ebf2' }
                    }
                }
            }
        }
    </script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-papaya_whip-900 text-gray-800 flex flex-col min-h-screen">
    <nav class="bg-deep_space_blue text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="/lib/index.php" class="text-2xl font-bold flex items-center space-x-2">
                    <?php if ($logo_mode !== 'text_only'): ?>
                        <?php if ($site_logo): ?>
                            <img src="/lib/uploads/logo/<?= htmlspecialchars($site_logo) ?>" class="h-10 w-auto object-contain" alt="Logo">
                        <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-papaya_whip" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php if ($logo_mode !== 'logo_only'): ?>
                        <span><?= htmlspecialchars($site_name) ?></span>
                    <?php endif; ?>
                </a>
                <div class="hidden md:flex space-x-6">
                    <a href="/lib/index.php" class="hover:text-papaya_whip transition">Beranda</a>
                    <a href="/lib/catalog.php" class="hover:text-papaya_whip transition">Katalog Lokal</a>
                    <a href="/lib/search_global.php" class="hover:text-papaya_whip transition">Pencarian Global</a>
                    <a href="/lib/info.php" class="hover:text-papaya_whip transition">Info</a>
                </div>
                <!-- Mobile Menu Button -->
                <div class="md:hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div x-show="open" class="absolute top-16 right-0 bg-deep_space_blue w-full shadow-lg z-50">
                        <div class="flex flex-col p-4 space-y-2">
                            <a href="/lib/index.php" class="block hover:text-papaya_whip">Beranda</a>
                            <a href="/lib/catalog.php" class="block hover:text-papaya_whip">Katalog Lokal</a>
                            <a href="/lib/news.php" class="block hover:text-papaya_whip">Berita</a>
                            <a href="/lib/search_global.php" class="block hover:text-papaya_whip">Pencarian Global</a>
                            <a href="/lib/info.php" class="block hover:text-papaya_whip">Info</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <main class="flex-grow">
