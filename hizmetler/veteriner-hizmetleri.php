<?php
require_once '../config/db.php';

$basariMesaji = '';
$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adSoyad = trim($_POST['ad_soyad'] ?? '');
    $telefon = trim($_POST['telefon'] ?? '');
    $eposta = trim($_POST['eposta'] ?? '');
    $konum = trim($_POST['konum'] ?? '');
    $konu = trim($_POST['konu'] ?? '');
    $aciklama = trim($_POST['aciklama'] ?? '');

    if ($adSoyad === '' || $telefon === '' || $konum === '' || $konu === '' || $aciklama === '') {
        $hataMesaji = 'Lütfen zorunlu (*) alanları doldurun.';
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO veteriner_ihbarlari (ad_soyad, telefon, eposta, konum, konu, aciklama)
             VALUES (:ad_soyad, :telefon, :eposta, :konum, :konu, :aciklama)"
        );
        $stmt->execute([
            'ad_soyad' => $adSoyad,
            'telefon' => $telefon,
            'eposta' => $eposta,
            'konum' => $konum,
            'konu' => $konu,
            'aciklama' => $aciklama,
        ]);

        $basariMesaji = 'İhbarınız/talebiniz alındı. Veteriner İşleri Müdürlüğümüz en kısa sürede değerlendirecektir.';
        $adSoyad = $telefon = $eposta = $konum = $konu = $aciklama = '';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veteriner Hizmetleri | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=70" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
    <style>
        /* Bu stil SADECE bu sayfada geçerli, siteyi etkilemez */
        i.text-danger { color: var(--lacivert) !important; }
        .badge-kategori { background-color: var(--altin) !important; }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-heart-pulse-fill me-2"></i>Veteriner Hizmetleri</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4 mb-lg-0">
            <?php $aktifHizmet = 'veteriner-hizmetleri'; include '../includes/hizmet-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Veteriner Hizmetleri'; include '../includes/kurumsal-icerik-ust.php'; ?>

    <!-- Tanıtım Metni -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Veteriner İşleri Müdürlüğü</h2>
        <p class="metin-govde">
            Veteriner İşleri Müdürlüğümüz; sokak hayvanlarının tedavisi, kısırlaştırılması,
            aşılanması ve sahiplendirilmesi başta olmak üzere hayvan sağlığı ve halk sağlığını
            koruyucu birçok alanda hizmet vermektedir. İlçemizdeki sahipsiz hayvanların
            sağlıklı ve güvenli bir yaşam sürmesi için gönüllü hayvanseverlerle iş birliği
            içinde çalışıyoruz.
        </p>
    </section>

    <!-- Verilen Hizmetler -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Verilen Hizmetler</h2>
        <div class="row g-4">
            <?php
            $hizmetler = [
                ['bi-hospital-fill', 'Tedavi ve Rehabilitasyon', 'Yaralı, hasta ve güçten düşmüş hayvanların tedavisi.', 'Sokak Hayvanları Tedavi, Rehabilitasyon ve Eğitim Merkezimiz 7/24 esasıyla hizmet verir; donanımlı ameliyathane, muayenehane, laboratuvar, yoğun bakım ve müşahede üniteleriyle yaralı/hasta hayvanların tedavisi sağlanır.'],
                ['bi-clipboard2-pulse-fill', 'Kısırlaştırma ve Aşılama', 'Sokak hayvanlarının popülasyon kontrolü ve aşılanması.', 'Sahipsiz hayvanların üremesinin kontrol altına alınması amacıyla kısırlaştırma, aşılama, işaretleme (küpeleme) ve kayıt altına alma çalışmaları yürütülür.'],
                ['bi-house-heart-fill', 'Sahiplendirme', 'Rehabilite edilen hayvanların sahiplendirilmesi.', 'Tedavi ve rehabilitasyon süreci tamamlanan hayvanlarımız, uygun koşullara sahip gönüllü ailelerle buluşturularak sahiplendirme işlemleri gerçekleştirilir.'],
                ['bi-exclamation-triangle-fill', 'Yaralı / Hasta Hayvan İhbarı', 'Sokakta yaralı veya hasta hayvan bildirimi.', 'Sokakta yaralı, hasta ya da yardıma muhtaç bir hayvan gördüğünüzde bu sayfadaki formdan veya telefonla bize bildirebilirsiniz; ekiplerimiz en kısa sürede müdahale eder.'],
                ['bi-virus', 'Salgın Hastalık Denetimi', 'Zoonoz hastalıklarla mücadele ve kontrol.', 'Kuduz başta olmak üzere hayvandan hayvana ve hayvandan insana geçebilen (zoonoz) hastalıklarla mücadele kapsamında denetim ve kontrol çalışmaları yürütülür.'],
                ['bi-mortarboard-fill', 'Eğitim ve Seminerler', 'Okullarda hayvan sevgisi ve hijyen eğitimleri.', 'İlk, orta ve lise düzeyindeki öğrencilerimize yönelik hayvan sevgisi, zoonoz hastalıklar, hijyen ve veteriner hekimlik mesleği konularında eğitim ve seminerler düzenlenir.'],
            ];
            foreach ($hizmetler as $i => $h):
            ?>
            <div class="col-md-4 col-sm-6">
                <button type="button" class="hizmet-kutu w-100 h-100 border-0 hizmet-tetik-veteriner" data-index="<?php echo $i; ?>">
                    <div class="hizmet-ikon"><i class="bi <?php echo $h[0]; ?>"></i></div>
                    <h6 class="fw-bold"><?php echo $h[1]; ?></h6>
                    <p class="text-muted mb-0"><?php echo $h[2]; ?></p>
                </button>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Tıklanan hizmetin detayının göründüğü panel -->
        <div id="veterinerDetayPaneli" class="form-kutu mt-4" style="display:none;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="badge badge-kategori text-white mb-2" id="veterinerDetayRozet"></span>
                    <h4 class="fw-bold" id="veterinerDetayBaslik"></h4>
                </div>
                <button type="button" class="btn-close" id="veterinerDetayKapat" aria-label="Kapat"></button>
            </div>
            <p class="metin-govde text-muted mb-0" id="veterinerDetayMetin"></p>
        </div>

        <script>
        const veterinerHizmetVerisi = <?php echo json_encode($hizmetler, JSON_UNESCAPED_UNICODE); ?>;
        document.addEventListener('DOMContentLoaded', function () {
            const panel = document.getElementById('veterinerDetayPaneli');
            document.querySelectorAll('.hizmet-tetik-veteriner').forEach(function (buton) {
                buton.addEventListener('click', function () {
                    const veri = veterinerHizmetVerisi[this.getAttribute('data-index')];
                    document.getElementById('veterinerDetayRozet').textContent = veri[1];
                    document.getElementById('veterinerDetayBaslik').textContent = veri[1];
                    document.getElementById('veterinerDetayMetin').textContent = veri[3];
                    panel.style.display = 'block';
                    panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            });
            document.getElementById('veterinerDetayKapat').addEventListener('click', function () {
                panel.style.display = 'none';
            });
        });
        </script>
    </section>

    <!-- Sokak Hayvanları Merkezi Tanıtım -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Sokak Hayvanları Tedavi, Rehabilitasyon ve Eğitim Merkezi</h2>
        <div class="hizmet-kutu text-start">
            <p class="metin-govde mb-3">
                Merkezimiz, ilçemizin sahipsiz sokak hayvanlarına <strong>7/24</strong> esaslı
                çalışma prensibiyle hizmet vermektedir. Bünyesinde 3 hayvan refah ekibi, 1 mobil
                klinik (acil müdahale aracı), 3 uzman veteriner hekim ve 12 destek personeli
                görev yapmaktadır. Donanımlı ameliyathane, muayenehane, laboratuvar, yoğun bakım
                üniteleri ve müşahede bölümü bulunmaktadır.
            </p>
            <div class="row g-2 small text-muted">
                <div class="col-md-4"><i class="bi bi-geo-alt-fill text-danger me-1"></i>Pelitli Mah. Yeni Mezarlık Yolu No:49, Gebze/Kocaeli</div>
                <div class="col-md-4"><i class="bi bi-clock-fill text-danger me-1"></i>7/24 Kesintisiz Hizmet</div>
                <div class="col-md-4"><i class="bi bi-envelope-fill text-danger me-1"></i>veteriner@gebze.bel.tr</div>
            </div>
        </div>
    </section>

    <!-- Konum -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Konum</h2>
        <div class="rounded-3 overflow-hidden shadow-sm" style="height:350px;">
            <iframe
                src="https://www.google.com/maps?q=Pelitli+Yeni+Mezarlik+Yolu+No49+Gebze+Kocaeli&output=embed"
                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    </section>

    <!-- İhbar / Talep Formu -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Yaralı / Hasta Hayvan İhbarı ve Talep</h2>
        <div class="form-kutu">
            <?php if ($basariMesaji): ?>
                <div class="alert alert-success alert-dismissible fade show otomatik-kaybol">
                    <i class="bi bi-check-circle-fill me-2"></i><?php echo $basariMesaji; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if ($hataMesaji): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i><?php echo $hataMesaji; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="veteriner-hizmetleri.php" class="js-dogrula needs-validation" novalidate>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Adınız Soyadınız *</label>
                        <input type="text" name="ad_soyad" class="form-control" required
                               value="<?php echo htmlspecialchars($adSoyad ?? ''); ?>">
                        <div class="invalid-feedback">Lütfen adınızı girin.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Telefon *</label>
                        <input type="text" name="telefon" class="form-control" required
                               value="<?php echo htmlspecialchars($telefon ?? ''); ?>">
                        <div class="invalid-feedback">Lütfen telefon numaranızı girin.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">E-posta</label>
                        <input type="email" name="eposta" class="form-control"
                               value="<?php echo htmlspecialchars($eposta ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Konum (Mahalle/Adres) *</label>
                        <input type="text" name="konum" class="form-control" required
                               value="<?php echo htmlspecialchars($konum ?? ''); ?>">
                        <div class="invalid-feedback">Lütfen konum bilgisi girin.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Konu *</label>
                        <select name="konu" class="form-select" required>
                            <?php foreach (['Yaralı Hayvan İhbarı','Hasta Hayvan İhbarı','Kısırlaştırma/Aşılama Talebi','Sahiplendirme Talebi','Şikayet','Diğer'] as $k): ?>
                                <option value="<?php echo $k; ?>"><?php echo $k; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Açıklama *</label>
                        <textarea name="aciklama" rows="4" class="form-control" required><?php echo htmlspecialchars($aciklama ?? ''); ?></textarea>
                        <div class="invalid-feedback">Lütfen durumu açıklayın.</div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-belediye px-4">
                            <i class="bi bi-send-fill me-1"></i> Gönder
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- İletişim -->
    <section>
        <h2 class="bolum-baslik">İletişim</h2>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="hizmet-kutu text-start">
                    <i class="bi bi-building fs-4 text-danger mb-2"></i>
                    <h6 class="fw-bold mb-0">Veteriner İşleri Müdürlüğü</h6>
                    <p class="small text-muted mb-0">Müdür: Cevat Altıntaş</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="hizmet-kutu text-start">
                    <i class="bi bi-envelope-fill fs-4 text-danger mb-2"></i>
                    <h6 class="fw-bold mb-0">E-posta</h6>
                    <p class="small text-muted mb-0">veteriner@gebze.bel.tr</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="hizmet-kutu text-start">
                    <i class="bi bi-telephone-fill fs-4 text-danger mb-2"></i>
                    <h6 class="fw-bold mb-0">Telefon</h6>
                    <p class="small text-muted mb-0">+90 262 642 04 30</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Fotoğraf Galerisi -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Fotoğraf Galerisi</h2>
        <?php $galeriSayfa = 'veteriner-hizmetleri'; $galeriBaslikGoster = false; include '../includes/fotograf-galerisi-bolum.php'; ?>
    </section>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
