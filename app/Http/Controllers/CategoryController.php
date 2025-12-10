<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $needle = $request->get('needle');

        try {
            $url = $needle ? "categories?needle=" . urlencode($needle) : "categories";
            $response = Http::api()->get($url);

            if ($response->failed()) {
                $message = $response->json('message') ?? 'Ismeretlen hiba történt.';
                return redirect()->route('categories.index')->with('error', $message);
            }

            $entities = $response->json('categories') ?? [];

            return view('categories.index', [
                'entities' => $entities,
                'isAuthenticated' => auth()->check()
            ]);

        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', $e->getMessage());
        }
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->only(['name']);

        try {
            $response = Http::api()->withToken(session('token'))->post('/categories', $data);

            if ($response->failed()) {
                $message = $response->json('message') ?? 'Nem sikerült létrehozni a kategóriát.';
                return redirect()->route('categories.index')->with('error', $message);
            }

            return redirect()->route('categories.index')->with('success', "{$data['name']} kategória sikeresen létrehozva!");
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', $e->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        $entity = [
            'id' => $id,
            'name' => $request->get('name'),
        ];

        return view('categories.edit', ['entity' => $entity]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(['name']);

        try {
            $response = Http::api()->withToken(session('token'))->put("/categories/$id", $data);

            if ($response->failed()) {
                $message = $response->json('message') ?? 'Nem sikerült frissíteni a kategóriát.';
                return redirect()->route('categories.index')->with('error', $message);
            }

            return redirect()->route('categories.index')->with('success', "{$data['name']} kategória sikeresen frissítve!");
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $response = Http::api()->withToken(session('token'))->delete("/categories/$id");

            if ($response->failed()) {
                $message = $response->json('message') ?? 'Nem sikerült törölni a kategóriát.';
                return redirect()->route('categories.index')->with('error', $message);
            }

            $name = $response->json('name') ?? 'Ismeretlen';
            return redirect()->route('categories.index')->with('success', "$name kategória sikeresen törölve!");
        } catch (\Exception $e) {
            return redirect()->route('categories.index')->with('error', $e->getMessage());
        }
    }
}
