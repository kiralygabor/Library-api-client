<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;

class BookController extends Controller
{
    public function index(Request $request)
{
    $needle = $request->get('needle'); 

    try {
        $url = $needle ? "books?needle=" . urlencode($needle) : "books";
        $response = Http::api()->get($url);

        if ($response->failed()) {
            $message = $response->json('message') ?? 'Ismeretlen hiba történt.';
            return redirect()->route('books.index')->with('error', $message);
        }

        $entities = $response->json('books') ?? [];

        return view('books.index', [
            'entities' => $entities,
            'isAuthenticated' => auth()->check()
        ]);
    } catch (\Exception $e) {
        return redirect()->route('books.index')->with('error', $e->getMessage());
    }
}


    public function create()
    {
        return view('books.create');
    }

    public function store(Request $request)
    {
        $data = $request->only([
            'name','category_id','price','publication_date','edition',
            'author_id','isbn','cover'
        ]);

        try {
            $response = Http::api()->withToken(session('token'))->post('/books', $data);

            if ($response->failed()) {
                $message = $response->json('message') ?? 'Nem sikerült létrehozni a könyvet.';
                return redirect()->route('books.index')->with('error', $message);
            }

            return redirect()->route('books.index')->with('success', "{$data['name']} könyv sikeresen létrehozva!");
        } catch (\Exception $e) {
            return redirect()->route('books.index')->with('error', $e->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        $entity = [
            'id' => $id,
            'name' => $request->get('name', ''),
            'category_id' => $request->get('category_id', ''),
            'price' => $request->get('price', ''),
            'publication_date' => $request->get('publication_date', ''),
            'edition' => $request->get('edition', ''),
            'author_id' => $request->get('author_id', ''),
            'isbn' => $request->get('isbn', ''),
            'cover' => $request->get('cover', ''),
        ];

        return view('books.edit', ['entity' => $entity]);
    }



    public function update(Request $request, $id)
    {
        $data = $request->only([
            'name','category_id','price','publication_date','edition',
            'author_id','isbn','cover'
        ]);

        try {
            $response = Http::api()->withToken(session('token'))->put("/books/$id", $data);

            if ($response->failed()) {
                $message = $response->json('message') ?? 'Nem sikerült frissíteni a könyvet.';
                return redirect()->route('books.index')->with('error', $message);
            }

            return redirect()->route('books.index')->with('success', "{$data['name']} könyv sikeresen frissítve!");
        } catch (\Exception $e) {
            return redirect()->route('books.index')->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::api()->withToken(session('token'))->delete("/books/$id");

            if ($response->failed()) {
                $message = $response->json('message') ?? 'Nem sikerült törölni a könyvet.';
                return redirect()->route('books.index')->with('error', $message);
            }

            $name = $response->json('name') ?? 'Ismeretlen';
            return redirect()->route('books.index')->with('success', "$name könyv sikeresen törölve!");
        } catch (\Exception $e) {
            return redirect()->route('books.index')->with('error', $e->getMessage());
        }
    }

    public function exportCsv()
    {
        $response = Http::api()->get('books');
        $books = $response->json('books') ?? [];

        $filename = 'books_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($books) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID','Név','Kategória ID','Ár','Megjelenés','Kiadás','Szerző ID','ISBN']);
            foreach ($books as $book) {
                fputcsv($handle, [
                    $book['id'],
                    $book['name'],
                    $book['category_id'],
                    $book['price'],
                    $book['publication_date'],
                    $book['edition'],
                    $book['author_id'],
                    $book['isbn'],
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $response = Http::api()->get('books');
        $books = $response->json('books') ?? [];

        $pdf = Pdf::loadView('books.pdf', ['books' => $books]);

        return $pdf->download('books_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}
