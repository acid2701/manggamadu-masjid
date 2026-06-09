# MASJIDKU — DOKUMENTASI LENGKAP PROYEK
**Masjid Sholikin, Potronayan, Nogosari, Boyolali**
**Versi 1.0 | Juni 2026**

---

# DAFTAR ISI

1. [BRD — Business Requirements Document](#brd)
2. [PRD — Product Requirements Document](#prd)
3. [SDD — System Design Document](#sdd)
4. [ERD — Entity Relationship Diagram](#erd)
5. [Wireframe — Deskripsi Layout Halaman](#wireframe)

---

---

# 1. BRD — BUSINESS REQUIREMENTS DOCUMENT {#brd}

## 1.1 Latar Belakang

Masjid Sholikin berlokasi di Desa Potronayan, Kecamatan Nogosari, Kabupaten Boyolali, Jawa Tengah. Sebagai pusat kegiatan ibadah dan sosial masyarakat dusun, masjid ini membutuhkan media digital untuk:

- Menyebarkan informasi kegiatan kepada jamaah lokal maupun luar daerah
- Menerima donasi secara transparan dari masyarakat umum
- Mengelola keuangan masjid secara tercatat dan akuntabel
- Membangun kepercayaan publik melalui transparansi laporan keuangan

## 1.2 Tujuan Bisnis

| No | Tujuan | Indikator Keberhasilan |
|----|--------|------------------------|
| 1 | Meningkatkan jangkauan informasi masjid | Website dapat diakses publik 24/7 |
| 2 | Membuka kanal donasi digital | Donasi online aktif diterima dan tercatat |
| 3 | Transparansi keuangan masjid | Laporan keuangan dapat ditampilkan ke publik |
| 4 | Efisiensi manajemen konten | Pengurus dapat update konten tanpa bantuan developer |
| 5 | Manajemen kegiatan terpusat | Semua jadwal kajian & event terdata di satu sistem |

## 1.3 Stakeholder

| Stakeholder | Peran | Kebutuhan Utama |
|-------------|-------|-----------------|
| DKM / Pengurus Masjid | Pemilik sistem | Kemudahan kelola konten & laporan keuangan |
| Jamaah Lokal | Pengguna informasi | Jadwal sholat, kegiatan, pengumuman |
| Donatur Luar Daerah | Pengguna donasi | Kemudahan berdonasi & melihat bukti penggunaan dana |
| Bendahara | Pengelola keuangan | Laporan otomatis, manajemen donasi |
| Developer (MasjidKu) | Pembangun sistem | Dokumentasi jelas, scope terdefinisi |

## 1.4 Batasan Bisnis

- Sistem berjalan di shared hosting / VPS dengan domain aktif
- Tidak mengintegrasikan payment gateway otomatis (fase 1)
- Konfirmasi donasi dilakukan manual oleh donatur + verifikasi bendahara
- Tidak mengelola data sensitif seperti NIK / KTP jamaah
- Laporan keuangan publik/private dapat dikontrol dari admin panel

## 1.5 Asumsi

- Masjid memiliki rekening bank aktif atas nama masjid/DKM
- Masjid memiliki QRIS statis yang sudah terdaftar
- Minimal satu pengurus aktif sebagai admin konten
- Koneksi internet tersedia untuk pengelolaan admin

## 1.6 Risiko Bisnis

| Risiko | Probabilitas | Dampak | Mitigasi |
|--------|-------------|--------|----------|
| Admin tidak aktif update konten | Tinggi | Sedang | UI admin sesederhana mungkin |
| Donatur tidak konfirmasi setelah transfer | Sedang | Sedang | Reminder teks di halaman donasi |
| Manipulasi nominal konfirmasi donasi | Sedang | Tinggi | Verifikasi manual oleh bendahara wajib |
| Hosting down | Rendah | Tinggi | Pilih hosting dengan uptime guarantee |

---

---

# 2. PRD — PRODUCT REQUIREMENTS DOCUMENT {#prd}

## 2.1 Gambaran Produk

**MasjidKu** adalah sistem website manajemen masjid berbasis web yang terdiri dari:
- **Public Website** — dapat diakses siapa saja tanpa login
- **Admin Panel** — diakses pengurus dengan role & permission berbeda

**Tech Stack:**
- Backend: Laravel 13
- Frontend: Livewire 3 + Alpine.js (Full SPA feel)
- CSS: Tailwind CSS v4
- RBAC: Spatie Laravel Permission
- Database: MySQL
- Jadwal Sholat: Aladhan API (by koordinat)
- Hosting: VPS / Shared Hosting + Domain aktif

## 2.2 User Roles

| Role | Deskripsi | Akses |
|------|-----------|-------|
| Super Admin | Developer / Ketua DKM | Akses penuh semua modul |
| Ketua | Ketua DKM | Lihat semua, approve konten tertentu |
| Sekretaris | Pengelola konten | Kelola berita, pengumuman, kegiatan, galeri |
| Bendahara | Pengelola keuangan | Kelola donasi, laporan keuangan, pengaturan rekening |
| Guest | Pengunjung publik | Akses halaman publik saja |

## 2.3 Modul & Fitur

### 2.3.1 PUBLIC — Halaman Publik

#### A. Beranda
- Hero section dengan nama & foto masjid
- Jadwal sholat hari ini (realtime dari Aladhan API)
- Pengumuman terbaru (3 item)
- Kegiatan mendatang (3 item)
- Total donasi bulan ini (jika setting publik = aktif)
- Link cepat: Donasi, Kegiatan, Kontak

#### B. Profil Masjid
- Sejarah masjid
- Visi & misi
- Struktur pengurus (nama + jabatan)
- Foto masjid

#### C. Jadwal Sholat
- Jadwal 1 bulan penuh (by koordinat dari settings)
- Highlight waktu sholat berikutnya
- Sumber data: Aladhan API

#### D. Jadwal Kegiatan
- List semua kegiatan mendatang
- Filter by kategori (kajian, pengajian, event, dll)
- Detail kegiatan (judul, deskripsi, tanggal, lokasi, poster)

#### E. Berita & Pengumuman
- List artikel/pengumuman
- Detail artikel
- Kategori: Berita, Pengumuman, Kajian

#### F. Galeri
- Grid foto kegiatan
- Lightbox preview

#### G. Donasi
- Tampil QRIS statis (gambar dari settings)
- Tampil nomor rekening + nama bank + nama penerima (dari settings)
- Form konfirmasi donasi:
  - Nama donatur (opsional, ada opsi "Anonim")
  - Nominal donasi
  - Kategori donasi (dari donation_categories — dinamis)
  - Upload bukti transfer (opsional)
  - Pesan / doa (opsional)
- Status donasi: Menunggu verifikasi
- Informasi: "Dana akan diverifikasi oleh bendahara dalam 1x24 jam"

#### H. Laporan Keuangan Publik
- Tampil hanya jika setting `show_finance_public = true`
- Ringkasan: total pemasukan, total pengeluaran, saldo
- Tabel riwayat transaksi (tanpa detail sensitif)
- Filter by bulan/tahun

#### I. Kontak
- Alamat lengkap masjid
- Embed Google Maps (koordinat dari settings)
- Nomor kontak pengurus (dari settings)

---

### 2.3.2 ADMIN PANEL

#### A. Dashboard
- Statistik ringkas:
  - Total donasi bulan ini
  - Donasi pending (perlu verifikasi)
  - Total pengeluaran bulan ini
  - Saldo kas
- Grafik donasi 6 bulan terakhir
- Notifikasi donasi pending terbaru

#### B. Manajemen Donasi
- List semua donasi dengan status (pending/approved/rejected)
- Filter: status, kategori, tanggal, nominal
- Detail donasi: lihat bukti transfer
- Aksi: Approve / Reject (dengan catatan jika reject)
- Ketika approve → otomatis insert ke finance_records

#### C. Kategori Donasi
- CRUD kategori donasi
- Aktif/nonaktifkan kategori
- Riwayat: kategori nonaktif tetap tersimpan di data lama

#### D. Laporan Keuangan
- Pemasukan: otomatis dari donasi approved + input manual
- Pengeluaran: input manual oleh bendahara
- Filter: bulan, tahun, kategori
- Export: PDF & Excel
- Toggle: tampilkan ke publik atau hanya admin

#### E. Manajemen Konten
- **Berita/Pengumuman:** CRUD artikel, kategori, status publish
- **Kegiatan:** CRUD event, tanggal, deskripsi, poster upload
- **Galeri:** Upload foto, judul, keterangan, hapus

#### F. Manajemen Pengurus
- CRUD data pengurus (nama, jabatan, foto, urutan tampil)

#### G. Manajemen Pengguna & Role
- CRUD user admin
- Assign role: Super Admin / Ketua / Sekretaris / Bendahara
- Powered by Spatie Laravel Permission

#### H. Pengaturan (Settings)
- **Informasi Masjid:** nama, alamat, deskripsi singkat, foto
- **Koordinat:** latitude, longitude (untuk jadwal sholat & maps)
- **Kontak:** nomor telepon, email
- **Rekening Donasi:** nama bank, nomor rekening, nama pemilik rekening
- **QRIS:** upload gambar QRIS statis
- **Tampilan Publik:**
  - Toggle: tampilkan laporan keuangan ke publik
  - Toggle: tampilkan total donasi di beranda
- **Sosial Media:** link Facebook, Instagram, YouTube (opsional)

## 2.4 Non-Functional Requirements

| Aspek | Requirement |
|-------|-------------|
| Performance | Halaman publik load < 3 detik |
| Responsive | Mobile-first, support semua ukuran layar |
| Security | CSRF protection, XSS prevention, file upload validation |
| Availability | Uptime target 99% |
| Usability | Admin panel dapat dioperasikan tanpa pelatihan teknis |
| SEO | Meta title, description, og:image per halaman |

## 2.5 User Stories

```
[Donatur]
Sebagai donatur luar daerah, saya ingin melihat nomor rekening & QRIS
masjid sehingga saya bisa berdonasi dengan mudah.

Sebagai donatur, saya ingin mengisi form konfirmasi donasi
sehingga donasi saya tercatat dan dapat diverifikasi pengurus.

[Jamaah]
Sebagai jamaah, saya ingin melihat jadwal sholat hari ini
sehingga saya tahu waktu sholat tanpa buka aplikasi lain.

Sebagai jamaah, saya ingin melihat jadwal kajian minggu ini
sehingga saya bisa merencanakan kehadiran.

[Bendahara]
Sebagai bendahara, saya ingin menerima notifikasi donasi masuk
sehingga saya bisa segera memverifikasi.

Sebagai bendahara, saya ingin laporan keuangan otomatis terbentuk
dari donasi yang diapprove sehingga tidak perlu input ulang.

Sebagai bendahara, saya ingin bisa toggle laporan keuangan publik/private
sehingga saya bisa kontrol informasi yang tampil ke jamaah.

[Sekretaris]
Sebagai sekretaris, saya ingin bisa posting berita & pengumuman
tanpa bantuan developer sehingga informasi cepat tersebar.

[Super Admin]
Sebagai super admin, saya ingin bisa manage semua user & role
sehingga akses sistem terkontrol dengan baik.
```

---

---

# 3. SDD — SYSTEM DESIGN DOCUMENT {#sdd}

## 3.1 Arsitektur Sistem

```
┌─────────────────────────────────────────────────────┐
│                    CLIENT BROWSER                    │
│              (Mobile / Desktop / Tablet)             │
└──────────────────────┬──────────────────────────────┘
                       │ HTTPS
┌──────────────────────▼──────────────────────────────┐
│                   WEB SERVER                         │
│              Nginx / Apache + PHP 8.3                │
└──────────────────────┬──────────────────────────────┘
                       │
┌──────────────────────▼──────────────────────────────┐
│              LARAVEL 13 APPLICATION                  │
│                                                      │
│  ┌─────────────┐    ┌─────────────────────────────┐ │
│  │   Routes    │    │      Livewire 3 Components  │ │
│  │  (web.php)  │───▶│   (Full SPA feel, no reload)│ │
│  └─────────────┘    └─────────────────────────────┘ │
│                                                      │
│  ┌─────────────┐    ┌─────────────┐                 │
│  │  Controllers│    │   Services  │                 │
│  └─────────────┘    └─────────────┘                 │
│                                                      │
│  ┌─────────────┐    ┌─────────────┐                 │
│  │   Models    │    │  Middleware │                 │
│  │  (Eloquent) │    │  (Auth/Role)│                 │
│  └─────────────┘    └─────────────┘                 │
└──────────────────────┬──────────────────────────────┘
                       │
          ┌────────────┴────────────┐
          │                         │
┌─────────▼──────┐       ┌──────────▼────────┐
│   MySQL DB     │       │  External API      │
│                │       │  Aladhan API       │
│  - users       │       │  (Jadwal Sholat)   │
│  - donations   │       └───────────────────┘
│  - finance_    │
│    records     │       ┌───────────────────┐
│  - settings    │       │  File Storage     │
│  - posts       │       │  (public/storage) │
│  - events      │       │  - QRIS image     │
│  - galleries   │       │  - Bukti transfer │
│  - etc         │       │  - Foto galeri    │
└────────────────┘       └───────────────────┘
```

## 3.2 Struktur Folder Laravel

```
masjidku/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── DonationController.php
│   │   │   │   ├── DonationCategoryController.php
│   │   │   │   ├── FinanceController.php
│   │   │   │   ├── PostController.php
│   │   │   │   ├── EventController.php
│   │   │   │   ├── GalleryController.php
│   │   │   │   ├── CommitteeController.php
│   │   │   │   ├── UserController.php
│   │   │   │   └── SettingController.php
│   │   │   └── Public/
│   │   │       ├── HomeController.php
│   │   │       ├── DonationController.php
│   │   │       ├── PostController.php
│   │   │       ├── EventController.php
│   │   │       ├── GalleryController.php
│   │   │       └── FinanceController.php
│   │   └── Middleware/
│   │       └── CheckRole.php
│   ├── Livewire/
│   │   ├── Admin/
│   │   │   ├── Dashboard.php
│   │   │   ├── DonationList.php
│   │   │   ├── DonationVerify.php
│   │   │   ├── FinanceTable.php
│   │   │   ├── FinanceForm.php
│   │   │   ├── PostEditor.php
│   │   │   ├── EventEditor.php
│   │   │   ├── GalleryManager.php
│   │   │   ├── UserManager.php
│   │   │   └── SettingsForm.php
│   │   └── Public/
│   │       ├── PrayerTime.php
│   │       ├── DonationForm.php
│   │       └── EventList.php
│   ├── Models/
│   │   ├── User.php
│   │   ├── Donation.php
│   │   ├── DonationCategory.php
│   │   ├── FinanceRecord.php
│   │   ├── Post.php
│   │   ├── Event.php
│   │   ├── Gallery.php
│   │   ├── Committee.php
│   │   └── Setting.php
│   └── Services/
│       ├── PrayerTimeService.php
│       ├── FinanceService.php
│       └── SettingService.php
├── database/
│   ├── migrations/
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── RolePermissionSeeder.php
│       ├── UserSeeder.php
│       ├── SettingSeeder.php
│       └── DonationCategorySeeder.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── public.blade.php
│       │   └── admin.blade.php
│       ├── public/
│       │   ├── home.blade.php
│       │   ├── profile.blade.php
│       │   ├── prayer-time.blade.php
│       │   ├── events.blade.php
│       │   ├── posts/
│       │   ├── gallery.blade.php
│       │   ├── donation.blade.php
│       │   ├── finance.blade.php
│       │   └── contact.blade.php
│       └── admin/
│           ├── dashboard.blade.php
│           ├── donations/
│           ├── finance/
│           ├── posts/
│           ├── events/
│           ├── gallery/
│           ├── users/
│           └── settings/
└── routes/
    ├── web.php
    └── admin.php
```

## 3.3 Role & Permission Matrix

| Permission | Super Admin | Ketua | Sekretaris | Bendahara |
|-----------|:-----------:|:-----:|:----------:|:---------:|
| view dashboard | ✅ | ✅ | ✅ | ✅ |
| manage donations | ✅ | ✅ | ❌ | ✅ |
| approve donations | ✅ | ✅ | ❌ | ✅ |
| manage finance | ✅ | ✅ | ❌ | ✅ |
| export finance | ✅ | ✅ | ❌ | ✅ |
| manage posts | ✅ | ✅ | ✅ | ❌ |
| manage events | ✅ | ✅ | ✅ | ❌ |
| manage gallery | ✅ | ✅ | ✅ | ❌ |
| manage committee | ✅ | ✅ | ✅ | ❌ |
| manage users | ✅ | ❌ | ❌ | ❌ |
| manage settings | ✅ | ❌ | ❌ | ❌ |
| manage donation categories | ✅ | ❌ | ❌ | ✅ |

## 3.4 Flow Donasi Detail

```
[PUBLIC]
1. Donatur buka /donasi
2. Lihat QRIS + Rekening (dari settings)
3. Transfer via aplikasi bank/e-wallet masing-masing
4. Klik "Konfirmasi Donasi"
5. Isi form:
   - nama (opsional)
   - nominal
   - kategori donasi
   - upload bukti (opsional)
   - pesan (opsional)
6. Submit → status = PENDING
7. Donatur lihat pesan: "Terima kasih, donasi sedang diverifikasi"

[ADMIN - BENDAHARA]
1. Dashboard tampil badge "X donasi pending"
2. Buka Manajemen Donasi
3. Klik detail donasi → lihat bukti transfer
4. Pilih: APPROVE atau REJECT
   - Jika APPROVE:
     a. Status donasi → approved
     b. approved_by = user id bendahara
     c. approved_at = now()
     d. AUTO INSERT ke finance_records:
        - type = income
        - category = donasi
        - amount = nominal donasi
        - source = donation
        - donation_id = id donasi
        - description = "Donasi [kategori] dari [nama/Anonim]"
   - Jika REJECT:
     a. Status donasi → rejected
     b. Isi alasan reject (catatan internal)
```

## 3.5 Jadwal Sholat — Integrasi Aladhan API

```
Endpoint:
GET http://api.aladhan.com/v1/calendar/{year}/{month}
    ?latitude={lat}&longitude={lng}&method=20

method=20 = Kementerian Agama RI

Koordinat default (dari settings):
latitude  = -7.4833  (approx Nogosari, Boyolali)
longitude = 110.8167

Caching: response di-cache 24 jam per bulan
         untuk mengurangi API call

Data yang diambil:
- Fajr (Subuh)
- Dhuhr (Dzuhur)
- Asr (Ashar)
- Maghrib
- Isha (Isya)
```

## 3.6 Pengaturan Sistem (Settings Service)

```php
// Contoh penggunaan SettingService
Setting::get('masjid_name')           // "Masjid Sholikin"
Setting::get('masjid_latitude')       // "-7.4833"
Setting::get('show_finance_public')   // "true"
Setting::get('qris_image')            // "storage/qris/qris.png"
Setting::get('rekening_number')       // "1234567890"

// Settings dikelompokkan dalam group:
// - masjid (info umum)
// - location (koordinat, alamat)
// - donation (rekening, qris)
// - display (toggle publik/private)
// - contact (telepon, sosmed)
```

---

---

# 4. ERD — ENTITY RELATIONSHIP DIAGRAM {#erd}

## 4.1 Daftar Tabel

### users
```sql
CREATE TABLE users (
    id              BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100) NOT NULL,
    email           VARCHAR(150) UNIQUE NOT NULL,
    password        VARCHAR(255) NOT NULL,
    avatar          VARCHAR(255) NULL,
    is_active       BOOLEAN DEFAULT TRUE,
    remember_token  VARCHAR(100) NULL,
    created_at      TIMESTAMP NULL,
    updated_at      TIMESTAMP NULL
);
```

### donation_categories
```sql
CREATE TABLE donation_categories (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,       -- "Infak", "Sedekah", "Pembangunan"
    description TEXT NULL,
    is_active   BOOLEAN DEFAULT TRUE,
    created_by  BIGINT UNSIGNED NULL,
    created_at  TIMESTAMP NULL,
    updated_at  TIMESTAMP NULL,

    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);
```

### donations
```sql
CREATE TABLE donations (
    id                    BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    donation_category_id  BIGINT UNSIGNED NOT NULL,
    donor_name            VARCHAR(100) NULL,      -- NULL = Anonim
    amount                DECIMAL(15,2) NOT NULL,
    proof_image           VARCHAR(255) NULL,       -- path bukti transfer
    message               TEXT NULL,
    status                ENUM('pending','approved','rejected') DEFAULT 'pending',
    rejection_note        TEXT NULL,
    approved_by           BIGINT UNSIGNED NULL,
    approved_at           TIMESTAMP NULL,
    created_at            TIMESTAMP NULL,
    updated_at            TIMESTAMP NULL,

    FOREIGN KEY (donation_category_id) REFERENCES donation_categories(id),
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
);
```

### finance_records
```sql
CREATE TABLE finance_records (
    id            BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    type          ENUM('income','expense') NOT NULL,
    category      VARCHAR(100) NOT NULL,      -- "Donasi", "Operasional", "Renovasi", dll
    amount        DECIMAL(15,2) NOT NULL,
    source        ENUM('donation','manual') DEFAULT 'manual',
    donation_id   BIGINT UNSIGNED NULL,        -- FK jika source = donation
    description   TEXT NULL,
    transaction_date DATE NOT NULL,
    recorded_by   BIGINT UNSIGNED NOT NULL,
    created_at    TIMESTAMP NULL,
    updated_at    TIMESTAMP NULL,

    FOREIGN KEY (donation_id) REFERENCES donations(id) ON DELETE SET NULL,
    FOREIGN KEY (recorded_by) REFERENCES users(id)
);
```

### posts
```sql
CREATE TABLE posts (
    id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title        VARCHAR(255) NOT NULL,
    slug         VARCHAR(255) UNIQUE NOT NULL,
    content      LONGTEXT NOT NULL,
    excerpt      TEXT NULL,
    thumbnail    VARCHAR(255) NULL,
    category     ENUM('berita','pengumuman','kajian') DEFAULT 'berita',
    status       ENUM('draft','published') DEFAULT 'draft',
    published_at TIMESTAMP NULL,
    author_id    BIGINT UNSIGNED NOT NULL,
    created_at   TIMESTAMP NULL,
    updated_at   TIMESTAMP NULL,

    FOREIGN KEY (author_id) REFERENCES users(id)
);
```

### events
```sql
CREATE TABLE events (
    id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title        VARCHAR(255) NOT NULL,
    slug         VARCHAR(255) UNIQUE NOT NULL,
    description  LONGTEXT NULL,
    location     VARCHAR(255) NULL,
    category     VARCHAR(100) NULL,    -- "Kajian", "Pengajian", "Event"
    poster       VARCHAR(255) NULL,
    start_datetime DATETIME NOT NULL,
    end_datetime   DATETIME NULL,
    is_active    BOOLEAN DEFAULT TRUE,
    created_by   BIGINT UNSIGNED NOT NULL,
    created_at   TIMESTAMP NULL,
    updated_at   TIMESTAMP NULL,

    FOREIGN KEY (created_by) REFERENCES users(id)
);
```

### galleries
```sql
CREATE TABLE galleries (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(255) NOT NULL,
    description TEXT NULL,
    image_path  VARCHAR(255) NOT NULL,
    order       INT DEFAULT 0,
    is_active   BOOLEAN DEFAULT TRUE,
    uploaded_by BIGINT UNSIGNED NOT NULL,
    created_at  TIMESTAMP NULL,
    updated_at  TIMESTAMP NULL,

    FOREIGN KEY (uploaded_by) REFERENCES users(id)
);
```

### committees (pengurus)
```sql
CREATE TABLE committees (
    id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100) NOT NULL,
    position   VARCHAR(100) NOT NULL,     -- "Ketua DKM", "Sekretaris", dst
    photo      VARCHAR(255) NULL,
    order      INT DEFAULT 0,
    is_active  BOOLEAN DEFAULT TRUE,
    period     VARCHAR(50) NULL,          -- "2023-2026"
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### settings
```sql
CREATE TABLE settings (
    id         BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    group      VARCHAR(50) NOT NULL,      -- 'masjid','location','donation','display','contact'
    key        VARCHAR(100) UNIQUE NOT NULL,
    value      TEXT NULL,
    type       ENUM('text','textarea','boolean','image','number') DEFAULT 'text',
    label      VARCHAR(150) NULL,         -- label tampil di admin
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### Tabel Spatie Permission (otomatis)
```
roles
permissions
model_has_roles
model_has_permissions
role_has_permissions
```

## 4.2 Relasi Antar Tabel

```
users
  ├──< donations (approved_by)
  ├──< finance_records (recorded_by)
  ├──< posts (author_id)
  ├──< events (created_by)
  ├──< galleries (uploaded_by)
  ├──< donation_categories (created_by)
  └──< [via Spatie] roles & permissions

donation_categories
  └──< donations (donation_category_id)

donations
  └──< finance_records (donation_id)
       [1 donation approved = 1 finance_record income]
```

## 4.3 ERD Visual (Teks)

```
┌──────────────┐       ┌─────────────────────┐
│    users     │       │  donation_categories │
│──────────────│       │─────────────────────│
│ id (PK)      │       │ id (PK)             │
│ name         │       │ name                │
│ email        │       │ description         │
│ password     │       │ is_active           │
│ avatar       │◀──┐   │ created_by (FK)─────│──▶ users.id
│ is_active    │   │   └──────────┬──────────┘
└──────┬───────┘   │              │ 1
       │           │              │
       │ 1         │              ▼ N
       │           │   ┌──────────────────────┐
       ▼ N         │   │      donations        │
┌──────────────┐   │   │──────────────────────│
│    posts     │   │   │ id (PK)              │
│──────────────│   │   │ donation_category_id │
│ id (PK)      │   │   │ donor_name           │
│ title        │   │   │ amount               │
│ slug         │   │   │ proof_image          │
│ content      │   │   │ message              │
│ category     │   │   │ status               │
│ status       │   │   │ rejection_note       │
│ author_id ───│───┘   │ approved_by (FK)─────│──▶ users.id
└──────────────┘       │ approved_at          │
                       └──────────┬───────────┘
                                  │ 1
                                  │
                                  ▼ 1
                       ┌──────────────────────┐
                       │   finance_records     │
                       │──────────────────────│
                       │ id (PK)              │
                       │ type (income/expense)│
                       │ category             │
                       │ amount               │
                       │ source               │
                       │ donation_id (FK)     │
                       │ description          │
                       │ transaction_date     │
                       │ recorded_by (FK)─────│──▶ users.id
                       └──────────────────────┘

┌──────────────┐   ┌──────────────┐   ┌──────────────┐
│    events    │   │  galleries   │   │  committees  │
│──────────────│   │──────────────│   │──────────────│
│ id (PK)      │   │ id (PK)      │   │ id (PK)      │
│ title        │   │ title        │   │ name         │
│ description  │   │ description  │   │ position     │
│ location     │   │ image_path   │   │ photo        │
│ category     │   │ order        │   │ order        │
│ poster       │   │ is_active    │   │ period       │
│ start_datetime│  │ uploaded_by ─│──▶│ is_active    │
│ end_datetime │   └──────────────┘   └──────────────┘
│ created_by ──│──▶ users.id
└──────────────┘

┌──────────────────────────────┐
│           settings           │
│──────────────────────────────│
│ id (PK)                      │
│ group  (masjid/donation/...) │
│ key    (unique)              │
│ value                        │
│ type   (text/boolean/image)  │
│ label                        │
└──────────────────────────────┘
```

---

---

# 5. WIREFRAME — DESKRIPSI LAYOUT HALAMAN {#wireframe}

> Wireframe dideskripsikan dalam format layout teks. Implementasi visual menggunakan Tailwind CSS v4 + Livewire 3.

---

## 5.1 Layout Publik (public.blade.php)

```
┌────────────────────────────────────────────────────┐
│  NAVBAR                                            │
│  [Logo MasjidKu]  Beranda Profil Jadwal Kegiatan  │
│                   Berita Galeri Donasi Kontak      │
│                                        [☰ Mobile] │
└────────────────────────────────────────────────────┘
│                                                    │
│              @yield('content')                     │
│                                                    │
┌────────────────────────────────────────────────────┐
│  FOOTER                                            │
│  Nama Masjid | Alamat | Kontak                    │
│  Link cepat | © 2026 MasjidKu                     │
└────────────────────────────────────────────────────┘
```

---

## 5.2 Beranda (/)

```
┌─────────────────────────────────────────────────────┐
│  HERO SECTION                                       │
│  [Foto/Background Masjid]                           │
│  "Masjid Sholikin"                                  │
│  "Potronayan, Nogosari, Boyolali"                   │
│  [Tombol: Donasi Sekarang]  [Lihat Kegiatan]        │
└─────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────┐
│  JADWAL SHOLAT HARI INI                             │
│  Subuh    Dzuhur    Ashar    Maghrib    Isya         │
│  04:32    11:52     15:12    17:45      19:00        │
│                              ← BERIKUTNYA →         │
└─────────────────────────────────────────────────────┘

┌──────────────────┐ ┌──────────────────────────────┐
│ PENGUMUMAN       │ │ KEGIATAN MENDATANG           │
│ TERBARU          │ │                              │
│ • [Judul 1]      │ │ 📅 12 Jun — Kajian Tafsir   │
│ • [Judul 2]      │ │ 📅 15 Jun — Pengajian Rutin │
│ • [Judul 3]      │ │ 📅 20 Jun — Gotong Royong   │
│ [Lihat Semua]    │ │ [Lihat Semua]                │
└──────────────────┘ └──────────────────────────────┘

┌─────────────────────────────────────────────────────┐
│  DONASI BULAN INI  (jika setting aktif)             │
│  💰 Total: Rp 2.450.000                             │
│  [Ikut Berdonasi →]                                 │
└─────────────────────────────────────────────────────┘
```

---

## 5.3 Halaman Donasi (/donasi)

```
┌─────────────────────────────────────────────────────┐
│  CARA BERDONASI                                     │
│                                                     │
│  ┌──────────────────┐  ┌────────────────────────┐  │
│  │  QRIS STATIS     │  │  TRANSFER BANK         │  │
│  │  [Gambar QR]     │  │  Bank: BSI             │  │
│  │                  │  │  No. Rek: XXXXXXXXXX   │  │
│  │  Scan untuk      │  │  A/N: DKM Sholikin     │  │
│  │  berdonasi       │  │                        │  │
│  └──────────────────┘  └────────────────────────┘  │
│                                                     │
│  ⚠️ Setelah transfer, harap konfirmasi di bawah    │
└─────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────┐
│  FORM KONFIRMASI DONASI                             │
│                                                     │
│  Nama Donatur                                       │
│  [________________________] ☐ Anonim               │
│                                                     │
│  Nominal Donasi (Rp)                                │
│  [________________________]                         │
│                                                     │
│  Kategori Donasi                                    │
│  [Pilih Kategori ▼]  ← dari donation_categories    │
│                                                     │
│  Upload Bukti Transfer (opsional)                   │
│  [Pilih File]                                       │
│                                                     │
│  Pesan / Doa (opsional)                             │
│  [________________________________]                 │
│  [________________________________]                 │
│                                                     │
│  [     Kirim Konfirmasi     ]                       │
│                                                     │
│  ℹ️ Donasi akan diverifikasi dalam 1x24 jam        │
└─────────────────────────────────────────────────────┘
```

---

## 5.4 Laporan Keuangan Publik (/keuangan)

```
(Hanya tampil jika setting show_finance_public = true)

┌─────────────────────────────────────────────────────┐
│  LAPORAN KEUANGAN MASJID                            │
│  Transparansi Dana Umat                             │
│                                                     │
│  Filter: [Bulan ▼] [Tahun ▼]                       │
└─────────────────────────────────────────────────────┘

┌────────────┐ ┌────────────┐ ┌────────────────────┐
│ PEMASUKAN  │ │PENGELUARAN │ │ SALDO              │
│ Rp 5.2 Jt │ │ Rp 2.1 Jt │ │ Rp 3.1 Jt         │
└────────────┘ └────────────┘ └────────────────────┘

┌─────────────────────────────────────────────────────┐
│  RIWAYAT TRANSAKSI                                  │
│  Tanggal    Keterangan           Jenis   Nominal    │
│  01/06/26   Donasi Infak         Masuk   Rp 150.000 │
│  02/06/26   Listrik Masjid       Keluar  Rp 250.000 │
│  03/06/26   Donasi Pembangunan   Masuk   Rp 500.000 │
│  ...                                                │
└─────────────────────────────────────────────────────┘
```

---

## 5.5 Layout Admin (admin.blade.php)

```
┌────────────┬───────────────────────────────────────┐
│  SIDEBAR   │  HEADER                               │
│            │  [≡ Menu]    [User: Admin ▼]          │
│  Dashboard │───────────────────────────────────────│
│  ─────     │                                       │
│  Donasi    │                                       │
│  Keuangan  │         @yield('content')             │
│  ─────     │                                       │
│  Berita    │                                       │
│  Kegiatan  │                                       │
│  Galeri    │                                       │
│  ─────     │                                       │
│  Pengurus  │                                       │
│  Pengguna  │                                       │
│  ─────     │                                       │
│  Pengaturan│                                       │
└────────────┴───────────────────────────────────────┘
```

---

## 5.6 Admin Dashboard

```
┌─────────────────────────────────────────────────────┐
│  Selamat datang, [Nama Admin]                       │
│  Rabu, 3 Juni 2026                                  │
└─────────────────────────────────────────────────────┘

┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐
│ Donasi   │ │ Pending  │ │Pengeluar │ │  Saldo   │
│ Bulan Ini│ │  Donasi  │ │  an Bln  │ │   Kas    │
│ Rp 5.2Jt │ │    3     │ │ Rp 2.1Jt│ │ Rp 3.1Jt │
└──────────┘ └──────────┘ └──────────┘ └──────────┘

┌───────────────────────────┐ ┌────────────────────┐
│  GRAFIK DONASI 6 BULAN   │ │  DONASI PENDING    │
│                           │ │                    │
│  [Bar/Line Chart]         │ │  Ahmad - Rp150rb   │
│                           │ │  [Anonim] - Rp50rb │
│                           │ │  Siti - Rp200rb    │
│                           │ │  [Verifikasi →]    │
└───────────────────────────┘ └────────────────────┘
```

---

## 5.7 Admin — Manajemen Donasi

```
┌─────────────────────────────────────────────────────┐
│  MANAJEMEN DONASI                                   │
│                                                     │
│  Filter: [Semua Status ▼] [Semua Kategori ▼]       │
│          [Tanggal Mulai] [Tanggal Akhir]  [Filter] │
└─────────────────────────────────────────────────────┘

┌───┬────────┬──────────┬─────────┬────────┬────────┐
│ # │ Nama  │ Nominal  │Kategori │ Status │ Aksi   │
├───┼────────┼──────────┼─────────┼────────┼────────┤
│ 1 │ Ahmad  │ 150.000 │ Infak   │🟡Pndng│[Detail]│
│ 2 │ Anonim │  50.000 │ Sedekah │🟡Pndng│[Detail]│
│ 3 │ Siti   │ 200.000 │ Bangun  │🟢Aprvd│[Detail]│
│ 4 │ Budi   │  75.000 │ Infak   │🔴Rjctd│[Detail]│
└───┴────────┴──────────┴─────────┴────────┴────────┘

-- MODAL DETAIL DONASI --
┌─────────────────────────────────────────────────────┐
│  Detail Donasi #1                            [✕]   │
│  Nama     : Ahmad Fauzi                            │
│  Nominal  : Rp 150.000                             │
│  Kategori : Infak                                  │
│  Pesan    : "Semoga berkah untuk masjid"           │
│  Tanggal  : 01 Juni 2026 14:32                     │
│  Bukti    : [Lihat Gambar]                         │
│                                                     │
│  [✅ Approve]          [❌ Reject]                  │
└─────────────────────────────────────────────────────┘
```

---

## 5.8 Admin — Pengaturan

```
┌─────────────────────────────────────────────────────┐
│  PENGATURAN SISTEM                                  │
│  [Tab: Masjid] [Donasi] [Tampilan] [Kontak]        │
└─────────────────────────────────────────────────────┘

[TAB: MASJID]
  Nama Masjid        : [Masjid Sholikin          ]
  Deskripsi          : [________________________ ]
  Alamat             : [________________________ ]
  Latitude           : [-7.4833                  ]
  Longitude          : [110.8167                 ]
  Foto Masjid        : [Upload Gambar]

[TAB: DONASI]
  Nama Bank          : [BSI                      ]
  Nomor Rekening     : [________________________ ]
  Atas Nama          : [DKM Masjid Sholikin      ]
  Upload QRIS        : [Upload Gambar QRIS]

[TAB: TAMPILAN]
  Tampilkan laporan keuangan ke publik  [ON/OFF]
  Tampilkan total donasi di beranda     [ON/OFF]

[TAB: KONTAK]
  Nomor Telepon/WA   : [________________________ ]
  Facebook           : [________________________ ]
  Instagram          : [________________________ ]
  YouTube            : [________________________ ]

  [Simpan Pengaturan]
```

---

---

# LAMPIRAN

## A. Default Seeder — Settings

```php
// SettingSeeder.php
$settings = [
    // Group: masjid
    ['group'=>'masjid', 'key'=>'masjid_name',        'value'=>'Masjid Sholikin',              'type'=>'text',    'label'=>'Nama Masjid'],
    ['group'=>'masjid', 'key'=>'masjid_description', 'value'=>'Masjid Dusun Potronayan',       'type'=>'textarea','label'=>'Deskripsi'],
    ['group'=>'masjid', 'key'=>'masjid_address',     'value'=>'Potronayan, Nogosari, Boyolali','type'=>'textarea','label'=>'Alamat'],
    ['group'=>'masjid', 'key'=>'masjid_photo',       'value'=>null,                            'type'=>'image',   'label'=>'Foto Masjid'],

    // Group: location
    ['group'=>'location','key'=>'latitude',           'value'=>'-7.4833',                      'type'=>'text',    'label'=>'Latitude'],
    ['group'=>'location','key'=>'longitude',          'value'=>'110.8167',                     'type'=>'text',    'label'=>'Longitude'],

    // Group: donation
    ['group'=>'donation','key'=>'bank_name',          'value'=>'',                             'type'=>'text',    'label'=>'Nama Bank'],
    ['group'=>'donation','key'=>'rekening_number',    'value'=>'',                             'type'=>'text',    'label'=>'Nomor Rekening'],
    ['group'=>'donation','key'=>'rekening_name',      'value'=>'',                             'type'=>'text',    'label'=>'Atas Nama'],
    ['group'=>'donation','key'=>'qris_image',         'value'=>null,                           'type'=>'image',   'label'=>'Gambar QRIS'],

    // Group: display
    ['group'=>'display', 'key'=>'show_finance_public','value'=>'false',                        'type'=>'boolean', 'label'=>'Tampilkan Laporan ke Publik'],
    ['group'=>'display', 'key'=>'show_donation_total','value'=>'true',                         'type'=>'boolean', 'label'=>'Tampilkan Total Donasi di Beranda'],

    // Group: contact
    ['group'=>'contact', 'key'=>'phone',             'value'=>'',                              'type'=>'text',    'label'=>'Nomor Telepon/WA'],
    ['group'=>'contact', 'key'=>'facebook',          'value'=>'',                              'type'=>'text',    'label'=>'Facebook'],
    ['group'=>'contact', 'key'=>'instagram',         'value'=>'',                              'type'=>'text',    'label'=>'Instagram'],
    ['group'=>'contact', 'key'=>'youtube',           'value'=>'',                              'type'=>'text',    'label'=>'YouTube'],
];
```

## B. Default Seeder — Donation Categories

```php
// DonationCategorySeeder.php
$categories = [
    ['name'=>'Infak',              'description'=>'Infak umum masjid'],
    ['name'=>'Sedekah',            'description'=>'Sedekah untuk kegiatan sosial'],
    ['name'=>'Dana Pembangunan',   'description'=>'Untuk pembangunan & renovasi masjid'],
    ['name'=>'Operasional',        'description'=>'Listrik, air, kebersihan'],
    ['name'=>'Sosial',             'description'=>'Santunan, bantuan warga'],
];
```

## C. Default Roles & Permissions

```php
// RolePermissionSeeder.php
$permissions = [
    'view dashboard',
    'manage donations', 'approve donations',
    'manage finance', 'export finance',
    'manage posts', 'manage events',
    'manage gallery', 'manage committee',
    'manage users', 'manage settings',
    'manage donation categories',
];

$roles = [
    'super-admin' => $permissions, // semua
    'ketua'       => ['view dashboard','manage donations','approve donations',
                      'manage finance','export finance','manage posts',
                      'manage events','manage gallery','manage committee'],
    'sekretaris'  => ['view dashboard','manage posts',
                      'manage events','manage gallery','manage committee'],
    'bendahara'   => ['view dashboard','manage donations','approve donations',
                      'manage finance','export finance','manage donation categories'],
];
```

## D. Ringkasan Halaman

| Halaman | Route | Auth |
|---------|-------|------|
| Beranda | / | Public |
| Profil Masjid | /profil | Public |
| Jadwal Sholat | /jadwal-sholat | Public |
| Kegiatan | /kegiatan | Public |
| Detail Kegiatan | /kegiatan/{slug} | Public |
| Berita | /berita | Public |
| Detail Berita | /berita/{slug} | Public |
| Galeri | /galeri | Public |
| Donasi | /donasi | Public |
| Laporan Keuangan | /keuangan | Public (conditional) |
| Kontak | /kontak | Public |
| Admin Dashboard | /admin | Auth + Role |
| Admin Donasi | /admin/donasi | Auth + Role |
| Admin Kategori Donasi | /admin/kategori-donasi | Auth + Role |
| Admin Keuangan | /admin/keuangan | Auth + Role |
| Admin Berita | /admin/berita | Auth + Role |
| Admin Kegiatan | /admin/kegiatan | Auth + Role |
| Admin Galeri | /admin/galeri | Auth + Role |
| Admin Pengurus | /admin/pengurus | Auth + Role |
| Admin Pengguna | /admin/pengguna | Auth + Role |
| Admin Pengaturan | /admin/pengaturan | Auth + Role |

---

**Dokumen ini dibuat untuk proyek MasjidKu — Masjid Sholikin, Potronayan, Nogosari, Boyolali.**
**Versi 1.0 | Juni 2026 | Dikembangkan dengan Laravel 13 + Livewire 3**
