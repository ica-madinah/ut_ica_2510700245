<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Car extends Model
{
    public $timestamps = false;
    public const TRANSMISSIONS = ['Manual', 'Automatic'];
    protected $fillable = ['brand', 'model', 'year', 'rent_price', 'image', 'transmission'];
    protected function casts(): array { return ['year' => 'integer', 'rent_price' => 'decimal:2']; }
    public function getNameAttribute(): string { return $this->brand.' '.$this->model; }
    public function getDescriptionAttribute(): string
    {
        return $this->name.' tahun '.$this->year.' dengan transmisi '.$this->transmission.'. Pilihan rental dengan tarif mulai Rp'.number_format((float) $this->rent_price, 0, ',', '.').' per hari. Hubungi pihak rental untuk membicarakan tanggal, durasi, dan ketentuan sewa.';
    }
    public function getImageUrlAttribute(): string { return asset('assets/images/'.$this->image); }
    public function getWhatsappUrlAttribute(): ?string
    {
        $number = preg_replace('/\D/', '', config('rental.whatsapp'));
        return $number ? 'https://wa.me/'.$number.'?text='.rawurlencode('Halo, saya ingin menanyakan rental '.$this->name.' tahun '.$this->year.'. Apakah tersedia untuk tanggal ... selama ... hari?') : null;
    }
}
