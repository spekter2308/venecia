Натисніть , щоб відновити пароль: <a href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>

<!-- Click here to reset your password: {{ url('/password/reset/' .$token) }}-->