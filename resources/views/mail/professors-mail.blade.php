<x-mail::message>
    @role ("admin")
        Nueva ausencia para el profesor <strong>{{ $professorName }} {{ $professorSurnames }}</strong> para el {{ $day }} de la semana del {{ $week[0] }} al {{ $week[1] }} de {{ $absence->startHour }} a {{ $absence->endHour }}
    @endrole

    @role ("professor")
        El profesor <strong>{{ $professorName }} {{ $professorSurnames }}</strong> ha agregado una ausencia para el {{ $day }} de la semana del {{ $week[0] }} al {{ $week[1] }} de {{ $absence->startHour }} a {{ $absence->endHour }}
    @endrole
</x-mail::message>
