@extends('layouts.app')

@section('content')
    @csrf
    @foreach($articles as $article)
        <p> <a href="{{ route('article', $article->title)}}">{{ $article->title }}</a></p>
        <form method="post" action="{{route('deleteArticle' , $article->title)}}">
            <input type="submit" name="delete" value="delete">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        </form>
    @endforeach
@endsection
