<?php

use Illuminate\Database\Seeder;

class EnrollmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('enrollments')->insert([
            'user_id' => '1',
            'section_id' => '4'
        ]);
        DB::table('enrollments')->insert([
            'user_id' => '1',
            'section_id' => '1'
        ]);
    }
}
