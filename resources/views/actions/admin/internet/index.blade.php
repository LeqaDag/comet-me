<!-- Action Items for adding english name for the internet contract holders -->
<li class="timeline-item mb-md-4 mb-5 timeline-item-left">
    <span class="timeline-indicator timeline-indicator-success" data-aos="zoom-in" data-aos-delay="200">
        <i class="bx bx-wifi"></i>
    </span>
    <div class="timeline-event card p-0" data-aos="fade-left">
        <h6 class="card-header">Internet Users</h6>
        <div class="card-body">
            <div class="mb-4">
                <h6>Contract Holders</h6>
                @if(count($internetUsers) == $internetDataApi[0]["total_contracts"])
                    <span>All is well!</span>
                @else 
                @if(count($internetUsers) < $internetDataApi[0]["total_contracts"])
                    <p>Go to the 
                        <a href="/internet-user" target="_blank">
                        internet service page
                        </a>
                        and click on “Get Latest Internet Holders”
                    </p>
                @else
                    <p>We've in the database 
                        {{$internetUsers->count()}}
                        contracts, while from the API we get 
                        {{$internetDataApi[0]["total_contracts"]}}
                        ,Please check them! 
                    </p>
                @endif
                @endif
            </div>
            <hr>
            <div class="mb-4">
                <h6>Internet Young Holders</h6>
                @if(count($youngHolders) > 0)
                <p>Add English Name for: </p>
                @foreach($youngHolders as $youngHolder)
                <ul class="list-unstyled">
                    <li class="d-flex" style="margin-top:9px">
                        <a class="btn btn-warning btn-sm" type="button" 
                            href="/household/{{$youngHolder->id}}/edit" target="_blank">
                            <span> {{$youngHolder->arabic_name}} </span>   
                                Go To Edit 
                        </a>
                    </li>
                </ul>
                @endforeach
                @else
                <span>No Internet Young Holders</span>
                @endif

            </div>
        </div>
        <div class="timeline-event-time">Internet Users</div>
    </div>
</li>

<!-- Action Items for adding internet system for the new communities -->
<li class="timeline-item mb-md-4 mb-5 timeline-item-left">
    <span class="timeline-indicator timeline-indicator-success" data-aos="zoom-in" data-aos-delay="200">
        <i class="bx bx-wifi"></i>
    </span>
    <div class="timeline-event card p-0" data-aos="fade-left">
        <h6 class="card-header">Internet Systems</h6>
        <div class="card-body">
     
            @if(count($communitiesNotInSystems) > 0)
            <p>Add the 
                <a class="btn btn-success btn-sm" type="button" 
                    href="/internet-system" target="_blank">
                    <span> internet system </span> 
                </a>
                for these communities: 
            </p>
            @foreach($communitiesNotInSystems as $communitiesNotInSystem)
            <ul class="list-group">
                <li class="d-flex list-group-item" style="margin-top:9px">
                    <span> {{$communitiesNotInSystem->english_name}} </span>   
                </li>
            </ul>
            @endforeach
            @endif
        </div>
        <div class="timeline-event-time">Internet System</div>
    </div>
</li>