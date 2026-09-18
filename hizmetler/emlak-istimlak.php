<?php
require_once '../config/db.php';

$basariMesaji = '';
$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adSoyad = trim($_POST['ad_soyad'] ?? '');
    $telefon = trim($_POST['telefon'] ?? '');
    $eposta = trim($_POST['eposta'] ?? '');
    $mahalle = trim($_POST['mahalle'] ?? '');
    $adaParsel = trim($_POST['ada_parsel'] ?? '');
    $aciklama = trim($_POST['aciklama'] ?? '');

    if ($adSoyad === '' || $telefon === '' || $mahalle === '' || $adaParsel === '') {
        $hataMesaji = 'Lütfen zorunlu (*) alanları doldurun.';
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO emlak_sorgu_talepleri (ad_soyad, telefon, eposta, mahalle, ada_parsel, aciklama)
             VALUES (:ad_soyad, :telefon, :eposta, :mahalle, :ada_parsel, :aciklama)"
        );
        $stmt->execute([
            'ad_soyad' => $adSoyad,
            'telefon' => $telefon,
            'eposta' => $eposta,
            'mahalle' => $mahalle,
            'ada_parsel' => $adaParsel,
            'aciklama' => $aciklama,
        ]);

        $basariMesaji = 'Talebiniz alındı. İmar durumu bilgisi hazırlandığında sizinle iletişime geçilecektir.';
        $adSoyad = $telefon = $eposta = $mahalle = $adaParsel = $aciklama = '';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emlak &amp; İstimlak | Gebze Belediyesi</title>
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
        <h1 class="mb-0"><i class="bi bi-house-door-fill me-2"></i>Emlak &amp; İstimlak</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4 mb-lg-0">
            <?php $aktifHizmet = 'emlak-istimlak'; include '../includes/hizmet-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Emlak & İstimlak'; include '../includes/kurumsal-icerik-ust.php'; ?>

    <!-- Verilen Hizmetler -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Verilen Hizmetler</h2>
        <div class="row g-4">
            <?php
            $hizmetler = [
                ['bi-map-fill', 'İmar Durumu Belgesi', 'Parsellere ait imar durumu bilgisinin hazırlanması ve verilmesi.', 'Talep edilen parsele ait yapılaşma koşullarını (kat yüksekliği, taban alanı, kullanım amacı vb.) gösteren imar durumu belgesi hazırlanır ve başvuru sahibine teslim edilir.'],
                ['bi-buildings-fill', 'Kamulaştırma (İstimlak) İşlemleri', 'Kamu yatırımları için gerekli taşınmazların kamulaştırılması.', 'İmar planında yol, park, otopark veya yeşil alan gibi kamu kullanımına ayrılmış özel mülkiyetteki taşınmazlar, ilgili kamulaştırma mevzuatına göre kamuya kazandırılır.'],
                ['bi-rulers', 'Tevhit / İfraz / Yola Terk', 'Parsel birleştirme, ayırma ve yoldan ihdas işlemleri.', 'İmar mevzuatına uygun olarak parsellerin birleştirilmesi (tevhit), ayrılması (ifraz) ve yola terk/yoldan ihdas gibi tapu işlemleri yürütülür.'],
                ['bi-signpost-2-fill', 'İmar Uygulaması', 'İmar planına göre parsellerin düzenlenmesi işlemleri.', 'İmar planında öngörülen düzenlemeler doğrultusunda parsellerin yeniden şekillendirilmesi (3194 sayılı Kanun 18. madde uygulaması) yürütülür.'],
                ['bi-cash-coin', 'Belediye Taşınmazları', 'Belediyeye ait taşınmazların kira ve satış işlemleri.', 'Belediyemize ait taşınmazların meclis kararı doğrultusunda ihale usulüyle satış ve kiralama işlemleri takip edilir.'],
                ['bi-pin-map-fill', 'Numarataj İşlemleri', 'Adres, kapı ve sokak numaralandırma hizmetleri.', 'Mahalle adres haritalarının hazırlanması, bina/kapı numaralarının verilmesi ve cadde-sokak levhalarının güncellenmesi bu birim tarafından yürütülür.'],
                ['bi-file-earmark-text-fill', 'Tapu Ferağ ve Devir İşlemleri', 'Hibe, ferağ ve tapu devir işlemlerinin yürütülmesi.', 'Belediye adına yapılacak hibe alma, tapuda ferağ verme/alma ve tescil-terkin işlemleri Başkanlık onayına dayanılarak gerçekleştirilir.'],
                ['bi-key-fill', 'Yapı Kayıt / Ecrimisil İşlemleri', 'Yapı kayıt belgeli parsel satışı ve ecrimisil takibi.', 'Yapı Kayıt Belgesi bulunan parsellerin satış işlemleri ile haksız işgal bedeli (ecrimisil) tahakkuk ve takip işlemleri yürütülür.'],
            ];
            foreach ($hizmetler as $i => $h):
            ?>
            <div class="col-md-3 col-sm-6">
                <button type="button" class="hizmet-kutu w-100 h-100 border-0 hizmet-tetik-emlak" data-index="<?php echo $i; ?>">
                    <div class="hizmet-ikon"><i class="bi <?php echo $h[0]; ?>"></i></div>
                    <h6 class="fw-bold"><?php echo $h[1]; ?></h6>
                    <p class="text-muted mb-0"><?php echo $h[2]; ?></p>
                </button>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Tıklanan hizmetin detayının göründüğü panel -->
        <div id="emlakDetayPaneli" class="form-kutu mt-4" style="display:none;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <span class="badge badge-kategori text-white mb-2" id="emlakDetayRozet"></span>
                    <h4 class="fw-bold" id="emlakDetayBaslik"></h4>
                </div>
                <button type="button" class="btn-close" id="emlakDetayKapat" aria-label="Kapat"></button>
            </div>
            <p class="metin-govde text-muted mb-0" id="emlakDetayMetin"></p>
        </div>

        <script>
        const emlakHizmetVerisi = <?php echo json_encode($hizmetler, JSON_UNESCAPED_UNICODE); ?>;
        document.addEventListener('DOMContentLoaded', function () {
            const panel = document.getElementById('emlakDetayPaneli');
            document.querySelectorAll('.hizmet-tetik-emlak').forEach(function (buton) {
                buton.addEventListener('click', function () {
                    const veri = emlakHizmetVerisi[this.getAttribute('data-index')];
                    document.getElementById('emlakDetayRozet').textContent = veri[1];
                    document.getElementById('emlakDetayBaslik').textContent = veri[1];
                    document.getElementById('emlakDetayMetin').textContent = veri[3];
                    panel.style.display = 'block';
                    panel.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            });
            document.getElementById('emlakDetayKapat').addEventListener('click', function () {
                panel.style.display = 'none';
            });
        });
        </script>
    </section>

    <!-- İmar Durumu Sorgulama Formu -->
    <section class="mb-5">
        <h2 class="bolum-baslik">İmar Durumu Sorgulama</h2>
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

            <form method="POST" action="emlak-istimlak.php" class="js-dogrula needs-validation" novalidate>
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
                    <div class="col-md-6">
                        <label class="form-label">Ada / Parsel No *</label>
                        <input type="text" name="ada_parsel" class="form-control" required placeholder="Örn: 1234 / 5"
                               value="<?php echo htmlspecialchars($adaParsel ?? ''); ?>">
                        <div class="invalid-feedback">Lütfen ada/parsel numarasını girin.</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Açıklama</label>
                        <textarea name="aciklama" rows="3" class="form-control" placeholder="Talebinizle ilgili ek bilgi (isteğe bağlı)"><?php echo htmlspecialchars($aciklama ?? ''); ?></textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-belediye px-4">
                            <i class="bi bi-search me-1"></i> Sorgu Talebi Gönder
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Konum -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Konum</h2>
        <div class="rounded-3 overflow-hidden shadow-sm" style="height:350px;">
            <iframe
                src="https://www.google.com/maps?q=G%C3%BCzeller+Mahallesi+Bahar+Caddesi+No1+Gebze+Kocaeli&output=embed"
                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
            </iframe>
        </div>
    </section>

    <!-- İletişim -->
    <section class="mb-5">
        <h2 class="bolum-baslik">İletişim</h2>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="hizmet-kutu text-start">
                    <i class="bi bi-building fs-4 text-danger mb-2"></i>
                    <h6 class="fw-bold mb-0">Emlak ve İstimlak Müdürlüğü</h6>
                    <p class="small text-muted mb-1">Müdür: Şaban SARIAY</p>
                    <p class="small text-muted mb-0">Güzeller Mah. Bahar Cad. No:1, Gebze</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="hizmet-kutu text-start">
                    <i class="bi bi-telephone-fill fs-4 text-danger mb-2"></i>
                    <h6 class="fw-bold mb-0">Telefon</h6>
                    <p class="small text-muted mb-0">+90 262 642 04 30 (dahili 3)</p>
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

    <!-- Sık Sorulan Sorular -->
    <section>
        <h2 class="bolum-baslik">Sık Sorulan Sorular</h2>
        <div class="accordion" id="sssAccordion">
            <?php
            $sss = [
                ['İmar durumu belgesi ne işe yarar?', 'Bir parselin hangi amaçla kullanılabileceğini, yapılaşma koşullarını (kat yüksekliği, taban alanı vb.) gösteren resmi belgedir; genelde tapu işlemleri ve inşaat öncesi gereklidir.'],
                ['İmar durumu sorgusu ne kadar sürede sonuçlanır?', 'Talepler genellikle 5-10 iş günü içinde değerlendirilip başvurduğunuz iletişim bilgisi üzerinden size dönüş yapılır.'],
                ['Kamulaştırma bedelleri nasıl belirlenir?', 'Kamulaştırma bedeli, yasal mevzuata uygun olarak yapılan kıymet takdiri çalışmasıyla belirlenir.'],
                ['Yapı ruhsatı başvurusu için hangi belgeler gerekir?', 'Tapu belgesi, mimari proje, statik proje ve ilgili diğer teknik belgeler gereklidir; detaylı liste için müdürlüğümüzle iletişime geçebilirsiniz.'],
            ];
            foreach ($sss as $i => $s):
                $id = 'sss' . $i;
            ?>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button <?php echo $i === 0 ? '' : 'collapsed'; ?>" type="button"
                            data-bs-toggle="collapse" data-bs-target="#<?php echo $id; ?>">
                        <?php echo htmlspecialchars($s[0]); ?>
                    </button>
                </h2>
                <div id="<?php echo $id; ?>" class="accordion-collapse collapse <?php echo $i === 0 ? 'show' : ''; ?>"
                     data-bs-parent="#sssAccordion">
                    <div class="accordion-body small text-muted"><?php echo htmlspecialchars($s[1]); ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
