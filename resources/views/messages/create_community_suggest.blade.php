<html>
<head>
    <title>You are the first</title>
    <style>
        .container{
            width: 100%;
            padding: 0px;
            margin: 0px auto;
        }
        .msg-1{
            font-size: 25px;
            color: #f00;
        }
        #body{
            margin-top: 100px;
        }
        #menu-bar{
            background-color: #384e65;
        }
        #menu-bar ul li a{
            color: #fff;
        }
    </style>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>
<body>

<div class="container text-center col-md-12">
    <nav class="navbar navbar-default navbar-fixed-top" id="menu-bar" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                </button>
                <a class="navbar-brand" href="/" style="color: #fff">ChoriyeDao</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li><a href="/qa">Q&A Forum</a></li>
                    <li><a href="#row2">Features</a></li>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>
</div>

    <div class="col-md-12 text-center">
        <h3 class="msg-2" style="color: #009356; font-size: 20px" class="text-center">
            <br><br>
            Hey {{ $userName }}, warm welcome !!
        </h3>
        <h2>No one created a community for your department</h2>
        <p style="font-style: italic; font-size: 20px">
            Please you create. From <a style="color: #00f" href="/community/create">here</a>
        </p>
        <p class="msg-2 col-md-12 text-center" style="color: #000; margin-top: 100px">We like to hear from you. Don't hesitate to mail : <b>choriyedao@gmail.com</b></p>
    </div>
</body>
</html>