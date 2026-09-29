@extends('backend.layouts.master')

@section('title')
Default Setup
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('back-content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Default Setup</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">All Default Setup</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">

            <div class="col-lg-4 col-12">
                <form action="{{ route('admin.welcome-bonus.update',$wc_bonus->id) }}" method="POST">
                    @csrf
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Welcome Bonus</h3>
                        </div>
                        <div class="card-body row">
                            <div class="form-group col-lg-12 col-md-12 col-12">
                                <label for="bonus">Bonus($)</label>
                                <input type="text" class="form-control" id="bonus" name="amount" value="{{ $wc_bonus->amount }}" required placeholder="Bonus">
                            </div>
                            <div class="form-group col-lg-12 col-md-12 col-12">
                                <label for="depo_amount">Deposite Bonus($)</label>
                                <input type="text" class="form-control" id="depo_amount" name="depo_amount" value="{{ $wc_bonus->depo_amount }}" required placeholder="Deposite Bonus">
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-lg-4 col-12">
                <form action="{{ route('admin.dollar-rate.update',$dollar_rate->id) }}" method="POST">
                    @csrf
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Dollar Rate</h3>
                        </div>
                        <div class="card-body row">
                            <div class="form-group col-lg-12 col-md-12 col-12">
                                <label for="rate">Rate(BDT)</label>
                                <input type="text" class="form-control" id="rate" name="rate" value="{{ $dollar_rate->rate }}" required placeholder="Rate">
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-lg-4 col-12">
                <form action="{{ route('admin.job-fee.update',$jobFee->id) }}" method="POST">
                    @csrf
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Per Job Fee</h3>
                        </div>
                        <div class="card-body row">
                            <div class="form-group col-lg-12 col-md-12 col-12">
                                <label for="fee">Fee(%)</label>
                                <input type="text" class="form-control" id="fee" name="fee" value="{{ $jobFee->fee }}" required placeholder="Fee">
                            </div>
                            <div class="form-group col-lg-12 col-md-12 col-12">
                                <label for="min_fee">Minimum Job Cost</label>
                                <input type="text" class="form-control" id="min_fee" name="min_fee" value="{{ $jobFee->min_fee }}" required placeholder="Fee">
                            </div>
                            <div class="form-group col-lg-12 col-md-12 col-12">
                                <label for="job_delete_fee">Jobe Delete Fee(%)</label>
                                <input type="text" class="form-control" id="job_delete_fee" name="job_delete_fee" value="{{ $jobFee->job_delete_fee	 }}" required placeholder="Fee">
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-lg-4 col-12">
                <form action="{{ route('admin.withdraw-fee.update',$withdrawFee->id) }}" method="POST">
                    @csrf
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Balance Withdraw Setup</h3>
                        </div>
                        <div class="card-body row">
                            <div class="form-group col-lg-12 col-md-12 col-12">
                                <label for="fee">Fee</label>
                                <input type="text" class="form-control" id="fee" name="fee" value="{{ $withdrawFee->fee }}" required placeholder="Fee">
                            </div>
                            <div class="form-group col-lg-12 col-md-12 col-12">
                                <label for="fee">Minimum($)</label>
                                <input type="text" class="form-control" id="minimum_withdraw" name="minimum_withdraw" value="{{ $withdrawFee->minimum_withdraw }}" required placeholder="Minimum Withdraw">
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-lg-4 col-12">
                <form action="{{ route('admin.main-wallet.update',$main_wallet->id) }}" method="POST">
                    @csrf
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Admin Main Wallet</h3>
                        </div>
                        <div class="card-body row">
                            <div class="form-group col-lg-12 col-md-12 col-12">
                                <label for="main_balance">Main Balance</label>
                                <input type="text" class="form-control" id="main_balance" name="main_balance" value="{{ $main_wallet->amount }}" required placeholder="Main Balance">
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </form>
            </div>
    
            <div class="col-lg-4 col-12">
                <form action="{{ route('admin.screenshot-charge.update',$schreen_shoot_charge->id) }}" method="POST">
                    @csrf
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Screenshoot Charge Setup</h3>
                        </div>
                        <div class="card-body row">
                            <div class="form-group col-lg-12 col-md-12 col-12">
                                <label for="fee">Fee</label>
                                <input type="text" class="form-control" id="fee" name="fee" value="{{ $schreen_shoot_charge->fee }}" required placeholder="Fee">
                            </div>
                            <div class="form-group col-lg-12 col-md-12 col-12">
                                <label for="fee">Status</label>
                                <select class="form-control" name="status">
                                    <option value="1" @if($schreen_shoot_charge->status == 1) selected @endif>Active</option>
                                    <option value="0" @if($schreen_shoot_charge->status == 0) selected @endif>Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
</section>
@endsection

@section('js')
<script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

<script>
    $(function() {
        $("#example1").DataTable({
            "responsive": true
            , "autoWidth": false
        , });
        $('#example2').DataTable({
            "paging": true
            , "lengthChange": false
            , "searching": false
            , "ordering": true
            , "info": true
            , "autoWidth": false
            , "responsive": true
        , });
    });

</script>
@endsection
