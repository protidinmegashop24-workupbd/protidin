@extends('backend.layouts.master')

@section('title','Edit Survey')
@section('back-content')

<div class="container-fluid mt-3">
  <div class="card shadow-sm">
    <div class="card-body">
      <h4 style="font-weight:900;">Edit Survey</h4>

      <form method="POST" action="{{ route('admin.surveys.update', $survey->id) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label class="form-label">Title</label>
          <input class="form-control" name="title" value="{{ old('title', $survey->title) }}" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Reward (USD)</label>
          <input class="form-control"
       type="number"
       step="0.0001"
       min="0.0001"
       name="reward"
       value="{{ old('reward', $survey->reward) }}"
       required>
        </div>

        <div class="mb-3">
          <label class="form-label">Topic</label>
          <select class="form-control" name="topic" required>
            @foreach(['islamic' => 'Islamic', 'general' => 'General', 'bangladesh_gk' => 'Bangladesh GK', 'sports' => 'Sports'] as $value => $label)
              <option value="{{ $value }}" @selected(old('topic', $survey->topic) === $value)>{{ $label }}</option>
            @endforeach
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label">Questions per day (per attempt)</label>
          <input class="form-control" type="number" name="questions_per_attempt" value="{{ old('questions_per_attempt', $survey->questions_per_attempt) }}" min="5" max="50" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Description (optional)</label>
          <textarea class="form-control" name="description" rows="3">{{ old('description', $survey->description) }}</textarea>
        </div>

        <div class="mb-3 form-check">
          <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $survey->is_active))>
          <label class="form-check-label" for="is_active">Active</label>
        </div>

        <button class="btn btn-success">Save Changes</button>
        <a href="{{ route('admin.surveys.index') }}" class="btn btn-secondary">Back</a>
      </form>
    </div>
  </div>
</div>
@endsection
