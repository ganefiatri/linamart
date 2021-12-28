@component('mail::message')
# Welcome

Hi {{ $member->name }},<br/><br/>
Selamat datang di {{ config('global.site_name') }}.<br/>
Anda dapat masuk melalui halaman Login dengan kredensial berikut :<br/><br/>

<b>Email : {{ $member->email }}</b><br/>
<b>Password : {{ $member->meta['pass'] ?? '******' }}</b><br/>

@component('mail::button', ['url' => route('login')])
Login Sekarang
@endcomponent

Salam Hangat,<br>
{{ config('global.site_name') }}
@endcomponent
