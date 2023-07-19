@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Edit Document</h2>
        <form method="post" action="{{ route('documents.update', ['index' => $index, 'id' => $document['id']]) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $document['name'] }}" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description">{{ $document['description'] }}</textarea>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" class="form-control" id="price" name="price" value="{{ $document['price'] }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Update Document</button>
        </form>
    </div>
@endsection
