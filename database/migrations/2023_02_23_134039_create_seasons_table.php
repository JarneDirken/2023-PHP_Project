<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
        DB::table('seasons')->insert(
            [
                [
                    'start_date' => '2022-01-01',
                    'end_date' => '2022-12-31',
                    'active' => false
                ],
                [
                    'start_date' => '2023-01-01',
                    'end_date' => '2023-12-31',
                    'active' => true
                ],
                [
                    'start_date' => '2021-01-01',
                    'end_date' => '2021-12-31',
                    'active' => false
                ],

            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seasons');
    }
};
