<?php
namespace App\Http\Controllers;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class CarController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate(['q' => ['nullable', 'string', 'max:80'], 'brand' => ['nullable', 'string', 'max:80'], 'transmission' => ['nullable', Rule::in(Car::TRANSMISSIONS)], 'sort' => ['nullable', Rule::in(['recommended', 'price_low', 'price_high', 'year'])]]);
        $query = Car::query()->when($filters['q'] ?? null, function ($query, $search) { $query->where(fn ($q) => $q->where('brand', 'like', '%'.$search.'%')->orWhere('model', 'like', '%'.$search.'%')); })
            ->when($filters['brand'] ?? null, fn ($q, $brand) => $q->where('brand', $brand))
            ->when($filters['transmission'] ?? null, fn ($q, $value) => $q->where('transmission', $value));
        match ($filters['sort'] ?? 'recommended') {
            'price_low' => $query->orderBy('rent_price'),
            'price_high' => $query->orderByDesc('rent_price'),
            'year' => $query->orderByDesc('year'),
            default => $query->orderBy('id'),
        };
        return view('pages.cars', ['cars' => $query->orderBy('id')->paginate(8)->withQueryString(), 'brands' => Car::select('brand')->distinct()->orderBy('brand')->pluck('brand'), 'totalCars' => Car::count(), 'minPrice' => Car::min('rent_price'), 'filters' => $filters]);
    }
    public function show(Car $car)
    {
        return view('pages.detail', ['car' => $car, 'others' => Car::where('id', '!=', $car->id)->orderBy('id')->limit(3)->get()]);
    }
}
