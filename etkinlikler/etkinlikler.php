<?php
require_once '../config/db.php';

$etkinlikler = $pdo->query("SELECT * FROM etkinlikler WHERE aktif = 1 ORDER BY etkinlik_tarihi ASC")->fetchAll();

// Kategori listesi gerçek gebze.bel.tr/etkinlik.html sayfasındaki kategorilerle aynı
$turler = [
    'Konser', 'Çocuk Sineması', 'Özel Program', 'Çocuk Etkinliği', 'Yetişkin Sineması',
    'Anma Programı', 'Off-Road', 'Sergi', 'Yetişkin Tiyatrosu', 'Çocuk Tiyatrosu',
    'Söyleşi', 'Stand-up', 'Panel', 'Şiir Dinletisi', 'Ramazan Özel Cami Programı',
    'Sirk Çocuk', 'Kandil Programı', 'Sohbet', 'Konferans',
];

// Kategori filtre listesinde her türün yanında gösterilecek ikon
$turIkon = [
    'Konser' => 'bi-music-note-beamed',
    'Çocuk Sineması' => 'bi-film',
    'Özel Program' => 'bi-star-fill',
    'Çocuk Etkinliği' => 'bi-balloon-fill',
    'Yetişkin Sineması' => 'bi-camera-reels-fill',
    'Anma Programı' => 'bi-flower1',
    'Off-Road' => 'bi-signpost-split-fill',
    'Sergi' => 'bi-easel-fill',
    'Yetişkin Tiyatrosu' => 'bi-person-video3',
    'Çocuk Tiyatrosu' => 'bi-emoji-laughing-fill',
    'Söyleşi' => 'bi-chat-square-text-fill',
    'Stand-up' => 'bi-mic-fill',
    'Panel' => 'bi-clipboard-data-fill',
    'Şiir Dinletisi' => 'bi-file-earmark-music-fill',
    'Ramazan Özel Cami Programı' => 'bi-moon-stars-fill',
    'Sirk Çocuk' => 'bi-emoji-sunglasses-fill',
    'Kandil Programı' => 'bi-moon-fill',
    'Sohbet' => 'bi-chat-dots-fill',
    'Konferans' => 'bi-mortarboard-fill',
];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etkinlikler | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=72" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
    <style>
        /* Bu stil SADECE bu sayfada geçerli, siteyi etkilemez */
        .badge-kategori { background-color: var(--altin) !important; }

        /* "Tüm Kategoriler": Kurumsal sayfasındaki başlık sistemiyle aynı - yuvarlak değil, altında vurgu çizgisi */
        #etkinlikFiltre .etkinlik-tum-kategoriler {
            background: transparent !important;
            border: none !important;
            border-bottom: 3px solid var(--altin) !important;
            border-radius: 0 !important;
            color: var(--lacivert-koyu) !important;
            font-weight: 800;
            text-transform: uppercase;
            font-size: .8rem;
            letter-spacing: .5px;
            padding-left: 0;
            padding-right: 0;
        }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-calendar-event-fill me-2"></i>Etkinlikler</h1>
    </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4 mb-lg-0">
            <div class="bg-white rounded-3 p-3" style="box-shadow:0 3px 12px rgba(11,61,98,0.07);">
                <ul class="nav nav-pills flex-column gap-2 hizmet-menu-liste etkinlik-menu-liste" id="etkinlikFiltre">
                    <li class="nav-item">
                        <button class="nav-link active w-100 text-start btn-filtre-etkinlik etkinlik-tum-kategoriler" data-filtre="hepsi">Tüm Kategoriler</button>
                    </li>
                    <?php foreach ($turler as $t): ?>
                    <li class="nav-item">
                        <button class="nav-link w-100 text-start btn-filtre-etkinlik" data-filtre="<?php echo htmlspecialchars($t); ?>">
                            <span class="hizmet-kutu"><i class="bi <?php echo $turIkon[$t] ?? 'bi-tag-fill'; ?>"></i></span><?php echo htmlspecialchars($t); ?>
                        </button>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="row g-4" id="etkinlikListesi">
                <?php if (count($etkinlikler) === 0): ?>
                    <p class="text-muted">Şu anda yaklaşan etkinlik bulunmuyor.</p>
                <?php endif; ?>

                <?php foreach ($etkinlikler as $e): ?>
                    <?php
                        // resim_url veritabanında bazen tam bağlantı (https://...), bazen kök dizine
                        // göre kısa yol ("uploads/x.jpg") olarak saklanıyor.
                        $eResimSrc = ($e['resim_url'] !== '' && !preg_match('/^https?:\/\//i', $e['resim_url']))
                            ? '../' . $e['resim_url']
                            : $e['resim_url'];
                    ?>
                    <div class="col-lg-4 col-md-6 etkinlik-karti-sarmal" data-tur="<?php echo htmlspecialchars($e['tur']); ?>">
                        <a href="etkinlik-detay.php?id=<?php echo $e['id']; ?>" class="card duyuru-karti h-100 text-decoration-none text-reset d-block">
                            <img src="<?php echo htmlspecialchars($eResimSrc); ?>"
                                 class="card-img-top" alt="<?php echo htmlspecialchars($e['baslik']); ?>">
                            <div class="card-body">
                                <span class="badge badge-kategori text-white mb-2"><?php echo htmlspecialchars($e['tur']); ?></span>
                                <h6 class="fw-bold"><?php echo htmlspecialchars($e['baslik']); ?></h6>
                                <p class="small text-muted mb-1">
                                    <i class="bi bi-geo-alt-fill me-1"></i><?php echo htmlspecialchars($e['mekan']); ?>
                                </p>
                                <p class="small text-muted mb-0">
                                    <i class="bi bi-calendar-event me-1"></i>
                                    <?php echo date('d.m.Y', strtotime($e['etkinlik_tarihi'])); ?>
                                    <?php echo $e['etkinlik_saati'] ? ' - ' . htmlspecialchars($e['etkinlik_saati']) : ''; ?>
                                </p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const butonlar = document.querySelectorAll('.btn-filtre-etkinlik');
    const kartlar = document.querySelectorAll('.etkinlik-karti-sarmal');

    butonlar.forEach(function (buton) {
        buton.addEventListener('click', function () {
            butonlar.forEach(b => b.classList.remove('active'));
            buton.classList.add('active');

            const secilenFiltre = buton.getAttribute('data-filtre');

            kartlar.forEach(function (kart) {
                if (secilenFiltre === 'hepsi' || kart.getAttribute('data-tur') === secilenFiltre) {
                    kart.style.display = '';
                } else {
                    kart.style.display = 'none';
                }
            });
        });
    });
});
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>
