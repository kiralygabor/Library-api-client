@extends('layout')

@section('content')
    <h1>Új könyv hozzáadása</h1>

    @if(session('error'))
        <div class="alert error">{{ session('error') }}</div>
    @endif

    <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label>Cím:</label>
        <input type="text" name="name" required>

        <label>Kategória ID:</label>
        <input type="number" name="category_id" required>

        <label>Szerző ID:</label>
        <input type="number" name="author_id" required>

        <label>Ár:</label>
        <input type="number" step="0.01" name="price" required>

        <label>Megjelenés:</label>
        <input type="date" name="publication_date" required>

        <label>Kiadás:</label>
        <input type="number" name="edition" required>

        <label>ISBN:</label>
        <input type="text" name="isbn" required>

        <label>Borító:</label>
        <input type="file" name="cover">

        <button type="submit">Mentés</button>
        <a href="{{ route('books.index') }}">Mégsem</a>
    </form>
@endsection
