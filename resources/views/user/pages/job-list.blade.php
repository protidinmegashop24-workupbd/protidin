@extends('user.layouts.master')

@section('css')
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom Styles */
        .card-title {
            font-weight: 600;
            color: #fff; /* Bootstrap's success color */
            font-size: 1.25rem;
        }

        .notice-box {
            position: relative;
            overflow: hidden;
            background-color: #ffffff;
            border: 2px solid #007bff;
            border-radius: 10px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .notice-text {
            color: #28a745;
            font-size: 1rem;
            white-space: nowrap;
            animation: marquee 15s linear infinite;
        }

        @keyframes marquee {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }

        /* Table Styles */
        .table thead th {
            background-color: #f8f9fa;
            vertical-align: middle;
            text-align: center;
        }

        .table tbody td {
            vertical-align: middle;
            text-align: center;
        }

        /* Button Styles */
        .btn-action {
            /* Removed individual margin to utilize Flexbox gap */
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .card-title {
                font-size: 1rem;
            }

            .notice-text {
                font-size: 0.875rem;
            }

            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.75rem;
            }
        }
    </style>
@endsection

@section('user-content')
    <div class="card mt-4 shadow-sm">
        <div class="card-header bg-success text-white d-flex justify-content-center align-items-center" style="height: 60px; border-radius: 10px 10px 0 0;">
            <h5 class="card-title mb-0">My Posted Jobs</h5>
        </div>

        <div class="card-body">
            <!-- Notice Box -->
            <div class="notice-box mb-4 mx-auto">
                <p class="notice-text mb-0">Dear sir, please review all submitted proof of workers within 24 hours or all proofs will be approved automatically.</p>
            </div>

            <!-- Jobs Table -->
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle" id="jobsTable">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" width="5%">SL</th>
                            <th scope="col">Job Name</th>
                            <th scope="col" width="10%">Progress</th>
                            <th scope="col" width="10%">Task Price</th>
                            <th scope="col" width="10%">Total Cost</th>
                            <th scope="col" width="10%">Status</th>
                            <th scope="col" width="25%">Action</th> <!-- Increased width for better space -->
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datas as $key => $data)
                            <tr>
                                <td>{{ $data->id }}</td>
                                <td>{{ $data->title }}</td>
                                <td>
                                    {{-- complete_work_this_job($data->id) }} of {{ $data->worker_need --}}
                                    {{ $data->worker_confirmed }} of {{ $data->worker_need }}
                                </td>
                                <td>${{ number_format($data->each_worker_earn, 2) }}</td>
                                <td>${{ number_format($data->budget, 2) }}</td>
                                <td>
                                    @if ($data->worker_need <= $data->worker_confirmed)
                                        <span class="badge bg-primary">Complete</span>
                                    @else
                                        @if ($data->status == 1)
                                            <span class="badge bg-success">Approved</span>
                                        @elseif ($data->status == 2)
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-nowrap justify-content-center gap-2">
                                        @if ($data->worker_need > $data->worker_confirmed)
                                            @if(job_ready_for_boost($data->id) == 1)
                                                <button class="btn btn-primary btn-sm btn-action" onclick="boostJob({{ $data->id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="Boost Job">
                                                     Boost
                                                </button>
                                            @else
                                                <button class="btn btn-secondary btn-sm btn-action" disabled data-bs-toggle="tooltip" data-bs-placement="top" title="Boost Available in {{ remain_interval_for_boost($data->id) }} minutes">
                                                  {{ remain_interval_for_boost($data->id) }}m
                                                </button>
                                            @endif
                                        @endif

                                        @if ($data->worker_need <= $data->worker_confirmed)
                                            <button class="btn btn-warning btn-sm btn-action" onclick="updateJobWorker({{ $data->id }})" data-bs-toggle="tooltip" data-bs-placement="top" title="Update Job">
                                                Update
                                            </button>
                                            <a href="{{ route('user.job-delete', $data->id) }}" class="btn btn-danger btn-sm btn-action" onclick="return confirm('Are you sure you want to delete this job?');" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Job">
                                                Delete
                                            </a>
                                        @endif

                                        <a href="{{ route('user.job-working-proves', $data->code) }}" class="btn btn-info btn-sm btn-action" data-bs-toggle="tooltip" data-bs-placement="top" title="View Proofs">
                                            Proves
                                        </a>
                                        <a href="{{ route('user.my-job-details', $data->code) }}" class="btn btn-success btn-sm btn-action" data-bs-toggle="tooltip" data-bs-placement="top" title="View Details">
                                           Details
                                        </a>
                                    </div>
                                </td>
                            </tr>

                            <!-- Boost Job Modal -->
                            @if(job_ready_for_boost($data->id) == 1)
                                <div class="modal fade" id="boost_job_{{ $data->id }}" tabindex="-1" aria-labelledby="boostJobModalLabel{{ $data->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{ route('user.job-boosting-update', $data->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="boostJobModalLabel{{ $data->id }}">Boost "{{ $data->title }}"</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="boost_charge_{{ $data->id }}" class="form-label">Select Boost Duration</label>
                                                        <select class="form-select" name="boost_charge" id="boost_charge_{{ $data->id }}" required>
                                                            <option value="">Choose...</option>
                                                            @foreach(boost_charges() as $boost_charge)
                                                                <option value="{{ $boost_charge->id }}">{{ $boost_charge->duration }} Minutes - ${{ number_format($boost_charge->charge, 2) }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Boost Now</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif

                            <!-- Update Worker Need Modal -->
                            @if ($data->worker_need <= $data->worker_confirmed)
                                <div class="modal fade" id="worker_need_{{ $data->id }}" tabindex="-1" aria-labelledby="updateWorkerNeedModalLabel{{ $data->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{ route('user.job-work-need-update', $data->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="updateWorkerNeedModalLabel{{ $data->id }}">Update Worker Requirement</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p class="text-danger">Update the job requirements; otherwise, it will be deleted automatically.</p>
                                                    <div class="mb-3">
                                                        <label for="worker_{{ $data->id }}" class="form-label">Number of Workers Needed</label>
                                                        <input type="number" class="form-control" id="worker_{{ $data->id }}" name="worker" value="0" min="0" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Update</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $datas->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Bootstrap 5 JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Initialize Bootstrap Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        // Function to show Boost Job Modal
        function boostJob(id){
            var boostModal = new bootstrap.Modal(document.getElementById('boost_job_' + id));
            boostModal.show();
        }

        // Function to show Update Worker Need Modal
        function updateJobWorker(id){
            var workerModal = new bootstrap.Modal(document.getElementById('worker_need_' + id));
            workerModal.show();
        }
    </script>
@endsection
