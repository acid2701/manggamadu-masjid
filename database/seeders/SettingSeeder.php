<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Group: masjid
            ['group' => 'masjid', 'key' => 'masjid_name', 'value' => 'Masjid Sholikin', 'type' => 'text', 'label' => 'Nama Masjid'],
            ['group' => 'masjid', 'key' => 'masjid_description', 'value' => 'Masjid Dusun Potronayan, Nogosari, Boyolali. Tempat ibadah dan pusat kegiatan sosial masyarakat.', 'type' => 'textarea', 'label' => 'Deskripsi'],
            ['group' => 'masjid', 'key' => 'masjid_address', 'value' => 'Dusun Potronayan, Desa Potronayan, Kecamatan Nogosari, Kabupaten Boyolali, Jawa Tengah', 'type' => 'textarea', 'label' => 'Alamat'],
            ['group' => 'masjid', 'key' => 'masjid_photo', 'value' => null, 'type' => 'image', 'label' => 'Foto Masjid'],

            // Group: location
            ['group' => 'location', 'key' => 'latitude', 'value' => '-7.4833', 'type' => 'text', 'label' => 'Latitude'],
            ['group' => 'location', 'key' => 'longitude', 'value' => '110.8167', 'type' => 'text', 'label' => 'Longitude'],

            // Group: donation
            ['group' => 'donation', 'key' => 'bank_name', 'value' => '', 'type' => 'text', 'label' => 'Nama Bank'],
            ['group' => 'donation', 'key' => 'rekening_number', 'value' => '', 'type' => 'text', 'label' => 'Nomor Rekening'],
            ['group' => 'donation', 'key' => 'rekening_name', 'value' => '', 'type' => 'text', 'label' => 'Atas Nama Rekening'],
            ['group' => 'donation', 'key' => 'qris_image', 'value' => null, 'type' => 'image', 'label' => 'Gambar QRIS'],

            // Group: display
            ['group' => 'display', 'key' => 'show_finance_public', 'value' => 'false', 'type' => 'boolean', 'label' => 'Tampilkan Laporan Keuangan ke Publik'],
            ['group' => 'display', 'key' => 'show_donation_total', 'value' => 'true', 'type' => 'boolean', 'label' => 'Tampilkan Total Donasi di Beranda'],

            // Group: contact
            ['group' => 'contact', 'key' => 'phone', 'value' => '', 'type' => 'text', 'label' => 'Nomor Telepon / WhatsApp'],
            ['group' => 'contact', 'key' => 'email', 'value' => '', 'type' => 'text', 'label' => 'Email'],
            ['group' => 'contact', 'key' => 'facebook', 'value' => '', 'type' => 'text', 'label' => 'Facebook'],
            ['group' => 'contact', 'key' => 'instagram', 'value' => '', 'type' => 'text', 'label' => 'Instagram'],
            ['group' => 'contact', 'key' => 'youtube', 'value' => '', 'type' => 'text', 'label' => 'YouTube'],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting,
            );
        }
    }
}
