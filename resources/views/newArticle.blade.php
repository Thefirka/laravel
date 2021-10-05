@extends('layouts.app')
@section('content')
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>New Article</title>
    </head>
<form action="{{ route('newArticle') }}" method="post">
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
    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
    Article title <input type="text" name="title">
    Article body  <input type="text" name="body">
    <input type="submit">
</form>
@endsection
