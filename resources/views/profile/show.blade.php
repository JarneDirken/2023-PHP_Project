<x-projectPHP-layout>
    <x-slot name="description">profiel</x-slot>
    <x-slot name="title">Profiel</x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>
    <div>
        {{-- help icon section --}}
        <x-help-modal>
            <x-slot name="title">Info profiel bewerken</x-slot>
            <x-slot name="content">
                <section class="border-b">
                    <p>Op deze pagina kan je je:</p>
                    <ul class="ml-2 mt-2 list-disc">
                        <li class="mb-2">Profiel Informatie bewerken</li>
                        <li class="mb-2">Verdiende punten en gereden ritten zien</li>
                        <li class="mb-2">Wachtwoord Aanpassen</li>
                        <li class="mb-2">Tweestapsverificatie in/uit schakelen</li>
                        <li class="mb-2">Browsersessies bekijken / uitloggen</li>
                    </ul>
                </section>
                <section class="mt-2 border-b">
                    <p class="font-medium">Profiel Informatie bewerken.</p>
                    <p class="mt-2">Hierin kan je:</p>
                    <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                        <li>
                            Een foto toevoegen door op de knop:
                            <x-secondary-button>Selecteer nieuwe profielfoto</x-secondary-button>
                            te klikken.
                        </li>
                        <li>
                            Je profiel informatie bijwerken door nieuwe gegevens in te vullen en achteraf op:
                            <x-button class="mt-3">
                                Opslaan
                            </x-button>
                            te drukken.
                        </li>
                        <li>
                            Alle velden met een:
                            <span style="color: red">*&nbsp</span>
                            zijn verplicht.
                        </li>
                    </ul>
                </section>
                <section class="mt-2 border-b">
                    <p class="font-medium">Verdiende punten en gereden ritten zien.</p>
                    <p class="mt-2">Hierin kan je:</p>
                    <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                        <li>
                            Rechts vanboven je aantal punten zien en aantal gereden ritten zien.
                        </li>
                        <li>
                            Onderaan kun je de ritten zien waarin je hebt meegereden.
                        </li>
                    </ul>
                </section>
                <section class="mt-2 border-b">
                    <p class="font-medium">Wachtwoord Aanpassen</p>
                    <p class="mt-2">Hierin kan je:</p>
                    <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                        <li>
                            Je oude wachtwoord ingeven.
                        </li>
                        <li>
                            Een nieuw wachtwoord kiezen (deze moet u 2x ingeven).
                        </li>
                        <li>
                            Het nieuwe wachtwoord opslaan door op de knop:
                            <x-button class="mt-3">
                                Opslaan
                            </x-button>
                            te klikken.
                        </li>
                    </ul>
                </section>
                <section class="mt-2 border-b">
                    <p class="font-medium">Tweestapsverificatie in/uit schakelen</p>
                    <p class="mt-2">Hierin kan je:</p>
                    <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                        <li>
                            Tweestapsverificatie in of uitschakelen door op de knop:
                            <x-button class="mt-3">
                                Schakel in
                            </x-button>
                            te klikken
                        </li>
                    </ul>
                </section>
                <section class="mt-2 border-b">
                    <p class="font-medium">Browsersessies bekijken / uitloggen</p>
                    <p class="mt-2">Hierin kan je:</p>
                    <ul class="mt-2 ml-2 list-disc [&>li]:mb-2">
                        <li>
                            Uitloggen op alle actieve apparaten door op:
                            <x-button class="mt-3">
                                Uitloggen bij alle sessies
                            </x-button>
                            te klikken
                        </li>
                    </ul>
                </section>
            </x-slot>
        </x-help-modal>
    </div>
    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')

                <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>

                <x-section-border />
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>

                <x-section-border />
            @endif

            <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

        </div>
    </div>
</x-projectPHP-layout>
