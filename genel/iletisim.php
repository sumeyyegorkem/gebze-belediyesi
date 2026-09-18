<?php
require_once '../config/db.php';

$siteAyar = $pdo->query("SELECT * FROM site_ayarlari WHERE id = 1")->fetch();
if (!$siteAyar) {
    $siteAyar = ['adres' => '', 'telefon' => '', 'eposta' => ''];
}

$basariMesaji = '';
$hataMesaji = '';

// Form "POST" ile gönderildiyse (yani kullanıcı "Gönder" butonuna bastıysa) bu blok çalışır.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1) Kullanıcıdan gelen verileri al ve trim() ile baştaki/sondaki boşlukları temizle
    $adSoyad = trim($_POST['ad_soyad'] ?? '');
    $eposta  = trim($_POST['eposta'] ?? '');
    $telefon = trim($_POST['telefon'] ?? '');
    $konu    = trim($_POST['konu'] ?? '');
    $mesaj   = trim($_POST['mesaj'] ?? '');

    // 2) Basit doğrulama (validation): zorunlu alanlar boş mu, e-posta geçerli mi?
    if ($adSoyad === '' || $eposta === '' || $konu === '' || $mesaj === '') {
        $hataMesaji = 'Lütfen zorunlu (*) alanları doldurun.';
    } elseif (!filter_var($eposta, FILTER_VALIDATE_EMAIL)) {
        $hataMesaji = 'Lütfen geçerli bir e-posta adresi girin.';
    } else {
        // 3) Her şey uygunsa, PDO "prepared statement" ile veritabanına güvenli şekilde ekle.
        //    Bu yöntem SQL Injection saldırılarını engeller.
        $stmt = $pdo->prepare(
            "INSERT INTO iletisim_mesajlari (ad_soyad, eposta, telefon, konu, mesaj)
             VALUES (:ad_soyad, :eposta, :telefon, :konu, :mesaj)"
        );
        $stmt->execute([
            'ad_soyad' => $adSoyad,
            'eposta'   => $eposta,
            'telefon'  => $telefon,
            'konu'     => $konu,
            'mesaj'    => $mesaj,
        ]);

        $basariMesaji = 'Mesajınız başarıyla alındı. En kısa sürede sizinle iletişime geçeceğiz.';

        // Formu boşaltmak için değişkenleri sıfırlıyoruz
        $adSoyad = $eposta = $telefon = $konu = $mesaj = '';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İletişim | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=63" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
    <style>
        /* Bu stil SADECE bu sayfada geçerli, siteyi etkilemez */
        i.text-danger { color: var(--altin) !important; }
        .btn-belediye { background-color: var(--lacivert) !important; border-color: var(--lacivert) !important; }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0">İletişim</h1>
    </div>
</header>

<div class="container py-5">
    <div class="row g-4 align-items-start">
        <!-- Sol taraf: bilgi kartları -->
        <div class="col-lg-4">
            <div class="hizmet-kutu mb-3 text-start">
                <i class="bi bi-geo-alt-fill fs-3 text-danger"></i>
                <h6 class="fw-bold mt-2">Adres</h6>
                <p class="small text-muted mb-0"><?php echo htmlspecialchars($siteAyar['adres']); ?></p>
            </div>
            <div class="hizmet-kutu mb-3 text-start">
                <i class="bi bi-telephone-fill fs-3 text-danger"></i>
                <h6 class="fw-bold mt-2">Telefon</h6>
                <p class="small text-muted mb-0"><?php echo htmlspecialchars($siteAyar['telefon']); ?></p>
            </div>
            <div class="hizmet-kutu text-start">
                <i class="bi bi-envelope-fill fs-3 text-danger"></i>
                <h6 class="fw-bold mt-2">E-posta</h6>
                <p class="small text-muted mb-0"><?php echo htmlspecialchars($siteAyar['eposta']); ?></p>
            </div>
        </div>

        <!-- Sağ taraf: iletişim formu -->
        <div class="col-lg-8">
            <div class="form-kutu">

                <?php if ($basariMesaji): ?>
                    <div class="alert alert-success alert-dismissible fade show otomatik-kaybol" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i><?php echo $basariMesaji; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($hataMesaji): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo $hataMesaji; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form method="POST" action="iletisim.php" class="js-dogrula needs-validation" novalidate>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Ad Soyad *</label>
                            <input type="text" name="ad_soyad" class="form-control"
                                   value="<?php echo htmlspecialchars($adSoyad ?? ''); ?>" required>
                            <div class="invalid-feedback">Lütfen adınızı ve soyadınızı girin.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">E-posta *</label>
                            <input type="email" name="eposta" class="form-control"
                                   value="<?php echo htmlspecialchars($eposta ?? ''); ?>" required>
                            <div class="invalid-feedback">Lütfen geçerli bir e-posta girin.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefon</label>
                            <input type="text" name="telefon" class="form-control"
                                   value="<?php echo htmlspecialchars($telefon ?? ''); ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Konu *</label>
                            <input type="text" name="konu" class="form-control"
                                   value="<?php echo htmlspecialchars($konu ?? ''); ?>" required>
                            <div class="invalid-feedback">Lütfen bir konu belirtin.</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Mesajınız *</label>
                            <textarea name="mesaj" rows="5" class="form-control" required><?php echo htmlspecialchars($mesaj ?? ''); ?></textarea>
                            <div class="invalid-feedback">Lütfen mesajınızı yazın.</div>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-belediye px-4">
                                <i class="bi bi-send-fill me-1"></i> Gönder
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bağlantılar -->
    <section id="baglantilar" class="mt-5">
        <h2 class="bolum-baslik">Bağlantılar</h2>
        <ul class="list-unstyled">
            <li class="mb-2">
                <i class="bi bi-link-45deg me-2"></i>
                <a href="https://www.kocaeli.bel.tr" target="_blank" class="text-decoration-none">Kocaeli Büyükşehir Belediyesi</a>
            </li>
            <li class="mb-2">
                <i class="bi bi-link-45deg me-2"></i>
                <a href="https://www.kocaeli.gov.tr" target="_blank" class="text-decoration-none">Kocaeli Valiliği</a>
            </li>
            <li class="mb-2">
                <i class="bi bi-link-45deg me-2"></i>
                <a href="https://www.gebze.gov.tr" target="_blank" class="text-decoration-none">Gebze Kaymakamlığı</a>
            </li>
            <li class="mb-2">
                <i class="bi bi-link-45deg me-2"></i>
                <a href="https://www.turkiye.gov.tr" target="_blank" class="text-decoration-none">e-Devlet Kapısı</a>
            </li>
            <li class="mb-2">
                <i class="bi bi-link-45deg me-2"></i>
                <a href="https://www.gtu.edu.tr" target="_blank" class="text-decoration-none">Gebze Teknik Üniversitesi (GTÜ)</a>
            </li>
            <li class="mb-2">
                <i class="bi bi-link-45deg me-2"></i>
                <a href="https://mam.tubitak.gov.tr" target="_blank" class="text-decoration-none">TÜBİTAK Gebze Yerleşkesi (Marmara Araştırma Merkezi)</a>
            </li>
            <li class="mb-2">
                <i class="bi bi-link-45deg me-2"></i>
                <a href="https://www.gto.org.tr" target="_blank" class="text-decoration-none">Gebze Ticaret Odası (GTO)</a>
            </li>
            <li class="mb-2">
                <i class="bi bi-link-45deg me-2"></i>
                <a href="https://kocaeliism.saglik.gov.tr" target="_blank" class="text-decoration-none">Kocaeli İl Sağlık Müdürlüğü</a>
            </li>
            <li class="mb-2">
                <i class="bi bi-link-45deg me-2"></i>
                <a href="https://www.sgk.gov.tr" target="_blank" class="text-decoration-none">Sosyal Güvenlik Kurumu (SGK)</a>
            </li>
            <li class="mb-2">
                <i class="bi bi-link-45deg me-2"></i>
                <a href="https://www.nvi.gov.tr" target="_blank" class="text-decoration-none">Nüfus ve Vatandaşlık İşleri Genel Müdürlüğü</a>
            </li>
            <li class="mb-2">
                <i class="bi bi-link-45deg me-2"></i>
                <a href="https://kocaeli.pol.tr" target="_blank" class="text-decoration-none">Kocaeli İl Emniyet Müdürlüğü</a>
            </li>
        </ul>
    </section>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
