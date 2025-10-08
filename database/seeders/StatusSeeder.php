<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'Open',
                'description' => 'Issue has been reported and is awaiting assignment'
            ],
            [
                'name' => 'In Progress',
                'description' => 'Issue is currently being worked on by staff'
            ],
            [
                'name' => 'Closed',
                'description' => 'Issue has been resolved and completed'
            ],
            [
                'name' => 'On Hold',
                'description' => 'Issue work has been temporarily suspended'
            ],
            [
                'name' => 'Rejected',
                'description' => 'Issue was reviewed and determined to be invalid or duplicate'
            ]
        ];

        foreach ($statuses as $status) {
            Status::updateOrCreate(
                ['name' => $status['name']],
                ['description' => $status['description']]
            );
        }
    }
}
