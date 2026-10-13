<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'model',
        'year',
        'plate_number',
        'type',
        'seats',
        'price_per_day',
        'description',
        'image',
        'color',
        'transmission',
        'fuel_type',
        'status',
        'location',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getImageUrlAttribute(): string
    {
        // 1. Check the stored image field (supports jpg, jpeg, png, webp)
        if ($this->image) {
            $stored = public_path('assets/img/vehicles/' . $this->image);
            if (file_exists($stored)) {
                return asset('assets/img/vehicles/' . $this->image);
            }
        }

        // 2. Auto-map by name/brand/model — tries jpg, jpeg, png, webp extensions
        $search    = strtolower($this->name . ' ' . $this->brand . ' ' . $this->model);
        $nameMap   = [
            'camry'        => 'toyota-camry',
            'land cruiser' => 'land-cruiser',
            'landcruiser'  => 'land-cruiser',
            'hiace'        => 'hiace',
            'bmw'          => 'bmw-x5',
            'x5'           => 'bmw-x5',
            'navara'       => 'nissan-navara',
            'mercedes'     => 'mercedes-e',
            'e-class'      => 'mercedes-e',
            'coaster'      => 'coaster-bus',
            'subaru'       => 'subaru-forester',
            'forester'     => 'subaru-forester',
        ];
        $extensions = ['jpg', 'jpeg', 'png', 'webp'];

        foreach ($nameMap as $keyword => $base) {
            if (str_contains($search, $keyword)) {
                foreach ($extensions as $ext) {
                    $path = public_path('assets/img/vehicles/' . $base . '.' . $ext);
                    if (file_exists($path)) {
                        return asset('assets/img/vehicles/' . $base . '.' . $ext);
                    }
                }
            }
        }

        // 3. Fallback — inline placeholder (no file on disk)
        return 'data:image/svg+xml;base64,' . base64_encode(
            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 480">'
            . '<rect width="800" height="480" fill="#1a2030"/>'
            . '<text x="400" y="248" text-anchor="middle" font-family="Arial,sans-serif" font-size="20" fill="#C9A84C">No Image Available</text>'
            . '</svg>'
        );
    }

    public function isAvailableForDates(string $pickupDate, string $returnDate): bool
    {
        if ($this->status !== 'available') {
            return false;
        }

        $conflict = $this->bookings()
            ->whereIn('status', ['approved', 'active'])
            ->where(function ($q) use ($pickupDate, $returnDate) {
                $q->whereBetween('pickup_date', [$pickupDate, $returnDate])
                  ->orWhereBetween('return_date', [$pickupDate, $returnDate])
                  ->orWhere(function ($q2) use ($pickupDate, $returnDate) {
                      $q2->where('pickup_date', '<=', $pickupDate)
                         ->where('return_date', '>=', $returnDate);
                  });
            })->exists();

        return !$conflict;
    }
}
