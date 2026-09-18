<?php
require_once '../config/db.php';

$basariMesaji = '';
$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adSoyad = trim($_POST['ad_soyad'] ?? '');
    $telefon = trim($_POST['telefon'] ?? '');
    $eposta = trim($_POST['eposta'] ?? '');
    $konu = trim($_POST['konu'] ?? '');
    $aciklama = trim($_POST['aciklama'] ?? '');

    if ($adSoyad === '' || $telefon === '' || $konu === '' || $aciklama === '') {
        $hataMesaji = 'Lütfen zorunlu (*) alanları doldurun.';
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO kultur_talepleri (ad_soyad, telefon, eposta, konu, aciklama)
             VALUES (:ad_soyad, :telefon, :eposta, :konu, :aciklama)"
        );
        $stmt->execute([
            'ad_soyad' => $adSoyad,
            'telefon' => $telefon,
            'eposta' => $eposta,
            'konu' => $konu,
            'aciklama' => $aciklama,
        ]);

        $basariMesaji = 'Talebiniz alındı. Kültür İşleri Müdürlüğümüz en kısa sürede değerlendirecektir.';
        $adSoyad = $telefon = $eposta = $konu = $aciklama = '';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kültür ve Sosyal İşler | Gebze Belediyesi</title>
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
        <h1 class="mb-0"><i class="bi bi-palette-fill me-2"></i>Kültür ve Sosyal İşler</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4 mb-lg-0">
            <?php $aktifHizmet = 'kultur-sosyal-isler'; include '../includes/hizmet-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Kültür ve Sosyal İşler'; include '../includes/kurumsal-icerik-ust.php'; ?>

    <!-- Tanıtım Metni -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Kültür İşleri Müdürlüğü</h2>
        <p class="metin-govde">
            Kültür İşleri Müdürlüğümüz (eski adıyla Kültür ve Sosyal İşler Müdürlüğü);
            kültürel etkinlikler, kütüphane, müze, evlendirme ve dış ilişkiler
            birimleriyle Gebzelilere sanat, kültür ve sosyal yaşam alanında hizmet
            vermektedir. Konserler, sergiler, kurslar ve atölyeler ile her yaştan
            hemşehrimize ücretsiz programlar sunuyoruz.
        </p>
    </section>

    <!-- Verilen Hizmetler -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Verilen Hizmetler</h2>
        <div class="row g-4">
            <?php
            $hizmetler = [
                ['bi-music-note-beamed', 'Kültürel Etkinlikler', 'Konser, sergi, tiyatro ve söyleşi organizasyonları.', 'Konserler, sergiler, tiyatro gösterileri, söyleşiler ve paneller düzenlenerek hemşehrilerimize ücretsiz kültürel içerikler sunulur. Güncel program için Etkinlikler sayfamızı takip edebilirsiniz.'],
                ['bi-book-half', 'Kütüphane', 'Halk kütüphanesi hizmetleri ve okuma etkinlikleri.', 'Kütüphane biriminde geniş bir kitap koleksiyonu, sessiz çalışma alanları ve çocuklara yönelik okuma etkinlikleri sunulmaktadır.'],
                ['bi-bank2', 'Müze', 'Müze şefliği ve kültürel miras çalışmaları.', 'Müze Şefliği bünyesinde ilçemizin tarihi ve kültürel mirasına dair sergiler ve koruma çalışmaları yürütülür.'],
                ['bi-heart-fill', 'Evlendirme Memurluğu', 'Nikah işlemleri ve randevu süreçleri.', 'Evlendirme Memurluğu, nikah başvuru ve tören süreçlerini yürütür. Detaylı bilgi ve randevu için Nikah İşlemleri sayfamızı ziyaret edebilirsiniz.'],
                ['bi-globe2', 'Dış İlişkiler', 'Kardeş şehir ilişkileri ve uluslararası projeler.', 'Kardeş şehirlerle kültürel işbirlikleri, uluslararası projeler ve değişim programları Dış İlişkiler Birimi tarafından koordine edilir.'],
                ['bi-building', 'Gebze Kültür Merkezi', 'Kurs, atölye ve etkinlik alanları.', 'Gebze Kültür Merkezi; kültür, sanat, spor ve sağlık alanlarında 7\'den 70\'e her kesime hitap eden ücretsiz programlar sunar. GKM Salonu ve Kardelen Salonu, kamu kurum ve kuruluşlarına ücret karşılığında tahsis edilebilir.'],
            ];
            foreach ($hizmetler as $i => $h):
            ?>
            <div class="col-md-4 col-sm-6">
                <button type="button" class="hizmet-kutu w-100 h-100 border-0 hizmet-tetik-kultur" data-index="<?php echo $i; ?>">
                    <div class="hizmet-ikon"><i class="bi <?php echo $h[0]; ?>"></i></div>
                    <h6 class="fw-bold"><?php echo $h[1]; ?></h6>
                    <p class="text-muted mb-0"><?php echo $h[2]; ?></p>
                </button>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Tıklanan hizmetin detayının göründüğü panel -->
        <div id="kulturDetayPaneli" class="form-kutu mt-4" style="display:none;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="badge badge-kategori text-white mb-2" id="kulturDetayRozet"></span>
                    <h4 class="fw-bold" id="kulturDetayBaslik"></h4>
                </div>
                <button type="button" class="btn-close" id="kulturDetayKapat" aria-label="Kapat"></button>
            </div>
            <p class="metin-govde text-muted mb-0" id="kulturDetayMetin"></p>
        </div>

        <script>
        const kulturHizmetVerisi = <?php echo json_encode($hizmetler, JSON_UNESCAPED_UNICODE); ?>;
        document.addEventListener('DOMContentLoaded', function () {
            const panel = document.getElementById('kulturDetayPaneli');
            document.querySelectorAll('.hizmet-tetik-kultur').forEach(function (buton) {
                buton.addEventListener('click', function () {
                    const veri = kulturHizmetVerisi[this.getAttribute('data-index')];
                    document.getElementById('kulturDetayRozet').textContent = veri[1];
                    document.getElementById('kulturDetayBaslik').textContent = veri[1];
                    document.getElementById('kulturDetayMetin').textContent = veri[3];
                    panel.style.display = 'block';
                    panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            });
            document.getElementById('kulturDetayKapat').addEventListener('click', function () {
                panel.style.display = 'none';
            });
        });
        </script>
    </section>

    <!-- Gebze Kültür Merkezi Tanıtım -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Gebze Kültür Merkezi</h2>
        <div class="hizmet-kutu text-start">
            <p class="metin-govde mb-3">
                Gebze Kültür Merkezi; kültür, sanat, spor ve sağlık gibi birçok alanda
                7'den 70'e her kesime hitap eden ücretsiz programlarıyla hizmet vermektedir.
                GKM Salonu ve Kardelen Salonu, kamu kurum ve kuruluşlarına ücret
                karşılığında tahsis edilmektedir.
            </p>
            <div class="row g-2 small text-muted">
                <div class="col-md-4"><i class="bi bi-geo-alt-fill text-danger me-1"></i>Hacı Halil Mah. Atatürk Cad. No:8, 15 Temmuz Milli İrade Kent Meydanı</div>
                <div class="col-md-4"><i class="bi bi-telephone-fill text-danger me-1"></i>0262 644 56 89</div>
                <div class="col-md-4"><i class="bi bi-envelope-fill text-danger me-1"></i>kultur@gebze.bel.tr</div>
            </div>
        </div>
    </section>

    <!-- Öneri / Talep Formu -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Öneri / Talep Bildir</h2>
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

            <form method="POST" action="kultur-sosyal-isler.php" class="js-dogrula needs-validation" novalidate>
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
                        <label class="form-label">Konu *</label>
                        <select name="konu" class="form-select" required>
                            <?php foreach (['Etkinlik Önerisi','Kütüphane','Müze','Kurs/Atölye Talebi','Kültür Merkezi Rezervasyonu','Diğer'] as $k): ?>
                                <option value="<?php echo $k; ?>"><?php echo $k; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Açıklama *</label>
                        <textarea name="aciklama" rows="4" class="form-control" required><?php echo htmlspecialchars($aciklama ?? ''); ?></textarea>
                        <div class="invalid-feedback">Lütfen talebinizi açıklayın.</div>
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
                    <h6 class="fw-bold mb-0">Kültür İşleri Müdürlüğü</h6>
                    <p class="small text-muted mb-0">Müdür: Carullah Recai Er</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="hizmet-kutu text-start">
                    <i class="bi bi-envelope-fill fs-4 text-danger mb-2"></i>
                    <h6 class="fw-bold mb-0">E-posta</h6>
                    <p class="small text-muted mb-0">kultur@gebze.bel.tr</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="hizmet-kutu text-start">
                    <i class="bi bi-telephone-fill fs-4 text-danger mb-2"></i>
                    <h6 class="fw-bold mb-0">Telefon</h6>
                    <p class="small text-muted mb-0">0262 644 56 89</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Fotoğraf Galerisi -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Fotoğraf Galerisi</h2>
        <?php $galeriSayfa = 'kultur-sosyal-isler'; $galeriBaslikGoster = false; include '../includes/fotograf-galerisi-bolum.php'; ?>
    </section>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
