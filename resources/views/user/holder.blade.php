<!--
@section('content')
    <div class="container">
        <h2>User Details</h2>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="mt-3">
            <a href="{{ route('users.export') }}" class="btn btn-success">Export to Excel</a><br>
        </div><br>
        <div class="table-responsive mt-1">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Delete</th>
                        <th>Edit</th>
                        <th>Report</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                </form>
                            </td>
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editModal{{ $user->id }}">Edit</a>
                            </td>
                            <td>
                                <a href="{{ route('user.pdf', $user->id) }}" class="btn btn-info" target="_blank">Generate PDF</a>
                                
                            </td>
                        </tr>

                        <div class="modal fade" id="editModal{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $user->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel{{ $user->id }}">Edit User</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">                                      
                                        <form action="{{ route('users.edit', $user->id) }}" method="GET">
                                            <button type="submit" class="btn btn-primary">Edit User</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>                      
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center">
            {{ $users->links() }}
        </div>

    </div>
@endsection-->