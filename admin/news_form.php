<?php
require_once 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$news = null;
$tags_string = '';

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM news WHERE id = ?");
    $stmt->execute([$id]);
    $news = $stmt->fetch();

    if (!$news) {
        redirect('news.php');
    }

    // Get Tags
    $stmt = $pdo->prepare("SELECT t.name FROM news_tags t JOIN news_tag_map m ON t.id = m.tag_id WHERE m.news_id = ?");
    $stmt->execute([$id]);
    $tags = $stmt->fetchAll(PDO::FETCH_COLUMN);
    $tags_string = implode(', ', $tags);
}

// Get Categories
$categories = $pdo->query("SELECT * FROM news_categories ORDER BY name")->fetchAll();
?>

<div class="mb-6">
    <div class="flex items-center text-sm text-gray-500 mb-2">
        <a href="news.php" class="hover:text-blue-900">Berita</a>
        <svg class="h-4 w-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span><?= $news ? 'Edit Berita' : 'Tambah Berita' ?></span>
    </div>
    <h1 class="text-3xl font-bold text-gray-800"><?= $news ? 'Edit Berita' : 'Tambah Berita' ?></h1>
</div>

<form action="news_action.php" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-lg overflow-hidden">
    <?php if ($news): ?>
        <input type="hidden" name="id" value="<?= $news['id'] ?>">
    <?php endif; ?>

    <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="md:col-span-2 space-y-6">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Judul Berita</label>
                <input type="text" name="title" value="<?= $news ? htmlspecialchars($news['title']) : '' ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Konten</label>
                <textarea name="content" rows="15" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required><?= $news ? htmlspecialchars($news['content']) : '' ?></textarea>
                <p class="text-xs text-gray-500 mt-1">Gunakan enter untuk paragraf baru.</p>
            </div>
        </div>

        <!-- Sidebar Options -->
        <div class="md:col-span-1 space-y-6">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                <select name="status" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <option value="published" <?= ($news && $news['status'] == 'published') ? 'selected' : '' ?>>Published</option>
                    <option value="draft" <?= ($news && $news['status'] == 'draft') ? 'selected' : '' ?>>Draft</option>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Kategori</label>
                <select name="category_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="">Pilih Kategori</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($news && $news['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Tags</label>
                <input type="text" name="tags" value="<?= htmlspecialchars($tags_string) ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Contoh: pendidikan, literasi, event">
                <p class="text-xs text-gray-500 mt-1">Pisahkan dengan koma.</p>
            </div>

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Gambar Utama</label>
                <?php if ($news && $news['image']): ?>
                    <div class="mb-2">
                        <img src="../uploads/news/<?= htmlspecialchars($news['image']) ?>" class="w-full h-auto rounded shadow">
                    </div>
                <?php endif; ?>
                <input type="file" name="image" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG. Max 2MB.</p>
            </div>
        </div>
    </div>

    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end">
        <a href="news.php" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded mr-2">Batal</a>
        <button type="submit" class="bg-blue-900 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
            <?= $news ? 'Simpan Perubahan' : 'Terbitkan Berita' ?>
        </button>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>
