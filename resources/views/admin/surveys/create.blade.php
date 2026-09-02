@extends('backend.layouts.master')

@section('title','Create Survey (Auto Daily Questions)')
@section('back-content')

<div class="container-fluid mt-3">
  <div class="card shadow-sm">
    <div class="card-body">
      <h4 style="font-weight:900;">Create Survey (Auto Daily Questions)</h4>

      <form method="POST" action="{{ route('admin.surveys.store') }}">
        @csrf

        <div class="mb-3">
          <label class="form-label">Title</label>
          <input class="form-control" name="title" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Reward (USD)</label>
          <input class="form-control"
       type="number"
       step="0.0001"
       min="0.0001"
       name="reward"
       required
       placeholder="Example: 0.005">
        </div>

        <div class="mb-3">
          <label class="form-label">Topic</label>
          <select class="form-control" name="topic" required>
            <option value="islamic">Islamic</option>
            <option value="general">General</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Questions per day (per attempt)</label>
          <input class="form-control" type="number" name="questions_per_attempt" value="10" min="5" max="50" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Description (optional)</label>
          <textarea class="form-control" name="description" rows="3"></textarea>
        </div>

        <button class="btn btn-success">Create</button>
        <a href="{{ route('admin.surveys.index') }}" class="btn btn-secondary">Back</a>
      </form>
    </div>
  </div>
</div>
@endsection