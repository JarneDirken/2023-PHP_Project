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
        Schema::create('garments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->float('price', 6, 2);
            $table->string('description')->nullable();
            $table->boolean('active')->default(true);
            $table->string('url');
            $table->timestamps();
        });

        DB::table('garments')->insert(
            [
                [
                    'name' => 'Broek',
                    'price' => 20,

                    'description' => 'Broek met kleuren van de club',
                    'active' => true,
                    'url' => '/storage/webshop-photos/broek.jpg',
                    'created_at' => now()
                ],
                [
                    'name' => 'Trui',
                    'price' => 20,
                    'description' => 'Trui met kleuren van de club',
                    'active' => true,
                    'url' => '/storage/webshop-photos/wielertrui.jpg',
                    'created_at' => now()
                ],
                [
                    'name' => 'Handschoenen',
                    'price' => 20,
                    'description' => 'Handschoenen met kleuren van de club',
                    'active' => true,
                    'url' => '/storage/webshop-photos/wielerhandschoenen.jpg',
                    'created_at' => now()
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
        Schema::dropIfExists('garments');
    }
};
