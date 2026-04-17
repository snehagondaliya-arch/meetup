<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryIconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $icons = [
            'all-events' => 'calendar',
            'technology' => 'cpu',
            'social-activities' => 'users',
            'hobbies-and-passions' => 'heart',
            'sports-and-physical-activity' => 'activity',
            'travel-and-outdoor-activities' => 'map',
            'work-and-business' => 'briefcase',
            'identity-and-language' => 'globe',
            'dance' => 'music',
            'support-and-coaching' => 'life-buoy',
            'music' => 'headphones',
            'health-and-well-being' => 'heart-pulse',
            'art-and-culture' => 'palette',
            'science-and-education' => 'book',
            'religion-and-spirituality' => 'star',
            'writing' => 'edit',
            'parents-and-family' => 'home',
            'community-and-environment' => 'leaf',
            'games' => 'gamepad',
            'movements-and-politics' => 'flag',
            'pets-and-animals' => 'paw',
        ];

        foreach ($icons as $slug => $icon) {
            DB::table('categories')
                ->where('slug', $slug)
                ->update(['icon' => $icon]);
        }
    }
}

