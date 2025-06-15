# Kafe Yönetim Sistemi

Bu proje, kafeler için geliştirilmiş kapsamlı bir yönetim sistemidir. Müşteri ve çalışan arayüzleri ile sipariş takibi, ürün yönetimi, kategori yönetimi ve fiş işlemlerini yönetebilirsiniz.

## 📸 Ekran Görüntüleri

### Yönetici Paneli
![Yönetici Paneli](/screenshots/admin-panel.png)
*Yönetici kontrol paneli*

![Kategori Yönetimi](/screenshots/category-management.png)
*Kategori Ekranı*

![Ürün Yönetimi](/screenshots/product-management.png)
*Ürün Ekranı*

![Fiş Yönetimi](/screenshots/receipts-management.png)
*Fiş Ekranı*

### Müşteri Paneli
![Müşteri Ana Sayfa](/screenshots/customer-dashboard.png)
*Müşteri paneli ana ekranı*

![Fiş Detayı](/screenshots/receipt-detail.png)
*Fiş detayı ve ürün listesi görüntüleme*

## 🎥 Tanıtım Videosu

[![Kafe Yönetim Sistemi Tanıtım](https://img.youtube.com/vi/mqP_XCyxFd0/0.jpg)](https://www.youtube.com/watch?v=mqP_XCyxFd0)

*Sistemin temel özelliklerini ve kullanımını gösteren tanıtım videosu*

## 🚀 Özellikler

### 👥 Kullanıcı Rolleri
- **Müşteri Paneli**: Siparişleri görüntüleme ve geçmiş siparişleri takip etme
- **Yönetici Paneli**: Ürün, kategori ve fiş yönetimi

### 📦 Ana Modüller
1. **Kategori Yönetimi**
   - Hiyerarşik kategori yapısı (Ana kategori - Alt kategori)
   - Kategori ekleme, düzenleme ve silme
   - Ağaç yapısında kategori görüntüleme

2. **Ürün Yönetimi**
   - Ürün ekleme, düzenleme ve silme
   - Kategoriye göre ürün sınıflandırma
   - Ürün fiyatı ve detayları yönetimi
   - Ürün görseli desteği

3. **Fiş İşlemleri**
   - Yeni fiş oluşturma
   - Fişe ürün ekleme/çıkarma
   - Fiş detayı görüntüleme
   - Fiş geçmişi ve raporlama

4. **Kullanıcı Yönetimi**
   - Müşteri kaydı ve girişi
   - Çalışan kaydı ve girişi
   - Profil yönetimi

## 🛠 Teknik Detaylar

### Kullanılan Teknolojiler
- **Backend**: PHP 7.4+
- **Frontend**: HTML5, CSS3, Bootstrap 5.3
- **Veritabanı**: MySQL

### Veritabanı Tabloları
- `CATEGORY`: Ürün kategorileri
- `CATEGORY_TREE`: Kategori hiyerarşisi
- `CUSTOMER`: Müşteri bilgileri
- `EMPLOYEE`: Çalışan bilgileri
- `PRODUCT`: Ürün bilgileri
- `RECEIPT`: Fiş bilgileri
- `RECEIPT_PRODUCTS`: Fiş-ürün ilişkileri

### Dizin Yapısı
```
├── admin/                 # Yönetici paneli
│   ├── dashboard/        # Yönetici kontrol paneli
│   │   ├── categories/  # Kategori işlemleri
│   │   ├── products/   # Ürün işlemleri
│   │   └── receipts/   # Fiş işlemleri
│   └── login/           # Yönetici girişi
├── app/                  # Uygulama çekirdeği
│   ├── Controllers/     # Denetleyiciler
│   ├── Models/         # Veri modelleri
│   ├── Repositories/   # Veritabanı işlemleri
│   ├── Views/          # Görünüm şablonları
│   └── Functions/      # Yardımcı fonksiyonlar
├── dashboard/            # Müşteri paneli
│   ├── products/       # Ürün listeleme
│   └── receipts/       # Fiş görüntüleme
└── login/              # Müşteri girişi
└── register/           # Müşteri kayıt
└── logout/             # Müşteri ve Yönetici çıkışı
```

## 🚀 Kurulum

1. Dosyaları web sunucusuna yükleyin
2. Veritabanını dummy.sql ve schema.sql dosyalarını kullanarak oluşturun
3. `.env` dosyasını oluşturun ve veritabanı bilgilerini girin:
   ```
   DB_HOST=localhost
   DB_NAME=your_database
   DB_USER=your_username
   DB_PASSWORD=your_password
   ```

## 💡 Özellikler ve Fonksiyonlar

### 🏪 Yönetici Paneli
- Kategori yönetimi (CRUD işlemleri)
- Ürün yönetimi (CRUD işlemleri)
- Fiş yönetimi ve takibi
- Satış istatistikleri
- Çalışan yönetimi

### 👥 Müşteri Paneli
- Fiş geçmişi görüntüleme
- Satın alınan ürünleri listeleme
- Toplam harcama istatistikleri
- Profil yönetimi

## 🔒 Güvenlik Özellikleri
- Şifreleme (password_hash)
- Oturum yönetimi
- SQL Injection koruması (PDO)
- XSS koruması (htmlspecialchars)
