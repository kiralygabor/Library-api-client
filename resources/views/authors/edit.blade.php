@extends('layout')

@section('content')
<h1>Szerző szerkesztése</h1>

<form action="{{ route('authors.update', $entity['id']) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Név:</label>
    <input type="text" name="name" value="{{ $entity['name'] }}" required><br>

    <label>Nemzetiség:</label>
    <input type="text" name="nationality" value="{{ $entity['nationality'] }}" required><br>

    <label>Életkor:</label>
    <input type="number" name="age" value="{{ $entity['age'] }}" required><br>

    <label>Nem:</label>
    <select name="gender" required>
        <option value="male" {{ $entity['gender'] == 'male' ? 'selected' : '' }}>férfi</option>
        <option value="female" {{ $entity['gender'] == 'female' ? 'selected' : '' }}>nő</option>
    </select><br>

    <button type="submit">Mentés</button>
    <a href="{{ route('authors.index') }}">Mégsem</a>
</form>
@endsection
