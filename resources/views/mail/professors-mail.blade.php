<x-mail::message>
Ahora solo tienes que cambiar tu contraseña y para eso lo puedes hacer con el siguiente botón:

<x-mail::button :url="''">
Cambiar contraseña
</x-mail::button>

Atentamente,<br>
{{ config('app.name') }}
</x-mail::message>
