<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'Brightness Fashion',
                'type' => 'string'
            ],
            [
                'key' => 'site_description',
                'value' => 'Your premium fashion destination',
                'type' => 'string'
            ],
            [
                'key' => 'site_icon',
                'value' => 'logo.png',
                'type' => 'string'
            ],
            // Footer Settings
            [
                'key' => 'footer_description',
                'value' => 'Brightness Fashion represents the pinnacle of luxury fashion, offering exclusive collections that embody timeless elegance and sophisticated style. With a heritage of craftsmanship spanning generations, we create bespoke pieces that celebrate both traditional artistry and contemporary design.',
                'type' => 'string'
            ],
            [
                'key' => 'footer_facebook',
                'value' => 'https://facebook.com/brightnessfashion',
                'type' => 'string'
            ],
            [
                'key' => 'footer_instagram',
                'value' => 'https://instagram.com/brightnessfashion',
                'type' => 'string'
            ],
            [
                'key' => 'footer_twitter',
                'value' => 'https://twitter.com/brightnessfashion',
                'type' => 'string'
            ],
            [
                'key' => 'footer_pinterest',
                'value' => 'https://pinterest.com/brightnessfashion',
                'type' => 'string'
            ],
            [
                'key' => 'footer_address',
                'value' => 'Shop #234, Level 4, Block A, Bashundhara City Shopping Complex, Panthapath, Dhaka-1205, Bangladesh',
                'type' => 'string'
            ],
            [
                'key' => 'footer_phone',
                'value' => '+880 1234 5678',
                'type' => 'string'
            ],
            [
                'key' => 'footer_email',
                'value' => 'info@brightnessbd.com',
                'type' => 'string'
            ],
            [
                'key' => 'footer_hours',
                'value' => 'Mon-Sat: 10AM-8PM',
                'type' => 'string'
            ],
            [
                'key' => 'footer_copyright',
                'value' => '2025 Brightness Fashion. All rights reserved.',
                'type' => 'string'
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type']
                ]
            );
        }
    }
}
