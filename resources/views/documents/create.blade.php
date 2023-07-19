@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Create Document</h2>
        <form method="post" action="{{ route('documents.store', $index) }}">
            @csrf
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description"></textarea>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" class="form-control" id="price" name="price" required>
            </div>
            <button type="submit" class="btn btn-primary">Create Document</button>
        </form>
    </div>
@endsection
