<?php require_once '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarihçe | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=68" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-clock-history me-2"></i>Tarihçe</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <!-- SOL MENÜ (SIDEBAR) -->
        <div class="col-lg-3 mb-4 mb-lg-0">
            <?php $aktifGebze = 'tarihce'; include '../includes/gebze-sidebar.php'; ?>
        </div>

        <!-- SAĞ İÇERİK ALANI -->
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Tarihçe'; include '../includes/kurumsal-icerik-ust.php'; ?>

            <h4 class="fw-bold mt-4">Tarihte Gebze</h4>
            <p class="metin-govde">
                Gebze'nin de içinde bulunduğu, eski Yunanlılar'ın ve Romalılar'ın Bitinya (Bithynie) dedikleri
                coğrafi bölgenin bilinen en eski tarihi, M.Ö. XII. yüzyıla kadar dayanır. Bölge, özellikle
                Kocaeli Yarımadası, coğrafi konumunun öneminden dolayı, tarihin hemen hemen bütün dönemlerinde
                birçok ulusa yurt olmuştur. Asya ile Avrupa kıtaları arasındaki en önemli geçit yeri olan
                Kocaeli Yarımadası, ya birçok ulusun yurdu ya da gelip geçtikleri, medeniyetlerinden izler
                bıraktığı bir yer olmuştur.
            </p>
            <p class="metin-govde">
                Bilinen ilk ulus göçü de M.Ö. XII. yüzyılın başlarındadır. Bu ulus Yunan kökenli Frikler'dir;
                Boğaz (Bosforos) yoluyla Anadolu'ya inmişlerdir. XII. yüzyıla kadar Trakya'dan İzmit dolaylarına
                göçler devam etmiş, fakat bu dönemde eski Gebze'nin yerine dair kesin bilgi edinilememiştir.
            </p>
            <p class="metin-govde">
                Bugün Gebze'nin bulunduğu yerde, M.Ö. 281-246 yıllarında Kral I. Nikomedes'in egemenliğindeki
                Bitinya Krallığı döneminde Dakibyza ve Libyssa adında yerleşmeler bulunmaktaydı. Bu yerleşim
                alanlarının araştırmalara konu olmasının en önemli nedeni, ünlü Kartacalı komutan Hannibal'ın
                krallık döneminde burada yaşamış olmasıdır.
            </p>
            <p class="metin-govde">
                Zama Savaşı'ndaki yenilgisinin ardından ülkesinde itibarını yitiren Hannibal, Bitinya Krallığı'na
                sığınmış ve I. ile II. Prusias'ın savaş danışmanlığını yapmıştır. II. Prusias'ın ihaneti sonucu
                düşmanın eline düşmemek için hayatına son vermiş ve Libyssa'ya defnedilmiştir. Roma kuvvetlerinden
                gizlenmek isteyen Hannibal, korunaklı, kaçışa elverişli ve denize yakın bu bölgeyi bilinçli olarak
                seçmiştir.
            </p>
            <p class="metin-govde">
                1330 yılında Osmanlılar ile Bizans arasındaki savaşın ardından Gebze'nin de içinde bulunduğu
                bölge Osmanlı idaresine dahil edilmiştir. Bugünkü Gebze'nin kurucusu Orhan Gazi'dir; bölgede kendi
                adına bir cami de yaptıran Orhan Gazi, imar ve iskân için işletmeler kurmuş, vakıfları desteklemiştir.
                Akçakoca Bey'in oğlu İlyas Çelebi de hem fetihte hem de kuruluşta önemli rol oynamıştır.
            </p>
            <p class="metin-govde">
                Gebze, Osmanlı İmparatorluğu'nun son dönemlerine kadar kimi zaman İstanbul'a, çoğunlukla da
                Kocaeli'ye bağlı önemli bir kaza olma özelliğini korumuştur. I. Dünya Savaşı sonrasında Anadolu
                ve Trakya'nın birçok bölgesi gibi Gebze de işgale uğramış; 1920'de İngilizler'in, 1921 başında
                ise Yunanlılar'ın işgaline sahne olmuştur. 18-19 Ocak 1923 tarihli Hakimiyet-i Milliye gazetesinde,
                Atatürk'ün bölgeyi ve Gebze'yi ziyaret ettiği ve buradaki askeri birliklerin durumundan memnun
                kaldığı aktarılır. Cumhuriyet'in ilanının ardından Gebze, yeni iller kanununa göre İzmit'e
                bağlanmıştır.
            </p>

            <h4 class="fw-bold mt-4">Libyssa'dan Gebze'ye</h4>
            <p class="metin-govde">
                Gebze adının kökeni, araştırmacıların çoğuna göre bölgedeki eski yerleşim adlarına
                dayanmaktadır. Antik çağ kaynaklarında Libyssa, Roma ve Bizans dönemlerinde ise Dakibyza adı
                kullanılmıştır; bu isimlerin okunuşunun günümüzdeki "Gebze" sözcüğüne benzerliği, kelimenin
                kökeninin çok eskiye dayandığını göstermektedir.
            </p>
            <p class="metin-govde">
                Tarih boyunca kaynaklara göre Gebseh, Gebisseh, Gjabseh, Gekbuze, Ghviza, Gavize, Dschebse,
                Dschebize, Gebize gibi farklı yazımlar da kullanılmıştır. Evliya Çelebi, Seyahatnamesi'nde
                bölgeden bir kez "Kekbeziye" olarak söz etmiş, başka bir yerde ise "Gebze" adının "Gelbize"den
                geldiğini yazmıştır. Araştırmacı İbrahim Hakkı Konyalı, Osmanlı arşiv kayıtlarında Geybüyze,
                Geybüveyze, Geyibüveyze, Geyiboyze, Geykivize gibi biçimlerin yer aldığını, günümüzde ise
                "Gebze" adının yerleştiğini belirtmiştir.
            </p>
            <p class="metin-govde">
                Halk arasında bir söylenceye göre, Osmanlı ve Bizans akınları sırasında sıkça el değiştiren
                ve özlenen bir yer olması nedeniyle "Gel bize" / "Bize gel" ifadelerinin zamanla halk dilinde
                "Gebze"ye dönüştüğü de aktarılır; ancak 1640'ta bölgeye gelen Evliya Çelebi, bu adın "Gelbize"
                kelimesinin bozulmuş biçimi olduğunu ifade etmiştir.
            </p>

            <h4 class="fw-bold mt-4">Yöresel Kültür ve Gelenekler</h4>

            <h6 class="fw-bold mt-3">Düğün</h6>
            <p class="metin-govde">
                Köylerde düğünlerde dışarıda ateş yakılır, kazanlarda düğün yemekleri pişirilir; misafirlere
                düğün çorbası, etli yemek, pilav ve zerde tatlısı ikram edilir. Düğünden önce çeyiz sergisi
                yapılır, Cuma günü gelin hamamına gidilir ve gelin çalgılarla hamamdan çıkarılır. Köylüler,
              genç kızların ev ev dolaşarak yaptığı davetlerle düğüne çağrılır. Büyük kına (düğün) gününde
                gelinin başında "bereket" dileğiyle ekmek kırılır.
            </p>

            <h6 class="fw-bold mt-3">Cenaze</h6>
            <p class="metin-govde">
                Cenaze geleneksel usullere göre yıkanır ve kıbleye yönlendirilerek hazırlanır; vefatın
                duyurulmasında "sela" okunur. Mevta dışarıda yıkanmışsa, yedi gece boyunca o alanda ışık
                yakılmaya devam edilir.
            </p>

            <h6 class="fw-bold mt-3">Bayramlar</h6>
            <p class="metin-govde">
                Bayramlaşmanın hangi köyde ne zaman yapılacağı camilerde duyurulur; o gün ev sahibi köye
                komşu köylerden ziyaretler gerçekleşir, pilav ve ikramlar hazırlanır, gençler gruplar hâlinde
                köy köy gezerek bayramlaşır.
            </p>

            <h6 class="fw-bold mt-3">Hıdırellez</h6>
            <p class="metin-govde">
                5-6 Mayıs'ta kutlanan Hıdırellez'de mayasız hamur yoğrulur, bereket ve bolluğa dair çeşitli
                inanışlar canlı tutulur. Genç kızlar küçük eşyalarını gömüp erkeklerin bulmasını bekler, ateş
                yakılıp üzerinden atlanır, soğan yapraklarıyla dilek tutulur.
            </p>

            <h6 class="fw-bold mt-3">Yöresel Yemekler</h6>
            <p class="metin-govde">
                Çarşır mancarı, kazayağı mancarı, ebegümeci mancarı, efelik mancarı, mantı, yamayuka böreği,
                tava tutuşturması, bulgurlu börek, sirkem mancar, kabak tatlısı, höşmerim, peynir höşmeli,
                kocagörmez, cızlama (nazlım), kabaklı börek ve tarta (dartı) yöreye özgü başlıca lezzetlerdir.
            </p>

            <h6 class="fw-bold mt-3">Giysiler</h6>
            <p class="metin-govde">
                Özellikle keten tarımıyla uğraşan dağ köylerinde halk, kendi el işi giysilerini tercih
                etmiştir. Kadınlar çoğunlukla şalvar, yelek ve hırka giyer; başlarına işlemeli ya da beyaz
                yazma örter, boyunlarına gerdanlık takarlardı.
            </p>

            <h6 class="fw-bold mt-3">Yöresel Atasözleri</h6>
            <ul class="metin-govde">
                <li>Anahtarı belinde, her gün babası evinde.</li>
                <li>Avludan bez alma; kına tamından kız alma.</li>
                <li>Çocuk, evin yemişidir.</li>
                <li>Dön dolaş, yine değirmen taşı.</li>
                <li>Eşek kendi yüküne yenilmez.</li>
                <li>İç güveyisi, iç ağrısı.</li>
                <li>Kırk taşımız var; ata ata vuracağız bu işi.</li>
                <li>Kırk kulpu kazan, birinden tut sen de kazan.</li>
                <li>Ye tatlıyı, iç suyu; ağzın dönsün yala.</li>
                <li>Ye tuzluyu, iç suyu; ağzın dönsün bala.</li>
            </ul>

            <?php $galeriSayfa = 'tarihce'; $galeriBaslikMetni = 'Tarihten Kareler'; include '../includes/fotograf-galerisi-bolum.php'; ?>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
