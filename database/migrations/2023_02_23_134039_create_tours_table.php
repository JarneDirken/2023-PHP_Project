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
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            //aanpassing
            $table->date('date');
            $table->time('departure_time');
            $table->foreignId('team_id')->constrained()->restrictOnDelete()->onUpdate('cascade');
            $table->string('name');
            $table->float('distance');
            $table->string('location');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete()->onUpdate('cascade');
            $table->string('description')->nullable();
            $table->boolean('open')->default(true); // een rit wordt gesloten als de aanwezigheden zijn aangeduid
            //einde aanpassing
            $table->timestamps();
        });

        DB::table('tours')->insert(
            [
                [
                    "id" => 1,
                    "date" => "2022-12-04",
                    "departure_time" => "10:15:00",
                    "name" => "Ochtendrit Brussel",
                    "description" => "Rit van 55km door Brussel, richting Anderlecht.",
                    "team_id" => 2,
                    "distance" => 55.0,
                    "location" => "Brussel, België",
                    "open" => true
                ],
                [
                    "id" => 2,
                    "date" => "2022-12-08",
                    "departure_time" => "11:00:00",
                    "name" => "Rit Brugge",
                    "description" => "Route langs Overpoort, met 1 cafépauze",
                    "team_id" => 3,
                    "distance" => 50,
                    "location" => "Brugge, België",
                    "open" => true
                ],
                [
                    "id" => 3,
                    "date" => "2023-5-20",
                    "departure_time" => "16:30:00",
                    "name" => "Namiddagrit Gent",
                    "description" => "We starten in Gent, rijden vervolgens richting Laarna en keren terug.",
                    "team_id" => 1,
                    "distance" => 70,
                    "location" => "Gent, België",
                    "open" => true
                ],
                [ // een tour die nog moet komen (kan je aanwezigheden nog niet opnemen)
                    "id" => 4,
                    "date" => "2023-07-03",
                    "departure_time" => "14:00",
                    "name" => "Mountainbikerit Averbode",
                    "description" => "Mountainbiken in bos van de Weefberg.",
                    "team_id" => 5,
                    "distance" => 35.0,
                    "location" => "Averbode, België",
                    "open" => true
                ],
                [
                    "id" => 5,
                    "date" => "2023-04-11",
                    "departure_time" => "13:00",
                    "name" => "Middagrit Geel",
                    "description" => "Start in Geel, 30km naar Heusen-Zolder en terug.",
                    "team_id" => 1,
                    "distance" => 60.0,
                    "location" => "Geel, België",
                    "open" => true
                ],
                [ // gesloten tours dit seizoen (komt in profiel gebuiker)
                    "id" => 6,
                    "date" => "2023-05-01",
                    "departure_time" => "10:00:00",
                    "name" => "Ochtendrit Hoogerheide",
                    "description" => "Korte rit rond Hoogerheide.",
                    "team_id" => 4,
                    "distance" => 25.0,
                    "location" => "Hoogerheide, België",
                    "open" => false
                ],
                [
                    "id" => 7,
                    "date" => "2023-02-27",
                    "departure_time" => "12:15:00",
                    "name" => "Mountainbikerit Genk",
                    "description" => "Mountainbikerit van 30km in Genk",
                    "team_id" => 6,
                    "distance" => 30.0,
                    "location" => "Genk, België",
                    "open" => false
                ],
                //data voor statistieken (oude, gesloten ritten waarvan aanwezigheden al zijn opgenomen)
                [
                    "id" => 8,
                    "date" => "2021-07-06",
                    "departure_time" => "09:00:00",
                    "name" => "Ochtendrit Antwerpen-Mechelen",
                    "description" => "Rit van Antwerpen tot Mechelen en terug.",
                    "team_id" => 1,
                    "distance" => 50,
                    "location" => "Antwerpen, België",
                    "open" => false
                ],
                [
                    "id" => 9,
                    "date" => "2021-10-18",
                    "departure_time" => "12:00:00",
                    "name" => "Middagrit Gent-Aalst",
                    "description" => "Rit van Gent tot Aalst en terug.",
                    "team_id" => 4,
                    "distance" => 60,
                    "location" => "Gent, België",
                    "open" => false
                ],
                [
                    "id" => 10,
                    "date" => "2022-11-02",
                    "departure_time" => "16:00:00",
                    "name" => "Rit Brugge-Oostende",
                    "description" => "Rit van Brugge tot Oostende en terug.",
                    "team_id" => 3,
                    "distance" => 50,
                    "location" => "Brugge, België",
                    "open" => false
                ],
                [
                    "id" => 11,
                    "date" => "2022-05-22",
                    "departure_time" => "18:00:00",
                    "name" => "Avondrit Charleroi-Namen",
                    "description" => "Rit van Charleroi tot Namen en terug.",
                    "team_id" => 2,
                    "distance" => 80,
                    "location" => "Charleroi, België",
                    "open" => false
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
        Schema::dropIfExists('tours');
    }
};
