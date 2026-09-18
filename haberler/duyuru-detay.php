<?php
require_once '../config/db.php';
require_once '../includes/seo.php';

// URL'den gelen id'yi alıyoruz: duyuru-detay.php?id=3
// (int) ile sayıya çeviriyoruz, böylece kötü niyetli metin girilemez (SQL Injection'a karşı ek önlem)
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Hazırlanmış sorgu (prepared statement) kullanıyoruz: ":id" bir yer tutucudur.
// Kullanıcıdan gelen veriyi ASLA doğrudan sorgunun içine yazmayız, bu SQL Injection açığına yol açar.
$stmt = $pdo->prepare("SELECT * FROM duyurular WHERE id = :id");
$stmt->execute(['id' => $id]);
$duyuru = $stmt->fetch();

// Eğer böyle bir duyuru yoksa ya da yayından kaldırılmışsa (pasif) ana sayfaya yönlendir.
if (!$duyuru || (int)$duyuru['aktif'] !== 1) {
    header('Location: ../index.php');
    exit;
}

// Görüntülenme sayısını 1 artır (basit bir UPDATE sorgusu örneği)
$pdo->prepare("UPDATE duyurular SET goruntulenme = goruntulenme + 1 WHERE id = :id")
    ->execute(['id' => $id]);

// Diğer duyuruları çekiyoruz (şu an görüntülenen hariç, en yeni 3 tanesi, sadece aktif olanlar)
$digerStmt = $pdo->prepare(
    "SELECT * FROM duyurular WHERE id != :id AND aktif = 1 ORDER BY yayin_tarihi DESC LIMIT 3"
);
$digerStmt->execute(['id' => $id]);
$digerDuyurular = $digerStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars(seoBaslikOlustur($duyuru['baslik'], $duyuru['seo_baslik'] ?? '')); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars(seoAciklamaOlustur($duyuru['ozet'], $duyuru['seo_aciklama'] ?? '')); ?>">
    <?php if (!empty($duyuru['seo_anahtar_kelimeler'])): ?>
    <meta name="keywords" content="<?php echo htmlspecialchars($duyuru['seo_anahtar_kelimeler']); ?>">
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
        <h1 class="mb-0"><i class="bi bi-megaphone-fill me-2"></i>Duyurular</h1>
    </div>
</header>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="icerik-kutusu">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb small">
                        <li class="breadcrumb-item"><a href="../index.php">Ana Sayfa</a></li>
                        <li class="breadcrumb-item"><a href="duyurular.php">Duyurular</a></li>
                        <li class="breadcrumb-item active"><?php echo htmlspecialchars($duyuru['baslik']); ?></li>
                    </ol>
                </nav>

                <span class="badge badge-kategori text-white mb-3"><?php echo htmlspecialchars($duyuru['kategori']); ?></span>
                <h1 class="fw-bold mb-3" style="color:var(--lacivert-koyu);"><?php echo htmlspecialchars($duyuru['baslik']); ?></h1>

                <div class="text-muted small mb-4">
                    <i class="bi bi-calendar3 me-1"></i> <?php echo date('d.m.Y H:i', strtotime($duyuru['yayin_tarihi'])); ?>
                    <span class="ms-3"><i class="bi bi-eye-fill me-1"></i> <?php echo (int)$duyuru['goruntulenme']; ?> görüntülenme</span>
                </div>

                <?php
                    // resim_url veritabanında bazen tam bağlantı (https://...), bazen kök dizine
                    // göre kısa yol ("uploads/x.jpg") olarak saklanıyor.
                    $duyuruResimSrc = ($duyuru['resim_url'] !== '' && !preg_match('/^https?:\/\//i', $duyuru['resim_url']))
                        ? '../' . $duyuru['resim_url']
                        : $duyuru['resim_url'];
                ?>
                <img src="<?php echo htmlspecialchars($duyuruResimSrc); ?>"
                     class="img-fluid rounded-3 mb-4 w-100" style="max-height:420px; object-fit:cover;"
                     alt="<?php echo htmlspecialchars($duyuru['baslik']); ?>">

                <p class="metin-govde fs-5"><?php echo nl2br(htmlspecialchars($duyuru['icerik'])); ?></p>

                <!-- Bu duyuruya özel fotoğraf galerisi (duyurunun kendi içeriğinin altında) -->
                <?php $galeriSayfa = 'duyuru-' . $duyuru['id']; include '../includes/fotograf-galerisi-bolum.php'; ?>

                <a href="duyurular.php" class="btn btn-geri-kutu mt-3">
                    <i class="bi bi-arrow-left me-1"></i> Tüm Duyurulara Dön
                </a>
            </div>
        </div>
    </div>

    <!-- Diğer Duyurular -->
    <?php if (count($digerDuyurular) > 0): ?>
    <div class="row justify-content-center mt-5">
        <div class="col-lg-9">
            <h4 class="fw-bold mb-4">Diğer Duyurular</h4>
            <div class="row g-4">
                <?php foreach ($digerDuyurular as $d): ?>
                    <?php
                        $dResimSrc = ($d['resim_url'] !== '' && !preg_match('/^https?:\/\//i', $d['resim_url']))
                            ? '../' . $d['resim_url']
                            : $d['resim_url'];
                    ?>
                    <div class="col-md-4">
                        <a href="duyuru-detay.php?id=<?php echo $d['id']; ?>" class="card duyuru-karti h-100 text-decoration-none text-reset d-block">
                            <img src="<?php echo htmlspecialchars($dResimSrc); ?>"
                                 class="card-img-top" alt="<?php echo htmlspecialchars($d['baslik']); ?>">
                            <div class="card-body">
                                <span class="badge badge-kategori text-white mb-2"><?php echo htmlspecialchars($d['kategori']); ?></span>
                                <h6 class="fw-bold"><?php echo htmlspecialchars($d['baslik']); ?></h6>
                                <small class="text-muted">
                                    <i class="bi bi-calendar3 me-1"></i><?php echo date('d.m.Y', strtotime($d['yayin_tarihi'])); ?>
                                </small>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
