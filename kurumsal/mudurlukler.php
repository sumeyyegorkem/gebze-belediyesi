<?php require_once '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Müdürlükler | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=67" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
    <style>
        /* Bu stil SADECE bu sayfada geçerli, siteyi etkilemez */
        .page-item.active .page-link { background-color: var(--lacivert); border-color: var(--lacivert); }
        .page-link { color: var(--lacivert); }
        .page-link:hover { color: var(--lacivert-koyu); }
        .page-link:focus { box-shadow: none; }
    </style>
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-buildings me-2"></i>Müdürlükler</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            <?php $aktifKurumsal = 'mudurlukler'; include '../includes/kurumsal-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Müdürlükler'; include '../includes/kurumsal-icerik-ust.php'; ?>

            <!-- Arama kutusu -->
            <div class="mb-4" style="max-width:400px;">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" id="mudurlukArama" class="form-control border-start-0" placeholder="Müdürlük ya da isim ara...">
                </div>
            </div>

            <div class="row g-3" id="mudurlukListesi">
                <?php
                // Veritabani: mudurlukler tablosu (admin/mudurlukler-yonet.php uzerinden yonetilir)
                $mudurlukler = $pdo->query("SELECT * FROM mudurlukler WHERE aktif = 1 ORDER BY sira ASC")->fetchAll();

                function mudurlukMetniHtml($metin) {
                    if (empty($metin)) return '';
                    $paragraflar = preg_split('/\n\s*\n/', trim($metin));
                    $html = '';
                    foreach ($paragraflar as $p) {
                        $html .= '<p class="metin-govde text-muted mb-3">' . nl2br(htmlspecialchars(trim($p))) . '</p>';
                    }
                    return $html;
                }
                $mudurlukVerisiJs = array_map(function ($m) {
                    $m['biyografiHtml'] = mudurlukMetniHtml($m['biyografi'] ?? '');
                    $m['yonetmelikHtml'] = mudurlukMetniHtml($m['yonetmelik'] ?? '');
                    return $m;
                }, $mudurlukler);

                $mudurlukSayfaBoyutu = 9;
                foreach ($mudurlukler as $i => $m):
                    $aramaMetni = mb_strtolower($m['ad'] . ' ' . $m['mudur'], 'UTF-8');
                ?>
                <div class="col-md-4 col-sm-6 mudurluk-karti" data-arama="<?php echo htmlspecialchars($aramaMetni); ?>" data-sayfa="<?php echo intdiv($i, $mudurlukSayfaBoyutu) + 1; ?>">
                    <button type="button" class="hizmet-kutu text-start h-100 d-flex flex-column w-100 border-0 mudurluk-tetik" style="padding:24px 20px;" data-index="<?php echo $i; ?>">
                        <h5 class="fw-bold mb-2" style="font-size:1.1rem;"><?php echo htmlspecialchars($m['ad']); ?></h5>
                        <span class="text-muted mb-2" style="font-size:1rem;"><?php echo htmlspecialchars($m['mudur']); ?></span>
                        <?php if (!empty($m['eposta'])): ?>
                            <span class="text-muted mb-2" style="font-size:.95rem;">
                                <i class="bi bi-envelope-fill me-1" style="color:var(--altin);"></i><?php echo htmlspecialchars($m['eposta']); ?>
                            </span>
                        <?php endif; ?>
                    </button>
                </div>
                <?php endforeach; ?>

                <p class="text-muted" id="mudurlukSonucYok" style="display:none;">Aramanıza uygun müdürlük bulunamadı.</p>
            </div>

            <nav class="mt-4">
                <ul class="pagination justify-content-center" id="mudurlukSayfalama"></ul>
            </nav>

            <!-- Müdürlük Detay Modalı -->
            <div class="modal fade" id="mudurlukModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content" style="border:none;border-radius:16px;">
                        <div class="modal-header border-0 pb-0">
                            <h4 class="fw-bold mb-0" id="mudurlukDetayBaslik"></h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                        </div>
                        <div class="modal-body pt-2">
                            <div class="d-flex flex-column flex-sm-row gap-3 align-items-start mb-4">
                                <img id="mudurlukDetayFoto" src="" alt="" class="rounded-3" style="width:130px;height:160px;object-fit:cover;object-position:top;flex-shrink:0;display:none;" onerror="this.style.display='none';">
                                <div>
                                    <h5 class="fw-bold mb-2" id="mudurlukDetayMudur"></h5>
                                    <p class="mb-1" id="mudurlukDetayTelefonSatir" style="display:none;"><i class="bi bi-telephone-fill me-2" style="color:var(--altin);"></i><span id="mudurlukDetayTelefon"></span></p>
                                    <p class="mb-1" id="mudurlukDetayEpostaSatir" style="display:none;"><i class="bi bi-envelope-fill me-2" style="color:var(--altin);"></i><span id="mudurlukDetayEposta"></span></p>
                                    <p class="mb-0" id="mudurlukDetayAdresSatir" style="display:none;"><i class="bi bi-geo-alt-fill me-2" style="color:var(--altin);"></i><span id="mudurlukDetayAdres"></span></p>
                                </div>
                            </div>

                            <ul class="nav nav-pills gap-2 mb-3" id="mudurlukModalSekme">
                                <li class="nav-item">
                                    <button type="button" class="nav-link active" data-hedef="biyografi" onclick="mudurlukSekmeDegistir('biyografi')">
                                        <i class="bi bi-person-lines-fill me-1"></i>Müdür Biyografi
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" data-hedef="yonetmelik" onclick="mudurlukSekmeDegistir('yonetmelik')">
                                        <i class="bi bi-journal-text me-1"></i>Müdürlük Yönetmelik
                                    </button>
                                </li>
                            </ul>

                            <div id="mudurlukSekmeBiyografi">
                                <div id="mudurlukBiyografiIcerik"></div>
                                <p class="text-muted small fst-italic mb-0" id="mudurlukBiyografiBos" style="display:none;">
                                    Bu müdürün biyografi bilgisi henüz eklenmedi, yakında eklenecek.
                                </p>
                            </div>
                            <div id="mudurlukSekmeYonetmelik" style="display:none;">
                                <div id="mudurlukYonetmelikIcerik" style="max-height:340px;overflow-y:auto;"></div>
                                <p class="text-muted small fst-italic mb-0" id="mudurlukYonetmelikBos" style="display:none;">
                                    Bu müdürlüğün yönetmelik metni henüz eklenmedi, yakında eklenecek.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <script>
            const mudurlukVerisi = <?php echo json_encode($mudurlukVerisiJs, JSON_UNESCAPED_UNICODE); ?>;

            function mudurlukSekmeDegistir(hedef) {
                document.getElementById('mudurlukSekmeBiyografi').style.display = (hedef === 'biyografi') ? '' : 'none';
                document.getElementById('mudurlukSekmeYonetmelik').style.display = (hedef === 'yonetmelik') ? '' : 'none';
                document.querySelectorAll('#mudurlukModalSekme .nav-link').forEach(function (b) {
                    b.classList.toggle('active', b.getAttribute('data-hedef') === hedef);
                });
            }

            document.addEventListener('DOMContentLoaded', function () {
                const modal = new bootstrap.Modal(document.getElementById('mudurlukModal'));
                document.querySelectorAll('.mudurluk-tetik').forEach(function (buton) {
                    buton.addEventListener('click', function () {
                        const veri = mudurlukVerisi[this.getAttribute('data-index')];
                        document.getElementById('mudurlukDetayBaslik').textContent = veri.ad;
                        document.getElementById('mudurlukDetayMudur').textContent = veri.mudur;

                        const foto = document.getElementById('mudurlukDetayFoto');
                        if (veri.foto) {
                            foto.src = veri.foto;
                            foto.alt = veri.mudur;
                            foto.style.display = '';
                        } else {
                            foto.style.display = 'none';
                        }

                        document.getElementById('mudurlukDetayTelefonSatir').style.display = veri.telefon ? '' : 'none';
                        document.getElementById('mudurlukDetayTelefon').textContent = veri.telefon || '';
                        document.getElementById('mudurlukDetayEpostaSatir').style.display = veri.eposta ? '' : 'none';
                        document.getElementById('mudurlukDetayEposta').textContent = veri.eposta || '';
                        document.getElementById('mudurlukDetayAdresSatir').style.display = veri.adres ? '' : 'none';
                        document.getElementById('mudurlukDetayAdres').textContent = veri.adres || '';

                        const biyoIcerik = document.getElementById('mudurlukBiyografiIcerik');
                        const biyoBos = document.getElementById('mudurlukBiyografiBos');
                        if (veri.biyografiHtml) {
                            biyoIcerik.innerHTML = veri.biyografiHtml;
                            biyoIcerik.style.display = '';
                            biyoBos.style.display = 'none';
                        } else {
                            biyoIcerik.style.display = 'none';
                            biyoBos.style.display = 'block';
                        }

                        const yonIcerik = document.getElementById('mudurlukYonetmelikIcerik');
                        const yonBos = document.getElementById('mudurlukYonetmelikBos');
                        if (veri.yonetmelikHtml) {
                            yonIcerik.innerHTML = veri.yonetmelikHtml;
                            yonIcerik.style.display = '';
                            yonBos.style.display = 'none';
                        } else {
                            yonIcerik.style.display = 'none';
                            yonBos.style.display = 'block';
                        }

                        mudurlukSekmeDegistir('biyografi');
                        modal.show();
                    });
                });
            });
            </script>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const arama = document.getElementById('mudurlukArama');
    const kartlar = document.querySelectorAll('.mudurluk-karti');
    const sonucYok = document.getElementById('mudurlukSonucYok');
    const sayfalamaEl = document.getElementById('mudurlukSayfalama');
    let toplamSayfa = 1;
    kartlar.forEach(function (kart) {
        toplamSayfa = Math.max(toplamSayfa, parseInt(kart.getAttribute('data-sayfa'), 10));
    });
    let aktifSayfa = 1;

    function sayfalamaCiz() {
        sayfalamaEl.innerHTML = '';
        for (let s = 1; s <= toplamSayfa; s++) {
            const li = document.createElement('li');
            li.className = 'page-item' + (s === aktifSayfa ? ' active' : '');
            const a = document.createElement('a');
            a.className = 'page-link rounded-circle mx-1';
            a.href = '#';
            a.textContent = s;
            a.addEventListener('click', function (e) {
                e.preventDefault();
                aktifSayfa = s;
                sayfayiGoster();
                sayfalamaCiz();
            });
            li.appendChild(a);
            sayfalamaEl.appendChild(li);
        }
    }

    function sayfayiGoster() {
        kartlar.forEach(function (kart) {
            kart.style.display = (parseInt(kart.getAttribute('data-sayfa'), 10) === aktifSayfa) ? '' : 'none';
        });
    }

    sayfalamaCiz();
    sayfayiGoster();

    arama.addEventListener('input', function () {
        const metin = this.value.trim().toLowerCase();

        if (metin === '') {
            sayfalamaEl.parentElement.style.display = '';
            aktifSayfa = 1;
            sayfalamaCiz();
            sayfayiGoster();
            sonucYok.style.display = 'none';
            return;
        }

        sayfalamaEl.parentElement.style.display = 'none';
        let gorunen = 0;
        kartlar.forEach(function (kart) {
            if (kart.getAttribute('data-arama').includes(metin)) {
                kart.style.display = '';
                gorunen++;
            } else {
                kart.style.display = 'none';
            }
        });
        sonucYok.style.display = (gorunen === 0) ? 'block' : 'none';
    });
});
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>
