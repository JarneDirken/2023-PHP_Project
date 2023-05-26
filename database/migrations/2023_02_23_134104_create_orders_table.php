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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            $table->foreignId('user_id')->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->date('date')->nullable();
            $table->foreignId('coupon_id')->nullable()->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->boolean('hasPaid')->default(false);
            $table->timestamps();
        });

        DB::table('orders')->insert(
            [
                // dummy order for HEIDI acc
                [
                    "id"=> 1,
                    "order_number"=>1,
                    "user_id"=>1,
                    "date"=>"2023-05-17",
                    "coupon_id"=>1,
                    "hasPaid"=>true
                ],
                [
                    "id"=> 2,
                    "order_number"=>2,
                    "user_id"=>1,
                    "date"=>"2023-05-19",
                    "coupon_id"=>null,
                    "hasPaid"=>true
                ],
                // dummy order for ADMIN acc
                [
                    "id"=> 3,
                    "order_number"=>3,
                    "user_id"=>11,
                    "date"=>"2023-05-13",
                    "coupon_id"=>2,
                    "hasPaid"=>true
                ],
                [
                    "id"=> 4,
                    "order_number"=>4,
                    "user_id"=>11,
                    "date"=>"2023-05-18",
                    "coupon_id"=>null,
                    "hasPaid"=>true
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
        Schema::dropIfExists('orders');
    }
};
