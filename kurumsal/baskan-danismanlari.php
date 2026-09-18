<?php require_once '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Başkan Danışmanları | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=67" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>Başkan Danışmanları</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            <?php $aktifKurumsal = 'baskan-danismanlari'; include '../includes/kurumsal-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Başkan Danışmanları'; include '../includes/kurumsal-icerik-ust.php'; ?>
            <div class="hizmet-kutu d-flex flex-column align-items-center text-center mb-4 mx-auto" style="max-width:420px; height:auto; padding:2.5rem 1.5rem;">
                <img src="https://www.gebze.bel.tr/resim/20240610103359.jpg" alt="Remzi Şeker"
                     class="rounded-circle mb-3" style="width:140px;height:140px;object-fit:cover;"
                     onerror="this.src='https://placehold.co/140x140?text=%20';">
                <h4 class="fw-bold mb-0">Remzi Şeker</h4>
                <span class="text-muted">Başkan Danışmanı</span>
            </div>

            <!-- Biyografi -->
            <div class="mt-5">
                <h2 class="bolum-baslik">Remzi Şeker - Biyografi</h2>
                <p class="metin-govde">
                    23 Şubat 1965 tarihinde Adapazarı'nda doğdu. İlk, orta ve lise öğrenimini
                    Adapazarı'nda tamamladıktan sonra, Konya Selçuk Üniversitesi Mimarlık ve
                    Mühendislik Fakültesi'nden Harita ve Kadastro Mühendisi olarak mezun oldu.
                    Ayrıca, T.C. Sermaye Piyasaları Kurulu (SPK) lisanslı Gayrimenkul Değerleme
                    Uzmanı unvanına sahiptir.
                </p>
                <p class="metin-govde">
                    1990 yılında Antalya Belediyesi'nde memur olarak göreve başladı. Ardından
                    Muratpaşa Belediyesi, Konya Büyükşehir Belediyesi ve Antalya Büyükşehir
                    Belediyesi'nde teknik eleman, Şube Müdürü ve Daire Başkanı olarak çeşitli
                    görevlerde bulundu. 2007 yılında İstanbul Pendik Belediyesi'ne Teknik
                    Müdürlüklerden sorumlu Belediye Başkan Yardımcısı olarak atandı.
                </p>
                <p class="metin-govde">
                    2009 Yerel Seçimleri'nde AK Parti'den Antalya ili Muratpaşa İlçesi Belediye
                    Başkan Aday Adayı oldu. Seçim sürecinin ardından Pendik Belediyesi'nde
                    memur Belediye Başkan Yardımcısı olarak görevine devam etti. 2014 Yerel
                    Seçimleri öncesinde memuriyetten ayrılarak, 2014-2019 yılları arasında
                    Pendik İlçesi kontenjanından AK Parti'den İstanbul Büyükşehir Belediyesi ve
                    Pendik Belediyesi Meclis Üyesi olarak Belediye Başkan Yardımcılığı görevine
                    devam etti. Aynı dönemde, Pendik Belediyesi iştiraki olan Penyapsan A.Ş.'de
                    Yönetim Kurulu Başkanı ve Genel Müdür olarak görev yaptı.
                </p>
                <p class="metin-govde">
                    2021-2024 yılları arasında Gebze Belediyesi'nde Teknik Başkan Yardımcısı
                    olarak görev yaptı. Halen Başkan Danışmanı sıfatıyla, Gebze Belediyesi'nde
                    Belediye Başkanımızın Teknik Danışmanı olarak memuriyetine devam etmektedir.
                </p>
                <p class="metin-govde">
                    Evli, iki kız ve iki erkek babasıdır.
                </p>
            </div>
            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
