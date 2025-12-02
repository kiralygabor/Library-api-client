<?php 

namespace App\Http\Controllers;

use App\Http\Requests\AuthorRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthorController extends Controller
{

    public function index(Request $request)
    {
        $needle = $request->get('needle');

        try {
            $url = $needle ? "authors?needle=" . urlencode($needle) : "authors";

            $response = Http::api()->get($url);

            if ($response->failed()) {
                $message = $response->json('message') ?? 'Ismeretlen hiba történt.';
                return redirect()
                    ->route('authors.index')
                    ->with('error', "Hiba történt a lekérdezés során: $message");
            }

            $entities = ResponseHelper::getData($response);

            return view('authors.index', ['entities' => $entities, 'isAuthenticated' => $this->isAuthenticated()]);

        } catch (\Exception $e) {
            return redirect()
                ->route('authors.index')
                ->with('error', "Nem sikerült betölteni a szerzőket: " . $e->getMessage());
        }

    }

    public function show($id)
    {
        try {
            $response = Http::api()->get("/authors/$id");

            if ($response->failed()) {
                $message = $response->json('message') ?? 'A szerző nem található vagy hiba történt.';
                return redirect()
                    ->route('authors.index')
                    ->with('error', "Hiba: $message");
            }

            $body = $response->json();
            $entity = $body['author'] ?? null;

            if (!$entity) {
                return redirect()
                    ->route('authors.index')
                    ->with('error', "A szerző adatai nem érhetők el.");
            }

            return view('authors.show', ['entity' => $entity]);

        } catch (\Exception $e) {
            return redirect()
                ->route('authors.index')
                ->with('error', "Nem sikerült betölteni a szerző adatait: " . $e->getMessage());
        }
    }

    public function create()
    {
        return view('authors.create');
    }
	

    public function store(AuthorRequest $request)
    {
        $name = $request->get('name');

        try {
            $response = Http::api()
                ->withToken($this->token)
                ->post('/authors', ['name' => $name]);

            if ($response->failed()) {
                // Ha az API válaszolt, de hibás státuszkóddal (pl. 422, 403, 500)
                $message = $response->json('message') ?? 'Nem sikerült létrehozni a szerzőt.';
                return redirect()
                    ->route('authors.index')
                    ->with('error', "Hiba: $message");
            }

            return redirect()
                ->route('authors.index')
                ->with('success', "$name szerző sikeresen létrehozva!");

        } catch (\Exception $e) {
            // Hálózati vagy JSON dekódolási hiba
            return redirect()
                ->route('authors.index')
                ->with('error', "Nem sikerült kommunikálni az API-val: " . $e->getMessage());
        }

    }

	public function edit($id)
    {
        try {
            $response = Http::api()->get("/authors/$id");

            if ($response->failed()) {
                $message = $response->json('message') ?? 'A szerző nem található vagy hiba történt.';
                return redirect()
                    ->route('authors.index')
                    ->with('error', "Hiba: $message");
            }

            $body = $response->json();
            $entity = $body['author'] ?? null;

            if (!$entity) {
                return redirect()
                    ->route('authors.index')
                    ->with('error', "A szerző adatai nem érhetők el.");
            }

            return view('authors.edit', ['entity' => $entity]);

        } catch (\Exception $e) {
            return redirect()
                ->route('authors.index')
                ->with('error', "Nem sikerült betölteni a szerző szerkesztő nézetét: " . $e->getMessage());
        }
    }


    public function update(AuthorRequest $request, $id)
    {
        $name = $request->get('name');

        try {
            $response = Http::api()
                ->withToken($this->token)
                ->put("/authors/$id", ['name' => $name]);

            if ($response->successful()) {
                return redirect()
                    ->route('authors.index')
                    ->with('success', "$name szerző sikeresen frissítve!");
            }

            // Ha nem sikeres, de nem dobott kivételt (pl. 422)
            $errorMessage = $response->json('message') ?? 'Ismeretlen hiba történt.';
            return redirect()
                ->route('authors.index')
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
                ->delete("/authors/$id", ['id' => $id]);

            if ($response->failed()) {
                $message = $response->json('message') ?? 'Nem sikerült törölni a szerzőt.';
                return redirect()
                    ->route('authors.index')
                    ->with('error', "Hiba: $message");
            }

            $body = $response->json();
            $name = $body['name'] ?? 'Ismeretlen';

            return redirect()
                ->route('authors.index')
                ->with('success', "$name szerző sikeresen törölve!");

        } catch (\Exception $e) {
            return redirect()
                ->route('authors.index')
                ->with('error', "Nem sikerült kommunikálni az API-val: " . $e->getMessage());
        }
    }

}