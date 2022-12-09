<!DOCTYPE html>
<html><head>

    {{--class=" js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage no-websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients no-cssreflections csstransforms csstransforms3d csstransitions fontface generatedcontent video audio localstorage sessionstorage webworkers applicationcache svg inlinesvg smil svgclippaths" lang="en"--}}

    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="ChoriyeDao">
    <meta name="author" content="choriyedao.com">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>{{ $userName }}</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="/assets/images/logo.png">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- themify icon -->
    <link href="https://cdn.jsdelivr.net/themify-icons/0.1.2/css/themify-icons.css" rel="stylesheet" type="text/css">

    <!-- font awesome  -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- jquery ui -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.js">

    <link rel="stylesheet" href="/assets/css/portfolio-style.css">


    <link href="https://fonts.googleapis.com/css?family=Courgette|Slabo+27px" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Fredericka+the+Great" rel="stylesheet">

</head>

<body>

<nav id="menu-bar" class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">Home</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li><a href="#skill">Skill</a></li>
                <li><a href="#projects">Projects</a></li>
                <li><a href="#awards">Awards</a></li>
                <li><a href="#research"> Research </a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>

<div id="page-content" class="container-fluid col-md-12 col-xs-12">
    <div class="row col-md-12" id="about-me-section">
        <img src="{{ $ppLink }}" alt="" class="col-md-5 col-xs-12">
        <div class="col-md-7 align-middle about-me-text">
            <h1>Hello I am {{ $userName }}</h1>
            <p><b>A student of Electrical & Electronic Engineering, Dhaka University</b></p><br>
            <p>Not just the wrapper, but all "col"s are floated. I'm guessing those are bootstrap classes or the like. So you would need to override all the bootstrappery, or I imagine there will be a bootstrap way of doing this, for which I'm not the person to ask.</p>
        </div>
    </div>

    <div id="skill-section" class="row col-md-12">
        <h1 class="text-center">My Skills</h1>
        <div class="col-md-2 text-center">
            <i class="fa fa-gg-circle"></i>
        </div>
        <div class="col-md-3 about-skill">
            Not just the wrapper, but all "col"s are floated. I'm guessing those are bootstrap classes or the like. So you would need to override all the bootstrappery, or I imagine there will be a bootstrap way of doing this, for which I'm not the person to ask.
        </div>
        <div class="skill-caption col-md-7 text-center">
            <ul class="skill-list list-inline">
                <li class="col-md-4 col-xs-12">Adobe Illustrator</li>
                <li class="col-md-3 col-xs-12">Java</li>
                <li class="col-md-5 col-xs-12">HTML</li>

                <li class="col-md-3 col-xs-12">Guitar</li>
                <li class="col-md-4 col-xs-12">C# </li>
                <li class="col-md-5 col-xs-12">Android Application Development</li>

                <li class="col-md-4 col-xs-12">PHP</li>
                <li class="col-md-4 col-xs-12">MySQL</li>
                <li class="col-md-4 col-xs-12">Laravel</li>
            </ul>
        </div>
    </div>

    <div id="project-section" class="col-md-12">
        <h1 class="text-center">Projects I Have Done</h1>
        <div class="project-1 col-md-7">
            owei jioerjoif joeirjf igj
        </div>
        <div class="project-caption col-md-2 text-center">P <br> R <br> O <br> J <br> E <br> C <br> T</div>
        <div class="project-icon-wrapper col-md-3 text-center">
            <img src="http://www.skillfish.co.uk/wp-content/uploads/2015/02/Projects-icon-300x300.png" class="col-md-12 col-xs-12">
        </div>
    </div>

    <div class="col-md-12 text-center">
        <div class="single-project col-md-4">
            oeirj goerjgioj
        </div>
        <div class="single-project col-md-4">
            ker ngjkerng
        </div>
        <div class="single-project col-md-4">
            ker ngjkerng
        </div>

        <div class="single-project col-md-4">
            oeirj goerjgioj
        </div>
        <div class="single-project col-md-4">
            ker ngjkerng
        </div>
        <div class="single-project col-md-4">
            ker ngjkerng
        </div>
    </div>

    <div id="other-activity" class="col-md-12">
        <i class="fa fa-check-square-o col-md-3 text-center"></i>
        <div class="col-md-9">
            <h1 class="text-center">Other Skills...</h1>
            <div class="list-inline">
                <li><i class="fa fa-check"></i>fd gdfg </li>
                <li><i class="fa fa-check"></i>fd gdfg </li>
                <li><i class="fa fa-check"></i>fd gdfg </li>
                <li><i class="fa fa-check"></i>fd gdfg </li>
            </div>
        </div>
    </div>

</div>
<p>jn</p><p>jn</p><p>jn</p><p>jn</p><p>jn</p><p>jn</p><p>jn</p><p>jn</p><p>jn</p>

<script type="text/javascript">
    $(document).ready(function () {
        $('#about-me-section').css('height', window.innerHeight);
    });
</script>

</body>

</html>