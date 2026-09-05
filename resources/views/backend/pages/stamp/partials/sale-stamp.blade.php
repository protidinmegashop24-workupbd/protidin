<div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h4 class="modal-title"><i class="fas fa-plus"></i> নতুন বিক্রয়</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form class="row" action="{{ route('admin.stamp-sale.store') }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                @csrf

                <div class="form-group col-lg-6 col-md-6 col-12">
                    <label for="cat_slug">মূল্যায়ন *</label>
                    <select class="form-control select2 select2-hidden-accessible" multiple name="cat_slug[]" required style="width: 100%;">
                        <option>---সিলেক্ট করুন---</option>
                        @foreach ($categorys as $category)
                            <option value="{{ $category->slug }}">{{ Converter::en2bn($category->name) }} টাকা</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-lg-6 col-md-6 col-12">
                    <label for="quantity">পরিমান *</label>
                    {{-- <input type="number" class="form-control" id="quantity" name="quantity" value="1" min="1" required placeholder="পরিমান."> --}}
                    <select class="form-control select2 select2-hidden-accessible" multiple name="quantity[]" name="quantity" required placeholder="পরিমান." style="width: 100%;">
                        <option value="1">{{ Converter::en2bn(1) }}</option>
                        <option value="2">{{ Converter::en2bn(2) }}</option>
                        <option value="3">{{ Converter::en2bn(3) }}</option>
                        <option value="4">{{ Converter::en2bn(4) }}</option>
                        <option value="5">{{ Converter::en2bn(5) }}</option>
                        <option value="6">{{ Converter::en2bn(6) }}</option>
                        <option value="7">{{ Converter::en2bn(7) }}</option>
                        <option value="8">{{ Converter::en2bn(8) }}</option>
                        <option value="9">{{ Converter::en2bn(9) }}</option>
                        <option value="10">{{ Converter::en2bn(10) }}</option>
                        <option value="11">{{ Converter::en2bn(11) }}</option>
                        <option value="12">{{ Converter::en2bn(12) }}</option>
                        <option value="13">{{ Converter::en2bn(13) }}</option>
                        <option value="14">{{ Converter::en2bn(14) }}</option>
                        <option value="15">{{ Converter::en2bn(15) }}</option>
                        <option value="16">{{ Converter::en2bn(16) }}</option>
                        <option value="17">{{ Converter::en2bn(17) }}</option>
                        <option value="18">{{ Converter::en2bn(18) }}</option>
                        <option value="19">{{ Converter::en2bn(19) }}</option>
                        <option value="20">{{ Converter::en2bn(20) }}</option>
                        <option value="21">{{ Converter::en2bn(21) }}</option>
                        <option value="22">{{ Converter::en2bn(22) }}</option>
                        <option value="23">{{ Converter::en2bn(23) }}</option>
                        <option value="24">{{ Converter::en2bn(24) }}</option>
                        <option value="25">{{ Converter::en2bn(25) }}</option>
                        <option value="26">{{ Converter::en2bn(26) }}</option>
                        <option value="27">{{ Converter::en2bn(27) }}</option>
                        <option value="28">{{ Converter::en2bn(28) }}</option>
                        <option value="29">{{ Converter::en2bn(29) }}</option>
                        <option value="30">{{ Converter::en2bn(30) }}</option>
                        <option value="31">{{ Converter::en2bn(31) }}</option>
                        <option value="32">{{ Converter::en2bn(32) }}</option>
                        <option value="33">{{ Converter::en2bn(33) }}</option>
                        <option value="34">{{ Converter::en2bn(34) }}</option>
                        <option value="35">{{ Converter::en2bn(35) }}</option>
                        <option value="36">{{ Converter::en2bn(36) }}</option>
                        <option value="37">{{ Converter::en2bn(37) }}</option>
                        <option value="38">{{ Converter::en2bn(38) }}</option>
                        <option value="39">{{ Converter::en2bn(39) }}</option>
                        <option value="40">{{ Converter::en2bn(40) }}</option>
                        <option value="41">{{ Converter::en2bn(41) }}</option>
                        <option value="42">{{ Converter::en2bn(42) }}</option>
                        <option value="43">{{ Converter::en2bn(43) }}</option>
                        <option value="44">{{ Converter::en2bn(44) }}</option>
                        <option value="45">{{ Converter::en2bn(45) }}</option>
                        <option value="46">{{ Converter::en2bn(46) }}</option>
                        <option value="47">{{ Converter::en2bn(47) }}</option>
                        <option value="58">{{ Converter::en2bn(58) }}</option>
                        <option value="49">{{ Converter::en2bn(49) }}</option>
                        <option value="50">{{ Converter::en2bn(50) }}</option>
                    </select>
                </div>

                <div class="form-group col-lg-6 col-md-6 col-12">
                    <label for="name">ক্রেতার নাম *</label>
                    <input type="text" class="form-control" id="name" name="name" required placeholder="ক্রেতার নাম.">
                </div>

                <div class="form-group col-lg-6 col-md-6 col-12">
                    <label for="father_name">বাবার নাম *</label>
                    <input type="text" class="form-control" id="father_name" name="father_name" required placeholder="বাবার নাম.">
                </div>

                <div class="form-group col-lg-4 col-md-4 col-12">
                    <label for="village">গ্রাম *</label>
                    <input type="text" class="form-control" required id="village" name="village" placeholder="গ্রাম.">
                </div>
                <div class="form-group col-lg-4 col-md-4 col-12">
                    <label for="thana">থানা *</label>
                    <input type="text" class="form-control" required id="thana" name="thana" placeholder="থানা.">
                </div>
                <div class="form-group col-lg-4 col-md-4 col-12">
                    <label for="district">জেলা *</label>
                    <input type="text" class="form-control" required id="district" name="district" placeholder="জেলা.">
                </div>

                <div class="form-group col-lg-12 col-md-12 col-12">
                    <label for="date">তারিখ *</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="buy_date" name="date" value="{{ Converter::en2bn(date('m/d/Y')) }}" placeholder="মাস/দিন/বছর">
                        <div class="input-group-prepend" data-target="#buy_date" data-toggle="datetimepicker">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                    </div>
                </div>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Save</button>
        </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
