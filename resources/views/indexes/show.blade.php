@extends('layouts.app')

@section('content')
    <h1>Documents of {{ $index }}</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($hits as $hit)
                <tr>
                    <td>{{ $hit['_source']['name'] }}</td>
                    <td>{{ $hit['_source']['description'] }}</td>
                    <td>{{ $hit['_source']['price'] }}</td>
                    <td>
                        <a href="{{ route('documents.edit', ['index' => $index, 'id' => $hit['_id']]) }}" class="btn btn-primary">Edit</a>
                        <form action="{{ route('documents.destroy', ['index' => $index, 'id' => $hit['_id']]) }}" method="POST" style="display: inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this document?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('index.show') }}" class="btn btn-secondary">Back to Indexes</a>
@endsection
