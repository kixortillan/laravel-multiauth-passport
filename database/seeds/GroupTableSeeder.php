<?php

use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Group::create([
            'name' => 'admin',
        ]);

        Group::create([
            'name' => 'employee',
        ]);

        Group::create([
            'name' => 'human-resource',
        ]);

        Group::create([
            'name' => 'manager',
        ]);
    }

}
