<div class="modal fade" id="edit_{{$key}}">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h4 class="modal-title"><i class="fas fa-plus"></i> ষ্ট্যাম্প পরিবর্তন</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form class="row" action="{{ route('admin.stamp.update',$stamp->id) }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                @csrf

                <div class="form-group col-lg-12 col-md-12 col-12">
                    <label for="calan">চালান নং *</label>
                    <input type="number" class="form-control" id="calan" name="calan" value="{{ $stamp->calan }}" required placeholder="চালান নং.">
                </div>

                <div class="form-group col-lg-12 col-md-12 col-12">
                    <label for="cat_slug">মূল্যায়ন *</label>
                    <select class="form-control select2" name="cat_slug" required style="width: 100%;">
                        <option selected="selected">---সিলেক্ট করুন---</option>
                        @foreach ($categorys as $category)
                            <option value="{{ $category->slug }}" @if ($category->slug == $stamp->cat_slug) selected @endif>{{ Converter::en2bn($category->name) }} টাকা</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-lg-12 col-md-12 col-12">
                    <label for="prefix">প্রারম্ভিক কোট *</label>
                    <input type="text" class="form-control" required id="prefix" name="prefix" value="{{ $stamp->prefix }}" placeholder="প্রারম্ভিক কোট.">
                </div>
                <div class="form-group col-lg-12 col-md-12 col-12">
                    <label for="serial">সিরিয়াল *</label>
                    <input type="number" class="form-control" required id="serial" name="serial" value="{{ Converter::en2bn($stamp->serial) }}" placeholder="Enter start value.">
                </div>

                <div class="form-group col-lg-12 col-md-12 col-12">
                    <label for="date">তারিখ *</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="calan_date_edit" name="date" value="{{ Converter::en2bn(\Carbon\Carbon::parse($stamp->date)->format('d-m-Y')) }}" placeholder="মাস/দিন/বছর">
                        <div class="input-group-prepend" data-target="#calan_date_edit" data-toggle="datetimepicker">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group col-lg-12 col-md-12 col-12">
                    <label for="date">তারিখ *</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="receive_date_edit" name="receive_date" value="{{ Converter::en2bn(\Carbon\Carbon::parse($stamp->receive_date)->format('d-m-Y')) }}" placeholder="মাস/দিন/বছর">
                        <div class="input-group-prepend" data-target="#receive_date_edit" data-toggle="datetimepicker">
                        <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                    </div>
                </div>

        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">কাটুন</button>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> পরিবর্তন করুন</button>
        </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
