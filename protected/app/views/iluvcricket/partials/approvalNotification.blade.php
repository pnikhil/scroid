<a href="{{Helpers::getUrlWithQuery(array('status' => 'awaiting_approval'), route('iluvcricketViewLists'))}}" class="btn btn-default" style="margin: 9px 10px;padding: 5px 16px;">
    @if(!empty($itemsAwaitingApproval) && $itemsAwaitingApproval > 0)
        <span class="label label-danger" style="background: #ff3a3c;">{{@$itemsAwaitingApproval}}</span>&nbsp;
    @else
        No
    @endif
    Lists awaiting approval
</a>
