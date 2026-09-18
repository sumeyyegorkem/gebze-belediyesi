<?php
// 503 - Hizmet Şu Anda Verilemiyor. .htaccess içindeki "ErrorDocument 503"
// satırı ile buraya gelinir. Admin panelinden ("Hata Sayfaları") özel bir
// mesaj girilmişse config/hata-mesaji-503.txt dosyasından okunur.
http_response_code(503);
header('Retry-After: 300');

$kok = '/gebze-belediyesi';
$mesajDosyasi = '../config/hata-mesaji-503.txt';
$ozelMesaj = is_file($mesajDosyasi) ? trim(file_get_contents($mesajDosyasi)) : '';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hizmet Şu Anda Verilemiyor | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="<?php echo $kok; ?>/css/style.css?v=65" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<div class="container py-5 text-center" style="min-height:100vh; display:flex; flex-direction:column; justify-content:center;">
    <div class="hizmet-ikon mx-auto mb-4" style="width:110px; height:110px; font-size:3rem;">
        <i class="bi bi-exclamation-octagon-fill"></i>
    </div>
    <h1 class="fw-bold" style="font-size:5rem; color: var(--lacivert-koyu);">503</h1>
    <h4 class="fw-bold mb-3">Hizmet Şu Anda Verilemiyor</h4>
    <p class="text-muted mb-4">
        <?php if ($ozelMesaj !== ''): ?>
            <?php echo nl2br(htmlspecialchars($ozelMesaj)); ?>
        <?php else: ?>
            Sunucumuzda geçici bir yoğunluk ya da teknik bir sorun yaşanıyor. Lütfen birkaç dakika sonra tekrar deneyin.
        <?php endif; ?>
    </p>
    <div>
        <a href="<?php echo $kok; ?>/index.php" class="btn btn-belediye me-2"><i class="bi bi-arrow-clockwise me-1"></i> Tekrar Dene</a>
        <a href="<?php echo $kok; ?>/genel/iletisim.php" class="btn btn-outline-secondary"><i class="bi bi-chat-left-text-fill me-1"></i> Bize Ulaşın</a>
    </div>
</div>

</body>
</html>
