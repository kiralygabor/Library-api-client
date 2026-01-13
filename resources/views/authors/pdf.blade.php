<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        header { text-align: center; margin-bottom: 20px; }
        footer { text-align: center; font-size: 0.8em; margin-top: 20px; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { border: 1px solid #000; padding: 5px; text-align: left; }
        table th { background-color: #3498db; color: white; }
        img.logo { max-height: 50px; }
    </style>
</head>
<body>
<header>
    <img src="{{ public_path('logo.png') }}" class="logo" alt="Logo"><br>
    <h2>Szerzők listája</h2>
</header>

<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Név</th>
            <th>Nemzetiség</th>
            <th>Kor</th>
            <th>Nem</th>
        </tr>
    </thead>
    <tbody>
        @foreach($entities as $author)
        <tr>
            <td>{{ $author['id'] }}</td>
            <td>{{ $author['name'] }}</td>
            <td>{{ $author['nationality'] }}</td>
            <td>{{ $author['age'] }}</td>
            <td>{{ $author['gender'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<footer>
    Létrehozva {{ date('Y-m-d H:i') }}
</footer>
</body>
</html>
