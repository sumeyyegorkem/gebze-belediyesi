<?php require_once '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mahalle Muhtarları | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=68" rel="stylesheet">
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
        <h1 class="mb-0"><i class="bi bi-signpost-split-fill me-2"></i>Mahalle Muhtarlıkları</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4 mb-lg-0">
            <?php $aktifGebze = 'muhtarlar'; include '../includes/gebze-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Mahalle Muhtarlıkları'; include '../includes/kurumsal-icerik-ust.php'; ?>

    <!-- Arama kutusu -->
    <div class="mb-4" style="max-width:400px;">
        <div class="input-group">
            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
            <input type="text" id="muhtarArama" class="form-control border-start-0"
                   placeholder="Mahalle veya muhtar adı ara...">
        </div>
    </div>

    <div class="row g-3" id="muhtarListesi">
        <?php
        // Artık veritabanından geliyor (admin panelinden ekle/çıkar/düzenle yapılabilir).
        $muhtarlar = $pdo->query("SELECT * FROM muhtarlar WHERE aktif = 1 ORDER BY sira ASC, id ASC")->fetchAll();

        // Renk paleti: her muhtara isim sırasına göre farklı bir renk atanır
        $renkPaleti = ['#0b3d62', '#d4a53d', '#1e7e5e', '#8e44ad', '#b7791f', '#2c5f8a', '#082c47', '#2f7a4f'];

        // Sayfalama: kartlar sayfa başına 10 muhtar olacak şekilde bölünür
        $muhtarSayfaBoyutu = 10;

        foreach ($muhtarlar as $index => $m):
            $telefonHref = 'tel:+90' . preg_replace('/\D/', '', $m['tel']);
            $isimParcalari = explode(' ', trim($m['ad']));
            $basHarfler = mb_substr($isimParcalari[0], 0, 1) . mb_substr(end($isimParcalari), 0, 1);
            $renk = $renkPaleti[$index % count($renkPaleti)];
        ?>
        <div class="col-md-6 muhtar-karti" data-arama="<?php echo mb_strtolower(htmlspecialchars($m['ad'] . ' ' . $m['mahalle'])); ?>" data-sayfa="<?php echo intdiv($index, $muhtarSayfaBoyutu) + 1; ?>">
            <div class="hizmet-kutu h-100 text-start">
                <div class="d-flex align-items-center mb-2">
                    <?php if (!empty($m['foto'])): ?>
                    <?php
                        // foto veritabanında bazen tam bağlantı (https://...) bazen de
                        // kök dizine göre kısa yol ("img/x.jpg") olarak saklanıyor.
                        $mFotoSrc = preg_match('/^https?:\/\//i', $m['foto']) ? $m['foto'] : '../' . $m['foto'];
                    ?>
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2 fw-bold text-white position-relative overflow-hidden"
                         style="width:52px;height:52px;background:<?php echo $renk; ?>;flex-shrink:0;font-size:1.1rem;letter-spacing:.5px;">
                        <?php echo mb_strtoupper($basHarfler); ?>
                        <img src="<?php echo htmlspecialchars($mFotoSrc); ?>" alt="<?php echo htmlspecialchars($m['ad']); ?>"
                             class="position-absolute top-0 start-0 w-100 h-100" style="object-fit:cover;"
                             onerror="this.remove();">
                    </div>
                    <?php else: ?>
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-2 position-relative overflow-hidden"
                         style="width:52px;height:52px;background:#ffffff;border:2px solid var(--lacivert);flex-shrink:0;">
                        <img src="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg" alt="Gebze Belediyesi"
                             style="width:65%;height:65%;object-fit:contain;">
                    </div>
                    <?php endif; ?>
                    <div>
                        <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($m['ad']); ?></h6>
                        <small class="text-muted"><?php echo htmlspecialchars($m['mahalle']); ?></small>
                    </div>
                </div>
                <div class="small mb-1">
                    <a href="<?php echo $telefonHref; ?>" class="text-decoration-none">
                        <i class="bi bi-telephone-fill text-danger me-1"></i><?php echo htmlspecialchars($m['tel']); ?>
                    </a>
                </div>
                <?php if (!empty($m['eposta'])): ?>
                <div class="small mb-1">
                    <a href="mailto:<?php echo htmlspecialchars($m['eposta']); ?>" class="text-decoration-none text-muted">
                        <i class="bi bi-envelope-fill text-danger me-1"></i><?php echo htmlspecialchars($m['eposta']); ?>
                    </a>
                </div>
                <?php endif; ?>
                <?php if (!empty($m['adres'])): ?>
                <div class="small text-muted mb-1">
                    <i class="bi bi-geo-alt-fill text-danger me-1"></i><?php echo htmlspecialchars($m['adres']); ?>
                </div>
                <?php endif; ?>
                <?php if (!empty($m['harita'])): ?>
                <a href="<?php echo htmlspecialchars($m['harita']); ?>" target="_blank" class="small text-decoration-none">
                    <i class="bi bi-map-fill text-danger me-1"></i>Konuma Git
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>

        <p class="text-muted" id="muhtarSonucYok" style="display:none;">Aramanıza uygun muhtar bulunamadı.</p>
    </div>

    <nav class="mt-4">
        <ul class="pagination justify-content-center" id="muhtarSayfalama"></ul>
    </nav>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const arama = document.getElementById('muhtarArama');
    const kartlar = document.querySelectorAll('.muhtar-karti');
    const sonucYok = document.getElementById('muhtarSonucYok');
    const sayfalamaEl = document.getElementById('muhtarSayfalama');
    let toplamSayfa = 1;
    kartlar.forEach(function (kart) {
        toplamSayfa = Math.max(toplamSayfa, parseInt(kart.getAttribute('data-sayfa'), 10));
    });
    let aktifSayfa = 1;

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
        kartlar.forEach(function (kart) {
            kart.style.display = (parseInt(kart.getAttribute('data-sayfa'), 10) === aktifSayfa) ? '' : 'none';
        });
    }

    sayfalamaCiz();
    sayfayiGoster();

    arama.addEventListener('input', function () {
        const metin = this.value.trim().toLowerCase();

        if (metin === '') {
            sayfalamaEl.parentElement.style.display = '';
            aktifSayfa = 1;
            sayfalamaCiz();
            sayfayiGoster();
            sonucYok.style.display = 'none';
            return;
        }

        sayfalamaEl.parentElement.style.display = 'none';
        let gorunen = 0;
        kartlar.forEach(function (kart) {
            if (kart.getAttribute('data-arama').includes(metin)) {
                kart.style.display = '';
                gorunen++;
            } else {
                kart.style.display = 'none';
            }
        });
        sonucYok.style.display = (gorunen === 0) ? 'block' : 'none';
    });
});
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>
