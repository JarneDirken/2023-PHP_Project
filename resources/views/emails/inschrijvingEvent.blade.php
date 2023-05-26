<x-mail::message>
# Beste {{ $data['name'] }},
Ingeschreven bij: {{ $data['event'] }}


<p>{!! str_replace('\n', "\n\n", $data['content']) !!}</p>


Bedankt!<br>
De platte berg!
</x-mail::message>
