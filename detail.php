<?php
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT books.*, categories.name as category_name FROM books LEFT JOIN categories ON books.category_id = categories.id WHERE books.id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch();

if (!$book) {
    echo "<div class='container mx-auto px-4 py-12 text-center'><h2 class='text-2xl font-bold'>Buku tidak ditemukan</h2><a href='catalog.php' class='text-brick_red mt-4 inline-block'>Kembali ke Katalog</a></div>";
    require_once 'includes/footer.php';
    exit;
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="catalog.php" class="text-gray-500 hover:text-blue-900 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Katalog
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="md:flex">
            <!-- Cover Image -->
            <div class="md:w-1/3 bg-gray-100 p-8 flex items-start justify-center">
                <?php if ($book['cover_image']): ?>
                    <img src="uploads/covers/<?= htmlspecialchars($book['cover_image']) ?>" alt="<?= htmlspecialchars($book['title']) ?>" class="shadow-xl rounded max-w-full h-auto object-contain" style="max-height: 400px;">
                <?php else: ?>
                    <div class="w-64 h-80 bg-gray-200 flex items-center justify-center text-gray-400 rounded shadow-inner">
                        <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Details -->
            <div class="md:w-2/3 p-8">
                <div class="flex items-center space-x-2 mb-2">
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-bold rounded-full uppercase"><?= $book['category_name'] ? htmlspecialchars($book['category_name']) : 'Umum' ?></span>
                    <?php if ($book['type'] == 'digital'): ?>
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-bold rounded-full uppercase flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Digital (PDF)
                        </span>
                    <?php else: ?>
                        <span class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-bold rounded-full uppercase flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            Fisik
                        </span>
                    <?php endif; ?>
                </div>

                <h1 class="text-3xl font-bold text-gray-900 mb-2"><?= htmlspecialchars($book['title']) ?></h1>
                <p class="text-xl text-gray-600 mb-6">oleh <span class="font-semibold text-gray-800"><?= htmlspecialchars($book['author']) ?></span></p>

                <div class="grid grid-cols-2 gap-4 mb-6 text-sm border-t border-b border-gray-100 py-4">
                    <div>
                        <span class="block text-gray-500">Penerbit</span>
                        <span class="font-medium text-gray-900"><?= htmlspecialchars($book['publisher'] ?? '-') ?></span>
                    </div>
                    <div>
                        <span class="block text-gray-500">Tahun Terbit</span>
                        <span class="font-medium text-gray-900"><?= htmlspecialchars($book['year'] ?? '-') ?></span>
                    </div>
                    <?php if ($book['type'] == 'physical'): ?>
                    <div>
                        <span class="block text-gray-500">Stok Tersedia</span>
                        <span class="font-medium text-gray-900"><?= (int)$book['stock'] ?> Buku</span>
                    </div>
                    <div>
                        <span class="block text-gray-500">Lokasi Rak</span>
                        <span class="font-medium text-gray-900">R-<?= substr(md5($book['id']), 0, 3) ?></span> <!-- Dummy rak -->
                    </div>
                    <?php endif; ?>
                </div>

                <div class="mb-8">
                    <h3 class="font-bold text-lg mb-2">Sinopsis</h3>
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">
                        <?= nl2br(htmlspecialchars($book['synopsis'] ?? 'Tidak ada sinopsis tersedia.')) ?>
                    </p>
                </div>

                <?php if ($book['type'] == 'digital' && $book['file_path']): ?>
                    <div>
                        <a href="read.php?id=<?= $book['id'] ?>" class="bg-brick_red hover:bg-brick_red-600 text-white font-bold py-3 px-8 rounded shadow-lg flex items-center transition inline-flex">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <span>Buka PDF Full Satu Halaman</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>