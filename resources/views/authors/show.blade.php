@extends('layout')

@section('content')
<h1>{{ $entity['name'] }}</h1>

<p><strong>Nemzetiség:</strong> {{ $entity['nationality'] }}</p>
<p><strong>Életkor:</strong> {{ $entity['age'] }}</p>
<p><strong>Nem:</strong> {{ $entity['gender'] }}</p>

<p><a href="{{ route('authors.index') }}">Vissza a listához</a></p>
@endsection
