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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->float('amount_euro', 6, 2);
            $table->integer('amount_point');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        DB::table('coupons')->insert(
            [
                [
                    'amount_euro' => 15,
                    'amount_point' => 25,
                    'active' => true,
                ],
                [
                    'amount_euro' => 20,
                    'amount_point' => 35,
                    'active' => true,
                ],
                [
                    'amount_euro' => 25,
                    'amount_point' => 45,
                    'active' => true,
                ],
                [
                    'amount_euro' => 30,
                    'amount_point' => 55,
                    'active' => false,
                ],
                [
                    'amount_euro' => 10,
                    'amount_point' => 20,
                    'active' => false,
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
        Schema::dropIfExists('coupons');
    }
};
