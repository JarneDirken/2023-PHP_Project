<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::create('membership_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete()->onUpdate('cascade');
            $table->foreignId('membership_id')->constrained()->restrictOnDelete()->onUpdate('cascade');
            $table->foreignId('season_id')->constrained()->restrictOnDelete()->onUpdate('cascade');
            $table->unique(['user_id','membership_id', 'season_id']);
            $table->timestamps();
        });
        DB::table('membership_users')->insert(
            [
                [
                    'user_id' => 1,
                    'membership_id' => 2,
                    'season_id' => 2
                ],
                [
                    'user_id' => 2,
                    'membership_id' => 2,
                    'season_id' => 2
                ],
                [
                    'user_id' => 3,
                    'membership_id' => 1,
                    'season_id' => 2
                ],
                [
                    'user_id' => 4,
                    'membership_id' => 1,
                    'season_id' => 2
                ],
                [
                    'user_id' => 7,
                    'membership_id' => 1,
                    'season_id' => 2
                ],
                [
                    'user_id' => 8,
                    'membership_id' => 1,
                    'season_id' => 2
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
        Schema::dropIfExists('membership_users');
    }
};
