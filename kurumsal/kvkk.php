<?php require_once '../config/db.php'; ?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KVKK Aydınlatma Metni | Gebze Belediyesi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="../css/style.css?v=67" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="https://commons.wikimedia.org/wiki/Special:FilePath/Gebze_Belediyesi_logo.svg">
</head>
<body>

<?php include '../includes/navbar.php'; ?>

<header class="hero-alan">
    <div class="container">
        <h1 class="mb-0"><i class="bi bi-shield-lock-fill me-2"></i>KVKK Aydınlatma Metni</h1>
        </div>
</header>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-3">
            <?php $aktifKurumsal = 'kvkk'; include '../includes/kurumsal-sidebar.php'; ?>
        </div>
        <div class="col-lg-9">
            <?php $sayfaBasligi = 'KVKK Aydınlatma Metni'; include '../includes/kurumsal-icerik-ust.php'; ?>

            <h2 class="bolum-baslik text-center mb-4">GENEL BELEDİYE HİZMETLERİNE İLİŞKİN AYDINLATMA METNİ</h2>

            <section class="mb-5">
                <h2 class="bolum-baslik">1. Amaç</h2>
                <p class="metin-govde">
                    Veri sorumlusu olan Güzeller Mahallesi Bahar Cad. No:1 41400 Gebze/Kocaeli adresinde mukim
                    Gebze Belediyesi ("Belediye" veya "Kurum"); belediyecilik hizmetleri esnasında topladığı
                    aşağıda yer alan bir kısım kişisel verileri, 6698 sayılı Kişisel Verilerin Korunması Kanunu
                    ("KVK Kanunu") ve sair mevzuat hükümlerine uygun şekilde işlenmesini amaçlamaktadır.
                </p>
                <p class="metin-govde">
                    Belediyemiz hizmetlerinden yararlanmanız esnasında bildirdiğiniz/bildireceğiniz kişisel
                    verileriniz Kurumumuz tarafından "Veri Sorumlusu" sıfatıyla,
                </p>
                <ul class="metin-govde">
                    <li class="mb-2">Kişisel verilerinizi işlenmelerini gerektiren amaç çerçevesinde ve bu amaç ile bağlantılı, sınırlı ve ölçülü şekilde,</li>
                    <li class="mb-2">Kurumumuza bildirdiğiniz veya bildirildiği şekliyle kişisel verilerin doğruluğunu ve en güncel halini koruyarak,</li>
                    <li class="mb-2">Kaydedileceğini, depolanacağını, muhafaza edileceğini, yeniden düzenleneceğini, kanunen bu kişisel verileri talep etmeye yetkili olan kurumlar ile paylaşılacağını ve KVK Kanunu'nun öngördüğü şartlarda, yurtiçi üçüncü kişilere aktarılacağını, devredileceğini, sınıflandırılabileceğini ve KVK Kanunu'nda sayılan sair şekillerde işlenebileceğini ve KVK Kanunu'nda sayılan diğer işlemlere tabi tutulabileceğini bildiririz.</li>
                </ul>
                <p class="metin-govde">
                    İş bu Aydınlatma Metni ile belediyemiz tarafından yürütülen faaliyetlerin KVK Kanunu'nda
                    yer alan ilkelerle uyumlu olarak sürdürülmesi ve geliştirilmesi benimsenmiştir.
                </p>
            </section>

            <section class="mb-5">
                <h2 class="bolum-baslik">2. Kişisel Verilerinin Toplanması ve Usulü</h2>
                <p class="metin-govde">
                    Kişisel verileriniz; https://www.gebze.bel.tr, belediye işlemleri, e-belediye işlemleri,
                    Gebze Belediyesi çağrı merkezi, Gebze Belediyesi çözüm masası başvuruları, e-devlet ile
                    ilişkili gerçekleştirilen işlemlerde e-devlet bağlantısı, fiziki başvuru, e-posta, çağrı
                    merkezlerimiz aracılığı ile otomatik olmayan ve otomatik yöntemlerle toplanmaktadır.
                    Kişisel verileriniz fiziki ortamda arşivlerde, dijital ortamda ise Gebze Belediyesi
                    bünyesinde bulunan sunucularda saklanmaktadır.
                </p>

                <h6 class="fw-bold mt-4 mb-2" style="color:var(--lacivert-koyu);">Veri Kategorisi</h6>
                <ul class="metin-govde">
                    <li class="mb-2"><strong>Kimlik Verisi:</strong> Ad-soyad, T.C. kimlik numarası, vergi kimlik numarası, uyruk bilgisi, anne adı-baba adı, doğum yeri, doğum tarihi, cinsiyet gibi bilgileri içeren ehliyet, nüfus cüzdanı, araç ruhsatı suretleri gibi belgeler, yabancı kimlik no, pasaport no ile imza/paraf bilgisi, ticaret sicil numarası, abonelik numaraları, askerlik durum belgesi, evlenme beyannamesi</li>
                    <li class="mb-2"><strong>İletişim Verisi:</strong> Telefon numarası, faks, açık adres bilgisi, ikametgah belgesi, sosyal medya hesapları, ülke, şehir, e-posta adresi (dâhili numarası ve kurumsal e-posta adresi dâhil)</li>
                    <li class="mb-2"><strong>Finans Verileri:</strong> Fatura, dekont, kredi dökümü, SGK hizmet dökümü, SGK son bordrosu, ödeme ve finans bilgileri (banka adı, banka hesabı, IBAN), malvarlığı bilgileri (tapu bilgileri), fakirlik belgesi, belediyeye olan borç bilgileri, sosyal yardımlara ilişkin bilgiler, emlak beyanı, gelir durumuna ilişkin bilgiler ve belgeler (fakirlik belgesi vb.)</li>
                    <li class="mb-2"><strong>Fiziksel Mekân Güvenlik Verisi:</strong> Fiziksel mekâna girişte, fiziksel mekânın içerisinde kalış sırasında alınan kamera kayıtları</li>
                    <li class="mb-2"><strong>Sağlık Verisi:</strong> Sağlık raporu, engelli raporu, kan grubu</li>
                    <li class="mb-2"><strong>Mesleki Deneyim Verisi:</strong> Diploma, öğrenim durumu, mesleği, eğitim bilgileri, iş güvenliği bilgisi, oda kayıt belgeler, ustalık belgesi, okul ve sınıf, özgeçmiş</li>
                    <li class="mb-2"><strong>Hukuki İşlem Verisi:</strong> Veraset İlamı, icra bilgileri, dava dosyaları kapsamındaki bilgi ve belgeler</li>
                    <li class="mb-2"><strong>Görsel ve İşitsel Kayıt Verisi:</strong> Fotoğraf, video kaydı</li>
                    <li class="mb-2"><strong>Dernek Üyeliği Verisi:</strong> Dernek üyelik bilgisi</li>
                    <li class="mb-2"><strong>Diğer Kişisel Veriler:</strong> Vekalet belgesi, hijyen belgesi, itfaiye raporu, desarj raporu, çed görüşü, kira sözleşmesi, iş güvenliği raporu, hekim ve uzman sözleşmesi, kapasite raporu, motor tesisat beyan belgesi, mesul müdürlük belgesi, sanayi sicil belgesi, araç plakası, kiralanan yere ilişkin bilgiler, noter tasdikli imza sirküleri, ukome izin belgesi, ticaret sicil belgesi, velinin muvaffakat belgesi, muvaffakatname, iş yeri açma ve çalıştırma ruhsat bilgisi, kapasite raporu</li>
                    <li class="mb-2"><strong>Ceza Mahkumiyeti ve Güvenlik Verisi:</strong> Adli sicil kaydı, cezaevi müddetnamesi, ceza mahkumiyet ve güvenlik tedbirleri</li>
                    <li class="mb-2"><strong>Biyometrik Veri:</strong> Parmak izi</li>
                    <li class="mb-2"><strong>Kılık ve Kıyafet Verisi:</strong> Beden bilgileri</li>
                    <li class="mb-2"><strong>Siyasi Düşünce Verisi:</strong> Siyasi parti üyeliği</li>
                </ul>
            </section>

            <section class="mb-5">
                <h2 class="bolum-baslik">3. Kişisel Verilerin İşlenme Amaçları ve Hukuki Sebepler</h2>
                <p class="metin-govde">
                    Kişisel Verileriniz, Kurumumuz tarafından aşağıda belirtilen amaçlar ve hukuki sebepler
                    doğrultusunda, işlenebilir. Kişisel verilerinizin işlenme amacında herhangi bir değişiklik
                    olması halinde tarafınızdan ayrıca izin alınacaktır.
                </p>
                <p class="metin-govde">
                    Belediyecilik hizmetlerimiz kapsamında işlenen kişisel verileriniz; kanunlarda açıkça
                    öngörülmesi, bir sözleşmenin kurulması veya ifasıyla doğrudan doğruya ilgili olması kaydıyla
                    sözleşmenin taraflarına ait kişisel verilerin işlenmesinin gerekli olması, hukuki
                    yükümlülüğümüzün yerine getirilebilmesi için veri işlemenin zorunlu olması, bir hakkın
                    tesisi kullanılması veya korunması için veri işlemenin zorunlu olması, ilgili kişinin temel
                    hak ve özgürlüklerine zarar vermemek kaydıyla meşru menfaatimiz için veri işlenmesinin
                    zorunlu olması ve veri işleme için açık rızanın zorunlu olması sebepleri aşağıda yer alan
                    amaçlar dahilinde işlenmektedir.
                </p>
                <ul class="metin-govde">
                    <li class="mb-2">İmar, su ve kanalizasyon, ulaşım gibi kentsel alt yapı; coğrafî ve kent bilgi sistemleri; çevre ve çevre sağlığı, temizlik ve katı atık; zabıta, itfaiye, acil yardım, kurtarma ve ambulans; şehir içi trafik; defin ve mezarlıklar; ağaçlandırma, park ve yeşil alanlar; konut; kültür ve sanat, turizm ve tanıtım, gençlik ve spor meslek ve beceri kazandırma; ekonomi ve ticaretin geliştirilmesi hizmetlerini yapmak veya yaptırmak</li>
                    <li class="mb-2">Belediyeler de mali durumları ve hizmet önceliklerini değerlendirerek kadınlar ve çocuklar için konukevleri açmak</li>
                    <li class="mb-2">Devlete ait her derecedeki okul binalarının inşaatı ile bakım ve onarımını yapmak veya yaptırmak</li>
                    <li class="mb-2">Her türlü araç, gereç ve malzeme ihtiyaçlarını karşılamak</li>
                    <li class="mb-2">Sağlıkla ilgili her türlü tesisi açabilir ve işletebilir; mabetlerin yapımı, bakımı, onarımını yapmak</li>
                    <li class="mb-2">Kültür ve tabiat varlıkları ile tarihî dokunun ve kent tarihi bakımından önem taşıyan mekânların ve işlevlerinin korunmasını sağlamak, bu amaçla bakım ve onarımını yapmak, korunması mümkün olmayanları aslına uygun olarak yeniden inşa etmek</li>
                    <li class="mb-2">Gerektiğinde, sporu teşvik etmek amacıyla gençlere spor malzemesi vermek, amatör spor kulüplerine ayni ve nakdî yardım yapar ve gerekli desteği sağlamak</li>
                    <li class="mb-2">Her türlü amatör spor karşılaşmaları düzenlemek</li>
                    <li class="mb-2">Yurt içi ve yurt dışı müsabakalarda üstün başarı gösteren veya derece alan öğrencilere, sporculara, teknik yöneticilere ve antrenörlere belediye meclisi kararıyla ödül vermek</li>
                    <li class="mb-2">Gıda bankacılığı yapmak</li>
                    <li class="mb-2">Belde sakinlerinin mahallî müşterek nitelikteki ihtiyaçlarını karşılamak amacıyla her türlü faaliyet ve girişimde bulunmak</li>
                    <li class="mb-2">Kanunların belediyeye verdiği yetki çerçevesinde yönetmelik çıkarmak, belediye yasakları koymak ve uygulamak, kanunlarda belirtilen cezaları vermek</li>
                    <li class="mb-2">Gerçek ve tüzel kişilerin faaliyetleri ile ilgili olarak kanunlarda belirtilen izin veya ruhsatı vermek</li>
                    <li class="mb-2">Özel kanunları gereğince belediyeye ait vergi, resim, harç, katkı ve katılma paylarının tarh, tahakkuk ve tahsilini yapmak; vergi, resim ve harç dışındaki özel hukuk hükümlerine göre tahsili gereken doğal gaz, su, atık su ve hizmet karşılığı alacakların tahsilini yapmak veya yaptırmak</li>
                    <li class="mb-2">Müktesep haklar saklı kalmak üzere; içme, kullanma ve endüstri suyu sağlamak; atık su ve yağmur suyunun uzaklaştırılmasını sağlamak; bunlar için gerekli tesisleri kurmak, kurdurmak, işletmek ve işlettirmek; kaynak sularını işletmek veya işlettirmek</li>
                    <li class="mb-2">Toplu taşıma yapmak; bu amaçla otobüs, deniz ve su ulaşım araçları, tünel, raylı sistem dâhil her türlü toplu taşıma sistemlerini kurmak, kurdurmak, işletmek ve işlettirmek</li>
                    <li class="mb-2">Katı atıkların toplanması, taşınması, ayrıştırılması, geri kazanımı, ortadan kaldırılması ve depolanması ile ilgili bütün hizmetleri yapmak ve yaptırmak</li>
                    <li class="mb-2">Mahallî müşterek nitelikteki hizmetlerin yerine getirilmesi amacıyla, belediye ve mücavir alan sınırları içerisinde taşınmaz almak, kamulaştırmak, satmak, kiralamak veya kiraya vermek, trampa etmek, tahsis etmek, bunlar üzerinde sınırlı aynî hak tesis etmek</li>
                    <li class="mb-2">Borç almak, bağış kabul etmek</li>
                    <li class="mb-2">Toptancı ve perakendeci hâlleri, otobüs terminali, fuar alanı, mezbaha, ilgili mevzuata göre yat limanı ve iskele kurmak, kurdurmak, işletmek, işlettirmek veya bu yerlerin gerçek ve tüzel kişilerce açılmasına izin vermek</li>
                    <li class="mb-2">Vergi, resim ve harçlar dışında kalan dava konusu uyuşmazlıkların anlaşmayla tasfiyesine karar vermek</li>
                    <li class="mb-2">Gayrisıhhî müesseseler ile umuma açık istirahat ve eğlence yerlerini ruhsatlandırmak ve denetlemek</li>
                    <li class="mb-2">Beldede ekonomi ve ticaretin geliştirilmesi ve kayıt altına alınması amacıyla izinsiz satış yapan seyyar satıcıları faaliyetten men etmek, izinsiz satış yapan seyyar satıcıların faaliyetten men edilmesi sonucu, cezası ödenmeyerek iki gün içinde geri alınmayan gıda maddelerini gıda bankalarına, cezası ödenmeyerek otuz gün içinde geri alınmayan gıda dışı malları yoksullara vermek</li>
                    <li class="mb-2">Reklam panoları ve tanıtıcı tabelalar konusunda standartlar getirmek</li>
                    <li class="mb-2">Gayrisıhhî işyerlerini, eğlence yerlerini, halk sağlığına ve çevreye etkisi olan diğer işyerlerini kentin belirli yerlerinde toplamak; hafriyat toprağı ve moloz döküm alanlarını; sıvılaştırılmış petrol gazı (LPG) depolama sahalarını; inşaat malzemeleri, odun, kömür ve hurda depolama alanları ve satış yerlerini belirlemek; bu alan ve yerler ile taşımalarda çevre kirliliği oluşmaması için gereken tedbirleri almak</li>
                    <li class="mb-2">Kara, deniz, su ve demiryolu üzerinde işletilen her türlü servis ve toplu taşıma araçları ile taksi sayılarını, bilet ücret ve tarifelerini, zaman ve güzergâhlarını belirlemek; durak yerleri ile karayolu, yol, cadde, sokak, meydan ve benzeri yerler üzerinde araç park yerlerini tespit etmek ve işletmek, işlettirmek veya kiraya vermek</li>
                    <li class="mb-2">Kanunların belediyelere verdiği trafik düzenlemesinin gerektirdiği bütün işleri yürütmek</li>
                    <li class="mb-2">Belediye mücavir alan sınırları içerisinde 5/11/2008 tarihli ve 5809 sayılı Elektronik Haberleşme Kanunu, 26/9/2011 tarihli ve 655 sayılı Ulaştırma, Denizcilik ve Haberleşme Bakanlığının Teşkilat ve Görevleri Hakkında Kanun Hükmünde Kararname ve ilgili diğer mevzuata göre kuruluş izni verilen alanda tesis edilecek elektronik haberleşme istasyonlarına kent ve yapı estetiği ile elektronik haberleşme hizmetinin gerekleri dikkate alınarak ücret karşılığında yer seçim belgesi vermek</li>
                    <li class="mb-2">Belediye sınırları içerisinde, yapı ruhsatı veya yapı kullanma izni hangi idare tarafından verilmiş olursa olsun, hizmete sunulacak olan asansörlerin tescilini yapmak, ilgili teknik mevzuat çerçevesinde yıllık periyodik kontrollerini yapmak ya da yetkilendirilmiş muayene kuruluşları aracılığıyla yaptırmak, gerekli hâllerde asansörleri hizmet dışı bırakmak</li>
                    <li class="mb-2">Bisiklet yollarının ve şeritlerinin, bisiklet ve elektrikli skuter park ve şarj istasyonlarının, yaya yollarının ve gürültü bariyerlerinin planlanması, projelendirilmesi, yapımı, bakımı ve onarımıyla ilgili işleri yürütmek</li>
                    <li class="mb-2">Vatandaşlara SMS ile bilgilendirme yapılması amacıyla</li>
                    <li class="mb-2">Organizasyon, davet ve açılış programlarının yönetilmesi</li>
                    <li class="mb-2">Kuruma gelen iş başvurularının özel sektördeki uygun pozisyonlara yönlendirilmesi</li>
                    <li class="mb-2">Sosyal yardım faaliyetlerini gerçekleştirebilmek</li>
                    <li class="mb-2">Nikah ve evlendirme işlemlerinin gerçekleştirilmesi</li>
                    <li class="mb-2">Çocukların psikolog hizmetinden yararlanması ve iletişim kurulması amacıyla</li>
                    <li class="mb-2">İnternet sitesi üzerinden bilgilendirme için yayınlanması amacıyla</li>
                    <li class="mb-2">Engelli vatandaşlara park yeri ayrılması amacıyla</li>
                    <li class="mb-2">Kurumsal iletişim faaliyetleri amacıyla</li>
                    <li class="mb-2">Dernekler ile ilgili işlemlerin yürütülmesi amacıyla</li>
                    <li class="mb-2">Belediye Meclis üyelerinin kişisel verilerinin işlenmesi amacıyla</li>
                    <li class="mb-2">Belediye Başkanları, başkan yardımcıları, müdürler, muhtarlar, etik komisyonu ve arabuluculuk komisyonu üyelerinin bilgilerinin web sitesinden paylaşımı yöntemiyle vatandaşların bilgilendirilmesi amacıyla</li>
                    <li class="mb-2">Vatandaş ve Kurum taleplerin alınması ve değerlendirilmesi amaçlarıyla</li>
                </ul>
            </section>

            <section class="mb-5">
                <h2 class="bolum-baslik">4. Kişisel Verilerin 3. Kişilere Aktarılması</h2>
                <p class="metin-govde">
                    Kişisel veri aktarımlarında uygulanacak usul ve esaslar KVK Kanunu'nun 8. ve 9. maddelerinde
                    düzenlenmiş olup, ilgili kişinin kişisel verileri ve özel nitelikli kişisel verileri,
                    gerektiğinde yurtiçinde yer alan gerçek veya tüzel üçüncü kişilere aktarılabilmektedir.
                    Kurumumuz tarafından toplanan kişisel verilerin yurt dışına aktarımı yapılmamaktadır.
                </p>

                <h6 class="fw-bold mt-4 mb-2" style="color:var(--lacivert-koyu);">Kişisel Verilerin Yurt İçindeki Üçüncü Kişilere Aktarılması</h6>
                <p class="metin-govde">
                    Kanunlarda açıkça öngörülmesi, bir sözleşmenin kurulması veya ifasıyla doğrudan doğruya
                    ilgili olması kaydıyla sözleşmenin taraflarına ait kişisel verilerin işlenmesinin gerekli
                    olması, hukuki yükümlülüğümüzün yerine getirilebilmesi için veri işlemenin zorunlu olması,
                    bir hakkın tesisi kullanılması veya korunması için veri işlemenin zorunlu olması, ilgili
                    kişinin temel hak ve özgürlüklerine zarar vermemek kaydıyla meşru menfaatimiz için veri
                    işlenmesinin zorunlu olması işleme şartları sebebiyle özellikle;
                </p>
                <ul class="metin-govde">
                    <li class="mb-2">T.C. İç İşleri Bakanlığına, Hazine ve Maliye Bakanlığına, Sanayi ve Teknoloji Bakanlığına, Çevre ve Şehircilik Bakanlığına Bağlı ve İlgili Kurumlara</li>
                    <li class="mb-2">Yasal denetim süreçleri dahilinde denetim ve teftişle görevli kamu kurum ve kuruluşlarına</li>
                    <li class="mb-2">Yetkili kamu kurum ve kuruluşları, adli makamlara ve gerekli olduğu takdirde kolluk kuvvetlerine aktarılabilmektedir</li>
                    <li class="mb-2">Bankalara, Belde A.Ş., Hastanelere, Royal Cert Denetim Firmasına, Kocaeli Büyükşehir Belediyesine, TOKİ'ye aktarılmaktadır</li>
                </ul>
            </section>

            <section class="mb-5">
                <h2 class="bolum-baslik">5. Kişisel Verilerin Güvenliğinin ve Gizliliğinin Sağlanması</h2>
                <p class="metin-govde">
                    Kurumumuz, KVK Kanunu'nun 12. maddesine uygun olarak, işlemekte olduğu kişisel verilerin
                    hukuka aykırı olarak işlenmesini ve kişisel verilere hukuka aykırı erişilmesini önlemek,
                    kişisel verilerin muhafazasını sağlamak için uygun güvenlik düzeyini temin etmeye yönelik
                    gerekli her türlü teknik ve idari tedbirleri almaktadır. İşlenen kişisel verilerin kanuni
                    olmayan yollarla başkaları tarafından elde edilmesi halinde, Kurumumuz bu durumu en kısa
                    sürede ilgili veri sahibine ve Kurul'a bildirecektir.
                </p>
            </section>

            <section class="mb-5">
                <h2 class="bolum-baslik">6. Kişisel Verilerin Silinmesi, Yok Edilmesi ve Anonim Hale Getirilmesi</h2>
                <p class="metin-govde">
                    KVK Kanunu'nun 7. maddesi uyarınca, kişisel verilerin ilgili mevzuata uygun olarak işlenmiş
                    olmasına rağmen, işlenmesini gerektiren sebeplerin ortadan kalkması halinde kişisel veriler
                    re'sen veya kişisel veri sahibinin talebi üzerine Kurumumuz tarafından saklama ve imha
                    politikamıza göre uygun görülecek yöntemle imha edilir.
                </p>
            </section>

            <section class="mb-5">
                <h2 class="bolum-baslik">7. İlgili Kişinin Sahip Olduğu Haklar</h2>
                <p class="metin-govde">
                    KVK Kanunu'nun 11. maddesi uyarınca, Kurumumuza başvurarak kişisel verileriniz hakkında
                    aşağıdaki konulara ilişkin taleplerde bulunabilirsiniz:
                </p>
                <ul class="metin-govde">
                    <li class="mb-2">Kişisel verilerinin işlenip işlenmediğini öğrenme</li>
                    <li class="mb-2">Kişisel verileri işlenmişse buna ilişkin bilgi talep etme</li>
                    <li class="mb-2">Kişisel verilerinin işlenme amacını ve bunların amacına uygun kullanılıp kullanılmadığını öğrenme</li>
                    <li class="mb-2">Kişisel verilerinin yurt içinde veya yurt dışında aktarıldığı üçüncü kişileri öğrenme</li>
                    <li class="mb-2">Kişisel verilerinin eksik veya yanlış işlenmiş olması halinde bunların düzeltilmesini isteme ve bu kapsamda yapılan işlemin kişisel verilerin aktarıldığı üçüncü kişilere bildirilmesini isteme</li>
                    <li class="mb-2">Kişisel verilerinin işlenmesini gerektiren sebeplerin ortadan kalkması halinde bunların silinmesini, yok edilmesini veya anonim hale getirilmesini isteme ve bu kapsamda yapılan işlemin kişisel verilerin aktarıldığı üçüncü kişilere bildirilmesini isteme</li>
                    <li class="mb-2">İşlenen verilerinin münhasıran otomatik sistemler vasıtasıyla analiz edilmesi suretiyle veri sahibinin aleyhine bir sonucun ortaya çıkmasına itiraz etme</li>
                    <li class="mb-2">Kişisel verilerinin kanuna aykırı olarak işlenmesi sebebiyle zarara uğraması halinde zararın giderilmesini talep etme</li>
                </ul>
                <p class="metin-govde">
                    İlgili kişiler kanun kapsamındaki başvuruları ve taleplerini, "https://www.gebze.bel.tr"
                    web adresinde bulunan "Kişisel Verilerin Korunması Kanunu Uyarınca Başvuru Formu"nu doldurarak:
                </p>
                <ul class="metin-govde">
                    <li class="mb-2">Güzeller Mahallesi Bahar Cad. N:1 41400 Gebze/Kocaeli adresine bizzat teslim edebilir veya Noter kanalıyla iletebilir,</li>
                    <li class="mb-2">gebzebelediyesi@hs01.kep.tr adresine güvenli elektronik imzalı ya da mobil imzalı olarak, kayıtlı elektronik postayla (KEP) veya kvkk@gebze.bel.tr mail adresine ilgili kişi tarafından veri sorumlusuna daha önce bildirilen ve veri sorumlusunun sisteminde kayıtlı bulunan elektronik posta adresini kullanmak suretiyle iletebilirsiniz.</li>
                </ul>
                <p class="metin-govde">
                    Kurumumuz başvuru taleplerini Kanun'un 13. maddesine uygun olarak, talebin niteliğine göre
                    ve en geç 30 (otuz) gün içinde ücretsiz olarak sonuçlandıracaktır. Talebin reddedilmesi
                    halinde, red nedeni/nedenleri yazılı olarak veya elektronik ortamda gerekçeleriyle bildirilir.
                </p>
                <p class="metin-govde">
                    İşbu Aydınlatma Metni, gerekli görüldüğü hallerde Kurumumuz tarafından revize edilebilir.
                    Revizyonun söz konusu olduğu hallerde ise, bu hususa ilişkin olarak tarafınıza bilgilendirme
                    yapılacaktır. Aydınlatma Metni'nin en güncel haline
                    <a href="https://www.gebze.bel.tr" target="_blank" rel="noopener">https://www.gebze.bel.tr</a>
                    linkinden ulaşabilirsiniz.
                </p>
            </section>

            <?php include '../includes/kurumsal-icerik-alt.php'; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>
