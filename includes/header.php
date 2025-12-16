<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
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
                        baby_pink: { DEFAULT: '#ff99c8', 100: '#ffebf4', 200: '#ffd6e9', 300: '#ffc2de', 400: '#ffadd3', 500: '#ff99c8', 600: '#ff479d', 700: '#f50072', 800: '#a3004c', 900: '#520026' },
                        lemon_chiffon: { DEFAULT: '#fcf6bd', 100: '#fefdf1', 200: '#fefbe4', 300: '#fdf9d6', 400: '#fcf7c8', 500: '#fcf6bd', 600: '#f8e967', 700: '#f4dd13', 800: '#a89808', 900: '#544c04' },
                        frosted_mint: { DEFAULT: '#d0f4de', 100: '#f6fdf9', 200: '#edfbf2', 300: '#e4f9ec', 400: '#dbf6e5', 500: '#d0f4de', 600: '#88e3ab', 700: '#3ed277', 800: '#22934e', 900: '#114a27' },
                        icy_blue: { DEFAULT: '#a9def9', 100: '#eef8fe', 200: '#ddf2fd', 300: '#ccebfb', 400: '#bae5fa', 500: '#a9def9', 600: '#5bc1f4', 700: '#10a2eb', 800: '#0b6c9c', 900: '#05364e' },
                        mauve: { DEFAULT: '#e4c1f9', 100: '#faf3fe', 200: '#f5e7fd', 300: '#efdbfb', 400: '#eacffa', 500: '#e4c1f9', 600: '#c272f1', 700: '#a021e9', 800: '#6c10a2', 900: '#360851' }
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
<body class="bg-frosted_mint-100 text-gray-800 flex flex-col min-h-screen">
    <nav class="bg-mauve-900 text-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="/lib/index.php" class="text-2xl font-bold flex items-center space-x-2">
                    <?php if ($logo_mode !== 'text_only'): ?>
                        <?php if ($site_logo): ?>
                            <img src="/lib/uploads/logo/<?= htmlspecialchars($site_logo) ?>" class="h-10 w-auto object-contain" alt="Logo">
                        <?php else: ?>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-baby_pink-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        <?php endif; ?>
                    <?php endif; ?>
                    
                    <?php if ($logo_mode !== 'logo_only'): ?>
                        <span><?= htmlspecialchars($site_name) ?></span>
                    <?php endif; ?>
                </a>
                <div class="hidden md:flex space-x-6 items-center">
                    <a href="/lib/index.php" class="hover:text-lemon_chiffon-400 transition">Beranda</a>
                    <a href="/lib/catalog.php" class="hover:text-lemon_chiffon-400 transition">Katalog Lokal</a>
                    <a href="/lib/news.php" class="hover:text-lemon_chiffon-400 transition">Berita</a>
                    <a href="/lib/search_global.php" class="hover:text-lemon_chiffon-400 transition">Pencarian Global</a>
                    <a href="/lib/info.php" class="hover:text-lemon_chiffon-400 transition">Info</a>
                    
                    <?php if (isset($_SESSION['member_id'])): ?>
                        <a href="/lib/member/index.php" class="hover:text-lemon_chiffon-400 transition font-bold">Dashboard</a>
                        <a href="/lib/logout.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full transition shadow-md">Keluar</a>
                    <?php else: ?>
                        <a href="/lib/login.php" class="hover:text-lemon_chiffon-400 transition">Masuk</a>
                        <a href="/lib/register.php" class="bg-baby_pink-600 hover:bg-baby_pink-700 text-white px-4 py-2 rounded-full transition shadow-md">Keanggotaan</a>
                    <?php endif; ?>
                </div>
                <!-- Mobile Menu Button -->
                <div class="md:hidden" x-data="{ open: false }">
                    <button @click="open = !open" class="focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <div x-show="open" class="absolute top-16 right-0 bg-mauve-900 w-full shadow-lg z-50 text-white">
                        <div class="flex flex-col p-4 space-y-2">
                            <a href="/lib/index.php" class="block hover:text-lemon_chiffon-400">Beranda</a>
                            <a href="/lib/catalog.php" class="block hover:text-lemon_chiffon-400">Katalog Lokal</a>
                            <a href="/lib/news.php" class="block hover:text-lemon_chiffon-400">Berita</a>
                            <a href="/lib/search_global.php" class="block hover:text-lemon_chiffon-400">Pencarian Global</a>
                            <a href="/lib/info.php" class="block hover:text-lemon_chiffon-400">Info</a>
                            
                            <?php if (isset($_SESSION['member_id'])): ?>
                                <a href="/lib/member/index.php" class="block text-baby_pink-500 font-bold">Dashboard</a>
                                <a href="/lib/logout.php" class="block text-red-400">Keluar</a>
                            <?php else: ?>
                                <a href="/lib/login.php" class="block hover:text-lemon_chiffon-400">Masuk</a>
                                <a href="/lib/register.php" class="block text-baby_pink-500 font-bold">Keanggotaan</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <main class="flex-grow">
