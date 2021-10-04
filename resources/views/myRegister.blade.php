@extends('layouts.app')
@section('content')
<form action="{{route('registerPost')}}" method="post">
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
   <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    Enter name <input type="text" name="name">
    Enter email <input type="email" name="email">
    Enter password <input type="password" name="password">
    <input type="submit">
</form>
@endsection
