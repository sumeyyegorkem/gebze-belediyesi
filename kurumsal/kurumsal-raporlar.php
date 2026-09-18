<?php
require_once '../config/db.php';
$raporlar = $pdo->query("SELECT * FROM kurumsal_raporlar WHERE aktif = 1 ORDER BY sira ASC, id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurumsal Raporlar | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=67" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-graph-up me-2"></i>Kurumsal Raporlar</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            <?php $aktifKurumsal = 'kurumsal-rapor'; include '../includes/kurumsal-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Kurumsal Raporlar'; include '../includes/kurumsal-icerik-ust.php'; ?>

            <div id="raporListesi">
            <?php foreach ($raporlar as $r): ?>
            <div class="hizmet-kutu d-flex align-items-center justify-content-between text-start mb-3">
                <div>
                    <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($r['baslik']); ?></h6>
                    <small class="text-muted">Yayın Tarihi: <?php echo htmlspecialchars($r['yayin_tarihi']); ?> &middot; Dosya Türü: PDF</small>
                </div>
                <?php if ($r['pdf_url']): ?>
                    <a href="<?php echo htmlspecialchars($r['pdf_url']); ?>" target="_blank" class="btn btn-sm btn-lacivert flex-shrink-0">
                        <i class="bi bi-file-earmark-pdf-fill me-1"></i>PDF İndir
                    </a>
                <?php else: ?>
                    <small class="text-muted flex-shrink-0"><i class="bi bi-dash-circle me-1"></i>Bağlantı paylaşılmadı</small>
                <?php endif; ?>
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
