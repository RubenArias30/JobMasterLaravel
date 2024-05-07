<x-mail::message>
# Change Password Request

Click on the button below to change password

<x-mail::button :url="'http://localhost:4200/response-password-reset?token='.$token">
Reset Password
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
