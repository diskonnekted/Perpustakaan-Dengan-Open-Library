    </main>
    <?php
    $footer_name = isset($site_name) ? $site_name : (function_exists('getSetting') && isset($pdo) ? getSetting($pdo, 'library_name', 'Perpustakaan Hybrid') : 'Perpustakaan Hybrid');
    $footer_addr = function_exists('getSetting') && isset($pdo) ? getSetting($pdo, 'library_address', 'Jl. Pendidikan No. 123, Jakarta') : 'Jl. Pendidikan No. 123, Jakarta';
    $footer_email = function_exists('getSetting') && isset($pdo) ? getSetting($pdo, 'library_email', 'info@perpustakaan.id') : 'info@perpustakaan.id';
    ?>
    <footer class="bg-mauve-900 text-white py-8">
        <div class="container mx-auto px-4 grid md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-xl font-bold text-white mb-4"><?= htmlspecialchars($footer_name) ?></h3>
                <p class="text-sm">
                    Membuka jendela dunia melalui koleksi fisik dan digital.
                    Belajar tanpa batas, di mana saja dan kapan saja.
                </p>
            </div>
            <div>
                <h3 class="text-xl font-bold text-white mb-4">Kontak Kami</h3>
                <ul class="space-y-2 text-sm">
                    <li class="flex items-center">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        <?= htmlspecialchars($footer_addr) ?>
                    </li>
                    <li class="flex items-center">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        <?= htmlspecialchars($footer_email) ?>
                    </li>
                </ul>
            </div>
            <div>
                <h3 class="text-xl font-bold text-white mb-4">Jam Operasional</h3>
                <ul class="space-y-2 text-sm">
                    <li>Senin - Jumat: 08:00 - 16:00</li>
                    <li>Sabtu: 08:00 - 12:00</li>
                    <li>Minggu: Tutup</li>
                </ul>
            </div>
        </div>
        <div class="text-center text-sm mt-8 pt-8 border-t border-mauve-800">
            &copy; <?php echo date('Y'); ?> <?= htmlspecialchars($footer_name) ?>. All rights reserved<a href="/lib/admin/index.php" class="text-lemon_chiffon-400 hover:text-lemon_chiffon-300">.</a>
        </div>
    </footer>
</body>
</html>