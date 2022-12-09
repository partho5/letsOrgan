<?php
    use \App\Library\Library;
    use \Illuminate\Support\Facades\Auth;
    use App\HelperClasses\PointBadges;

    $filePathPrefix = ($profileInfo->pp_url == "/assets/images/avatar.png")? "" : "https://s3.ap-south-1.amazonaws.com/choriyedao";
    $Lib = new Library();
    $userId = Auth::id();
    $userName = Auth::user()->name;
    $communityId = $Lib->getCurrentCommunityId($userId);
    $unreadNotifications = $Lib->getUnreadNotifications($userId, $communityId);
    $readNotifications = $Lib->getReadNotifications($userId, $communityId, 10);
    $communityCount = $Lib->getCommunityCount($userId);

    $this->pointBadge = new PointBadges($userId, $currentCommunityId);
    $reputation = $this->pointBadge->getCurrentReputationPoint();
?>

<div id="menuBar" class="text-center">
    @if($communityCount == 0)
         <a class="menuItem col-md-3 col-xs-6" href="/">Study Info</a>
    @elseif($communityCount == 1)
        <a class="menuItem col-md-3 col-xs-6" href="/community/view/{{ $communityId }}">Study Info</a>
    @elseif($communityCount > 1)
        <a class="menuItem col-md-3 col-xs-6" href="/community/all">Study Info</a>
    @endif
        <a class="menuItem col-md-3 col-xs-6" href="/users/cloud/{{ $currentCommunityId }}">My Files</a>

    <a class="menuItem col-md-3 col-xs-6" href="/notice_board">Notice Board</a>
    <a class="menuItem col-md-3 col-xs-6" href="/qa/">QA Forum</a>

    <div id="options">
        <ul class="list-inline">
            <li><a href="/">Home</a></li>
            <li id="profile-link">
                <a id="avatar" href="/users/profile/id/{{ $userId }}">
                    <img src="{{$filePathPrefix}}{{$profileInfo->pp_url or "/assets/images/avatar.png"}}" alt="{{ $userName }}" title="My Profile">&nbsp;&nbsp;
                </a>
            </li>
            <li>
                <span id="reputation" title="Reputation score : {{ $reputation }}">
                    <img src="/assets/images/reputation.png" alt="" style="width: 15px; height: 15px;">
                    <span style="background-color: #00a962; padding: 1px 5px; border-radius: 30%">{{ $reputation }}</span>
                </span>
            </li>

            <li id="noti_Container">
                <?php $totalUnread = count($unreadNotifications); ?>
                @if($totalUnread > 0)
                    <span id='noti_Counter'>{{ $totalUnread }}</span>
                @endif
                        <!--A CIRCLE LIKE BUTTON TO DISPLAY NOTIFICATION DROPDOWN.-->
                <div id="noti_Button"><img src="/assets/images/bell-icon.png" width="30px" height="30px"></div>

                <!--THE NOTIFICAIONS DROPDOWN BOX.-->
                <div id="notifications">
                    <p id="notific_heading">Notifications</p>
                    <div id="notification-row-container" style="height:300px;">
                        @foreach($unreadNotifications as $notification)
                            <p class="single-notification unread-notification" notific-id="{{ $notification->id }}" link="{{ $notification->link }}">{{ $notification->readable_text }}</p>
                        @endforeach

                        @foreach($readNotifications as $notification)
                            <p class="single-notification" notific-id="{{ $notification->id }}" link="{{ $notification->link }}">{{ $notification->readable_text }}</p>
                        @endforeach
                    </div>
                    <div class="seeAll"><span>See All</span></div>
                </div>
            </li>


            <li class="hidden"><a title="This is a upcoming fearure" href="/website/test-profile">My Website</a></li>
            @if($communityCount > 0)
                <li><a href="/users/community/settings">Settings</a></li>
                <li><a href="/tutorial/video">Tutorial Collection</a></li>
            @endif

            <li class="">
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                   document.getElementById('logout-form').submit();">
                   Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
        </ul>
    </div>
