<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>test</title>
</head>
<body>
<form action="/post" method="post">
  @csrf
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
   <input type="hidden" name="_token" value="{{ csrf_token() }}" />
   Enter email <input type="email" name="email">
   Enter password <input type="password" name="password">
    <input type="submit">
</form>
</body>
</html>
