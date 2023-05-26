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
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question')->unique();
            $table->longText('answer');

            $table->timestamps();
        });

        DB::table('faqs')->insert(
            [
                [
                    'question' => "Wat is Fietsclub 'Platte Berg'?",
                    'answer' => "De ‘Platte Berg - PB’ is een fietsclub waar zowel mannen als vrouwen in clubverband op een sportieve en veilige manier hun hobby kunnen uit oefenen zowel op de weg als in het bos (mountainbiken).",
                ],
                [
                    'question' => "Hoe kan ik lid worden van de fietsclub?",
                    'answer' => "Je kan lid worden van de fietsclub door simpelweg rechtsbovenaan op 'Schrijf in' te klikken.\n
                                 Je word doorverwezen naar de betaalpagina. Na de betaling kun je jouw account aanmaken en ben je officieel lid van onze fietsclub.",
                ],
                [
                    'question' => "Hoeveel bedraagt het lidgeld voor een seizoen?",
                    'answer' => "Dit verschild. Er zijn 2 soorten lidmaatschappen.\n
                                 1. Het standaard lidmaatschap zonder verzekering, deze kost €30 per seizoen.\n
                                 2. En het lidmaatschap met verzekering, deze kost €45 per seizoen.",
                ],
                [
                    'question' => "Wat is inbegrepen in het lidgeld?",
                    'answer' => "Met het standaard lidmaatschap zonder verzekering kunt u heel het seizoen meedoen aan verschillende ritten zowel op de weg als in het bos.\n
                                 Je hebt ook de mogelijkheid om punten te sparen en deze punten te gebruiken als kortingsbonnen voor kledij te kopen",
                ],
                [
                    'question' => "Moeten nieuwe leden verplicht clubkleding bestellen?",
                    'answer' => "Ja, nieuwe leden zijn verplicht om minimum één trui of vest met lange mouwen van de club te bestellen. Dit zorgt voor een uniforme uitstraling en identiteit binnen de fietsclub.\n
                                 Bestaande leden hebben ook de mogelijkheid om clubkleding te bestellen, zoals koersbroeken, koerstruitjes, handschoenen, enzovoort.",
                ],
                [
                    'question' => "Hoe kan ik deelnemen aan de ritten en in welke ploeg kan ik rijden?",
                    'answer' => "1. Aanmelding: Als lid van de fietsclub kun je deelnemen aan de ritten. Het lidmaatschap vereist betaling van het lidgeld voor het seizoen.\n
                                 2. Ploegkeuze: Fietsclub 'Platte Berg' heeft verschillende ploegen op zaterdag en zondag, elk met een verschillende richtsnelheid. Je kunt aansluiten bij een ploeg op basis van je eigen snelheid en voorkeur. Bijvoorbeeld: A-ploeg zondag heeft een gemiddelde richtsnelheid van 35 km/uur.\n
                                 3. Indeling: Na aanmelding en ploegkeuze zal het bestuur de rittenkalender opstellen. Deze kalender bevat de datum, vertrektijd, naam van de rit, afstand en de ritverkenner. Het bestuur zorgt ervoor dat deelnemers in de gewenste ploeg worden ingedeeld op basis van hun snelheidsvoorkeur.\n
                                 Indeling: Na aanmelding en ploegkeuze zal het bestuur de rittenkalender opstellen. Deze kalender bevat de datum, vertrektijd, naam van de rit, afstand en de ritverkenner. Het bestuur zorgt ervoor dat deelnemers in de gewenste ploeg worden ingedeeld op basis van hun snelheidsvoorkeur.",
                ],
                [
                    'question' => "Hoe kan ik punten verdienen en wat kan ik met deze punten doen?",
                    'answer' => "1. Gereden ritten: Voor elke gereden rit op zaterdag of zondag verdien je 2 punten. Gedurende het seizoen, dat loopt van de eerste zondag van maart tot de tweede zondag van oktober, kun je maximaal 60 punten verdienen met gereden ritten.\n
                                 2. Extra ritten: Als je in hetzelfde weekend een extra rit rijdt (bijvoorbeeld zowel op zaterdag als op zondag), ontvang je 1 punt per extra gereden rit. Hiermee kun je maximaal 30 extra punten verdienen.\n
                                 3. Evenementen: Door te helpen bij specifieke evenementen, zoals VVT juni, VVT december en Cross Herentals, kun je 6 punten per evenement verdienen. In totaal kun je hiermee maximaal 18 punten verdienen.\n
                                 De verdiende punten kunnen worden ingezet voor kledijbonnen bij de aankoop van clubkleding. Hier zijn de beschikbare opties:\n
                                 - 25 punten: Kledijbon ter waarde van 15€ (50% korting)\n
                                 - 35 punten: Kledijbon ter waarde van 20€ (65% korting)\n
                                 - 45 punten: Kledijbon ter waarde van 25€ (83% korting)\n
                                 - 55 punten: Kledijbon ter waarde van 30€ (100% korting, maximumbedrag)\n
                                 De kledijbonnen worden gebruikt als een vorm van beloning en motivatie voor trouwe leden die regelmatig deelnemen aan ritten en evenementen. Hoe meer punten je verdient, hoe groter de korting is die je kunt krijgen bij je volgende aankoop van clubkleding.",
                ],
                [
                    'question' => "Moet ik me vooraf aanmelden voor de ritten?",
                    'answer' => "Nee de aanwezigheden van de ritten word opgenomen na dat de rit is gereden.",
                ],
                [
                    'question' => "Zijn er beperkingen voor het aantal ritten dat ik kan rijden?",
                    'answer' => "Nee, er zijn geen beperkingen voor het aantal ritten dat je mag rijden.\n
                                 De enige beperking is in het puntensysteem, je mag met zoveel ritten meedoen als je wil.",
                ],
//                [
//                    'question' => "",
//                    'answer' => "",
//                ],

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
        Schema::dropIfExists('faqs');
    }
};
