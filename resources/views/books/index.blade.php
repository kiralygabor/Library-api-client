@extends('layout')

@section('content')
<h1>Könyvek</h1>

@if(session('error'))
    <div style="color:red">{{ session('error') }}</div>
@endif
@if(session('success'))
    <div style="color:green">{{ session('success') }}</div>
@endif

<div style="margin-bottom: 15px;">
    <a href="{{ route('books.export.csv') }}" class="btn">Export CSV</a>
    <a href="{{ route('books.export.pdf') }}" class="btn">Export PDF</a>
</div>

<form method="GET" action="{{ route('books.index') }}" style="margin-bottom: 20px;">
    <input type="text" name="needle" value="{{ request('needle') }}" placeholder="Keresés...">
    <button type="submit">Keresés</button>
</form>

<table border="1">
    <thead>
        <tr>
            <th>Név</th>
            <th>Kategória ID</th>
            <th>Ár</th>
            <th>Megjelenés</th>
            <th>Kiadás</th>
            <th>Szerző ID</th>
            <th>ISBN</th>
            <th>Borító</th>
            <th>Műveletek</th>
        </tr>
    </thead>
    <tbody>
        @foreach($entities as $book)
        <tr>
            <td>{{ $book['name'] }}</td>
            <td>{{ $book['category_id'] }}</td>
            <td>{{ $book['price'] }}</td>
            <td>{{ $book['publication_date'] }}</td>
            <td>{{ $book['edition'] }}</td>
            <td>{{ $book['author_id'] }}</td>
            <td>{{ $book['isbn'] }}</td>
            <td>
                @if($book['cover'])
                    <img src="{{ asset($book['cover']) }}" width="50" alt="Borító">
                @endif
            </td>
            <td>
                @if($isAuthenticated)
                    <a href="{{ route('books.edit', $book['id']) }}">Szerkesztés</a>

                    <form action="{{ route('books.destroy', $book['id']) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button onclick="return confirm('Biztos törlöd?')">Törlés</button>
                    </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@if($isAuthenticated)
    <a href="{{ route('books.create') }}">Új könyv hozzáadása</a>
@endif
@endsection
