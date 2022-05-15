<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Referendum;
use App\Models\Voter;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Voter::factory(100)->create();
    }
}
