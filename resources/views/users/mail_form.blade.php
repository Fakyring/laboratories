@component('mail::message')
# Здравствуйте, {{$user->surname}} {{$user->name}}

Вы были зарегистрированы на веб-ресурсе {{config('app.name')}} <br>
Ваш пароль: {{$password}}<br>
Для авторизации нажмите на кнопку ниже
@component('mail::button', ['url' => route('auth')])
Войти
@endcomponent

Спасибо,<br>
{{ config('app.name') }}
@endcomponent
