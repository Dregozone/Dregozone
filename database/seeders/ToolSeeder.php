<?php

namespace Database\Seeders;

use App\Models\Tool;
use Illuminate\Database\Seeder;

class ToolSeeder extends Seeder
{
    /**
     * Seed the tools table with initial data.
     */
    public function run(): void
    {
        Tool::firstOrCreate(
            ['url' => '/run-tools'],
            [
                'title' => 'Running Pace Calculator',
                'description' => 'Calculate the running pace you need to maintain to cover any distance within your target finish time. Supports kilometres and miles.',
                'order' => 1,
            ]
        );
    }
}
