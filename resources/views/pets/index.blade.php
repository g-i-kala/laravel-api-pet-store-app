<h1>Zwierzaki z Api</h1>
<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>ID</th>
            <th>Cat</th>
            <th>Name</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($pets as $pet)
            <tr>
                <td>{{ $pet['id'] }}</td>
                <td>{{ $pet['category'] }}</td>
                <td>{{ $pet['name'] }}</td>
                <td>{{ $pet['status'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
