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
        Schema::create('article_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->foreignId('article_id')->constrained()->onDelete('restrict')->onUpdate('cascade');
            $table->unique(['order_id', 'article_id']);
            $table->integer('amount');
            $table->timestamps();
        });

        DB::table('article_orders')->insert(
            [
                // dummy order for HEIDI acc
                [
                    "id"=> 1,
                    "order_id"=>1,
                    "article_id"=>1,
                    "amount"=>3,
                ],
                [
                    "id"=> 2,
                    "order_id"=>1,
                    "article_id"=>2,
                    "amount"=>1,
                ],
                [
                    "id"=> 3,
                    "order_id"=>2,
                    "article_id"=>1,
                    "amount"=>1,
                ],
                // dummy order for ADMIN acc
                [
                    "id"=> 4,
                    "order_id"=>3,
                    "article_id"=>2,
                    "amount"=>2,
                ],
                [
                    "id"=> 5,
                    "order_id"=>4,
                    "article_id"=>1,
                    "amount"=>1,
                ],
                [
                    "id"=> 6,
                    "order_id"=>4,
                    "article_id"=>2,
                    "amount"=>2,
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
        Schema::dropIfExists('article_orders');
    }
};
