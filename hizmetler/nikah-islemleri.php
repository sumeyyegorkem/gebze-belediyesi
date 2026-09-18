<?php
require_once '../config/db.php';

$basariMesaji = '';
$hataMesaji = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gelinDamatAdi = trim($_POST['gelin_damat_adi'] ?? '');
    $esininAdi = trim($_POST['esinin_adi'] ?? '');
    $telefon = trim($_POST['telefon'] ?? '');
    $eposta = trim($_POST['eposta'] ?? '');
    $istenenTarih = trim($_POST['istenen_tarih'] ?? '');
    $notlar = trim($_POST['notlar'] ?? '');

    if ($gelinDamatAdi === '' || $esininAdi === '' || $telefon === '' || $istenenTarih === '') {
        $hataMesaji = 'Lütfen zorunlu (*) alanları doldurun.';
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO nikah_randevu_talepleri (gelin_damat_adi, esinin_adi, telefon, eposta, istenen_tarih, notlar)
             VALUES (:gelin_damat_adi, :esinin_adi, :telefon, :eposta, :istenen_tarih, :notlar)"
        );
        $stmt->execute([
            'gelin_damat_adi' => $gelinDamatAdi,
            'esinin_adi' => $esininAdi,
            'telefon' => $telefon,
            'eposta' => $eposta,
            'istenen_tarih' => $istenenTarih,
            'notlar' => $notlar,
        ]);

        $basariMesaji = 'Randevu talebiniz alındı. Belediyemiz en kısa sürede sizinle iletişime geçecektir.';
        $gelinDamatAdi = $esininAdi = $telefon = $eposta = $istenenTarih = $notlar = '';
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nikah İşlemleri | Gebze Belediyesi</title>
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
        <h1 class="mb-0"><i class="bi bi-heart-fill me-2"></i>Nikah İşlemleri</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4 mb-lg-0">
            <?php $aktifHizmet = 'nikah'; include '../includes/hizmet-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Nikah İşlemleri'; include '../includes/kurumsal-icerik-ust.php'; ?>

    <!-- Nikah Salonu Tanıtımı -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Gebze Belediyesi Nikah Salonu</h2>
        <p class="metin-govde mb-0">
            Modern ve şık tasarımıyla nikah salonumuz, hayatınızın en özel anını
            unutulmaz kılmak için hizmetinizde. Salonumuz 150 kişilik oturma
            kapasitesine sahip olup, hafta içi ve hafta sonu randevu alınabilmektedir.
        </p>
    </section>

    <!-- Başvuru Adımları -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Başvuru Adımları</h2>
        <div class="row g-4">
            <?php
            $adimlar = [
                ['1', 'Ön Başvuru', 'Bu sayfadaki randevu formunu doldurarak ön başvurunuzu yapın, ya da Evlendirme Dairesi\'ni telefonla arayın.'],
                ['2', 'Belge Teslimi', 'Aşağıda listelenen belgelerle birlikte Evlendirme Dairesi\'ne şahsen başvurun.'],
                ['3', 'Randevu Onayı', 'Belgeleriniz kontrol edildikten sonra size uygun tarih ve saat için randevunuz kesinleşir.'],
                ['4', 'Nikah Töreni', 'Belirlenen gün ve saatte nikah salonumuzda töreniniz gerçekleştirilir.'],
            ];
            foreach ($adimlar as $a):
            ?>
            <div class="col-md-3 col-6">
                <div class="hizmet-kutu h-100">
                    <div class="hizmet-ikon"><?php echo $a[0]; ?></div>
                    <h6 class="fw-bold"><?php echo $a[1]; ?></h6>
                    <p class="small text-muted mb-0"><?php echo $a[2]; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Gerekli Belgeler -->
    <section class="mb-4">
        <h2 class="bolum-baslik">Nikah İçin Gerekli Evraklar</h2>
        <div class="row g-3">
            <?php
            $belgeler = [
                'Çiftlerin T.C. kimlik/nüfus cüzdanı fotokopisi (varsa boşanma veya vefat bilgisi işlenmiş olmalı)',
                'Son 6 ay içinde çekilmiş 3\'er adet vesikalık fotoğraf (yabancı uyruklu başvurularda 4\'er adet)',
                'Aile hekiminden alınan, Evlendirme Yönetmeliği\'ne uygun evlenme sağlık raporu',
                'Kızlık soyadını kullanmak isteyen kadınlar için soyadı dilekçesi (memurluğumuzda hazır bulunur)',
                'Mal rejimi sözleşmesi yapılmışsa, sözleşmeye ait belgeler',
            ];
            foreach ($belgeler as $b):
            ?>
            <div class="col-md-6">
                <div class="d-flex align-items-start p-3 rounded-3" style="background:var(--acik-gri);">
                    <i class="bi bi-file-earmark-check-fill text-danger fs-5 me-3 mt-1"></i>
                    <span class="small"><?php echo htmlspecialchars($b); ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Yaş Şartı, Boşanmış/Dul, Yabancı Uyruklu -->
    <section class="mb-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="hizmet-kutu h-100 text-start">
                    <i class="bi bi-person-check-fill fs-3 text-danger mb-2"></i>
                    <h6 class="fw-bold">Yaş Şartı</h6>
                    <ul class="small text-muted ps-3 mb-0">
                        <li>18 yaşını dolduranlar kendi başına evlenebilir.</li>
                        <li>17 yaşını dolduranlar anne-baba onayıyla (gelemiyorlarsa noter onaylı muvafakatname ile) evlenebilir.</li>
                        <li>16 yaşını dolduranlar, aile mahkemesinin kesinleşmiş izin kararıyla evlenebilir.</li>
                    </ul>
                </div>
            </div>
            <div class="col-md-4">
                <div class="hizmet-kutu h-100 text-start">
                    <i class="bi bi-person-heart fs-3 text-danger mb-2"></i>
                    <h6 class="fw-bold">Boşanmış / Dul Kadınlar</h6>
                    <p class="small text-muted mb-0">
                        Boşanma ya da eşin vefat tarihinin nüfusa işlenmesinden itibaren 300 gün (10 ay) geçmeden
                        yeniden evlenilemez. Bu süreyi kaldırmak için kesinleşmiş mahkeme kararı gereklidir.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="hizmet-kutu h-100 text-start">
                    <i class="bi bi-globe-americas fs-3 text-danger mb-2"></i>
                    <h6 class="fw-bold">Yabancı Uyruklu Başvurular</h6>
                    <p class="small text-muted mb-0">
                        Ülkeye göre değişen bekarlık/evlenme ehliyet belgesi, doğum kayıt belgesi ve pasaport
                        fotokopisi gerekir; belgelerin onaylı Türkçe çevirisi istenir. Detaylı bilgi için
                        Evlendirme Dairesi'yle iletişime geçmenizi öneririz.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Önemli Notlar -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Önemli Notlar</h2>
        <ul class="metin-govde">
            <li>Başvurular hafta içi 09.00-12.30 / 13.30-17.00 saatleri arasında, çiftin birlikte gelmesiyle alınır.</li>
            <li>Gelemeyen taraf için noterden düzenlenmiş özel vekaletname ile başvuru yapılabilir.</li>
            <li>Nikah günü, T.C. kimlikli ve 18 yaşını doldurmuş 2 şahit gerekir (anne-baba şahit olamaz).</li>
            <li>Tüm evrakların geçerlilik süresi 6 aydır.</li>
            <li>Saat 16.30'dan sonra nikah kıyılmaz.</li>
        </ul>
    </section>

    <!-- İndirilebilir Döküman -->
    <section class="mb-5">
        <h2 class="bolum-baslik">İndirilebilir Dökümanlar</h2>
        <a href="../uploads/nikah-ucret-tarifesi.docx" download
           class="hizmet-kutu d-flex align-items-center justify-content-between text-decoration-none">
            <span>
                <i class="bi bi-file-earmark-word-fill fs-4 text-danger me-2"></i>
                2026 Yılı Nikah Ücret Tarifesi <small class="text-muted">(DOCX)</small>
            </span>
            <i class="bi bi-download fs-5"></i>
        </a>
    </section>

    <!-- Fotoğraf Galerisi -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Fotoğraf Galerisi</h2>
        <?php
        // Tablo yoksa oluştur (diğer galeri sayfalarıyla aynı ortak kontrol)
        require_once '../includes/fotograf-galerisi-tablo-kontrol.php';
        // Bu sayfada daha önce sabit (hardcoded) duran 3 fotoğrafı, galeri boşsa bir kereliğine veritabanına aktarıyoruz
        $nikahGaleriSayisi = $pdo->query("SELECT COUNT(*) AS toplam FROM fotograf_galerisi WHERE sayfa = 'nikah-islemleri'")->fetch()['toplam'];
        if ((int)$nikahGaleriSayisi === 0) {
            $pdo->exec("INSERT INTO fotograf_galerisi (baslik, resim_url, sayfa) VALUES
                ('Nikah Salonu', 'img/nikah-salonu.jpg', 'nikah-islemleri'),
                ('Nikah Salonu', 'https://www.gebze.bel.tr/haber/20210608170001.jpg', 'nikah-islemleri'),
                ('Nikah Salonu', 'https://www.gebze.bel.tr/haber/20200217120920.jpg', 'nikah-islemleri')");
        }
        $galeriSayfa = 'nikah-islemleri';
        $galeriBaslikGoster = false;
        include '../includes/fotograf-galerisi-bolum.php';
        ?>
    </section>

    <!-- Randevu Talep Formu -->
    <section class="mb-5">
        <h2 class="bolum-baslik">Randevu Talep Formu</h2>
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

            <form method="POST" action="nikah-islemleri.php" class="js-dogrula needs-validation" novalidate>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Adınız Soyadınız *</label>
                        <input type="text" name="gelin_damat_adi" class="form-control" required
                               value="<?php echo htmlspecialchars($gelinDamatAdi ?? ''); ?>">
                        <div class="invalid-feedback">Lütfen adınızı girin.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Eşinizin Adı Soyadı *</label>
                        <input type="text" name="esinin_adi" class="form-control" required
                               value="<?php echo htmlspecialchars($esininAdi ?? ''); ?>">
                        <div class="invalid-feedback">Lütfen eşinizin adını girin.</div>
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
                        <label class="form-label">İstenen Tarih *</label>
                        <input type="date" name="istenen_tarih" class="form-control" required
                               value="<?php echo htmlspecialchars($istenenTarih ?? ''); ?>">
                        <div class="invalid-feedback">Lütfen bir tarih seçin.</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Notlarınız</label>
                        <textarea name="notlar" rows="3" class="form-control"><?php echo htmlspecialchars($notlar ?? ''); ?></textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-belediye px-4">
                            <i class="bi bi-calendar-check-fill me-1"></i> Randevu Talep Et
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    <!-- Sık Sorulan Sorular -->
    <section>
        <h2 class="bolum-baslik">Sık Sorulan Sorular</h2>
        <div class="accordion" id="sssAccordion">
            <?php
            $sss = [
                ['Nikah için randevu ne kadar önceden alınmalı?', 'Yoğunluğa bağlı olarak en az 2-3 hafta önceden randevu almanızı öneriyoruz. Özellikle yaz aylarında talep artmaktadır.'],
                ['Nikah salonunun kapasitesi nedir?', 'Salonumuz 150 kişilik oturma kapasitesine sahiptir, ayrıca ayakta duracak misafirler için ek alan bulunmaktadır.'],
                ['Nikah ücretsiz mi?', 'Belediyemizde nikah işlemleri ücretsizdir. Sadece cüzdan bedeli gibi yasal harçlar uygulanabilir.'],
                ['Salon dışında farklı bir yerde nikah kıyılabilir mi?', 'Evet, belirli şartlar dahilinde dış mekan nikah talepleri de değerlendirilmektedir; detaylar için Evlendirme Dairesi ile iletişime geçebilirsiniz.'],
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
