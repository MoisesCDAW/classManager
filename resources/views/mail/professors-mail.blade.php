<x-mail::message>
    El profesor {{ $professorName }} {{ $professorSurnames }} ha agregado una ausencia 
    para el {{ $day }} de la semana del {{ $week[0] }} al {{ $week[1] }} de {{ $absence->startHour }} a {{ $absence->endHour }}
</x-mail::message>
