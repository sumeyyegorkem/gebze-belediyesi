<?php
require_once '../config/db.php';
require_once '../includes/seo.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM projeler WHERE id = :id");
$stmt->execute(['id' => $id]);
$proje = $stmt->fetch();

// Böyle bir proje yoksa ya da yayından kaldırılmışsa (pasif) proje listesine yönlendir.
if (!$proje || (int)$proje['aktif'] !== 1) {
    header('Location: projeler.php');
    exit;
}

$durumEtiketi = ['tamamlanmis' => 'Tamamlanmış', 'devam_eden' => 'Devam Eden', 'planli' => 'Planlanan'];
$durumRenk = ['tamamlanmis' => 'bg-success', 'devam_eden' => 'bg-info text-dark', 'planli' => 'bg-warning text-dark'];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(seoBaslikOlustur($proje['baslik'], $proje['seo_baslik'] ?? '')); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars(seoAciklamaOlustur($proje['aciklama'], $proje['seo_aciklama'] ?? '')); ?>">
    <?php if (!empty($proje['seo_anahtar_kelimeler'])): ?>
    <meta name="keywords" content="<?php echo htmlspecialchars($proje['seo_anahtar_kelimeler']); ?>">
    <?php endif; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=63" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-kanban-fill me-2"></i>Projelerimiz</h1>
    </div>
</header>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="icerik-kutusu">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb small">
                        <li class="breadcrumb-item"><a href="../index.php">Ana Sayfa</a></li>
                        <li class="breadcrumb-item"><a href="projeler.php">Projelerimiz</a></li>
                        <li class="breadcrumb-item active"><?php echo htmlspecialchars($proje['baslik']); ?></li>
                    </ol>
                </nav>

                <span class="badge <?php echo $durumRenk[$proje['durum']]; ?> mb-3">
                    <?php echo $durumEtiketi[$proje['durum']]; ?>
                </span>
                <h1 class="fw-bold mb-4" style="color:var(--lacivert-koyu);"><?php echo htmlspecialchars($proje['baslik']); ?></h1>

                <?php
                    // resim_url veritabanında bazen tam bağlantı (https://...), bazen kök dizine
                    // göre kısa yol ("uploads/x.jpg") olarak saklanıyor.
                    $projeResimSrc = ($proje['resim_url'] !== '' && !preg_match('/^https?:\/\//i', $proje['resim_url']))
                        ? '../' . $proje['resim_url']
                        : $proje['resim_url'];
                ?>
                <img src="<?php echo htmlspecialchars($projeResimSrc); ?>"
                     class="img-fluid rounded-3 mb-4 w-100" style="max-height:420px; object-fit:cover;"
                     alt="<?php echo htmlspecialchars($proje['baslik']); ?>">

                <p class="metin-govde fs-5"><?php echo nl2br(htmlspecialchars($proje['aciklama'])); ?></p>

                <a href="projeler.php" class="btn btn-geri-kutu mt-3">
                    <i class="bi bi-arrow-left me-1"></i> Tüm Projelere Dön
                </a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
