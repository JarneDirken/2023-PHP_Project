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
        Schema::create('mail_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('subject')->nullable();
            $table->string('body')->nullable();
            $table->timestamps();
        });
        DB::table('mail_templates')->insert(
            [
                [
                    'name' => 'Inschrijving',
                    'subject' => 'Bevestiging inschrijving',
                    'body' => 'Bedankt voor je inschrijving!\n Tot snel!',
                ],
                [
                    'name' => 'Bestelling',
                    'subject' => 'Bevestiging bestelling',
                    'body' => 'Bedankt voor de bestelling!\n Wij gaan zo snel mogelijk aan de slag!',
                ],
                [
                    'name' => 'Inschrijving evenement',
                    'subject' => 'Bevestiging inschrijving evenement',
                    'body' => 'Bedankt voor je inschrijving!\n Tot snel!',
                ],
                [
                    'name' => 'Register',
                    'subject' => 'Nieuwe register',
                    'body' => 'Bedankt voor je inschrijving!\n Je zal nog wel moeten betalen voor je account geactiveerd wordt.',
                ],
                [
                    'name' => 'Betaling',
                    'subject' => 'Nieuwe Betaling',
                    'body' => 'Bedankt voor de betaling!\n Tot snel en veel fietsplezier!',
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
        Schema::dropIfExists('mail_templates');
    }
};
