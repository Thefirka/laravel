@extends('layouts.app')
@section('content')
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Articles</title>
    </head>
    @foreach($articles as $article)
       <p>{{ $article->title }}</p>
    @endforeach
</html>
@endsection
