@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white fw-bold">
                    <i class="fas fa-user"></i> User Profile
                </div>
                <div class="card-body text-center">

                    <!-- Display User Photo -->
                    <div class="mb-3">
                        @if ($user->photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" alt="User Photo" class="rounded-circle" width="150" height="150">
                        @else
                            <img src="{{ asset('default-user.png') }}" alt="Default User" class="rounded-circle" width="150" height="150">
                        @endif
                    </div>

                    <div class="mb-3">
                        <strong>ID:</strong> {{ $user->id }}
                    </div>
                    <div class="mb-3">
                        <strong>Name:</strong> {{ $user->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong> {{ $user->email }}
                    </div>
                    <div class="mb-3">
                        <strong>Role:</strong> {{ ucfirst($user->role) }}
                    </div>
                    <div class="mb-3">
                        <strong>Status:</strong> 
                        <span class="badge bg-{{ $user->status ? 'success' : 'danger' }}">
                            {{ $user->status ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="text-center">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Users
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
