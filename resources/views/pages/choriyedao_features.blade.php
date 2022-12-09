<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Features Of LetsOrgan</title>
    <style>
        body{
            font-family: "brandon-grotesque", sans-serif;
        }
        .single-feature{
            font-size: 20px;
            color: #314557;
        }
        .single-feature span{
            background-color: rgba(89, 129, 162, 0.87);
            padding: 0px 10px;
            color: #fff;
        }
        .single-feature:nth-child(even) {
            //background-color: rgba(89, 129, 162, 0.14);
        }
    </style>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
    <p id="head1"><br><a href="/"> <span class="fa fa-home fa-2x"></span> LetsOrgan Home</a></p>
    <p class="single-feature">
        <span class="fa fa-gift"> 1. Academic info. & Notification :</span> Manage only academic information and get notified to prevent mix up of valuable academic notification with other less important or only entertaining ones.List view of upcoming exams,assignment etc.
    </p>

    <p class="single-feature">
        <span class="fa fa-gift"> 2. Organized Archive :</span> Each Department will get an organized archive to manage study materials more smartly.Enjoy an unlimited cloud storage unlike traditional limitation.
    </p>

    <p class="single-feature">
        <span class="fa fa-gift"> 3. Share Knowledge/Study Materials:</span> Students will be able to share their knowledge/study materials with students of other universities.
    </p>

    <p class="single-feature">
        <span class="fa fa-gift"> 4. Online Notice Board:</span> Every department will have an online notice board. All students of that department will get notified via email/sms/push notification when a notice will be published.
    </p>

    <p class="single-feature">
        <span class="fa fa-gift"> 5. Contest:</span> Regular Online Contest to make them connected with creative stuff (Programing Contest,Math Contest,Physics Contest, Business Case Contest,Circuit Contest etc). Anyone can participate around Bangladesh.
    </p>

    <p class="single-feature">
        <span class="fa fa-gift"> 6. Question Answer Forum (BD perspective) :</span> Sometime students get confused about their passion, their study ,career, etc. Here they can ask their problems or questions and get answer from seniors or expert people all over Bangladesh.
    </p>

    <p class="single-feature">
        <span class="fa fa-gift"> 7. Sharpen IQ</span> Daily Analytical Questions to sharpen their IQ.
    </p>

    <p class="single-feature">
        <span class="fa fa-gift"> 8. Idea Sharing platform :</span> People can share their idea(anonymously if they want) and get their project mate who is also bearing the same idea on their head.
    </p>

    <p class="single-feature">
        <span class="fa fa-gift"> 9. Tutorials :</span> When you get a good or important tutorial on youtube or other site,share it on if it's related to academic topic to help others to easily get this.
    </p>

    <p class="single-feature">
        <span class="fa fa-gift"> 10.Upload Request :</span> If anyone seeking for a book or slide or sheet or questions or other essential files,they can simply request for the file and everyone can see the request and upload this if they have.
    </p>

    <p class="single-feature">
        <span class="fa fa-gift"> 11.Custom Search Engine :</span> It will search whole LetsOrgan.And moreover some important files may be needed in future but not much in present.People can individually name the files like code word which is easy to remember,may be found it in search bar in future.
    </p>

    <p class="single-feature">
        <span class="fa fa-gift"> 12.Personal Website :</span> Every user will get a website of their own like portfolio to arrange resume,skills,project works,experiences and many more.
    </p>

    <p class="single-feature">
        <span class="fa fa-gift"> 13.Daily Study :</span> We are developing something where students can discuss about their daily study topic.
    </p>

    <p class="single-feature">
        <span class="fa fa-gift"> 14.Mail Notification :</span> Smart Notification system makes you more professional like other university around the world already have.
    </p>

    <p class="single-feature">
        <span class="fa fa-gift"> 15.Events You may Interested In :</span> In the right column of homepage we will help to get you about upcoming events or competition you may interested in or want to join.
    </p>

    <p class="single-feature">
        <span class="fa fa-gift"> 16.Projects:</span> Now working on projects is more fun and interesting.Get project partners not only around your department or university but also around the country who are really interested in doing work with you.
    </p>

    <p class="single-feature text-center">
        <span class="fa fa-briefcase fa-2x"> More Features are coming...</span> <br><br><br><br><br>
    </p>
    </div>
</div>










<!DOCTYPE html>
<html>
<head>
    <title></title>

    <style type="text/css">
        table {
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid rgba(145, 145, 145, 0.86);
        }
        .absent{
            color: #f00;
        }
        .present{
            color: rgba(0, 0, 222, 0.84);
        }
        .percent{
            color: rgba(4,178,0,0.84);
        }
    </style>
</head>
<body>



<table border="1">
    <tr>
        <td>Roll</td>
        <td>Name</td>
        @foreach ($allClasses as $class) {
        <td>{{ $date = substr($class->date_time, 2, 8) }}</td>
        }
        @endforeach
        <td>Percent</td>
    </tr>

    <?php $uid = 0; $count=0; $totalPresent = 0; ?>
    @foreach ($info as $row) {
    @if($uid != $row->user_id){
    <tr>
        <td>{{ $row->roll_full_form }}</td>
        <td>{{ $row->name }}</td>
        }
        @endif

        @if($row->attendance){
        <?php  $status = "P"; ++$totalPresent; ?>
        }
        @else{
        <?php $status = "A" ?>
        }
        @endif
        <td class='{{ $status == "A" ? "absent":"present"}}'>{{ $status }}</td>
        <?php ++$count; ?>
        @if($count % count($allClasses) == 0){
        <?php
        $percent = round($totalPresent*100/7, 2);
        $c = count($allClasses);
        ?>
        <td>
            <span style='color:#666; padding-right:10px;'>{{ $totalPresent }}/{{ $c }} </span>
            <span class="percent">{{ $percent }} %</span>
        </td>
    </tr>
    <?php $totalPresent = 0; ?>
    }
    @endif
    <?php  $uid = $row->user_id;   ?>
    }
    @endforeach

</table>




</body>
</html>









</body>
</html>