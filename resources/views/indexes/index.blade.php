@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Indexes</h1>

        @if ($indexes->isEmpty())
            <p>Không tìm thấy bất kỳ index nào trong Elasticsearch.</p>
        @else
            <ul>
                @foreach ($indexes as $index)
                    <li>
                        {{ $index }}
                        <a href="{{ route('documents.index', ['index' => $index]) }}" class="btn btn-primary btn-sm">Show Documents</a>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection