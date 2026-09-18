<?php
require_once '../config/db.php';
require_once '../includes/seo.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM etkinlikler WHERE id = :id");
$stmt->execute(['id' => $id]);
$etkinlik = $stmt->fetch();

// Böyle bir etkinlik yoksa ya da yayından kaldırılmışsa (pasif) ana sayfaya yönlendir.
if (!$etkinlik || (int)$etkinlik['aktif'] !== 1) {
    header('Location: ../index.php');
    exit;
}

// Bu etkinlik tablosunda ayrı bir "özet" alanı olmadığından, SEO açıklaması
// boş bırakılırsa tür/mekan/tarih bilgisinden otomatik bir açıklama üretiyoruz.
$etkinlikVarsayilanAciklama = !empty($etkinlik['aciklama'])
    ? $etkinlik['aciklama']
    : $etkinlik['tur'] . ' - ' . $etkinlik['mekan'] . ' - '
        . date('d.m.Y', strtotime($etkinlik['etkinlik_tarihi']))
        . ' tarihinde Gebze Belediyesi tarafından düzenlenmektedir.';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(seoBaslikOlustur($etkinlik['baslik'], $etkinlik['seo_baslik'] ?? '')); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars(seoAciklamaOlustur($etkinlikVarsayilanAciklama, $etkinlik['seo_aciklama'] ?? '')); ?>">
    <?php if (!empty($etkinlik['seo_anahtar_kelimeler'])): ?>
    <meta name="keywords" content="<?php echo htmlspecialchars($etkinlik['seo_anahtar_kelimeler']); ?>">
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
        <h1 class="mb-0"><i class="bi bi-calendar-event-fill me-2"></i>Etkinlikler</h1>
    </div>
</header>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="icerik-kutusu">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb small">
                        <li class="breadcrumb-item"><a href="../index.php">Ana Sayfa</a></li>
                        <li class="breadcrumb-item"><a href="etkinlikler.php">Etkinlikler</a></li>
                        <li class="breadcrumb-item active"><?php echo htmlspecialchars($etkinlik['baslik']); ?></li>
                    </ol>
                </nav>

                <span class="badge badge-kategori text-white mb-3"><?php echo htmlspecialchars($etkinlik['tur']); ?></span>
                <h1 class="fw-bold mb-3" style="color:var(--lacivert-koyu);"><?php echo htmlspecialchars($etkinlik['baslik']); ?></h1>

                <div class="text-muted small mb-4">
                    <i class="bi bi-geo-alt-fill me-1"></i> <?php echo htmlspecialchars($etkinlik['mekan']); ?>
                    <span class="ms-3">
                        <i class="bi bi-calendar-event me-1"></i>
                        <?php echo date('d.m.Y', strtotime($etkinlik['etkinlik_tarihi'])); ?>
                        <?php echo $etkinlik['etkinlik_saati'] ? ' - ' . htmlspecialchars($etkinlik['etkinlik_saati']) : ''; ?>
                    </span>
                </div>

                <?php
                    // resim_url veritabanında bazen tam bağlantı (https://...), bazen kök dizine
                    // göre kısa yol ("uploads/x.jpg") olarak saklanıyor.
                    $etkinlikResimSrc = ($etkinlik['resim_url'] !== '' && !preg_match('/^https?:\/\//i', $etkinlik['resim_url']))
                        ? '../' . $etkinlik['resim_url']
                        : $etkinlik['resim_url'];
                ?>
                <img src="<?php echo htmlspecialchars($etkinlikResimSrc); ?>"
                     class="img-fluid rounded-3 mb-4 w-100" style="max-height:420px; object-fit:cover;"
                     alt="<?php echo htmlspecialchars($etkinlik['baslik']); ?>">

                <?php if (!empty($etkinlik['aciklama'])): ?>
                    <p class="metin-govde fs-5"><?php echo nl2br(htmlspecialchars($etkinlik['aciklama'])); ?></p>
                <?php else: ?>
                    <p class="metin-govde fs-5 text-muted">Bu etkinlik hakkında ayrıntılı bilgi yakında eklenecektir.</p>
                <?php endif; ?>

                <!-- Bu etkinliğe özel fotoğraf galerisi (etkinlik açıklamasının altında) -->
                <?php $galeriSayfa = 'etkinlik-' . $etkinlik['id']; include '../includes/fotograf-galerisi-bolum.php'; ?>

                <a href="etkinlikler.php" class="btn btn-geri-kutu mt-3">
                    <i class="bi bi-arrow-left me-1"></i> Tüm Etkinliklere Dön
                </a>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
