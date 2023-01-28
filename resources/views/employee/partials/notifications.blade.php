<ul class="dropdown-menu dropdown-menu-media dropdown-menu-right">
  <li class="dropdown-menu-header">
    <h6 class="dropdown-header m-0">
      <span class="grey darken-2">
        @lang('main.notifications')
      </span>
     
    </h6>
  </li>
  <li class="scrollable-container media-list">
      @foreach($reviews as $review)
      @if($review->number >=2)
      <?php
        $cryptos = App\Models\EmployeeRecommendation::where('user_id', Auth::guard('user')->user()->id)
          ->where("id", $review->employee_recommendation_id)
          ->first();
      ?>
      <a href="" data-id="{{$cryptos->id}}">
        <div class="media">
          <div class="media-left align-self-center">
            <i class="fa fa-check icon-bg-circle bg-cyan"></i>
          </div>
          <div class="media-body">
            <h6 class="media-heading">{{ $cryptos->crypto_name }}</h6>
            <small>
              <time class="media-meta text-muted" datetime="2015-06-11T18:29:20+08:00">
                تم الموافقة على التوصية الخاصة بك
              </time>
            </small>
          </div>
        </div>
        @endif
        @endforeach
      </a>
  </li>
  <li class="dropdown-menu-footer">
    <a class="dropdown-item text-muted text-center"
      href="javascript:void(0)">
    </a>
  </li>
</ul>