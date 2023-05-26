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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('location')->nullable();
            $table->string('description')->nullable();
            $table->integer('max_volunteer')->nullable();
            $table->timestamps();
        });
        DB::table('events')->insert(
            [
                [
                    "id"=> 1,
                    "name"=> "Ronde Van Limburg",
                    "start_date"=> "2023-05-29",
                    "end_date"=> "2023-05-29",
                    "location"=> "Hasselt",
                    "description" => "Hulp gevraagd bij het plaatsen van de hekken.",
                    "max_volunteer"=> 5
                ],
                [
                    "id"=> 2,
                    "name"=> "Sint-Martens-Bodegem (VB)",
                    "start_date"=> "2023-04-29",
                    "end_date"=> "2023-04-29",
                    "location"=> "Bodegem",
                    "description" => "Materiaal transporteren.",
                    "max_volunteer"=> 4
                ],
                [
                    "id"=> 3,
                    "name"=> "Baloise Belgium Tour dag 1",
                    "start_date"=> "2023-06-14",
                    "end_date"=> "2023-06-14",
                    "location"=> "Scherpenheuvel-Zichem",
                    "description" => "Hulp gevraagd bij hekken op eerste dag Baloise Belgium Tour.",
                    "max_volunteer"=> 6
                ],
                [
                    "id"=> 4,
                    "name"=> "Baloise Belgium Tour dag 5",
                    "start_date"=> "2023-06-18",
                    "end_date"=> "2023-06-18",
                    "location"=> "Brussel",
                    "description" => "Hulp gevraagd bij hekken op laatste dag Baloise Beligum Tour.",
                    "max_volunteer"=> 5
                ],
                [
                    "id"=> 5,
                    "name"=> "BK tijdrijden jeugd Waregem",
                    "start_date"=> "2023-05-01",
                    "end_date"=> "2023-05-01",
                    "location"=> "Waregem",
                    "description" => "Materiaal meenemen naar Waregem.",
                    "max_volunteer"=> 3
                ],
                [
                    "id"=> 6,
                    "name"=> "BK tijdrijden volwassenen Waregem",
                    "start_date"=> "2023-06-01",
                    "end_date"=> "2023-06-01",
                    "location"=> "Waregem",
                    "description" => "Materiaal meenemen naar Waregem.",
                    "max_volunteer"=> 2
                ],
                [
                    "id"=> 7,
                    "name"=> "Baloise Belgium Tour dag 3",
                    "start_date"=> "2023-06-16",
                    "end_date"=> "2023-06-16",
                    "location"=> "Antwerpen",
                    "description" => "Hulp gevraagd bij hekken op laatste dag Baloise Beligum Tour.",
                    "max_volunteer"=> 4
                ]
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
        Schema::dropIfExists('events');
    }
};
