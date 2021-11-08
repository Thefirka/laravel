@extends('layouts.app')

@section('content')
 {{$article->body}}
     <br>
 Categories for this article are:
 @foreach($article->categories as $category)
     <br>
 {{$category->name}}
 @endforeach
 <p>At that moment temperature was {{$article->temperature}} Celsius</p>
 <p>And the weather were {{$article->weather_description}}</p>
 <form action="{{ route('newComment') }}" method="post">
     @csrf
     <input type="hidden" name="_token" value="{{ csrf_token() }}"/>

     Write comment <input type="text" name="body">
     <input type="hidden" name="article_id" value="{{$article->id}}"/>
     <input type="hidden" name="parent_id" value=""/>
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
        <form method="post" action="{{route('loadArticle' , $article->slug)}}">
            <input type="submit" name="LoadArticle" value="My Previous Article">
            <input type="submit" name="LoadArticle" value="My Next Article">
            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
        </form>
    </div>
@endif
@endsection
