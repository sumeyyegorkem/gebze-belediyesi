<?php
require_once '../config/db.php';
$asilUyeler = $pdo->query("SELECT ad_soyad, gorev FROM arabuluculuk_uyeleri WHERE tip = 'asil' AND aktif = 1 ORDER BY sira ASC, id ASC")->fetchAll();
$yedekUyeler = $pdo->query("SELECT ad_soyad, gorev FROM arabuluculuk_uyeleri WHERE tip = 'yedek' AND aktif = 1 ORDER BY sira ASC, id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arabuluculuk Komisyonu | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=67" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-people-fill me-2"></i>Arabuluculuk Komisyonu</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            <?php $aktifKurumsal = 'arabuluculuk'; include '../includes/kurumsal-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Arabuluculuk Komisyonu'; include '../includes/kurumsal-icerik-ust.php'; ?>

            <p class="metin-govde mb-4">
                Belediyemiz, 7036 sayılı İş Mahkemeleri Kanunu ve ilgili mevzuat kapsamında,
                iş uyuşmazlıklarının dava açılmadan önce çözümlenmesi amacıyla bir
                Arabuluculuk Komisyonu oluşturmuştur. Komisyon; asıl ve yedek üyelerden
                oluşmaktadır.
            </p>

            <!-- Asıl Üyeler -->
            <h5 class="fw-bold mb-3">Asıl Üyeler</h5>
            <div class="row g-3 mb-5">
                <?php foreach ($asilUyeler as $u): ?>
                <div class="col-md-4 col-sm-6">
                    <div class="hizmet-kutu text-start h-100 d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:48px;height:48px;background:var(--lacivert);color:#fff;">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0" style="font-size:.92rem;"><?php echo htmlspecialchars($u['ad_soyad']); ?></h6>
                            <small class="text-muted"><?php echo htmlspecialchars($u['gorev']); ?></small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Yedek Üyeler -->
            <h5 class="fw-bold mb-3">Yedek Üyeler</h5>
            <div class="row g-3">
                <?php foreach ($yedekUyeler as $u): ?>
                <div class="col-md-4 col-sm-6">
                    <div class="hizmet-kutu text-start h-100 d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                             style="width:44px;height:44px;background:var(--acik-gri);color:var(--lacivert);">
                            <i class="bi bi-person-fill"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0" style="font-size:.88rem;"><?php echo htmlspecialchars($u['ad_soyad']); ?></h6>
                            <small class="text-muted"><?php echo htmlspecialchars($u['gorev']); ?></small>
                        </div>
                    </div>
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
