<?php

namespace App\Console\Commands;

use App\Models\Season;
use Illuminate\Console\Command;

class UpdateSeason extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'seasons:update-active';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if new season is active';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //actief seizoen zoeken = seizoen waar datum tussen start en eind valt
        $today = date('Y-m-d');
        $activeSeason = Season::where('start_date', '<=', $today)->where('end_date', '>=', $today);

        //seizoen die actief zijn op inactief zetten
        Season::where('active', true)->update(['active' => false]);

        //nieuwe actief seizeon op actief zetten
        $activeSeason->update(['active' => true]);
    }
}
