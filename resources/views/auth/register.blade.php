<x-projectPHP-layout>
    <x-slot name="description">Register pagina</x-slot>
    <x-slot name="title">Schrijf je in</x-slot>
    @if(request()->session()->previousUrl() == route('payment') || request()->session()->has('errors'))
        <x-authentication-card>
            <x-slot name="logo">
                <x-authentication-card-logo />
            </x-slot>

            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="flex gap-x-3">
                    <div class="flex-auto">
                        <div class="flex">
                            <span style="color: red">*&nbsp</span>
                            <x-label for="first_name" value="{{ __('Voornaam') }}" />
                        </div>
                        <x-input id="first_name" class="block mt-1 w-full" type="text" name="first_name" :value="old('first_name')" required autofocus />
                    </div>

                    <div class="flex-auto">
                        <div class="flex">
                            <span style="color: red">*&nbsp</span>
                            <x-label for="last_name" value="{{ __('Achternaam') }}" />
                        </div>
                        <x-input id="last_name" class="block mt-1 w-full" type="text" name="last_name" :value="old('last_name')" required />
                    </div>
                </div>

                <div class="flex gap-x-3 mt-4">
                    <div class="flex-auto w-4/12">
                        <div class="flex">
                            <span style="color: red">*&nbsp</span>
                            <x-label for="city" value="{{ __('Gemeente') }}" />
                        </div>
                        <x-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" required />
                    </div>

                    <div class="flex-auto w-2/12">
                        <div class="flex">
                            <span style="color: red">*&nbsp</span>
                            <x-label for="postal_code" value="{{ __('Postcode') }}" />
                        </div>
                        <x-input id="postal_code" class="block mt-1 w-full" type="text" name="postal_code" :value="old('postal_code')" required />
                    </div>

                    <div class="flex-auto w-4/12">
                        <div class="flex">
                            <span style="color: red">*&nbsp</span>
                            <x-label for="street" value="{{ __('Straat') }}" />
                        </div>
                        <x-input id="street" class="block mt-1 w-full" type="text" name="street" :value="old('street')" required />
                    </div>

                    <div class="flex-auto w-2/12">
                        <div class="flex">
                            <span style="color: red">*&nbsp</span>
                            <x-label for="house_number" value="{{ __('Huisnummer') }}" />
                        </div>
                        <x-input id="house_number" class="block mt-1 w-full" type="text" name="house_number" :value="old('house_number')" required />
                    </div>
                </div>

                <div class="flex gap-3 mt-4">
                    <div class="flex-auto w-8/12">
                        <div class="flex">
                            <span style="color: red">*&nbsp</span>
                            <x-label for="email" value="{{ __('Email') }}" />
                        </div>
                        <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                    </div>

                    <div class="flex-auto w-4/12">
                        <div class="flex">
                            <span style="color: red">*&nbsp</span>
                            <x-label for="phone" value="{{ __('Telefoonnummer') }}" />
                        </div>
                        <x-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required />
                    </div>
                </div>

                <div class="flex gap-3 mt-4">
                    <div class="flex-auto">
                        <div class="flex">
                            <span style="color: red">*&nbsp</span>
                            <x-label for="password" value="{{ __('Password') }}" />
                        </div>
                        <x-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                    </div>

                    <div class="flex-auto">
                        <div class="flex">
                            <span style="color: red">*&nbsp</span>
                            <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                        </div>
                        <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                    </div>
                </div>

                <div class="flex gap-3 mt-4">
                    <div class="flex-auto w-6/12 flex flex-col">
                        <div class="flex">
                            <span style="color: red">*&nbsp</span>
                            <h2 class="text-sm font-bold">Geslacht</h2>
                        </div>
                        <div class="flex justify-between mt-2">
                            <label>
                                <x-input type="radio" name="gender_id" required value="1" />
                                Man
                            </label>
                            <label>
                                <x-input type="radio" name="gender_id" required value="2" />
                                Vrouw
                            </label>
                            <label>
                                <x-input type="radio" name="gender_id" required value="3" />
                                Anders
                            </label>
                        </div>
                    </div>

                    <div class="flex-auto w-6/12">
                        <x-label for="householder_name" value="{{ __('Naam gezinshoofd') }}" />
                        <x-input id="householder_name" class="block mt-1 w-full" type="text" name="householder_name" />
                        <p class="text-sm italic">Heb je iemand in je gezin die verzekerd is?<br>Zo ja, geeft de voor- en achternaam.</p>
                    </div>
                </div>

                <div class="w-6/12">
                    <label class="mt-2 font-bold">
                        <input type="checkbox" name="actual_wbv_insurance" {{ session('insurance') === true ? 'checked' : '' }} disabled />
                        WBV verzekering (+€15)
                    </label>
                    <p class="text-sm italic">Deze aansluiting geeft recht op een korting bij veldtoertochten,
                        wielerwedstrijden en andere ritten van Wielerbond Vlaanderen.</p>
                </div>


                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="mt-4">
                        <x-label for="terms">
                            <div class="flex items-center">
                                <x-checkbox name="terms" id="terms" required />

                                <div class="ml-2">
                                    {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Terms of Service').'</a>',
                                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Privacy Policy').'</a>',
                                    ]) !!}
                                </div>
                            </div>
                        </x-label>
                    </div>
                @endif

                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>

                    <x-button
                        class="ml-4">
                        {{ __('Register') }}
                    </x-button>
                </div>
            </form>
        </x-authentication-card>
    @else
        @php
            return abort(403, 'Geen toegang');
        @endphp
    @endif

</x-projectPHP-layout>
