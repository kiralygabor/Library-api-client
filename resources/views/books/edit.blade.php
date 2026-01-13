@extends('layout')

@section('content')
<h1>Könyv szerkesztése</h1>

@if(session('error'))
    <div style="color:red">{{ session('error') }}</div>
@endif
@if(session('success'))
    <div style="color:green">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('books.update', $entity['id']) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div>
        <label for="name">Név:</label>
        <input type="text" id="name" name="name" value="{{ old('name', $entity['name']) }}" required>
    </div>

    <div>
        <label for="category_id">Kategória:</label>
        <select id="category_id" name="category_id" required>
            @foreach($categories as $category)
                <option value="{{ $category['id'] }}"
                    {{ $category['id'] == $entity['category_id'] ? 'selected' : '' }}>
                    {{ $category['name'] }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="price">Ár:</label>
        <input type="number" step="0.01" id="price" name="price" value="{{ old('price', $entity['price']) }}" required>
    </div>

    <div>
        <label for="publication_date">Megjelenés:</label>
        <input type="date" id="publication_date" name="publication_date" value="{{ old('publication_date', $entity['publication_date']) }}" required>
    </div>

    <div>
        <label for="edition">Kiadás:</label>
        <input type="text" id="edition" name="edition" value="{{ old('edition', $entity['edition']) }}">
    </div>

    <div>
        <label for="author_id">Szerző:</label>
        <select id="author_id" name="author_id" required>
            @foreach($authors as $author)
                <option value="{{ $author['id'] }}"
                    {{ $author['id'] == $entity['author_id'] ? 'selected' : '' }}>
                    {{ $author['name'] }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="isbn">ISBN:</label>
        <input type="text" id="isbn" name="isbn" value="{{ old('isbn', $entity['isbn']) }}">
    </div>

    <div>
        <label for="cover">Borító:</label>
        @if($entity['cover'])
            <div>
                <img src="{{ asset($entity['cover']) }}" width="100" alt="Borító">
            </div>
        @endif
        <input type="file" id="cover" name="cover" accept="image/*">
    </div>

    <div style="margin-top: 10px;">
        <button type="submit">Mentés</button>
        <a href="{{ route('books.index') }}">Mégsem</a>
    </div>
</form>
@endsection
