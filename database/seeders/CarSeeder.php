<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class CarSeeder extends Seeder
{
    public function run(): void
    {
        $cars = [
            ['Toyota', 'Avanza', 2023, 350000, 'toyota-avanza.svg', 'Manual'],
            ['Toyota', 'Innova', 2024, 650000, 'toyota-innova.svg', 'Automatic'],
            ['Honda', 'Brio', 2023, 300000, 'honda-brio.svg', 'Automatic'],
            ['Mitsubishi', 'Xpander', 2024, 450000, 'mitsubishi-xpander.svg', 'Automatic'],
            ['Suzuki', 'Ertiga', 2022, 375000, 'suzuki-ertiga.svg', 'Manual'],
            ['Daihatsu', 'Terios', 2023, 425000, 'daihatsu-terios.svg', 'Automatic'],
            ['Honda', 'HR-V', 2024, 700000, 'honda-hrv.svg', 'Automatic'],
            ['Toyota', 'Fortuner', 2024, 950000, 'toyota-fortuner.svg', 'Automatic'],
        ];
        // Seeder adalah satu-satunya sumber pengelolaan data katalog.
        // ID tetap membuat perubahan model, tahun, harga, dan gambar tersinkron.
        $records = [];
        foreach ($cars as $index => [$brand, $model, $year, $rent_price, $image, $transmission]) {
            $records[] = ['id' => $index + 1] + compact('brand', 'model', 'year', 'rent_price', 'image', 'transmission');
        }
        DB::transaction(function () use ($records) {
            DB::table('cars')->upsert($records, ['id'], ['brand', 'model', 'year', 'rent_price', 'image', 'transmission']);
            DB::table('cars')->whereNotIn('id', array_column($records, 'id'))->delete();
        });
    }
}
