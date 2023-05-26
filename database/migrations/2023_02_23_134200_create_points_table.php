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
        Schema::create('points', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->integer('amount');
            $table->integer('maximum');
            $table->timestamps();
        });
        DB::table('points')->insert(
            [
                [
                    'id' => '1',
                    'name' => 'Helpen evenement',
                    'amount' => '6',
                    'maximum' =>'18'
                ],
                [
                    'id' => '2',
                    'name' => 'Eerste rit',
                    'amount' => '2',
                    'maximum' => '60'
                ],
                [
                    'id' => '3',
                    'name' => 'Extra rit',
                    'amount' => '1',
                    'maximum' => '30'
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
        Schema::dropIfExists('points');
    }
};
