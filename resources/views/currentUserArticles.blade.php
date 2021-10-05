@extends('layouts.app')

@section('content')
    @foreach($articles as $article)
        <p> <a href="{{ route('article', $article->title)}}">{{ $article->title }}</a></p>
    @endforeach

{{--    {{ $articles->links("pagination::bootstrap-4") }}--}}
@endsection
