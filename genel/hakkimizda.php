<?php
require_once '../config/db.php';
$projeler = $pdo->query("SELECT * FROM projeler WHERE aktif = 1 ORDER BY olusturma_tarihi DESC LIMIT 4")->fetchAll();

// Başkanın biyografi metni admin panelinden düzenlenebilir
// (bkz. admin/icerik-sayfasi-duzenle.php?sayfa=hakkimizda).
$gbHakkimizdaStmt = $pdo->prepare("SELECT icerik FROM sabit_sayfa_icerikleri WHERE sayfa_anahtari = 'hakkimizda'");
$gbHakkimizdaStmt->execute();
$hakkimizdaIcerik = $gbHakkimizdaStmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hakkımızda | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=63" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-md-4 text-center">
                <div class="baskan-foto-dev mx-auto">
                    <img src="../img/baskan.jpg" alt="Zinnur Büyükgöz"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="baskan-foto-dev-yedek" style="display:none;">
                        <i class="bi bi-person-badge-fill"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-8 text-center text-md-start">
                <span class="badge mb-2" style="background: var(--altin); color: var(--lacivert-koyu);">Belediye Başkanı</span>
                <h1 class="fw-bold mb-1">Zinnur Büyükgöz</h1>
                <p class="lead text-white-50 mb-3">
                    Gebze Belediye Başkanı olarak, hemşehrilerimize katılımcı ve
                    şeffaf bir yönetim anlayışıyla hizmet vermeyi sürdürüyoruz.
                </p>
                <div class="mb-3">
                    <a href="https://www.facebook.com/zinnurbuyukgoz" target="_blank" class="baskan-sosyal-ikon"><i class="bi bi-facebook"></i></a>
                    <a href="https://twitter.com/zinnurbuyukgoz" target="_blank" class="baskan-sosyal-ikon"><i class="bi bi-twitter-x"></i></a>
                    <a href="https://www.instagram.com/zinnurbuyukgoz" target="_blank" class="baskan-sosyal-ikon"><i class="bi bi-instagram"></i></a>
                </div>

            </div>
        </div>
    </div>
</header>

<div class="container py-5">

    <!-- Başkanın Biyografisi -->
    <div class="row mb-5">
        <div class="col-lg-12">
            <div class="icerik-kutusu">
                <h2 class="bolum-baslik">Başkanın Biyografisi</h2>
                <?php if ($hakkimizdaIcerik !== false && trim(strip_tags($hakkimizdaIcerik)) !== ''): ?>
                    <?php echo $hakkimizdaIcerik; ?>
                <?php else: ?>
                    <p class="metin-govde">
                        1964 yılında Erzurum'da doğan Zinnur Büyükgöz, ilk ve orta öğrenimini İstanbul'da
                        tamamladıktan sonra 1983'te Gebze İmam Hatip Lisesi'nden mezun oldu. 1987 yılında
                        Yıldız Teknik Üniversitesi Mimarlık Fakültesi'nden Şehir ve Bölge Plancısı unvanıyla
                        mezun olan Büyükgöz, aynı üniversitede yüksek lisansını tamamlayarak 30 yılı aşkın
                        süredir şehir plancılığı mesleğini sürdürmektedir. Evli ve dört çocuk babasıdır.
                    </p>
                    <p class="metin-govde">
                        Siyasi hayatına Darıca Belde Başkanlığı ve çeşitli parti yönetim kurulu üyelikleriyle
                        başlayan Büyükgöz, 2004-2009 döneminde Gebze Belediyesi Teknik Başkan Yardımcılığı ve
                        Belediye Meclis Üyeliği ile Kocaeli Büyükşehir Belediyesi Meclis ve İmar Komisyonu
                        üyeliklerinde bulundu. 2004 yılından itibaren İstanbul, Bursa ve Kocaeli Kültür
                        Varlıklarını Koruma Bölge Kurulları'nda üyelik yaptı; ayrıca İdare Mahkemeleri'nde
                        bilirkişilik görevlerinde bulundu.
                    </p>
                    <p class="metin-govde">
                        2014-2016 yılları arasında İstanbul Ticaret Odası Proje Danışma Kurulu Üyesi olarak
                        görev yapan Büyükgöz, 2014'ten bu yana Teknopark İstanbul Proje Danışma Kurulu
                        Üyeliği'ni sürdürmektedir. 31 Mart 2019 Mahalli İdareler Seçimi'nde Gebze halkının
                        teveccühüyle Belediye Başkanı seçilmiş olup, halen bu görevi yürütmektedir.
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- İstatistikler -->
    <div class="row g-3 mb-5">
        <div class="col-md-3 col-6">
            <div class="hizmet-kutu">
                <div class="hizmet-ikon"><i class="bi bi-people-fill"></i></div>
                <h3 class="fw-bold mb-0">450K+</h3>
                <p class="small text-muted mb-0">Nüfusa Hizmet</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="hizmet-kutu">
                <div class="hizmet-ikon"><i class="bi bi-signpost-split-fill"></i></div>
                <h3 class="fw-bold mb-0">40</h3>
                <p class="small text-muted mb-0">Mahalle</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="hizmet-kutu">
                <div class="hizmet-ikon"><i class="bi bi-building"></i></div>
                <h3 class="fw-bold mb-0">12</h3>
                <p class="small text-muted mb-0">Müdürlük</p>
            </div>
        </div>
        <div class="col-md-3 col-6">
            <div class="hizmet-kutu">
                <div class="hizmet-ikon"><i class="bi bi-award-fill"></i></div>
                <h3 class="fw-bold mb-0">30+</h3>
                <p class="small text-muted mb-0">Yıllık Tecrübe</p>
            </div>
        </div>
    </div>

</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
