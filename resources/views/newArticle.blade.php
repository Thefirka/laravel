@extends('layouts.app')
@section('content')
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>New Article</title>
    </head>
<form action="{{ route('newArticle') }}" method="post" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
    Article title <input type="text" name="title">
    Article body <textarea rows = "5" cols = "50" name = "body"></textarea>
    Add category <input type="text" name="category">
    <br>
    Upload Article Image: <input type="file" name="image">
    <br>
    Add tags (separated by coma) <input type="text" name="tags">
    <br>
    <input type="submit">
</form>

@endsection
