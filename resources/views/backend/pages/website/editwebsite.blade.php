@extends('backend.layouts.master')

@section('title')
    Site Information - Dashboard
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    {{-- select2 --}}
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
    <style>
        .new-user{
            position: absolute;
            margin: -4px 0 0px 58px;
        }
    </style>
@endsection

@section('back-content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 text-dark">Website Info</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active">Update Info</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Website Info Update</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form class="row" action="{{ route('admin.website.update',$website->id) }}" method="POST" enctype="multipart/form-data" >
                    @csrf
                    <div class="form-group col-lg-6 col-md-6 col-12">
                        <label for="title">Application Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ $website->title }}" placeholder="Application Title">
                    </div>

                    <div class="form-group col-lg-6 col-md-6 col-12">
                        <label for="title">Site Email</label>
                        <input name="email" type="text" class="form-control" id="email" placeholder="Email Address"  value="{{$website->email}}">
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-12">
                        <label for="title">Mobile No.</label>
                        <input name="mobile" type="text" class="form-control" id="mobile" placeholder="Mobile No"  value="{{$website->mobile}}" >
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-12">
                        <label for="title">Phone No.</label>
                        <input name="phone" type="text" class="form-control" id="phone" placeholder="Phone No"  value="{{$website->phone}}" >
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-12">
                        <label for="title">Reject Ratio</label>
                        <input name="job_work_reject_ratio" type="text" class="form-control" id="job_work_reject_ratio" placeholder="Reject Ration"  value="{{$website->job_work_reject_ratio}}" >
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-12">
                        <label for="title">User Block Ratio</label>
                        <input name="user_block_ratio" type="text" class="form-control" id="user_block_ratio" placeholder="Block Ration"  value="{{$website->user_block_ratio}}" >
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-12">
                        <label for="instant_verify_cost">Instant Verify Cost</label>
                        <input name="instant_verify_cost" type="text" class="form-control" id="instant_verify_cost" placeholder="Cost"  value="{{$website->instant_verify_cost}}" >
                    </div>

                    <div class="form-group col-lg-3 col-md-3 col-12">
                        <label for="instant_verify_referral_commission">Instant Verify Referral Commission(%)</label>
                        <input name="instant_verify_referral_commission" type="text" class="form-control" id="instant_verify_referral_commission" placeholder="Commission"  value="{{$website->instant_verify_referral_commission}}" >
                    </div>
                    
                    
                    
                    <div class="form-group col-lg-3 col-md-3 col-12">
                        <label for="fee">Need Instant Verify</label>
                        <select class="form-control" name="instanat_verify_active">
                            <option value="1" @if($website->instanat_verify_active == 1) selected @endif>Active</option>
                            <option value="0" @if($website->instanat_verify_active == 0) selected @endif>Inactive</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
    <label>Marketplace Commission (%)</label>
    <input type="number" step="0.01" min="0" max="100" name="marketplace_commission_percent"
           class="form-control"
           value="{{ $website->marketplace_commission_percent ?? 20 }}">
