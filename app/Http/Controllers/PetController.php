<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // narazie na sztywno
        $pets = [
            ['id' => 1,
            'category' => 'pluszak',
            'name' => 'uszatek',],
            ['id' => 2,
            'category' => 'pluszak2',
            'name' => 'uszatex',],
        ];

        return view('pets.index', [
            'pets' => $pets,
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
            'name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'in:available,pending,sold'],
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
        //
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
