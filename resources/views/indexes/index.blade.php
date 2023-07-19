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
                        
                        <form action="{{ route('indexes.destroy', ['index' => $index]) }}" method="POST">
                            @csrf
                            {{ $index }}
                            <a href="{{ route('documents.index', ['index' => $index]) }}" class="btn btn-primary">Show Documents</a>
                            <button type="submit" class="btn btn-danger">Xóa chỉ mục</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endsection