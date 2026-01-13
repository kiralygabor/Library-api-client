@extends('layout')

@section('content')
<h1>{{ $entity['name'] }}</h1>

<p><a href="{{ route('categories.index') }}">Vissza a listához</a></p>
@endsection
