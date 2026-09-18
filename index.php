<?php
// 1) Veritabanı bağlantısını dahil ediyoruz ($pdo değişkeni buradan gelir)
require_once 'config/db.php';

// 2) Duyuruları veritabanından en yeniden en eskiye doğru çekiyoruz.
$sorgu = $pdo->query("SELECT id, baslik, ozet, kategori, resim_url, yayin_tarihi
                       FROM duyurular
                       WHERE aktif = 1
                       ORDER BY yayin_tarihi DESC");
$duyurular = $sorgu->fetchAll();

// 2b) Haberler artık tamamen ayrı bir tablodan geliyor
$haberListesi = $pdo->query("SELECT id, baslik, ozet, kategori, resim_url, yayin_tarihi
                              FROM haberler
                              WHERE aktif = 1
                              ORDER BY yayin_tarihi DESC")->fetchAll();
$duyuruListesi = $duyurular;

// 2c) Videolar (en yeni 6 tanesi)
$videolar = $pdo->query("SELECT * FROM videolar WHERE aktif = 1 ORDER BY tarih DESC LIMIT 6")->fetchAll();

// 3) Yaklaşan etkinlikleri çekiyoruz (bugünden itibaren, tarihe göre sıralı)
$etkSorgu = $pdo->query("SELECT * FROM etkinlikler
                          WHERE etkinlik_tarihi >= CURDATE() AND aktif = 1
                          ORDER BY etkinlik_tarihi ASC");
$etkinlikler = $etkSorgu->fetchAll();

// En yakın tek etkinlik (varsa) - "En Yakın Etkinlik" kartı için
$enYakinEtkinlik = count($etkinlikler) > 0 ? $etkinlikler[0] : null;

// 4) Projeleri çekiyoruz (ana sayfada göstermek için)
$projeler = $pdo->query("SELECT * FROM projeler WHERE aktif = 1 ORDER BY olusturma_tarihi DESC LIMIT 8")->fetchAll();

// 5) Hero slaytları (üstteki büyük görsel carousel)
$heroSlaytlari = $pdo->query("SELECT * FROM hero_slaytlari WHERE aktif = 1 ORDER BY sira ASC, id ASC")->fetchAll();

// 5b) Sosyal medya ikon şeridi (ticker altındaki ikonlar) - admin panelinden
// eklenip/pasife alınabilsin diye veritabanından geliyor (footer.php ile aynı kaynak)
$anaSayfaSosyalHesaplar = $pdo->query("SELECT * FROM sosyal_medya WHERE aktif = 1 ORDER BY sira ASC, id ASC")->fetchAll();

// 6) Hava durumu kutusu için canlı sıcaklık bilgisi (Open-Meteo API, 30 dk önbellekli)
require_once 'includes/hava-durumu.php';
$havaDurumu = gebzeHavaDurumuGetir();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gebze Belediyesi | Ana Sayfa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700;800&family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    <link href="css/style.css?v=64" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<!-- ============== HERO SLIDER (görsel carousel) ============== -->
<div id="anaSlider" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3000">
    <?php if (count($heroSlaytlari) > 1): ?>
    <div class="carousel-indicators">
        <?php foreach ($heroSlaytlari as $hsIndex => $hs): ?>
        <button type="button" data-bs-target="#anaSlider" data-bs-slide-to="<?php echo $hsIndex; ?>"<?php echo $hsIndex === 0 ? ' class="active" aria-current="true"' : ''; ?>></button>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <div class="carousel-inner">
        <?php if (count($heroSlaytlari) > 0): ?>
            <?php foreach ($heroSlaytlari as $hsIndex => $hs): ?>
            <div class="carousel-item<?php echo $hsIndex === 0 ? ' active' : ''; ?>" style="background-image:url('<?php echo htmlspecialchars($hs['resim_url']); ?>');">
                <div class="slider-katman"></div>
                <div class="slider-icerik">
                    <h1 class="display-5"><?php echo htmlspecialchars($hs['baslik_on']); ?><?php if ($hs['baslik_vurgu'] !== ''): ?><span class="hero-vurgu"><?php echo htmlspecialchars($hs['baslik_vurgu']); ?></span><?php endif; ?><?php echo htmlspecialchars($hs['baslik_son']); ?></h1>
                    <?php if (!empty($hs['aciklama'])): ?><p class="lead"><?php echo htmlspecialchars($hs['aciklama']); ?></p><?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="carousel-item active" style="background-image:url('img/hero-1.jpg');">
                <div class="slider-katman"></div>
                <div class="slider-icerik">
                    <h1 class="display-5">Gebze'nin <span class="hero-vurgu">Geleceğine</span> Birlikte Yön Veriyoruz</h1>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#anaSlider" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#anaSlider" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </button>

    <!-- Başkan fotoğrafı -->
    <div class="hero-baskan-bant d-none d-md-block"></div>
    <div class="hero-baskan-overlay d-none d-md-flex">
        <img src="img/baskan-kesim.png" alt="Zinnur Büyükgöz" onerror="this.style.display='none';">
        <div class="hero-baskan-overlay-metin">
            <h6 class="fw-bold mb-0">ZİNNUR BÜYÜKGÖZ</h6>
            <small>BELEDİYE BAŞKANI</small>
            <img src="img/baskan-imza-beyaz.png" alt="Zinnur Büyükgöz imzası" class="hero-baskan-imza-gorsel">
        </div>
    </div>
</div>

<!-- ============== SOSYAL MEDYA İKON ŞERİDİ ============== -->
<div class="py-3 text-center border-bottom">
    <?php foreach ($anaSayfaSosyalHesaplar as $asHesap): ?>
    <a href="<?php echo htmlspecialchars($asHesap['url']); ?>" target="_blank" class="sosyal-ikon"><i class="bi <?php echo htmlspecialchars($asHesap['ikon']); ?>"></i></a>
    <?php endforeach; ?>
</div>

<!-- ============== HİZMETLER ============== -->
<section id="hizmetler" class="py-5">
    <div class="container">
        <h2 class="bolum-baslik ortali text-center mx-auto" style="max-width:600px;">Öne Çıkan Hizmetlerimiz</h2>
        <div class="row g-4 mt-2">
            <div class="col-md-3 col-sm-6">
                <a href="hizmetler/nikah-islemleri.php" class="hizmet-kutu d-block text-decoration-none">
                    <div class="hizmet-ikon one-cikan-rozet"><i class="bi bi-heart-fill"></i></div>
                    <h6 class="fw-bold">Nikah İşlemleri</h6>
                    <p class="small text-muted mb-0">Online randevu ve başvuru işlemleri</p>
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="hizmetler/fen-isleri.php" class="hizmet-kutu d-block text-decoration-none">
                    <div class="hizmet-ikon one-cikan-rozet"><i class="bi bi-cone-striped"></i></div>
                    <h6 class="fw-bold">Fen İşleri</h6>
                    <p class="small text-muted mb-0">Yol, altyapı ve bakım çalışmaları</p>
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="e-belediye/e-belediye.php" class="hizmet-kutu d-block text-decoration-none">
                    <div class="hizmet-ikon one-cikan-rozet"><i class="bi bi-laptop"></i></div>
                    <h6 class="fw-bold">E-Belediye</h6>
                    <p class="small text-muted mb-0">Vergi, borç ve online işlemler</p>
                </a>
            </div>
            <div class="col-md-3 col-sm-6">
                <a href="hizmetler/emlak-istimlak.php" class="hizmet-kutu d-block text-decoration-none">
                    <div class="hizmet-ikon one-cikan-rozet"><i class="bi bi-house-door-fill"></i></div>
                    <h6 class="fw-bold">Emlak &amp; İstimlak</h6>
                    <p class="small text-muted mb-0">İmar durumu ve harita bilgileri</p>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ============== ETKİNLİK TAKVİMİ ============== -->
<section id="etkinlikler" class="py-5 bg-light">
    <div class="container">
        <div class="row g-5 align-items-stretch">
            <div class="col-lg-7 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h2 class="bolum-baslik mb-0">Etkinlik Takvimi</h2>
                    <a href="etkinlikler/etkinlikler.php" class="btn-etkinlik-tumu">Tümü</a>
                </div>
                <?php if (count($etkinlikler) === 0): ?>
                    <p class="text-muted">Şu anda yaklaşan etkinlik bulunmuyor.</p>
                <?php else: ?>
                    <div class="table-responsive etkinlik-takvim-tablo flex-grow-1">
                        <table class="table table-hover align-middle bg-white rounded-3 overflow-hidden shadow-sm mb-0 h-100">
                            <thead>
                                <tr>
                                    <th>Etkinlik</th>
                                    <th>Tür</th>
                                    <th>Mekan</th>
                                    <th>Tarih</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($etkinlikler as $e): ?>
                                    <tr class="etkinlik-satiri" onclick="window.location.href='etkinlikler/etkinlik-detay.php?id=<?php echo $e['id']; ?>';" style="cursor:pointer;">
                                        <td class="fw-semibold"><?php echo htmlspecialchars($e['baslik']); ?></td>
                                        <td><span class="badge badge-tur-etkinlik text-white"><?php echo htmlspecialchars($e['tur']); ?></span></td>
                                        <td class="small text-muted"><?php echo htmlspecialchars($e['mekan']); ?></td>
                                        <td class="small text-muted">
                                            <?php echo date('d.m.Y', strtotime($e['etkinlik_tarihi'])); ?>
                                            <?php echo $e['etkinlik_saati'] ? ' - ' . htmlspecialchars($e['etkinlik_saati']) : ''; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-lg-5 d-flex flex-column">
                <h2 class="bolum-baslik mb-3">En Yakın Etkinlik</h2>
                <?php if ($enYakinEtkinlik): ?>
                    <a href="etkinlikler/etkinlik-detay.php?id=<?php echo $enYakinEtkinlik['id']; ?>" class="proje-kart-yeni flex-grow-1" style="height:auto; min-height:260px;">
                        <img src="<?php echo htmlspecialchars($enYakinEtkinlik['resim_url']); ?>" alt="<?php echo htmlspecialchars($enYakinEtkinlik['baslik']); ?>">
                        <span class="proje-kart-yeni-badge durum-devam"><?php echo htmlspecialchars(mb_strtoupper($enYakinEtkinlik['tur'])); ?></span>
                        <div class="proje-kart-yeni-katman">
                            <h6 class="mb-2"><?php echo htmlspecialchars($enYakinEtkinlik['baslik']); ?></h6>
                            <small class="d-block text-white-50">
                                <i class="bi bi-geo-alt-fill me-1"></i><?php echo htmlspecialchars($enYakinEtkinlik['mekan']); ?>
                            </small>
                            <small class="d-block text-white-50">
                                <i class="bi bi-calendar-event me-1"></i>
                                <?php echo date('d.m.Y', strtotime($enYakinEtkinlik['etkinlik_tarihi'])); ?>
                                <?php echo $enYakinEtkinlik['etkinlik_saati'] ? ' - ' . htmlspecialchars($enYakinEtkinlik['etkinlik_saati']) : ''; ?>
                            </small>
                        </div>
                    </a>
                <?php else: ?>
                    <p class="text-muted">Şu anda yaklaşan etkinlik bulunmuyor.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ============== HABERLER / DUYURULAR / VİDEOLAR ============== -->
<section id="duyurular" class="py-5">
    <div class="container text-center mb-4">
        <h2 class="haberler-baslik"><span class="haberler-baslik-vurgu">Haber</span>ler</h2>
        <p class="text-muted mb-4">Belediyemizden güncel haberler ve duyuruları buradan takip edebilirsiniz.</p>

        <ul class="nav nav-pills justify-content-center gap-2" id="haberSekmeleri">
            <li class="nav-item">
                <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#panelHaberler" type="button">
                    <i class="bi bi-newspaper me-1"></i>Haberler
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#panelDuyurular" type="button">
                    <i class="bi bi-megaphone-fill me-1"></i>Duyurular
                </button>
            </li>
            <li class="nav-item">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#panelVideolar" type="button">
                    <i class="bi bi-play-circle-fill me-1"></i>Videolar
                </button>
            </li>
        </ul>
    </div>

    <div class="container">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="panelHaberler">
                <?php if (count($haberListesi) === 0): ?>
                    <p class="text-muted text-center">Henüz haber eklenmemiş.</p>
                <?php else: ?>
                <div class="row g-4">
                    <?php foreach (array_slice($haberListesi, 0, 6) as $d): ?>
                        <div class="col-md-4">
                            <a href="haberler/haber-detay.php?id=<?php echo (int)$d['id']; ?>" class="haber-mini-karti text-decoration-none text-reset d-block">
                                <img src="<?php echo htmlspecialchars($d['resim_url']); ?>" alt="<?php echo htmlspecialchars($d['baslik']); ?>">
                                <small class="text-muted d-block mt-2"><?php echo date('d.m.Y', strtotime($d['yayin_tarihi'])); ?></small>
                                <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($d['baslik']); ?></h6>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <div class="text-center mt-4"><a href="haberler/haberler.php" class="btn-tumu-pill">Tüm Haberler</a></div>
            </div>

            <div class="tab-pane fade" id="panelDuyurular">
                <?php if (count($duyuruListesi) === 0): ?>
                    <p class="text-muted text-center">Henüz resmi duyuru eklenmemiş.</p>
                <?php else: ?>
                <div class="row g-4">
                    <?php foreach (array_slice($duyuruListesi, 0, 6) as $d): ?>
                        <div class="col-md-4">
                            <a href="haberler/duyuru-detay.php?id=<?php echo (int)$d['id']; ?>" class="haber-mini-karti text-decoration-none text-reset d-block">
                                <img src="<?php echo htmlspecialchars($d['resim_url']); ?>" alt="<?php echo htmlspecialchars($d['baslik']); ?>">
                                <small class="text-muted d-block mt-2"><?php echo date('d.m.Y', strtotime($d['yayin_tarihi'])); ?></small>
                                <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($d['baslik']); ?></h6>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
                <div class="text-center mt-4"><a href="haberler/duyurular.php" class="btn-tumu-pill">Tüm Duyurular</a></div>
            </div>

            <div class="tab-pane fade" id="panelVideolar">
                <?php if (count($videolar) === 0): ?>
                    <div class="text-center py-4">
                        <i class="bi bi-youtube text-danger" style="font-size:3.5rem;"></i>
                        <h5 class="fw-bold mt-3">Henüz video eklenmedi</h5>
                        <p class="text-muted mb-3">Video arşivimiz için resmi YouTube kanalımızı ziyaret edebilirsiniz.</p>
                        <a href="https://www.youtube.com/@gebzebelediyesi7295/videos" target="_blank" class="btn-tumu-pill">
                            <i class="bi bi-box-arrow-up-right me-1"></i>YouTube Kanalına Git
                        </a>
                    </div>
                <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($videolar as $v): ?>
                        <div class="col-md-4">
                            <a href="#" data-bs-toggle="modal" data-bs-target="#videoModal"
                               data-video-id="<?php echo htmlspecialchars($v['youtube_id']); ?>"
                               data-video-baslik="<?php echo htmlspecialchars($v['baslik']); ?>"
                               class="haber-mini-karti text-decoration-none text-reset d-block position-relative">
                                <img src="https://img.youtube.com/vi/<?php echo htmlspecialchars($v['youtube_id']); ?>/hqdefault.jpg" alt="<?php echo htmlspecialchars($v['baslik']); ?>">
                                <div class="video-oynat-ikon"><i class="bi bi-play-fill"></i></div>
                                <small class="text-muted d-block mt-2"><?php echo date('d.m.Y', strtotime($v['tarih'])); ?></small>
                                <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($v['baslik']); ?></h6>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="text-center mt-4">
                    <a href="haberler/videolar.php" class="btn-tumu-pill">Tüm Videolar</a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ============== HAVA DURUMU + HIZLI LİNKLER ============== -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <a href="https://www.mgm.gov.tr/tahmin/il-ve-ilceler.aspx?il=Kocaeli&ilce=Gebze" target="_blank"
                   class="hava-kutu hava-kutu-<?php echo htmlspecialchars($havaDurumu['durum']); ?> text-center h-100 text-decoration-none d-block">
                    <i class="bi <?php echo htmlspecialchars($havaDurumu['ikon']); ?> fs-1 mb-2"></i>
                    <h2 class="fw-bold mb-0"><?php echo $havaDurumu['sicaklik'] !== null ? htmlspecialchars((string) $havaDurumu['sicaklik']) . '°C' : '--°C'; ?></h2>
                    <p class="mb-0" style="opacity:.9;"><?php echo htmlspecialchars($havaDurumu['etiket']); ?></p>
                    <h6 class="fw-bold mb-0 mt-1">Gebze Hava Durumu</h6>
                    <p class="small text-white-50 mb-0"><?php echo date('d.m.Y'); ?></p>
                </a>
            </div>
            <div class="col-lg-8">
                <div class="row g-3 h-100">
                    <div class="col-md-3 col-6">
                        <a href="gebze/gebze.php#tarihce" class="hizli-link-karti">
                            <i class="bi bi-book-fill fs-3 mb-2"></i>
                            <h6 class="fw-bold mb-0">Tarihçe</h6>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="gebze/kent-rehberi.php" class="hizli-link-karti">
                            <i class="bi bi-bank2 fs-3 mb-2"></i>
                            <h6 class="fw-bold mb-0">Tarihi Mekanlar</h6>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="https://kentrehberi.gebze.bel.tr/GebzeNobetciEczaneler" target="_blank" class="hizli-link-karti">
                            <i class="bi bi-capsule fs-3 mb-2"></i>
                            <h6 class="fw-bold mb-0">Nöbetçi Eczaneler</h6>
                        </a>
                    </div>
                    <div class="col-md-3 col-6">
                        <a href="gebze/muhtarlar.php" class="hizli-link-karti">
                            <i class="bi bi-people-fill fs-3 mb-2"></i>
                            <h6 class="fw-bold mb-0">Muhtarlar</h6>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============== PROJELERİMİZ ============== -->
<section id="projelerimiz" class="py-5">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
            <h2 class="bolum-baslik mb-0">Projelerimiz</h2>
            <ul class="nav nav-pills gap-2 mb-0" id="anaSayfaProjeFiltre">
                <li class="nav-item"><a href="projeler/projeler.php" class="nav-link">Tümü</a></li>
                <li class="nav-item"><button class="nav-link btn-filtre-ana" data-filtre="devam_eden">Devam Eden</button></li>
                <li class="nav-item"><button class="nav-link btn-filtre-ana" data-filtre="tamamlanmis">Tamamlanan</button></li>
                <li class="nav-item"><button class="nav-link btn-filtre-ana" data-filtre="planli">Planlanan</button></li>
            </ul>
        </div>
        <p class="text-muted mb-4">Gebze'yi geleceğe taşıyan, tamamlanan ve devam eden yatırım projelerimiz.</p>

        <?php if (count($projeler) === 0): ?>
            <p class="text-muted">Henüz proje eklenmemiş.</p>
        <?php else: ?>
            <div class="duyuru-serit" id="projeSerit">
                <?php foreach ($projeler as $p): ?>
                    <?php
                        $durumEtiket = ['tamamlanmis' => 'TAMAMLANAN', 'devam_eden' => 'DEVAM EDEN', 'planli' => 'PLANLANAN'];
                        $durumSinif = ['tamamlanmis' => 'durum-tamam', 'devam_eden' => 'durum-devam', 'planli' => 'durum-plan'];
                    ?>
                    <div class="duyuru-serit-oge ana-proje-karti" data-durum="<?php echo $p['durum']; ?>">
                        <a href="projeler/proje-detay.php?id=<?php echo $p['id']; ?>" class="proje-kart-yeni">
                            <img src="<?php echo htmlspecialchars($p['resim_url']); ?>" alt="<?php echo htmlspecialchars($p['baslik']); ?>">
                            <div class="proje-kart-yeni-renk <?php echo $durumSinif[$p['durum']]; ?>"></div>
                            <span class="proje-kart-yeni-badge <?php echo $durumSinif[$p['durum']]; ?>">
                                <?php echo $durumEtiket[$p['durum']]; ?>
                            </span>
                            <div class="proje-kart-yeni-katman">
                                <h6><?php echo htmlspecialchars($p['baslik']); ?></h6>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ============== BİZE ULAŞIN (MAVİ KUTU) ============== -->
<section class="bize-ulasin-banner py-5">
    <div class="container">
        <div class="row align-items-center g-4">
            <div class="col-lg-6 text-center text-lg-start">
                <h2 class="bize-ulasin-baslik">Bize Ulaşın</h2>
                <p class="text-white-50 mb-4">
                    Görüş, öneri ve şikayetleriniz için iletişim formundan
                    ya da aşağıdaki bilgilerden bize ulaşabilirsiniz.
                </p>
                <a href="genel/iletisim.php" class="btn btn-lg bize-ulasin-buton">
                    <i class="bi bi-envelope-fill me-2"></i>Forma Git
                </a>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-sm-4">
                        <div class="bize-ulasin-kutu">
                            <i class="bi bi-telephone-fill"></i>
                            <h6 class="fw-bold mb-0 mt-2">444 4 429</h6>
                            <small class="text-white-50">Çağrı Merkezi</small>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="bize-ulasin-kutu">
                            <i class="bi bi-envelope-fill"></i>
                            <h6 class="fw-bold mb-0 mt-2">gebze@gebze.bel.tr</h6>
                            <small class="text-white-50">E-posta</small>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="bize-ulasin-kutu">
                            <i class="bi bi-geo-alt-fill"></i>
                            <h6 class="fw-bold mb-0 mt-2">Güzeller Mah.</h6>
                            <small class="text-white-50">Bahar Cad. No:1</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ============== KONUM HARİTASI (BAĞIMSIZ BÖLÜM - ANA SAYFA ARKA PLANI İLE UYUMLU) ============== -->
<section class="py-5 bg-light" id="harita-bolumu">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="bolum-baslik ortali text-center mx-auto" style="max-width:600px;">Belediye Hizmet Binamız</h2>
            <p class="text-muted">Güzeller Mah. Bahar Cad. No:1, 41400 Gebze/Kocaeli</p>
        </div>
        <div class="rounded-4 overflow-hidden shadow-lg border">
            <div id="belediyeHaritasi" style="height:450px; width:100%;"></div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const haritaAlani = document.getElementById('belediyeHaritasi');
    if (haritaAlani && typeof L !== 'undefined') {
        // Gebze Belediyesi Ana Hizmet Binası Tam Koordinatları
        const konum = [40.8061, 29.4399]; 
        const harita = L.map('belediyeHaritasi', { scrollWheelZoom: false }).setView(konum, 17);

        // API Key Gerektirmeyen OpenStreetMap Basemap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap katkıda bulunanlar'
        }).addTo(harita);

        // Vurgulu Özel İşaretçi (Marker)
        const ozelIkon = L.divIcon({
            html: '<div style="width:42px;height:42px;background:#0d6efd;border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 0 15px rgba(13,110,253,0.8);border:3px solid #fff;"><i class="bi bi-geo-alt-fill" style="color:#fff;font-size:1.3rem;"></i></div>',
            className: '',
            iconSize: [42, 42],
            iconAnchor: [21, 42],
            popupAnchor: [0, -40]
        });

        L.marker(konum, { icon: ozelIkon }).addTo(harita)
            .bindPopup('<div style="color:#333; font-family:sans-serif;"><strong>Gebze Belediyesi Ana Hizmet Binası</strong><br>Güzeller Mah. Bahar Cad. No:1<br><a href="https://maps.google.com/?q=40.8061,29.4399" target="_blank" style="color:#0d6efd; text-decoration:none; font-weight:bold;">Yol Tarifi Al &rarr;</a></div>')
            .openPopup();
    }

    const serit = document.getElementById('duyuruSerit');
    if (serit) {
        function kartGenisliginiOlc() {
            const ilkKart = serit.querySelector('.duyuru-serit-oge');
            if (!ilkKart) return 320;
            const stil = window.getComputedStyle(serit);
            const aralik = parseFloat(stil.columnGap || stil.gap || 20);
            return ilkKart.getBoundingClientRect().width + aralik;
        }

        function birAdimKaydir() {
            const kaydirmaMiktari = kartGenisliginiOlc();
            const sonaGelindiMi = serit.scrollLeft + serit.clientWidth >= serit.scrollWidth - 10;
            if (sonaGelindiMi) {
                serit.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
                serit.scrollBy({ left: kaydirmaMiktari, behavior: 'smooth' });
            }
        }

        let otomatikKaydirma = setInterval(birAdimKaydir, 3000);

        serit.addEventListener('mouseenter', function () {
            clearInterval(otomatikKaydirma);
        });
        serit.addEventListener('mouseleave', function () {
            otomatikKaydirma = setInterval(birAdimKaydir, 3000);
        });
    }
});

