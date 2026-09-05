<div class="modal fade" id="modal-lg">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h4 class="modal-title"><i class="fas fa-plus"></i> নতুন ষ্ট্যাম্প</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <form class="row" action="{{ route('admin.stamp.store') }}" method="POST" novalidate="novalidate" enctype="multipart/form-data">
                @csrf

                <div class="form-group col-lg-6 col-md-6 col-12">
                    <label for="calan">চালান নং *</label>
                    <input type="number" class="form-control" id="calan" name="calan" required placeholder="চালান নং.">
                </div>

                <div class="form-group col-lg-6 col-md-6 col-12">
                    <label for="cat_slug">মূল্যায়ন *</label>
                    <select class="form-control select2" name="cat_slug" required style="width: 100%;">
                        <option selected="selected">---সিলেক্ট করুন---</option>
                        @foreach ($categorys as $category)
                            <option value="{{ $category->slug }}">{{ Converter::en2bn($category->name) }} টাকা</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-lg-4 col-md-4 col-12">
                    <label for="prefix">প্রারম্ভিক কোট *</label>
                    <input type="text" class="form-control" required id="prefix" name="prefix" placeholder="প্রারম্ভিক কোট.">
                </div>
                <div class="form-group col-lg-4 col-md-4 col-12">
                    <label for="start_value">শুরুর নাম্বার *</label>
                    <input type="number" class="form-control" required id="start_value" name="start_value" placeholder="শুরুর নাম্বার.">
                </div>
                <div class="form-group col-lg-4 col-md-4 col-12">
                    <label for="end_value">শেষ নাম্বার *</label>
                    <input type="number" class="form-control" required id="end_value" name="end_value" placeholder="শেষ নাম্বার.">
                </div>

                <div class="form-group col-lg-6 col-md-6 col-12">
                    <label for="date">চালানের তারিখ *</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="calan_date" name="date" value="{{ Converter::en2bn(date('m/d/Y')) }}" placeholder="মাস/দিন/বছর">
                        <div class="input-group-prepend" data-target="#calan_date" data-toggle="datetimepicker">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                    </div>
                </div>

                <div class="form-group col-lg-6 col-md-6 col-12">
                    <label for="date">চালান প্রাপ্তির তারিখ *</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="receive_date" name="receive_date" value="{{ Converter::en2bn(date('m/d/Y')) }}" placeholder="মাস/দিন/বছর">
                        <div class="input-group-prepend" data-target="#receive_date" data-toggle="datetimepicker">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                    </div>
                </div>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">কাটুন</button>
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> যুক্ত করুন</button>
        </div>
        </form>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
