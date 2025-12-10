@extends('layout')

@section('content')
<h1>Új szerző hozzáadása</h1>

@if(session('error'))
    <div style="color:red">{{ session('error') }}</div>
@endif

<form action="{{ route('authors.store') }}" method="POST">
    @csrf
    <label>Név:</label>
    <input type="text" name="name" required><br>

    <label>Nemzetiség:</label>
    <input type="text" name="nationality" required><br>

    <label>Életkor:</label>
    <input type="number" name="age" required><br>

    <label>Nem:</label>
    <select name="gender" required>
        <option value="male">férfi</option>
        <option value="female">nő</option>
    </select><br>

    <button type="submit">Mentés</button>
    <a href="{{ route('authors.index') }}">Mégsem</a>
</form>
@endsection
