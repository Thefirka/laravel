@extends('layouts.app')

@section('content')
 {{$article->body}}
@endsection

@section('footer')
@if(Auth::user())
    <div align="bottom">
    <a href="{{route('previousArticle' , $article->title)}}"> My Previous Article</a>
    <a href="{{route('nextArticle' , $article->title)}}"> My Next Article</a>
 </div>
    @endif
@endsection
