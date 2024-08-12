<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionSeeder extends Seeder
{
    public function run()
    {
        $divisions = [
            ['name' => 'Mobile Apps'],
            ['name' => 'QA'],
            ['name' => 'Full Stack'],
            ['name' => 'Backend'],
            ['name' => 'Frontend'],
            ['name' => 'UI/UX Designer'],
        ];

        DB::table('divisions')->insert($divisions);
    }
}
