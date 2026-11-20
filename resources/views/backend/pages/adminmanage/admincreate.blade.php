@extends('backend.layouts.master')

@section('content')
<div class="container">
    <h2>Create New Account</h2>

    <!-- ফর্মটি পোস্ট করে AdminController এর store মেথডে পাঠানো হবে -->
    <form action="{{ route('admin.account.store') }}" method="POST">
        @csrf
        <!-- Name Field -->
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" required>
        </div>

        <!-- Email Field -->
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email" required>
        </div>

        <!-- Password Field -->
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
        </div>

        <!-- Role Select Field -->
        <div class="form-group">
            <label for="role">Role</label>
            <select class="form-control" id="role" name="role_id" required>
                <option value="">Select Role</option>
                <option value="1">Admin</option>
                <option value="2">Sub Admin</option>
            </select>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Create Account</button>
    </form>
</div>
@endsection
