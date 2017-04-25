<?php

use Illuminate\Database\Seeder;

class CoursesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('courses')->insert([
            'id' => '2110432',
            'name' => 'AUTO SPEECH RECOG'
        ]);
        DB::table('sections')->insert([
            'course_id' => '2110432',
            'section_id' => '1',
            'day' => 'thu',
            'time' => 'afternoon',
            'lecturer' => 'AST',
            'capacity' => 40
        ]);


        DB::table('courses')->insert([
            'id' => '3743422',
            'name' => 'WEIGHT CONTROL'
        ]);
        DB::table('sections')->insert([
            'course_id' => '3743422',
            'section_id' => '1',
            'day' => 'wed',
            'time' => 'afternoon',
            'lecturer' => 'STAFF',
            'capacity' => 200
        ]);
        DB::table('sections')->insert([
            'course_id' => '3743422',
            'section_id' => '2',
            'day' => 'thu',
            'time' => 'afternoon',
            'lecturer' => 'STAFF',
            'capacity' => 100
        ]);


        DB::table('courses')->insert([
            'id' => '2313213',
            'name' => 'DIGITAL PHOTO'
        ]);
        DB::table('sections')->insert([
            'course_id' => '2313213',
            'section_id' => '1',
            'day' => 'mon',
            'time' => 'afternoon',
            'lecturer' => 'ANV',
            'capacity' => 300
        ]);
        DB::table('sections')->insert([
            'course_id' => '2313213',
            'section_id' => '2',
            'day' => 'tue',
            'time' => 'afternoon',
            'lecturer' => 'ANV',
            'capacity' => 300
        ]);
        DB::table('sections')->insert([
            'course_id' => '2313213',
            'section_id' => '3',
            'day' => 'wed',
            'time' => 'afternoon',
            'lecturer' => 'ANV',
            'capacity' => 300
        ]);
        DB::table('sections')->insert([
            'course_id' => '2313213',
            'section_id' => '4',
            'day' => 'thu',
            'time' => 'afternoon',
            'lecturer' => 'ANV',
            'capacity' => 300
        ]);
        DB::table('sections')->insert([
            'course_id' => '2313213',
            'section_id' => '5',
            'day' => 'fri',
            'time' => 'afternoon',
            'lecturer' => 'ANV',
            'capacity' => 300
        ]);


        DB::table('courses')->insert([
            'id' => '2502390',
            'name' => 'INTRO PACK DESIGN'
        ]);
        DB::table('sections')->insert([
            'course_id' => '2502390',
            'section_id' => '1',
            'day' => 'wed',
            'time' => 'afternoon',
            'lecturer' => 'SSV',
            'capacity' => 40
        ]);
        DB::table('sections')->insert([
            'course_id' => '2502390',
            'section_id' => '2',
            'day' => 'wed',
            'time' => 'morning',
            'lecturer' => 'VPT',
            'capacity' => 40
        ]);


        DB::table('courses')->insert([
            'id' => '3700105',
            'name' => 'FOOD SCI ART'
        ]);
        DB::table('sections')->insert([
            'course_id' => '3700105',
            'section_id' => '1',
            'day' => 'mon',
            'time' => 'morning',
            'lecturer' => 'STAFF',
            'capacity' => 270
        ]);


        DB::table('courses')->insert([
            'id' => '0123101',
            'name' => 'PARAGRAP WRITING'
        ]);
        DB::table('sections')->insert([
            'course_id' => '0123101',
            'section_id' => '1',
            'day' => 'mon',
            'time' => 'morning',
            'lecturer' => 'STAFF',
            'capacity' => 26
        ]);
        DB::table('sections')->insert([
            'course_id' => '0123101',
            'section_id' => '2',
            'day' => 'mon',
            'time' => 'afternoon',
            'lecturer' => 'STAFF',
            'capacity' => 26
        ]);
        DB::table('sections')->insert([
            'course_id' => '0123101',
            'section_id' => '3',
            'day' => 'wed',
            'time' => 'morning',
            'lecturer' => 'STAFF',
            'capacity' => 26
        ]);
        DB::table('sections')->insert([
            'course_id' => '0123101',
            'section_id' => '4',
            'day' => 'wed',
            'time' => 'afternoon',
            'lecturer' => 'STAFF',
            'capacity' => 26
        ]);
        DB::table('sections')->insert([
            'course_id' => '0123101',
            'section_id' => '5',
            'day' => 'fri',
            'time' => 'morning',
            'lecturer' => 'STAFF',
            'capacity' => 26
        ]);
        DB::table('sections')->insert([
            'course_id' => '0123101',
            'section_id' => '6',
            'day' => 'fri',
            'time' => 'afternoon',
            'lecturer' => 'STAFF',
            'capacity' => 26
        ]);


        DB::table('courses')->insert([
            'id' => '2604362',
            'name' => 'PERSONAL FINANCE'
        ]);
        DB::table('sections')->insert([
            'course_id' => '2604362',
            'section_id' => '1',
            'day' => 'wed',
            'time' => 'morning',
            'lecturer' => 'JSC',
            'capacity' => 100
        ]);
        DB::table('sections')->insert([
            'course_id' => '2604362',
            'section_id' => '2',
            'day' => 'wed',
            'time' => 'morning',
            'lecturer' => 'STAFF',
            'capacity' => 50
        ]);
    }
}
