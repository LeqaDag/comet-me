<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comet-ME</title>
   <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include any CSS stylesheets or libraries here -->
    <style>
        /* Example CSS for styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .header {
            background-color: #333;
            color: #fff;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 10px;
        }
        .nav-links {
            display: flex;
            gap: 20px;
        }
        .nav-link {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            background-color: #555;
            border-radius: 5px;
        }
        .nav-link:hover {
            background-color: #777;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .section {
            margin-top: 40px;
        }
        .video-container {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 41.25%; /* 16:9 aspect ratio */
        }
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .services, .team {
            padding: 20px;
            background-color: #f2f2f2;
            text-align: center;
        }
        .services h2, .team h2 {
            margin-bottom: 20px;
        }
        .service-item, .team-member {
            flex: 0 0 30%;
            margin: 10px;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .team-member img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin-bottom: 10px;
        }
        .team-member-name {
            font-weight: bold;
        }
        .carousel-item img {
            height: 600px; /* Adjust the height as needed */
            object-fit: cover; /* Ensure the image covers the entire container */
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ asset('logo.jpg') }}" alt="Logo" class="logo">
        <div class="nav-links">
            <a href="#services" class="nav-link">Our Services</a>
            <a href="#team" class="nav-link">Our Team</a>
            @if(Auth::guard())
                @if(Auth::guard('user')->user() == null)
                    <a href="{{ route('login') }}" class="nav-link">Log in</a>
                @else
                    <a href="{{ url('/home') }}" class="nav-link">Home</a>
                @endif
            @endif
        </div>
    </div>

    <!-- Images Sliders --> 
    <div class="video-container">
        <div id="carouselExampleImages" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{url('/images/home.jpg')}}" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="{{url('/images/img1.jpg')}}" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="{{url('/images/img2.jpg')}}" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="{{url('/images/img3.jpg')}}" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="{{url('/images/img4.jpg')}}" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="{{url('/images/img5.jpg')}}" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="{{url('/images/img6.jpg')}}" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="{{url('/images/img7.jpg')}}" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="{{url('/images/img8.jpg')}}" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="{{url('/images/img9.jpg')}}" class="d-block w-100">
                </div>
                <div class="carousel-item">
                    <img src="{{url('/images/img10.jpg')}}" class="d-block w-100">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleImages" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleImages" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>  

    <!-- Full-screen video -->
    <!-- <div class="video-container">
        <iframe src="https://www.youtube.com/embed/JpVvy3GLFa4?autoplay=1&mute=1" frameborder="0" allowfullscreen></iframe>
    </div> -->

    <div class="container" id="services">
        <div class="services section">
            <h2>Our Services</h2>
            @foreach($settings as $service)   
                <div class="service-item">
                    <h3>{{$service->name}}</h3>
                    <p>{{$service->english_name}}</p>
                </div>
            @endforeach
        </div>
    </div>
    
    <div class="container" id="team">
    <!-- Our team -->
        <div class="team section">
            <h2>Our Team</h2>
            <div class="row">
                @foreach($teamMembers as $member)
                @if($member->is_archived == 0 && $member->user_role_type_id == 1)
                    <div class="col-sm-4 col-lg-4 col-md-4">
                        <div class="team-member">
                            <img src="{{url('users/profile/'.$member->image)}}" alt="{{ $member->name }}">
                            <div class="team-member-details">
                                <p class="team-member-name">{{ $member->name }}</p>
                                <p>{{ $member->Role->name }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                @endforeach
                @foreach($teamMembers as $member)
                @if($member->is_archived == 0 && $member->user_role_type_id == 2)
                    <div class="col-sm-4 col-lg-4 col-md-4">
                        <div class="team-member">
                            <img src="{{url('users/profile/'.$member->image)}}" alt="{{ $member->name }}">
                            <div class="team-member-details">
                                <p class="team-member-name">{{ $member->name }}</p>
                                @if($member->Role)
                                <p>{{ $member->Role->name }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                @endforeach
                
                @foreach($teamMembers as $member)
                @if($member->is_archived == 0 && $member->user_role_type_id == 3)
                    <div class="col-sm-4 col-lg-4 col-md-4">
                        <div class="team-member">
                            <img src="{{url('users/profile/'.$member->image)}}" alt="{{ $member->name }}">
                            <div class="team-member-details">
                                <p class="team-member-name">{{ $member->name }}</p>
                                @if($member->Role)
                                <p>{{ $member->Role->name }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                @endforeach

                @foreach($teamMembers as $member)
                @if($member->is_archived == 0 && $member->user_role_type_id == 4 )
                    <div class="col-sm-4 col-lg-4 col-md-4">
                        <div class="team-member">
                            <img src="{{url('users/profile/'.$member->image)}}" alt="{{ $member->name }}">
                            <div class="team-member-details">
                                <p class="team-member-name">{{ $member->name }}</p>
                                @if($member->Role)
                                <p>{{ $member->Role->name }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                @endforeach

                @foreach($teamMembers as $member)
                @if($member->is_archived == 0 && $member->user_role_type_id == 5 )
                    <div class="col-sm-4 col-lg-4 col-md-4">
                        <div class="team-member">
                            <img src="{{url('users/profile/'.$member->image)}}" alt="{{ $member->name }}">
                            <div class="team-member-details">
                                <p class="team-member-name">{{ $member->name }}</p>
                                @if($member->Role)
                                <p>{{ $member->Role->name }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                @endforeach

                @foreach($teamMembers as $member)
                @if($member->is_archived == 0 && $member->user_role_type_id == 6 )
                    <div class="col-sm-4 col-lg-4 col-md-4">
                        <div class="team-member">
                            <img src="{{url('users/profile/'.$member->image)}}" alt="{{ $member->name }}">
                            <div class="team-member-details">
                                <p class="team-member-name">{{ $member->name }}</p>
                                @if($member->Role)
                                <p>{{ $member->Role->name }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                @endforeach

                @foreach($teamMembers as $member)
                @if($member->is_archived == 0 && $member->user_role_type_id == 7 )
                    <div class="col-sm-4 col-lg-4 col-md-4">
                        <div class="team-member">
                            <img src="{{url('users/profile/'.$member->image)}}" alt="{{ $member->name }}">
                            <div class="team-member-details">
                                <p class="team-member-name">{{ $member->name }}</p>
                                @if($member->Role)
                                <p>{{ $member->Role->name }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Include any JavaScript scripts or libraries here -->
</body>
</html>
