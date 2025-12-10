@extends('layout')

@section('content')
<h1>Könyv szerkesztése</h1>

<form action="{{ route('books.update', $entity['id']) }}" method="POST">
    @csrf
    @method('PUT')
    <label>Név: <input type="text" name="name" value="{{ $entity['name'] }}"></label><br>
    <label>Kategória ID: <input type="number" name="category_id" value="{{ $entity['category_id'] }}"></label><br>
    <label>Ár: <input type="number" name="price" value="{{ $entity['price'] }}"></label><br>
    <label>Megjelenés: <input type="date" name="publication_date" value="{{ $entity['publication_date'] }}"></label><br>
    <label>Kiadás: <input type="number" name="edition" value="{{ $entity['edition'] }}"></label><br>
    <label>Szerző ID: <input type="number" name="author_id" value="{{ $entity['author_id'] }}"></label><br>
    <label>ISBN: <input type="text" name="isbn" value="{{ $entity['isbn'] }}"></label><br>
    <label>Borító: <input type="text" name="cover" value="{{ $entity['cover'] }}"></label><br>
    <button type="submit">Mentés</button>
</form>
@endsection
