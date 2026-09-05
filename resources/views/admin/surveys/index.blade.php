@extends('backend.layouts.master')

@section('title','Surveys (Admin)')
@section('back-content')

<div class="container-fluid mt-3">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Surveys (Admin)</h3>
    <a href="{{ route('admin.surveys.create') }}" class="btn btn-primary">+ Create Survey</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success" style="font-weight:900;">{{ session('success') }}</div>
  @endif

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-bordered mb-0">
        <thead>
          <tr>
            <th>Title</th>
            <th>Topic</th>
            <th>Reward (USD)</th>
            <th>Questions/day</th>
            <th>Active</th>
            <th style="width:120px;">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($surveys as $s)
            <tr>
              <td>{{ $s->title }}</td>
              <td>{{ $s->topic }}</td>
              <td>${{ number_format((float)$s->reward, 4) }}</td>
              <td>{{ (int)$s->questions_per_attempt }}</td>
              <td>{{ $s->is_active ? 'Yes' : 'No' }}</td>
              <td>
                <form action="{{ route('admin.surveys.destroy', $s->id) }}" method="POST"
                      onsubmit="return confirm('Delete this survey?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    {{ $surveys->links() }}
  </div>
</div>

@endsection