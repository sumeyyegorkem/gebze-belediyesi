<?php
// 500 - Beklenmedik Hata. .htaccess içindeki "ErrorDocument 500" satırı
// ile buraya gelinir. Admin panelinden ("Hata Sayfaları") özel bir mesaj
// girilmişse config/hata-mesaji-500.txt dosyasından okunur.
http_response_code(500);

$kok = '/gebze-belediyesi';
$mesajDosyasi = '../config/hata-mesaji-500.txt';
$ozelMesaj = is_file($mesajDosyasi) ? trim(file_get_contents($mesajDosyasi)) : '';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beklenmedik Bir Hata Oluştu | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="<?php echo $kok; ?>/css/style.css?v=65" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<div class="container py-5 text-center" style="min-height:100vh; display:flex; flex-direction:column; justify-content:center;">
    <div class="hizmet-ikon mx-auto mb-4" style="width:110px; height:110px; font-size:3rem;">
        <i class="bi bi-tools"></i>
    </div>
    <h1 class="fw-bold" style="font-size:5rem; color: var(--lacivert-koyu);">500</h1>
    <h4 class="fw-bold mb-3">Beklenmedik Bir Hata Oluştu</h4>
    <p class="text-muted mb-4">
        <?php if ($ozelMesaj !== ''): ?>
            <?php echo nl2br(htmlspecialchars($ozelMesaj)); ?>
        <?php else: ?>
            Sayfayı açmaya çalışırken sistemde beklenmedik bir sorun meydana geldi. Bu durum kayıt altına alındı, lütfen daha sonra tekrar deneyin.
        <?php endif; ?>
    </p>
    <div>
        <a href="<?php echo $kok; ?>/index.php" class="btn btn-belediye me-2"><i class="bi bi-house-door-fill me-1"></i> Ana Sayfaya Dön</a>
        <a href="<?php echo $kok; ?>/genel/iletisim.php" class="btn btn-outline-secondary"><i class="bi bi-chat-left-text-fill me-1"></i> Bize Ulaşın</a>
    </div>
</div>

</body>
</html>
