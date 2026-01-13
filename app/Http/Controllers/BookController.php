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

            $books = $response->json('books') ?? [];

            $categoriesResponse = Http::api()->get('categories');
            $authorsResponse = Http::api()->get('authors');

            $categories = $categoriesResponse->json('categories') ?? [];
            $authors = $authorsResponse->json('authors') ?? [];

            $categoryNames = [];
            foreach ($categories as $cat) {
                $categoryNames[$cat['id']] = $cat['name'];
            }

            $authorNames = [];
            foreach ($authors as $auth) {
                $authorNames[$auth['id']] = $auth['name'];
            }

            foreach ($books as &$book) {
                $book['category_name'] = $categoryNames[$book['category_id']] ?? 'Ismeretlen';
                $book['author_name'] = $authorNames[$book['author_id']] ?? 'Ismeretlen';
            }

            return view('books.index', [
                'entities' => $books,
                'isAuthenticated' => $this->isAuthenticated(),
            ]);

        } catch (\Exception $e) {
            return redirect()->route('books.index')->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        $categories = Http::api()->get('categories')->json('categories') ?? [];

        $authors = Http::api()->get('authors')->json('authors') ?? [];

        return view('books.create', [
            'categories' => $categories,
            'authors' => $authors,
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'price' => 'required|numeric',
            'publication_date' => 'required|date',
            'edition' => 'nullable|string|max:50',
            'author_id' => 'required|integer',
            'isbn' => 'nullable|string|max:20',
            'cover' => 'nullable|image|max:2048',
        ]);
    
        $data = $request->only([
            'name','category_id','price','publication_date','edition',
            'author_id','isbn'
        ]);
    
        if ($request->hasFile('cover')) {
            $file = $request->file('cover');
            $path = $file->store('covers', 'public'); 
            $data['cover'] = $path;
        }
    
        try {
            $response = Http::api()->withToken(session('api_token'))->post('/books', $data);
    
            if ($response->failed()) {
                $message = $response->json('message') ?? 'Nem sikerült létrehozni a könyvet.';
                return redirect()->route('books.create')->with('error', $message);
            }
    
            return redirect()->route('books.index')->with('success', "{$data['name']} könyv sikeresen létrehozva!");
        } catch (\Exception $e) {
            return redirect()->route('books.create')->with('error', $e->getMessage());
        }
    }
    


    public function edit($id)
    {
        $response = Http::api()->get("books/$id");
        $book = $response->json() ?? [];
    
        $entity = [
            'id' => $book['id'] ?? $id, 
            'name' => $book['name'] ?? '',
            'category_id' => $book['category_id'] ?? null,
            'price' => $book['price'] ?? '',
            'publication_date' => $book['publication_date'] ?? '',
            'edition' => $book['edition'] ?? '',
            'author_id' => $book['author_id'] ?? null,
            'isbn' => $book['isbn'] ?? '',
            'cover' => $book['cover'] ?? '',
        ];

        $categories = Http::api()->get('categories')->json('categories') ?? [];
        $authors = Http::api()->get('authors')->json('authors') ?? [];

        return view('books.edit', [
            'entity' => $entity,
            'categories' => $categories,
            'authors' => $authors,
        ]);
    }




    public function update(Request $request, $id)
    {
        $data = $request->only([
            'name','category_id','price','publication_date','edition',
            'author_id','isbn','cover'
        ]);

        try {
            $response = Http::api()->withToken(session('api_token'))->put("/books/$id", $data);

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
            $response = Http::api()->withToken(session('api_token'))->delete("/books/$id");

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
        $booksResponse = Http::api()->get('books');
        $books = $booksResponse->json('books') ?? [];

        // Kategóriák és szerzők
        $categoriesResponse = Http::api()->get('categories');
        $authorsResponse = Http::api()->get('authors');

        $categories = $categoriesResponse->json('categories') ?? [];
        $authors = $authorsResponse->json('authors') ?? [];

        $categoryNames = [];
        foreach ($categories as $cat) {
            $categoryNames[$cat['id']] = $cat['name'];
        }

        $authorNames = [];
        foreach ($authors as $auth) {
            $authorNames[$auth['id']] = $auth['name'];
        }

        foreach ($books as &$book) {
            $book['category_name'] = $categoryNames[$book['category_id']] ?? 'Ismeretlen';
            $book['author_name'] = $authorNames[$book['author_id']] ?? 'Ismeretlen';
        }

        $filename = 'books_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'no-store, no-cache',
            'Expires' => '0',
        ];

        $callback = function () use ($books) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'ID',
                'Név',
                'Kategória',
                'Ár',
                'Megjelenés',
                'Kiadás',
                'Szerző',
                'ISBN'
            ]);

            foreach ($books as $book) {
                fputcsv($handle, [
                    $book['id'] ?? '',
                    $book['name'] ?? '',
                    $book['category_name'] ?? '',
                    $book['price'] ?? '',
                    $book['publication_date'] ?? '',
                    $book['edition'] ?? '',
                    $book['author_name'] ?? '',
                    $book['isbn'] ?? '',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $booksResponse = Http::api()->get('books');
        $books = $booksResponse->json('books') ?? [];

        $categoriesResponse = Http::api()->get('categories');
        $authorsResponse = Http::api()->get('authors');

        $categories = $categoriesResponse->json('categories') ?? [];
        $authors = $authorsResponse->json('authors') ?? [];

        $categoryNames = [];
        foreach ($categories as $cat) {
            $categoryNames[$cat['id']] = $cat['name'];
        }

        $authorNames = [];
        foreach ($authors as $auth) {
            $authorNames[$auth['id']] = $auth['name'];
        }

        foreach ($books as &$book) {
            $book['category_name'] = $categoryNames[$book['category_id']] ?? 'Ismeretlen';
            $book['author_name'] = $authorNames[$book['author_id']] ?? 'Ismeretlen';
        }

        $pdf = Pdf::loadView('books.pdf', ['books' => $books]);

        return $pdf->download('books_' . date('Y-m-d_H-i-s') . '.pdf');
    }
}
