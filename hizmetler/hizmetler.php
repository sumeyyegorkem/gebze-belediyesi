<?php
require_once '../config/db.php';
$hizmetlerListesi = $pdo->query("SELECT ikon, resim_url AS gorsel, baslik, ozet, detay, link FROM hizmet_kartlari WHERE aktif = 1 ORDER BY sira ASC, id ASC")->fetchAll();

// hizmetler.php artık hizmetler/ klasörünün içinde yaşıyor. Veritabanındaki "link" değerleri
// farklı biçimlerde saklanmış olabilir: bazen "hizmetler/x.php" (taşınmadan önceki köke göre
// yazılmış), bazen sadece "x.php", bazen de "e-belediye.php?..." gibi kök dizindeki başka bir
// sayfaya işaret ediyor (E-Belediye de kendi "e-belediye/" klasörüne taşındı). Görüntüleme
// anında bu durumların hepsini doğru hedefe çeviriyoruz.
$tasinanHizmetSayfalari = [
    'nikah-islemleri.php', 'fen-isleri.php', 'zabita.php', 'emlak-istimlak.php',
    'temizlik-isleri.php', 'kultur-sosyal-isler.php', 'veteriner-hizmetleri.php',
];
foreach ($hizmetlerListesi as &$hgecici) {
    if (!empty($hgecici['link']) && !preg_match('/^https?:\/\//i', $hgecici['link'])) {
        $link = $hgecici['link'];
        // "hizmetler/" öneki varsa temizle (hizmetler.php artık aynı klasörde, önek gereksiz)
        if (strpos($link, 'hizmetler/') === 0) {
            $link = substr($link, strlen('hizmetler/'));
        }
        $dosyaAdi = strtok($link, '?');
        if (in_array($dosyaAdi, $tasinanHizmetSayfalari, true)) {
            // Aynı klasördeki (hizmetler/) bir sayfa, olduğu gibi bırak
            $hgecici['link'] = $link;
        } elseif (strpos($link, 'e-belediye.php') === 0) {
            // E-Belediye de kendi klasörüne taşındığı için bir üst dizinden oraya iniyoruz
            $hgecici['link'] = '../e-belediye/' . $link;
        } else {
            // Kök dizinde kalan diğer sayfalar (varsa) için bir üst dizine çık
            $hgecici['link'] = '../' . $link;
        }
    }

    // Kart görseli (resim_url), admin tarafında kök dizindeki "uploads/" klasörüne göre
    // saklanıyor. hizmetler.php artık bir alt klasörde yaşadığı için, dış bağlantı
    // (http/https) olmayan görsellerin başına bir üst dizine çıkmak için "../" eklenir.
    if (!empty($hgecici['gorsel']) && !preg_match('/^https?:\/\//i', $hgecici['gorsel'])) {
        $hgecici['gorsel'] = '../' . $hgecici['gorsel'];
    }
}
unset($hgecici);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hizmetler | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=64" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-grid-fill me-2"></i>Hizmetlerimiz</h1>
    </div>
</header>

<div class="container py-5">

    <div class="row g-4" id="hizmetGrid">
        <?php foreach ($hizmetlerListesi as $i => $h): ?>
        <div class="col-md-3 col-sm-6">
            <button type="button" class="hizmet-kutu w-100 border-0 hizmet-tetik" data-index="<?php echo $i; ?>">
                <div class="hizmet-ikon one-cikan-rozet"><i class="bi <?php echo $h['ikon']; ?>"></i></div>
                <h6 class="fw-bold"><?php echo htmlspecialchars($h['baslik']); ?></h6>
                <p class="small text-muted mb-0"><?php echo htmlspecialchars($h['ozet']); ?></p>
            </button>
        </div>
        <?php endforeach; ?>
    </div>

    <!-- Tıklanan hizmetin detayının göründüğü kutucuk (modal) -->
    <div class="modal fade" id="hizmetDetayModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;">
                <div style="height:200px;position:relative;" id="detayGorselAlani"></div>
                <button type="button" class="btn-close position-absolute" data-bs-dismiss="modal" aria-label="Kapat"
                        style="top:14px;right:14px;background-color:#fff;border-radius:50%;padding:10px;opacity:1;z-index:2;"></button>
                <div class="modal-body p-4">
                    <span class="fw-bold small text-uppercase" style="color:var(--altin); letter-spacing:.5px;" id="detayIkonBaslik"></span>
                    <h4 class="fw-bold mt-1" id="detayBaslik"></h4>
                    <p class="metin-govde text-muted mb-3" id="detayMetin"></p>
                    <a href="#" id="detayLink" class="btn btn-geri-kutu" style="display:none;">
                        Detaylı Bilgi / Randevu <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
// Her hizmetin bilgisini PHP'den JavaScript dizisine aktarıyoruz
const hizmetVerisi = <?php echo json_encode($hizmetlerListesi, JSON_UNESCAPED_UNICODE); ?>;

document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('hizmetDetayModal');
    const modal = new bootstrap.Modal(modalEl);
    const baslikEl = document.getElementById('detayBaslik');
    const metinEl = document.getElementById('detayMetin');
    const linkEl = document.getElementById('detayLink');
    const rozetEl = document.getElementById('detayIkonBaslik');
    const gorselAlani = document.getElementById('detayGorselAlani');

    document.querySelectorAll('.hizmet-tetik').forEach(function (buton) {
        buton.addEventListener('click', function () {
            const veri = hizmetVerisi[this.getAttribute('data-index')];

            rozetEl.textContent = veri.ozet;
            baslikEl.textContent = veri.baslik;
            metinEl.textContent = veri.detay;

            if (veri.gorsel) {
                gorselAlani.innerHTML = '<img src="' + veri.gorsel + '" alt="" style="width:100%;height:100%;object-fit:cover;">';
            } else {
                gorselAlani.innerHTML = '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;' +
                    'background:linear-gradient(135deg, var(--lacivert) 0%, var(--lacivert-koyu) 100%);">' +
                    '<i class="bi ' + veri.ikon + '" style="font-size:3.2rem;color:#fff;"></i></div>';
            }

            if (veri.link) {
                linkEl.href = veri.link;
                linkEl.style.display = 'inline-block';
            } else {
                linkEl.style.display = 'none';
            }

            modal.show();
        });
    });

    // Faaliyet Alanları sayfasından bir hizmete yönlendirme geldiyse ilgili kutucuğu otomatik aç
    const acilisHizmet = new URLSearchParams(window.location.search).get('hizmet');
    if (acilisHizmet) {
        const acilisIndex = hizmetVerisi.findIndex(function (h) {
            return h.link && h.link.split('?')[0].replace('.php', '').replace(/^hizmetler\//, '') === acilisHizmet;
        });
        if (acilisIndex !== -1) {
            document.querySelector('.hizmet-tetik[data-index="' + acilisIndex + '"]').click();
        }
    }
});
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>
