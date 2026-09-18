<?php
require_once '../config/db.php';

// Tüm projeleri çekiyoruz; filtreleme ve arama JavaScript ile TARAYICI tarafında yapılacak
// (sadece aktif/yayındaki projeler - pasife alınanlar burada gösterilmez)
$projeler = $pdo->query("SELECT * FROM projeler WHERE aktif = 1 ORDER BY olusturma_tarihi DESC")->fetchAll();

// Kategori listesi
$kategoriler = [
    'İmar ve Şehircilik', 'Ulaşım, Altyapı ve Üstyapı', 'Fiziki Yatırımlar',
    'Çevre ve Sıfır Atık', 'Sosyal Belediyecilik', 'Kadın, Aile ve Çocuk',
    'Gençlik, Spor ve Eğitim', 'Kültür ve Sanat', 'Dijital Dönüşüm',
];

// Durum filtresinde her durumun yanında gösterilecek ikon
$durumIkon = [
    'devam_eden' => 'bi-hourglass-split',
    'tamamlanmis' => 'bi-check-circle-fill',
    'planli' => 'bi-calendar-week-fill',
];

// Kategori filtresinde her kategorinin yanında gösterilecek ikon
$kategoriIkon = [
    'İmar ve Şehircilik' => 'bi-building',
    'Ulaşım, Altyapı ve Üstyapı' => 'bi-truck-front-fill',
    'Fiziki Yatırımlar' => 'bi-hammer',
    'Çevre ve Sıfır Atık' => 'bi-recycle',
    'Sosyal Belediyecilik' => 'bi-people-fill',
    'Kadın, Aile ve Çocuk' => 'bi-person-hearts',
    'Gençlik, Spor ve Eğitim' => 'bi-trophy-fill',
    'Kültür ve Sanat' => 'bi-palette-fill',
    'Dijital Dönüşüm' => 'bi-cpu-fill',
];
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projelerimiz | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=63" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
    <style>
        /* Haberler sayfasındaki gibi sol bar kart görünümü */
        .sol-filtre-kutusu {
            background-color: #ffffff;
            border-radius: 0.5rem;
            padding: 1rem;
            box-shadow: 0 3px 12px rgba(11, 61, 98, 0.07);
        }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-diagram-3-fill me-2"></i>Projelerimiz</h1>
    </div>
</header>

