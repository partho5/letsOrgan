/**
 * Created by partho on 4/6/17.
 */


function validateUrl(value) {
    return /^(?:(?:(?:https?|ftp):)?\/\/)(?:\S+(?::\S*)?@)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:[/?#]\S*)?$/i.test(value);
}

function directoryExist(dirName){
    var exist = false;
    $(".dir").each(function () {
        if( ($(this).text() == dirName) ){
            exist = true;
            return false; //this breaks $.each
        }
    });
    return exist;
}

function validDirName(dirName){
    var chars = dirName.split('');
    for (var i in chars){
        if(chars[i] === "*" || chars[i] === "/" || chars[i] === "&" ){
            return false;
        }
    }
    return true;
}

window.mobileOrTablet = function() {
    var check = false;
    (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
    return check;
};

function isRightClickedInBlank(clickedTxt){
    var blank = true;

    $('.dir, .file').each(function () {
        if ($(this).text() === clickedTxt) {
            blank = false;
            return false;
        }
    });

    if( clickedTxt.indexOf('DetailsDownload') >= 0 ){
        blank = true;
    }

    return blank;
}

function getDestinationFullDir(){
    var full_dir_url = "";
    $(".dirPartInBar").each(function (index) {
        full_dir_url += "/"+$(this).text();
    });
    return full_dir_url ? full_dir_url : '/';
}

function dataToAppendForNewDir(cloudId, dirName){

    return '<div class="dirWrapper col-md-12" tabindex="1">' +
        '<span class="dir hasmenu col-md-8" cloud_id="'+(cloudId)+'">'+(dirName)+'</span>'+
//                            '<span class="col-md-4">'+
//                            '<ul class="list-inline">'+
//                            '<li class="cloudBtn"><a target="_blank" href="/users/cloud/view/">Details</a></li>'+
//                            '<li class="cloudBtn"><a target="_blank" href="/users/cloud/download/">Download</a></li>'+
//                            '</ul>'+
//                            '</span>'+
        '</div>';
    //on-create dir, don't show options, so commented 'span ul li' above
}

function dataToAppendForNewFile(cloudId, fileName){
    return '<div class="fileWrapper col-md-12" tabindex="1">'+
    '<span class="file hasmenu col-md-8" cloud_id="'+cloudId+'"><a class="hasmenuA" target="_blank" href="/users/cloud/view/'+cloudId+'" cloud_id="'+cloudId+'">'+fileName+'</a></span>'+
    '<span class="col-md-4">'+
    '<ul class="list-inline">'+
    '<li class="cloudBtn"><a target="_blank" href="/users/cloud/view/'+cloudId+'">Details</a></li>'+
    '<li class="cloudBtn"><a target="_blank" href="/users/cloud/preview/'+cloudId+'">Preview</a></li>'+
    '<li class="cloudBtn"><a href="/users/cloud/download/'+cloudId+'">Download</a></li>'+
        '</ul>'+
        '</span>'+
        '</div>';
}


function hideCommunityPost(THIS){
    var thisPost = THIS.closest('.singleUserPost');
    thisPost.css('opacity', 0.2);
    thisPost.slideToggle(1500);
}

var fakeCookie = [];
function setFakeCookie(key, val) {
    fakeCookie[key] = val;
}
function getFakeCookie(key) {
    return fakeCookie[key];
}

function bgcolorAnimate(element, color1, color2){
    element.animate({
        backgroundColor: color1
    }, 800, function () {
        element.css('border','0px');
        element.animate({
            backgroundColor: color2
        },800);
    });
}

function updateGeneral_postToNicEdit(singleUserPost, postTitle, postDetails, postType, postId){
    var panelId = 'editable'+postType+postId;
    singleUserPost.fadeOut(300);
    setTimeout(function () {
        singleUserPost.html(
            '<div class="col-md-12" id="generalPostContainer">'+
            '<input id="generalTitle'+postId+'" type="text" class="generalTitle form-control col-md-12" value="'+postTitle+'">'+
            '<label class="text-left">Details (Optional) : </label>'+
            '<textarea name="postField" id="'+panelId+'" rows="5" class="col-md-12" value=""></textarea>'+
            '<button class="spanBtn cancelUpdatePostBtn" post-type="'+postType+'" post-id="'+postId+'" style="color: #fff; background-color: #f00">Cancel</button>'+
            '<button class="spanBtn updatePostBtn" post-type="'+postType+'" post-id="'+postId+'">Update</button>'+
            '</div>'
        );
        new nicEditor().panelInstance(panelId);
        nicEdit = new nicEditors.findEditor(panelId);
        nicEditors.findEditor( panelId ).setContent( postDetails );
    }, 300);
    singleUserPost.fadeIn(400);
}

function updateExam_postToNicEdit(singleUserPost, examCourseName, examDeclareDate, examDate, examDetails,postType, postId){
    var panelId = 'editable'+postType+postId;
    singleUserPost.fadeOut(300);
    setTimeout(function () {
        singleUserPost.html(
            '<div class="form-group col-md-12">'+
            '<label for="" class="col-md-4 text-left">Course Code/Name</label>'+
            '<div class="input-group col-md-8">'+
            '<input type="text" id="examCourseName'+postId+'" class="examCourseName form-control" value="'+examCourseName+'">'+
            '</div>'+
            '</div>'+
            '<div class="form-group col-md-12">'+
            '<label for="" class="col-md-4 text-left">Exam Declared at</label>'+
            '<div class="input-group col-md-8">'+
            '<input type="text" id="examDeclareDate'+postId+'" class="examDeclareDate form-control" value="'+examDeclareDate+'">'+
            '</div>'+
            '</div>'+
            '<div class="form-group col-md-12">'+
            '<label for="" class="col-md-4 text-left">Exam Date Time</label>'+
            '<div class="input-group col-md-8">'+
            '<input type="text" id="examDate'+postId+'" class="examDate form-control" value="'+examDate+'">'+
            '</div>'+
            '</div>'+
            '<div class="form-group col-md-12">'+
            '<div class="input-group col-md-12">'+
            '<label class="text-left"></label>'+
            '<textarea name="examDetails" id="'+panelId+'" rows="4" class="col-md-12" ></textarea>'+
            '</div>'+
            '<button class="spanBtn cancelUpdatePostBtn" post-type="'+postType+'" post-id="'+postId+'" style="color: #fff; background-color: #f00">Cancel</button>'+
            '<button class="spanBtn updatePostBtn" post-type="'+postType+'" post-id="'+postId+'">Update</button>'+
            '</div>'
        );
        new nicEditor().panelInstance(panelId);
        nicEdit = new nicEditors.findEditor(panelId);
        nicEditors.findEditor( panelId ).setContent( examDetails );
    }, 300);
    singleUserPost.fadeIn(400);
} //updateExam_postToNicEdit


function updateAssignmentToNicEdit(singleUserPost, assignTitle, givenAt, deadline, details, postType, postId){

    //singleUserPost.css('background-color', '#786');
}

function contentOfEditedPoll(postId, pollOptionStr){
    var domToAppend = '<div class="singleUserPost" id="poll_container3">'+
    '<div class="col-md-12"> <!-- start poll heading portion -->'+
    '<div class="postHeading col-md-10" id="poll_q3">tyhjut btu</div>'+
    '<img title="Edit" post-type="poll" post-id="3" class="editIcon col-md-1" src="/assets/images/edit-icon.png" alt="Edit">'+
    '<img title="Delete" post-type="poll" post-id="3" class="deleteIcon col-md-1" src="/assets/images/delete-btn.png" alt="Delete">                                                    </div> <!-- end poll heading portion -->'+
    '<div class="col-md-12">'+
    '<div id="pollOptionsWrapper">'+
    pollOptionStr+
    '</div>'+
    '</div>'+
    '<div class="col-md-12"> <!-- start posted by portion -->'+
'    <div class="text-right"><a href="/users/profile/id/0">Anonymous</a> [ <span title="2017-08-25 18:36:01">2 hours ago</span> ] </div>'+
        '</div> <!-- end posted by portion -->'+
    '<br><hr>'+
        '</div> <!-- end polls -->';

    return domToAppend;
}






// STSRT "nicEdit to Post" functions definition
function generalNicEditToPost(dom, postId, generalTitle, generalDetails){
    dom.html(
        '<div class="col-md-12"> <!-- start heading portion -->'+
    '<div id="generalTitle'+postId+'" class="postHeading col-md-10">'+generalTitle+'</div>'+
    '<img title="Edit" post-type="general" post-id="'+postId+'" class="editIcon col-md-1" src="/assets/images/edit-icon.png" alt="Edit">'+
    '<img title="Delete" post-type="general" post-id="'+postId+'" class="deleteIcon col-md-1" src="/assets/images/delete-btn.png" alt="Delete">                                                    </div> <!-- end heading portion -->'+
    '<div id="generalDetails'+postId+'" class="postDetails">'+generalDetails+'</div> <!-- general post details -->'+

    '<div class="col-md-12"> <!-- start posted by portion -->'+
    '<div class="text-right">Me [ <span>Edited Just Now</span> ] </div>'+
    '</div> <!-- end posted by portion -->'+
    '<br><hr>'
    );
}

function examNicEditToPost(singleUserPost, examCourseName, examDeclareDate, examDate, examDetails,postType, postId){
    singleUserPost.html(
        '<div class="singleUserPost">'+
        '<div class="col-md-12"> <!-- start exam heading portion -->'+
        '<div class="postHeading col-md-10"><span class="courseName" id="examCourseName'+postId+'">'+(examCourseName)+'</span> Exam</div>'+
        '<img title="Edit" post-type="exam" post-id="'+postId+'"  class="editIcon col-md-1" src="/assets/images/edit-icon.png" alt="Edit">'+
        '<img title="Delete" post-type="exam" post-id="'+postId+'" class="deleteIcon col-md-1" src="/assets/images/delete-btn.png" alt="Delete">'+
        '</div> <!-- end exam heading portion -->'+

        '<!-- start exam body portion -->'+
        '<p for="" class="col-md-12">'+
        '<span class="col-md-4">Exam Deeclared At : </span>'+
        '<span id="examDeclareDate'+postId+'" class="examDeclareDate col-md-8">'+(examDeclareDate)+'</span>'+
        '</p>'+
        '<p for="" class="col-md-12">'+
        '<span class="col-md-4">Exam Date time : </span>'+
        '<span id="examDate'+postId+'" class="examDate col-md-8">'+(examDate)+'</span>'+
        '</p>'+
        '<div id="examDetails'+postId+'" class="postDetails">'+examDetails+'</div> <!-- exam post details -->'+
        '<div class="col-md-12"> <!-- start posted by portion -->'+
        '<div class="text-right">Me [ <span title="just now">just now</span> ] </div>'+
        '</div> <!-- end posted by portion -->'+
        '<br><hr>'+
        '</div> <!-- examPosts -->'
    );
}

function incrementByOne(dom){
    var existing = dom.text();
    dom.text(parseInt(existing)+1);
}
function decrementByOne(dom){
    var existing = dom.text();
    dom.text(parseInt(existing)-1);
}

function fileSizeReadableFormat(fileSize){
    return (fileSize>1024) ? (fileSize/1024).toFixed(2)+" MB" : fileSize+" KB" ;
}


var allInstituteList = [
    "Bangabandhu Sheikh Mujib Medical University","Bangabandhu Sheikh Mujibur Rahman Agricultural University","Bangabandhu Sheikh Mujibur Rahman Maritime University","Bangabandhu Sheikh Mujibur Rahman Science and Technology University","Bangladesh Agricultural University","Bangladesh Open University","Bangladesh University of Engineering and Technology","Bangladesh University of Professionals","Bangladesh University of Textiles","Barisal University","Begum Rokeya University","Chittagong Medical University","Chittagong University of Engineering and Technology","Chittagong Veterinary and Animal Sciences University","Comilla University","Dhaka University of Engineering and Technology","Hajee Mohammad Danesh Science and Technology University","Islamic Arabic University","Islamic University","Jagannath University","Jahangirnagar University","Jatiya Kabi Kazi Nazrul Islam University","Jessore University of Science and Technology","Khulna University","Khulna University of Engineering and Technology","Mawlana Bhashani Science and Technology University","National University","Noakhali Science and Technology University","Pabna University of Science and Technology","Patuakhali Science And Technology University","Rabindra University","Northern University of Business and Technology, Khulna","Rajshahi Medical University","Rajshahi University of Engineering and Technology","Rangamati Science and Technology University","Shahjalal University of Science and Technology","Sher-e-Bangla Agricultural University","Sylhet Agricultural University","University of Chittagong","University of Dhaka","University of Rajshahi","Independent University, Bangladesh", "Ahsanullah University of Science and Technology","American International University-Bangladesh","Anwer Khan Modern University","ASA University Bangladesh","Asian University of Bangladesh","Atish Dipankar University of Science and Technology","Bangladesh Army International University of Science and Technology","Bangladesh Islami University","Bangladesh University","Bangladesh University of Business and Technology","Bangladesh University of Health Sciences","BGC Trust University Bangladesh","BGMEA University of Fashion and Technology","BRAC University","Britannia University","Canadian University of Bangladesh","CCN University of Science and Technology","Central University of Science and Technology","Central Women's University","Chittagong Independent University","City University","Cox's Bazar International University","Daffodil International University","Dhaka International University","East Delta University","East West University","Eastern University","European University of Bangladesh","Exim Bank Agricultural University","Fareast International University","Feni University","First Capital University of Bangladesh","German University Bangladesh","Global University Bangladesh","Gono Bishwabidyalay","Green University of Bangladesh","Hamdard University Bangladesh","IBAIS University","International Islamic University Chittagong","International University of Business Agriculture and Technology","Ishakha International University","","Khwaja Yunus Ali University","Leading University","Manarat International University","Metropolitan University","N.P.I University of Bangladesh","North Bengal International University","North East University Bangladesh","North South University","North Western University","Northern University Bangladesh","Notre Dame University Bangladesh","Port City International University","Premier University","Presidency University","Prime University","Primeasia University"," Pundra University of Science and Technology","Queens University","Rabindra Maitree University","Rajshahi Science and Technology University","Ranada Prasad Shaha University","Royal University of Dhaka","Rupayan A.K.M Shamsuzzoha University","Shanto-Mariam University of Creative Technology","Sheikh Fazilatunnesa Mujib University","Sonargaon University","Southeast University","Southern University Bangladesh","Stamford University Bangladesh","State University of Bangladesh","Sylhet International University","The International University of Scholars","The Millennium University","The People's University of Bangladesh","University of Asia Pacific","Times University"," Bangladesh","United International University","University of Creative Technology","University of Development Alternative","University of Global Village","University of Information Technology and Sciences","University of Liberal Arts Bangladesh","University of Science and Technology Chittagong","University of South Asia","Uttara University","Varendra University","Victoria University of Bangladesh","World University of Bangladesh","Z.H Sikder University of Science and Technology"
];
var allDeptList = [
    "Water and Environment","Accounting and Information Systems","Agronomy and Agricultural Extension","Anthropology", "Applied Chemistry", "Applied Chemistry and Chemical Engineering","Applied Mathematics","Applied Physics and Electronic Engineering","Arabic","Architechture","Architecture","Bangla","Banking and Insurance","Biochemistry and Molecular Biology","Biomedical Engineering","Biomedical Physics and Technology","Botany","Building Engineering and Construction Management","Ceramics and Sculpture","Chemical Engineering","Chemical and food Process Engineering","Chemistry","Civil Engineering","Clinical Pharmacy and Pharmacology","Clinical Psychology","Computer Science and Engineering","Crop Science and Technology","Disaster Science and Management","Economics","Educational and Counselling Psychology","Electrical and Computer Engineering","Electrical and Electronic Engineering","Electronic and Telecommunication Engineering","Electronics and Communication Engineering","Energy  Science and Engineering","English","Finance","Fisheries","Folklore","Genetic Engineering and Bio-Technology","Genetic Engineering and Biotechnology","Geography and Environment","Geography and Environmental Studies","Geology and Mining","Geology","Glass and Ceramic Engineering","Glass and Ceramics Engineering","History","Humanities","Industrial Engineering and Management","Industrial and Production Engineering","Information Science and Library Management","Information and Communication Engineering", "Institute of Information Technology", "International Relations","Islamic History and Culture","Islamic Studies","Language","Law and Land Administration","Law","Leather Engineering","Management studies", "Management Information Systems", "Marketing","Mass Communication and Journalism","Material Science and Engineering","Materials Science and Engineering","Materials and Metallurgical Engineering","Mathematics","Mechanical Engineering","Mechatronics Engineering","Meteorology","Microbiology","Music","Naval Architecture and Marine Engineering","Nuclear Engineering","Oceanography","Oriental Art and Printmaking","Painting","Petroleum and Mineral Resources Engineering","Pharmaceutical Chemistry","Pharmaceutical Technology","Pharmacy","Philosophy","Physical Education and Sports Sciences","Physics","Political Science","Population Science and Human Resource Development","Psychology","Public Administration","Robotics and Mechatronics Engineering","Social Work","Sociology","Soil, Water and Environment","Statistics","Textile Engineering","Theatre","Theoretical Physics","Theoretical and Computational Chemistry","Urban and Regional Planning","Veterinary and Animal Sciences","Water Resources Engineering","Zoology","Graphic Design","Crafts & History of Art","Institute of Marine Sciences and Fisheries","Institute of Forestry and Environmental Sciences","Research Centre for Mathematics and Physical Sciences","Geography and Environmental Science","Soil Science","Fine Arts","Oriental Languages","Islamic Studies and Culture", "Nazrul Research Centre","Home Economics","Finance and Banking","Business Research"
];

var incrementSessionDurationAjaxInterval = 5; //seconds


