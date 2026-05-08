{{-- resources/views/pets/show.blade.php --}}
@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Zwierzak - w szczegółach.</h1>

    <table class="border border-gray-300 bg-white">
        <thead>
            <tr>
                <th class="border border-gray-300 px-2 py-1 text-left text-sm">ID</th>
                <th class="border border-gray-300 px-2 py-1 text-left text-sm">Cat</th>
                <th class="border border-gray-300 px-2 py-1 text-left text-sm">Name</th>
                <th class="border border-gray-300 px-2 py-1 text-left text-sm">Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="border border-gray-300 px-2 py-1 text-sm">{{ $pet['id'] }}</td>
                <td class="border border-gray-300 px-2 py-1 text-sm">{{ $pet['category'] }}</td>
                <td class="border border-gray-300 px-2 py-1 text-sm">{{ $pet['name'] }}</td>
                <td class="border border-gray-300 px-2 py-1 text-sm">{{ $pet['status'] }}</td>
            </tr>
        </tbody>
    </table>
@endsection
