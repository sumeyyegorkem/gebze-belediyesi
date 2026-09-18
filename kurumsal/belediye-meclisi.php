<?php
require_once '../config/db.php';
$uyeler = $pdo->query("SELECT ad_soyad, foto_url FROM meclis_uyeleri WHERE aktif = 1 ORDER BY sira ASC, id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Belediye Meclisi | Gebze Belediyesi</title>
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
        <h1 class="mb-0"><i class="bi bi-bank2 me-2"></i>Belediye Meclisi</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            <?php $aktifKurumsal = 'meclis'; include '../includes/kurumsal-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Belediye Meclisi'; include '../includes/kurumsal-icerik-ust.php'; ?>
            <!-- Belediye Başkanı (Meclis Başkanı) -->
            <div class="hizmet-kutu d-flex flex-column align-items-center text-center mb-4 mx-auto" style="max-width:420px; height:auto; padding:2.5rem 1.5rem;">
                <img src="https://www.gebze.bel.tr/resim/20191114113633.jpg" alt="Zinnur Büyükgöz"
                     class="rounded-circle mb-3" style="width:140px;height:140px;object-fit:cover;">
                <h4 class="fw-bold mb-0">Zinnur Büyükgöz</h4>
                <span class="text-muted">Belediye Başkanı</span>
            </div>

            <!-- Arama kutusu -->
            <h6 class="fw-bold text-muted mb-2">Meclis Üyeleri</h6>
            <div class="mb-3" style="max-width:400px;">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" id="meclisArama" class="form-control border-start-0" placeholder="Üye adı ara...">
                </div>
            </div>

            <div class="row row-cols-2 row-cols-sm-3 row-cols-lg-5 g-3" id="meclisListesi">
                <?php
                $sayfaBasinaUye = 10;
                foreach ($uyeler as $index => $uye):
                    $ad = $uye['ad_soyad'];
                    $foto = $uye['foto_url'];
                    // foto_url veritabanında bazen tam bağlantı (https://...) bazen de
                    // kök dizine göre kısa yol ("img/x.jpg") olarak saklanıyor.
                    $fotoSrc = preg_match('/^https?:\/\//i', $foto) ? $foto : '../' . $foto;
                    $sayfaNo = intdiv($index, $sayfaBasinaUye) + 1;
                ?>
                <div class="col meclis-karti" data-arama="<?php echo mb_strtolower(htmlspecialchars($ad)); ?>" data-sayfa="<?php echo $sayfaNo; ?>">
                    <div class="card duyuru-karti h-100 text-center border-0">
                        <img src="<?php echo htmlspecialchars($fotoSrc); ?>" alt="<?php echo htmlspecialchars($ad); ?>"
                             class="card-img-top" style="height:170px;object-fit:cover;object-position:top;">
                        <div class="card-body py-3 px-2">
                            <h6 class="fw-bold mb-0" style="font-size:.88rem;"><?php echo htmlspecialchars($ad); ?></h6>
                            <small class="text-muted">Meclis Üyesi</small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
                <p class="text-muted" id="meclisSonucYok" style="display:none;">Aramanıza uygun üye bulunamadı.</p>
            </div>

            <nav class="mt-4">
                <ul class="pagination justify-content-center" id="meclisSayfalama"></ul>
            </nav>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const arama = document.getElementById('meclisArama');
    const kartlar = document.querySelectorAll('.meclis-karti');
    const sonucYok = document.getElementById('meclisSonucYok');
    const sayfalamaEl = document.getElementById('meclisSayfalama');
    const toplamSayfa = Math.max(...[...kartlar].map(k => parseInt(k.getAttribute('data-sayfa'), 10)));
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
        let gorunen = 0;
        kartlar.forEach(function (kart) {
            if (parseInt(kart.getAttribute('data-sayfa'), 10) === aktifSayfa) {
                kart.style.display = '';
                gorunen++;
            } else {
                kart.style.display = 'none';
            }
        });
        sonucYok.style.display = (gorunen === 0) ? 'block' : 'none';
    }

    arama.addEventListener('input', function () {
        const metin = this.value.trim().toLowerCase();

        if (metin === '') {
            sayfalamaEl.parentElement.style.display = '';
            aktifSayfa = 1;
            sayfalamaCiz();
            sayfayiGoster();
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

    sayfalamaCiz();
    sayfayiGoster();
});
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>
