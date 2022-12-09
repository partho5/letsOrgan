<html>
<head>
    <style>
        .container{
            padding: 5px 10px;
        }
        h3{
            text-align: center;
            font-family: Georgia, Times, "Times New Roman", serif;
        }
        #alert-box{
            border: 1px solid #000000;
            text-align: center;
        }
        #alert-box .important{
            color: #f00;
            font-size: 30px;
        }
        #alert-box .span1{
            color: #008c52;
        }
        #alert-box .span2{
            color: #9e322f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>
            <span style="color: #00a962">Lets</span><span style="color: #dc9258">Organ</span>
            এ  আপনাকে  স্বাগতম
        </h3>
        Hello {{ $data['name'] }},
        <p>
            আপনার LetsOrgan অ্যাকাউন্ট সফলভাবে সম্পন্ন হয়েছে। LetsOrgan এর মূল ফিচারগুলো উপভোগ করতে দয়া করে আপনার
            ডিপার্টমেন্টের  কমিউনিটি  তে জয়েন করুন  ।  <a href="http://letsorgan.com/">এখানে </a>
        </p>
         <div id="alert-box">
             <span class="important"> দ্রষ্টব্য </span> <br>
             <span class="span1">LetsOrgan সকল একাডেমিক ও অফিসিয়াল নোটিফিকেশন ইন্সট্যান্ট মেইল করে থাকে</span> <br>
             <span class="span2"> দয়া  করে  রেগুলার মেইল  চেক  করুন </span>
         </div>
    </div>
</body>
</html>