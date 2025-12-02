<?php 

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
                return redirect()
                    ->route('books.index')
                    ->with('error', "Hiba történt a lekérdezés során: $message");
            }

            $entities = ResponseHelper::getData($response);

            return view('books.index', ['entities' => $entities, 'isAuthenticated' => $this->isAuthenticated()]);

        } catch (\Exception $e) {
            return redirect()
                ->route('books.index')
                ->with('error', "Nem sikerült betölteni a könyveket: " . $e->getMessage());
        }

    }

    public function show($id)
    {
        try {
            $response = Http::api()->get("/books/$id");

            if ($response->failed()) {
                $message = $response->json('message') ?? 'A könyv nem található vagy hiba történt.';
                return redirect()
                    ->route('books.index')
                    ->with('error', "Hiba: $message");
            }

            $body = $response->json();
            $entity = $body['book'] ?? null;

            if (!$entity) {
                return redirect()
                    ->route('books.index')
                    ->with('error', "A könyvek adatai nem érhetők el.");
            }

            return view('books.show', ['entity' => $entity]);

        } catch (\Exception $e) {
            return redirect()
                ->route('books.index')
                ->with('error', "Nem sikerült betölteni a könyv adatait: " . $e->getMessage());
        }
    }

    public function create()
    {
        return view('books.create');
    }
	

    public function store(AuthorRequest $request)
    {
        $name = $request->get('name');

        try {
            $response = Http::api()
                ->withToken($this->token)
                ->post('/books', ['name' => $name]);

            if ($response->failed()) {
                // Ha az API válaszolt, de hibás státuszkóddal (pl. 422, 403, 500)
                $message = $response->json('message') ?? 'Nem sikerült létrehozni a könyvet.';
                return redirect()
                    ->route('books.index')
                    ->with('error', "Hiba: $message");
            }

            return redirect()
                ->route('books.index')
                ->with('success', "$name könyv sikeresen létrehozva!");

        } catch (\Exception $e) {
            // Hálózati vagy JSON dekódolási hiba
            return redirect()
                ->route('books.index')
                ->with('error', "Nem sikerült kommunikálni az API-val: " . $e->getMessage());
        }

    }

	public function edit($id)
    {
        try {
            $response = Http::api()->get("/books/$id");

            if ($response->failed()) {
                $message = $response->json('message') ?? 'A könyv nem található vagy hiba történt.';
                return redirect()
                    ->route('books.index')
                    ->with('error', "Hiba: $message");
            }

            $body = $response->json();
            $entity = $body['book'] ?? null;

            if (!$entity) {
                return redirect()
                    ->route('books.index')
                    ->with('error', "A könyv adatai nem érhetők el.");
            }

            return view('books.edit', ['entity' => $entity]);

        } catch (\Exception $e) {
            return redirect()
                ->route('books.index')
                ->with('error', "Nem sikerült betölteni a könyv szerkesztő nézetét: " . $e->getMessage());
        }
    }


    public function update(BookRequest $request, $id)
    {
        $name = $request->get('name');

        try {
            $response = Http::api()
                ->withToken($this->token)
                ->put("/books/$id", ['name' => $name]);

            if ($response->successful()) {
                return redirect()
                    ->route('books.index')
                    ->with('success', "$name könyv sikeresen frissítve!");
            }

            // Ha nem sikeres, de nem dobott kivételt (pl. 422)
            $errorMessage = $response->json('message') ?? 'Ismeretlen hiba történt.';
            return redirect()
                ->route('books.index')
                ->with('error', "Hiba történt: $errorMessage");

        } catch (\Exception $e) {
            // Hálózati vagy egyéb kivétel
            return redirect()
                ->route('authors.index')
                ->with('error', "Nem sikerült frissíteni: " . $e->getMessage());
        }
    }


    public function destroy($id)
    {
        try {
            $response = Http::api()
                ->withToken($this->token)
                ->delete("/books/$id", ['id' => $id]);

            if ($response->failed()) {
                $message = $response->json('message') ?? 'Nem sikerült törölni a könyvet.';
                return redirect()
                    ->route('books.index')
                    ->with('error', "Hiba: $message");
            }

            $body = $response->json();
            $name = $body['name'] ?? 'Ismeretlen';

            return redirect()
                ->route('books.index')
                ->with('success', "$name könyv sikeresen törölve!");

        } catch (\Exception $e) {
            return redirect()
                ->route('books.index')
                ->with('error', "Nem sikerült kommunikálni az API-val: " . $e->getMessage());
        }
    }

}