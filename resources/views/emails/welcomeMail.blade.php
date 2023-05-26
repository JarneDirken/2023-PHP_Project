<x-mail::message>
    <h1>Welkom {{ $data['name'] }}</h1>
{!! str_replace('\n', "\n\n", $data['content']) !!}
</x-mail::message>