</div>
                    
                    
                    <div class="form-group col-lg-3 col-md-3 col-12 d-none">
                        <label for="email_verification_active">Email Verification System</label>
                        <select class="form-control" name="email_verification_active">
                            <option value="1" @if($website->email_verification_active == 1) selected @endif>Active</option>
                            <option value="0" @if($website->email_verification_active == 0) selected @endif>Inactive</option>
                        </select>
                    </div>
                    
                         <div class="form-group col-lg-3 col-md-3 col-12">
                        <label for="fee">Balance Transfer</label>
                        <select class="form-control" name="balance_transfer_active">
                            <option value="1" @if($website->balance_transfer_active == 1) selected @endif>Active</option>
                            <option value="0" @if($website->balance_transfer_active == 0) selected @endif>Inactive</option>
                        </select>
                    </div>
                    
                    
                    
                    
                    
                    <div class="form-group col-lg-3 col-md-3 col-12">
                        <label for="smm_service_active">SMM Service System</label>
                        <select class="form-control" name="smm_service_active">
                            <option value="1" @if($website->smm_service_active == 1) selected @endif>Active</option>
                            <option value="0" @if($website->smm_service_active == 0) selected @endif>Inactive</option>
                        </select>
                    </div>

                    
                    
                    
                    
                    
                    <div class="form-group col-lg-3 col-md-3 col-12 d-none">
                        <label for="fee">Need Captcha Verify</label>
                        <select class="form-control" name="captcha_verify_active">
                            <option value="1" @if($website->captcha_verify_active == 1) selected @endif>Active</option>
                            <option value="0" @if($website->captcha_verify_active == 0) selected @endif>Inactive</option>
                        </select>
                    </div>

                    <div class="form-group col-12">
                        <label for="instant_verify_note">Instant Verify Note</label>
                        <textarea name="instant_verify_note" class="form-control"  placeholder="Verify Note" rows="2">{{$website->instant_verify_note}}</textarea>
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-12">
                        <label for="title">Minimum Job Cost</label>
                        <input name="minimum_job_cost" type="text" class="form-control" id="minimum_job_cost" placeholder="Job Cost"  value="{{$website->minimum_job_cost}}" >
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-12">
                        <label for="title">Referral Deposit Commision</label>
                        <input name="referral_deposit_commission" type="text" class="form-control" id="referral_deposit_commission" placeholder="Referral Deposit Commision"  value="{{$website->referral_deposit_commission}}" >
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-12">
                        <label for="title">Referral Earning Commision</label>
                        <input name="referral_earning_commission" type="text" class="form-control" id="referral_earning_commission" placeholder="Referral Earning Commision"  value="{{$website->referral_earning_commission}}" >
                    </div>

                    <div class="form-group col-12">
                        <label for="title">Referral Notice</label>
                        <textarea name="referral_notice" class="form-control"  placeholder="Referral Notice" rows="2">{{$website->referral_notice}}</textarea>
                    </div>

                    {{-- <div class="form-group col-lg-4 col-md-4 col-12">
                        <label for="title">Fax No.</label>
                        <input name="fax" type="text" class="form-control" id="fax" placeholder="Fax No"  value="{{$website->fax}}" >
                    </div> --}}

                    <div class="form-group col-lg-6 col-md-6 col-12">
                        <label for="title">Complete Task Note</label>
                        <textarea name="complete_task_note" class="form-control"  placeholder="Complete Task Note" rows="2">{{$website->complete_task_note}}</textarea>
                    </div>

                    <div class="form-group col-lg-6 col-md-6 col-12">
                        <label for="title">Accepted Task Note</label>
                        <textarea name="accepted_task_note" class="form-control"  placeholder="Accepted Task Note" rows="2">{{$website->accepted_task_note}}</textarea>
                    </div>

                    <div class="form-group col-12">
                        <label for="head_tag_data">Inside Head Tag Code</label>
                        <textarea name="head_tag_data" class="form-control"  placeholder="Inside Head" rows="2">{{$website->head_tag_data}}</textarea>
                    </div>

                    <div class="form-group col-12">
                        <label for="after_start_body_tag">Inside Body Tag Code</label>
                        <textarea name="after_start_body_tag" class="form-control"  placeholder="Inside Body" rows="2">{{$website->after_start_body_tag}}</textarea>
                    </div>

                    <div class="form-group col-12">
                        <label for="ad_one_code">Ad One Code</label>
                        <textarea name="ad_one_code" class="form-control"  placeholder="Ad One" rows="2">{{$website->ad_one_code}}</textarea>
                    </div>

                    <div class="form-group col-12">
                        <label for="ad_two_code">Ad Two Code</label>
                        <textarea name="ad_two_code" class="form-control"  placeholder="Ad Two" rows="2">{{$website->ad_two_code}}</textarea>
                    </div>

                    <div class="form-group col-lg-12 col-md-12 col-12">
                        <label for="title">Description</label>
                        <textarea name="description" class="form-control"  placeholder="Description" rows="2">{{$website->description}} </textarea>
                    </div>

                    <div class="form-group col-lg-6 col-md-6 col-12">
                        <label for="title">Meta Keyword</label>
                        <textarea name="meta_keyword" class="form-control"  placeholder="Meta Keyword" rows="2">{{$website->meta_keyword}}</textarea>
                    </div>

                    <div class="form-group col-lg-6 col-md-6 col-12">
                        <label for="title">Meta Description</label>
                        <textarea name="meta_tag" class="form-control"  placeholder="Meta Description" rows="2">{{$website->meta_tag}}</textarea>
                    </div>

                    <div class="form-group col-lg-6 col-md-6 col-12">
                        <label for="title">Address</label>
                        <textarea name="address" class="form-control"  placeholder="Address" rows="2">{{$website->address}}</textarea>
                    </div>

                    {{-- <div class="form-group col-lg-6 col-md-6 col-12">
                        <label for="title">Twitter Api</label>
                        <textarea name="twitter_api" class="form-control"  placeholder="Twitter Api" rows="2">{{$website->twitter_api}}</textarea>
                    </div> --}}

                    <div class="form-group col-lg-6 col-md-6 col-12">
                        <label for="title">Google Map</label>
                        <textarea name="google_map" class="form-control"  placeholder="Google Map" rows="2">{{$website->google_map}}</textarea>
                    </div>

                    <div class="form-group col-lg-12 col-md-12 col-12">
                        <label for="oldImage" class="control-label col-lg-2">Old Favicon Image</label>
                        <div class="input-group">
                            <img src="{{ URL::to($website->favicon) }}" class="thumb-lg img-circle img-thumbnail" alt="Site favicon" name="old_favicon" height="100px" width="100px">
                        </div>
                    </div>

                    <div class="form-group col-lg-12 col-md-12 col-12">
                        <label for="exampleInputFile">Favicon Image</label>
                        <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input upload" id="fevico" name="favicon" type="file" accept="image/*" onchange="readURL(this);">
                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                        </div>
                        <div class="input-group-append">
                            <span class="input-group-text" id="">Upload</span>
                        </div>
                        </div>
                    </div>

                    <div class="form-group col-lg-12 col-md-12 col-12">
                        <label for="oldImage" class="control-label col-lg-2">Old Logo Image</label>
                        <div class="input-group">
                            <img src="{{ URL::to($website->logo) }}" class="thumb-lg img-circle img-thumbnail" alt="Site Logo" name="old_logo" height="100px" width="100px">
                        </div>
                    </div>

                    <div class="form-group col-lg-12 col-md-12 col-12">
                        <label for="exampleInputFile">Logo Image</label>
                        <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input upload" id="image" name="logo" type="file" accept="image/*" onchange="readURL(this);">
                            <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                        </div>
                        <div class="input-group-append">
                            <span class="input-group-text" id="">Upload</span>
                        </div>
                        </div>
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-12">
                        <label for="facebook_page">Facebook Page</label>
                        <input name="facebook_page" type="text" class="form-control" id="facebook_page" placeholder="Link"  value="{{$website->facebook_page}}" >
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-12">
                        <label for="facebook_group">Facebook Group</label>
                        <input name="facebook_group" type="text" class="form-control" id="facebook_group" placeholder="Link"  value="{{$website->facebook_group}}" >
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-12">
                        <label for="twitter">Twitter</label>
                        <input name="twitter" type="text" class="form-control" id="twitter" placeholder="Link"  value="{{$website->twitter}}" >
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-12">
                        <label for="linkedin">Linkedin</label>
                        <input name="linkedin" type="text" class="form-control" id="linkedin" placeholder="Link"  value="{{$website->linkedin}}" >
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-12">
                        <label for="instagram">Instagram</label>
                        <input name="instagram" type="text" class="form-control" id="instagram" placeholder="Link"  value="{{$website->instagram}}" >
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-12">
                        <label for="youtube">Youtube</label>
                        <input name="youtube" type="text" class="form-control" id="youtube" placeholder="Link"  value="{{$website->youtube}}" >
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-12">
                        <label for="whatsapp">Whatsapp</label>
                        <input name="whatsapp" type="text" class="form-control" id="whatsapp" placeholder="Link"  value="{{$website->whatsapp}}" >
                    </div>

                    <div class="form-group col-lg-4 col-md-4 col-12">
                        <label for="teligram">Teligram</label>
                        <input name="teligram" type="text" class="form-control" id="teligram" placeholder="Link"  value="{{$website->teligram}}" >
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-6">
                            <div class="ui buttons">
                                <button type="submit" class="btn btn-success m-t-15 waves-effect">UPDATE</button>

                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div><!-- /.container-fluid -->
  </section>
  <!-- /.content -->
@endsection

@section('js')
    <script src="{{ asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('backend/plugins/select2/js/select2.full.min.js') }}"></script>

    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    <script>
        $(function () {
          $("#example1").DataTable({
            "responsive": true,
            "autoWidth": false,
          });
          $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
          });
        });

        $(function () {
            $('.select2').select2();
        });

    </script>


<script>
    $(document).ready(function() {
      //#------------------------------------
      //   STARTS OF DIAGNOSIS
      //#------------------------------------
      //add row
      $('body').on('click','.DiaAddBtn' ,function() {
          var itemData = $(this).parent().parent().parent();
          $('#diagnosis').append("<tr>"+itemData.html()+"</tr>");
          $('#diagnosis tr:last-child').find(':input').val('');
      });
      //remove row
      $('body').on('click','.DiaRemoveBtn' ,function() {
          $(this).parent().parent().parent().remove();
      });

    });
  </script>
@endsection
