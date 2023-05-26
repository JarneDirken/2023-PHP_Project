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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('size_id')->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('garment_id')->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->unique(['size_id', 'garment_id']);
            $table->integer('stock');
            $table->timestamps();
        });

        DB::table('articles')->insert(
            [
                [
                    'size_id' => 1,
                    'garment_id' => 2,
                    'stock' => 3,
                    'created_at' => now()
                ],
                [
                    'size_id' => 2,
                    'garment_id' => 2,
                    'stock' => 4,
                    'created_at' => now()
                ],
                [
                    'size_id' => 3,
                    'garment_id' => 2,
                    'stock' => 5,
                    'created_at' => now()
                ],
                [
                    'size_id' => 4,
                    'garment_id' => 2,
                    'stock' => 5,
                    'created_at' => now()
                ],
                [
                    'size_id' => 1,
                    'garment_id' => 1,
                    'stock' => 3,
                    'created_at' => now()
                ],
                [
                    'size_id' => 2,
                    'garment_id' => 1,
                    'stock' => 8,
                    'created_at' => now()
                ],
                [
                    'size_id' => 3,
                    'garment_id' => 1,
                    'stock' => 5,
                    'created_at' => now()
                ],
                [
                    'size_id' => 4,
                    'garment_id' => 1,
                    'stock' => 6,
                    'created_at' => now()
                ],
                [
                    'size_id' => 1,
                    'garment_id' => 3,
                    'stock' => 0,
                    'created_at' => now()
                ],
                [
                    'size_id' => 2,
                    'garment_id' => 3,
                    'stock' => 0,
                    'created_at' => now()
                ],
                [
                    'size_id' => 3,
                    'garment_id' => 3,
                    'stock' => 0,
                    'created_at' => now()
                ],
                [
                    'size_id' => 4,
                    'garment_id' => 3,
                    'stock' => 0,
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
        Schema::dropIfExists('articles');
    }
};
