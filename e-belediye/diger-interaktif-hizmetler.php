<?php require_once '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diğer İnteraktif Hizmetler | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=63" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-grid-3x3-gap-fill me-2"></i>Diğer İnteraktif Hizmetler</h1>
    </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <?php $sayfaBasligi = 'Diğer İnteraktif Hizmetler'; include '../includes/kurumsal-icerik-ust.php'; ?>

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
    // Not: "e-Rayiç" ve "SENDE" gerçek sitedeki gibi bir açılır pencere (modal) ile
    // çalışır; bu ikisinin modal içeriği aşağıda sabit kalır.
    $bolumler = [];
    $bolumStmt = $pdo->prepare(
        "SELECT b.id, b.baslik FROM e_belediye_bolumler b
         JOIN e_belediye_kategoriler k ON k.id = b.kategori_id
         WHERE k.anahtar = :anahtar AND b.aktif = 1
         ORDER BY b.sira ASC, b.id ASC"
    );
    $bolumStmt->execute(['anahtar' => 'diger-interaktif-hizmetler']);
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
                    <?php if (!empty($k[3])): ?>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#<?php echo htmlspecialchars($k[3]); ?>"
                       class="hizmet-kutu d-flex flex-column align-items-center justify-content-center text-decoration-none h-100 py-4 w-100 border-0">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-2"
                             style="width:44px;height:44px;border:2px solid var(--lacivert);">
                            <i class="bi bi-info-circle-fill" style="color:var(--lacivert);font-size:1.2rem;"></i>
                        </div>
                        <h6 class="fw-bold text-center mb-0" style="font-size:.92rem;"><?php echo htmlspecialchars($k[1]); ?></h6>
                    </button>
                    <?php else: ?>
                    <a href="<?php echo htmlspecialchars($k[2]); ?>" target="_blank"
                       class="hizmet-kutu d-flex flex-column align-items-center justify-content-center text-decoration-none h-100 py-4">
                        <div class="rounded-circle d-flex align-items-center justify-content-center mb-2"
                             style="width:44px;height:44px;border:2px solid var(--lacivert);">
                            <i class="bi bi-info-circle-fill" style="color:var(--lacivert);font-size:1.2rem;"></i>
                        </div>
                        <h6 class="fw-bold text-center mb-0" style="font-size:.92rem;"><?php echo htmlspecialchars($k[1]); ?></h6>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endforeach; ?>
        <p class="text-muted" id="sayfaSonucYok" style="display:none;">Aradığınız kritere uygun hizmet bulunamadı.</p>
    </div>

    <!-- e-Rayiç: Gerçek Kişi / Tüzel Kişi seçimi (gerçek site ile aynı akış, e-Devlet sorgu sayfalarına yönlendirir) -->
    <div class="modal fade" id="eRayicModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">e-Rayiç</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body p-0">
                    <h6 class="fw-bold text-center px-4 pt-3 pb-2">Başvuru Türünüzü Seçiniz</h6>
                    <div class="list-group list-group-flush">
                        <a href="https://www.turkiye.gov.tr/gebze-belediyesi-emlak-vergisi-bildirim-sureti-sorgu" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-person-fill"></i>Gerçek Kişi</a>
                        <a href="https://www.turkiye.gov.tr/gebze-belediyesi-tuzel-emlak-vergisi-bildirim-sureti-sorgulama" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-briefcase-fill"></i>Tüzel Kişi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SENDE (Kayıt ve Eğitim Portalı): eğitim/atölye kategorisi seçimi (gerçek site ile aynı akış) -->
    <div class="modal fade" id="sendeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">SENDE (Kayıt ve Eğitim Portalı)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body p-0" style="max-height:70vh;overflow-y:auto;">
                    <h6 class="fw-bold text-center px-4 pt-3 pb-2">Başvuru Türünüzü Seçiniz</h6>
                    <div class="list-group list-group-flush">
                        <a href="https://sende.gebze.bel.tr/egitimler?categories[0]=36" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-person-lines-fill"></i>Atlı Rehabilitasyon ve Eğitim Merkezi (Eğitim)</a>
                        <a href="https://sende.gebze.bel.tr/randevulu-hizmetler" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-person-lines-fill"></i>Atlı Eğitim Merkezi (Randevu)</a>
                        <a href="https://sende.gebze.bel.tr/egitimler?categories[0]=37" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-person-lines-fill"></i>Çocuk Atölyeleri</a>
                        <a href="https://sende.gebze.bel.tr/egitimler?categories[0]=35" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-person-lines-fill"></i>Enderun Çocuk Atölyeleri</a>
                        <a href="https://sende.gebze.bel.tr/egitimler?categories[0]=41" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-person-lines-fill"></i>Fit Yaşam</a>
                        <a href="https://sende.gebze.bel.tr/egitimler?categories[0]=30" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-person-lines-fill"></i>GESMEK</a>
                        <a href="https://sende.gebze.bel.tr/egitimler?categories[0]=34" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-person-lines-fill"></i>Güzide Gençlik Merkezi</a>
                        <a href="https://sende.gebze.bel.tr/egitimler?categories[0]=31" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-person-lines-fill"></i>Kış Okulları</a>
                        <a href="https://sende.gebze.bel.tr/egitimler?categories[0]=38" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-person-lines-fill"></i>Spor Okulları</a>
                        <a href="https://sende.gebze.bel.tr/egitimler?categories[0]=45" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-person-lines-fill"></i>Yetişkin Atölyeleri</a>
                        <a href="https://sende.gebze.bel.tr/egitimler?categories[0]=33" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-person-lines-fill"></i>Doğru Tercih Hazırlık Kursları</a>
                        <a href="https://sende.gebze.bel.tr/egitimler?categories[0]=29" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-person-lines-fill"></i>Yaz Okulları</a>
                    </div>
                </div>
            </div>
        </div>
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
