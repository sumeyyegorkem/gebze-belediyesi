<?php require_once '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Üye Olduğumuz Birlikler | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=68" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-diagram-3-fill me-2"></i>Üye Olduğumuz Birlikler</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4 mb-lg-0">
            <?php $aktifGebze = 'uye-birlikler'; include '../includes/gebze-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Üye Olduğumuz Birlikler'; include '../includes/kurumsal-icerik-ust.php'; ?>

    <p class="metin-govde mb-4">
        Gebze Belediyesi, aşağıdaki belediyeler birliği ve platformlara üye olarak yerel
        yönetimler arası işbirliğine katkı sağlamaktadır.
    </p>
    <ul class="list-unstyled">
        <?php
        $birlikler = $pdo->query("SELECT ad, url FROM uye_birlikler WHERE aktif = 1 ORDER BY sira ASC, id ASC")->fetchAll(PDO::FETCH_NUM);
        foreach ($birlikler as $b): ?>
        <li class="mb-2">
            <i class="bi bi-link-45deg me-2"></i>
            <?php if (!empty($b[1])): ?>
                <a href="<?php echo htmlspecialchars($b[1]); ?>" target="_blank" class="text-decoration-none"><?php echo htmlspecialchars($b[0]); ?></a>
            <?php else: ?>
                <?php echo htmlspecialchars($b[0]); ?>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
        <?php if (count($birlikler) === 0): ?>
            <li class="text-muted small">Henüz birlik eklenmemiş.</li>
        <?php endif; ?>
    </ul>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
