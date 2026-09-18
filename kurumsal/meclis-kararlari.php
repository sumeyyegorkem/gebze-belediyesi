<?php
require_once '../config/db.php';
$kararlar = $pdo->query("SELECT * FROM meclis_kararlari WHERE aktif = 1 ORDER BY yil DESC, ay DESC, id DESC")->fetchAll();
$mevcutYillar = $pdo->query("SELECT DISTINCT yil FROM meclis_kararlari ORDER BY yil DESC")->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meclis Kararları | Gebze Belediyesi</title>
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
        <h1 class="mb-0"><i class="bi bi-file-earmark-text-fill me-2"></i>Meclis Kararları</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            <?php $aktifKurumsal = 'meclis-kararlari'; include '../includes/kurumsal-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Meclis Kararları'; include '../includes/kurumsal-icerik-ust.php'; ?>

            <!-- Yıl/Ay Filtreleri -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Yıl</label>
                    <select id="yilFiltre" class="form-select">
                        <option value="hepsi">Tüm Yıllar</option>
                        <?php foreach ($mevcutYillar as $yilSecenegi): ?>
                            <option value="<?php echo htmlspecialchars($yilSecenegi); ?>"><?php echo htmlspecialchars($yilSecenegi); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-semibold">Ay</label>
                    <select id="ayFiltre" class="form-select">
                        <option value="hepsi">Tüm Aylar</option>
                        <option value="01">Ocak</option>
                        <option value="02">Şubat</option>
                        <option value="03">Mart</option>
                        <option value="04">Nisan</option>
                        <option value="05">Mayıs</option>
                        <option value="06">Haziran</option>
                        <option value="07">Temmuz</option>
                        <option value="08">Ağustos</option>
                        <option value="09">Eylül</option>
                        <option value="10">Ekim</option>
                        <option value="11">Kasım</option>
                        <option value="12">Aralık</option>
                    </select>
                </div>
            </div>

            <div id="kararListesi">
                <?php
                $kararSayfaBoyutu = 5;
                foreach ($kararlar as $i => $k):
                ?>
                <div class="hizmet-kutu d-flex align-items-center justify-content-between text-start mb-3 karar-satiri"
                     data-yil="<?php echo htmlspecialchars($k['yil']); ?>" data-ay="<?php echo htmlspecialchars($k['ay']); ?>" data-sayfa="<?php echo intdiv($i, $kararSayfaBoyutu) + 1; ?>">
                    <div>
                        <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($k['baslik']); ?></h6>
                        <small class="text-muted"><?php echo htmlspecialchars($k['aciklama']); ?></small>
                    </div>
                    <?php if ($k['pdf_url']): ?>
                    <a href="<?php echo htmlspecialchars($k['pdf_url']); ?>" target="_blank" class="btn btn-sm btn-lacivert flex-shrink-0">
                        <i class="bi bi-file-earmark-pdf-fill me-1"></i>PDF İndir
                    </a>
                    <?php else: ?>
                    <small class="text-muted flex-shrink-0"><i class="bi bi-dash-circle me-1"></i>Bağlantı paylaşılmadı</small>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
                <p class="text-muted" id="kararSonucYok" style="display:none;">Seçtiğiniz döneme ait meclis kararı bulunamadı.</p>
            </div>

            <nav class="mt-4">
                <ul class="pagination justify-content-center" id="kararSayfalama"></ul>
            </nav>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const yilFiltre = document.getElementById('yilFiltre');
    const ayFiltre = document.getElementById('ayFiltre');
    const satirlar = document.querySelectorAll('.karar-satiri');
    const sonucYok = document.getElementById('kararSonucYok');
    const sayfalamaEl = document.getElementById('kararSayfalama');
    let toplamSayfa = 1;
    satirlar.forEach(function (satir) {
        toplamSayfa = Math.max(toplamSayfa, parseInt(satir.getAttribute('data-sayfa'), 10));
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
        satirlar.forEach(function (satir) {
            // Not: satir "d-flex" sınıfına sahip; Bootstrap bu sınıfı !important ile
            // tanımladığından style.display='none' onu gizleyemiyordu. Bunun yerine
            // "d-none" sınıfı ekleyip çıkarıyoruz (o da !important, ve d-flex'ten sonra
            // tanımlı olduğu için üstün geliyor).
            const gorunecek = parseInt(satir.getAttribute('data-sayfa'), 10) === aktifSayfa;
            satir.classList.toggle('d-none', !gorunecek);
        });
    }

    sayfalamaCiz();
    sayfayiGoster();

    function filtrele() {
        const yil = yilFiltre.value;
        const ay = ayFiltre.value;

        if (yil === 'hepsi' && ay === 'hepsi') {
            sayfalamaEl.parentElement.style.display = '';
            aktifSayfa = 1;
            sayfalamaCiz();
            sayfayiGoster();
            sonucYok.style.display = 'none';
            return;
        }

        sayfalamaEl.parentElement.style.display = 'none';
        let gorunen = 0;
        satirlar.forEach(function (satir) {
            const yilUyuyor = (yil === 'hepsi' || satir.getAttribute('data-yil') === yil);
            const ayUyuyor = (ay === 'hepsi' || satir.getAttribute('data-ay') === ay);
            if (yilUyuyor && ayUyuyor) {
                satir.classList.remove('d-none');
                gorunen++;
            } else {
                satir.classList.add('d-none');
            }
        });
        sonucYok.style.display = (gorunen === 0) ? 'block' : 'none';
    }

    yilFiltre.addEventListener('change', filtrele);
    ayFiltre.addEventListener('change', filtrele);
});
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>
