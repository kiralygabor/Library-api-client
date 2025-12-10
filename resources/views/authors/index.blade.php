@extends('layout')

@section('content')
<h1>Szerzők</h1>

@if(session('error'))
    <div style="color:red">{{ session('error') }}</div>
@endif
@if(session('success'))
    <div style="color:green">{{ session('success') }}</div>
@endif

<!-- Kereső -->
<form method="GET" action="{{ route('authors.index') }}">
    <input type="text" name="needle" placeholder="Keresés..." value="{{ request('needle') }}">
    <button type="submit">Keresés</button>
</form>

<table border="1">
    <thead>
        <tr>
            <th>Név</th>
            <th>Nemzetiség</th>
            <th>Kor</th>
            <th>Nem</th>
            <th>Műveletek</th>
        </tr>
    </thead>
    <tbody>
        @foreach($entities as $author)
        <tr>
            <td>{{ $author['name'] }}</td>
            <td>{{ $author['nationality'] }}</td>
            <td>{{ $author['age'] }}</td>
            <td>{{ $author['gender'] }}</td>
            <td>
                @if(auth()->check())
                    <a href="{{ route('authors.edit', $author['id']) }}">Szerkesztés</a>

                    <form action="{{ route('authors.destroy', $author['id']) }}" method="POST" style="display:inline;">
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

@if(auth()->check())
    <a href="{{ route('authors.create') }}">Új szerző hozzáadása</a>
@endif
@endsection
