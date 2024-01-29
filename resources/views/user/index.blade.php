<!-- resources/views/user/index.blade.php -->

@extends('layouts.main')

@section('content')
    <div class="container">
        <h2>User Details</h2>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(count($users) > 0)
           <!-- <div class="mt-3">
                <a href="{{ route('user.downloadZip') }}" class="btn btn-primary">Download All PDFs as ZIP</a>
            </div>-->
        @endif

        <form action="{{ route('user.sendEmails') }}" method="post">
            @csrf
            <div class="mt-3 mb-2">
                <button type="submit" class="btn btn-success">Generate PDF for Selected Rows</button>
                <button type="submit" class="btn btn-primary">Send Emails</button>
            </div>
          

            <div class="table-responsive">
                <table class="table table-bordered">
                    <!-- Table headers... -->
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Action</th>
                            <th>Select</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <!-- Table rows... -->
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    <a href="{{ route('user.pdf', $user->id) }}" class="btn btn-primary" target="_blank">Generate PDF</a>
                                    <!-- Other actions... -->
                                </td>
                                <td>
                                    <input type="checkbox" name="selected_users[]" value="{{ $user->id }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>   
        </form>

        <!-- Pagination links -->
        <div class="d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function sendEmails() {
            if (confirm('Are you sure you want to send emails?')) {
                // Assuming you have jQuery available
                $.post("{{ route('user.sendEmails') }}", {_token: "{{ csrf_token() }}"})
                    .done(function (response) {
                        //alert(response.message);
                        alert("test failed");
                    })
                    .fail(function (error) {
                        console.error(error);
                        alert('Failed to send emails.');
                    });
            }
        }
    </script>
@endsection