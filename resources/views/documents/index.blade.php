@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Documents in {{ $index }}</h2>
        <a href="{{ route('documents.create', $index) }}" class="btn btn-primary mb-2">Create Document</a>
        @if ($documents->isEmpty())
            <p>No documents found.</p>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($documents as $document)
                        <tr>
                            <td>{{ $document['name'] }}</td>
                            <td>{{ $document['description'] }}</td>
                            <td>{{ $document['price'] }}</td>
                            <td>
                                <a href="{{ route('documents.show', ['index' => $index, 'id' => $document['id']]) }}" class="btn btn-info btn-sm">View</a>
                                <a href="{{ route('documents.edit', ['index' => $index, 'id' => $document['id']]) }}" class="btn btn-primary btn-sm">Edit</a>
                                <form action="{{ route('documents.destroy', ['index' => $index, 'id' => $document['id']]) }}" method="post" style="display: inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this document?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection