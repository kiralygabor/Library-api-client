<?php

namespace App\Http\Controllers;

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
                return redirect()->route('authors.index')->with('error', $message);
            }

            $entities = $response->json('authors') ?? [];

            return view('authors.index', [
                'entities' => $entities,
                'isAuthenticated' => auth()->check()
            ]);
        } catch (\Exception $e) {
            return redirect()->route('authors.index')->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        return view('authors.create');
    }

    public function store(Request $request)
    {
        $data = $request->only(['name','nationality','age','gender']);

        try {
            $response = Http::api()->withToken(session('token'))->post('/authors', $data);

            if ($response->failed()) {
                $message = $response->json('message') ?? 'Nem sikerült létrehozni a szerzőt.';
                return redirect()->route('authors.index')->with('error', $message);
            }

            return redirect()->route('authors.index')->with('success', "{$data['name']} szerző sikeresen létrehozva!");
        } catch (\Exception $e) {
            return redirect()->route('authors.index')->with('error', $e->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        $entity = [
            'id' => $id,
            'name' => $request->get('name'),
            'nationality' => $request->get('nationality'),
            'age' => $request->get('age'),
            'gender' => $request->get('gender'),
        ];

        return view('authors.edit', ['entity' => $entity]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(['name','nationality','age','gender']);

        try {
            $response = Http::api()->withToken(session('token'))->put("/authors/$id", $data);

            if ($response->failed()) {
                $message = $response->json('message') ?? 'Nem sikerült frissíteni a szerzőt.';
                return redirect()->route('authors.index')->with('error', $message);
            }

            return redirect()->route('authors.index')->with('success', "{$data['name']} szerző sikeresen frissítve!");
        } catch (\Exception $e) {
            return redirect()->route('authors.index')->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::api()->withToken(session('token'))->delete("/authors/$id");

            if ($response->failed()) {
                $message = $response->json('message') ?? 'Nem sikerült törölni a szerzőt.';
                return redirect()->route('authors.index')->with('error', $message);
            }

            $name = $response->json('name') ?? 'Ismeretlen';
            return redirect()->route('authors.index')->with('success', "$name szerző sikeresen törölve!");
        } catch (\Exception $e) {
            return redirect()->route('authors.index')->with('error', $e->getMessage());
        }
    }
}
