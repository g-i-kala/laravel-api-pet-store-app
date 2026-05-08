<?php

namespace App\Http\Controllers;

use App\Clients\PetStoreClient;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Request;

class PetController extends Controller
{
    // rejestracja klienta
    public function __construct(
        protected PetStoreClient $petstoreClient
    ) {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'available');
        // narazie na sztywno
        try {
            $pets = $this->petstoreClient->findPetsByStatus($status);
        } catch (ConnectionException $e) {
            // brak odpowiedzi API
            return back()->with('error', 'Brak odpowiedzi z API Petstore. Spróbuj ponownie później.');
        } catch (RequestException $e) {
            // błąd HTTP (4xx/5xx)
            return back()->with('error', 'Błąd podczas pobierania listy petów z API.');
        }

        return view('pets.index', [
            'pets' => $pets,
            'status' => $status,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pets.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // pozniej do FormRequest
        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'category_name' => ['nullable', 'string', 'max:255'],
            'photo_urls'    => ['nullable', 'string'],
            'tags'          => ['nullable', 'string'],
            'status'        => ['required', 'in:available,pending,sold'],
        ]);

        // wysłanie do API POST
        // dd($validated)

        // przekierowanie -zakładamy sukces na ten moment
        return redirect()
            ->route('pets.index')
            ->with('success', "Zwierzak dodany.");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // pobranie zwierzaka o id
        $pet = [
            'id' => $id,
            'name' => "Sztywny pet",
            'status' => 'available',
            'category' => ['id' => 1, 'name' => 'Psy'],
            'photoUrls' => ['https://example.com/photo1.jpg'],
            'tags' => [
                ['id' => 1, 'name' => 'tag1'],
                ['id' => 2, 'name' => 'tag2'],
            ],
        ];

        return view('pets.show', [
            'pet' => $pet,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
