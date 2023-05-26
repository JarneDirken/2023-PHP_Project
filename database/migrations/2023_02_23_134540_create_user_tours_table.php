<?php

use App\Models\Tour;
use App\Models\User;
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
        Schema::create('user_tours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete()->onUpdate('cascade');
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete()->onUpdate('cascade');
            $table->unique(['user_id', 'tour_id']);
            $table->boolean('present')->default(false);
            $table->timestamps();
        });


        //voor tour 1 tem 5 alle gebruikers ermee verbinden (tour nog open dus altijd afwezig)
        $tourIds = range(1, 5);
        foreach ($tourIds as $tourId) {
            foreach (User::get() as $user) {
                DB::table('user_tours')->insert([
                    'user_id' => $user->id,
                    'tour_id' => $tourId
                ]);
            }
        }

        //voor tour 6 tem 11 (gesloten ritten voor stats) sommige gebruikers aanwezig zetten
        $tourIds = range(6, 11);
        foreach ($tourIds as $tourId) {
            foreach (User::get() as $user) {
                //gebruiker 1 en 11 altijd aanwezig, anders willekeurig
                if($user->id == 1 || $user->id == 11) {
                    $present = true;
                } else {
                    //sommige ritten +- 50% aanwezigheid, sommige 33%
                    $odds = mt_rand(2, 3);
                    $present = mt_rand(1, $odds) === 1; // 1/odds chance of being true
                }

                DB::table('user_tours')->insert([
                    'user_id' => $user->id,
                    'tour_id' => $tourId,
                    'present' => $present
                ]);
            }
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_tours');
    }
};
