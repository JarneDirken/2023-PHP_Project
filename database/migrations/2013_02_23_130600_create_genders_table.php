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
        Schema::create('genders', function (Blueprint $table) {
            $table->id();
            //aangepast
            $table->string('name')->unique();
            //einde aanpassing
            $table->timestamps();
        });
        DB::table('genders')->insert(
            [
                [
                    "id"=>1,
                    "name"=>"Man"
                ],
                [
                    "id"=>2,
                    "name"=>"Vrouw"
                ],
                [
                    "id"=>3,
                    "name"=>"Anders"
                ]]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('genders');
    }
};
