<?php
require_once '../config/db.php';

$basariMesaji = '';
$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $anonim = isset($_POST['anonim']) ? 1 : 0;
    $adSoyad = trim($_POST['ad_soyad'] ?? '');
    $telefon = trim($_POST['telefon'] ?? '');
    $mahalle = trim($_POST['mahalle'] ?? '');
    $konu = trim($_POST['konu'] ?? '');
    $aciklama = trim($_POST['aciklama'] ?? '');

    if ($mahalle === '' || $konu === '' || $aciklama === '') {
        $hataMesaji = 'Lütfen zorunlu (*) alanları doldurun.';
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO zabita_ihbarlari (anonim, ad_soyad, telefon, mahalle, konu, aciklama)
             VALUES (:anonim, :ad_soyad, :telefon, :mahalle, :konu, :aciklama)"
        );
        $stmt->execute([
            'anonim' => $anonim,
            'ad_soyad' => $anonim ? null : $adSoyad,
            'telefon' => $anonim ? null : $telefon,
            'mahalle' => $mahalle,
            'konu' => $konu,
            'aciklama' => $aciklama,
        ]);

        $basariMesaji = 'İhbarınız alındı. Zabıta ekiplerimiz en kısa sürede yerinde inceleme yapacaktır.';
        $adSoyad = $telefon = $mahalle = $konu = $aciklama = '';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zabıta | Gebze Belediyesi</title>
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
        <h1 class="mb-0"><i class="bi bi-shield-check me-2"></i>Zabıta</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4 mb-lg-0">
            <?php $aktifHizmet = 'zabita'; include '../includes/hizmet-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Zabıta'; include '../includes/kurumsal-icerik-ust.php'; ?>

    <!-- Tanıtım Metni -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Zabıta Hizmetleri</h2>
        <p class="metin-govde">
            İlçemizde huzur, güven ve düzenin sağlanması amacıyla zabıta ekiplerimiz
            tarafından denetim ve kontrol çalışmaları gerçekleştirilmektedir.
        </p>
        <p class="metin-govde">
            Zabıta ekiplerimiz; iş yerlerinin denetimi, işgal ve kaldırım düzeninin kontrolü,
            çevre ve halk sağlığına yönelik denetimler, pazar yerlerinin düzenlenmesi ve
            vatandaşlarımızdan gelen şikâyet ve taleplerin değerlendirilmesi gibi birçok
            alanda hizmet vermektedir.
        </p>
        <p class="metin-govde">
            Vatandaşlarımızın güvenli, düzenli ve sağlıklı bir kent ortamında yaşamalarını
            sağlamak amacıyla denetim ve çalışmalarımız düzenli olarak sürdürülmektedir.
            Daha düzenli ve yaşanabilir bir ilçe için zabıta ekiplerimiz görev başında.
        </p>
    </section>

    <!-- Verilen Hizmetler -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Verilen Hizmetler</h2>
        <div class="row g-4">
            <?php
            $hizmetler = [
                ['bi-shop', 'İşgal Denetimi', 'Kaldırım ve yol işgallerinin tespiti ve önlenmesi.', 'Kaldırım, yol ve ortak kullanım alanlarına yapılan izinsiz işgaller tespit edilir; işletme ve şahıslara gerekli uyarı ve yasal işlemler uygulanır.'],
                ['bi-volume-up-fill', 'Gürültü Kirliliği Denetimi', 'İş yeri ve inşaat kaynaklı gürültü şikayetlerinin denetimi.', 'İş yerleri, eğlence mekanları ve inşaat alanlarından kaynaklanan gürültü şikayetleri yerinde incelenir, mevzuata aykırı durumlar için işlem başlatılır.'],
                ['bi-cart-fill', 'Seyyar Satıcı Denetimi', 'Ruhsatsız seyyar satış faaliyetlerinin denetimi.', 'Ruhsatsız/izinsiz seyyar satış yapan kişiler tespit edilir, hem esnafımızın hem de vatandaşlarımızın mağduriyetini önlemek amacıyla denetim yapılır.'],
                ['bi-building-fill-exclamation', 'Kaçak Yapı Denetimi', 'İzinsiz yapılaşmaların tespiti ve ilgili birimlere bildirimi.', 'İmara aykırı ve ruhsatsız yapılaşmalar tespit edilerek Emlak ve İstimlak Müdürlüğü ile koordineli şekilde gerekli işlemler başlatılır.'],
                ['bi-car-front-fill', 'Trafik ve Otopark Denetimi', 'Hatalı park ve trafik düzenine aykırı durumların denetimi.', 'Hatalı/keyfi park, yaya yolu işgali ve trafik düzenine aykırı durumlar denetlenir, gerekli yasal işlemler uygulanır.'],
                ['bi-heart-pulse-fill', 'Hayvan Barınağı Hizmetleri', 'Sokak hayvanlarıyla ilgili ihbarların değerlendirilmesi.', 'Sokak hayvanlarıyla ilgili yaralı/hasta ihbarları değerlendirilir, Veteriner İşleri Müdürlüğümüzle koordineli olarak müdahale sağlanır.'],
            ];
            foreach ($hizmetler as $i => $h):
            ?>
            <div class="col-md-4 col-sm-6">
                <button type="button" class="hizmet-kutu w-100 h-100 border-0 hizmet-tetik-zabita" data-index="<?php echo $i; ?>">
                    <div class="hizmet-ikon"><i class="bi <?php echo $h[0]; ?>"></i></div>
                    <h6 class="fw-bold"><?php echo $h[1]; ?></h6>
                    <p class="text-muted mb-0"><?php echo $h[2]; ?></p>
                </button>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Tıklanan hizmetin detayının göründüğü panel -->
        <div id="zabitaDetayPaneli" class="form-kutu mt-4" style="display:none;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="badge badge-kategori text-white mb-2" id="zabitaDetayRozet"></span>
                    <h4 class="fw-bold" id="zabitaDetayBaslik"></h4>
                </div>
                <button type="button" class="btn-close" id="zabitaDetayKapat" aria-label="Kapat"></button>
            </div>
            <p class="metin-govde text-muted mb-0" id="zabitaDetayMetin"></p>
        </div>

        <script>
        const zabitaHizmetVerisi = <?php echo json_encode($hizmetler, JSON_UNESCAPED_UNICODE); ?>;
        document.addEventListener('DOMContentLoaded', function () {
            const panel = document.getElementById('zabitaDetayPaneli');
            document.querySelectorAll('.hizmet-tetik-zabita').forEach(function (buton) {
                buton.addEventListener('click', function () {
                    const veri = zabitaHizmetVerisi[this.getAttribute('data-index')];
                    document.getElementById('zabitaDetayRozet').textContent = veri[1];
                    document.getElementById('zabitaDetayBaslik').textContent = veri[1];
                    document.getElementById('zabitaDetayMetin').textContent = veri[3];
                    panel.style.display = 'block';
                    panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            });
            document.getElementById('zabitaDetayKapat').addEventListener('click', function () {
                panel.style.display = 'none';
            });
        });
        </script>
    </section>

    <!-- Tanıtım Videosu -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Tanıtım Videosu</h2>
        <div class="row">
            <div class="col-lg-8">
                <video controls class="w-100 rounded-3" style="max-height:420px; background:#000;"
                       poster="../img/zabita-video-kapak.jpg">
                    <source src="zabita-tanitim.mp4" type="video/mp4">
                    Tarayıcınız video oynatmayı desteklemiyor.
                </video>
                <a href="zabita-tanitim.mp4" download class="btn btn-belediye mt-3">
                    <i class="bi bi-download me-1"></i> Videoyu İndir
                </a>
            </div>
        </div>
    </section>

    <!-- Şikayet / İhbar Bildirme Formu -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Şikayet / İhbar Bildir</h2>
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

            <form method="POST" action="zabita.php" class="js-dogrula needs-validation" novalidate>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="anonimKutu" name="anonim" onchange="
                        document.getElementById('kimlikAlanlari').style.display = this.checked ? 'none' : 'flex';
                    ">
                    <label class="form-check-label small" for="anonimKutu">
                        Anonim bildirmek istiyorum (adım ve telefonum paylaşılmasın)
                    </label>
                </div>

                <div class="row g-3" id="kimlikAlanlari">
                    <div class="col-md-6">
                        <label class="form-label">Adınız Soyadınız</label>
                        <input type="text" name="ad_soyad" class="form-control"
                               value="<?php echo htmlspecialchars($adSoyad ?? ''); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Telefon</label>
                        <input type="text" name="telefon" class="form-control"
                               value="<?php echo htmlspecialchars($telefon ?? ''); ?>">
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label class="form-label">Mahalle *</label>
                        <input type="text" name="mahalle" class="form-control" required
                               value="<?php echo htmlspecialchars($mahalle ?? ''); ?>">
                        <div class="invalid-feedback">Lütfen mahalle girin.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Konu *</label>
                        <select name="konu" class="form-select" required>
                            <?php foreach (['İşgal','Gürültü','Seyyar Satıcı','Kaçak Yapı','Trafik/Otopark','Sokak Hayvanı','Diğer'] as $k): ?>
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
                            <i class="bi bi-send-fill me-1"></i> İhbarı Gönder
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
                    <i class="bi bi-telephone-fill fs-4 text-danger mb-2"></i>
                    <h6 class="fw-bold mb-0">7/24 Zabıta Hattı</h6>
                    <p class="small text-muted mb-0">+90 262 642 04 44</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="hizmet-kutu text-start">
                    <i class="bi bi-geo-alt-fill fs-4 text-danger mb-2"></i>
                    <h6 class="fw-bold mb-0">Zabıta Müdürlüğü</h6>
                    <p class="small text-muted mb-0">Güzeller Mah. Bahar Cad. No:1, Gebze</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="hizmet-kutu text-start">
                    <i class="bi bi-clock-fill fs-4 text-danger mb-2"></i>
                    <h6 class="fw-bold mb-0">Çalışma Saatleri</h6>
                    <p class="small text-muted mb-0">Kesintisiz 7/24 sahada</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Fotoğraf Galerisi -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Fotoğraf Galerisi</h2>
        <?php $galeriSayfa = 'zabita'; $galeriBaslikGoster = false; include '../includes/fotograf-galerisi-bolum.php'; ?>
    </section>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
