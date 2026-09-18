<?php require_once '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurumsal Kimlik | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=67" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-patch-check-fill me-2"></i>Kurumsal Kimlik</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            <?php $aktifKurumsal = 'kurumsal-kimlik'; include '../includes/kurumsal-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Kurumsal Kimlik'; include '../includes/kurumsal-icerik-ust.php'; ?>

            <!-- Logo ve Amblem -->
            <section class="mb-5">
                <h2 class="bolum-baslik">Logo ve Amblem</h2>
                <p class="metin-govde">
                    Gebze Belediyesi'nin kurumsal logosu; resmi yazışmalarda, tabela ve
                    yayınlarda, araç ve bina cephelerinde, dijital mecralarda ve tüm
                    kurumsal iletişim materyallerinde kurumu temsil eder. Logo; oranları
                    bozulmadan, renkleri değiştirilmeden, döndürülmeden ve izinsiz üçüncü
                    taraflarca kullanılmadan uygulanır.
                </p>
                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <div class="hizmet-kutu h-100">
                            <img src="../img/logo-yatay.png" alt="Gebze Belediyesi Logosu - Yatay Kullanım" class="img-fluid mb-3" style="max-height:140px;">
                            <h6 class="fw-bold mb-0">Yatay Kullanım</h6>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="hizmet-kutu h-100">
                            <img src="../img/logo-dikey.png" alt="Gebze Belediyesi Logosu - Dikey Kullanım" class="img-fluid mb-3" style="max-height:140px;">
                            <h6 class="fw-bold mb-0">Dikey Kullanım</h6>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Başkanlık İmza Bloğu -->
            <section class="mb-5">
                <h2 class="bolum-baslik">Başkanlık İmza Bloğu</h2>
                <p class="metin-govde">
                    Resmi yazışma ve belgelerde kullanılan başkanlık imza bloğu
                    aşağıda yer almaktadır.
                </p>
                <div class="hizmet-kutu d-flex flex-column align-items-center text-center mx-auto" style="max-width:360px;">
                    <img src="../img/baskan-imza.png" alt="Zinnur Büyükgöz İmza Bloğu" class="img-fluid">
                </div>
            </section>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