</div>

<script type="text/javascript" src="/assets/js/library.js"></script>
<script type="text/javascript" src="/assets/js/mousemove.js"></script>
<script>

    function markAsSeen(notificationId) {
        $.ajax({
            url : '/notification/mark_as_seen',
            type : 'post',
            dataType : 'text',
            data : {
                _token : "<?php echo csrf_token() ?>",
                notificationId : notificationId,
                seenFrom : 'website'
            }, success : function (response) {
                console.log(response);
            }, error : function () {
            }
        });
    } // markAsSeen()

    /************ Notification ************/
    //http://www.encodedna.com/jquery/create-a-facebook-like-notifications-using-jquery-css.htm

    // ANIMATEDLY DISPLAY THE NOTIFICATION COUNTER.
//    $('#noti_Counter')
//        .css({ opacity: 0 })
//        .text('7')              // ADD DYNAMIC VALUE (YOU CAN EXTRACT DATA FROM DATABASE OR XML).
//        .css({ top: '-10px' })
//        .animate({ top: '-2px', opacity: 1 }, 500);

    var alreadyMarked = false;
    $('#noti_Button, #noti_Counter').click(function () {

        // TOGGLE (SHOW OR HIDE) NOTIFICATION WINDOW.
        $('#notifications').fadeToggle('fast', 'linear', function () {
            if ($('#notifications').is(':hidden')) {
                //$('#noti_Button').css('background-color', '#2E467C');
            }
            else $('#noti_Button').css('background-color', '#FFF');        // CHANGE BACKGROUND COLOR OF THE BUTTON.
        });

        $('#noti_Counter').fadeOut('slow');                 // HIDE THE COUNTER.

        $('.unread-notification').each(function () {
            var notificationId = $(this).attr('notific-id');
            if( ! alreadyMarked ){
                markAsSeen(notificationId);
            }
        });
        alreadyMarked = true;

        return false;
    });

    // HIDE NOTIFICATIONS WHEN CLICKED ANYWHERE ON THE PAGE.
    $(document).click(function () {
        $('#notifications').hide();

        // CHECK IF NOTIFICATION COUNTER IS HIDDEN.
        if ($('#noti_Counter').is(':hidden')) {
            // CHANGE BACKGROUND COLOR OF THE BUTTON.
            //$('#noti_Button').css('background-color', '#2E467C');
        }
    });

    $('#notifications').click(function () {
        return false;       // DO NOTHING WHEN CONTAINER IS CLICKED.
    });


    $('.single-notification').click(function(){
        console.log($(this).attr('notific-id'));
        location.replace($(this).attr('link'));
    });


    /************ Notification ************/


    var rawPoints, filteredPoints;
    var clickTime = [];
    var clickPositionX = [];
    var clickPositionY = [];
    var clicks = [];

    $('body').on('mousemove', function (event) {
        rawPoints = grabPoints(event);
    });

    $('body').on('click', function (event) {
        clickTime.push(new Date().toLocaleString());
        clickPositionX.push(event.clientX);
        clickPositionY.push(event.clientY);
        clicks = [{'time' : clickTime, 'x' : clickPositionX, 'y' : clickPositionY}];
    });

    //update session_duration at every certain interval
    var incrementSessionDurationAfterEvery = incrementSessionDurationAjaxInterval; //defined in library.js
    setInterval(function () {
        filteredPoints = excludePointsCloserThan(10);
        //console.log(filteredPoints);

        $.ajax({
            url : '/visitor/increment_visit_time',
            type : 'post',
            data :{
                _token : "{{ csrf_token() }}",
                incrementBy : incrementSessionDurationAfterEvery , // seconds
                pageUrl : window.location.pathname+window.location.search, mousemove : filteredPoints, click : clicks,
            },
            success : function (response) {
                //console.log(response);
            },error : function () {
                console.log('error');
            }
        });
    }, incrementSessionDurationAfterEvery*1000);


</script>