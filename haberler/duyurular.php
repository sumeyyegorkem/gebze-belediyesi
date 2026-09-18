<?php
require_once '../config/db.php';

$duyurular = $pdo->query("SELECT * FROM duyurular WHERE aktif = 1 ORDER BY yayin_tarihi DESC")->fetchAll();

// Kategori listesini duyurulardan otomatik çıkarıyoruz (tekrar etmeyen kategoriler)
$kategoriler = array_unique(array_column($duyurular, 'kategori'));
sort($kategoriler);

// Kategori filtre listesinde her kategorinin yanında gösterilecek ikon
$kategoriIkon = [
    'Genel' => 'bi-info-circle-fill',
];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duyurular | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=63" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
    <style>
        /* Bu stil SADECE bu sayfada geçerli, siteyi etkilemez */
        .badge-kategori { background-color: var(--altin) !important; }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-megaphone-fill me-2"></i>Duyurular</h1>
    </div>
</header>

<div class="container py-5">

    <!-- Kategori filtreleri -->
    <ul class="nav nav-pills mb-4 gap-2 hizmet-menu-liste haber-menu-liste" id="duyuruFiltre">
        <li class="nav-item">
            <button class="nav-link active btn-filtre-duyuru duyuru-tum-kategoriler" data-filtre="hepsi">Hepsi</button>
        </li>
        <?php foreach ($kategoriler as $k): ?>
        <li class="nav-item">
            <button class="nav-link btn-filtre-duyuru" data-filtre="<?php echo htmlspecialchars($k); ?>">
                <span class="hizmet-kutu"><i class="bi <?php echo $kategoriIkon[$k] ?? 'bi-tag-fill'; ?>"></i></span><?php echo htmlspecialchars($k); ?>
            </button>
        </li>
        <?php endforeach; ?>
    </ul>

    <div class="row g-4" id="duyuruListesi">
        <?php if (count($duyurular) === 0): ?>
            <p class="text-muted">Henüz duyuru eklenmemiş.</p>
        <?php endif; ?>

        <?php foreach ($duyurular as $d): ?>
            <?php
                // resim_url veritabanında bazen tam bağlantı (https://...), bazen kök dizine
                // göre kısa yol ("uploads/x.jpg") olarak saklanıyor.
                $dResimSrc = ($d['resim_url'] !== '' && !preg_match('/^https?:\/\//i', $d['resim_url']))
                    ? '../' . $d['resim_url']
                    : $d['resim_url'];
            ?>
            <div class="col-lg-4 col-md-6 duyuru-karti-sarmal" data-kategori="<?php echo htmlspecialchars($d['kategori']); ?>">
                <div class="card duyuru-karti h-100">
                    <img src="<?php echo htmlspecialchars($dResimSrc); ?>"
                         class="card-img-top" alt="<?php echo htmlspecialchars($d['baslik']); ?>">
                    <div class="card-body d-flex flex-column">
                        <span class="badge badge-kategori text-white mb-2 align-self-start">
                            <?php echo htmlspecialchars($d['kategori']); ?>
                        </span>
                        <h6 class="card-title fw-bold"><?php echo htmlspecialchars($d['baslik']); ?></h6>
                        <p class="card-text text-muted small flex-grow-1"><?php echo htmlspecialchars($d['ozet']); ?></p>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-muted">
                                <i class="bi bi-calendar3 me-1"></i>
                                <?php echo date('d.m.Y', strtotime($d['yayin_tarihi'])); ?>
                            </small>
                            <a href="duyuru-detay.php?id=<?php echo (int)$d['id']; ?>" class="btn btn-sm btn-geri-kutu">Devamını Oku</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const butonlar = document.querySelectorAll('.btn-filtre-duyuru');
    const kartlar = document.querySelectorAll('.duyuru-karti-sarmal');

    butonlar.forEach(function (buton) {
        buton.addEventListener('click', function () {
            butonlar.forEach(b => b.classList.remove('active'));
            buton.classList.add('active');

            const secilenFiltre = buton.getAttribute('data-filtre');

            kartlar.forEach(function (kart) {
                if (secilenFiltre === 'hepsi' || kart.getAttribute('data-kategori') === secilenFiltre) {
                    kart.style.display = '';
                } else {
                    kart.style.display = 'none';
                }
            });
        });
    });
});
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>
