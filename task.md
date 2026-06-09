# 🕌 MASJIDKU — MASTER TASK TRACKER

> **Project**: Website Masjid Sholikin — Potronayan, Nogosari, Boyolali
> **Stack**: Laravel 13 · Livewire 4 · Flux UI v2 · Tailwind CSS v4 · Alpine.js
> **Design Reference**: `DESIGN.md` (MongoDB-inspired design system)
> **Documentation**: `DOKUMENTATION.md` (BRD, PRD, SDD, ERD, Wireframe)

---

## Status Legend

| Icon | Status |
|------|--------|
| `[ ]` | Belum dikerjakan |
| `[/]` | Sedang dikerjakan |
| `[x]` | Selesai |
| `[-]` | Dibatalkan / Skip |

---

## Dependency Map

```
Phase 1 ──► Phase 2 ──► Phase 3 ──► Phase 4 ──► Phase 5 ──► Phase 6 ──► Phase 7
Foundation   Design      Public      Admin       Admin       Testing     Deploy
             System      Pages       Core        Extended    & QA
```

---
---

# PHASE 1 — FOUNDATION & INFRASTRUCTURE 🏗️

> **Goal**: Database schema, models, auth, RBAC, dan service layer.
> **Dependency**: Tidak ada (phase pertama).
> **Acceptance**: `php artisan migrate:fresh --seed` sukses, semua model & service berjalan.

---

## 1.1 Package & Dependencies

- [x] Install `spatie/laravel-permission`
- [x] Publish config & migration Spatie Permission
- [x] Verifikasi package terdaftar di service provider

## 1.2 Database — Migrations

- [x] Modifikasi migration `users` — primary key `id` (uuid), tambah kolom `avatar` (nullable string), `is_active` (boolean default true)
- [x] Buat migration `create_donation_categories_table`
  - Kolom: `id` (uuid), `name`, `description`, `is_active`, `created_by` (FK uuid → users), timestamps
- [x] Buat migration `create_donations_table`
  - Kolom: `id` (uuid), `donation_category_id` (FK uuid), `donor_name` (nullable), `amount` (decimal 15,2), `proof_image` (nullable), `message` (nullable), `status` (enum: pending/approved/rejected), `rejection_note` (nullable), `approved_by` (FK uuid → users, nullable), `approved_at` (nullable), timestamps
- [x] Buat migration `create_finance_records_table`
  - Kolom: `id` (uuid), `type` (enum: income/expense), `category`, `amount` (decimal 15,2), `source` (enum: donation/manual), `donation_id` (FK uuid nullable), `description`, `transaction_date` (date), `recorded_by` (FK uuid → users), timestamps
- [x] Buat migration `create_posts_table`
  - Kolom: `id` (uuid), `title`, `slug` (unique), `content` (longtext), `excerpt`, `thumbnail`, `category` (enum: berita/pengumuman/kajian), `status` (enum: draft/published), `published_at`, `author_id` (FK uuid → users), timestamps
- [x] Buat migration `create_events_table`
  - Kolom: `id` (uuid), `title`, `slug` (unique), `description` (longtext), `location`, `category`, `poster`, `start_datetime`, `end_datetime`, `is_active`, `created_by` (FK uuid → users), timestamps
- [x] Buat migration `create_galleries_table`
  - Kolom: `id` (uuid), `title`, `description`, `image_path`, `order` (int), `is_active`, `uploaded_by` (FK uuid → users), timestamps
- [x] Buat migration `create_committees_table`
  - Kolom: `id` (uuid), `name`, `position`, `photo`, `order` (int), `is_active`, `period`, timestamps
- [x] Buat migration `create_settings_table`
  - Kolom: `id` (uuid), `group`, `key` (unique), `value` (text nullable), `type` (enum: text/textarea/boolean/image/number), `label`, timestamps
- [x] Jalankan `php artisan migrate` — pastikan SEMUA migration sukses

## 1.3 Models, Factories & Relationships

- [x] Buat model `DonationCategory` + factory
  - Fillable: name, description, is_active, created_by
  - Relasi: `belongsTo(User)`, `hasMany(Donation)`
- [x] Buat model `Donation` + factory
  - Fillable: donation_category_id, donor_name, amount, proof_image, message, status, rejection_note, approved_by, approved_at
  - Relasi: `belongsTo(DonationCategory)`, `belongsTo(User, 'approved_by')`, `hasOne(FinanceRecord)`
  - Casts: amount → decimal, approved_at → datetime, status → enum/string
- [x] Buat model `FinanceRecord` + factory
  - Fillable: type, category, amount, source, donation_id, description, transaction_date, recorded_by
  - Relasi: `belongsTo(Donation)`, `belongsTo(User, 'recorded_by')`
  - Casts: amount → decimal, transaction_date → date
- [x] Buat model `Post` + factory
  - Fillable: title, slug, content, excerpt, thumbnail, category, status, published_at, author_id
  - Relasi: `belongsTo(User, 'author_id')`
  - Casts: published_at → datetime
  - Scope: `published()`, `byCategory()`
- [x] Buat model `Event` + factory
  - Fillable: title, slug, description, location, category, poster, start_datetime, end_datetime, is_active, created_by
  - Relasi: `belongsTo(User, 'created_by')`
  - Casts: start_datetime → datetime, end_datetime → datetime
  - Scope: `upcoming()`, `active()`
- [x] Buat model `Gallery` + factory
  - Fillable: title, description, image_path, order, is_active, uploaded_by
  - Relasi: `belongsTo(User, 'uploaded_by')`
  - Scope: `active()`, `ordered()`
- [x] Buat model `Committee` + factory
  - Fillable: name, position, photo, order, is_active, period
  - Scope: `active()`, `ordered()`
- [x] Buat model `Setting` (tanpa factory)
  - Fillable: group, key, value, type, label
  - Static helper: `Setting::get('key')`, `Setting::set('key', 'value')`
- [x] Update model `User`
  - Tambah trait `HasRoles` (Spatie)
  - Tambah fillable: avatar, is_active
  - Tambah relasi: `hasMany(Post, 'author_id')`, `hasMany(Event, 'created_by')`, `hasMany(Gallery, 'uploaded_by')`, `hasMany(FinanceRecord, 'recorded_by')`

## 1.4 Seeders

