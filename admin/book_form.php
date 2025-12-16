<?php
require_once 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$book = null;

// Pre-fill data from GET params (e.g. from OpenLibrary Import)
$pre_title = isset($_GET['title']) ? $_GET['title'] : '';
$pre_author = isset($_GET['author']) ? $_GET['author'] : '';
$pre_publisher = isset($_GET['publisher']) ? $_GET['publisher'] : '';
$pre_year = isset($_GET['year']) ? $_GET['year'] : date('Y');
$pre_cover = isset($_GET['cover']) ? $_GET['cover'] : '';

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->execute([$id]);
    $book = $stmt->fetch();
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();
?>

<div class="mb-6">
    <div class="flex items-center text-sm text-gray-500 mb-2">
        <a href="books.php" class="hover:text-blue-600">Buku</a>
        <svg class="h-4 w-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span><?= $book ? 'Edit Buku' : 'Tambah Buku' ?></span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800"><?= $book ? 'Edit Buku' : 'Tambah Buku Baru' ?></h1>
</div>

<div class="bg-white rounded-lg shadow p-8">
    <form action="book_action.php" method="POST" enctype="multipart/form-data">
        <?php if ($book): ?>
            <input type="hidden" name="id" value="<?= $book['id'] ?>">
        <?php endif; ?>
        <?php if ($pre_cover): ?>
            <input type="hidden" name="remote_cover_url" value="<?= htmlspecialchars($pre_cover) ?>">
        <?php endif; ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Judul Buku</label>
                    <input type="text" name="title" value="<?= $book ? htmlspecialchars($book['title']) : htmlspecialchars($pre_title) ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Penulis</label>
                    <input type="text" name="author" value="<?= $book ? htmlspecialchars($book['author']) : htmlspecialchars($pre_author) ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Penerbit</label>
                        <input type="text" name="publisher" list="publisher_list" value="<?= $book ? htmlspecialchars($book['publisher']) : htmlspecialchars($pre_publisher) ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" autocomplete="off" placeholder="Pilih atau ketik penerbit baru...">
                        <datalist id="publisher_list">
                            <?php foreach ($publishers as $pub): ?>
                                <option value="<?= htmlspecialchars($pub) ?>">
                            <?php endforeach; ?>
                        </datalist>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Tahun</label>
                        <input type="number" name="year" value="<?= $book ? htmlspecialchars($book['year']) : htmlspecialchars($pre_year) ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
                    <select name="category_id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        <option value="">Pilih Kategori</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($book && $book['category_id'] == $cat['id']) ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Sinopsis</label>
                    <textarea name="synopsis" rows="5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"><?= $book ? htmlspecialchars($book['synopsis']) : '' ?></textarea>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4" x-data="{ type: '<?= $book ? $book['type'] : 'physical' ?>' }">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Cover Buku</label>
                    <?php if ($book && $book['cover_image']): ?>
                        <div class="mb-2">
                            <img src="../uploads/covers/<?= htmlspecialchars($book['cover_image']) ?>" class="h-32 object-cover rounded shadow">
                            <p class="text-xs text-gray-500 mt-1">Cover saat ini</p>
                        </div>
                    <?php elseif ($pre_cover): ?>
                        <div class="mb-2">
                            <img src="<?= htmlspecialchars($pre_cover) ?>" class="h-32 object-cover rounded shadow">
                            <p class="text-xs text-green-600 mt-1 font-semibold">Cover dari OpenLibrary akan disimpan otomatis</p>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="cover_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Tipe Buku</label>
                    <div class="mt-2">
                        <label class="inline-flex items-center">
                            <input type="radio" class="form-radio" name="type" value="physical" x-model="type">
                            <span class="ml-2">Fisik</span>
                        </label>
                        <label class="inline-flex items-center ml-6">
                            <input type="radio" class="form-radio" name="type" value="digital" x-model="type">
                            <span class="ml-2">Digital (E-Book)</span>
                        </label>
                    </div>
                </div>

                <div x-show="type === 'physical'">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Stok Buku</label>
                    <input type="number" name="stock" value="<?= $book ? $book['stock'] : '1' ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div x-show="type === 'digital'" class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Upload File PDF</label>
                    <input type="file" name="pdf_file" accept=".pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="text-xs text-gray-500 mt-2">Format wajib .pdf. <?= $book && $book['file_path'] ? 'Biarkan kosong jika tidak ingin mengganti file.' : '' ?></p>
                    <?php if ($book && $book['file_path']): ?>
                        <div class="mt-2 text-sm text-green-600">File saat ini: <?= htmlspecialchars($book['file_path']) ?></div>
                    <?php endif; ?>
                </div>

                <div class="border-t pt-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Cover Buku</label>
                    <?php if ($book && $book['cover_image']): ?>
                        <div class="mb-2">
                            <img src="../uploads/covers/<?= htmlspecialchars($book['cover_image']) ?>" class="h-32 object-cover rounded">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="cover_image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end mt-8">
            <a href="books.php" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-4 transition">Batal</a>
            <button type="submit" class="bg-blue-900 hover:bg-blue-800 text-white font-bold py-2 px-6 rounded transition">
                <?= $book ? 'Update Buku' : 'Simpan Buku' ?>
            </button>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>