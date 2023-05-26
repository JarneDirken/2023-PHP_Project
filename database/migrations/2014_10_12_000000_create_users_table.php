<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            //aangepast van originele users_table
            // hoe maak je combinatie van first_name en last_name unique?
            $table->string('first_name');
            $table->string('last_name');
            $table->unique(['first_name', 'last_name']);
            //einde aanpassing
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();
            //aangepast van originele users_table
            $table->string('password');
            $table->string('phone')->unique();
            $table->string('city');
            $table->string('postal_code');
            $table->string('street');
            $table->string('house_number');
            $table->boolean('management')->default(false);
            //gender id is DTR
            $table->foreignId('gender_id')->constrained()->restrictOnDelete()->onUpdate('cascade');
            $table->boolean('active')->default(true);
            //member id is DTN (en dus ook NA)
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete()->onUpdate('cascade');
            $table->boolean('actual_wbv_insurance')->nullable()->default(false);
            $table->string('householder_name')->nullable();
            //einde aanpassing
        });
        DB::table('users')->insert(
            [
                [
                "id"=> 1,
                "first_name"=> "Heidie",
                "last_name"=> "Pieroni",
                "email"=> "hpieroni0@npr.org",
                "profile_photo_path"=> null,
                "password"=> Hash::make("SENxlD9HjI7l"),
                "phone"=> "267-807-4599",
                "city"=> "Philadelphia",
                "postal_code"=> "19146",
                "street"=> "Kedzie",
                "house_number"=> "8",
                "management"=> true,
                "gender_id"=> 1,
                "active"=> true,
                "actual_wbv_insurance"=> true,
                "householder_name"=> null,
                "user_id" => null
                ],
                [
                "id"=> 2,
                "first_name"=> "Otes",
                "last_name"=> "Crosskell",
                "email"=> "ocrosskell1@unicef.org",
                "profile_photo_path"=> null,
                "password"=> Hash::make("akdGHQD"),
                "phone"=> "804-170-2468",
                "city"=> "Gālīkesh",
                "postal_code"=> "19146",
                "street"=> "Independence",
                "house_number"=> "17",
                "management"=> false,
                "gender_id"=> 1,
                "active"=> true,
                "actual_wbv_insurance"=> true,
                "householder_name"=> null,
                "user_id" => null
            ],
            [
                "id"=> 3,
                "first_name"=> "Roland",
                "last_name"=> "Maloney",
                "email"=> "rmaloney2@addthis.com",
                "profile_photo_path"=> null,
                "password"=> Hash::make("mqLcM5"),
                "phone"=> "389-499-8530",
                "city"=> "Encañada",
                "postal_code"=> "19146",
                "street"=> "Oneill",
                "house_number"=> "87",
                "management"=> true,
                "gender_id"=> 1,
                "active"=> true,
                "actual_wbv_insurance"=> true,
                "householder_name"=> "Otes Crosskell",
                "user_id" => 2
            ],
            [
                "id"=> 4,
                "first_name"=> "Arron",
                "last_name"=> "Maber",
                "email"=> "amaber3@yolasite.com",
                "profile_photo_path"=> null,
                "password"=> Hash::make("5FDPoISuzu1"),
                "phone"=> "425-412-2804",
                "city"=> "Sukamantri Satu",
                "postal_code"=> "19146",
                "street"=> "Miller",
                "house_number"=> "64931",
                "management"=> false,
                "gender_id"=> 2,
                "active"=> true,
                "actual_wbv_insurance"=> false,
                "householder_name"=> "Otes Crosskell",
                "user_id" => null
            ],
            [
                "id"=> 5,
                "first_name"=> "Shirline",
                "last_name"=> "Brandoni",
                "email"=> "sbrandoni4@a8.net",
                "profile_photo_path"=> null,
                "password"=> Hash::make("M1Iqa74imLp"),
                "phone"=> "529-873-4879",
                "city"=> "Lukashin",
                "postal_code"=> "19146",
                "street"=> "Springview",
                "house_number"=> "836",
                "management"=> true,
                "gender_id"=> 3,
                "active"=> false,
                "actual_wbv_insurance"=> false,
                "householder_name"=> null,
                "user_id" => null
            ],
            [
                "id"=> 6,
                "first_name"=> "Dominic",
                "last_name"=> "Rosario",
                "email"=> "drosario5@i2i.jp",
                "profile_photo_path"=> null,
                "password"=> Hash::make("XRMvYq0hsNf7"),
                "phone"=> "107-594-8427",
                "city"=> "Bells Corners",
                "postal_code"=> "K2R",
                "street"=> "Walton",
                "house_number"=> "65",
                "management"=> true,
                "gender_id"=> 2,
                "active"=> false,
                "actual_wbv_insurance"=> false,
                "householder_name"=> "Heidi Pironi",
                "user_id" => 1
            ],
            [
                "id"=> 7,
                "first_name"=> "Caria",
                "last_name"=> "Blackborow",
                "email"=> "cblackborow6@xinhuanet.com",
                "profile_photo_path"=> null,
                "password"=> Hash::make("yq5JAH"),
                "phone"=> "136-598-6633",
                "city"=> "Oslo",
                "postal_code"=> "0173",
                "street"=> "Crowley",
                "house_number"=> "1886",
                "management"=> false,
                "gender_id"=> 1,
                "active"=> true,
                "actual_wbv_insurance"=> false,
                "householder_name"=> null,
                "user_id" => null
            ],
            [
                "id"=> 8,
                "first_name"=> "Falito",
                "last_name"=> "Pettecrew",
                "email"=> "fpettecrew7@nsw.gov.au",
                "profile_photo_path"=> null,
                "password"=> Hash::make("CHqQLygcV"),
                "phone"=> "937-463-3278",
                "city"=> "Pavlogradka",
                "postal_code"=> "646760",
                "street"=> "Sheridan",
                "house_number"=> "1590",
                "management"=> true,
                "gender_id"=> 2,
                "active"=> true,
                "actual_wbv_insurance"=> false,
                "householder_name"=> "Caria Blackborow",
                "user_id" => 7
            ],
            [
                "id"=> 9,
                "first_name"=> "Rikki",
                "last_name"=> "Gatlin",
                "email"=> "rgatlin8@alexa.com",
                "profile_photo_path"=> null,
                "password"=> Hash::make("niI92W"),
                "phone"=> "322-218-5814",
                "city"=> "Grosuplje",
                "postal_code"=> "1290",
                "street"=> "Cordelia",
                "house_number"=> "8481",
                "management"=> true,
                "gender_id"=> 1,
                "active"=> true,
                "actual_wbv_insurance"=> false,
                "householder_name"=> null,
                "user_id" => null
            ],
            [
                "id"=> 10,
                "first_name"=> "Philomena",
                "last_name"=> "McGann",
                "email"=> "pmcgann9@biglobe.ne.jp",
                "profile_photo_path"=> null,
                "password"=> Hash::make("fMXUIv"),
                "phone"=> "880-421-5350",
                "city"=> "Huaguo",
                "postal_code"=> "19146",
                "street"=> "Charing Cross",
                "house_number"=> "2871",
                "management"=> true,
                "gender_id"=> 2,
                "active"=> true,
                "actual_wbv_insurance"=> false,
                "householder_name"=> "Philomena McGann",
                "user_id" => null
            ],
            [
                "id"=> 11,
                "first_name"=> "Adriaan",
                "last_name"=> "Ministrator",
                "email"=> "admin@email.be",
                "profile_photo_path"=> null,
                "password"=> Hash::make("admin123"),
                "phone"=> "0412345678",
                "city"=> "Geel",
                "postal_code"=> "2440",
                "street"=> "Kleinhoefstraat",
                "house_number"=> "4",
                "management"=> true,
                "gender_id"=> 1,
                "active"=> true,
                "actual_wbv_insurance"=> true,
                "householder_name"=> null,
                "user_id" => null
            ],
            ]
        );


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