- [x] Buat `RolePermissionSeeder`
  - 12 permissions: view dashboard, manage donations, approve donations, manage finance, export finance, manage posts, manage events, manage gallery, manage committee, manage users, manage settings, manage donation categories
  - 4 roles dengan permission matrix sesuai SDD 3.3:
    - `super-admin` → semua permission
    - `ketua` → dashboard, donations, finance, posts, events, gallery, committee
    - `sekretaris` → dashboard, posts, events, gallery, committee
    - `bendahara` → dashboard, donations, finance, donation categories
- [x] Buat `SettingSeeder` — 17 default settings (5 group)
  - Group `masjid`: masjid_name, masjid_description, masjid_address, masjid_photo
  - Group `location`: latitude (-7.4833), longitude (110.8167)
  - Group `donation`: bank_name, rekening_number, rekening_name, qris_image
  - Group `display`: show_finance_public (false), show_donation_total (true)
  - Group `contact`: phone, email, facebook, instagram, youtube
- [x] Buat `DonationCategorySeeder` — 5 kategori: Infak, Sedekah, Dana Pembangunan, Operasional, Sosial
- [x] Buat `UserSeeder` — 1 super-admin (email: admin@masjidsholikin.com)
- [x] Update `DatabaseSeeder` — panggil urutan: RolePermission → User → Setting → DonationCategory
- [x] Jalankan `php artisan db:seed` — verifikasi semua data masuk

## 1.5 Services

- [x] Buat `App\Services\SettingService`
  - `get(string $key, mixed $default = null): mixed`
  - `set(string $key, mixed $value): void`
  - `getByGroup(string $group): Collection`
  - Cache layer untuk mengurangi query
- [x] Buat `App\Services\PrayerTimeService`
  - Fetch dari Aladhan API: `GET /v1/calendar/{year}/{month}?latitude=&longitude=&method=20`
  - Cache response 24 jam per bulan
  - Return: array jadwal sholat (Fajr, Dhuhr, Asr, Maghrib, Isha)
  - Method: `getToday()`, `getMonthly(int $year, int $month)`
- [x] Buat `App\Services\FinanceService`
  - `getMonthlyIncome(int $year, int $month): float`
  - `getMonthlyExpense(int $year, int $month): float`
  - `getBalance(): float`
  - `getMonthlySummary(int $year, int $month): array`

## 1.6 Authentication & Middleware

- [x] Konfigurasi Fortify — login & logout (register disabled untuk publik)
- [x] Setup middleware role check (Spatie `role` middleware atau custom)
- [x] Definisikan route group `/admin` dengan middleware `auth` + `verified`
- [ ] Buat halaman login custom (sesuai design system) — dikerjakan di Phase 2
- [x] Test login → redirect ke /admin
- [x] Test akses /admin tanpa login → redirect ke login

## 1.7 ✅ Phase 1 — Checklist Verifikasi

- [x] `php artisan migrate:fresh --seed` — sukses tanpa error (15 migrations, 4 seeders)
- [x] Cek di DB: semua tabel terbuat, data seeder masuk
- [x] Test model relationship via tinker
- [x] Test `SettingService::get('masjid_name')` → "Masjid Sholikin" ✅
- [ ] Test `PrayerTimeService::getToday()` → return data sholat (butuh network call)
- [x] Test login dengan user super-admin (role: super-admin confirmed)
- [x] `vendor/bin/pint --dirty --format agent` — clean
- [x] Unit tests untuk models & services — PASS (38 tests, 55 assertions)

---
---

# PHASE 2 — DESIGN SYSTEM & LAYOUTS 🎨