<div class="container py-5">
    <div class="row">
        <!-- SOL FİLTRE BARI (Haberler Sayfası Düzeninde) -->
        <div class="col-lg-3 mb-4 mb-lg-0">
            <div class="sol-filtre-kutusu">
                <!-- Proje Arama -->
                <div class="mb-4">
                    <h6 class="fw-bold text-uppercase small text-muted mb-2" style="letter-spacing:.5px;">Proje Ara</h6>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                        <input type="text" id="projeArama" class="form-control border-start-0" placeholder="Proje adında ara...">
                    </div>
                </div>

                <!-- Durum Filtreleri -->
                <div class="mb-4">
                    <h6 class="fw-bold text-uppercase small text-muted mb-2" style="letter-spacing:.5px;">Durum</h6>
                    <ul class="nav nav-pills flex-column gap-2 hizmet-menu-liste" id="projeDurumFiltre">
                        <li class="nav-item">
                            <button class="nav-link active w-100 text-start btn-filtre-durum proje-tum-durum" data-durum="hepsi">Hepsi</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link w-100 text-start btn-filtre-durum" data-durum="devam_eden">
                                <span class="hizmet-kutu"><i class="bi <?php echo $durumIkon['devam_eden']; ?>"></i></span>Devam Eden
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link w-100 text-start btn-filtre-durum" data-durum="tamamlanmis">
                                <span class="hizmet-kutu"><i class="bi <?php echo $durumIkon['tamamlanmis']; ?>"></i></span>Tamamlanmış
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link w-100 text-start btn-filtre-durum" data-durum="planli">
                                <span class="hizmet-kutu"><i class="bi <?php echo $durumIkon['planli']; ?>"></i></span>Planlanan
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Kategori Filtreleri -->
                <div>
                    <h6 class="fw-bold text-uppercase small text-muted mb-2" style="letter-spacing:.5px;">Kategoriler</h6>
                    <ul class="nav nav-pills flex-column gap-2 hizmet-menu-liste kurumsal-menu-liste" id="projeKategoriFiltre">
                        <li class="nav-item">
                            <button class="nav-link active w-100 text-start btn-filtre-kategori proje-tum-kategori" data-kategori="hepsi">Tüm Kategoriler</button>
                        </li>
                        <?php foreach ($kategoriler as $k): ?>
                        <li class="nav-item">
                            <button class="nav-link w-100 text-start btn-filtre-kategori" data-kategori="<?php echo htmlspecialchars($k); ?>">
                                <span class="hizmet-kutu"><i class="bi <?php echo $kategoriIkon[$k] ?? 'bi-tag-fill'; ?>"></i></span><?php echo htmlspecialchars($k); ?>
                            </button>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- SAĞ PROJE LİSTESİ -->
        <div class="col-lg-9">
            <div class="row g-4" id="projeListesi">
                <?php if (count($projeler) === 0): ?>
                    <p class="text-muted">Henüz proje eklenmemiş.</p>
                <?php endif; ?>

                <?php foreach ($projeler as $p): ?>
                    <div class="col-lg-4 col-md-6 proje-karti-sarmal"
                         data-durum="<?php echo $p['durum']; ?>"
                         data-kategori="<?php echo htmlspecialchars($p['kategori']); ?>"
                         data-baslik="<?php echo htmlspecialchars(mb_strtolower($p['baslik'])); ?>">
                        <?php
                            // resim_url veritabanında bazen tam bağlantı (https://...), bazen kök dizine
                            // göre kısa yol ("uploads/x.jpg") olarak saklanıyor.
                            $pResimSrc = ($p['resim_url'] !== '' && !preg_match('/^https?:\/\//i', $p['resim_url']))
                                ? '../' . $p['resim_url']
                                : $p['resim_url'];
                        ?>
                        <div class="card duyuru-karti h-100">
                            <img src="<?php echo htmlspecialchars($pResimSrc); ?>"
                                 class="card-img-top" alt="<?php echo htmlspecialchars($p['baslik']); ?>">
                            <div class="card-body d-flex flex-column">
                                <div class="mb-2">
                                    <?php if ($p['durum'] === 'tamamlanmis'): ?>
                                        <span class="badge bg-success">Tamamlanmış</span>
                                    <?php elseif ($p['durum'] === 'devam_eden'): ?>
                                        <span class="badge bg-info text-dark">Devam Eden</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Planlanan</span>
                                    <?php endif; ?>
                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($p['kategori']); ?></span>
                                </div>
                                <h6 class="card-title fw-bold"><?php echo htmlspecialchars($p['baslik']); ?></h6>
                                <p class="card-text text-muted small flex-grow-1"><?php echo htmlspecialchars($p['aciklama']); ?></p>
                                <div class="mt-3">
                                    <a href="proje-detay.php?id=<?php echo (int)$p['id']; ?>" class="btn btn-sm btn-geri-kutu w-100">Detayları İncele</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <p class="text-muted" id="sonucYokMesaji" style="display:none;">Aramanıza uygun proje bulunamadı.</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const aramaKutusu = document.getElementById('projeArama');
    const kategoriButonlari = document.querySelectorAll('.btn-filtre-kategori');
    const durumButonlari = document.querySelectorAll('.btn-filtre-durum');
    const kartlar = document.querySelectorAll('.proje-karti-sarmal');
    const sonucYokMesaji = document.getElementById('sonucYokMesaji');

    let secilenKategori = 'hepsi';
    let secilenDurum = 'hepsi';

    function kartlariFiltrele() {
        const aramaMetni = aramaKutusu.value.trim().toLowerCase();
        let gorunenSayisi = 0;

        kartlar.forEach(function (kart) {
            const kategoriUyuyor = (secilenKategori === 'hepsi' || kart.getAttribute('data-kategori') === secilenKategori);
            const durumUyuyor = (secilenDurum === 'hepsi' || kart.getAttribute('data-durum') === secilenDurum);
            const aramaUyuyor = (aramaMetni === '' || kart.getAttribute('data-baslik').includes(aramaMetni));

            if (kategoriUyuyor && durumUyuyor && aramaUyuyor) {
                kart.style.display = '';
                gorunenSayisi++;
            } else {
                kart.style.display = 'none';
            }
        });

        sonucYokMesaji.style.display = (gorunenSayisi === 0) ? 'block' : 'none';
    }

    kategoriButonlari.forEach(function (buton) {
        buton.addEventListener('click', function () {
            kategoriButonlari.forEach(b => b.classList.remove('active'));
            buton.classList.add('active');
            secilenKategori = buton.getAttribute('data-kategori');

            // "Tüm Kategoriler" seçilince durum filtresi de sıfırlansın, gerçekten hepsi görünsün
            if (secilenKategori === 'hepsi') {
                secilenDurum = 'hepsi';
                durumButonlari.forEach(b => b.classList.remove('active'));
                document.querySelector('.proje-tum-durum').classList.add('active');
            }

            kartlariFiltrele();
        });
    });

    durumButonlari.forEach(function (buton) {
        buton.addEventListener('click', function () {
            durumButonlari.forEach(b => b.classList.remove('active'));
            buton.classList.add('active');
            secilenDurum = buton.getAttribute('data-durum');

            // "Hepsi" seçilince kategori filtresi de sıfırlansın, gerçekten hepsi görünsün
            if (secilenDurum === 'hepsi') {
                secilenKategori = 'hepsi';
                kategoriButonlari.forEach(b => b.classList.remove('active'));
                document.querySelector('.proje-tum-kategori').classList.add('active');
            }

            kartlariFiltrele();
        });
    });

    aramaKutusu.addEventListener('input', kartlariFiltrele);
});
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>
