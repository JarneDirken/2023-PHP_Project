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
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            //aanpassing
            $table->string('name')->unique();
            $table->integer('speed_aim');
            //einde aanpassing
            $table->timestamps();
        });
        DB::table('teams')->insert(
            [
                [
                    "id"=>1,
                    "name"=>"Ploeg A",
                    "speed_aim" => 40
                ],
                [
                    "id"=>2,
                    "name"=>"Ploeg B",
                    "speed_aim" => 35
                ],
                [
                    "id"=>3,
                    "name"=>"Ploeg C",
                    "speed_aim" => 30
                ],
                [
                    "id"=>4,
                    "name"=>"Ploeg D",
                    "speed_aim" => 25
                ],
                [
                    "id"=>5,
                    "name"=>"Ploeg MTB",
                    "speed_aim" => 35
                ],
                [
                    "id"=>6,
                    "name"=>"Ploeg MTB-S",
                    "speed_aim" => 30
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
        Schema::dropIfExists('teams');
    }
};
