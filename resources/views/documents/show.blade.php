@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Document Details</h2>
        <p><strong>Name:</strong> {{ $document['name'] }}</p>
        <p><strong>Description:</strong> {{ $document['description'] }}</p>
        <p><strong>Price:</strong> {{ $document['price'] }}</p>
        <a href="{{ route('documents.index', $index) }}" class="btn btn-primary">Back to Index</a>
    </div>
@endsection