<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    test email
</head>
<body>
Hi {{ $user->name }}! There is new comment on your article {{ $article->title }}
</body>
