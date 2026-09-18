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
            "INSERT INTO fen_isleri_talepleri (ad_soyad, telefon, eposta, mahalle, adres, konu, aciklama)
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

        $basariMesaji = 'Talebiniz alındı. Fen İşleri Müdürlüğümüz en kısa sürede değerlendirecektir.';
        $adSoyad = $telefon = $eposta = $mahalle = $adres = $konu = $aciklama = '';
    }
}

// Devam eden çalışmaları, mevcut "projeler" tablosundan çekiyoruz (ayrı tablo açmaya gerek yok)
$devamEdenler = $pdo->query("SELECT * FROM projeler WHERE durum = 'devam_eden' AND aktif = 1 ORDER BY olusturma_tarihi DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fen İşleri | Gebze Belediyesi</title>
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
        <h1 class="mb-0"><i class="bi bi-cone-striped me-2"></i>Fen İşleri</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4 mb-lg-0">
            <?php $aktifHizmet = 'fen-isleri'; include '../includes/hizmet-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Fen İşleri'; include '../includes/kurumsal-icerik-ust.php'; ?>

    <!-- Verilen Hizmetler -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Verilen Hizmetler</h2>
        <div class="row g-4">
            <?php
            $hizmetler = [
                ['bi-signpost-2-fill', 'Yol Yapım ve Onarımı', 'Asfalt serimi, çukur onarımı ve yeni yol açma çalışmaları.', 'Ekiplerimiz, ilçemizdeki cadde ve sokaklarda asfalt serimi, çukur onarımı ve yeni yol açma çalışmalarını mahalle bazında planlı şekilde yürütür. Acil çukur/bozulma bildirimleri öncelikli olarak değerlendirilir.'],
                ['bi-diagram-3-fill', 'Altyapı Çalışmaları', 'Kanalizasyon, yağmur suyu drenajı ve içme suyu hattı çalışmaları.', 'Kanalizasyon hatları, yağmur suyu drenaj sistemleri ve içme suyu altyapısıyla ilgili yenileme ve bakım çalışmaları ilgili kurumlarla koordineli olarak yürütülür.'],
                ['bi-bricks', 'Kaldırım Düzenleme', 'Yaya kaldırımlarının yenilenmesi ve engelli erişimine uygun hale getirilmesi.', 'Yaya kaldırımları düzenli olarak yenilenir; rampa ve kılavuz yüzeyler eklenerek engelli vatandaşlarımızın erişimi kolaylaştırılır.'],
                ['bi-tree-fill', 'Park ve Yeşil Alan Bakımı', 'Park, refüj ve yeşil alanların düzenlenmesi ve bakımı.', 'İlçemizdeki parklar, refüjler ve yeşil alanların budama, sulama ve genel bakımı düzenli periyotlarla yapılır; yeni yeşil alan projeleri de bu birim tarafından yürütülür.'],
                ['bi-lightbulb-fill', 'Sokak Aydınlatması', 'Sokak lambalarının bakımı, arızalı armatürlerin değişimi.', 'Sokak lambalarının periyodik bakımı yapılır, arızalı armatürler tespit edilerek en kısa sürede değiştirilir. Karanlık kalan bölgeler için bildirim yapabilirsiniz.'],
                ['bi-signpost-split-fill', 'Trafik Düzenlemeleri', 'Yol çizgileri, tabela ve trafik güvenliği çalışmaları.', 'Yol çizgilerinin yenilenmesi, trafik tabelalarının yerleştirilmesi ve kavşak güvenliğini artıracak düzenlemeler bu birim tarafından planlanır.'],
            ];
            foreach ($hizmetler as $i => $h):
            ?>
            <div class="col-md-4 col-sm-6">
                <button type="button" class="hizmet-kutu w-100 h-100 border-0 hizmet-tetik-fen" data-index="<?php echo $i; ?>">
                    <div class="hizmet-ikon"><i class="bi <?php echo $h[0]; ?>"></i></div>
                    <h6 class="fw-bold"><?php echo $h[1]; ?></h6>
                    <p class="small text-muted mb-0"><?php echo $h[2]; ?></p>
                </button>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Tıklanan hizmetin detayının göründüğü panel -->
        <div id="fenDetayPaneli" class="form-kutu mt-4" style="display:none;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="badge badge-kategori text-white mb-2" id="fenDetayRozet"></span>
                    <h4 class="fw-bold" id="fenDetayBaslik"></h4>
                </div>
                <button type="button" class="btn-close" id="fenDetayKapat" aria-label="Kapat"></button>
            </div>
            <p class="metin-govde text-muted mb-0" id="fenDetayMetin"></p>
        </div>

        <script>
        const fenHizmetVerisi = <?php echo json_encode($hizmetler, JSON_UNESCAPED_UNICODE); ?>;
        document.addEventListener('DOMContentLoaded', function () {
            const panel = document.getElementById('fenDetayPaneli');
            document.querySelectorAll('.hizmet-tetik-fen').forEach(function (buton) {
                buton.addEventListener('click', function () {
                    const veri = fenHizmetVerisi[this.getAttribute('data-index')];
                    document.getElementById('fenDetayRozet').textContent = veri[1];
                    document.getElementById('fenDetayBaslik').textContent = veri[1];
                    document.getElementById('fenDetayMetin').textContent = veri[3];
                    panel.style.display = 'block';
                    panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            });
            document.getElementById('fenDetayKapat').addEventListener('click', function () {
                panel.style.display = 'none';
            });
        });
        </script>
    </section>

    <!-- Devam Eden Çalışmalar -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Devam Eden Çalışmalar</h2>
        <?php if (count($devamEdenler) === 0): ?>
            <p class="text-muted">Şu anda devam eden bir çalışma bulunmuyor.</p>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($devamEdenler as $d): ?>
                    <?php
                        // resim_url veritabanında bazen tam bağlantı (https://...), bazen kök dizine
                        // göre kısa yol ("uploads/x.jpg") olarak saklanıyor; boş da olabilir.
                        $dResimSrc = ($d['resim_url'] !== '' && !preg_match('/^https?:\/\//i', $d['resim_url']))
                            ? '../' . $d['resim_url']
                            : $d['resim_url'];
                    ?>
                    <div class="col-md-6">
                        <div class="card duyuru-karti h-100 flex-row">
                            <img src="<?php echo htmlspecialchars($dResimSrc); ?>"
                                 style="width:140px;object-fit:cover;" alt="<?php echo htmlspecialchars($d['baslik']); ?>">
                            <div class="card-body">
                                <span class="badge bg-info text-dark mb-1">Devam Eden</span>
                                <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($d['baslik']); ?></h6>
                                <p class="small text-muted mb-0"><?php echo htmlspecialchars($d['aciklama']); ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- Arıza / Şikayet Bildirme Formu -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Arıza / Şikayet Bildir</h2>
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

            <form method="POST" action="fen-isleri.php" class="js-dogrula needs-validation" novalidate>
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
                            <?php foreach (['Yol/Asfalt Sorunu','Altyapı Arızası','Kaldırım Sorunu','Sokak Lambası Arızası','Park/Yeşil Alan','Diğer'] as $k): ?>
                                <option value="<?php echo $k; ?>"><?php echo $k; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Açıklama *</label>
                        <textarea name="aciklama" rows="4" class="form-control" required><?php echo htmlspecialchars($aciklama ?? ''); ?></textarea>
                        <div class="invalid-feedback">Lütfen sorunu açıklayın.</div>
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

    <!-- İletişim Bilgileri -->
    <section>
        <h2 class="bolum-baslik">İletişim</h2>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="hizmet-kutu text-start">
                    <i class="bi bi-building fs-4 text-danger mb-2"></i>
                    <h6 class="fw-bold mb-0">Fen İşleri Müdürlüğü</h6>
                    <p class="small text-muted mb-0">Güzeller Mah. Bahar Cad. No:1, Gebze</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="hizmet-kutu text-start">
                    <i class="bi bi-telephone-fill fs-4 text-danger mb-2"></i>
                    <h6 class="fw-bold mb-0">Telefon</h6>
                    <p class="small text-muted mb-0">+90 262 642 04 30 (dahili 2)</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="hizmet-kutu text-start">
                    <i class="bi bi-clock-fill fs-4 text-danger mb-2"></i>
                    <h6 class="fw-bold mb-0">Çalışma Saatleri</h6>
                    <p class="small text-muted mb-0">Hafta içi 08:30 - 17:30</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Fotoğraf Galerisi -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Fotoğraf Galerisi</h2>
        <?php $galeriSayfa = 'fen-isleri'; $galeriBaslikGoster = false; include '../includes/fotograf-galerisi-bolum.php'; ?>
    </section>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
