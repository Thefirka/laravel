@extends('layouts.app')

@section('content')
 {{$article->body}}
 <form action="{{ route('newComment') }}" method="post">
     @csrf
     <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
     Write comment <input type="text" name="title">
     <input type="hidden" name="{{$article->id}}" value="{{$article->id}}"/>
     <input type="hidden" name="comment_id" value="NULL"/>
     <input type="submit">
 </form>
 <br>
@endsection
@section('footer')

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
    {!! $commentShow !!}

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
@endsection
