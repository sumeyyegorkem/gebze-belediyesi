<?php require_once '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yönetim Şeması | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=67" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
    <style>
        /* Bu stil SADECE bu sayfada geçerli, siteyi etkilemez */
        .page-item.active .page-link { background-color: var(--lacivert); border-color: var(--lacivert); }
        .page-link { color: var(--lacivert); }
        .page-link:hover { color: var(--lacivert-koyu); }
        .page-link:focus { box-shadow: none; }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-diagram-3-fill me-2"></i>Yönetim Şeması</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            <?php $aktifKurumsal = 'yonetim-semasi'; include '../includes/kurumsal-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Yönetim Şeması'; include '../includes/kurumsal-icerik-ust.php'; ?>

            <!-- Başkan -->
            <div class="hizmet-kutu text-start d-flex align-items-center gap-3 mb-4" style="max-width:420px;">
                <img src="https://www.gebze.bel.tr/resim/20191114113459.jpg" alt="Zinnur Büyükgöz"
                     class="rounded-circle" style="width:72px;height:72px;object-fit:cover;flex-shrink:0;"
                     onerror="this.src='img/baskan.jpg';">
                <div>
                    <h5 class="fw-bold mb-0">Zinnur Büyükgöz</h5>
                    <small class="text-muted">Belediye Başkanı</small>
                </div>
            </div>

            <!-- Başkana Bağlı Birimler -->
            <h6 class="fw-bold text-muted mb-3">Başkana Bağlı Birimler</h6>
            <div class="row g-3 mb-5">
                <?php
                // Veritabani: mudurlukler tablosu, baskan_yardimcisi_id NULL olanlar dogrudan Baskana baglidir
                $baskanaBagli = $pdo->query("SELECT ad, mudur FROM mudurlukler WHERE baskan_yardimcisi_id IS NULL AND aktif = 1 ORDER BY sira ASC")->fetchAll();
                foreach ($baskanaBagli as $b):
                ?>
                <div class="col-md-4 col-sm-6">
                    <div class="hizmet-kutu text-start h-100">
                        <h6 class="fw-bold mb-1" style="font-size:.92rem;"><?php echo htmlspecialchars($b['ad']); ?></h6>
                        <small class="text-muted"><?php echo htmlspecialchars($b['mudur']); ?></small>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Başkan Yardımcıları ve bağlı müdürlükler -->
            <h6 class="fw-bold text-muted mb-3">Başkan Yardımcıları ve Bağlı Müdürlükler</h6>
            <?php
            // Veritabani: baskan_yardimcilari + mudurlukler tablolari
            $yardimcilarDb = $pdo->query("SELECT * FROM baskan_yardimcilari WHERE aktif = 1 ORDER BY sira ASC")->fetchAll();
            $yardimcilar = [];
            foreach ($yardimcilarDb as $yy) {
                $mStmt = $pdo->prepare("SELECT ad, mudur FROM mudurlukler WHERE baskan_yardimcisi_id = :id AND aktif = 1 ORDER BY sira ASC");
                $mStmt->execute(['id' => $yy['id']]);
                $mudurlukleri = [];
                foreach ($mStmt->fetchAll() as $mm) {
                    $mudurlukleri[] = [$mm['ad'], $mm['mudur']];
                }
                $yardimcilar[] = ['ad' => $yy['ad'], 'foto' => $yy['foto'], 'slug' => $yy['slug'], 'mudurlukler' => $mudurlukleri];
            }
            ?>
            <div id="yardimciListesi">
            <?php
            foreach ($yardimcilar as $index => $y):
                // foto veritabanında bazen tam bağlantı (https://...) bazen de
                // kök dizine göre kısa yol ("img/x.jpg") olarak saklanıyor.
                $yFotoSrc = preg_match('/^https?:\/\//i', $y['foto']) ? $y['foto'] : '../' . $y['foto'];
            ?>
            <div class="mb-4 yardimci-blok" id="<?php echo $y['slug']; ?>" data-sayfa="<?php echo $index + 1; ?>">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <img src="<?php echo htmlspecialchars($yFotoSrc); ?>" alt="<?php echo htmlspecialchars($y['ad']); ?>"
                         class="rounded-circle" style="width:96px;height:96px;object-fit:cover;flex-shrink:0;"
                         onerror="this.src='https://placehold.co/96x96?text=%20';">
                    <div>
                        <h4 class="fw-bold mb-0"><?php echo htmlspecialchars($y['ad']); ?></h4>
                        <span class="text-muted fs-6">Başkan Yardımcısı</span>
                    </div>
                </div>
                <div class="row g-3">
                    <?php foreach ($y['mudurlukler'] as $m): ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="hizmet-kutu text-start h-100" style="padding:24px 20px;">
                                <h6 class="fw-bold mb-1" style="font-size:1.05rem;"><?php echo htmlspecialchars($m[0]); ?></h6>
                                <span class="text-muted" style="font-size:.95rem;"><?php echo htmlspecialchars($m[1]); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
            </div>

            <nav class="mt-4">
                <ul class="pagination justify-content-center" id="yardimciSayfalama"></ul>
            </nav>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const bloklar = document.querySelectorAll('.yardimci-blok');
    const sayfalamaEl = document.getElementById('yardimciSayfalama');
    const toplamSayfa = bloklar.length;
    let aktifSayfa = 1;

    const hedefId = window.location.hash ? window.location.hash.substring(1) : '';
    if (hedefId) {
        const hedefBlok = document.getElementById(hedefId);
        if (hedefBlok && hedefBlok.classList.contains('yardimci-blok')) {
            aktifSayfa = parseInt(hedefBlok.getAttribute('data-sayfa'), 10);
        }
    }

    function sayfalamaCiz() {
        sayfalamaEl.innerHTML = '';
        for (let s = 1; s <= toplamSayfa; s++) {
            const li = document.createElement('li');
            li.className = 'page-item' + (s === aktifSayfa ? ' active' : '');
            const a = document.createElement('a');
            a.className = 'page-link rounded-circle mx-1';
            a.href = '#';
            a.textContent = s;
            a.addEventListener('click', function (e) {
                e.preventDefault();
                aktifSayfa = s;
                sayfayiGoster();
                sayfalamaCiz();
            });
            li.appendChild(a);
            sayfalamaEl.appendChild(li);
        }
    }

    function sayfayiGoster() {
        bloklar.forEach(function (blok) {
            blok.style.display = (parseInt(blok.getAttribute('data-sayfa'), 10) === aktifSayfa) ? '' : 'none';
        });
    }

    sayfalamaCiz();
    sayfayiGoster();
});
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>
