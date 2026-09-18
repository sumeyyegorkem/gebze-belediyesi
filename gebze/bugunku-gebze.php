<?php require_once '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bugünkü Gebze | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=68" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-graph-up-arrow me-2"></i>Bugünkü Gebze</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3 mb-4 mb-lg-0">
            <?php $aktifGebze = 'bugunku-gebze'; include '../includes/gebze-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Bugünkü Gebze'; include '../includes/kurumsal-icerik-ust.php'; ?>

    <p class="metin-govde">
        Gebze, Marmara Bölgesi'nin doğusunda, İzmit Körfezi'nin kuzey kesiminde yer alan; tarımı,
        hayvancılığı ve özellikle sanayisiyle hızla gelişen bir Kocaeli ilçesidir. İstanbul'a 45,
        İzmit'e 49 kilometre uzaklıkta, deniz seviyesinden 130 metre yükseklikte bulunan ilçe,
        Marmara Bölgesi'nin en büyük ikinci ilçesi olup Türkiye sanayi üretiminin yaklaşık %15'ini
        barındırmaktadır.
    </p>
    <p class="metin-govde">
        Limanlara, havalimanına, devlet demiryollarına ve E-5 ile TEM karayollarına yakınlığı,
        Gebze'yi hem Avrupa'ya yönelik ticarette hem de Anadolu ve Orta Asya'ya geçişte önemli bir
        kavşak konumuna taşımıştır. Ucuz ve kolay bulunur arazi maliyetleri, sanayi tesislerinin
        yıllar içinde İstanbul'dan Gebze'ye kaymasında belirleyici olmuştur.
    </p>
    <p class="metin-govde">
        İlçe sınırları içinde göl, dağ ve akarsu bulunmamakla birlikte, en yükseği Gaziler Tepesi
        olan 650 metreyi geçmeyen tepeler ve sırtlar yer alır. Karadeniz ile Akdeniz iklimleri
        arasında geçiş özelliği taşıyan Gebze'de yıllık ortalama yağış 550 mm'dir; en sıcak ay
        ortalaması 24,2 derece ile Ağustos, en soğuk ay ortalaması 6,5 derece ile Ocak'tır.
    </p>
    <p class="metin-govde">
        2008 yılında yürürlüğe giren kanunla Çayırova, Darıca ve Dilovası ilçe olarak Gebze'den
        ayrılmış, bu değişiklik ilçenin nüfusuna da yansımıştır:
    </p>
    <ul class="list-unstyled metin-govde mb-4">
        <li class="d-flex align-items-center mb-1"><i class="bi bi-graph-up-arrow me-2" style="color:var(--altin);"></i><strong class="me-2">1973:</strong> 27.000 kişi</li>
        <li class="d-flex align-items-center mb-1"><i class="bi bi-graph-up-arrow me-2" style="color:var(--altin);"></i><strong class="me-2">1990:</strong> 159.116 kişi</li>
        <li class="d-flex align-items-center mb-1"><i class="bi bi-graph-up-arrow me-2" style="color:var(--altin);"></i><strong class="me-2">2000:</strong> 253.487 kişi</li>
        <li class="d-flex align-items-center mb-1"><i class="bi bi-graph-up-arrow me-2" style="color:var(--altin);"></i><strong class="me-2">2007:</strong> 521.291 kişi</li>
        <li class="d-flex align-items-center mb-1"><i class="bi bi-graph-up-arrow me-2" style="color:var(--altin);"></i><strong class="me-2">2008 (yeni ilçe ayrımı sonrası):</strong> 288.569 kişi</li>
    </ul>

    <h4 class="fw-bold mt-4">Önemli Kurum ve Kuruluşlar</h4>
    <p class="metin-govde">
        1985 yılında kurulan Gebze Organize Sanayi Bölgesi (GOSB), Gebze merkezine 7 km mesafede
        10.370.000 m²'lik alanda 85 firmada yaklaşık 9.100 kişiye istihdam sağlamaktadır; yatırımların
        tutar bazında %65'i yabancı sermayelidir. GOSB bünyesinde makine, kimya, otomotiv yan sanayi,
        optik, elektronik, gıda-ambalaj ve bilişim sektörlerinde üretim yapan firmalar yer alır.
    </p>
    <p class="metin-govde">
        Türk Standartları Enstitüsü (TSE), Gebze'deki laboratuvarlarında kalibrasyon, deney ve
        tahribatsız muayene hizmetleriyle çeşitli belgelendirme hizmetleri sunar. 1985'te kurulan
        TÜSSİDE ise kamu ve özel sektör yönetici ve çalışanlarına liderlik, stratejik yönetim ve
        kalite kültürü alanlarında eğitim vermektedir.
    </p>
    <p class="metin-govde">
        Gebze Teknik Üniversitesi, Türkiye'nin tıp fakültesi bulunmayan üniversiteler arasında en
        iyi ikincisi olarak gösterilen, ilçe sınırları içindeki başlıca yükseköğretim kurumudur.
        TÜBİTAK Marmara Araştırma Merkezi de Bilişim Teknolojileri, Enerji, Yer ve Deniz Bilimleri
        ile Malzeme Enstitüleri ve MARTEK Teknopark'ıyla bölgenin bilim ve teknoloji üssü
        niteliğindedir.
    </p>

            <?php $galeriSayfa = 'bugunku-gebze'; include '../includes/fotograf-galerisi-bolum.php'; ?>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
