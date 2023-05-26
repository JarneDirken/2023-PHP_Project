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
        Schema::create('user_events', function (Blueprint $table) {
            $table->id();
            //aanpassing
            $table->foreignId('user_id')->constrained()->restrictOnDelete()->onUpdate('cascade');
            $table->foreignId('event_id')->constrained()->restrictOnDelete()->onUpdate('cascade');
            $table->unique(['user_id', 'event_id']);
            //einde aanpassing
            $table->timestamps();
        });
        DB::table('user_events')->insert(
            [[
                "id"=> 1,
                "user_id"=> 1,
                "event_id"=> 1,
            ]]);
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_events');
    }
};