// ============== PROJELER: filtre + otomatik yatay kayma ==============
document.addEventListener('DOMContentLoaded', function () {
    const projeSerit = document.getElementById('projeSerit');
    const filtreButonlari = document.querySelectorAll('.btn-filtre-ana');
    if (!projeSerit) return;

    const kartlar = projeSerit.querySelectorAll('.ana-proje-karti');

    filtreButonlari.forEach(function (buton) {
        buton.addEventListener('click', function () {
            filtreButonlari.forEach(b => b.classList.remove('active'));
            buton.classList.add('active');
            const secilenDurum = buton.getAttribute('data-filtre');
            kartlar.forEach(function (kart) {
                const uyuyor = (secilenDurum === 'hepsi' || kart.getAttribute('data-durum') === secilenDurum);
                kart.style.display = uyuyor ? '' : 'none';
            });
        });
    });

    function projeKartGenisliginiOlc() {
        const ilkKart = projeSerit.querySelector('.duyuru-serit-oge');
        if (!ilkKart) return 320;
        const stil = window.getComputedStyle(projeSerit);
        const aralik = parseFloat(stil.columnGap || stil.gap || 20);
        return ilkKart.getBoundingClientRect().width + aralik;
    }

    function projeBirAdimKaydir() {
        const projeKaydirmaMiktari = projeKartGenisliginiOlc();
        const sonaGelindiMi = projeSerit.scrollLeft + projeSerit.clientWidth >= projeSerit.scrollWidth - 10;
        if (sonaGelindiMi) {
            projeSerit.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
            projeSerit.scrollBy({ left: projeKaydirmaMiktari, behavior: 'smooth' });
        }
    }

    let projeOtomatikKaydirma = setInterval(projeBirAdimKaydir, 3000);
    projeSerit.addEventListener('mouseenter', function () {
        clearInterval(projeOtomatikKaydirma);
    });
    projeSerit.addEventListener('mouseleave', function () {
        projeOtomatikKaydirma = setInterval(projeBirAdimKaydir, 3000);
    });
});
</script>
</body>
</html>