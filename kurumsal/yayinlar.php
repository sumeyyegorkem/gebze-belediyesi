<?php require_once '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yayınlar | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=67" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
    <style>
        /* Bu stil SADECE bu sayfada geçerli, siteyi etkilemez */
        .badge-kategori { background-color: var(--altin) !important; }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-journal-richtext me-2"></i>Gebze Belediyesi Yayınları</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            <?php $aktifKurumsal = 'yayinlar'; include '../includes/kurumsal-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Yayınlar'; include '../includes/kurumsal-icerik-ust.php'; ?>

            <!-- Arama ve Kategori Filtresi -->
            <div class="row g-3 mb-4">
                <div class="col-md-5">
                    <label class="form-label small fw-semibold">Ara</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="yayinArama" class="form-control border-start-0 ps-0" placeholder="Yayın ara...">
                    </div>
                </div>
                <div class="col-md-5">
                    <label class="form-label small fw-semibold">Kategori</label>
                    <select id="kategoriFiltre" class="form-select">
                        <option value="hepsi">Tüm Yayınlar</option>
                        <option value="kultur">Kültür Yayınları</option>
                        <option value="projeler">Gebze Belediyesi Projeleri</option>
                        <option value="manset">Gebze Manşet</option>
                    </select>
                </div>
            </div>

            <div id="yayinListesi">
                <?php
                // Veritabani: yayinlar tablosu (admin/yayinlar-yonet.php üzerinden yönetilir)
                // kategori: kultur | projeler | manset — filtreleme için kullanılır.
                $kategoriEtiket = ['kultur' => 'Kültür Yayınları', 'projeler' => 'Gebze Belediyesi Projeleri', 'manset' => 'Gebze Manşet'];
                $yayinlar = $pdo->query("SELECT * FROM yayinlar WHERE aktif = 1 ORDER BY id DESC")->fetchAll();
                foreach ($yayinlar as $y):
                ?>
                <div class="hizmet-kutu d-flex align-items-center justify-content-between text-start mb-3 yayin-satiri"
                     data-kategori="<?php echo htmlspecialchars($y['kategori']); ?>"
                     data-baslik="<?php echo htmlspecialchars(mb_strtolower($y['baslik'], 'UTF-8')); ?>">
                    <div>
                        <span class="badge-kategori badge rounded-pill mb-1"><?php echo htmlspecialchars($kategoriEtiket[$y['kategori']] ?? $y['kategori']); ?></span>
                        <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($y['baslik']); ?></h6>
                        <small class="text-muted">Yayın Tarihi: <?php echo htmlspecialchars($y['tarih']); ?></small>
                    </div>
                    <?php if ($y['pdf_url']): ?>
                        <a href="<?php echo htmlspecialchars($y['pdf_url']); ?>" target="_blank" class="btn btn-sm btn-lacivert flex-shrink-0">
                            <i class="bi bi-file-earmark-pdf-fill me-1"></i>PDF İndir
                        </a>
                    <?php else: ?>
                        <small class="text-muted flex-shrink-0"><i class="bi bi-dash-circle me-1"></i>Bağlantı paylaşılmadı</small>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
                <p class="text-muted" id="yayinSonucYok" style="display:none;">Aradığınız kritere uygun yayın bulunamadı.</p>
            </div>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const kategoriFiltre = document.getElementById('kategoriFiltre');
    const yayinArama = document.getElementById('yayinArama');
    const satirlar = document.querySelectorAll('.yayin-satiri');
    const sonucYok = document.getElementById('yayinSonucYok');

    function filtrele() {
        const kategori = kategoriFiltre.value;
        const aranan = yayinArama.value.trim().toLocaleLowerCase('tr-TR');
        let gorunen = 0;
        satirlar.forEach(function (satir) {
            const kategoriUyuyor = (kategori === 'hepsi' || satir.getAttribute('data-kategori') === kategori);
            const aramaUyuyor = (aranan === '' || satir.getAttribute('data-baslik').includes(aranan));
            const uyuyor = kategoriUyuyor && aramaUyuyor;
            // Not: satır "d-flex" sınıfına sahip; Bootstrap bu sınıfı !important ile
            // tanımladığından style.display='none' onu gizleyemiyordu. Bunun yerine
            // "d-none" sınıfı ekleyip çıkarıyoruz (o da !important, ve d-flex'ten sonra
            // tanımlı olduğu için üstün geliyor).
            satir.classList.toggle('d-none', !uyuyor);
            if (uyuyor) gorunen++;
        });
        sonucYok.style.display = (gorunen === 0) ? 'block' : 'none';
    }

    kategoriFiltre.addEventListener('change', filtrele);
    yayinArama.addEventListener('input', filtrele);
});
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>
