<?php
require_once '../config/db.php';
$etikUyeleri = $pdo->query("SELECT ad_soyad, unvan, gorev FROM etik_komisyonu_uyeleri WHERE aktif = 1 ORDER BY sira ASC, id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Etik Komisyonu | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=67" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-shield-fill-check me-2"></i>Etik Komisyonu</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            <?php $aktifKurumsal = 'etik'; include '../includes/kurumsal-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Etik Komisyonu'; include '../includes/kurumsal-icerik-ust.php'; ?>

            <!-- Etik Davranış İlkeleri -->
            <h2 class="bolum-baslik">Etik Davranış İlkeleri</h2>
            <div class="row g-3 mb-5">
                <?php
                $ilkeler = [
                    'Halka Hizmet Bilinci', 'Hizmet Standartlarına Uymak', 'Amaç ve Misyona Bağlılık',
                    'Dürüstlük ve Tarafsızlık', 'Saygınlık ve Güven', 'Nezaket ve Saygı',
                    'Ayrımcılık Yapmamak', 'Saydamlık ve Katılımcılık', 'Hediye Almamak',
                    'Kamu Mallarını Korumak', 'Savurganlıktan Kaçınmak', 'Çıkar Çatışmasından Kaçınmak',
                    'Hesap Verme Sorumluluğu', 'İmtiyazsız Kamu Hizmeti', 'Doğruluk',
                ];
                foreach ($ilkeler as $ilke):
                ?>
                <div class="col-md-4 col-sm-6">
                    <div class="d-flex align-items-center p-3 rounded-3 h-100" style="background:var(--acik-gri);">
                        <i class="bi bi-check-circle-fill fs-5 me-3" style="color:var(--altin);"></i>
                        <span class="fw-semibold small"><?php echo htmlspecialchars($ilke); ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Etik Komisyonu Listesi -->
            <h2 class="bolum-baslik">Etik Komisyonu Listesi</h2>
            <div class="table-responsive">
                <table class="table table-hover align-middle bg-white rounded-3 overflow-hidden shadow-sm">
                    <thead class="table-light">
                        <tr>
                            <th>Adı Soyadı</th>
                            <th>Ünvanı</th>
                            <th>Görevi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($etikUyeleri) === 0): ?>
                            <tr><td colspan="3" class="text-center text-muted">Henüz üye eklenmemiş.</td></tr>
                        <?php endif; ?>
                        <?php foreach ($etikUyeleri as $u): ?>
                        <tr>
                            <td class="fw-semibold"><?php echo htmlspecialchars($u['ad_soyad']); ?></td>
                            <td><?php echo htmlspecialchars($u['unvan']); ?></td>
                            <td><?php echo htmlspecialchars($u['gorev']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
