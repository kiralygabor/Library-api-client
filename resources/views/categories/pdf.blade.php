<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kategóriák PDF</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        header, footer { text-align: center; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #3498db; color: #fff; }
    </style>
</head>
<body>
    <header>
        <img src="{{ public_path('logo.png') }}" alt="Logo" width="100">
        <h2>Kategóriák</h2>
    </header>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Név</th>
            </tr>
        </thead>
        <tbody>
            @foreach($entities as $category)
            <tr>
                <td>{{ $category['id'] }}</td>
                <td>{{ $category['name'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <footer>
        <p>Generated at {{ date('Y-m-d H:i') }}</p>
    </footer>
</body>
</html>
