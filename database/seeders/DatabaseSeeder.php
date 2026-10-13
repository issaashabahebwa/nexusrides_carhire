<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin user ──────────────────────────────────────────────────────────
        $admin = User::firstOrCreate(
            ['email' => 'admin@nexusrides.ug'],
            [
                'name'      => 'NexusRides Admin',
                'phone'     => '+256 700 000 001',
                'address'   => 'Kampala, Uganda',
                'id_number' => 'ADMIN001',
                'role'      => 'admin',
                'password'  => Hash::make('password'),
            ]
        );

        // ── Demo customer ───────────────────────────────────────────────────────
        $customer = User::firstOrCreate(
            ['email' => 'customer@nexusrides.ug'],
            [
                'name'      => 'John Mukasa',
                'phone'     => '+256 770 123 456',
                'address'   => 'Ntinda, Kampala',
                'id_number' => 'CM9000000012XY',
                'role'      => 'customer',
                'password'  => Hash::make('password'),
            ]
        );

        // ── Vehicles ─────────────────────────────────────────────────────────────
        $vehicles = [
            [
                'name'          => 'Toyota Camry XLE',
                'brand'         => 'Toyota',
                'model'         => 'Camry XLE',
                'year'          => 2022,
                'plate_number'  => 'UAA 001T',
                'type'          => 'sedan',
                'seats'         => 5,
                'price_per_day' => 120000,
                'color'         => 'Pearl White',
                'transmission'  => 'automatic',
                'fuel_type'     => 'Petrol',
                'status'        => 'available',
                'location'      => 'Kampala',
                'description'   => 'Comfortable executive sedan perfect for business trips and city travel.',
            ],
            [
                'name'          => 'Toyota Land Cruiser V8',
                'brand'         => 'Toyota',
                'model'         => 'Land Cruiser V8',
                'year'          => 2021,
                'plate_number'  => 'UAB 202T',
                'type'          => 'suv',
                'seats'         => 7,
                'price_per_day' => 350000,
                'color'         => 'Midnight Black',
                'transmission'  => 'automatic',
                'fuel_type'     => 'Diesel',
                'status'        => 'available',
                'location'      => 'Kampala',
                'description'   => 'Powerful luxury SUV ideal for safaris and long-distance travel.',
            ],
            [
                'name'          => 'Toyota Hiace Minibus',
                'brand'         => 'Toyota',
                'model'         => 'Hiace',
                'year'          => 2020,
                'plate_number'  => 'UAC 303H',
                'type'          => 'van',
                'seats'         => 14,
                'price_per_day' => 200000,
                'color'         => 'White',
                'transmission'  => 'manual',
                'fuel_type'     => 'Diesel',
                'status'        => 'available',
                'location'      => 'Entebbe',
                'description'   => 'Spacious minibus, perfect for group travel, tours, and events.',
            ],
            [
                'name'          => 'BMW X5 xDrive',
                'brand'         => 'BMW',
                'model'         => 'X5 xDrive',
                'year'          => 2023,
                'plate_number'  => 'UAD 404B',
                'type'          => 'suv',
                'seats'         => 5,
                'price_per_day' => 500000,
                'color'         => 'Mineral Grey',
                'transmission'  => 'automatic',
                'fuel_type'     => 'Petrol',
                'status'        => 'available',
                'location'      => 'Kampala',
                'description'   => 'Premium BMW SUV delivering top-tier luxury and performance.',
            ],
            [
                'name'          => 'Nissan Navara Pickup',
                'brand'         => 'Nissan',
                'model'         => 'Navara',
                'year'          => 2021,
                'plate_number'  => 'UAE 505N',
                'type'          => 'pickup',
                'seats'         => 5,
                'price_per_day' => 180000,
                'color'         => 'Silver',
                'transmission'  => 'manual',
                'fuel_type'     => 'Diesel',
                'status'        => 'available',
                'location'      => 'Jinja',
                'description'   => 'Double-cab pickup for rugged terrain and cargo transport.',
            ],
            [
                'name'          => 'Mercedes-Benz E-Class',
                'brand'         => 'Mercedes-Benz',
                'model'         => 'E-Class 220',
                'year'          => 2022,
                'plate_number'  => 'UAF 606M',
                'type'          => 'sedan',
                'seats'         => 5,
                'price_per_day' => 400000,
                'color'         => 'Obsidian Black',
                'transmission'  => 'automatic',
                'fuel_type'     => 'Diesel',
                'status'        => 'available',
                'location'      => 'Kampala',
                'description'   => 'Flagship executive sedan with full leather interior and MBUX system.',
            ],
            [
                'name'          => 'Coaster Bus 30-Seater',
                'brand'         => 'Toyota',
                'model'         => 'Coaster',
                'year'          => 2019,
                'plate_number'  => 'UAG 707C',
                'type'          => 'bus',
                'seats'         => 30,
                'price_per_day' => 450000,
                'color'         => 'White',
                'transmission'  => 'manual',
                'fuel_type'     => 'Diesel',
                'status'        => 'available',
                'location'      => 'Kampala',
                'description'   => 'Comfortable 30-seater for corporate, school, and group transport.',
            ],
            [
                'name'          => 'Subaru Forester AWD',
                'brand'         => 'Subaru',
                'model'         => 'Forester',
                'year'          => 2020,
                'plate_number'  => 'UAH 808S',
                'type'          => 'suv',
                'seats'         => 5,
                'price_per_day' => 160000,
                'color'         => 'Crystal Blue',
                'transmission'  => 'automatic',
                'fuel_type'     => 'Petrol',
                'status'        => 'available',
                'location'      => 'Kampala',
                'description'   => 'All-wheel drive SUV for both city and off-road adventures.',
            ],
        ];

        foreach ($vehicles as $vData) {
            Vehicle::firstOrCreate(['plate_number' => $vData['plate_number']], $vData);
        }

        // ── Demo booking ────────────────────────────────────────────────────────
        $vehicle = Vehicle::where('plate_number', 'UAA 001T')->first();
        if ($vehicle && !Booking::where('booking_reference', 'NR-DEMO0001')->exists()) {
            $booking = Booking::create([
                'booking_reference' => 'NR-DEMO0001',
                'user_id'           => $customer->id,
                'vehicle_id'        => $vehicle->id,
                'pickup_date'       => now()->addDays(2)->toDateString(),
                'return_date'       => now()->addDays(5)->toDateString(),
                'pickup_location'   => 'Kampala City Centre',
                'return_location'   => 'Kampala City Centre',
                'days'              => 3,
                'total_amount'      => 360000,
                'status'            => 'approved',
                'notes'             => 'Please ensure the vehicle is clean.',
                'approved_by'       => $admin->id,
                'approved_at'       => now(),
            ]);

            Notification::send(
                $customer->id,
                'Welcome to NexusRides!',
                'Your account is ready. Browse our fleet and make your first booking.',
                'system',
                route('vehicles.index')
            );

            Notification::send(
                $customer->id,
                'Booking Approved',
                "Your booking #{$booking->booking_reference} has been approved. Please proceed with payment.",
                'booking',
                route('bookings.show', $booking)
            );
        }
    }
}
