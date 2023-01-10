<h1>{{ __('admin.login.forget_password_mail') }}</h1>
{{(app()->getLocale() == 'en') ? 'You can reset password from bellow link : ' : ' আপনি নীচের লিঙ্ক থেকে পাসওয়ার্ড পুনরায় সেট করতে পারেন : '}}
<a href="{{ route('admin.reset.password.get', $token) }}">{{ __('admin.login.reset_password') }}</a>