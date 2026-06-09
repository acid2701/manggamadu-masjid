<?php

namespace Database\Seeders;

use App\Models\DonationCategory;
use Illuminate\Database\Seeder;

class DonationCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Infak', 'description' => 'Infak umum masjid'],
            ['name' => 'Sedekah', 'description' => 'Sedekah untuk kegiatan sosial'],
            ['name' => 'Dana Pembangunan', 'description' => 'Untuk pembangunan & renovasi masjid'],
            ['name' => 'Operasional', 'description' => 'Listrik, air, kebersihan'],
            ['name' => 'Sosial', 'description' => 'Santunan, bantuan warga'],
        ];

        foreach ($categories as $category) {
            DonationCategory::firstOrCreate(
                ['name' => $category['name']],
                array_merge($category, ['is_active' => true]),
            );
        }
    }
}
