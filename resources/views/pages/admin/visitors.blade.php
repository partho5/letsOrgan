<html>
<head>
    <title>Visitor Log</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


    <style>

    </style>
</head>
<body>
<h3 class="text-center">Traffic Log</h3>
    <table class="table">
        <tr>
            <td>User Id</td> <td>Community Id</td>  <td>IP</td> <td>Device Type</td>     <td>Browser</td>
            <td>Page Url</td>  <td>Visit Time</td>
        </tr>
        @foreach($visitors as $visitor)
            <tr>
                <td>{{ $visitor->user_id }}</td>
                <td>{{ $visitor->community_id }}</td>
                <td>{{ $visitor->ip }}</td>
                <td>{{ $visitor->device_type }}</td>
                <td>{{ $visitor->browser }}</td>
                <td>{{ $visitor->page_url }}</td>
                <td>{{ $visitor->session_duration }}</td>
                <td>{{ $visitor->visit_time }}</td>
            </tr>
        @endforeach
    </table>
</body>
</html>