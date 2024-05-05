<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comet-ME</title>
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
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
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

    <!-- Full-screen video -->
    <div class="video-container">
        <iframe src="https://www.youtube.com/embed/JpVvy3GLFa4?autoplay=1&mute=1" frameborder="0" allowfullscreen></iframe>
    </div>

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
                @if($member->is_archived == 0 && $member->user_type_id == 2)
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
                @if($member->is_archived == 0 && $member->user_type_id == 1)
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
                @if($member->is_archived == 0 && $member->user_type_id != 1 && $member->user_type_id != 2)
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
            </div>
        </div>
    </div>
    <!-- Include any JavaScript scripts or libraries here -->
</body>
</html>

