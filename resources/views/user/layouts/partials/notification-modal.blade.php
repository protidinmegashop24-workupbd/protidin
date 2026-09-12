<div class="modal fade notification-modal" id="notification_modal" tabindex="-1" role="dialog" aria-labelledby="divisionEditModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">You have {{ latest_notification(Auth::user()->id)->count() }} new notifications.</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="closeNotificationModal()">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <div class="row align-items-center">
                    @foreach(all_latest_notification(Auth::user()->id) as $key=>$notify)
                        @if($key > 0) <hr> @endif
                        <div class="col">
                            <h3><strong class="mb-0">{{ $notify->message_title }}</strong></h3>
                            <p class="text-muted mb-0">
                                <strong class="mb-0">{{ $notify->message }}</strong>
                            </p>
                        </div>
                    @endforeach
                </div>
                
            </div>
            <div class="modal-footer text-center">
                <a href="@if(Auth::user()->status == 0) javascript:; @else {{ route('user.message-list') }} @endif" class="text-primary">Read All</button>
            </div>
        </div>
    </div>
</div>