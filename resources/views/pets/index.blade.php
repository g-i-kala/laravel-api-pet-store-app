@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Lista Petów</h1>

    @if (empty($pets))
        <p class="text-gray-700">Brak petów do wyświetlenia (jeszcze nie podłączyliśmy API).</p>
    @else
        <table class="border border-gray-300 bg-white">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-2 py-1 text-left text-sm">ID</th>
                    <th class="border border-gray-300 px-2 py-1 text-left text-sm">Nazwa</th>
                    <th class="border border-gray-300 px-2 py-1 text-left text-sm">Status</th>
                    <th class="border border-gray-300 px-2 py-1 text-left text-sm">Akcje</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pets as $pet)
                    <tr>
                        <td class="border border-gray-300 px-2 py-1 text-sm">{{ $pet['id'] }}</td>
                        <td class="border border-gray-300 px-2 py-1 text-sm">{{ $pet['name'] }}</td>
                        <td class="border border-gray-300 px-2 py-1 text-sm">{{ $pet['status'] }}</td>
                        <td class="border border-gray-300 px-2 py-1 text-sm">
                            <a href="{{ route('pets.show', $pet['id']) }}" class="text-blue-600 hover:underline text-sm">
                                Pokaż
                            </a>
                            {{-- tu później dodamy Edytuj/Usuń --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
