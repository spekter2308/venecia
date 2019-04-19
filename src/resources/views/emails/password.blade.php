Натисніть тут, щоб відновити пароль: <a href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>

<!-- Натисніть тут, щоб відновити пароль: {{ url('/password/reset/' .$token) }}-->