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
        Schema::create('memberships', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->float('price');
            $table->timestamps();
        });
        DB::table('memberships')->insert(
            [
                [
                    'name' => 'Lidmaatschap',
                    'price' => '30'
                ],
                [
                    'name' => 'Verzekering',
                    'price' => '45'
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
        Schema::dropIfExists('memberships');
    }
};
