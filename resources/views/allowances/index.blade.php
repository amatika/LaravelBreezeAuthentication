@extends('layouts.main')

@section('content')
    <div class="container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    @foreach($allowances as $allowance)
                        <th>{{ $allowance->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                {{-- Add your table body content here --}}
            </tbody>
        </table>
    </div>
@endsection
