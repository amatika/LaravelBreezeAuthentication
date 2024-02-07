<!-- resources/views/allowance_user_table.blade.php -->

@extends('layouts.main')

@section('content')
    <div class="container">
        <label for="yearSelect">Select Year:</label>
        <select id="yearSelect" class="form-select mb-3" onchange="this.form.submit()" name="year">
            @foreach($years as $year)
                <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>{{ $year }}</option>
            @endforeach
        </select>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    @foreach($allowances as $allowance)
                        <th>{{ $allowance->name }}</th>
                    @endforeach
                    <th>Total</th>
                    <th>Average</th>
                    <th>Grade</th>
                    <th>Position</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        @php
                            $totalAmount = 0;
                        @endphp
                        @foreach($allowances as $allowance)
                            @php
                                $amount = $tableData->where('user_id', $user->id)
                                    ->where('allowance_id', $allowance->id)
                                    ->first();
                                $totalAmount += $amount ? $amount->amount : 0;
                            @endphp
                            <td>{{ $amount ? $amount->amount : 0 }}</td>
                        @endforeach
                        <td>{{ $totalAmount }}</td>

                        @php
                            $average = $averages[$user->id] ?? 0;
                            $grade = $grades[$user->id] ?? '';
                            $position = $positions->search($average) + 1;
                        @endphp

                        <td>{{ $average }}</td>
                        <td>{{ $grade }}</td>
                        <td>{{ $position }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        document.getElementById('yearSelect').addEventListener('change', function () {
            window.location.href = '/allowances?year=' + this.value;
        });
    </script>
@endsection
