<?php require_once '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Başkan Yardımcıları | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=67" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-person-check-fill me-2"></i>Başkan Yardımcıları</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            <?php $aktifKurumsal = 'baskan-yardimcilari'; include '../includes/kurumsal-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Başkan Yardımcıları'; include '../includes/kurumsal-icerik-ust.php'; ?>
            <?php
            // Veritabani: baskan_yardimcilari + mudurlukler tablolari (admin/baskan-yardimcilari-yonet.php ve admin/mudurlukler-yonet.php uzerinden yonetilir)
            $yardimcilarDb = $pdo->query("SELECT * FROM baskan_yardimcilari WHERE aktif = 1 ORDER BY sira ASC")->fetchAll();
            $yardimcilar = [];
            foreach ($yardimcilarDb as $yy) {
                $mStmt = $pdo->prepare("SELECT ad, mudur FROM mudurlukler WHERE baskan_yardimcisi_id = :id AND aktif = 1 ORDER BY sira ASC");
                $mStmt->execute(['id' => $yy['id']]);
                $mudurlukleri = [];
                foreach ($mStmt->fetchAll() as $mm) {
                    $mudurlukleri[] = [$mm['ad'], $mm['mudur']];
                }
                // foto veritabanında bazen tam bağlantı (https://...) bazen de kök dizine
                // göre kısa yol ("img/x.jpg") olarak saklanıyor; bu sayfa artık kurumsal/
                // klasöründe olduğu için kısa yolların başına "../" ekleniyor. Bu değer hem
                // karttaki <img> hem de aşağıdaki JS modalinde kullanıldığı için tek yerde düzeltiliyor.
                $yyFotoSrc = preg_match('/^https?:\/\//i', $yy['foto']) ? $yy['foto'] : '../' . $yy['foto'];
                $yardimcilar[] = ['ad' => $yy['ad'], 'foto' => $yyFotoSrc, 'slug' => $yy['slug'], 'mudurlukler' => $mudurlukleri];
            }
            ?>
            <div class="row g-4">
                <?php foreach ($yardimcilar as $index => $y): ?>
                <div class="col-md-4 col-sm-6">
                    <div class="card duyuru-karti h-100 text-center border-0" style="cursor:pointer;" onclick="yardimciModalAc(<?php echo $index; ?>)">
                        <img src="<?php echo htmlspecialchars($y['foto']); ?>" alt="<?php echo htmlspecialchars($y['ad']); ?>"
                             class="card-img-top" style="height:280px;object-fit:cover;object-position:top;"
                             onerror="this.src='https://placehold.co/300x280?text=%20';">
                        <div class="card-body py-3">
                            <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($y['ad']); ?></h6>
                            <small class="text-muted">Başkan Yardımcısı</small>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Başkan Yardımcısı Detay Modalı -->
            <div class="modal fade" id="yardimciModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;">
                        <div class="modal-header border-0 pb-0">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                        </div>
                        <div class="modal-body px-4 pb-4 pt-0">
                            <div class="d-flex flex-column align-items-center text-center mb-3">
                                <img id="yardimciModalFoto" src="" alt="" class="rounded-circle mb-3" style="width:140px;height:140px;object-fit:cover;box-shadow:0 3px 12px rgba(11,61,98,.15);">
                                <h5 class="fw-bold mb-1" id="yardimciModalAd"></h5>
                                <small class="text-muted">Başkan Yardımcısı</small>
                            </div>

                            <ul class="nav nav-pills gap-2 mb-3" id="yardimciModalSekme">
                                <li class="nav-item">
                                    <button type="button" class="nav-link active" data-hedef="biyografi" onclick="yardimciSekmeDegistir('biyografi')">
                                        <i class="bi bi-person-lines-fill me-1"></i>Biyografi
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" data-hedef="birimler" onclick="yardimciSekmeDegistir('birimler')">
                                        <i class="bi bi-diagram-3-fill me-1"></i>Bağlı Birimler
                                    </button>
                                </li>
                            </ul>

                            <div id="yardimciSekmeBiyografi">
                            </div>

                            <div id="yardimciSekmeBirimler" style="display:none;">
                                <div class="row g-2" id="yardimciModalBirimler"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
            const yardimciVerisi = <?php echo json_encode(array_values($yardimcilar), JSON_UNESCAPED_UNICODE); ?>;

            function yardimciModalAc(i) {
                const y = yardimciVerisi[i];
                document.getElementById('yardimciModalFoto').src = y.foto;
                document.getElementById('yardimciModalFoto').alt = y.ad;
                document.getElementById('yardimciModalAd').textContent = y.ad;

                const birimlerEl = document.getElementById('yardimciModalBirimler');
                birimlerEl.innerHTML = '';
                y.mudurlukler.forEach(function (m) {
                    birimlerEl.insertAdjacentHTML('beforeend',
                        '<div class="col-md-6"><div class="hizmet-kutu text-start h-100" style="padding:16px;">' +
                        '<h6 class="fw-bold mb-1" style="font-size:.92rem;">' + m[0] + '</h6>' +
                        '<small class="text-muted">' + m[1] + '</small></div></div>');
                });

                yardimciSekmeDegistir('biyografi');
                new bootstrap.Modal(document.getElementById('yardimciModal')).show();
            }

            function yardimciSekmeDegistir(hedef) {
                document.getElementById('yardimciSekmeBiyografi').style.display = (hedef === 'biyografi') ? '' : 'none';
                document.getElementById('yardimciSekmeBirimler').style.display = (hedef === 'birimler') ? '' : 'none';
                document.querySelectorAll('#yardimciModalSekme .nav-link').forEach(function (b) {
                    b.classList.toggle('active', b.getAttribute('data-hedef') === hedef);
                });
            }
            </script>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
