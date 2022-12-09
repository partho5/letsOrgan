<html>
<head>
    <title>Advertisement Benefit</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <link href="https://fonts.googleapis.com/css?family=Supermercado+One" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Cairo" rel="stylesheet">

    <style>
        body{
            background: url("https://www.mhi.com/discover/sustainable/learn/images/bg04.jpg") no-repeat;
            background-size: cover;
        }
        h2{
            font-size: 6vh;
            font-family: "Times New Roman";
            font-style: italic;
        }
        h3{
            font-family: 'Supermercado One', cursive;
            text-shadow: 1px 2px #e2e2e2;
            font-size: 5vh;
        }
        #head2 .p1{
            color: #e02700;
            font-size: 4vh;
            font-family: 'Cairo', sans-serif;
        }
        .p2{
            font-size: 5vh;
            font-family: Raleway, Sans-Serif;
            margin-top: 200px;
            display: none;
            color: #0072e0;
        }
        .ajaxMsg1{
            display: none;
            color: #00f;
        }
        #form{
            font-size: 2.5vh;
        }
        #done-btn{
            font-size: 4vh;
            padding: 2px 30px;
        }

    </style>
</head>
<body>

<div class="container-fluid col-md-12">
    <div id="head1" class="col-md-12">
        <h2 class="text-center">Advertising Benefit</h2>
        <h3 class="text-center">For Entrepreneurs</h3>
    </div>
    <div id="head2" class="col-md-12 text-center">
        <p class="p1">You may not accept our proposal. But why not just talk !!</p>
    </div>

    <div id="form" class="col-md-6 col-md-offset-3">
        <div class="form-group col-md-12">
            <div class="col-md-12">
                <div class="col-md-4">
                    <span>Name</span>
                </div>
                <div class="col-md-8">
                    <input type="text" id="name" class="form-control" placeholder="Full Name Please">
                </div>
            </div>
        </div>

        <div class="form-group col-md-12">
            <div class="col-md-12">
                <div class="col-md-4">
                    <span>Phone Number</span>
                </div>
                <div class="col-md-8">
                    <input type="text" id="phone" class="form-control" placeholder="We need to contact you">
                </div>
            </div>
        </div>

        <div class="form-group col-md-12">
            <div class="col-md-12">
                <div class="col-md-4">
                    <span>Email Address</span>
                </div>
                <div class="col-md-8">
                    <input type="email" id="email" class="form-control" placeholder="Further details will be mailed">
                </div>
            </div>
        </div>

        <div class="form-group col-md-12">
            <div class="col-md-12">
                <div class="col-md-4">
                    <span>Your Business Link</span>
                </div>
                <div class="col-md-8">
                    <input type="text" id="business-link" class="form-control" placeholder="Website / Facebook Page">
                </div>
            </div>
        </div>

        <div class="col-md-12 text-center" id="msg1">
            <p class="p2">You Put X, Draw 5X In Return</p>
        </div>

        <div class="form-group col-md-12">
            <div class="col-md-12">
                <div class="col-md-4">
                    <span>Write us something? </span>
                </div>
                <div class="col-md-8">
                    <textarea id="say-more" class="form-control" placeholder="Optional"></textarea>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <span type="button" id="done-btn" class="btn btn-success text-center col-md-6 col-md-offset-3">Get In Touch</span>
            <span class="ajaxMsg1">Please wait...</span>
        </div>

    </div>
</div>

<script>
    $(document).ready(function () {
        var name,phone,email,businessLink,additionalMsg;
        $('#done-btn').click(function () {
            name = $('#name').val();
            phone = $('#phone').val();
            email = $('#email').val();
            businessLink = $('#business-link').val();
            additionalMsg = $('#say-more').val();

            //console.log(name+'\n'+phone+'\n'+email+'\n'+businessLink+'\n'+additionalMsg);
        });

        $('#business-link').click(function () {
            $('.p2').show();
            $('#msg1').animate({
                marginTop : '-200px'
            },800);
        });


        $('#done-btn').click(function () {
            $(this).attr('disabled', true);
            $('.ajaxMsg1').show();
            $.ajax({
                url : '/sponsor/save_sponsor_data',
                type : 'post',
                dataType : 'text',
                data : {
                    _token : "{{ csrf_token() }}",
                    name : name, phone : phone, email : email, businessLink : businessLink, additionalMsg : additionalMsg
                }, success : function (response) {
                    console.log(response);
                    $('.ajaxMsg1').text('Thank you. We will email you soon');
                    setTimeout(function () {
                        $('.ajaxMsg1').fadeOut();
                    }, 5000);
                }, error : function (a,b,c) {
                    console.log(c);
                    alert("Error Occurred. Please try again");
                }
            });
        });
    });
</script>
</body>
</html>