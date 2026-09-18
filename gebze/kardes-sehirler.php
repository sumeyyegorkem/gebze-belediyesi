<?php require_once '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kardeş Şehirler | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=68" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-globe-americas me-2"></i>Kardeş Şehirler</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4 mb-lg-0">
            <?php $aktifGebze = 'kardes-sehirler'; include '../includes/gebze-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Kardeş Şehirler'; include '../includes/kurumsal-icerik-ust.php'; ?>

    <p class="metin-govde mb-4">
        Gebze Belediyesi'nin yurt içinden ve yurt dışından kardeş şehir anlaşması bulunan
        belediyeler aşağıda listelenmiştir.
    </p>

    <h5 class="fw-bold mb-3">Yurt İçi</h5>
    <div class="row g-3 mb-4">
        <?php
        $kardesYurtIci = $pdo->query("SELECT ad, il FROM kardes_sehirler WHERE tur = 'ici' AND aktif = 1 ORDER BY sira ASC, id ASC")->fetchAll(PDO::FETCH_NUM);
        foreach ($kardesYurtIci as $k): ?>
        <div class="col-md-3 col-sm-6">
            <div class="hizmet-kutu h-100 text-center">
                <i class="bi bi-geo-alt-fill fs-4 mb-2" style="color:var(--altin);"></i>
                <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($k[0]); ?></h6>
                <small class="text-muted"><?php echo htmlspecialchars($k[1]); ?>, Türkiye</small>
            </div>
        </div>
        <?php endforeach; ?>
        <?php if (count($kardesYurtIci) === 0): ?>
            <p class="text-muted small mb-0">Henüz yurt içi kardeş şehir eklenmemiş.</p>
        <?php endif; ?>
    </div>

    <h5 class="fw-bold mb-3">Yurt Dışı</h5>
    <div class="row g-3">
        <?php
        $kardesYurtDisi = $pdo->query("SELECT ad, sehir, ulke FROM kardes_sehirler WHERE tur = 'disi' AND aktif = 1 ORDER BY sira ASC, id ASC")->fetchAll(PDO::FETCH_NUM);
        foreach ($kardesYurtDisi as $k): ?>
        <div class="col-md-3 col-sm-6">
            <div class="hizmet-kutu h-100 text-center">
                <i class="bi bi-globe-americas fs-4 mb-2" style="color:var(--altin);"></i>
                <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($k[0]); ?></h6>
                <small class="text-muted"><?php echo htmlspecialchars($k[1] . ', ' . $k[2]); ?></small>
            </div>
        </div>
        <?php endforeach; ?>
        <?php if (count($kardesYurtDisi) === 0): ?>
            <p class="text-muted small mb-0">Henüz yurt dışı kardeş şehir eklenmemiş.</p>
        <?php endif; ?>
    </div>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
