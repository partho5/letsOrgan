<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>404</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <style>
        div{
            text-align: center;
        }
        h2{
            margin-top: 5%;
            font-size: 3em;
            color: #969696;
            font-family : "DejaVu Serif";
        }
        #eCode{
            font-size: 20vh;
            font-family: BenSenHandwriting;
            font-weight: 900;
        }
        #eMsg{
            font-size: 1.5em;
        }
        #suggestion{
            font-size: 5vh;
        }
    </style>
</head>
<body>
    <div class="col-md-12">
        <h2>WHOOPS!</h2>
        <p id="eCode">404</p>
        <p id="eMsg">Page Not Found</p>
    </div>

    <div>
        <p id="suggestion">
            Go back to the <a href="" onclick="history.go(-1)">previous page</a> or <a href="/">homepage</a>
        </p>
    </div>
</body>
</html>