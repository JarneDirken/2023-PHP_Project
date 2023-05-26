<?php
use App\Http\Livewire\Admin\Calendar;
use App\Http\Livewire\Admin\Articles;
use App\Http\Livewire\Admin\Coupons;
use App\Http\Livewire\Admin\Faq;
use App\Http\Livewire\Admin\Faqs;
use App\Http\Livewire\Admin\HistoryAdmin;
use App\Http\Livewire\Admin\MailTemplates;
use App\Http\Livewire\Admin\Statistics;
use App\Http\Livewire\Admin\Teams;
use App\Http\Livewire\Admin\Events;
use App\Http\Livewire\Admin\Garments;
use App\Http\Livewire\Admin\Seasons;
use App\Http\Livewire\Admin\Sizes;
use App\Http\Livewire\Admin\Genders;
use App\Http\Livewire\Admin\Users;
use App\Http\Livewire\FaqPage;
use App\Http\Livewire\History;
use App\Http\Livewire\Home;
use App\Http\Livewire\InschrijvenRitverkenner;
use App\Http\Livewire\KledijBestellen;
use App\Http\Livewire\OpnemenAanwezigheden;
use App\Http\Livewire\ShoppingCart;
use App\Http\Livewire\Shared\Payment;
use App\Models\User;
use App\Http\Livewire\Admin\Points;
use App\Http\Livewire\Admin\Memberships;
use App\Http\Livewire\InschrijvenEvenement;
use App\Http\Livewire\Admin\Tours;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProjectController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Gebruiker moet ingelogd zijn, actief zijn en een lidmaatschap hebben
Route::middleware(['auth', 'active', 'membership'])->group(function () {
    Route::get('inschrijven-evenement', InschrijvenEvenement::class)->name('inschrijven-evenement');
    Route::get('inschrijven-ritverkenner', InschrijvenRitverkenner::class)->name('inschrijven-ritverkenner');
    Route::get('opnemen-aanwezigheden', OpnemenAanwezigheden::class)->name('opnemen-aanwezigheden');
    Route::get('kledij-bestellen', KledijBestellen::class)->name('kledij-bestellen');
    Route::post('addcart/{id}', [KledijBestellen::class,'addcart'])->name('addcart');
    Route::get('winkelmand', ShoppingCart::class)->name('showcart');
    Route::post('apply', [ShoppingCart::class, 'apply'])->name('apply');
    Route::get('betalen', Payment::class)->name('payment');
    Route::get('kalender', Calendar::class)->name('calendar');
    Route::get('bestellingen', History::class)->name('bestellingen');
});

// gebruiker moet niet ingelogd zijn
Route::get('/', Home::class)->name('home');
Route::get('betalen', Payment::class)->name('payment');
Route::get('kalender', Calendar::class)->name('calendar');
Route::get('inschrijven-evenement', InschrijvenEvenement::class)->name('inschrijven-evenement');
Route::get('faq', FaqPage::class)->name('faq-page');

// gebuiker moet admin rechten hebben
Route::middleware(['auth', 'active', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::redirect('/', '/admin/index');
    Route::get('index', [ProjectController::class, 'index']);
    Route::get('ploegen', Teams::class)->name('teams');
    Route::get('kortingsbonnen', Coupons::class)->name('coupons');
    Route::get('evenementen', Events::class)->name('events');
    Route::get('maten', Sizes::class)->name('sizes');
    Route::get('ritten', Tours::class)->name('tours');
    Route::get('gebruikers', Users::class)->name('users');
    Route::get('punten', Points::class)->name('points');
    Route::get('lidmaatschappen', Memberships::class)->name('memberships');
    Route::get('kledij', Garments::class)->name('garments');
    Route::get('seizoenen', Seasons::class)->name('seasons');
    Route::get('genders', Genders::class)->name('genders');
    Route::get('artikels', Articles::class)->name('articles');
    Route::get('email-sjablonen', MailTemplates::class)->name('mail-templates');
    Route::get('faqs', Faqs::class)->name('faqs');


    Route::get('overzicht-bestellingen', HistoryAdmin::class)->name('overzicht-bestellingen');

    Route::get('statistieken', Statistics::class)->name('statistics');

});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'active',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::redirect('/dashboard', '/');
});
