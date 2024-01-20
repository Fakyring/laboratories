@component('mail::message')
# Здравствуйте, {{$user->surname}} {{$user->name}}

С вашей почты поступил запрос на восстановление пароля на веб-ресурсе {{config('app.name')}} <br>
Если Вы не отправляли запрос, то проигнорируйти данное сообщение <br>
Ваш код: {{$code}}<br>

Спасибо,<br>
{{ config('app.name') }}
@endcomponent
