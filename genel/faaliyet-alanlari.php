<?php require_once '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faaliyet Alanları | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=72" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
    <style>
        /* Bu stil SADECE bu sayfada geçerli, siteyi etkilemez */
        .faaliyet-arama-sonuc { position:absolute; top:100%; left:0; right:0; background:#fff; border:1px solid var(--acik-gri); border-radius:12px; box-shadow:0 8px 24px rgba(11,61,98,.12); max-height:320px; overflow-y:auto; z-index:20; }
        .faaliyet-arama-sonuc a { display:block; padding:.6rem 1rem; color:var(--lacivert-koyu); text-decoration:none; border-bottom:1px solid var(--acik-gri); }
        .faaliyet-arama-sonuc a:last-child { border-bottom:0; }
        .faaliyet-arama-sonuc a:hover { background:var(--acik-gri); }
        .faaliyet-arama-sonuc a small { display:block; color:#888; font-weight:400; }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-grid-3x3-gap-fill me-2"></i>Faaliyet Alanları</h1>
    </div>
</header>

<div class="container py-5">
    <?php
    // Faaliyet Alanları kategorileri ve ögeleri artık veritabanından geliyor
    // (admin panelinden "Faaliyet Alanları" bölümünden ekleyip çıkarabilirsiniz).
    $faaliyetKategorileri = [];
    $katListesi = $pdo->query("SELECT * FROM faaliyet_kategorileri WHERE aktif = 1 ORDER BY sira ASC, id ASC")->fetchAll();
    foreach ($katListesi as $kat) {
        $faaliyetKategorileri[$kat['anahtar']] = [
            'label' => $kat['baslik'],
            'icon' => $kat['ikon'],
            'ogeler' => [],
        ];
    }
    $ogeListesi = $pdo->query(
        "SELECT o.*, k.anahtar AS kategori_anahtar FROM faaliyet_ogeleri o
         JOIN faaliyet_kategorileri k ON k.id = o.kategori_id
         WHERE o.aktif = 1
         ORDER BY o.sira ASC, o.id ASC"
    )->fetchAll();
    foreach ($ogeListesi as $oge) {
        // "Nikah İşlemleri" gibi site-içi bağlantılar veritabanında "hizmetler.php?..." olarak
        // saklanmış olabilir; hizmetler.php artık hizmetler/ klasörünün içinde olduğu için
        // görüntüleme anında önek ekleniyor. Bu sayfa da artık genel/ klasöründe olduğu için
        // (bir seviye daha derin), tüm site-içi göreli bağlantılara ve görsellere "../" ekleniyor
        // (dış (http/https) bağlantılar ve görseller olduğu gibi bırakılır).
        $ogeHref = $oge['href'];
        if ($ogeHref !== null && $ogeHref !== '' && !preg_match('#^https?://#i', $ogeHref)) {
            if (strpos($ogeHref, 'hizmetler.php') === 0) {
                $ogeHref = 'hizmetler/' . $ogeHref;
            }
            $ogeHref = '../' . $ogeHref;
        }
        $ogeImg = $oge['img'];
        if ($ogeImg !== null && $ogeImg !== '' && !preg_match('#^https?://#i', $ogeImg)) {
            $ogeImg = '../' . $ogeImg;
        }
        $gbFaaliyetOge = [
            'baslik' => $oge['baslik'],
            'href' => $ogeHref,
            'img' => $ogeImg,
        ];
        if ($oge['detay'] !== null && $oge['detay'] !== '') {
            $gbFaaliyetOge['detay'] = $oge['detay'];
        }
        if ((int)$oge['ic_baglanti'] === 1) {
            $gbFaaliyetOge['ic'] = true;
        }
        if (isset($faaliyetKategorileri[$oge['kategori_anahtar']])) {
            $faaliyetKategorileri[$oge['kategori_anahtar']]['ogeler'][] = $gbFaaliyetOge;
        }
    }

    // Arama kutusu ve detay kutucuğu (modal) için tüm ögeleri tek bir listede birleştiriyoruz
    $tumOgeler = [];
    foreach ($faaliyetKategorileri as $kKey => $kVeri) {
        foreach ($kVeri['ogeler'] as $oge) {
            $tumOgeler[] = [
                'baslik' => $oge['baslik'],
                'href' => $oge['href'],
                'kategori' => $kVeri['label'],
                'img' => $oge['img'] ?? null,
                'detay' => $oge['detay'] ?? null,
                'ic' => !empty($oge['ic']),
            ];
        }
    }
    $ilkKategori = array_key_first($faaliyetKategorileri);
    ?>

    <div class="row">
        <div class="col-lg-3 mb-4 mb-lg-0">
            <div class="bg-white rounded-3 p-3" style="box-shadow:0 3px 12px rgba(11,61,98,0.07);">
                <h6 class="fw-bold text-uppercase small text-muted bolum-baslik mb-3" style="letter-spacing:.5px;padding-bottom:10px;">Kategoriler</h6>
                <ul class="nav nav-pills flex-column gap-2 hizmet-menu-liste faaliyet-menu-liste" id="faaliyetKategoriListesi">
                    <?php foreach ($faaliyetKategorileri as $kKey => $kVeri): ?>
                    <li class="nav-item">
                        <button type="button" class="nav-link w-100 text-start faaliyet-kategori-btn <?php echo $kKey === $ilkKategori ? 'active' : ''; ?>" data-kategori="<?php echo $kKey; ?>">
                            <span class="hizmet-kutu"><i class="bi <?php echo $kVeri['icon']; ?>"></i></span><?php echo htmlspecialchars($kVeri['label']); ?>
                        </button>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <div class="col-lg-9">
            <!-- Hızlı Erişim -->
            <div class="bg-white rounded-3 p-3 p-md-4 mb-4" style="box-shadow:0 3px 12px rgba(11,61,98,0.07);">
                <h6 class="bolum-baslik mb-3" style="font-size:1rem;padding-bottom:10px;">Hızlı Erişim</h6>
                <div class="position-relative">
                    <div class="input-group input-group-lg">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search" style="color:var(--lacivert);"></i></span>
                        <input type="text" id="faaliyetArama" class="form-control border-start-0 ps-0" placeholder="Hizmet ara veya listeden seç...">
                    </div>
                    <div id="faaliyetAramaSonuc" class="faaliyet-arama-sonuc" style="display:none;"></div>
                </div>
            </div>

            <!-- Yazı Boyutu / Yazdır Araç Çubuğu -->
            <div class="d-flex justify-content-end mb-3">
                <div class="d-flex gap-2 p-2 rounded-pill" style="background:var(--acik-gri);">
                    <button type="button" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center bg-white" style="width:44px;height:44px;box-shadow:0 2px 6px rgba(11,61,98,.12);" title="Yazıyı Küçült" onclick="faaliyetMetinBoyutAyarla('faaliyetKategoriIcerik', -1)"><i class="bi bi-zoom-out" style="color:var(--lacivert);"></i></button>
                    <button type="button" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center bg-white" style="width:44px;height:44px;box-shadow:0 2px 6px rgba(11,61,98,.12);" title="Yazı Boyutunu Sıfırla" onclick="faaliyetMetinBoyutAyarla('faaliyetKategoriIcerik', 0)"><i class="bi bi-arrow-counterclockwise" style="color:var(--lacivert);"></i></button>
                    <button type="button" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center bg-white" style="width:44px;height:44px;box-shadow:0 2px 6px rgba(11,61,98,.12);" title="Yazıyı Büyüt" onclick="faaliyetMetinBoyutAyarla('faaliyetKategoriIcerik', 1)"><i class="bi bi-zoom-in" style="color:var(--lacivert);"></i></button>
                    <button type="button" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center bg-white" style="width:44px;height:44px;box-shadow:0 2px 6px rgba(11,61,98,.12);" title="Yazdır" onclick="window.print()"><i class="bi bi-printer" style="color:var(--lacivert);"></i></button>
                </div>
            </div>

            <!-- Kategori İçerikleri -->
            <div id="faaliyetKategoriIcerik">
                <?php $globalIndex = 0; ?>
                <?php foreach ($faaliyetKategorileri as $kKey => $kVeri): ?>
                <div class="faaliyet-kategori-panel row g-3" data-kategori-panel="<?php echo $kKey; ?>" style="<?php echo $kKey === $ilkKategori ? '' : 'display:none;'; ?>">
                    <?php if (count($kVeri['ogeler']) === 0): ?>
                        <p class="text-muted">Bu kategoride henüz içerik bulunmuyor.</p>
                    <?php else: ?>
                        <?php foreach ($kVeri['ogeler'] as $oge): ?>
                        <div class="col-md-6">
                            <?php if (!empty($oge['ic'])): ?>
                            <a href="<?php echo htmlspecialchars($oge['href']); ?>" class="card duyuru-karti flex-row border-0 p-0 w-100 text-start text-decoration-none" style="height:140px;overflow:hidden;">
                                <img src="<?php echo htmlspecialchars($oge['img']); ?>" style="width:220px;min-width:220px;height:100%;object-fit:cover;"
                                     alt="<?php echo htmlspecialchars($oge['baslik']); ?>"
                                     onerror="this.src='https://placehold.co/400x300?text=Fotoğraf+Yok';">
                                <div class="card-body d-flex flex-column justify-content-center overflow-hidden">
                                    <h6 class="fw-bold mb-2" style="color:var(--lacivert);"><?php echo htmlspecialchars($oge['baslik']); ?></h6>
                                    <span class="small fw-semibold" style="color:var(--altin);">
                                        Detaylı Bilgi <i class="bi bi-arrow-right ms-1"></i>
                                    </span>
                                </div>
                            </a>
                            <?php else: ?>
                            <button type="button" class="card duyuru-karti flex-row faaliyet-tetik border-0 p-0 w-100 text-start" style="height:140px;overflow:hidden;" data-index="<?php echo $globalIndex; ?>">
                                <img src="<?php echo htmlspecialchars($oge['img']); ?>" style="width:220px;min-width:220px;height:100%;object-fit:cover;"
                                     alt="<?php echo htmlspecialchars($oge['baslik']); ?>"
                                     onerror="this.src='https://placehold.co/400x300?text=Fotoğraf+Yok';">
                                <div class="card-body d-flex flex-column justify-content-center overflow-hidden">
                                    <h6 class="fw-bold mb-2"><?php echo htmlspecialchars($oge['baslik']); ?></h6>
                                    <span class="small fw-semibold" style="color:var(--altin);">
                                        Detaylı Bilgi <i class="bi bi-arrow-right ms-1"></i>
                                    </span>
                                </div>
                            </button>
                            <?php endif; ?>
                        </div>
                        <?php $globalIndex++; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Tıklanan faaliyetin detayının göründüğü kutucuk (modal) - görsel üstte, geniş ve büyük -->
    <div class="modal fade" id="faaliyetDetayModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content" style="border:none;border-radius:16px;overflow:hidden;">
                <div style="height:320px;position:relative;" id="faaliyetDetayGorsel"></div>
                <button type="button" class="btn-close position-absolute" data-bs-dismiss="modal" aria-label="Kapat"
                        style="top:14px;right:14px;background-color:#fff;border-radius:50%;padding:10px;opacity:1;z-index:2;"></button>
                <div class="modal-body p-4" style="max-height:55vh;overflow-y:auto;">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                        <div>
                            <span class="fw-bold small text-uppercase" style="color:var(--altin); letter-spacing:.5px;" id="faaliyetDetayKategori"></span>
                            <h4 class="fw-bold mt-1 mb-0" id="faaliyetDetayBaslik"></h4>
                        </div>
                        <div class="d-flex gap-2 p-1 rounded-pill flex-shrink-0" style="background:var(--acik-gri);">
                            <button type="button" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center bg-white" style="width:34px;height:34px;box-shadow:0 2px 6px rgba(11,61,98,.12);" title="Yazıyı Küçült" onclick="faaliyetMetinBoyutAyarla('faaliyetDetayMetin', -1)"><i class="bi bi-zoom-out small" style="color:var(--lacivert);"></i></button>
                            <button type="button" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center bg-white" style="width:34px;height:34px;box-shadow:0 2px 6px rgba(11,61,98,.12);" title="Yazı Boyutunu Sıfırla" onclick="faaliyetMetinBoyutAyarla('faaliyetDetayMetin', 0)"><i class="bi bi-arrow-counterclockwise small" style="color:var(--lacivert);"></i></button>
                            <button type="button" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center bg-white" style="width:34px;height:34px;box-shadow:0 2px 6px rgba(11,61,98,.12);" title="Yazıyı Büyüt" onclick="faaliyetMetinBoyutAyarla('faaliyetDetayMetin', 1)"><i class="bi bi-zoom-in small" style="color:var(--lacivert);"></i></button>
                            <button type="button" class="btn btn-sm rounded-circle d-flex align-items-center justify-content-center bg-white" style="width:34px;height:34px;box-shadow:0 2px 6px rgba(11,61,98,.12);" title="Yazdır" onclick="window.print()"><i class="bi bi-printer small" style="color:var(--lacivert);"></i></button>
                        </div>
                    </div>
                    <p class="metin-govde text-muted mb-0" id="faaliyetDetayMetin" style="white-space:pre-line;"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
    const faaliyetVerisi = <?php echo json_encode($tumOgeler, JSON_UNESCAPED_UNICODE); ?>;
    document.addEventListener('DOMContentLoaded', function () {
        const kategoriBtnlari = document.querySelectorAll('.faaliyet-kategori-btn');
        const paneller = document.querySelectorAll('.faaliyet-kategori-panel');
        const aramaKutusu = document.getElementById('faaliyetArama');
        const aramaSonucKutusu = document.getElementById('faaliyetAramaSonuc');
        const kategoriListesi = document.getElementById('faaliyetKategoriListesi');
        const kategoriIcerik = document.getElementById('faaliyetKategoriIcerik');

        // Faaliyet detay kutucuğu (modal)
        const detayModalEl = document.getElementById('faaliyetDetayModal');
        const detayModal = new bootstrap.Modal(detayModalEl);
        const detayBaslikEl = document.getElementById('faaliyetDetayBaslik');
        const detayMetinEl = document.getElementById('faaliyetDetayMetin');
        const detayKategoriEl = document.getElementById('faaliyetDetayKategori');
        const detayGorselAlani = document.getElementById('faaliyetDetayGorsel');

        // Bir faaliyet ögesinin detayını kutucukta (modal) açar. Hem kart tıklamasında
        // hem de arama sonucu tıklamasında (dış siteye değil, kendi sitemizdeki bu bilgiye
        // yönlendirmek için) kullanılan ortak fonksiyon.
        function faaliyetDetayiAc(index) {
            const veri = faaliyetVerisi[index];
            if (!veri) return;

            detayKategoriEl.textContent = veri.kategori;
            detayBaslikEl.textContent = veri.baslik;
            detayMetinEl.textContent = veri.detay || '';
            detayMetinEl.style.display = veri.detay ? '' : 'none';

            if (veri.img) {
                detayGorselAlani.innerHTML = '<img src="' + veri.img + '" alt="" style="width:100%;height:100%;object-fit:cover;" onerror="this.src=\'https://placehold.co/400x300?text=Foto%C4%9Fraf+Yok\';">';
            } else {
                detayGorselAlani.innerHTML = '<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;' +
                    'background:linear-gradient(135deg, var(--lacivert) 0%, var(--lacivert-koyu) 100%);">' +
                    '<i class="bi bi-image" style="font-size:3.2rem;color:#fff;"></i></div>';
            }

            detayModal.show();
        }

        document.querySelectorAll('.faaliyet-tetik').forEach(function (buton) {
            buton.addEventListener('click', function () {
                faaliyetDetayiAc(this.getAttribute('data-index'));
            });
        });

        kategoriBtnlari.forEach(function (btn) {
            btn.addEventListener('click', function () {
                kategoriBtnlari.forEach(function (b) { b.classList.remove('active'); });
                btn.classList.add('active');
                const secilen = btn.getAttribute('data-kategori');
                paneller.forEach(function (p) {
                    p.style.display = (p.getAttribute('data-kategori-panel') === secilen) ? '' : 'none';
                });
            });
        });

        aramaKutusu.addEventListener('input', function () {
            const metin = this.value.trim().toLocaleLowerCase('tr-TR');
            if (metin === '') {
                aramaSonucKutusu.style.display = 'none';
                kategoriIcerik.style.display = '';
                return;
            }
            kategoriIcerik.style.display = 'none';

            // Not: her ögeye orijinal (faaliyetVerisi içindeki) index'ini ekliyoruz ki
            // "ic" (kendi sitemizde ayrı sayfası olan) olmayan ögelerde arama sonucuna
            // tıklanınca gerçek/dış siteye değil, kart tıklamasıyla aynı şekilde kendi
            // sitemizdeki detay kutucuğuna (modal) yönlendirebilelim.
            const eslesenler = faaliyetVerisi
                .map(function (o, i) { return Object.assign({}, o, { _index: i }); })
                .filter(function (o) {
                    return o.baslik.toLocaleLowerCase('tr-TR').includes(metin);
                });

            if (eslesenler.length === 0) {
                aramaSonucKutusu.innerHTML = '<p class="text-muted small mb-0 p-3">Sonuç bulunamadı.</p>';
            } else {
                aramaSonucKutusu.innerHTML = eslesenler.map(function (o) {
                    if (o.ic) {
                        return '<a href="' + o.href + '">' + o.baslik + '<small>' + o.kategori + '</small></a>';
                    }
                    return '<a href="#" class="faaliyet-arama-detay" data-index="' + o._index + '">' + o.baslik + '<small>' + o.kategori + '</small></a>';
                }).join('');
                aramaSonucKutusu.querySelectorAll('.faaliyet-arama-detay').forEach(function (a) {
                    a.addEventListener('click', function (e) {
                        e.preventDefault();
                        aramaSonucKutusu.style.display = 'none';
                        aramaKutusu.value = '';
                        kategoriIcerik.style.display = '';
                        faaliyetDetayiAc(this.getAttribute('data-index'));
                    });
                });
            }
            aramaSonucKutusu.style.display = 'block';
        });

        document.addEventListener('click', function (e) {
            if (!e.target.closest('#faaliyetArama') && !e.target.closest('#faaliyetAramaSonuc')) {
                aramaSonucKutusu.style.display = 'none';
                if (aramaKutusu.value.trim() === '') {
                    kategoriIcerik.style.display = '';
                }
            }
        });
    });

    // Yazı boyutu küçült/büyüt/sıfırla (hem sayfa içeriği hem faaliyet detay kutucuğu için kullanılır)
    if (typeof faaliyetMetinBoyutAyarla !== 'function') {
        function faaliyetMetinBoyutAyarla(hedefId, yon) {
            const hedef = document.getElementById(hedefId);
            if (!hedef) return;
            let boyut = parseFloat(hedef.dataset.boyut || '1');
            if (yon === 0) {
                boyut = 1;
            } else {
                boyut = Math.min(1.4, Math.max(0.8, boyut + (yon * 0.1)));
            }
            hedef.dataset.boyut = boyut;
            hedef.style.fontSize = boyut + 'em';
        }
    }
    </script>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
