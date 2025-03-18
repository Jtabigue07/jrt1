@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Users Management') }}</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <table id="users-table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let userShowRoute = @json(route('admin.users.show', ':id'));

    $(document).ready(function() {
        $('#users-table').DataTable({
            processing: true,
            serverSide: false,
            ajax: "{{ route('admin.users.list') }}",
            columns: [
                { data: 'id' },
                { data: 'name' },
                { data: 'email' },
                { 
                    data: 'role',
                    render: function(data, type, row) {
                        return `
                            <select class="form-select user-role" data-id="${row.id}">
                                <option value="user" ${data === 'user' ? 'selected' : ''}>User</option>
                                <option value="admin" ${data === 'admin' ? 'selected' : ''}>Admin</option>
                            </select>
                        `;
                    }
                },
                { 
                    data: 'status',
                    render: function(data, type, row) {
                        return `
                            <div class="form-check form-switch">
                                <input class="form-check-input user-status" type="checkbox" 
                                       id="status-${row.id}" data-id="${row.id}" 
                                       ${data ? 'checked' : ''}>
                                <label class="form-check-label" for="status-${row.id}">
                                    ${data ? 'Active' : 'Inactive'}
                                </label>
                            </div>
                        `;
                    }
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        let url = userShowRoute.replace(':id', row.id);
                        return `<a href="${url}" class="btn btn-sm btn-info">View</a>`;
                    }
                }
            ]
        });
    });
</script>
@endpush

