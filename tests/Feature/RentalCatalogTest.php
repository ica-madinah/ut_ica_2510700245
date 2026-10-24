<?php
namespace Tests\Feature;
use App\Models\Car;
use Database\Seeders\CarSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\{Route, Schema, DB};
use Tests\TestCase;
class RentalCatalogTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void { parent::setUp(); $this->seed(CarSeeder::class); }
    public function test_cars_schema_matches_the_assignment_exactly(): void
    {
        $this->assertSame(['id', 'brand', 'model', 'year', 'rent_price', 'image', 'transmission'], Schema::getColumnListing('cars'));
        $this->assertFalse(Schema::hasTable('services'));
        $this->assertFalse(Schema::hasTable('bookings'));
    }
    public function test_seeder_provides_eight_cars_and_local_images(): void
    {
        $this->assertDatabaseCount('cars', 8);
        foreach (Car::all() as $car) {
            $this->assertFileExists(public_path('assets/images/'.$car->image));
            $this->assertStringContainsString($car->brand, $car->description);
            $this->assertGreaterThan(0, (float) $car->rent_price);
        }
    }
    public function test_catalog_uses_blade_layout_and_local_bootstrap_assets(): void
    {
        foreach (['/', '/mobil'] as $url) {
            $this->get($url)->assertOk()->assertSee('Toyota Avanza')->assertSee('Honda Brio')->assertSee('Bootstrap/css/bootstrap.min.css', false)->assertSee('Bootstrap/js/bootstrap.bundle.min.js', false)->assertSee('assets/images/toyota-avanza.svg', false)->assertSee('card car-card', false);
        }
    }
    public function test_detail_page_uses_seeded_values_and_navigation(): void
    {
        $car = Car::where('model', 'Avanza')->firstOrFail();
        $this->get(route('cars.show', $car))->assertOk()->assertSee('Toyota Avanza')->assertSee('2023')->assertSee('Manual')->assertSee('Rp350.000')->assertSee($car->description)->assertSee('Kembali ke katalog');
    }
    public function test_unknown_car_is_not_found(): void { $this->get('/mobil/9999')->assertNotFound(); }
    public function test_search_filters_by_model(): void
    {
        $this->get('/mobil?q=Brio')->assertOk()->assertSee('Honda Brio')->assertDontSee('Toyota Avanza');
    }
    public function test_brand_filter(): void
    {
        $this->get('/mobil?brand=Toyota')->assertOk()->assertSee('Toyota Avanza')->assertSee('Toyota Innova')->assertDontSee('Honda Brio');
    }
    public function test_transmission_filter(): void
    {
        $this->get('/mobil?transmission=Manual')->assertOk()->assertSee('Toyota Avanza')->assertSee('Suzuki Ertiga')->assertDontSee('Toyota Innova');
    }
    public function test_price_and_year_sorting(): void
    {
        $this->get('/mobil?sort=price_low')->assertSeeInOrder(['Honda Brio', 'Toyota Avanza', 'Suzuki Ertiga']);
        $this->get('/mobil?sort=price_high')->assertSeeInOrder(['Toyota Fortuner', 'Honda HR-V', 'Toyota Innova']);
        $this->get('/mobil?sort=year')->assertOk()->assertSeeInOrder(['Toyota Innova', 'Mitsubishi Xpander']);
    }
    public function test_empty_state_and_invalid_filter(): void
    {
        $this->get('/mobil?q=TidakAdaMobilIni')->assertOk()->assertSee('Mobil belum ditemukan.');
        $this->get('/mobil?transmission=invalid')->assertRedirect()->assertSessionHasErrors('transmission');
        $this->get('/mobil?sort=invalid')->assertRedirect()->assertSessionHasErrors('sort');
    }
    public function test_all_web_routes_are_read_only(): void
    {
        foreach (Route::getRoutes() as $route) {
            $this->assertSame([], array_values(array_diff($route->methods(), ['GET', 'HEAD'])), $route->uri());
        }
    }
    public function test_no_admin_auth_booking_or_management_pages_exist(): void
    {
        foreach (['/admin','/admin/login','/admin/cars','/admin/cars/create','/login','/register','/booking','/mobil/create','/mobil/1/edit'] as $url) { $this->get($url)->assertNotFound(); }
    }
    public function test_write_methods_are_rejected_without_changing_cars(): void
    {
        $before = Car::orderBy('id')->get()->toArray();
        foreach (['POST', 'PUT', 'PATCH', 'DELETE'] as $method) {
            foreach (['/mobil', '/mobil/1'] as $url) { $this->call($method, $url, ['brand' => 'Diubah', 'model' => 'Data baru', 'rent_price' => 1])->assertStatus(405); }
        }
        $this->assertSame($before, Car::orderBy('id')->get()->toArray());
    }
    public function test_public_get_requests_only_read_car_data(): void
    {
        DB::enableQueryLog();
        foreach (['/', '/mobil?q=Toyota', '/mobil/1'] as $url) { $this->get($url)->assertOk(); }
        $queries = DB::getQueryLog();
        DB::disableQueryLog();
        foreach ($queries as $query) { $this->assertDoesNotMatchRegularExpression('/^\s*(insert|update|delete|replace|truncate)\b/i', $query['query']); }
        $this->assertDatabaseCount('cars', 8);
    }
    public function test_search_input_is_escaped(): void
    {
        $this->get('/mobil?q='.urlencode('<script>alert(1)</script>'))->assertOk()->assertDontSee('<script>alert(1)</script>', false)->assertSee('&lt;script&gt;', false);
    }
    public function test_seeder_can_run_again_without_duplicating_cars(): void
    {
        $this->seed(CarSeeder::class);
        $this->assertDatabaseCount('cars', 8);
    }
}
