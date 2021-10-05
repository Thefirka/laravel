@extends('layouts.app')
@section('content')
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Articles</title>
    </head>
    @foreach($articles as $article)
        <p> <a href="{{ route('article', $article->title)}}">{{ $article->title }}</a></p>
    @endforeach
    {{ $articles->links("pagination::bootstrap-4") }}
</html>
@endsection
