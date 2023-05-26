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
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        DB::table('sizes')->insert(
            [
                [
                    'name' => 'S',
                    'created_at' => now()
                ],
                [
                    'name' => 'M',
                    'created_at' => now()
                ],
                [
                    'name' => 'L',
                    'created_at' => now()
                ],
                [
                    'name' => 'XL',
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
        Schema::dropIfExists('sizes');
    }
};
