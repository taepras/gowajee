<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->string('name');
            $table->timestamps();

            $table->primary('id');
        });

        Schema::create('sections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('section_number');
            $table->string('course_id')->references('id')->on('courses');
            $table->string('lecturer');
            $table->enum('day', ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun']);
            $table->enum('time', ['morning', 'afternoon']);
            $table->integer('capacity');
            $table->timestamps();

            $table->unique(['section_number', 'course_id']);
        });

        Schema::create('enrollments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->references('id')->on('users');
            $table->integer('section_id')->references('id')->on('sections');
            $table->timestamps();

            $table->unique(['user_id', 'section_id']);
        });

        // DB::statement('
        // CREATE VIEW sections_info AS
        //     SELECT
	    //         courses.name,
        //         sections.*,
        //         COUNT(user_id) AS enrolled
        //     FROM courses
        //         INNER JOIN sections
        //             ON courses.id = sections.course_id
        //         LEFT OUTER JOIN enrollments
        //             ON sections.course_id = enrollments.course_id
        //                 AND sections.section_id = enrollments.section_id
        //         GROUP BY sections.course_id, sections.section_id
        // ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
        Schema::dropIfExists('sections');
        Schema::dropIfExists('enrollments');
        // DB::statement('DROP VIEW IF EXISTS sections_info');
    }
}