> **Goal**: Terjemahkan DESIGN.md tokens ke Tailwind, buat layout publik & admin + komponen reusable.
> **Dependency**: Phase 1 selesai (SettingService## 2.1 Tailwind CSS v4 — Design Tokens

- [x] Setup `@theme` block di `resources/css/app.css` dengan custom tokens:
  - [x] **Colors** — semua warna dari DESIGN.md:
    - Brand: green (#00ed64), green-dark (#00684a), green-mid (#00a35c), green-soft (#c3f0d2)
    - Teal: deep (#001e2b), mid (#003d4f), teal-mid (#00684a)
    - Accent: purple (#7b3ff2), orange (#fa6e39), pink (#f06bb8), blue (#3d4f9f)
    - Surface: canvas (#fff), canvas-dark (#001e2b), surface (#f9fbfa), surface-soft (#f4f7f6), surface-feature (#e3fcef)
    - Hairline: default (#e1e5e8), soft (#eceff1), strong (#c1ccd6), dark (#1c2d38)
    - Text: ink (#001e2b), charcoal (#1c2d38), slate (#3d4f5b), steel (#5c6c7a), stone (#7c8c9a), muted (#a8b3bc)
    - On-dark: white (#fff), muted (#a8b3bc)
  - [x] **Spacing** — xxs(4px), xs(8px), sm(12px), md(16px), lg(20px), xl(24px), xxl(32px), xxxl(40px), section-sm(48px), section(64px), section-lg(96px), hero(120px)
  - [x] **Border Radius** — xs(4px), sm(6px), md(8px), lg(12px), xl(16px), xxl(24px), full(9999px)
  - [x] **Shadows** — 4 level elevasi sesuai DESIGN.md
  - [x] **Font Family** — Inter (fallback Euclid Circular A) + Source Code Pro (mono)
  - [x] **Font Size** — hero-display(72px) sampai micro(12px), sesuai hierarchy
- [x] Import Google Fonts (Inter, Source Code Pro) via `<link>` atau `@import`
- [x] Buat CSS utility classes untuk komponen dasar (`.btn-primary`, `.btn-secondary`, `.card-base`, dll)

## 2.2 Layout Publik — `layouts/public.blade.php`

- [x] Buat file `resources/views/layouts/public.blade.php`
- [x] **Navbar (sticky top)**
  - [x] Logo + nama masjid (dari `SettingService::get('masjid_name')`)
  - [x] Menu items: Beranda, Profil, Jadwal Sholat, Kegiatan, Berita, Galeri, Donasi, Kontak
  - [x] Active state pada menu sesuai halaman aktif
  - [x] CTA button "Donasi" hijau (brand-green pill)
  - [x] Mobile: hamburger menu → slide-in drawer (Alpine.js `x-data`, `x-show`, `x-transition`)
  - [x] Sticky navbar dengan border-bottom hairline
- [x] **Footer (dark teal)**
  - [x] Background: brand-teal-deep
  - [x] 3-4 kolom: Info Masjid, Link Cepat, Kontak, Sosial Media
  - [x] Data dinamis dari SettingService (alamat, telepon, sosmed links)
  - [x] Copyright: "© 2026 Masjid Sholikin"
  - [x] Responsive: collapse ke accordion di mobile
- [x] **Head section**
  - [x] Dynamic `<title>` per halaman (`@yield('title')`)
  - [x] Meta description (`@yield('meta_description')`)
  - [x] Open Graph tags (`@yield('og_image')`)
  - [x] Favicon
  - [x] Vite CSS & JS includes

## 2.3 Layout Admin — `layouts/admin.blade.php`

- [x] Buat file `resources/views/layouts/admin.blade.php`
- [x] **Sidebar (kiri, fixed)**
  - [x] Logo / nama app "MasjidKu Admin"
  - [x] Menu navigasi (icon + label):
    - Dashboard
    - ── separator ──
    - Donasi (permission: manage donations)
    - Kategori Donasi (permission: manage donation categories)
    - Keuangan (permission: manage finance)
    - ── separator ──
    - Berita (permission: manage posts)
    - Kegiatan (permission: manage events)
    - Galeri (permission: manage gallery)
    - ── separator ──
    - Pengurus (permission: manage committee)
    - Pengguna (permission: manage users)
    - ── separator ──
    - Pengaturan (permission: manage settings)
  - [x] Active state indicator (brand-green left border + bg tint)
  - [x] Menu visibility berdasarkan `@can` / Spatie permission
  - [x] Collapsible di tablet/mobile (overlay sidebar)
- [x] **Header bar (top)**
  - [x] Hamburger toggle sidebar (mobile)
  - [x] Breadcrumb (`@yield('breadcrumb')`)
  - [x] User info: nama + avatar + dropdown (Profil, Logout)
- [x] **Content area**
  - [x] `@yield('content')` dengan padding consistent

## 2.4 Reusable Blade Components

- [x] `<x-button>` — variant: primary, secondary, on-dark, ghost, link, danger
  - Props: type, href, variant, size(sm/md/lg), icon, disabled, wire:click
- [x] `<x-card>` — variant: base, feature, dark
  - Props: variant, padding, class
- [x] `<x-badge>` — variant: green, green-soft, purple, orange, popular, danger
  - Props: variant, label
- [x] `<x-input>` — type: text, email, password, number, textarea, select, file, toggle
  - Props: name, label, placeholder, value, error, required
- [x] `<x-modal>` — Alpine.js powered modal dialog
  - Props: name, title, maxWidth
- [x] `<x-stat-card>` — kartu statistik untuk dashboard
  - Props: title, value, icon, color, trend
- [x] `<x-alert>` — variant: success, warning, error, info
  - Props: variant, message, dismissible
- [x] `<x-empty-state>` — placeholder saat data kosong
  - Props: icon, title, description, action
- [x] `<x-table>` — styled table wrapper
- [x] `<x-pagination>` — styled pagination wrapper

## 2.5 ✅ Phase 2 — Checklist Verifikasi

- [x] Buka `/` di browser — layout publik tampil (navbar + footer)
- [x] Responsive check: mobile (375px), tablet (768px), desktop (1280px)
- [x] Hamburger menu berfungsi di mobile
- [x] Buka `/admin` — layout admin tampil (sidebar + header + content)
- [x] Sidebar menu hanya tampil sesuai permission user
- [x] Warna, typography, spacing sesuai DESIGN.md tokens
- [x] `npm run build` — sukses tanpa error
- [x] `vendor/bin/pint --dirty --format agent` — clean

---
---

# PHASE 3 — PUBLIC PAGES 🌐

> **Goal**: Semua halaman publik yang bisa diakses tanpa login.
> **Dependency**: Phase 2 selesai (layout & komponen siap).
> **Acceptance**: 11 halaman publik berfungsi, responsive, data dinamis dari DB.

---

## 3.1 Routes — Public Web

- [ ] Definisikan semua public routes di `routes/web.php`:

```
GET  /                    → HomeController@index          (name: home)
GET  /profil              → ProfileController@index       (name: profile)
GET  /jadwal-sholat       → PrayerTimeController@index    (name: prayer-time)
GET  /kegiatan            → EventController@index         (name: events.index)
GET  /kegiatan/{slug}     → EventController@show          (name: events.show)
GET  /berita              → PostController@index           (name: posts.index)
GET  /berita/{slug}       → PostController@show            (name: posts.show)
GET  /galeri              → GalleryController@index        (name: gallery)
GET  /donasi              → DonationController@index       (name: donation)
GET  /keuangan            → FinanceController@index        (name: finance)
GET  /kontak              → ContactController@index        (name: contact)
```

- [ ] Buat controller-controller di `App\Http\Controllers\Public\`

## 3.2 Beranda — `/`

- [ ] Buat `Public\HomeController` + view `public/home.blade.php`
- [ ] **Hero Section**
  - [ ] Background foto masjid (dari settings `masjid_photo`) atau gradient default
  - [ ] Nama masjid (h1) + lokasi (subtitle)
  - [ ] 2 CTA buttons: "Donasi Sekarang" (primary) + "Lihat Kegiatan" (secondary)
  - [ ] Dark overlay agar teks terbaca di atas foto
- [ ] **Jadwal Sholat Hari Ini**
  - [ ] Livewire `Public\PrayerTime` component
  - [ ] 5 kartu waktu sholat: Subuh, Dzuhur, Ashar, Maghrib, Isya
  - [ ] Highlight waktu sholat berikutnya (berdasarkan waktu sekarang)
  - [ ] Data dari `PrayerTimeService::getToday()`
- [ ] **Pengumuman Terbaru**
  - [ ] 3 item dari `Post::published()->where('category', 'pengumuman')->latest()->take(3)`
  - [ ] Tampil: judul, tanggal, excerpt
  - [ ] Link "Lihat Semua →" ke `/berita?kategori=pengumuman`
- [ ] **Kegiatan Mendatang**
  - [ ] 3 item dari `Event::upcoming()->active()->take(3)`
  - [ ] Tampil: ikon kalender, tanggal, judul, lokasi
  - [ ] Link "Lihat Semua →" ke `/kegiatan`
- [ ] **Total Donasi Bulan Ini** (conditional)
  - [ ] Hanya tampil jika `Setting::get('show_donation_total') == 'true'`
  - [ ] Total dari `FinanceService::getMonthlyIncome()`
  - [ ] CTA: "Ikut Berdonasi →" ke `/donasi`

## 3.3 Profil Masjid — `/profil`

- [ ] Buat `Public\ProfileController` + view `public/profile.blade.php`
- [ ] Section sejarah masjid (dari settings atau content statis awal)
- [ ] Section visi & misi
- [ ] **Struktur Pengurus**
  - [ ] Data dari `Committee::active()->ordered()->get()`
  - [ ] Grid card: foto, nama, jabatan, periode
- [ ] Foto masjid (dari settings)

## 3.4 Jadwal Sholat — `/jadwal-sholat`

- [ ] Buat `Public\PrayerTimeController` + view `public/prayer-time.blade.php`
- [ ] **Livewire component** jadwal bulanan:
  - [ ] Tabel: Tanggal | Subuh | Dzuhur | Ashar | Maghrib | Isya
  - [ ] Data dari `PrayerTimeService::getMonthly(year, month)`
  - [ ] Highlight baris hari ini (bg accent)
  - [ ] Navigasi bulan: ◀ Bulan Sebelumnya | Bulan Berikutnya ▶
  - [ ] Tampil nama bulan + tahun di header
- [ ] Info sumber data: "Data dari Kementerian Agama RI via Aladhan API"

## 3.5 Kegiatan — `/kegiatan`

- [ ] Buat `Public\EventController` + view `public/events.blade.php`
- [ ] **Livewire `Public\EventList` component**
  - [ ] List kegiatan aktif & mendatang
  - [ ] Card per event: poster (jika ada), judul, tanggal/waktu, lokasi, kategori badge
  - [ ] Filter by kategori: Semua, Kajian, Pengajian, Event (pill tabs)
  - [ ] Pagination
- [ ] **Detail Kegiatan** — `/kegiatan/{slug}`
  - [ ] Buat view `public/event-detail.blade.php`
  - [ ] Poster (full width top)
  - [ ] Judul, deskripsi lengkap, tanggal & waktu (start — end), lokasi
  - [ ] Tombol "Kembali ke Daftar Kegiatan"

## 3.6 Berita & Pengumuman — `/berita`

- [ ] Buat `Public\PostController` + views
- [ ] **List Berita** — `public/posts/index.blade.php`
  - [ ] Card per artikel: thumbnail, judul, excerpt, tanggal, kategori badge
  - [ ] Filter by kategori: Semua, Berita, Pengumuman, Kajian (pill tabs)
  - [ ] Pagination (12 per halaman)
  - [ ] Urutan: terbaru di atas (by published_at DESC)
- [ ] **Detail Berita** — `public/posts/show.blade.php`
  - [ ] Thumbnail (hero image)
  - [ ] Judul (h1), kategori badge, tanggal publish, author
  - [ ] Konten lengkap (rendered HTML/markdown)
  - [ ] Navigasi: "← Kembali ke Berita"

## 3.7 Galeri — `/galeri`

- [ ] Buat `Public\GalleryController` + view `public/gallery.blade.php`
- [ ] **Grid foto** — responsive: 3 kolom desktop, 2 tablet, 1 mobile
  - [ ] Data dari `Gallery::active()->ordered()->get()`
  - [ ] Setiap item: gambar, judul, keterangan (overlay on hover)
- [ ] **Lightbox preview** (Alpine.js)
  - [ ] Klik foto → modal fullscreen dengan gambar besar
  - [ ] Navigasi: prev / next di dalam lightbox
  - [ ] Close: klik backdrop atau tombol X
  - [ ] Keyboard: ESC close, ← → navigasi

## 3.8 Halaman Donasi — `/donasi`

- [ ] Buat `Public\DonationController` + view `public/donation.blade.php`
- [ ] **Section Info Pembayaran**
  - [ ] Kartu QRIS: gambar dari `Setting::get('qris_image')` — full size readable
  - [ ] Kartu Rekening Bank:
    - Nama bank dari `Setting::get('bank_name')`
    - Nomor rekening dari `Setting::get('rekening_number')` + tombol copy
    - Atas nama dari `Setting::get('rekening_name')`
  - [ ] Alert: "Setelah transfer, harap konfirmasi di bawah ini"
- [ ] **Livewire `Public\DonationForm` component**
  - [ ] Input nama donatur (text) + checkbox "Donasi sebagai Anonim"
    - Jika anonim → disable field nama, set null
  - [ ] Input nominal donasi (number, format Rupiah)
  - [ ] Select kategori donasi (dari `DonationCategory::where('is_active', true)->get()`)
  - [ ] File upload bukti transfer (opsional, validasi: image, max 2MB)
  - [ ] Textarea pesan / doa (opsional, max 500 karakter)
  - [ ] Tombol "Kirim Konfirmasi" (primary)
  - [ ] **On submit**:
    - Validasi server-side semua field
    - Simpan ke `donations` table (status = pending)
    - Upload bukti ke `storage/donations/`
    - Tampilkan success message: "Terima kasih! Donasi Anda sedang diverifikasi bendahara."
    - Reset form
  - [ ] Info: "Donasi akan diverifikasi dalam 1×24 jam"

## 3.9 Laporan Keuangan Publik — `/keuangan`

- [ ] Buat `Public\FinanceController` + view `public/finance.blade.php`
- [ ] **Conditional access**: hanya jika `Setting::get('show_finance_public') == 'true'`
  - Jika false → tampilkan halaman "Laporan keuangan tidak tersedia untuk publik"
- [ ] **Ringkasan** (3 stat cards):
  - Total Pemasukan bulan ini
  - Total Pengeluaran bulan ini
  - Saldo
- [ ] **Livewire component: tabel riwayat transaksi**
  - [ ] Kolom: Tanggal, Keterangan, Jenis (Masuk/Keluar + badge warna), Nominal
  - [ ] Filter: dropdown bulan & tahun
  - [ ] Pagination
  - [ ] Tidak tampilkan detail sensitif (siapa yang approve, dll)

## 3.10 Kontak — `/kontak`

- [ ] Buat `Public\ContactController` + view `public/contact.blade.php`
- [ ] **Info Kontak**
  - [ ] Alamat lengkap (dari settings)
  - [ ] Nomor telepon/WA (dari settings) + link `tel:` dan `https://wa.me/`
  - [ ] Email (jika ada di settings)
- [ ] **Embed Google Maps**
  - [ ] Iframe Google Maps dengan koordinat dari settings (latitude, longitude)
  - [ ] Responsive: full-width, fixed height
- [ ] **Sosial Media Links**
  - [ ] Icon + link ke Facebook, Instagram, YouTube (dari settings)
  - [ ] Hanya tampil jika value tidak kosong

## 3.11 ✅ Phase 3 — Checklist Verifikasi

- [ ] Semua 11 route publik return HTTP 200
- [ ] Responsive test di 3 breakpoint (375px, 768px, 1280px)
- [ ] Form donasi: submit → data masuk ke DB dengan status pending
- [ ] Upload bukti transfer → file tersimpan di storage
- [ ] Jadwal sholat tampil data benar dari Aladhan API
- [ ] Pengumuman & kegiatan tampil dari DB
- [ ] Laporan keuangan: conditional render berfungsi
- [ ] Lightbox galeri berfungsi (open, navigate, close)
- [ ] Google Maps embed tampil di halaman kontak
- [ ] Navigasi antar halaman via navbar berfungsi
- [ ] `vendor/bin/pint --dirty --format agent` — clean
- [ ] Feature tests untuk setiap public route — PASS
- [ ] Livewire component tests (DonationForm, PrayerTime, EventList) — PASS

---
---

# PHASE 4 — ADMIN PANEL: CORE MODULES 🔧

> **Goal**: Dashboard, manajemen donasi (approval flow), keuangan + export, manajemen konten.
> **Dependency**: Phase 3 selesai (data publik sudah bisa di-manage).
> **Acceptance**: Admin bisa kelola donasi, keuangan, berita, kegiatan, galeri. Approval flow berjalan.

---

## 4.1 Routes — Admin

- [ ] Buat route group admin di `routes/web.php` (atau `routes/admin.php`):

```
Prefix: /admin | Middleware: auth + verified

GET    /admin                    → Dashboard        (name: admin.dashboard)
GET    /admin/donasi             → DonationList      (name: admin.donations.index)
GET    /admin/donasi/{id}        → DonationDetail    (name: admin.donations.show)
GET    /admin/kategori-donasi    → DonationCategories (name: admin.donation-categories.index)
GET    /admin/keuangan           → FinanceTable      (name: admin.finance.index)
GET    /admin/keuangan/create    → FinanceForm       (name: admin.finance.create)
GET    /admin/berita             → PostList          (name: admin.posts.index)
GET    /admin/berita/create      → PostEditor        (name: admin.posts.create)
GET    /admin/berita/{id}/edit   → PostEditor        (name: admin.posts.edit)
GET    /admin/kegiatan           → EventList         (name: admin.events.index)
GET    /admin/kegiatan/create    → EventEditor       (name: admin.events.create)
GET    /admin/kegiatan/{id}/edit → EventEditor       (name: admin.events.edit)
GET    /admin/galeri             → GalleryManager    (name: admin.gallery.index)
GET    /admin/pengurus           → CommitteeManager  (name: admin.committee.index)
GET    /admin/pengguna           → UserManager       (name: admin.users.index)
GET    /admin/pengaturan         → SettingsForm      (name: admin.settings.index)
```

## 4.2 Admin Dashboard — `/admin`

- [ ] Buat Livewire `Admin\Dashboard` component + view
- [ ] **Header**: "Selamat datang, [nama]" + tanggal hari ini
- [ ] **4 Stat Cards** (baris atas):
  - [ ] 💰 Donasi Bulan Ini — total amount donasi approved bulan ini
  - [ ] ⏳ Donasi Pending — count donasi status=pending (badge merah jika > 0)
  - [ ] 📤 Pengeluaran Bulan Ini — total expense bulan ini
  - [ ] 💵 Saldo Kas — total income - total expense (all time)
- [ ] **Grafik Donasi 6 Bulan** (card kiri besar)
  - [ ] Bar/line chart — data donasi approved per bulan (6 bulan terakhir)
  - [ ] Implementasi: Alpine.js + Chart.js atau simple CSS bars
- [ ] **Donasi Pending Terbaru** (card kanan)
  - [ ] List 5 donasi pending terbaru: nama, nominal, waktu
  - [ ] Link "Verifikasi →" ke detail donasi
  - [ ] Link "Lihat Semua →" ke `/admin/donasi?status=pending`

## 4.3 Manajemen Donasi — `/admin/donasi`

- [ ] Buat Livewire `Admin\DonationList` component
  - [ ] **Tabel donasi**:
    - Kolom: #, Nama Donatur, Nominal (format Rp), Kategori, Status (badge), Tanggal, Aksi
    - Sort by: tanggal (default DESC), nominal
  - [ ] **Filter bar**:
    - Select status: Semua / Pending / Approved / Rejected
    - Select kategori donasi
    - Date range: tanggal mulai — tanggal akhir
    - Search: nama donatur
    - Tombol "Filter" + "Reset"
  - [ ] **Status badges**:
    - 🟡 Pending → badge-orange
    - 🟢 Approved → badge-green
    - 🔴 Rejected → badge danger
  - [ ] Pagination
  - [ ] Tombol "Detail" per row → buka modal/halaman detail

- [ ] Buat Livewire `Admin\DonationVerify` component (modal detail)
  - [ ] **Tampilan detail**:
    - Nama donatur (atau "Anonim")
    - Nominal (format Rupiah)
    - Kategori donasi
    - Pesan / doa
    - Tanggal submit
    - Preview bukti transfer (image lightbox)
    - Status saat ini
  - [ ] **Aksi Approve** (permission: approve donations):
    - [ ] Klik "Approve" → konfirmasi dialog
    - [ ] Update donation: status=approved, approved_by=auth user, approved_at=now
    - [ ] **AUTO INSERT** ke `finance_records`:
      - type = 'income'
      - category = 'Donasi'
      - amount = donation amount
      - source = 'donation'
      - donation_id = donation id
      - description = "Donasi [kategori] dari [nama/Anonim]"
      - transaction_date = today
      - recorded_by = auth user
    - [ ] Flash success message
    - [ ] Refresh list
  - [ ] **Aksi Reject** (permission: approve donations):
    - [ ] Klik "Reject" → muncul textarea "Alasan penolakan"
    - [ ] Update donation: status=rejected, rejection_note=[input]
    - [ ] Flash message
    - [ ] Refresh list

## 4.4 Kategori Donasi — `/admin/kategori-donasi`

- [ ] Buat Livewire `Admin\DonationCategoryManager` component
  - [ ] Tabel kategori: nama, deskripsi, status aktif, jumlah donasi, aksi
  - [ ] **Create**: modal form — nama + deskripsi, validasi nama unique
  - [ ] **Edit**: modal form — update nama, deskripsi
  - [ ] **Toggle Active**: switch aktif/nonaktif (nonaktif = tidak tampil di form donasi publik)
  - [ ] **Delete**: hanya jika tidak ada donasi terkait, otherwise soft deactivate
  - [ ] Permission guard: `manage donation categories`

## 4.5 Laporan Keuangan — `/admin/keuangan`

- [ ] Buat Livewire `Admin\FinanceTable` component
  - [ ] **Ringkasan** (3 stat cards filtered):
    - Total Pemasukan (filtered period)
    - Total Pengeluaran (filtered period)
    - Saldo (filtered period)
  - [ ] **Tabel finance_records**:
    - Kolom: Tanggal, Kategori, Keterangan, Jenis (badge income/expense), Sumber (badge donation/manual), Nominal
    - Sort by tanggal DESC
  - [ ] **Filter**:
    - Bulan & tahun
    - Type: income / expense / semua
    - Source: donation / manual / semua
  - [ ] Pagination
  - [ ] Tombol "Tambah Transaksi" → ke form

- [ ] Buat Livewire `Admin\FinanceForm` component
  - [ ] Form input transaksi manual:
    - Select type: income / expense
    - Input category (text atau select predefined)
    - Input amount (number)
    - Input description (textarea)
    - Input transaction_date (date picker)
  - [ ] Validasi server-side
  - [ ] Simpan ke finance_records (source=manual, recorded_by=auth user)
  - [ ] Redirect ke list setelah simpan

- [ ] **Export**
  - [ ] Tombol "Export PDF" — generate PDF laporan keuangan (filter aktif)
    - Install & gunakan package DomPDF atau Snappy
  - [ ] Tombol "Export Excel" — generate spreadsheet
    - Install & gunakan Maatwebsite/Excel atau simple CSV download
  - [ ] Permission guard: `export finance`

- [ ] **Toggle Publik**
  - [ ] Switch "Tampilkan ke Publik" → update `Setting::set('show_finance_public', value)`
  - [ ] Langsung berlaku di halaman `/keuangan` publik

## 4.6 Manajemen Berita — `/admin/berita`

- [ ] Buat Livewire `Admin\PostEditor` component
  - [ ] **List view**:
    - Tabel: judul, kategori (badge), status (draft/published badge), tanggal, author, aksi
    - Filter: kategori, status
    - Pagination
  - [ ] **Create / Edit form**:
    - Input title → auto-generate slug (editable)
    - Select category: berita / pengumuman / kajian
    - Textarea / rich text editor untuk content
    - Textarea excerpt (auto dari content jika kosong)
    - File upload thumbnail (image, max 2MB)
    - Select status: draft / published
    - Saat publish → set published_at = now (jika belum pernah set)
    - author_id = auth user (otomatis)
  - [ ] **Delete**: konfirmasi dialog → soft delete atau hard delete
  - [ ] Permission guard: `manage posts`

## 4.7 Manajemen Kegiatan — `/admin/kegiatan`

- [ ] Buat Livewire `Admin\EventEditor` component
  - [ ] **List view**:
    - Tabel: judul, kategori, tanggal mulai, lokasi, status aktif, aksi
    - Filter: kategori, status aktif
    - Pagination
  - [ ] **Create / Edit form**:
    - Input title → auto-generate slug
    - Textarea description
    - Input location
    - Select/input category (Kajian, Pengajian, Event, dll)
    - File upload poster (image, max 2MB)
    - Datetime picker: start_datetime (required), end_datetime (optional)
    - Toggle is_active
  - [ ] **Delete**: konfirmasi → hapus
  - [ ] Permission guard: `manage events`

## 4.8 Manajemen Galeri — `/admin/galeri`

- [ ] Buat Livewire `Admin\GalleryManager` component
  - [ ] **Grid view** (bukan tabel):
    - Thumbnail foto + judul + status
    - Drag-and-drop reorder (update field `order`) — atau tombol ↑↓
  - [ ] **Upload baru**:
    - Multiple file upload (validasi image, max 2MB per file)
    - Input judul + keterangan per foto
  - [ ] **Edit**: update judul, keterangan
  - [ ] **Toggle active**: aktif/nonaktif foto
  - [ ] **Delete**: konfirmasi → hapus file dari storage + record dari DB
  - [ ] Permission guard: `manage gallery`

## 4.9 ✅ Phase 4 — Checklist Verifikasi

- [ ] Dashboard statistik akurat & real-time
- [ ] **Donasi flow end-to-end**:
  1. Donatur submit form di `/donasi` → status pending
  2. Admin lihat di dashboard (badge pending)
  3. Admin buka detail → lihat bukti
  4. Approve → status approved + finance_record income otomatis terbuat
  5. Reject → status rejected + rejection_note tersimpan
- [ ] CRUD berita berfungsi — draft & publish, tampil di publik
- [ ] CRUD kegiatan berfungsi — tampil di publik
- [ ] CRUD galeri berfungsi — foto tampil di publik
- [ ] Export PDF & Excel menghasilkan file yang benar
- [ ] Permission check:
  - Bendahara: ✅ donasi, keuangan | ❌ berita, kegiatan, galeri
  - Sekretaris: ✅ berita, kegiatan, galeri | ❌ donasi, keuangan
- [ ] `vendor/bin/pint --dirty --format agent` — clean
- [ ] Feature tests semua admin modules — PASS
- [ ] Livewire component tests — PASS

---
---

# PHASE 5 — ADMIN: PENGURUS, USERS & SETTINGS ⚙️

> **Goal**: CRUD pengurus masjid, manajemen user + role assignment, pengaturan sistem.
> **Dependency**: Phase 4 selesai.
> **Acceptance**: Super admin bisa kelola user, roles, dan semua settings.

---

## 5.1 Manajemen Pengurus — `/admin/pengurus`

- [ ] Buat Livewire `Admin\CommitteeManager` component
  - [ ] **List view**: grid atau tabel — foto, nama, jabatan, periode, urutan, status, aksi
  - [ ] **Create form**: nama, jabatan (text), foto upload, urutan (number), periode (text, e.g. "2023-2026"), is_active
  - [ ] **Edit form**: update semua field
  - [ ] **Reorder**: drag-and-drop atau tombol ↑↓ untuk ubah urutan tampil
  - [ ] **Toggle active**: aktif/nonaktif
  - [ ] **Delete**: konfirmasi → hapus (+ hapus file foto)
  - [ ] Permission guard: `manage committee`
  - [ ] Data ini tampil di `/profil` halaman publik

## 5.2 Manajemen Pengguna — `/admin/pengguna`

- [ ] Buat Livewire `Admin\UserManager` component
  - [ ] **Akses**: HANYA `super-admin` (permission: `manage users`)
  - [ ] **List view**: tabel — nama, email, role (badge), status aktif, last login, aksi
  - [ ] **Create user**:
    - Input: nama, email, password (min 8 karakter), avatar upload (opsional)
    - Select role: super-admin / ketua / sekretaris / bendahara
    - Validasi: email unique
  - [ ] **Edit user**:
    - Update nama, email, avatar
    - Change role (assignment ulang)
    - Reset password (opsional — field kosong = tidak ubah)
    - Toggle is_active
  - [ ] **Proteksi**:
    - [ ] Tidak bisa hapus diri sendiri
    - [ ] Tidak bisa ubah role diri sendiri
    - [ ] Tidak bisa nonaktifkan diri sendiri
    - [ ] Minimal 1 super-admin harus ada (tidak bisa hapus/downgrade terakhir)
  - [ ] **Delete**: konfirmasi → hapus user (cascade atau restrict?)

## 5.3 Pengaturan Sistem — `/admin/pengaturan`

- [ ] Buat Livewire `Admin\SettingsForm` component
  - [ ] **Akses**: HANYA `super-admin` (permission: `manage settings`)
  - [ ] **Tab-based form** (sesuai wireframe 5.8):

  ### Tab Masjid
  - [ ] Nama Masjid (text input)
  - [ ] Deskripsi (textarea)
  - [ ] Alamat (textarea)
  - [ ] Latitude (text input)
  - [ ] Longitude (text input)
  - [ ] Foto Masjid (file upload — preview existing + replace)

  ### Tab Donasi
  - [ ] Nama Bank (text input)
  - [ ] Nomor Rekening (text input)
  - [ ] Atas Nama (text input)
  - [ ] Upload QRIS (file upload — preview existing + replace)

  ### Tab Tampilan
  - [ ] Toggle: Tampilkan laporan keuangan ke publik (boolean)
  - [ ] Toggle: Tampilkan total donasi di beranda (boolean)

  ### Tab Kontak
  - [ ] Nomor Telepon/WA (text input)
  - [ ] Facebook URL (text input)
  - [ ] Instagram URL (text input)
  - [ ] YouTube URL (text input)

- [ ] **Save action**: simpan per group → update tabel settings
- [ ] **File uploads**: simpan ke `storage/settings/`, update value di settings record
- [ ] **Validasi**: URL format untuk sosmed, coordinate format untuk lat/lng
- [ ] **Cache clear**: setelah update settings → clear setting cache

## 5.4 ✅ Phase 5 — Checklist Verifikasi

- [ ] CRUD pengurus berfungsi → data tampil benar di `/profil`
- [ ] CRUD user berfungsi → role assignment benar
- [ ] Proteksi user: tidak bisa hapus/edit diri sendiri, minimal 1 super-admin
- [ ] Settings tersimpan → langsung berlaku di halaman publik:
  - Update nama masjid → navbar publik berubah
  - Update QRIS → halaman donasi berubah
  - Toggle keuangan publik → `/keuangan` berubah
- [ ] Upload foto (masjid, QRIS, pengurus) berfungsi + preview
- [ ] Hanya super-admin yang bisa akses `/admin/pengguna` dan `/admin/pengaturan`
- [ ] `vendor/bin/pint --dirty --format agent` — clean
- [ ] Feature tests — PASS

---
---

# PHASE 6 — TESTING & QUALITY ASSURANCE ✅

> **Goal**: Comprehensive testing — pastikan semua fitur stabil dan terlindungi dari regresi.
> **Dependency**: Phase 5 selesai (semua fitur sudah diimplementasi).
> **Acceptance**: Semua test pass, coverage memadai untuk critical paths.

---

## 6.1 Unit Tests

- [ ] `SettingServiceTest` — get, set, default value, cache behavior, getByGroup
- [ ] `PrayerTimeServiceTest` — API response parsing, caching, error handling (API down)
- [ ] `FinanceServiceTest` — monthly income/expense/balance calculation
- [ ] Model scope tests: `Post::published()`, `Event::upcoming()`, `Gallery::active()`, `Committee::ordered()`
- [ ] Model relationship tests: user→posts, donation→financeRecord, dll
- [ ] Model cast tests: datetime, decimal, enum

## 6.2 Feature Tests — Public Routes

- [ ] Test GET `/` → 200, contains nama masjid
- [ ] Test GET `/profil` → 200
- [ ] Test GET `/jadwal-sholat` → 200
- [ ] Test GET `/kegiatan` → 200
- [ ] Test GET `/kegiatan/{slug}` → 200 (existing) / 404 (not found)
- [ ] Test GET `/berita` → 200
- [ ] Test GET `/berita/{slug}` → 200 (published) / 404 (draft or not found)
- [ ] Test GET `/galeri` → 200
- [ ] Test GET `/donasi` → 200
- [ ] Test POST donasi form → creates donation with status=pending
- [ ] Test GET `/keuangan` → 200 (when setting on) / redirect/403 (when setting off)
- [ ] Test GET `/kontak` → 200

## 6.3 Feature Tests — Auth & Authorization

- [ ] Test GET `/admin` tanpa login → redirect ke login
- [ ] Test GET `/admin` dengan login → 200
- [ ] Test role access:
  - [ ] Bendahara akses `/admin/donasi` → 200
  - [ ] Bendahara akses `/admin/berita` → 403
  - [ ] Sekretaris akses `/admin/berita` → 200
  - [ ] Sekretaris akses `/admin/donasi` → 403
  - [ ] Super admin akses semua → 200
  - [ ] Ketua tidak bisa akses `/admin/pengguna` → 403
  - [ ] Ketua tidak bisa akses `/admin/pengaturan` → 403

## 6.4 Feature Tests — Donation Approval Flow

- [ ] Test: submit donasi publik → status pending
- [ ] Test: approve donasi → status approved + finance_record created
- [ ] Test: reject donasi → status rejected + rejection_note saved
- [ ] Test: approve donasi → finance_record amount matches donation amount
- [ ] Test: approve donasi → finance_record source = 'donation', type = 'income'
- [ ] Test: non-bendahara tidak bisa approve/reject

## 6.5 Feature Tests — CRUD Admin

- [ ] Test CRUD posts (create, read, update, delete)
- [ ] Test CRUD events
- [ ] Test CRUD gallery (upload, edit, delete + file cleanup)
- [ ] Test CRUD committees
- [ ] Test CRUD users (create, assign role, toggle active, delete)
- [ ] Test CRUD donation categories (create, toggle, delete protection)

## 6.6 Livewire Component Tests

- [ ] `Public\DonationForm` — validation errors, successful submit, anonim toggle, file upload
- [ ] `Public\PrayerTime` — renders 5 prayer times, highlights next prayer
- [ ] `Public\EventList` — filter by category, pagination
- [ ] `Admin\Dashboard` — renders stats, chart data, pending list
- [ ] `Admin\DonationList` — filter, search, pagination, status badges
- [ ] `Admin\DonationVerify` — approve action, reject action
- [ ] `Admin\FinanceTable` — filter, pagination, summary calculation
- [ ] `Admin\SettingsForm` — save per tab, file upload, toggle

## 6.7 ✅ Phase 6 — Checklist Verifikasi

- [ ] `php artisan test --compact` — **SEMUA TEST PASS**
- [ ] Tidak ada test yang di-skip tanpa alasan
- [ ] Critical paths tercakup: donasi flow, auth, role-based access
- [ ] `vendor/bin/pint --dirty --format agent` — clean
- [ ] Review test output — no warnings, no deprecation notices

---
---

# PHASE 7 — OPTIMIZATION, SEO & DEPLOYMENT 🚀

> **Goal**: Polish, optimize performa, SEO, security, dan persiapan deploy production.
> **Dependency**: Phase 6 selesai (semua test pass).
> **Acceptance**: Lighthouse score baik, security hardened, siap deploy.

---

## 7.1 Performance Optimization

- [ ] Audit & fix N+1 queries (gunakan `preventLazyLoading` di AppServiceProvider)
- [ ] Eager loading di semua controller: `with()` relationships yang dipakai di view
- [ ] Cache layer di SettingService (cache all settings, invalidate on update)
- [ ] Cache jadwal sholat — sudah 24 jam di PrayerTimeService, verifikasi
- [ ] Optimize image uploads — resize ke max dimension + compress quality
- [ ] Minify production assets: `npm run build`
- [ ] Database indexing — index pada kolom yang sering di-filter:
  - `donations`: status, donation_category_id, created_at
  - `finance_records`: type, transaction_date
  - `posts`: status, category, published_at
  - `events`: is_active, start_datetime

## 7.2 SEO

- [ ] Setiap halaman publik punya:
  - [ ] `<title>` yang deskriptif (e.g. "Jadwal Sholat — Masjid Sholikin Boyolali")
  - [ ] `<meta name="description">` yang relevan
  - [ ] `<meta property="og:title">`, `og:description`, `og:image`
- [ ] Semantic HTML: `<article>` untuk berita, `<section>` untuk blocks, `<nav>` untuk navigasi
- [ ] Heading hierarchy: 1 `<h1>` per halaman
- [ ] Generate `sitemap.xml` (package atau manual)
- [ ] Setup `robots.txt` — allow public, disallow /admin
- [ ] Structured data (JSON-LD) untuk mosque (LocalBusiness/PlaceOfWorship)

## 7.3 Security Hardening

- [ ] CSRF protection — default Laravel, verifikasi semua form
- [ ] XSS prevention — escape semua user input di view (`{{ }}` bukan `{!! !!}` kecuali perlu)
- [ ] File upload validation:
  - [ ] Tipe file (hanya image: jpg, png, webp)
  - [ ] Max size (2MB)
  - [ ] Sanitize filename
- [ ] Rate limiting pada:
  - [ ] Form donasi: max 5 per menit per IP
  - [ ] Login: max 5 attempt per menit
- [ ] Secure headers (via middleware atau .htaccess):
  - X-Content-Type-Options: nosniff
  - X-Frame-Options: DENY
  - X-XSS-Protection: 1; mode=block
- [ ] HTTPS enforcement di production

## 7.4 Accessibility & Responsive

- [ ] Semua interactive elements punya unique `id`
- [ ] Form labels terhubung dengan input (`for` attribute)
- [ ] Alt text pada semua gambar
- [ ] Focus states visible pada keyboard navigation
- [ ] Touch targets minimal 44px pada mobile
- [ ] Test responsive: 320px, 375px, 480px, 768px, 1024px, 1280px
- [ ] QRIS image readable & zoomable di mobile
- [ ] Cross-browser: Chrome, Firefox, Safari, Edge

## 7.5 Deployment

- [ ] Setup `.env` production:
  - APP_ENV=production, APP_DEBUG=false
  - Database MySQL credentials
  - APP_URL=https://domain.com
- [ ] Laravel optimization commands:
  - [ ] `php artisan config:cache`
  - [ ] `php artisan route:cache`
  - [ ] `php artisan view:cache`
  - [ ] `php artisan event:cache`
  - [ ] `php artisan optimize`
- [ ] Frontend build: `npm run build`
- [ ] Storage link: `php artisan storage:link`
- [ ] Database setup:
  - [ ] Jalankan migrations di production
  - [ ] Jalankan seeders: roles, permissions, settings, categories, admin user
- [ ] Backup strategy: daily DB backup (cron atau hosting feature)
- [ ] Monitoring: setup error logging (Laravel log atau Sentry/Bugsnag free tier)

## 7.6 ✅ Phase 7 — Checklist Verifikasi

- [ ] Lighthouse audit:
  - Performance > 90
  - SEO > 90
  - Accessibility > 80
  - Best Practices > 90
- [ ] Semua halaman load < 3 detik
- [ ] Form donasi end-to-end berfungsi di production
- [ ] Admin panel fully functional di production
- [ ] File uploads berfungsi di production
- [ ] HTTPS aktif
- [ ] Backup strategy terdokumentasi & ditest
- [ ] Handover documentation untuk pengurus masjid

---
---

# 📊 PROGRESS SUMMARY

| Phase | Nama | Tasks | Status |
|:-----:|------|:-----:|:------:|
| 1 | Foundation & Infrastructure | ~30 | ✅ Selesai |
| 2 | Design System & Layouts | ~25 | ✅ Selesai |
| 3 | Public Pages | ~40 | ⬜ Belum mulai |
| 4 | Admin Panel — Core | ~35 | ⬜ Belum mulai |
| 5 | Admin — Pengurus, Users, Settings | ~20 | ⬜ Belum mulai |
| 6 | Testing & QA | ~25 | ⬜ Belum mulai |
| 7 | Optimization & Deployment | ~25 | ⬜ Belum mulai |
| | **TOTAL** | **~200** | |

---

> **Last Updated**: 2026-06-09
> **Author**: Developer MasjidKu
