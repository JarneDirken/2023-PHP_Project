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
        Schema::create('point_users', function (Blueprint $table) {
            $table->id();
            $table->integer('amount');
            $table->integer('points');
            $table->foreignId('user_id')->constrained()->restrictOnDelete()->onUpdate('cascade');
            $table->foreignId('season_id')->constrained()->restrictOnDelete()->onUpdate('cascade');
            $table->foreignId('point_id')->constrained()->restrictOnDelete()->onUpdate('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete()->onUpdate('cascade');
            $table->unique(['user_id','point_id', 'season_id']);
            $table->timestamps();
        });
        DB::table('point_users')->insert(
            [
                // heidi points
                [
                    'id' => '1',
                    'amount' => '18',
                    'user_id' => '1',
                    'season_id' => '2',
                    'point_id' => '1',
                    'points' => '18',
                ],
                [
                    'id' => '2',
                    'amount' => '18',
                    'user_id' => '1',
                    'season_id' => '2',
                    'point_id' => '2',
                    'points' => '18',
                ],
                // admin points
                [
                    'id' => '3',
                    'amount' => '18',
                    'user_id' => '11',
                    'season_id' => '2',
                    'point_id' => '1',
                    'points' => '18',
                ],
                [
                    'id' => '4',
                    'amount' => '18',
                    'user_id' => '11',
                    'season_id' => '2',
                    'point_id' => '2',
                    'points' => '18',
                ],
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('point_users');
    }
};
