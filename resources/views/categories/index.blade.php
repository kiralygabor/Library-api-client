@extends('layout')

@section('content')
<h1>Kategóriák</h1>

@if(session('error'))
    <div style="color:red">{{ session('error') }}</div>
@endif
@if(session('success'))
    <div style="color:green">{{ session('success') }}</div>
@endif

<a href="{{ route('categories.export.csv') }}"><button>CSV export</button></a>
<a href="{{ route('categories.export.pdf') }}"><button>PDF export</button></a>

<form action="{{ route('categories.index') }}" method="GET">
    <input type="text" name="needle" placeholder="Keresés..." value="{{ request('needle') }}">
    <button type="submit">Keresés</button>
</form>

<table border="1">
    <thead>
        <tr>
            <th>Név</th>
            <th>Műveletek</th>
        </tr>
    </thead>
    <tbody>
        @foreach($entities as $category)
        <tr>
            <td>{{ $category['name'] }}</td>
            <td>
                @if($isAuthenticated)
                    <a href="{{ route('categories.edit', ['id' => $category['id'], 'name' => $category['name']]) }}">Szerkesztés</a>

                    <form action="{{ route('categories.destroy', $category['id']) }}" method="POST" style="display:inline;">
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
    <a href="{{ route('categories.create') }}">Új kategória hozzáadása</a>
@endif
@endsection
