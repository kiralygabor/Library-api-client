@extends('layout')

@section('content')
    <h1>{{ $entity['name'] }}</h1>

    <p><strong>Kategória ID:</strong> {{ $entity['category_id'] }}</p>
    <p><strong>Szerző ID:</strong> {{ $entity['author_id'] }}</p>
    <p><strong>Ár:</strong> {{ $entity['price'] }}</p>
    <p><strong>Megjelenés:</strong> {{ $entity['publication_date'] }}</p>
    <p><strong>Kiadás:</strong> {{ $entity['edition'] }}</p>
    <p><strong>ISBN:</strong> {{ $entity['isbn'] }}</p>

    @if($entity['cover'])
        <img src="{{ asset($entity['cover']) }}" alt="{{ $entity['name'] }}" width="200">
    @endif

    <p><a href="{{ route('books.index') }}">Vissza a listához</a></p>
@endsection
