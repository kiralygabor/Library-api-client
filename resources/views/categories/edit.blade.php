@extends('layout')

@section('content')
<h1>Kategória szerkesztése</h1>

<form action="{{ route('categories.update', $entity['id']) }}" method="POST">
    @csrf
    @method('PUT')
    <label>Név: <input type="text" name="name" value="{{ $entity['name'] }}"></label><br>
    <button type="submit">Mentés</button>
    <a href="{{ route('categories.index') }}">Mégsem</a>
</form>
@endsection
