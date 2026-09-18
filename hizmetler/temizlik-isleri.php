<?php
require_once '../config/db.php';

$basariMesaji = '';
$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adSoyad = trim($_POST['ad_soyad'] ?? '');
    $telefon = trim($_POST['telefon'] ?? '');
    $eposta = trim($_POST['eposta'] ?? '');
    $mahalle = trim($_POST['mahalle'] ?? '');
    $adres = trim($_POST['adres'] ?? '');
    $konu = trim($_POST['konu'] ?? '');
    $aciklama = trim($_POST['aciklama'] ?? '');

    if ($adSoyad === '' || $telefon === '' || $mahalle === '' || $konu === '' || $aciklama === '') {
        $hataMesaji = 'Lütfen zorunlu (*) alanları doldurun.';
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO temizlik_talepleri (ad_soyad, telefon, eposta, mahalle, adres, konu, aciklama)
             VALUES (:ad_soyad, :telefon, :eposta, :mahalle, :adres, :konu, :aciklama)"
        );
        $stmt->execute([
            'ad_soyad' => $adSoyad,
            'telefon' => $telefon,
            'eposta' => $eposta,
            'mahalle' => $mahalle,
            'adres' => $adres,
            'konu' => $konu,
            'aciklama' => $aciklama,
        ]);

        $basariMesaji = 'Talebiniz alındı. Temizlik İşleri Müdürlüğümüz en kısa sürede değerlendirecektir.';
        $adSoyad = $telefon = $eposta = $mahalle = $adres = $konu = $aciklama = '';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temizlik İşleri | Gebze Belediyesi</title>
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
        <h1 class="mb-0"><i class="bi bi-trash-fill me-2"></i>Temizlik İşleri</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4 mb-lg-0">
            <?php $aktifHizmet = 'temizlik-isleri'; include '../includes/hizmet-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Temizlik İşleri'; include '../includes/kurumsal-icerik-ust.php'; ?>

    <!-- Tanıtım Metni -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Temizlik İşleri Müdürlüğü</h2>
        <p class="metin-govde">
            Temizlik İşleri Müdürlüğümüz; ilçemizdeki cadde, sokak ve meydanların düzenli
            temizliği, evsel atıkların toplanması, geri dönüşüm çalışmaları ve çevre
            denetimi gibi birçok alanda 7/24 hizmet vermektedir. Amacımız, Gebze'yi
            hemşehrilerimiz için daha temiz, sağlıklı ve yaşanabilir bir kent haline getirmektir.
        </p>
    </section>

    <!-- Verilen Hizmetler -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Verilen Hizmetler</h2>
        <div class="row g-4">
            <?php
            $hizmetler = [
                ['bi-trash-fill', 'Çöp Toplama', 'Evsel atıkların düzenli olarak toplanması.', 'İlçemizdeki tüm mahallelerde evsel atıklar, belirlenen program dahilinde düzenli olarak toplanır. Mahallenize göre toplama saatleri değişiklik gösterebilir; güncel bilgi için bize ulaşabilirsiniz.'],
                ['bi-cone-striped', 'Cadde / Sokak Süpürme', 'Cadde, sokak ve meydanların mekanik ve elle süpürülmesi.', 'İlçe genelindeki cadde, sokak ve meydanlar hem mekanik süpürge araçları hem de saha ekiplerimiz tarafından düzenli olarak temizlenir.'],
                ['bi-recycle', 'Geri Dönüşüm', 'Ambalaj atıklarının ayrıştırılması ve geri kazanımı.', 'Kağıt, plastik, cam ve metal ambalaj atıklarının kaynağında ayrıştırılması ve geri kazanımı için toplama noktaları ve bilinçlendirme çalışmaları yürütülür.'],
                ['bi-droplet-half', 'Konteyner Yıkama & Dezenfeksiyon', 'Çöp konteynerlerinin periyodik yıkanması ve dezenfekte edilmesi.', 'İlçe genelindeki çöp konteynerleri, koku ve hijyen sorunlarını önlemek amacıyla periyodik olarak yıkanır ve dezenfekte edilir.'],
                ['bi-shield-fill-check', 'Çevre Denetimi', 'Çevre kirliliğine yönelik denetim ve yaptırım çalışmaları.', 'Çevre kirliliğine sebep olan kişi ve işletmelere yönelik denetimler yapılır; 2872 sayılı Çevre Kanunu kapsamında gerekli işlemler uygulanır.'],
                ['bi-shop-window', 'Pazar Yeri Temizliği', 'Semt pazarlarının kurulum sonrası temizlenmesi.', 'Semt pazarlarının kapanışının ardından alan süpürülür, yıkanır ve bir sonraki kullanıma hazır hale getirilir.'],
            ];
            foreach ($hizmetler as $i => $h):
            ?>
            <div class="col-md-4 col-sm-6">
                <button type="button" class="hizmet-kutu w-100 h-100 border-0 hizmet-tetik-temizlik" data-index="<?php echo $i; ?>">
                    <div class="hizmet-ikon"><i class="bi <?php echo $h[0]; ?>"></i></div>
                    <h6 class="fw-bold"><?php echo $h[1]; ?></h6>
                    <p class="text-muted mb-0"><?php echo $h[2]; ?></p>
                </button>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Tıklanan hizmetin detayının göründüğü panel -->
        <div id="temizlikDetayPaneli" class="form-kutu mt-4" style="display:none;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="badge badge-kategori text-white mb-2" id="temizlikDetayRozet"></span>
                    <h4 class="fw-bold" id="temizlikDetayBaslik"></h4>
                </div>
                <button type="button" class="btn-close" id="temizlikDetayKapat" aria-label="Kapat"></button>
            </div>
            <p class="metin-govde text-muted mb-0" id="temizlikDetayMetin"></p>
        </div>

        <script>
        const temizlikHizmetVerisi = <?php echo json_encode($hizmetler, JSON_UNESCAPED_UNICODE); ?>;
        document.addEventListener('DOMContentLoaded', function () {
            const panel = document.getElementById('temizlikDetayPaneli');
            document.querySelectorAll('.hizmet-tetik-temizlik').forEach(function (buton) {
                buton.addEventListener('click', function () {
                    const veri = temizlikHizmetVerisi[this.getAttribute('data-index')];
                    document.getElementById('temizlikDetayRozet').textContent = veri[1];
                    document.getElementById('temizlikDetayBaslik').textContent = veri[1];
                    document.getElementById('temizlikDetayMetin').textContent = veri[3];
                    panel.style.display = 'block';
                    panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            });
            document.getElementById('temizlikDetayKapat').addEventListener('click', function () {
                panel.style.display = 'none';
            });
        });
        </script>
    </section>

    <!-- Talep / Şikayet Bildirme Formu -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Talep / Şikayet Bildir</h2>
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

            <form method="POST" action="temizlik-isleri.php" class="js-dogrula needs-validation" novalidate>
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
                        <label class="form-label">Mahalle *</label>
                        <input type="text" name="mahalle" class="form-control" required
                               value="<?php echo htmlspecialchars($mahalle ?? ''); ?>">
                        <div class="invalid-feedback">Lütfen mahalle girin.</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Adres</label>
                        <input type="text" name="adres" class="form-control" placeholder="Cadde, sokak, no vb."
                               value="<?php echo htmlspecialchars($adres ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Konu *</label>
                        <select name="konu" class="form-select" required>
                            <?php foreach (['Toplanmayan Çöp','Konteyner Sorunu','Cadde/Sokak Kirliliği','Geri Dönüşüm','Çevre Kirliliği İhbarı','Diğer'] as $k): ?>
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
                            <i class="bi bi-send-fill me-1"></i> Talebi Gönder
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
                    <h6 class="fw-bold mb-0">Temizlik İşleri Müdürlüğü</h6>
                    <p class="small text-muted mb-1">Müdür: Senay Altıntaş</p>
                    <p class="small text-muted mb-0">Kirazpınar Mah. Yeni Bağdat Cad. No:883/A Gebze/Kocaeli</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="hizmet-kutu text-start">
                    <i class="bi bi-envelope-fill fs-4 text-danger mb-2"></i>
                    <h6 class="fw-bold mb-0">E-posta</h6>
                    <p class="small text-muted mb-0">temizlikisleri@gebze.bel.tr</p>
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
        <?php $galeriSayfa = 'temizlik-isleri'; $galeriBaslikGoster = false; include '../includes/fotograf-galerisi-bolum.php'; ?>
    </section>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
