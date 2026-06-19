<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RegionSeeder::class,
            ProductSeeder::class,
            DistributorSeeder::class,
            OutletSeeder::class,
            CsvOutletSeeder::class,
            ShippingContactSeeder::class,
            ArticleSeeder::class,
            SettingSeeder::class,
            MarketingTemplateSeeder::class,
        ]);
    }
}
