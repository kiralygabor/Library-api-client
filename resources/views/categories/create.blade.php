@extends('layout')

@section('content')
<h1>Új kategória hozzáadása</h1>

@if(session('error'))
    <div style="color:red">{{ session('error') }}</div>
@endif

<form action="{{ route('categories.store') }}" method="POST">
    @csrf
    <label>Név: <input type="text" name="name" required></label><br>
    <button type="submit">Mentés</button>
    <a href="{{ route('categories.index') }}">Mégsem</a>
</form>
@endsection
