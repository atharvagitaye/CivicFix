<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\SubCategory;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Roads & Transportation',
                'description' => 'Issues related to roads, traffic, and transportation infrastructure',
                'subcategories' => [
                    'Potholes',
                    'Traffic Lights',
                    'Road Signs',
                    'Road Lighting',
                    'Parking Issues',
                    'Public Transport'
                ]
            ],
            [
                'name' => 'Water & Drainage',
                'description' => 'Water supply, drainage, and sewerage related issues',
                'subcategories' => [
                    'Water Supply',
                    'Water Quality',
                    'Drainage Problems',
                    'Sewerage',
                    'Flooding',
                    'Water Leaks'
                ]
            ],
            [
                'name' => 'Waste Management',
                'description' => 'Garbage collection and waste disposal issues',
                'subcategories' => [
                    'Garbage Collection',
                    'Overflowing Bins',
                    'Illegal Dumping',
                    'Recycling',
                    'Hazardous Waste'
                ]
            ],
            [
                'name' => 'Public Safety',
                'description' => 'Safety and security related infrastructure issues',
                'subcategories' => [
                    'Street Lighting',
                    'CCTV Issues',
                    'Emergency Services',
                    'Fire Hazards',
                    'Security Concerns'
                ]
            ],
            [
                'name' => 'Parks & Recreation',
                'description' => 'Public parks, playgrounds, and recreational facilities',
                'subcategories' => [
                    'Park Maintenance',
                    'Playground Equipment',
                    'Sports Facilities',
                    'Public Toilets',
                    'Landscaping'
                ]
            ],
            [
                'name' => 'Utilities',
                'description' => 'Electricity, gas, and telecommunications infrastructure',
                'subcategories' => [
                    'Power Outages',
                    'Electrical Hazards',
                    'Gas Leaks',
                    'Telecom Issues',
                    'Internet Connectivity'
                ]
            ]
        ];

        foreach ($categories as $categoryData) {
            $category = Category::updateOrCreate(
                ['name' => $categoryData['name']],
                ['description' => $categoryData['description']]
            );

            foreach ($categoryData['subcategories'] as $subCategoryName) {
                SubCategory::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'name' => $subCategoryName
                    ],
                    [
                        'description' => "Sub-category for {$subCategoryName} under {$category->name}"
                    ]
                );
            }
        }
    }
}
