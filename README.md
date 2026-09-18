# Gebze Belediyesi Web Sitesi

Gebze Belediyesi'nin resmi web sitesinin, staj/eğitim amaçlı olarak yeniden tasarlanıp geliştirilmesi projesi. Bu depo, ziyaretçi tarafındaki (frontend) siteyi içerir. İçeriğin yönetildiği **Admin Panel**, kendi başına çalışan ayrı bir depodadır: [gebze-belediyesi-admin](https://github.com/sumeyyegorkem/gebze-belediyesi-admin)

## 📋 Proje Hakkında

Bu proje, veritabanı destekli, dinamik bir belediye web sitesidir. Haberler, duyurular, videolar, fotoğraf galerisi, etkinlikler, projeler, kurumsal sayfalar (yönetim şeması, meclis kararları, müdürlükler vb.) ve belediye hizmetleri, ayrı depodaki Admin Panel üzerinden yönetilip bu sitede yayınlanır.

## 🛠️ Kullanılan Teknolojiler

- **Backend:** PHP (PDO ile MySQL/MariaDB bağlantısı), SQL injection'a karşı prepared statement kullanımı
- **Veritabanı:** MySQL/MariaDB (XAMPP üzerinde phpMyAdmin ile yönetilir)
- **Frontend:** HTML, CSS, JavaScript, Bootstrap 5.3 + Bootstrap Icons
- **Geliştirme Ortamı:** XAMPP (Apache, MySQL, PHP), VS Code
- **Versiyon Kontrolü:** Git & GitHub

## 📁 Proje Yapısı

```
gebze-belediyesi/
├── config/         # Veritabanı bağlantısı (db.php) ve veritabanı yedeği (veritabani.sql)
├── css/            # Sitenin stil dosyası (style.css)
├── e-belediye/     # E-Belediye hizmet sayfaları: imar-ve-yapı, vergi, bilgilendirme, diğer hizmetler
├── etkinlikler/    # Etkinlik listesi ve detay sayfası
├── gebze/          # İlçe tanıtım sayfaları: tarihçe, bugünkü Gebze, kent rehberi, muhtarlar, kardeş şehirler
├── genel/          # İletişim, hakkımızda (Başkan), faaliyet alanları
├── haberler/       # Haber, duyuru ve video sayfaları (liste + detay)
├── hizmetler/      # Hizmetler tanıtım sayfası ve hizmet detay sayfaları (emlak, fen işleri, zabita, vb.)
├── img/            # Site genelinde kullanılan sabit görseller
├── includes/       # Ortak parçalar: navbar, footer, sidebar'lar, SEO yardımcıları
├── js/             # JavaScript dosyası (script.js)
├── kurumsal/       # Kurumsal sayfalar: meclis, yönetim şeması, müdürlükler, komisyonlar, KVKK vb.
├── projeler/       # Proje listesi ve proje detay sayfası
├── sistem/         # 403/404/500/503 hata sayfaları ve bakım modu ekranı
├── uploads/        # Admin panelden yüklenen görsel/belge içerikleri (kullanıcı içeriği)
└── index.php       # Ana sayfa
```

## 🚀 Kurulum

1. [XAMPP](https://www.apachefriends.org/) indirip kurun, Apache ve MySQL servislerini başlatın.
2. Bu depoyu `htdocs` klasörüne klonlayın:
   ```
   cd C:\xampp\htdocs
   git clone https://github.com/sumeyyegorkem/gebze-belediyesi.git
   ```
3. `http://localhost/phpmyadmin` adresinden `gebze_belediyesi` adında bir veritabanı oluşturun.
4. `config/veritabani.sql` dosyasını **İçe Aktar (Import)** sekmesinden içeri aktarın.
5. `config/db.php` içindeki veritabanı bağlantı bilgilerinin (host, kullanıcı adı, şifre) kendi XAMPP kurulumunuzla eşleştiğinden emin olun.
6. Siteyi tarayıcıda açın: `http://localhost/gebze-belediyesi/`

## ✨ Site Özellikleri

- Haberler, Duyurular, Videolar ve albüm bazlı Fotoğraf Galerisi
- Filtrelenebilir, sayfalanabilir Etkinlikler ve Projeler listeleri
- Kurumsal organizasyon şeması (Yönetim Şeması) ve Müdürlükler detay sayfaları
- E-Belediye elektronik hizmet sayfaları (imar, vergi, bilgilendirme hizmetleri)
- Belediye hizmet sayfaları: emlak-istimlak, fen işleri, zabita, temizlik, veteriner, nikah işlemleri, kültür-sosyal işler
- İletişim sayfası ve tüm listeleme sayfalarında ortak breadcrumb / kategori menüsü / sayfalama deseni
- Bakım modu ve özel hata sayfaları (403/404/500/503)

## 🔐 Admin Panel

Bu depo yalnızca ziyaretçi tarafını içerir. İçerik yönetimi (haber/duyuru/etkinlik ekleme, kullanıcı yönetimi, site ayarları vb.) için ayrı ve kendi başına çalışan **[gebze-belediyesi-admin](https://github.com/sumeyyegorkem/gebze-belediyesi-admin)** deposuna bakın.

## 👥 Ekip

Sümeyye

## 📝 Notlar

- Tüm veritabanı sorguları PDO "prepared statement" ile yazılmıştır (SQL Injection'a karşı).
- Yönetici şifreleri `password_hash()` ile saklanır, düz metin şifre veritabanında tutulmaz.
- `config/db.php` içinde bulunan bilgiler XAMPP'ın varsayılan yerel geliştirme ayarlarıdır (kullanıcı: root, şifre yok); gerçek bir sunucuya taşırken mutlaka değiştirilmelidir.
- `uploads/` klasörü, admin panelden yüklenen kullanıcı içeriğidir; `config/veritabani.sql` ise sadece veritabanı yapısının yedeğidir.
