<?php
// Bakım Modu ekranı. config/bakim.lock dosyası varken .htaccess'teki
// RewriteRule tüm ziyaretçileri (admin paneli ve statik dosyalar hariç)
// buraya yönlendirir. Veritabanına ulaşılamasa bile bu sayfa sorunsuz
// açılmalı, bu yüzden iletişim bilgisi try/catch içindedir.
http_response_code(503);
header('Retry-After: 3600');

$kok = '/gebze-belediyesi';
$bakimKilitYolu = '../config/bakim.lock';
$ozelMesaj = is_file($bakimKilitYolu) ? trim(file_get_contents($bakimKilitYolu)) : '';

$telefon = '';
$eposta = '';
try {
    require_once '../config/db.php';
    $ayar = $pdo->query("SELECT telefon, eposta FROM site_ayarlari WHERE id = 1")->fetch();
    if ($ayar) {
        $telefon = $ayar['telefon'];
        $eposta = $ayar['eposta'];
    }
} catch (Throwable $e) {
    // Veritabanı erişilemez durumda olsa da bakım sayfası gösterilmeye devam eder.
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakım Çalışması | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="<?php echo $kok; ?>/css/style.css?v=65" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<div class="container py-5 text-center" style="min-height:100vh; display:flex; flex-direction:column; justify-content:center;">
    <div class="hizmet-ikon mx-auto mb-4" style="width:110px; height:110px; font-size:3rem;">
        <i class="bi bi-cone-striped"></i>
    </div>
    <h1 class="fw-bold mb-2" style="color: var(--lacivert-koyu);">Bakım Çalışması</h1>
    <p class="text-muted mb-4" style="font-size:1.05rem; max-width:560px; margin-left:auto; margin-right:auto;">
        <?php if ($ozelMesaj !== ''): ?>
            <?php echo nl2br(htmlspecialchars($ozelMesaj)); ?>
        <?php else: ?>
            Sitemizde kısa süreli bir bakım çalışması yapılıyor.
            Anlayışınız için teşekkür ederiz, en kısa sürede geri döneceğiz.
        <?php endif; ?>
    </p>
    <?php if ($telefon !== '' || $eposta !== ''): ?>
    <p class="text-muted small">
        Acil durumlar için bize ulaşabilirsiniz:
        <?php if ($telefon !== ''): ?><strong><?php echo htmlspecialchars($telefon); ?></strong><?php endif; ?>
        <?php if ($telefon !== '' && $eposta !== ''): ?> &middot; <?php endif; ?>
        <?php if ($eposta !== ''): ?><strong><?php echo htmlspecialchars($eposta); ?></strong><?php endif; ?>
    </p>
    <?php endif; ?>
</div>

</body>
</html>
