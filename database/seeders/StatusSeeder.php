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
                'description' => 'Issue has been reported and is awaiting assignment or work'
            ],
            [
                'name' => 'In Progress',
                'description' => 'Issue is currently being worked on by staff'
            ],
            [
                'name' => 'Resolved',
                'description' => 'Issue has been fixed and completed'
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
