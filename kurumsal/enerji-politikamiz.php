<?php require_once '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enerji Politikamız | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=67" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-lightning-charge-fill me-2"></i>Enerji Politikamız</h1>
    </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            <?php $aktifKurumsal = 'enerji'; include '../includes/kurumsal-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'Enerji Politikamız'; include '../includes/kurumsal-icerik-ust.php'; ?>
            <p class="metin-govde">
                Belediye Kanunu ile tayin edilen hizmetlerimizi; ulusal kanun ve yönetmeliklere,
                bağlı bulunduğumuz mevzuat hükümlerine ve Enerji Yönetim Sistemi (EnYS) şartlarına
                bağlı kalarak, hizmetlerimizin sürdürülebilirliğini esas alarak yürütmekteyiz.
                Bu doğrultuda;
            </p>

            <ul class="metin-govde">
                <li class="mb-2">Enerji ve doğal kaynaklarımızı stratejik bir bakış açısıyla ele alarak verimli kullanmayı,</li>
                <li class="mb-2">Enerji Yönetim Sistemi'ni; ilgili standartlar, uygulanabilir yasal şartlar ve diğer gereklilikler doğrultusunda etkin şekilde yönetmeyi,</li>
                <li class="mb-2">Kaynaklarımızı etkin ve verimli bir şekilde kullanmayı,</li>
                <li class="mb-2">Enerji verimliliğini artırmak için gerekli olan süreç ve sistemleri oluşturarak, bu süreçleri gelişmiş teknolojilerle uygulamayı ve sürdürülebilirliği sağlamayı,</li>
                <li class="mb-2">İklim değişikliğiyle mücadeleye olumlu katkı sağlayacak enerji verimliliği projeleri geliştirerek uygulamayı,</li>
                <li class="mb-2">Tüm personelin EnYS süreçlerine katılımını sağlamayı, ekip çalışmasını güçlendirmeyi ve enerji verimliliği farkındalığını artırmayı,</li>
                <li class="mb-2">EnYS hedeflerini belirlemeyi, bu hedeflerin gerçekleşmesi için gerekli kaynakları sağlamayı ve sistemi sürekli gözden geçirerek iyileştirmeyi,</li>
                <li class="mb-2">Enerji performansını sürekli artırmak amacıyla, belirlenen amaç ve hedeflere ulaşmak için gerekli tüm bilgi ve kaynağı temin ederek; tedarik ve tasarım süreçlerinde enerji verimliliğini ön planda tutmayı,</li>
                <li class="mb-2">Vatandaşlarımız için faaliyetlerimiz çerçevesinde verimlilik artırıcı projeler tasarlamayı, enerji bakımından verimli ürün ve hizmetlerin tedarik edilmesi hususunda teşvik etmeyi, enerji verimliliği farkındalığını geliştirmek için bilgilendirmeyi ve desteklemeyi, enerji verimliliğimizi sürekli iyileştirmeyi taahhüt ederiz.</li>
            </ul>
            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
