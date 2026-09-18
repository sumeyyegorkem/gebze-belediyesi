<?php require_once '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vergi ve Başvuru İşlemleri | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=63" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-cash-coin me-2"></i>Vergi ve Başvuru İşlemleri</h1>
    </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <?php $sayfaBasligi = 'Vergi ve Başvuru İşlemleri'; include '../includes/kurumsal-icerik-ust.php'; ?>

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
    // Not: "e-Beyan" ve "Başvuru Formu" gerçek sitedeki gibi çok adımlı bir
    // açılır pencere (modal) ile çalışır; bu ikisinin modal içeriği aşağıda sabit kalır.
    $bolumler = [];
    $bolumStmt = $pdo->prepare(
        "SELECT b.id, b.baslik FROM e_belediye_bolumler b
         JOIN e_belediye_kategoriler k ON k.id = b.kategori_id
         WHERE k.anahtar = :anahtar AND b.aktif = 1
         ORDER BY b.sira ASC, b.id ASC"
    );
    $bolumStmt->execute(['anahtar' => 'vergi-ve-basvuru-islemleri']);
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

    <!-- e-Beyan: Başvuru türü seçimi ve bildirim türleri (gerçek site ile aynı akış) -->
    <div class="modal fade" id="eBeyanModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">e-Beyan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body p-0">

                    <div class="e-beyan-adim" id="eBeyanAdim-baslangic">
                        <h6 class="fw-bold text-center px-4 pt-3 pb-2">Başvuru Türünüzü Seçiniz</h6>
                        <div class="list-group list-group-flush">
                            <button type="button" class="list-group-item list-group-item-action menu-link e-beyan-tur-btn" data-hedef="eBeyanAdim-gercek">
                                <i class="bi bi-person-fill"></i>Gerçek Kişi
                            </button>
                            <button type="button" class="list-group-item list-group-item-action menu-link e-beyan-tur-btn" data-hedef="eBeyanAdim-tuzel">
                                <i class="bi bi-briefcase-fill"></i>Tüzel Kişi
                            </button>
                        </div>
                    </div>

                    <div class="e-beyan-adim d-none" id="eBeyanAdim-gercek">
                        <button type="button" class="btn btn-link text-decoration-none ps-3 pt-3 pb-0 e-beyan-geri-btn" data-hedef="eBeyanAdim-baslangic">
                            <i class="bi bi-arrow-left me-1"></i>Geri Dön
                        </button>
                        <h6 class="fw-bold text-center pb-2">Gerçek Kişi</h6>
                        <div class="list-group list-group-flush">
                            <a href="https://ulakbel.gebze.bel.tr/WebBasvuru/e-arsa-beyani-bildirim-formu/" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Arsa e Beyan Bildirimi</a>
                            <a href="https://ulakbel.gebze.bel.tr/WebBasvuru/e-arazi-beyani-bildirim-formu/" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Arazi e Beyan Bildirimi</a>
                            <a href="https://ulakbel.gebze.bel.tr/WebBasvuru/e-cevre-ve-temizlik-beyani/" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Çevre Temizlik e Beyan Bildirimi</a>
                            <a href="https://ulakbel.gebze.bel.tr/WebBasvuru/e-ilan-ve-reklam-beyani/" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>İlan ve Reklam e Beyan Bildirimi</a>
                            <a href="https://ulakbel.gebze.bel.tr/WebBasvuru/e-isyeri-beyani-bildirim-formu/" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>İşyeri e Beyan Bildirimi</a>
                            <a href="https://ulakbel.gebze.bel.tr/WebBasvuru/e-mesken-beyani-bildirim-formu/" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Mesken e Beyan Bildirimi</a>
                        </div>
                    </div>

                    <div class="e-beyan-adim d-none" id="eBeyanAdim-tuzel">
                        <button type="button" class="btn btn-link text-decoration-none ps-3 pt-3 pb-0 e-beyan-geri-btn" data-hedef="eBeyanAdim-baslangic">
                            <i class="bi bi-arrow-left me-1"></i>Geri Dön
                        </button>
                        <h6 class="fw-bold text-center pb-2">Tüzel Kişi</h6>
                        <div class="list-group list-group-flush">
                            <a href="https://ulakbel.gebze.bel.tr/WebBasvuru/e-arsa-beyani-bildirim-formu-tuzel/#/" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Arsa e Beyan Bildirimi</a>
                            <a href="https://ulakbel.gebze.bel.tr/WebBasvuru/e-arazi-beyani-bildirim-formu-tuzel/#/" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Arazi e Beyan Bildirimi</a>
                            <a href="https://ulakbel.gebze.bel.tr/WebBasvuru/e-cevre-ve-temizlik-beyani-tuzel/#/" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Çevre Temizlik e Beyan Bildirimi</a>
                            <a href="https://ulakbel.gebze.bel.tr/WebBasvuru/e-ilan-ve-reklam-beyani-tuzel/#/" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>İlan ve Reklam e Beyan Bildirimi</a>
                            <a href="https://ulakbel.gebze.bel.tr/WebBasvuru/e-isyeri-beyani-bildirim-formu-tuzel/#/" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>İşyeri e Beyan Bildirimi</a>
                            <a href="https://ulakbel.gebze.bel.tr/WebBasvuru/e-mesken-beyani-bildirim-formu-tuzel/#/" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Mesken e Beyan Bildirimi</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Başvuru Formu: istek/şikayet/teşekkür türü seçimi (gerçek site ile aynı akış) -->
    <div class="modal fade" id="basvuruFormuModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Başvuru Formu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body p-0">
                    <h6 class="fw-bold text-center px-4 pt-3 pb-2">Başvuru Türünüzü Seçiniz</h6>
                    <div class="list-group list-group-flush">
                        <a href="https://ulakbel.gebze.bel.tr/WebBasvuru/?incident_type_id=1&hidden_fields=incident_type_id#/" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>İstek</a>
                        <a href="https://ulakbel.gebze.bel.tr/WebBasvuru/?incident_type_id=2&hidden_fields=incident_type_id#/" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>Şikayet</a>
                        <a href="https://ulakbel.gebze.bel.tr/WebBasvuru/?incident_type_id=3&hidden_fields=incident_type_id#/" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>Teşekkür</a>
                        <a href="https://ulakbel.gebze.bel.tr/WebBasvuru/?incident_type_id=4&hidden_fields=incident_type_id#/" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>Proje Bildirimi</a>
                        <a href="https://ulakbel.gebze.bel.tr/WebBasvuru/?incident_type_id=5&hidden_fields=incident_type_id#/" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>Bilgi Talebi</a>
                        <a href="https://ulakbel.gebze.bel.tr/WebBasvuru/?incident_type_id=6&hidden_fields=incident_type_id#/" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>İhbar</a>
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

    // e-Beyan modalı: adım geçişleri
    const eBeyanModalEl = document.getElementById('eBeyanModal');
    if (eBeyanModalEl) {
        const adimlar = eBeyanModalEl.querySelectorAll('.e-beyan-adim');
        function eBeyanAdimGoster(hedefId) {
            adimlar.forEach(function (a) { a.classList.toggle('d-none', a.id !== hedefId); });
        }
        eBeyanModalEl.querySelectorAll('.e-beyan-tur-btn, .e-beyan-geri-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                eBeyanAdimGoster(this.getAttribute('data-hedef'));
            });
        });
        eBeyanModalEl.addEventListener('hidden.bs.modal', function () {
            eBeyanAdimGoster('eBeyanAdim-baslangic');
        });
    }

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
