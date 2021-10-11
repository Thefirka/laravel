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
<form action="{{ route('newComment') }}" method="post">
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
    @foreach($comments as $comment)
        @if(!$comment->parent_id)
        {{$comment->body}}
        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        Write comment <input type="text" name="body">
        <input type="hidden" name="article_id" value="{{ $article->id }}"/>
            <input type="hidden" name="article_id" value="{{ $comment->id }}"/>
        <input type="submit">
</form>
    @endif
    @endforeach
@endsection
