<?php 
require_once '../config/db.php'; 

// URL'den gelen sayfa parametresini alıyoruz, yoksa varsayılan: vizyon
$sayfa = $_GET['sayfa'] ?? 'vizyon';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurumsal | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=67" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
    <style>
        /* Bu stil SADECE bu sayfada geçerli, siteyi etkilemez */
        .accordion-button:not(.collapsed) {
            background-color: var(--lacivert);
            color: #fff;
            box-shadow: none;
        }
        .accordion-button:not(.collapsed) i { color: var(--altin) !important; }
        .accordion-button:not(.collapsed)::after { filter: invert(1) brightness(2); }
        .accordion-button:focus { box-shadow: none; border-color: var(--altin); }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-building me-2"></i>Kurumsal</h1>
    </div>
</header>

<div class="container py-5">
    <div class="row">
        <!-- SOL MENÜ (SIDEBAR) -->
        <div class="col-lg-3 mb-4 mb-lg-0">
            <?php 
            $aktifKurumsal = $sayfa; 
            include '../includes/kurumsal-sidebar.php'; 
            ?>
        </div>

        <!-- SAĞ İÇERİK ALANI -->
        <div class="col-lg-9">
            <?php
            $baslikMap = [
                'vizyon' => 'Vizyonumuz',
                'misyon' => 'Misyonumuz',
                'ilkeler' => 'İlkelerimiz',
                'ilkelerimiz' => 'İlkelerimiz',
                'dokumanlar' => 'Kurumsal Dökümanlar',
                'kurumsal-dokumanlar' => 'Kurumsal Dökümanlar',
            ];
            $sayfaBasligi = $baslikMap[$sayfa] ?? 'Kurumsal';
            include '../includes/kurumsal-icerik-ust.php';
            ?>

            <?php if ($sayfa === 'vizyon'): ?>
                <!-- VİZYONUMUZ -->
                <div class="metin-govde">
                    <p>
                        Gebze'yi; yaşam kalitesi yüksek, sürdürülebilir, katılımcı ve teknolojiyi
                        etkin kullanan bir yönetim anlayışıyla geleceğe taşıyan öncü bir belediye
                        olmak. Sanayi ve nüfus bakımından Kocaeli'nin en büyük ilçesi olan Gebze'nin,
                        aynı zamanda yaşanabilirlik, çevre duyarlılığı ve kültürel zenginlik
                        açısından da örnek gösterilen bir kent haline gelmesini hedefliyoruz.
                    </p>
                    <p>
                        Kentleşme sürecinde tarihi ve kültürel mirasımızı koruyarak, çağın
                        gerektirdiği altyapı ve teknolojik dönüşümü hemşehrilerimizin hizmetine
                        sunmayı; şeffaf, hesap verebilir ve katılımcı bir belediyecilik anlayışını
                        her kararımızın merkezine yerleştirmeyi vizyon ediniyoruz.
                    </p>
                </div>

            <?php elseif ($sayfa === 'misyon'): ?>
                <!-- MİSYONUMUZ -->
                <div class="metin-govde">
                    <p>
                        Gebze’de yaşam kalitesini arttırmak için yerel hizmetleri, adil, etkin ve sürekli bir biçimde sunmaktır.
                    </p>
                </div>

            <?php elseif ($sayfa === 'ilkeler' || $sayfa === 'ilkelerimiz'): ?>
                <!-- İLKELERİMİZ -->
                <ul class="list-unstyled metin-govde">
                    <?php
                    $ilkeler = [
                        'Belediye hizmetlerinde kalite, etkinlik ve verimlilik sağlamak görevimizdir.',
                        'Belediye karar ve uygulamalarında şeffaflık ve hesap verebilirlik esastır.',
                        'Belediye hizmetlerinde insan ve vatandaş odaklılık esastır.',
                        'Gebze’yi katılımcı anlayışla yönetmek temel prensiptir.',
                        'Belediye hizmetlerinin üretim ve sunumunda bilgi teknolojilerinden azami derecede yararlanmak esastır.',
                        'Belediye karar ve uygulamalarında yasalara uymak zorunluluktur.',
                        'Belediye hizmetlerinin ihtiyaçlara ve önceliklere göre adil dağıtımı esastır.',
                        'Çalışanlarımızın memnuniyeti temel önceliklerimizdendir.',
                        'Kurum kültürünün oluşturulması için çaba sarf ederiz.',
                        'Sorunları oluşmadan önlemeye çalışırız.'
                    ];
                    foreach ($ilkeler as $ilke):
                    ?>
                    <li class="d-flex align-items-start mb-3">
                        <i class="bi bi-check2-circle text-primary me-3 mt-1 fs-4"></i>
                        <span><?php echo htmlspecialchars($ilke); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>

            <?php elseif ($sayfa === 'dokumanlar' || $sayfa === 'kurumsal-dokumanlar'): ?>
                <!-- KURUMSAL DÖKÜMANLAR -->
                <p class="metin-govde mb-4">
                    Müdürlüklere ait dilekçe ve evrak örneklerine aşağıdaki listeden ulaşabilirsiniz.
                </p>
                <?php
                function dokumanIkonu($url) {
                    $uzanti = strtolower(pathinfo($url, PATHINFO_EXTENSION));
                    if ($uzanti === 'pdf') return 'bi-file-earmark-pdf-fill';
                    if (in_array($uzanti, ['doc', 'docx'])) return 'bi-file-earmark-word-fill';
                    return 'bi-file-earmark-fill';
                }

                $mudurlukler = [
                    [
                        'isim' => 'Zabıta Müdürlüğü',
                        'dilekceler' => [
                            ['Pazar Yeri İptal - Tahsis Dilekçesi', 'https://www.gebze.bel.tr/dosya/20200108095357.doc'],
                            ['Pazar Yeri Sorgulama', 'https://www.gebze.bel.tr/dosya/20200108095419.doc'],
                            ['Rayiç Bedel Dilekçesi', 'https://www.gebze.bel.tr/dosya/20200108095445.doc'],
                        ],
                        'evraklar' => [
                            ['Zabıta Müdürlüğü Organizasyon Şeması', 'https://www.gebze.bel.tr/dosya/20260505171527.pdf'],
                        ],
                    ],
                    [
                        'isim' => 'Mali Hizmetler Müdürlüğü',
                        'dilekceler' => [
                            ['376 Pişmanlık', 'https://www.gebze.bel.tr/dosya/20200108102334.doc'],
                            ['Ç.T.V Kapatma', 'https://www.gebze.bel.tr/dosya/20200108102354.doc'],
                            ['Ç.T.V Mükerrer İptal', 'https://www.gebze.bel.tr/dosya/20200108102411.doc'],
                            ['Çevre Temizlik Adres Değişikliği', 'https://www.gebze.bel.tr/dosya/20200108102437.doc'],
                        ],
                        'evraklar' => [],
                    ],
                    [
                        'isim' => 'İmar ve Şehircilik Müdürlüğü',
                        'dilekceler' => [
                            ['6306 Kanun Gereği Tapu Satış Dilekçesi', 'https://www.gebze.bel.tr/dosya/20200904151731.docx'],
                            ['Bağımsız Bölüm Planı Onayı Başvuru Dilekçesi', 'https://www.gebze.bel.tr/dosya/20200904151748.docx'],
                            ['Emsal Krokisi Evrakı Başvuru Dilekçesi', 'https://www.gebze.bel.tr/dosya/20200904151803.docx'],
                            ['Hafriyat Taşıma ve Kabul Belgesi Dilekçesi', 'https://www.gebze.bel.tr/dosya/20200904151815.docx'],
                        ],
                        'evraklar' => [],
                    ],
                    ['isim' => 'Ruhsat ve Denetim Müdürlüğü', 'dilekceler' => [], 'evraklar' => []],
                    ['isim' => 'Sosyal Destek Hizmetleri Müdürlüğü', 'dilekceler' => [], 'evraklar' => []],
                    ['isim' => 'Fen İşleri Müdürlüğü', 'dilekceler' => [], 'evraklar' => []],
                    ['isim' => 'Park ve Bahçeler Müdürlüğü', 'dilekceler' => [], 'evraklar' => []],
                    ['isim' => 'Temizlik İşleri Müdürlüğü', 'dilekceler' => [], 'evraklar' => []],
                    ['isim' => 'Emlak ve İstimlak Müdürlüğü', 'dilekceler' => [], 'evraklar' => []],
                    ['isim' => 'Veteriner İşleri Müdürlüğü', 'dilekceler' => [], 'evraklar' => []],
                    ['isim' => 'Plan ve Proje Müdürlüğü', 'dilekceler' => [], 'evraklar' => []],
                ];
                ?>
                <div class="accordion" id="dokumanAkordiyon">
                    <?php foreach ($mudurlukler as $i => $m): ?>
                    <div class="accordion-item mb-3 border-0 rounded-3 shadow-sm overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#dokuman<?php echo $i; ?>">
                                <i class="bi bi-folder-fill me-2" style="color:var(--altin);"></i>
                                <?php echo htmlspecialchars($m['isim']); ?>
                            </button>
                        </h2>
                        <div id="dokuman<?php echo $i; ?>" class="accordion-collapse collapse"
                             data-bs-parent="#dokumanAkordiyon">
                            <div class="accordion-body">
                                <?php if (empty($m['dilekceler']) && empty($m['evraklar'])): ?>
                                    <p class="text-muted mb-0">
                                        <i class="bi bi-hourglass-split me-2"></i>Bu müdürlük için dökümanlar yakında eklenecektir.
                                    </p>
                                <?php else: ?>
                                    <?php if (!empty($m['dilekceler'])): ?>
                                        <h6 class="fw-bold mb-2" style="color:var(--lacivert-koyu);">Dilekçe Listeleri</h6>
                                        <ul class="list-unstyled mb-3">
                                            <?php foreach ($m['dilekceler'] as $d): ?>
                                            <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                <span><i class="bi <?php echo dokumanIkonu($d[1]); ?> me-2" style="color:var(--lacivert);"></i><?php echo htmlspecialchars($d[0]); ?></span>
                                                <a href="<?php echo htmlspecialchars($d[1]); ?>" target="_blank" class="btn btn-sm btn-lacivert">İndir</a>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                    <?php if (!empty($m['evraklar'])): ?>
                                        <h6 class="fw-bold mb-2" style="color:var(--lacivert-koyu);">Evrak Listeleri</h6>
                                        <ul class="list-unstyled mb-0">
                                            <?php foreach ($m['evraklar'] as $e): ?>
                                            <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                <span><i class="bi <?php echo dokumanIkonu($e[1]); ?> me-2" style="color:var(--lacivert);"></i><?php echo htmlspecialchars($e[0]); ?></span>
                                                <a href="<?php echo htmlspecialchars($e[1]); ?>" target="_blank" class="btn btn-sm btn-lacivert">İndir</a>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

            <?php endif; ?>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>