<!DOCTYPE HTML>
<html>
<head>
    <title>{{ $profileInfo->name }}</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="/assets/profile/css/profile.css">
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Patua+One" rel="stylesheet">
</head>
<body>

<!-- Header -->
<header id="header" class="reveal">
    <div class="logo"><a href="/">Lets<span>Organ</span></a></div>
    <a href="#menu"><b>Menu</b></a>
</header>

<!-- Nav -->
<nav id="menu">
    <ul class="links">
        <li><a href="/">Home</a></li>
        <li><a href="/community/view/{{ $basicInfo['currentCommunityId'] }}">Study Info</a></li>
        <li><a href="/users/cloud/{{ $basicInfo['currentCommunityId'] }}">My Files</a></li>
        <li><a href="/notice_board">Notice Board</a></li>
        <li><a href="/users/profile/update">Edit Profile</a></li>
    </ul>
</nav>
<div class="container" id="body-wrapper">
    <div class="col-md-12">
        <h2 id="hello">Hello,</h2>
        <p id="a-bit">a bit about me</p>
        <div class="col-md-7">
            <!--left-->
            <div class="col-md-12">
                <div class="profile-image-wrapper text-center">
                    <img class="img-circle profile-image" src="{{ $profileInfo->pp_url }}" alt="avatar" width="50%">
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <!--right-->
            <div class="left-align right-content about">
                <h2 class="h-property">{{ $profileInfo->name }}</h2>
                <p class="p-property">{{ $basicInfo['isAdmin'] ? '':'Student' }}</p>
                <br>
                <p><span class="property">University: </span><span class="value">{{ $academicInfo['institute'] }}</span></p>
                <p><span class="property">Department: </span><span class="value">{{ $academicInfo['dept'] }}</span></p>
                @if( ! $basicInfo['isAdmin'] )
                <p><span class="property">Session: </span><span class="value">{{ $academicInfo['session'] }}</span></p>
                @endif
                <p><span class="property">Email: </span><span class="value">{{ $profileInfo->email }}</span></p>

                <p><span class="property">Mobile: </span><span class="value">{{ request()->route()->parameters['id'] == 4 ? "01781 60 34 05": 'Not Public' }}</span></p>
            </div>
        </div>
    </div>
        <!--<div class="col-md-12">-->
            <!--<div class="col-md-7">-->
                <!--<div class="col-md-10 col-md-offset-1 about">-->
                    <!--<div class="text-left">-->
                        <!--<p class="text-center bio">Biography</p>-->
                        <!--<p>-->
                            <!--Bangladesh one of the densely populated country of the world is now in the driving seat for its true recent developments. The significant strength, people of Bangladesh are contributing all over the world in research and technologies. Also, there are people who are contributing other sectors as well. An extremely potential country lacks a platform where experts in different sector can collaborate and communicate. Therefore, Bangladesh Research and Education Network (BdREN) would like to facilitate the potentials through a common platform titled Expert as a Service platform. The platform would be an enabler for different stakeholders to implement various communication and collaboration services in tertiary education sector of Bangladesh. BdREN has already implemented a huge network and other relevant infrastructure to provide the service.-->
                        <!--</p>-->
                    <!--</div>-->
                <!--</div>-->
            <!--</div>-->
            <!--<div class="col-md-5 about">-->
                <!--<p class="text-center bio">Thinks about <b>Let's Organ</b></p>-->
                <!--<p>-->
                   <!--" <b>Let's Organ</b> is an educational website has made to organize all your study materials more efficiently, more smartly!  It's a platform where university students can make themselves more creative,productive,passionate and develop their skills as well.They can manage their academic course materials and get notified smartly via email or push notification  like many universities have their own around the world.And various online contest and IQ problems to enrich basic skills.-->
                <!--"</p>-->

            <!--</div>-->
        <!--</div>-->


</div>


<!--Footer-->
<footer id="footer">
    <div class="container">
        <ul class="icons">
            <li><a href="https://twitter.com/LetsOrgan" class="fa fa-twitter"><span class="label">Twitter</span></a></li>
            <li><a href="https://www.facebook.com/letsorgan/" class="fa fa-facebook"><span class="label">Facebook</span></a></li>
            <!--<li><a href="#" class="fa fa-instagram"><span class="label">Instagram</span></a></li>-->
            <!--<li><a href="#" class="fa fa-envelope-o"><span class="label">Email</span></a></li>-->
        </ul>
    </div>
    <div class="copyright">
        &copy; LetsOrgan 20{{ date('y') }}. All rights reserved.
    </div>
</footer>

<!-- Scripts -->
<script src="/assets/profile/js/jquery.min.js"></script>
<script src="/assets/profile/js/jquery.scrollex.min.js"></script>
<script src="/assets/profile/js/skel.min.js"></script>
<script src="/assets/profile/js/util.js"></script>
<script src="/assets/profile/js/main.js"></script>

</body>
</html>