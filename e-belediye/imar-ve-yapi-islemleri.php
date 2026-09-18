<?php require_once '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İmar ve Yapı İşlemleri | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=63" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-building me-2"></i>İmar ve Yapı İşlemleri</h1>
    </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <?php $sayfaBasligi = 'İmar ve Yapı İşlemleri'; include '../includes/kurumsal-icerik-ust.php'; ?>

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
    // Not: Modal ile açılan 5 başvuru türünün (İmar Durumu, Yapı Ruhsatı, Güçlendirme,
    // Asansör Tescil, Yapı Ruhsat Başvurusu) çok adımlı içeriği aşağıda sabit kalır.
    $bolumler = [];
    $bolumStmt = $pdo->prepare(
        "SELECT b.id, b.baslik FROM e_belediye_bolumler b
         JOIN e_belediye_kategoriler k ON k.id = b.kategori_id
         WHERE k.anahtar = :anahtar AND b.aktif = 1
         ORDER BY b.sira ASC, b.id ASC"
    );
    $bolumStmt->execute(['anahtar' => 'imar-ve-yapi-islemleri']);
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

    <!-- İmar Durumu Başvurusu: Gerçek Kişi / Tüzel Kişi seçimi ve başvuru türleri (gerçek site ile aynı akış) -->
    <div class="modal fade" id="imarDurumuModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">İmar Durumu Başvurusu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body p-0">

                    <div class="imar-durumu-adim" id="imarDurumuAdim-baslangic">
                        <h6 class="fw-bold text-center px-4 pt-3 pb-2">Başvuru Türünüzü Seçiniz</h6>
                        <div class="list-group list-group-flush">
                            <button type="button" class="list-group-item list-group-item-action menu-link imar-durumu-tur-btn" data-hedef="imarDurumuAdim-gercek">
                                <i class="bi bi-person-fill"></i>Gerçek Kişi
                            </button>
                            <button type="button" class="list-group-item list-group-item-action menu-link imar-durumu-tur-btn" data-hedef="imarDurumuAdim-tuzel">
                                <i class="bi bi-briefcase-fill"></i>Tüzel Kişi
                            </button>
                        </div>
                    </div>

                    <div class="imar-durumu-adim d-none" id="imarDurumuAdim-gercek">
                        <button type="button" class="btn btn-link text-decoration-none ps-3 pt-3 pb-0 imar-durumu-geri-btn" data-hedef="imarDurumuAdim-baslangic">
                            <i class="bi bi-arrow-left me-1"></i>Geri Dön
                        </button>
                        <h6 class="fw-bold text-center pb-2">Gerçek Kişi</h6>
                        <div class="list-group list-group-flush">
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=fnv%2AgJuygJZ3GWtL7gth2w%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>Tevhit-İfraz Amaçlı İmar Durumu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=0K3%2Ad2JOcWBtkopYTDZSfA%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>İnşaat Amaçlı İmar Durumu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=aQZ2HUkAj8VjnoXJqPzdhw%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>Bilgi Amaçlı İmar Durum Yazısı</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=%2FpDhaqMCkMl9nuvs%2FYfWMQ%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>İmar Barışı Kapsamında Yola, Parka, Kamu Vb. Alanlara Terk Yazısı</a>
                        </div>
                    </div>

                    <div class="imar-durumu-adim d-none" id="imarDurumuAdim-tuzel">
                        <button type="button" class="btn btn-link text-decoration-none ps-3 pt-3 pb-0 imar-durumu-geri-btn" data-hedef="imarDurumuAdim-baslangic">
                            <i class="bi bi-arrow-left me-1"></i>Geri Dön
                        </button>
                        <h6 class="fw-bold text-center pb-2">Tüzel Kişi</h6>
                        <div class="list-group list-group-flush">
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=5o3vq7lfCr0zZoXllM3tIg%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>Tevhit-İfraz Amaçlı İmar Durumu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=LQyX3hqCLmi0h90lbFPMyg%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>İnşaat Amaçlı İmar Durumu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=delz%2ARWCn8jfbcyQ4SRjJg%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>Bilgi Amaçlı İmar Durum Yazısı</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=h%2FFV%2ABkYEdWF2jQigwnAtw%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>İmar Barışı Kapsamında Yola, Parka, Kamu Vb. Alanlara Terk Yazısı</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Yapı Ruhsatına Esas Başvurular: çok kademeli başvuru türü seçimi (gerçek site ile aynı akış) -->
    <div class="modal fade" id="yapiRuhsatModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Yapı Ruhsatına Esas Başvurular</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body p-0" style="max-height:70vh;overflow-y:auto;">

                    <div class="yapi-ruhsat-adim" id="yapiRuhsatAdim-baslangic">
                        <h6 class="fw-bold text-center px-4 pt-3 pb-2">Başvuru Türünüzü Seçiniz</h6>
                        <div class="list-group list-group-flush">
                            <button type="button" class="list-group-item list-group-item-action menu-link yapi-ruhsat-tur-btn" data-hedef="yapiRuhsatAdim-mimariOn"><i class="bi bi-check-circle-fill"></i>Mimari Ön Onay Başvurusu</button>
                            <button type="button" class="list-group-item list-group-item-action menu-link yapi-ruhsat-tur-btn" data-hedef="yapiRuhsatAdim-mimariProje"><i class="bi bi-check-circle-fill"></i>Mimari Proje Onay Başvurusu</button>
                            <button type="button" class="list-group-item list-group-item-action menu-link yapi-ruhsat-tur-btn" data-hedef="yapiRuhsatAdim-statikProje"><i class="bi bi-check-circle-fill"></i>Statik Proje Onay Başvurusu</button>
                            <button type="button" class="list-group-item list-group-item-action menu-link yapi-ruhsat-tur-btn" data-hedef="yapiRuhsatAdim-elektrikProje"><i class="bi bi-check-circle-fill"></i>Elektrik Proje Onay Başvurusu</button>
                            <button type="button" class="list-group-item list-group-item-action menu-link yapi-ruhsat-tur-btn" data-hedef="yapiRuhsatAdim-mekanikProje"><i class="bi bi-check-circle-fill"></i>Mekanik Proje Onay Başvurusu</button>
                            <button type="button" class="list-group-item list-group-item-action menu-link yapi-ruhsat-tur-btn" data-hedef="yapiRuhsatAdim-zeminEtut"><i class="bi bi-check-circle-fill"></i>Zemin Etüt Onay Başvurusu</button>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=dxTOC1DE2YE3Nh%2FOpoehHw%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>Zemin Durum Belgesi Başvurusu</a>
                            <button type="button" class="list-group-item list-group-item-action menu-link yapi-ruhsat-tur-btn" data-hedef="yapiRuhsatAdim-asansorAvan"><i class="bi bi-check-circle-fill"></i>Asansör Avan Projesi Onay Başvurusu</button>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=OUDAtNc0aPXIazxZnay35g%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>Yapı Aplikasyon Projesi Başvurusu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=I3rWi5q8YmPj00H95240mg%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>Plankote Onay Başvurusu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=ZqVr%2FyJ%2A%2Aj5qyBuQNxQfdA%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>Emsal Krokisi Belge Başvurusu</a>
                        </div>
                    </div>

                    <div class="yapi-ruhsat-adim d-none" id="yapiRuhsatAdim-mimariOn">
                        <button type="button" class="btn btn-link text-decoration-none ps-3 pt-3 pb-0 yapi-ruhsat-geri-btn" data-hedef="yapiRuhsatAdim-baslangic"><i class="bi bi-arrow-left me-1"></i>Geri Dön</button>
                        <h6 class="fw-bold text-center pb-2">Mimari Ön Onay Başvurusu</h6>
                        <div class="list-group list-group-flush">
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=mchLhtAnKyL5kC4PpMa8Mw%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Mimari Proje Ön Onay Başvurusu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=ktL580rnjBbwWKM2WW%2Actw%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Tadilat Mimari Proje Ön Onay Başvurusu</a>
                        </div>
                    </div>

                    <div class="yapi-ruhsat-adim d-none" id="yapiRuhsatAdim-mimariProje">
                        <button type="button" class="btn btn-link text-decoration-none ps-3 pt-3 pb-0 yapi-ruhsat-geri-btn" data-hedef="yapiRuhsatAdim-baslangic"><i class="bi bi-arrow-left me-1"></i>Geri Dön</button>
                        <h6 class="fw-bold text-center pb-2">Mimari Proje Onay Başvurusu</h6>
                        <div class="list-group list-group-flush">
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=KPvK9a%2A0jgVGMHtsHwRFIw%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Mimari Proje Onay Başvurusu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=9tKG%2AU%2FheoJGTV5E2Xi4Xw%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Mimari Proje Onay Başvurusu (TUS Var)</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=0%2FTyMCLPifMgFxxxrE9Lnw%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Diğer Bloklar İçin Mimari Proje Onay Başvurusu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=IQtZXMJPYCrHQgM6qrw3Ag%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Tadilat Mimari Proje Onay Başvurusu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=GULvZD9HD%2Aa%2FQQNIox706Q%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Tadilat Mimari Proje Onay Başvurusu (TUSLU)</a>
                        </div>
                    </div>

                    <div class="yapi-ruhsat-adim d-none" id="yapiRuhsatAdim-statikProje">
                        <button type="button" class="btn btn-link text-decoration-none ps-3 pt-3 pb-0 yapi-ruhsat-geri-btn" data-hedef="yapiRuhsatAdim-baslangic"><i class="bi bi-arrow-left me-1"></i>Geri Dön</button>
                        <h6 class="fw-bold text-center pb-2">Statik Proje Onay Başvurusu</h6>
                        <div class="list-group list-group-flush">
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=gdpiEdtphTR7M9KhQMWLrg%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Statik Proje Onay Başvurusu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=oyy002RzwFKivB%2Ad4lFxyA%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Statik Proje Onay Başvurusu (TUS Var)</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=DICUTcg6%2AQXqxM2ycwdT8w%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Diğer Bloklar İçin Statik Proje Onay Başvurusu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=DK%2FpHGVtacIrR9T93MUDNQ%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Tadilat Statik Proje Onay Başvurusu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=ol2akubK1AIbpICc5FmZXg%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Tadilat Statik Proje Onay Başvurusu (TUSLU)</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=PYvLrPJbcTPFgKvweFiJaQ%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>İksa Proje Onay Başvurusu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=HydnSk7wFAx%2FnlPK4PuQ1Q%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>İstinat Duvarı Proje Onay Başvurusu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=GVMHXeNIebadlBwiEHygoQ%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Güçlendirme Proje Onay Başvurusu</a>
                        </div>
                    </div>

                    <div class="yapi-ruhsat-adim d-none" id="yapiRuhsatAdim-elektrikProje">
                        <button type="button" class="btn btn-link text-decoration-none ps-3 pt-3 pb-0 yapi-ruhsat-geri-btn" data-hedef="yapiRuhsatAdim-baslangic"><i class="bi bi-arrow-left me-1"></i>Geri Dön</button>
                        <h6 class="fw-bold text-center pb-2">Elektrik Proje Onay Başvurusu</h6>
                        <div class="list-group list-group-flush">
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=FwBG6P%2FUnrSKPODgc%2FZWaw%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Elektrik Proje Onay Başvurusu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=Gsnm%2Fp%2AFbERucHGe9OLDXA%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Elektrik Proje Onay Başvurusu (TUS Var)</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=wzUMV1gBRIdIHi8c57WWMg%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Diğer Bloklar İçin Elektrik Proje Onay Başvurusu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=sEFyzRZ2TEYYjYCXhCc4Nw%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Tadilat Elektrik Proje Onay Başvurusu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=vT0qQtISp0VgixAgMiu%2AIg%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Tadilat Elektrik Proje Onay Başvurusu (TUSLU)</a>
                        </div>
                    </div>

                    <div class="yapi-ruhsat-adim d-none" id="yapiRuhsatAdim-mekanikProje">
                        <button type="button" class="btn btn-link text-decoration-none ps-3 pt-3 pb-0 yapi-ruhsat-geri-btn" data-hedef="yapiRuhsatAdim-baslangic"><i class="bi bi-arrow-left me-1"></i>Geri Dön</button>
                        <h6 class="fw-bold text-center pb-2">Mekanik Proje Onay Başvurusu</h6>
                        <div class="list-group list-group-flush">
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=myk9%2F4DW5%2FlHxHFSV7CtNQ%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Mekanik Proje Onay Başvurusu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=tpEV3PrDf%2FPtFDBEfoXDEg%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Mekanik Proje Onay Başvurusu (TUS Var)</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=Y77Tkwr7jXk8w9PfIExXpQ%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Diğer Bloklar İçin Mekanik Proje Onay Başvurusu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=L9RjeeIp8zUzRlBcMNItQQ%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Tadilat Mekanik Proje Onay Başvurusu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=Ej04aUOxw3uDmJCVj0TtBQ%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Tadilat Mekanik Proje Onay Başvurusu (TUSLU)</a>
                        </div>
                    </div>

                    <div class="yapi-ruhsat-adim d-none" id="yapiRuhsatAdim-zeminEtut">
                        <button type="button" class="btn btn-link text-decoration-none ps-3 pt-3 pb-0 yapi-ruhsat-geri-btn" data-hedef="yapiRuhsatAdim-baslangic"><i class="bi bi-arrow-left me-1"></i>Geri Dön</button>
                        <h6 class="fw-bold text-center pb-2">Zemin Etüt Onay Başvurusu</h6>
                        <div class="list-group list-group-flush">
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=8F%2FCGAQGpK%2FWwjYwajdo3Q%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Zemin Etüt Onay Başvurusu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=iCYB1Yyb9dUXu5FVEdnK8w%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Tadilat Zemin Etüt Onay Başvurusu</a>
                        </div>
                    </div>

                    <div class="yapi-ruhsat-adim d-none" id="yapiRuhsatAdim-asansorAvan">
                        <button type="button" class="btn btn-link text-decoration-none ps-3 pt-3 pb-0 yapi-ruhsat-geri-btn" data-hedef="yapiRuhsatAdim-baslangic"><i class="bi bi-arrow-left me-1"></i>Geri Dön</button>
                        <h6 class="fw-bold text-center pb-2">Asansör Avan Projesi Onay Başvurusu</h6>
                        <div class="list-group list-group-flush">
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=qFLoVn23KYVn6W7fCh9%2Atg%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Asansör Avan Proje Onay Başvurusu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=dP0owp4WYBJ5MCnRKxdusw%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Asansör Avan Proje Onay Başvurusu (TUS Var)</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=Ffy0gJNKpPzcJUlw6xQbQw%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Diğer Bloklar İçin Asansör Avan Proje Onay Başvurusu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=yT32M0TjJH4PgZ5ojnrJCQ%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Tadilat Asansör Avan Proje Onay Başvurusu</a>
                            <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=XYtF%2A2q4HR5ukI2oof6YOw%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-file-earmark-text-fill"></i>Tadilat Asansör Avan Proje Onay Başvurusu (TUS Var)</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Güçlendirmeye Esas Başvurular: başvuru türü seçimi (gerçek site ile aynı akış) -->
    <div class="modal fade" id="gucBasvuruModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Güçlendirmeye Esas Başvurular</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body p-0">
                    <h6 class="fw-bold text-center px-4 pt-3 pb-2">Başvuru Türünüzü Seçiniz</h6>
                    <div class="list-group list-group-flush">
                        <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=cynObOdzITZiBCkGREdebQ%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>Güçlendirme İzin Belgesi Başvurusu</a>
                        <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=GVMHXeNIebadlBwiEHygoQ%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>Güçlendirme Proje Onay Başvurusu</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Asansör Tescil Başvurusu: başvuru türü seçimi (gerçek site ile aynı akış) -->
    <div class="modal fade" id="asansorTescilModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Asansör Tescil Başvurusu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body p-0">
                    <h6 class="fw-bold text-center px-4 pt-3 pb-2">Başvuru Türünüzü Seçiniz</h6>
                    <div class="list-group list-group-flush">
                        <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=Bzl9iOWJrAJIjiZCOcBSPw%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>Asansör Uygulama Proje Başvurusu</a>
                        <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=R%2ABnVxcA580OlMyvX5Mt7g%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>Asansör Uygulama Projesi Ön Onay Başvurusu</a>
                        <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=qBmQ%2A3c%2FNCVx0sFRYD5a%2FQ%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>Yeni Asansör İçin Tescil Belgesi Başvurusu</a>
                        <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=S4RK3s2c8zNuxXd6vQb7ZQ%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>Mevcut Asansör İçin Tescil Belgesi Başvurusu</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Yapı Ruhsat Başvurusu: başvuru türü seçimi (gerçek site ile aynı akış) -->
    <div class="modal fade" id="yapiRuhsatBasvuruModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Yapı Ruhsat Başvurusu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body p-0">
                    <h6 class="fw-bold text-center px-4 pt-3 pb-2">Başvuru Türünüzü Seçiniz</h6>
                    <div class="list-group list-group-flush">
                        <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=3CDQFNqGYzY8JECuFiiipw%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>Yeni Yapı Ruhsat Başvurusu</a>
                        <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=H7cJrF4yhnbMuVzorCcjuQ%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>Tadilat Ruhsatı Başvurusu</a>
                        <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=iwCG6f0ItMxIXUvJY59Yxg%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>Diğer Bloklar İçin Yapı Ruhsat Başvurusu</a>
                        <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=00%2FZz99ulQtYSx4fkNxbng%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>İksa Yapı Ruhsat Başvurusu</a>
                        <a href="https://eimar.gebze.bel.tr/YapiBelgeleriWeb/BasvuruTurList/RedirectEDevlet?BasvuruTurId=76hglEhGWp%2A0GLdIF6cYwA%3D%3D" target="_blank" rel="noopener" class="list-group-item list-group-item-action menu-link"><i class="bi bi-check-circle-fill"></i>İstinat Duvarı Yapı Ruhsatı Başvurusu</a>
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

    // Yapı Ruhsatına Esas Başvurular modalı: adım geçişleri
    const yapiRuhsatModalEl = document.getElementById('yapiRuhsatModal');
    if (yapiRuhsatModalEl) {
        const adimlar = yapiRuhsatModalEl.querySelectorAll('.yapi-ruhsat-adim');
        function yapiRuhsatAdimGoster(hedefId) {
            adimlar.forEach(function (a) { a.classList.toggle('d-none', a.id !== hedefId); });
        }
        yapiRuhsatModalEl.querySelectorAll('.yapi-ruhsat-tur-btn, .yapi-ruhsat-geri-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                yapiRuhsatAdimGoster(this.getAttribute('data-hedef'));
            });
        });
        yapiRuhsatModalEl.addEventListener('hidden.bs.modal', function () {
            yapiRuhsatAdimGoster('yapiRuhsatAdim-baslangic');
        });
    }

    // İmar Durumu Başvurusu modalı: adım geçişleri
    const imarDurumuModalEl = document.getElementById('imarDurumuModal');
    if (imarDurumuModalEl) {
        const adimlar = imarDurumuModalEl.querySelectorAll('.imar-durumu-adim');
        function imarDurumuAdimGoster(hedefId) {
            adimlar.forEach(function (a) { a.classList.toggle('d-none', a.id !== hedefId); });
        }
        imarDurumuModalEl.querySelectorAll('.imar-durumu-tur-btn, .imar-durumu-geri-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                imarDurumuAdimGoster(this.getAttribute('data-hedef'));
            });
        });
        imarDurumuModalEl.addEventListener('hidden.bs.modal', function () {
            imarDurumuAdimGoster('imarDurumuAdim-baslangic');
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
