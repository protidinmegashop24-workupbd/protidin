@extends('user.layouts.master')
@section('css')

@endsection
@section('user-content')
    <div class="card mt-2">
        <div class="card-header">
            <div class="card-title">Investment List</div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered text-nowrap" id="example1">
                    <thead>
                        <tr>
                            <th scope="col border-bottom-0">ID</th>
                            <th scope="col border-bottom-0">Amount</th>
                            <th scope="col border-bottom-0">Profit Per</th>
                            <th scope="col border-bottom-0">Duration</th>
                            <th scope="col border-bottom-0">Total Amount</th>
                            <th scope="col border-bottom-0">Date</th>
                            <th scope="col border-bottom-0">Reason/Remark</th>
                            <th scope="col border-bottom-0">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datas as $key => $data)
                            <tr>
                                <td>{{ $data->id }}</td>
                                <td>{{ find_investment_package($data->package_id)->invest_amount }} $</td>
                                <td>{{ find_investment_package($data->package_id)->profit_per }} %</td>
                                <td>{{ find_investment_package($data->package_id)->duration }} Day</td>
                                <td>
                                    @php
                                        $total = find_investment_package($data->package_id)->invest_amount + (find_investment_package($data->package_id)->invest_amount * find_investment_package($data->package_id)->profit_per)/100;
                                    @endphp
                                    {{ $total }} $
                                </td>
                                <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d/m/Y g:i A')}}</td>
                                <td>{{ $data->reason }}</td>
                                <td>
                                    @if($data->status == 0)
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($data->status == 1)
                                        <span class="badge bg-success p-2">Paid</span>
                                    @else
                                        <span class="badge bg-danger p-2">Rejected</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        function setAccount(account_id){
            $('#deposit_account').val(account_id);

            $.ajax({
                url: "{{ route('user.deposit-account-info') }}",
                type:"POST",
                data:{
                    _token: '{{csrf_token()}}',
                    account_id: account_id,
                },
                success:function(data) {
                    $('#deposit_area').show();
                    $('#deposit_account_text').html('Account No: '+data['account_no']);
                    $('#deposit_account_guideline').html(data['guideline']);
                },
            });
        }
    </script>
@endsection
