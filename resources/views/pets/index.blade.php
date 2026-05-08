@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Lista Petów</h1>

    @if (empty($pets))
        <p class="text-gray-700">Brak petów do wyświetlenia.</p>
    @else
        <table class="border border-gray-300 bg-white">
            <thead>
                <tr>
                    <th class="border border-gray-300 px-2 py-1 text-left text-sm">ID</th>
                    <th class="border border-gray-300 px-2 py-1 text-left text-sm">Nazwa</th>
                    <th class="border border-gray-300 px-2 py-1 text-left text-sm">Status</th>
                    <th class="border border-gray-300 px-2 py-1 text-left text-sm">Kategoria</th>
                    <th class="border border-gray-300 px-2 py-1 text-left text-sm">Photo URL (1)</th>
                    <th class="border border-gray-300 px-2 py-1 text-left text-sm">Tagi</th>
                    <th class="border border-gray-300 px-2 py-1 text-left text-sm">Akcje</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pets as $pet)
                    <tr>
                        <td class="border border-gray-300 px-2 py-1 text-sm">
                            {{ $pet['id'] ?? '-' }}
                        </td>
                        <td class="border border-gray-300 px-2 py-1 text-sm">
                            {{ $pet['name'] ?? '-' }}
                        </td>
                        <td class="border border-gray-300 px-2 py-1 text-sm">
                            {{ $pet['status'] ?? '-' }}
                        </td>
                        <td class="border border-gray-300 px-2 py-1 text-sm">
                            {{ $pet['category']['name'] ?? '-' }}
                        </td>
                        <td class="border border-gray-300 px-2 py-1 text-sm">
                            @if (!empty($pet['photoUrls'][0]))
                                <a href="{{ $pet['photoUrls'][0] }}" target="_blank" class="text-blue-600 hover:underline">
                                    {{ $pet['photoUrls'][0] }}
                                </a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="border border-gray-300 px-2 py-1 text-sm">
                            @if (!empty($pet['tags']))
                                {{ collect($pet['tags'])->pluck('name')->filter()->join(', ') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="border border-gray-300 px-2 py-1 text-sm">
                            @if (isset($pet['id']))
                                <a href="{{ route('pets.show', $pet['id']) }}"
                                    class="text-blue-600 hover:underline text-sm">
                                    Pokaż
                                </a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
