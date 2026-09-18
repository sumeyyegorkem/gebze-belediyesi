<?php
require_once '../config/db.php';
$eskiBaskanlarDb = $pdo->query("SELECT donem_grubu, yil_araligi, ad_soyad, foto_url FROM eski_baskanlar WHERE aktif = 1 ORDER BY sira ASC, id ASC")->fetchAll();
$donemler = [];
foreach ($eskiBaskanlarDb as $eb) {
    $donemler[$eb['donem_grubu']][] = $eb;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eski Başkanlar | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=67" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-clock-history me-2"></i>Eski Başkanlar</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            <?php $aktifKurumsal = 'eski-baskanlar'; include '../includes/kurumsal-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Eski Başkanlar'; include '../includes/kurumsal-icerik-ust.php'; ?>

            <?php foreach ($donemler as $donemAdi => $baskanlar): ?>
            <div class="mb-4">
                <h5 class="fw-bold text-muted mb-3"><?php echo htmlspecialchars($donemAdi); ?></h5>
                <div class="row row-cols-2 row-cols-sm-3 row-cols-lg-4 g-3">
                    <?php foreach ($baskanlar as $b): ?>
                        <?php
                            // foto_url veritabanında bazen tam bağlantı (https://...) bazen de
                            // kök dizine göre kısa yol ("img/x.jpg") olarak saklanıyor.
                            $bFotoSrc = preg_match('/^https?:\/\//i', $b['foto_url']) ? $b['foto_url'] : '../' . $b['foto_url'];
                        ?>
                        <div class="col">
                            <div class="card duyuru-karti h-100 text-center border-0">
                                <img src="<?php echo htmlspecialchars($bFotoSrc); ?>" alt="<?php echo htmlspecialchars($b['ad_soyad']); ?>"
                                     class="card-img-top" style="height:190px;object-fit:cover;object-position:top;"
                                     onerror="this.src='https://placehold.co/300x190?text=%20';">
                                <div class="card-body py-3">
                                    <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($b['ad_soyad']); ?></h6>
                                    <small class="text-muted"><?php echo htmlspecialchars($b['yil_araligi']); ?></small>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
