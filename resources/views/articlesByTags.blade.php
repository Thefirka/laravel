@extends('layouts.app')

@section('content')
    @csrf
    @foreach($articles as $article)
        <p> <a href="{{ route('article', $article->slug)}}">{{ $article->title }}</a></p>
    @endforeach
@endsection
