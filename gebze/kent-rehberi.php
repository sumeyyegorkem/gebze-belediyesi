<?php
require_once '../config/db.php';
$mekanlar = $pdo->query("SELECT id, ikon, baslik, kisa_aciklama, resim_url, detay FROM tarihi_yerler WHERE aktif = 1 ORDER BY sira ASC, id ASC")->fetchAll();

// Her tarihi yerin KENDİ fotoğraf galerisi olsun diye (tüm sayfanın altında ortak
// bir galeri yerine), her mekan için "tarihi-yer-{id}" anahtarıyla ayrı ayrı
// sorgulanır ve mekanın detay paneline gömülür.
// Tablo oluşturma/migrasyon kontrolü includes/fotograf-galerisi-bolum.php ile
// ortak kullanıldığı için tek bir ortak dosyaya taşındı (tekrar yazılmasın diye).
require_once '../includes/fotograf-galerisi-tablo-kontrol.php';
$mekanGaleriStmt = $pdo->prepare("SELECT resim_url, baslik FROM fotograf_galerisi WHERE sayfa = :sayfa ORDER BY id ASC");
foreach ($mekanlar as &$mekanGaleriRef) {
    $mekanGaleriStmt->execute(['sayfa' => 'tarihi-yer-' . $mekanGaleriRef['id']]);
    $mekanGaleriRef['galeri'] = $mekanGaleriStmt->fetchAll();

    // Bu sayfa artık gebze/ klasöründe olduğu için, veritabanında kök dizine göre
    // saklanan resim yollarının (resim_url) başına "../" eklenir — JS bu değerleri
    // doğrudan <img src> olarak kullanıyor. resim_url bazen tam bağlantı (https://...)
    // olarak saklanmış olabilir; o durumda olduğu gibi bırakılır.
    if (!empty($mekanGaleriRef['resim_url']) && !preg_match('/^https?:\/\//i', $mekanGaleriRef['resim_url'])) {
        $mekanGaleriRef['resim_url'] = '../' . $mekanGaleriRef['resim_url'];
    }
    foreach ($mekanGaleriRef['galeri'] as &$mekanGaleriFoto) {
        if (!empty($mekanGaleriFoto['resim_url']) && !preg_match('/^https?:\/\//i', $mekanGaleriFoto['resim_url'])) {
            $mekanGaleriFoto['resim_url'] = '../' . $mekanGaleriFoto['resim_url'];
        }
    }
    unset($mekanGaleriFoto);
}
unset($mekanGaleriRef);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarihi Yerler | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=68" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
    <style>
        /* Bu stil SADECE bu sayfada (kent-rehberi.php) geçerli, siteyi etkilemez */
        #mekanlar i.text-danger { color: var(--lacivert) !important; }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-compass-fill me-2"></i>Tarihi Yerler</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4 mb-lg-0">
            <?php $aktifGebze = 'tarihi-yerler'; include '../includes/gebze-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Tarihi Yerler'; include '../includes/kurumsal-icerik-ust.php'; ?>

    <!-- Tarihi Mekanlar -->
    <section id="mekanlar" class="mb-5">
        <div class="row g-4">
            <?php foreach ($mekanlar as $i => $m): ?>
            <div class="col-md-3 col-sm-6">
                <button type="button" class="hizmet-kutu w-100 h-100 border-0 mekan-tetik" data-index="<?php echo $i; ?>">
                    <i class="bi <?php echo htmlspecialchars($m['ikon']); ?> fs-3 text-danger mb-2"></i>
                    <h6 class="fw-bold"><?php echo htmlspecialchars($m['baslik']); ?></h6>
                    <p class="small text-muted mb-0"><?php echo htmlspecialchars($m['kisa_aciklama']); ?></p>
                </button>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Tıklanan mekanın resim + yazı panelinin göründüğü alan -->
        <div id="mekanDetayPaneli" class="form-kutu mt-4" style="display:none;">
            <div class="row g-4 align-items-center">
                <div class="col-md-5">
                    <img id="mekanDetayResim" src="" alt="" class="img-fluid rounded-3 w-100" style="max-height:260px;object-fit:cover;cursor:pointer;">
                </div>
                <div class="col-md-7">
                    <div class="d-flex justify-content-between align-items-start">
                        <h4 class="fw-bold" id="mekanDetayBaslik"></h4>
                        <button type="button" class="btn-close" id="mekanDetayKapat" aria-label="Kapat"></button>
                    </div>
                    <p class="metin-govde text-muted mb-0" id="mekanDetayMetin"></p>
                </div>
            </div>

            <!-- Bu mekana özel fotoğraf galerisi (başlığın altında, sayfanın genelinde değil) -->
            <div id="mekanDetayGaleriAlani" class="mt-4" style="display:none;">
                <h6 class="fw-bold mb-3"><i class="bi bi-images me-1"></i> Fotoğraf Galerisi</h6>
                <div class="row g-3" id="mekanDetayGaleri"></div>
            </div>
        </div>

        <!-- Fotoğrafa tıklayınca büyük halinin göründüğü kutucuk -->
        <div class="modal fade" id="galeriBuyutmeModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content bg-transparent border-0">
                    <button type="button" class="btn-close btn-close-white position-absolute" data-bs-dismiss="modal" aria-label="Kapat"
                            style="top:-38px;right:0;z-index:2;"></button>
                    <button type="button" class="btn btn-light rounded-circle galeri-onceki d-flex align-items-center justify-content-center"
                            aria-label="Önceki fotoğraf" style="position:absolute;left:10px;top:50%;transform:translateY(-50%);width:44px;height:44px;z-index:2;opacity:.85;">
                        <i class="bi bi-chevron-left fs-5"></i>
                    </button>
                    <img src="" id="galeriBuyukResim" class="img-fluid rounded-3 w-100" alt="">
                    <button type="button" class="btn btn-light rounded-circle galeri-sonraki d-flex align-items-center justify-content-center"
                            aria-label="Sonraki fotoğraf" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);width:44px;height:44px;z-index:2;opacity:.85;">
                        <i class="bi bi-chevron-right fs-5"></i>
                    </button>
                </div>
            </div>
        </div>

        <script>
        const mekanVerisi = <?php echo json_encode($mekanlar, JSON_UNESCAPED_UNICODE); ?>;
        document.addEventListener('DOMContentLoaded', function () {
            const panel = document.getElementById('mekanDetayPaneli');
            const galeriAlani = document.getElementById('mekanDetayGaleriAlani');
            const galeriKutu = document.getElementById('mekanDetayGaleri');

            const buyutmeModalEl = document.getElementById('galeriBuyutmeModal');
            const buyutmeModal = new bootstrap.Modal(buyutmeModalEl);
            const buyukResim = document.getElementById('galeriBuyukResim');
            const oncekiBtn = buyutmeModalEl.querySelector('.galeri-onceki');
            const sonrakiBtn = buyutmeModalEl.querySelector('.galeri-sonraki');

            // O an açık olan mekanın tüm fotoğrafları (ana fotoğraf + galeri fotoğrafları),
            // aralarında sağ/sol ok ile gezinebilmek için burada tutulur.
            let mevcutFotograflar = [];
            let aktifFotoIndex = 0;

            function oklariGuncelle() {
                const gizle = mevcutFotograflar.length <= 1;
                oncekiBtn.style.display = gizle ? 'none' : '';
                sonrakiBtn.style.display = gizle ? 'none' : '';
            }

            function fotografiGoster(index) {
                if (mevcutFotograflar.length === 0) return;
                aktifFotoIndex = (index + mevcutFotograflar.length) % mevcutFotograflar.length;
                const secilen = mevcutFotograflar[aktifFotoIndex];
                buyukResim.src = secilen.src;
                buyukResim.alt = secilen.alt;
            }

            function fotografiBuyut(index) {
                fotografiGoster(index);
                buyutmeModal.show();
            }

            oncekiBtn.addEventListener('click', function () { fotografiGoster(aktifFotoIndex - 1); });
            sonrakiBtn.addEventListener('click', function () { fotografiGoster(aktifFotoIndex + 1); });
            buyutmeModalEl.addEventListener('keydown', function (e) {
                if (e.key === 'ArrowLeft') fotografiGoster(aktifFotoIndex - 1);
                if (e.key === 'ArrowRight') fotografiGoster(aktifFotoIndex + 1);
            });

            document.getElementById('mekanDetayResim').addEventListener('click', function () {
                if (this.src) fotografiBuyut(0);
            });

            document.querySelectorAll('.mekan-tetik').forEach(function (buton) {
                buton.addEventListener('click', function () {
                    const veri = mekanVerisi[this.getAttribute('data-index')];
                    document.getElementById('mekanDetayBaslik').textContent = veri.baslik;
                    document.getElementById('mekanDetayMetin').textContent = veri.detay;
                    const resim = document.getElementById('mekanDetayResim');
                    resim.src = veri.resim_url;
                    resim.alt = veri.baslik;
                    resim.onerror = function () {
                        this.onerror = null;
                        this.src = 'https://placehold.co/500x350?text=Fotoğraf+Ekleyin';
                    };

                    galeriKutu.innerHTML = '';
                    const galeri = veri.galeri || [];

                    // Bu mekanın ana fotoğrafı + galeri fotoğrafları, büyütme kutusunda
                    // sağ/sol ok ile gezinebilmek için tek bir listede toplanır.
                    mevcutFotograflar = [{ src: veri.resim_url, alt: veri.baslik }];

                    if (galeri.length > 0) {
                        galeri.forEach(function (gf) {
                            const sutun = document.createElement('div');
                            sutun.className = 'col-md-3 col-6';
                            const img = document.createElement('img');
                            img.src = gf.resim_url;
                            img.alt = gf.baslik || veri.baslik;
                            img.className = 'img-fluid rounded-3 w-100';
                            img.style.height = '160px';
                            img.style.objectFit = 'cover';
                            img.style.cursor = 'pointer';
                            img.onerror = function () {
                                this.onerror = null;
                                this.src = 'https://placehold.co/400x300?text=Fotoğraf+Ekleyin';
                            };
                            const fotoIndex = mevcutFotograflar.length;
                            mevcutFotograflar.push({ src: gf.resim_url, alt: gf.baslik || veri.baslik });
                            img.addEventListener('click', function () {
                                fotografiBuyut(fotoIndex);
                            });
                            sutun.appendChild(img);
                            galeriKutu.appendChild(sutun);
                        });
                        galeriAlani.style.display = 'block';
                    } else {
                        galeriAlani.style.display = 'none';
                    }
                    oklariGuncelle();

                    panel.style.display = 'block';
                    panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            });
            document.getElementById('mekanDetayKapat').addEventListener('click', function () {
                panel.style.display = 'none';
            });
        });
        </script>
    </section>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
