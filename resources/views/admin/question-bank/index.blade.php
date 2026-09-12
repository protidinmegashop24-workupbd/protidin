@extends('backend.layouts.master')

@section('title','Question Bank (Admin)')
@section('back-content')

<div class="container-fluid mt-3">

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Question Bank (Admin)</h3>
    <div class="d-flex gap-2">
      <a href="{{ route('admin.question-bank.bulk-upload') }}" class="btn btn-outline-primary">⬆ Bulk Upload (CSV)</a>
      <a href="{{ route('admin.question-bank.create') }}" class="btn btn-primary">+ Add Question</a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success" style="font-weight:900;">{{ session('success') }}</div>
  @endif

  <div class="mb-3 d-flex gap-2 flex-wrap">
    <a href="{{ route('admin.question-bank.index') }}" class="btn btn-sm {{ $topic ? 'btn-outline-dark' : 'btn-dark' }}">
      All ({{ array_sum($counts) }})
    </a>
    <a href="{{ route('admin.question-bank.index', ['topic' => 'general']) }}" class="btn btn-sm {{ $topic === 'general' ? 'btn-dark' : 'btn-outline-dark' }}">
      General ({{ $counts['general'] }})
    </a>
    <a href="{{ route('admin.question-bank.index', ['topic' => 'islamic']) }}" class="btn btn-sm {{ $topic === 'islamic' ? 'btn-dark' : 'btn-outline-dark' }}">
      Islamic ({{ $counts['islamic'] }})
    </a>
    <a href="{{ route('admin.question-bank.index', ['topic' => 'bangladesh_gk']) }}" class="btn btn-sm {{ $topic === 'bangladesh_gk' ? 'btn-dark' : 'btn-outline-dark' }}">
      Bangladesh GK ({{ $counts['bangladesh_gk'] }})
    </a>
    <a href="{{ route('admin.question-bank.index', ['topic' => 'sports']) }}" class="btn btn-sm {{ $topic === 'sports' ? 'btn-dark' : 'btn-outline-dark' }}">
      Sports ({{ $counts['sports'] }})
    </a>
  </div>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table table-bordered mb-0">
        <thead>
          <tr>
            <th style="width:10%">Topic</th>
            <th>Question</th>
            <th style="width:28%">Options</th>
            <th style="width:14%">Correct Answer</th>
            <th style="width:110px;">Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($questions as $q)
            <tr>
              <td>{{ ucfirst($q->topic) }}</td>
              <td>{{ $q->question }}</td>
              <td>{{ implode(', ', (array) $q->options) }}</td>
              <td>{{ $q->correct_option }}</td>
              <td>
                <a href="{{ route('admin.question-bank.edit', $q->id) }}" class="btn btn-success btn-sm">Edit</a>
                <form action="{{ route('admin.question-bank.destroy', $q->id) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Delete this question?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center">No questions yet. Click "Add Question" to create the first one.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <div class="mt-3">
    {{ $questions->links() }}
  </div>
</div>

@endsection
