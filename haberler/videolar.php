<?php
require_once '../config/db.php';

$videolar = $pdo->query("SELECT * FROM videolar WHERE aktif = 1 ORDER BY tarih DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Videolar | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=63" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-play-circle-fill me-2"></i>Videolar</h1>
    </div>
</header>

<div class="container py-5">
    <?php if (count($videolar) === 0): ?>
        <div class="text-center py-5">
            <i class="bi bi-youtube text-danger" style="font-size:3.5rem;"></i>
            <h5 class="fw-bold mt-3">Henüz video eklenmedi</h5>
            <p class="text-muted mb-3">Video arşivimiz için resmi YouTube kanalımızı ziyaret edebilirsiniz.</p>
            <a href="https://www.youtube.com/@gebzebelediyesi7295/videos" target="_blank" class="btn-tumu-pill">
                <i class="bi bi-box-arrow-up-right me-1"></i>YouTube Kanalına Git
            </a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($videolar as $v): ?>
                <div class="col-lg-4 col-md-6">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#videoModal"
                       data-video-id="<?php echo htmlspecialchars($v['youtube_id']); ?>"
                       data-video-baslik="<?php echo htmlspecialchars($v['baslik']); ?>"
                       class="haber-mini-karti text-decoration-none text-reset d-block position-relative">
                        <img src="https://img.youtube.com/vi/<?php echo htmlspecialchars($v['youtube_id']); ?>/hqdefault.jpg" alt="<?php echo htmlspecialchars($v['baslik']); ?>">
                        <div class="video-oynat-ikon"><i class="bi bi-play-fill"></i></div>
                        <small class="text-muted d-block mt-2"><?php echo date('d.m.Y', strtotime($v['tarih'])); ?></small>
                        <h6 class="fw-bold mb-0"><?php echo htmlspecialchars($v['baslik']); ?></h6>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
