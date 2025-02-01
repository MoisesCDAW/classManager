<x-mail::message>
    Nueva ausencia para el profesor <strong>{{ $professorName }} {{ $professorSurnames }}</strong> para el {{ $day }} de la semana del {{ $week[0] }} al {{ $week[1] }} de {{ $absence->startHour }} a {{ $absence->endHour }}
</x-mail::message>
