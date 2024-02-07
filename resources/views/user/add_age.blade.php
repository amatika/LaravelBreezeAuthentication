<!-- resources/views/user/index.blade.php -->

@extends('layouts.main')

@section('content')
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <form action="{{ route('ages.save') }}" method="post">
        @csrf
        <button type="submit" class="btn btn-primary mb-1">Save Ages</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Age</th>
                </tr>
            </thead>
            <tbody>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <input type="number" name="ages[{{ $user->id }}]" value="{{ $user->age }}" class="age-input">
                            <span class="error-message" style="color: red;"></span>
                        </td>
                     </tr>
                @endforeach                              
            </tbody>
        </table>
        {{ $users->links() }}       
    </form>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    
    <script>
        $(document).ready(function () {
            // Capture arrow key events
            $('.age-input').keydown(function (e) {
                if (e.which === 38 || e.which === 40) { // Up or Down arrow keys
                    e.preventDefault();

                    // Get the index of the current input
                    var currentIndex = $('.age-input').index(this);

                    // Calculate the index of the next input
                    var nextIndex = (e.which === 38) ? currentIndex - 1 : currentIndex + 1;

                    // Validate the current input
                    var inputValue = parseInt($(this).val());
                    if (isNaN(inputValue) || inputValue < 0 || inputValue > 100) {
                        $(this).css('border-color', 'red');
                        $(this).next('.error-message').text('Invalid input (0-100)');
                        return;
                    } else {
                        $(this).css('border-color', ''); // Reset border color
                        $(this).next('.error-message').text(''); // Clear error message
                    }

                    // Save the data to the database
                    var currentId = $(this).attr('name').match(/\d+/)[0];
                    saveData(currentId, inputValue);

                    // Focus on the next input if it exists
                    if ($('.age-input').eq(nextIndex).length) {
                        $('.age-input').eq(nextIndex).focus();
                    }
                }
            });

            function saveData(id, age) {
                // Implement your logic to save data to the database using Ajax or other methods
                // For simplicity, let's assume you have a route to handle saving data
                $.ajax({
                    type: 'POST',
                    url: '{{ route('ages.save') }}',
                    data: { '_token': '{{ csrf_token() }}', 'ages': { [id]: age } },
                    success: function(response) {
                        // Handle success if needed
                    },
                    error: function(error) {
                        // Handle error if needed
                    }
                });
            }
        });
    </script>
@endsection
