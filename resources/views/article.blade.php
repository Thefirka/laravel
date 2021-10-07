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
    @endif
{{--        Write comment<br><br>--}}
{{--        <form method="post" action="">--}}
{{--            <textarea rows = "5" cols = "50" name = "body"></textarea>--}}
{{--            <input type="submit">--}}
{{--        </form>--}}

    @foreach($comments as $comment)
        {{$comment->body}}
        @if($comment->children()->get()->all())

        @endif
{{--        @foreach($comment->parent()->get()->all() as $reply)--}}

{{--           Я КАМЕНТ КАМЕНТА {{$reply->body}}--}}
{{--        @endforeach--}}
    @endforeach
@endsection
