<?php
require_once '../config/db.php';
// Hizmetler bölümünden (yan menüden veya Mali Hizmetler kartından) gelindiyse yan bar gösterilir,
// üst menüdeki doğrudan "E-Belediye" linkinden gelindiyse yan bar gösterilmez.
$sidebarGoster = isset($_GET['panel']) && $_GET['panel'] === 'hizmet';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Belediye | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=70" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
    <style>
        /* Bu stil SADECE bu sayfada geçerli, siteyi etkilemez */
        .ebelediye-kategori-karti { transition: transform .2s ease, box-shadow .2s ease; }
        .ebelediye-kategori-karti:hover { transform: translateY(-4px); }
        .ebelediye-ok { transition: transform .2s ease; display: inline-block; }
        .ebelediye-kategori-karti:hover .ebelediye-ok { transform: translateX(4px); }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-laptop me-2"></i>E-Belediye</h1>
    </div>
</header>

<div class="container py-5">
    <div class="row">
        <?php if ($sidebarGoster): ?>
        <div class="col-lg-3 mb-4 mb-lg-0">
            <?php $aktifHizmet = 'mali-hizmetler'; include '../includes/hizmet-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
        <?php else: ?>
        <div class="col-12">
        <?php endif; ?>
            <?php $sayfaBasligi = 'E-Belediye'; include '../includes/kurumsal-icerik-ust.php'; ?>

    <p class="metin-govde mb-5">
        Gebze Belediyesi olarak vergi, borç sorgulama, beyan bildirimi ve daha birçok
        işleminizi evinizden çıkmadan halledebilmeniz için e-Belediye hizmetlerimizi
        sunuyoruz. Aşağıdaki kategorilerden birine tıklayarak ilgili hizmetlere ulaşabilirsiniz.
    </p>

    <?php
    // E-Belediye kategorileri artık veritabanından geliyor (admin panelinden "E-Belediye" bölümünden
    // ekleyip çıkarabilirsiniz); hizmet sayısı da her kategorideki gerçek hizmet sayısına göre canlı hesaplanır.
    $kategoriler = [];
    $katSatirlari = $pdo->query(
        "SELECT k.*, COUNT(h.id) AS hizmet_sayisi
         FROM e_belediye_kategoriler k
         LEFT JOIN e_belediye_bolumler b ON b.kategori_id = k.id AND b.aktif = 1
         LEFT JOIN e_belediye_hizmetleri h ON h.bolum_id = b.id AND h.aktif = 1
         WHERE k.aktif = 1
         GROUP BY k.id
         ORDER BY k.sira ASC, k.id ASC"
    )->fetchAll();
    // hedef_sayfa veritabanında bazen "e-belediye/x.php" (taşınmadan önceki köke göre yazılmış),
    // bazen sadece "x.php" olarak saklanmış olabilir. e-belediye.php artık kendi klasörünün
    // içinde yaşadığı için, aynı klasördeki kategori sayfalarına giden önek temizleniyor.
    $tasinanKategoriSayfalari = [
        'vergi-ve-basvuru-islemleri.php', 'imar-ve-yapi-islemleri.php',
        'bilgilendirme-hizmetleri.php', 'diger-interaktif-hizmetler.php',
    ];
    foreach ($katSatirlari as $k) {
        $hedefSayfa = $k['hedef_sayfa'];
        if (strpos($hedefSayfa, 'e-belediye/') === 0) {
            $hedefSayfa = substr($hedefSayfa, strlen('e-belediye/'));
        }
        $kategoriler[] = [$k['ikon'], $k['baslik'], $k['aciklama'], $hedefSayfa, (int)$k['hizmet_sayisi']];
    }
    ?>

    <div class="row g-4">
        <?php foreach ($kategoriler as $kat): ?>
        <div class="col-md-6">
            <a href="<?php echo htmlspecialchars($kat[3]); ?>"
               class="hizmet-kutu ebelediye-kategori-karti d-flex align-items-start gap-3 text-decoration-none h-100 p-4">
                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                     style="width:56px;height:56px;background:var(--lacivert);color:#fff;font-size:1.5rem;">
                    <i class="bi <?php echo $kat[0]; ?>"></i>
                </div>
                <div class="flex-grow-1 text-start">
                    <div class="d-flex justify-content-between align-items-start mb-1 gap-2">
                        <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($kat[1]); ?></h5>
                        <span class="badge rounded-pill flex-shrink-0" style="background:var(--acik-gri);color:var(--lacivert-koyu);font-weight:600;">
                            <?php echo (int) $kat[4]; ?> hizmet
                        </span>
                    </div>
                    <p class="text-muted mb-2" style="font-size:.92rem;"><?php echo htmlspecialchars($kat[2]); ?></p>
                    <span class="fw-semibold small" style="color:var(--altin);">
                        İncele <i class="bi bi-arrow-right ms-1 ebelediye-ok"></i>
                    </span>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
