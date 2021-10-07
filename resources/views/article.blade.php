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
{{--        Write comment<br><br>--}}
{{--        <form method="post" action="">--}}
{{--            <textarea rows = "5" cols = "50" name = "body"></textarea>--}}
{{--            <input type="submit">--}}
{{--        </form>--}}

    @foreach($comments as $comment)
{{--            @if(!$comment->parent_id)--}}
{{--                {{$comment->body}}--}}
{{--            @endif--}}
        @if($comment->children()->get())
            @foreach($comment->get() as $reply)
                {{$reply->body}}

            @endforeach
        @endif

    @endforeach
@endsection
