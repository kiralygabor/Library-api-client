<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        header { text-align: center; margin-bottom: 20px; }
        header img { max-height: 50px; }
        table { width: 100%; border-collapse: collapse; }
        table th, table td { border: 1px solid #000; padding: 5px; text-align: left; }
        footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 0.8em; }
    </style>
</head>
<body>
    <header>
        <img src="{{ public_path('logo.png') }}" alt="Logo">
        <h2>Könyvek listája</h2>
    </header>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Név</th>
                <th>Kategória ID</th>
                <th>Ár</th>
                <th>Megjelenés</th>
                <th>Kiadás</th>
                <th>Szerző ID</th>
                <th>ISBN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($books as $book)
                <tr>
                    <td>{{ $book['id'] }}</td>
                    <td>{{ $book['name'] }}</td>
                    <td>{{ $book['category_id'] }}</td>
                    <td>{{ $book['price'] }}</td>
                    <td>{{ $book['publication_date'] }}</td>
                    <td>{{ $book['edition'] }}</td>
                    <td>{{ $book['author_id'] }}</td>
                    <td>{{ $book['isbn'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <footer>
        Oldal {{ '{PAGE_NUM}' }} / {{ '{PAGE_COUNT}' }}
    </footer>
</body>
</html>
