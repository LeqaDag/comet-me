<!-- user_task.blade.php -->

<div class="user-tasks">
    <div class="d-flex flex-wrap mb-4">
        @foreach($users as $user)
            @if($user->user_type_id == $userTypeId)
                @if($flag == 1)
                <div>
                    <div class="avatar avatar-xs me-2">
                        @if($user->image == "")
                            @if($user->gender == "male")
                                <img src="{{url('users/profile/male.png')}}" class="rounded-circle">
                            @else
                                <img src="{{url('users/profile/female.png')}}" class="rounded-circle">
                            @endif
                        @else
                            <img src="{{url('users/profile/'.$user->image)}}" alt="Avatar" class="rounded-circle" />
                        @endif
                    </div>
                </div>
                <a data-toggle="collapse" class="text-dark" 
                    href="#{{$collapseId}}" 
                    aria-expanded="false" 
                    aria-controls="{{$collapseId}}">
                    Assigned this task to <strong>{{$user->name}}</strong>
                </a>
                @endif
            @endif 
        @endforeach
    </div>
    @if($flag == 1)
    <div id="{{$collapseId}}" data-aos="fade-right"
        class="collapse multi-collapse timeline-event p-0 mb-4">
        <div class="row overflow-hidden container mb-4">
            <div class="col-12">
                <ul class="timeline timeline-center mt-5">
                    @include($includeView)
                </ul>
            </div>
        </div>
    </div>
    @else
    
        <div mb-4>
            <h5>
                Platform Tasks
            </h5>
        </div>
        <div class="timeline-event p-0 mb-4">
            <div class="row overflow-hidden container mb-4">
                <div class="col-12">
                    <ul class="timeline timeline-center mt-5">
                        @include($includeView)
                    </ul>
                </div>
            </div>
        </div>
    @endif
</div>
