<?php require_once '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bilgilendirme Hizmetleri | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=63" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-info-circle-fill me-2"></i>Bilgilendirme Hizmetleri</h1>
    </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <?php $sayfaBasligi = 'Bilgilendirme Hizmetleri'; include '../includes/kurumsal-icerik-ust.php'; ?>

    <a href="e-belediye.php" class="btn btn-geri-kutu mb-4">
        <i class="bi bi-arrow-left me-1"></i>Tüm E-Belediye Hizmetleri
    </a>

    <div class="mb-4" style="max-width:420px;">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
            <input type="text" id="sayfaArama" class="form-control border-start-0 ps-0" placeholder="Hizmet ara...">
        </div>
    </div>

    <?php
    // Bu sayfanın hizmet kutucukları artık veritabanından geliyor
    // (admin panelinden "E-Belediye" bölümünden ekleyip çıkarabilirsiniz).
    $bolumler = [];
    $bolumStmt = $pdo->prepare(
        "SELECT b.id, b.baslik FROM e_belediye_bolumler b
         JOIN e_belediye_kategoriler k ON k.id = b.kategori_id
         WHERE k.anahtar = :anahtar AND b.aktif = 1
         ORDER BY b.sira ASC, b.id ASC"
    );
    $bolumStmt->execute(['anahtar' => 'bilgilendirme-hizmetleri']);
    foreach ($bolumStmt->fetchAll() as $b) {
        $hizmetStmt = $pdo->prepare("SELECT * FROM e_belediye_hizmetleri WHERE bolum_id = :bolum_id AND aktif = 1 ORDER BY sira ASC, id ASC");
        $hizmetStmt->execute(['bolum_id' => $b['id']]);
        $ogeler = [];
        foreach ($hizmetStmt->fetchAll() as $h) {
            $oge = [$h['ikon'], $h['baslik'], $h['href']];
            if (!empty($h['ozel_hedef'])) {
                $oge[3] = $h['ozel_hedef'];
            }
            $ogeler[] = $oge;
        }
        $bolumler[$b['baslik']] = $ogeler;
    }
    ?>

    <div id="sayfaGrid">
        <?php foreach ($bolumler as $baslik => $kutular): ?>
        <section class="mb-5 sayfa-bolum">
            <h2 class="bolum-baslik"><?php echo htmlspecialchars($baslik); ?></h2>
            <div class="row g-3">
                <?php foreach ($kutular as $k): ?>
                <div class="col-md-4 col-sm-6 sayfa-kart" data-baslik="<?php echo htmlspecialchars(mb_strtolower($k[1], 'UTF-8')); ?>">
                    <a href="<?php echo htmlspecialchars($k[2]); ?>" target="_blank"
                       class="hizmet-kutu d-flex flex-column align-items-center justify-content-center text-decoration-none h-100 py-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-2"
                             style="width:44px;height:44px;border:2px solid var(--lacivert);">
                            <i class="bi bi-info-circle-fill" style="color:var(--lacivert);font-size:1.2rem;"></i>
                        </div>
                        <h6 class="fw-bold text-center mb-0" style="font-size:.92rem;"><?php echo htmlspecialchars($k[1]); ?></h6>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endforeach; ?>
        <p class="text-muted" id="sayfaSonucYok" style="display:none;">Aradığınız kritere uygun hizmet bulunamadı.</p>
    </div>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const arama = document.getElementById('sayfaArama');
    const bolumler = document.querySelectorAll('.sayfa-bolum');
    const sonucYok = document.getElementById('sayfaSonucYok');

    arama.addEventListener('input', function () {
        const aranan = arama.value.trim().toLocaleLowerCase('tr-TR');
        let toplamGorunen = 0;
        bolumler.forEach(function (bolum) {
            let bolumdeGorunen = 0;
            bolum.querySelectorAll('.sayfa-kart').forEach(function (kart) {
                const goster = (aranan === '' || kart.getAttribute('data-baslik').includes(aranan));
                kart.style.display = goster ? '' : 'none';
                if (goster) bolumdeGorunen++;
            });
            bolum.style.display = (bolumdeGorunen > 0) ? '' : 'none';
            toplamGorunen += bolumdeGorunen;
        });
        sonucYok.style.display = (toplamGorunen === 0) ? 'block' : 'none';
    });
});
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>
