<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <title>Online Radio</title>
</head>    
<body>
<?php
//Header("content-type: application/x-javascript");

date_default_timezone_set("Asia/Kolkata");
require_once('getid3.php');

$getID3 = new getID3;
$arrContextOptions=array(
    "ssl"=>array(
        "verify_peer"=>false,
        "verify_peer_name"=>false,
    ),
);  

$response = file_get_contents("https://www.paalamtv.com/audiofilesGet.php", false, stream_context_create($arrContextOptions));

$files = $response;


$myfile = fopen("mp3.txt", "r") or die("Unable to open file!");

$s = fread($myfile,filesize("mp3.txt"));


$str = explode('|',$s);
$videopath =$str[0];
//echo $videopath;

$timeFirst  = date('Y-m-d H:i:s');
$timeSecond = $str[2];





//echo $differenceInSeconds;


$datetime1 = strtotime($timeFirst );
$datetime2 = strtotime($timeSecond);
$interval  = abs($datetime1 - $datetime2);

//echo $interval ;
$minutes   = round($interval / 60);
//echo 'Diff. in minutes is: '.$minutes;
//exit;

$file = $getID3->analyze($videopath);
$duration = $file['playtime_string'];
                     

  $ex = explode(":",$str[1]);
 // echo count($ex);                    

if(count($ex) ==2)
{
  
  $duration = $ex[0].' minutes +'.$ex[1].' seconds';
}
//$duration ="3";
$current =$str[2];
$newtimestamp = strtotime('+'.$duration ,strtotime($current));
$cur = date('Y-m-d  H:i:s', strtotime('+'.$duration) );

//echo $cur;

//echo 'wevfgewghf-'.$date = date('H:i:s', strtotime('+'.$duration.' minute') );




//echo date('Y-m-d H:i:s') .'>='. $cur;
if(strtotime(date('Y-m-d H:i:s')) > strtotime($cur) )
{
$date = $files.'|'.date('Y-m-d H:i:s');


$file2 = fopen("mp3.txt","w");
fwrite($file2,$date);
fclose($file2);
}
?>
<script src="./jquery.js"></script>
<script src="./jquery.min.js"></script>


<script type="text/javascript" src="./jw6/jwplayer.js"></script>
<marquee id="songtitle" style="color:#fff;height: 70px;width: 264px;"></marquee>
<div id="sound">Loading the player ...</video>
<script type="text/javascript">
jwplayer("sound").setup({
flashplayer: "jwplayer/player.swf",
file: "<?php echo $str[0]; ?>",
fullscreen:false,
autostart: true,
events:{
          onComplete:function(event){completedFunction(Math.floor(CurrentTime));},
          onPause:function(event){ pauseFunction(Math.floor(CurrentTime)); },
          onTime: function(event) {
          
          CurrentTime = event.position;
          
          
          },
          onSeek: function(event) {

            IsSkipped = true;
          }

      },

height: 24,
width: 280

});


// var player = "sound"
// var playerInstance = jwplayer(player);

// playerInstance.onReady(function () {
//     $("#" +player+ " .jw-media")
//         .each(function () {
//             var audioHtml = $(this).html();
//             audioHtml = audioHtml
//                 .replace(/<video/g, '<audio')
//                 .replace(/<\/video>/g, '</audio>');
//             $(this).html(audioHtml);
//         });
// });

function chnagesaudion(data)
{

jwplayer("sound").setup({
flashplayer: "jwplayer/player.swf",
autostart: true,
file: data,
events:{
          onComplete:function(event){completedFunction(Math.floor(CurrentTime));},
          onPause:function(event){ pauseFunction(Math.floor(CurrentTime)); },
          onTime: function(event) {
          
          CurrentTime = event.position;
          
          
          },
          onSeek: function(event) {

            IsSkipped = true;
          }

      },

height: 24,
width: 280
});
// var player = "sound"
// var playerInstance = jwplayer(player);

// playerInstance.onReady(function () {
//     $("#" +player+ " .jw-media")
//         .each(function () {
//             var audioHtml = $(this).html();
//             audioHtml = audioHtml
//                 .replace(/<video/g, '<audio')
//                 .replace(/<\/video>/g, '</audio>');
//             $(this).html(audioHtml);
//         });
// });
jwplayer("sound").play();
playingname();

}

function stopVideo()
    {
      jwplayer("beginner-videos").pause(true);
    }
    
    function completedFunction()
    {

        $.ajax({
    type: 'POST',  
    url: './mp3.php',  
    data: { action: 'completed'},
    success: function(data, textStatus, XMLHttpRequest){
      //jwplayer("sound").load(data);

chnagesaudion(data);
      //alert(data);
      //jwplayer("sound").setup({file:data});
     // jwplayer("sound").play();


      
    },
    error: function(MLHttpRequest, textStatus, errorThrown){  
      alert(errorThrown);
    } 
    });


    }
    function pauseFunction(times)
    {

        $.ajax({
    type: 'POST',  
    url: './usertracking.php',  
    data: { action: 'pauses',userId:userId,courseId:courseId,chapterId:chapterId,lessonId:lessonId,videosId:videosId,times:times },
    success: function(data, textStatus, XMLHttpRequest){
      
    },
    error: function(MLHttpRequest, textStatus, errorThrown){  
      alert(errorThrown);
    } 
    });


    }
jwplayer("sound").play();
jwplayer("sound").seek(<?php echo $interval;?>);

playingname();
function playingname()
{

jwplayer('sound').onPlaylistItem(function(event){

     index = jwplayer('sound').getPlaylistIndex();
     item = jwplayer('sound').getPlaylistItem(index);

     theFile = item.file;
 
      theFile =theFile.replace("http://paalamtv.com/songsfolder/", "");
      theFile = theFile.replace(".mp3", "");
        theFile = theFile.replace("-", " ");
          theFile = theFile.replace("_", " ");
          $('#songtitle').html(theFile);
     
 });

}
jwplayer("sound").play();
</script>
</body>
</html>
