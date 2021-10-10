@extends('layouts.app')

@section('content')
 {{$article->body}}

@endsection

@section('footer')

@if(Auth::user())
    @csrf
    <div align="bottom">
        <form method="post" action="{{route('loadArticle' , $article->title)}}">
            <input type="submit" name="LoadArticle" value="My Previous Article">
            <input type="submit" name="LoadArticle" value="My Next Article">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        </form>
    </div>
    @endif
<h4>Add comment</h4>
<form method="post" action="{{ route('commentController.add') }}">
    @csrf
    <div class="form-group">
        <input type="text" name="comment_body" class="form-control" />
        <input type="hidden" name="article_id" value="{{ $article->id }}" />
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-warning" value="Add Comment" />
@endsection
