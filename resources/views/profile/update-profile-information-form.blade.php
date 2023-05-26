<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Variable -->

        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" class="hidden"
                       wire:model="photo"
                       x-ref="photo"
                       x-on:change="
                                     photoName = $refs.photo.files[0].name;
                                     const reader = new FileReader();
                                     reader.onload = (e) => {
                                         photoPreview = e.target.result;
                                     };
                                     reader.readAsDataURL($refs.photo.files[0]);
                             " />

                <x-label for="photo" value="{{ __('Photo') }}" />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="@if($this->user->profile_photo_path) {{  $this->user->profile_photo_url }} @else {{'https://ui-avatars.com/api/?name=' . (auth()->user()->first_name . " " . auth()->user()->last_name)}} @endif" alt="{{ $this->user->first_name }} {{$this->user->last_name}}" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                     <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                           x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                     </span>
                </div>

                <x-secondary-button class="mt-2 mr-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
                </x-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </x-secondary-button>
                @endif

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Points -->
        <div class="col-span-6 sm:col-span-1">
            <x-label for="points" value="{{ __('Aantal punten') }}" />
            <x-input id="points" type="text" class="mt-1 block w-full"
                     value="{{ \App\Models\PointUser::where('user_id', Auth::id())->sum('points')}}" readonly/>
        </div>

        <!-- Ritten -->
        <div class="col-span-6 sm:col-span-1">
            <x-label for="points" value="{{ __('Gereden ritten') }}" />
            @php
                $currentSeason = \App\Models\Season::where('active', true)->first();
                $tours = \App\Models\Tour::with(['team', 'userTours'])
                    ->where('open', false)
                    ->whereHas('userTours', function ($query) use ($currentSeason) {
                        $query->where('user_id', Auth::id())->where('present', true);
                    })
                    ->whereBetween('date', [$currentSeason->start_date, $currentSeason->end_date])
                    ->get();
               $attendedToursCount = $tours->count();
            @endphp

            <x-input id="points" type="text" class="mt-1 block w-full" value="{{ $attendedToursCount }}" readonly/>
        </div>

        <!-- FirstName -->
        <div class="col-span-6 sm:col-span-3">
            <div class="flex">
                <span style="color: red">*&nbsp</span>
                <x-label for="first_name" :value="__('Voornaam')" class="font-bold" style="flex: 1" />
            </div>
            <x-input id="first_name" type="text" class="mt-1 block w-full" wire:model.defer="state.first_name" value="{{Auth::user()->first_name}}" />
            <x-input-error for="first_name" class="mt-2" />
        </div>

        <!-- LastName -->
        <div class="col-span-6 sm:col-span-3">
            <div class="flex">
                <span style="color: red">*&nbsp</span>
                <x-label for="last_name" value="{{ __('Achternaam') }}" />
            </div>
            <x-input id="last_name" type="text" class="mt-1 block w-full" wire:model.defer="state.last_name" value="{{Auth::user()->last_name}}" />
            <x-input-error for="last_name" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-6">
            <div class="flex">
                <span style="color: red">*&nbsp</span>
                <x-label for="email" value="{{ __('Email') }}" />
            </div>
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model.defer="state.email" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="text-sm mt-2">
                    {{ __('Your email address is unverified.') }}

                    <button type="button" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:click.prevent="sendEmailVerification">
                        {{ __('Click here to re-send the verification email.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p v-show="verificationLinkSent" class="mt-2 font-medium text-sm text-green-600">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </p>
                @endif
            @endif
        </div>

        <!-- Address Straat -->
        <div class="col-span-6 sm:col-span-4">
            <div class="flex">
                <span style="color: red">*&nbsp</span>
                <x-label for="street" value="{{ __('Straat') }}" />
            </div>
            <x-input id="street" type="text" class="mt-1 block w-full" wire:model.defer="state.street" value="{{Auth::user()->street}}" />
            <x-input-error for="street" class="mt-2" />
        </div>

        <!-- Address Nummer -->
        <div class="col-span-6 sm:col-span-2">
            <div class="flex">
                <span style="color: red">*&nbsp</span>
                <x-label for="house_number" value="{{ __('Huisnummer') }}" />
            </div>
            <x-input id="house_number" type="text" class="mt-1 block w-full" wire:model.defer="state.house_number" value="{{Auth::user()->house_number}}" />
            <x-input-error for="house_number" class="mt-2" />
        </div>

        <!-- Address City -->
        <div class="col-span-6 sm:col-span-3">
            <div class="flex">
                <span style="color: red">*&nbsp</span>
                <x-label for="city" value="{{ __('Stad') }}" />
            </div>
            <x-input id="city" type="text" class="mt-1 block w-full" wire:model.defer="state.city" value="{{Auth::user()->city}}" />
            <x-input-error for="city" class="mt-2" />
        </div>

        <!-- Address Postal Code -->
        <div class="col-span-6 sm:col-span-3">
            <div class="flex">
                <span style="color: red">*&nbsp</span>
                <x-label for="postal_code" value="{{ __('Postcode') }}" />
            </div>
            <x-input id="postal_code" type="text" class="mt-1 block w-full" wire:model.defer="state.postal_code" value="{{Auth::user()->postal_code}}" />
            <x-input-error for="postal_code" class="mt-2" />
        </div>

        <!-- Gender -->
        <div class="col-span-6 sm:col-span-2">
            <div class="flex">
                <span style="color: red">*&nbsp</span>
                <x-label for="gender" value="{{ __('Gender') }}" />
            </div>
            <x-select id="gender" class="mt-1 block w-full" wire:model.defer="state.gender_id">
                <option value="1" {{ Auth::user()->gender_id == 1 ? 'selected' : '' }}>Man</option>
                <option value="2" {{ Auth::user()->gender_id == 2 ? 'selected' : '' }}>Vrouw</option>
                <option value="3" {{ Auth::user()->gender_id == 3 ? 'selected' : '' }}>Ander</option>
            </x-select>
            <x-input-error for="gender" class="mt-2" />
        </div>

        <!-- Gezindshoofd -->
        <div class="col-span-6 sm:col-span-3">
            <x-label for="householder_name" value="{{ __('Gezinshoofd') }}" />
            <x-input id="householder_name" type="text" class="mt-1 block w-full" wire:model.defer="state.householder_name" value="{{Auth::user()->householder_name}}" />
            <x-input-error for="householder_name" class="mt-2" />
        </div>

        <!-- Insurance -->
        <div class="col-span-6 sm:col-span-1">
            <x-label for="actual_wbv_insurance" value="{{ __('Verzekering') }}" />
            <x-input id="actual_wbv_insurance" type="checkbox" class="mt-1 block w-4" wire:model.defer="state.actual_wbv_insurance" disabled />
            <x-input-error for="actual_wbv_insurance" class="mt-2" />
        </div>

        <!-- Gereden Ritten -->
        <div class="col-span-6 sm:col-span-6">
            <x-label for="points" value="{{ __('Gereden Ritten') }}" />
            @php
                $currentSeason = \App\Models\Season::where('active', true)->first();
                $tours = \App\Models\Tour::with(['team', 'userTours'])
                    ->where('open', false)
                    ->whereHas('userTours', function ($query) use ($currentSeason) {
                        $query->where('user_id', Auth::id())->where('present', true);
                    })
                    ->whereBetween('date', [$currentSeason->start_date, $currentSeason->end_date])
                    ->get();
            @endphp
            @if($tours->isEmpty())
                <p>De gebruiker was niet aanwezig bij ritten in het huidig actieve seizoen</p>
            @else
                <table class="w-full">
                    <thead>
                    <tr>
                        <th class="px-2 py-2">Datum</th>
                        <th class="px-2 py-2">Vertrek uur</th>
                        <th class="px-2 py-2">Team</th>
                        <th class="px-2 py-2">Naam</th>
                        <th class="px-2 py-2">Afstand</th>
                        <th class="px-2 py-2">Locatie</th>
                        <th class="px-2 py-2">Beschrijving</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tours as $tour)
                        <tr>
                            <td class="border px-2 py-2">{{ \Carbon\Carbon::parse($tour->date)->format('d F Y') }}</td>
                            <td class="border px-2 py-2">{{ $tour->departure_time }}</td>
                            <td class="border px-2 py-2">{{ $tour->team->name }}</td>
                            <td class="border px-2 py-2">{{ $tour->name }}</td>
                            <td class="border px-2 py-2">{{ $tour->distance }}</td>
                            <td class="border px-2 py-2">{{ $tour->location }}</td>
                            <td class="border px-2 py-2">{{ $tour->description }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="mr-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Save') }}
        </x-button>
    </x-slot>
</x-form-section>
