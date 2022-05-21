<?php
$f="./data1/";

include($f."data.inc.php");

$video_s_height=floor($video_height/($vcount-1)-1);			//小视窗高    小视窗数量为n=($vcount-1);
$video_s_width=round($video_s_height*($video_width/$video_height));	//小视窗宽

$mainframe_width=($video_width+6)+($video_s_width+6);

?><html>
<head><meta http-equiv="content-type" content="text/html;charset=UTF-8">
  <title>分屏播放</title>
  <script type="text/javascript" src="./js/jquery.js"></script>
</head>
<body>﻿
<input type=button onclick="set();" value="set">
<input type=button onclick="go();" value="go">
<input type=button onclick="pause();" value="pause">
<input type=button onclick="play();" value="play">
<div> <a id="duration">0:00</a> ( <a id="current">0:00</a> ) </div>
<p>
<div  style="position: absolute; font-size: 28px;width: <?php echo $mainframe_width;?>px;height: <?php echo ($video_height+6);?>px;text-align: center;background-image:url('./data1/bg.png');margin: auto; right: 0;left: 0; ">
  <div style="width:<?php echo $mainframe_width;?>px;height: <?php echo ($video_height+6);?>px;position:relative;" align=left >
<script>
var currentMainDiv=null;
var currentMainVideo=null;
<?php

for($i=0;$i<$vcount;$i++)			//初始化视频和DIV的句柄
{
   echo "var d".$i."=null;\r\n";			//DIV
   echo "var v".$i."=null;\r\n";			//VIDEO
   echo "var p".$i."=null;\r\n";			//PLAYSTATUS
}
?>

//音频播放指示器静音
function muteall()
{
<?php 
for($i=0;$i<$vcount;$i++)
{
   echo "   p".$i.".style.display=\"none\";\r\n";
}
?>
}


//全体暂停
function pause()
{
<?php 
for($i=0;$i<$vcount;$i++)
{
   echo "   v".$i.".pause();\r\n";
}
?>
}

//全体播放
function play()
{
<?php 
for($i=0;$i<$vcount;$i++)
{
   echo "   v".$i.".play();\r\n";
}
?>
}

//所有视频就位
//记录好每个视频的坐标位置
var posArr=Array();		//视频坐标
var comments=null;		//字幕
function set()
{
<?php 
for($i=0;$i<$vcount;$i++)		//遍历所有视频
{
   echo "   d".$i."=document.getElementById(\"d".$i."\");\r\n";
   echo "   v".$i."=document.getElementById(\"v".$i."\");\r\n";
   echo "   p".$i."=document.getElementById(\"p".$i."\");\r\n";
   echo "   d".$i.".style.display=\"block\";\r\n";
   echo "   v".$i.".style.display=\"block\";\r\n";
   if($i==0)								//主视频
   {
      echo "   v".$i.".style.width=\"".$video_width."px\";\r\n";
      echo "   v".$i.".style.height=\"".$video_height."px\";\r\n";
      echo "   d".$i.".style.left=\"0px\";\r\n";
      echo "   d".$i.".style.top=\"0px\";\r\n";
      echo "   d".$i.".style.width=\"".$video_width."px\";\r\n";
      echo "   d".$i.".style.height=\"".($video_height+1)."px\";\r\n";
      echo "   d".$i.".style.borderColor=\"red\";\r\n";
      echo "   posArr[".$i."]=Array(".($video_width).",".($video_height+1).",0,0);\r\n";	//主视频：{宽width，高height，左偏移量left，顶部偏移量top}
   }
   else									//分视频
   {
      echo "   v".$i.".style.width=\"".($video_s_width)."px\";\r\n";
      echo "   v".$i.".style.height=\"".($video_s_height)."px\";\r\n";
      echo "   d".$i.".style.left=\"".($video_width+6)."px\";\r\n";
      echo "   d".$i.".style.top=\"".(($i-1)*($video_s_height+2))."px\";\r\n";
      echo "   d".$i.".style.width=\"".($video_s_width)."px\";\r\n";
      echo "   d".$i.".style.height=\"".($video_s_height)."px\";\r\n";
      echo "   d".$i.".style.borderColor=\"gray\";\r\n";
      echo "   posArr[".$i."]=Array(".($video_s_width).",".($video_s_height).",".($video_width).",".(($i-1)*($video_s_height+2)).");\r\n";//分视频：{宽width，高height，左偏移量left，顶部偏移量top}
   }
}
?>
   currentMainDiv=d0;						//默认第一个视频在主屏位置
   comments=document.getElementById("comments");
   comments.innerHTML="本视频演示平台中的小组分享功能。";	//初始化字幕
}

//响应视频点击切换主副屏操作
function setme(obj)
{
   var i=0;

   for(var j=0;j<<?php echo $vcount;?>;j++)
   {
      if(obj.id.charAt(1)==j)//待设置为主屏的DIV
      {
         $("#d"+j).css("zIndex",9999);				//主屏置顶

         $("#v"+j).animate({"width":(posArr[0][0]),"height":(posArr[0][1]+1),"left":0,"top":-27},1000);	//在Chrome里，top为-26；在IE里，top的偏移量为-23.
         $("#d"+j).animate({"width":posArr[0][0],"height":(posArr[0][1]),"left":0,"top":0},1000);
         $("#d"+j).css("borderColor","red");
      }
      else//其它分屏
      {
         i++;

         if(currentMainDiv.id.charAt(1)==j)	//刚才在主屏的，在右偏缩小变成副屏时，应该在其他缩小副屏的上方
            $("#d"+j).css("zIndex",999); 
         else
            $("#d"+j).css("zIndex",99);

         $("#v"+j).animate({"width":(posArr[i][0]),"height":(posArr[i][1]+1),"left":0,"top":-27},1000);					//在Chrome里，top为-27；在IE里，top的偏移量为-23.
         $("#d"+j).animate({"width":(posArr[i][0]),"height":(posArr[i][1]),"left":(posArr[i][2]+6),"top":(posArr[i][3])},1000);
         $("#d"+j).css("borderColor","gray");
      }
   }
   currentMainDiv=obj;
}

//开始播放，从主视频开始
function go()
{
   v0.play();
   v0.ontimeupdate= function(event){onTrackedVideoFrame(this.currentTime, this.duration);};
}

<?php 
for($i=1;$i<$vcount;$i++)
{
   echo "var v".$i."_play=false;\r\n";
}
?>
//分视频和字幕的控制
var speaker=null;
function onTrackedVideoFrame(currentTime, duration){

<?php 
for($i=1;$i<$vcount;$i++)//分屏视频开始的时间点
{
   if($i>1) echo "   else";

   echo "   if(currentTime>".$vstart_time[$i]." && v".$i."_play == false)//时序
   {
      v".$i."_play=true;
      v".$i.".play();
   }
   ";
}
?>
<?php
$subtitle=@file_get_contents($stfile);//字幕加载控制
if($subtitle!="")
{
   $subArrs=explode("\r\n",trim($subtitle,"\r\n"));

   $sc=count($subArrs);
   for($i=0;$i<$sc;$i++)
   {
      $subdataArr=explode("\t",$subArrs[$i]);
      if(count($subdataArr)==3)
      {

//输出JS脚本
?>
   else   if(currentTime > <?php echo $subdataArr[0];?> && currentTime < <?php echo ($subdataArr[0]+0.3);?>)
   {
      comments.innerHTML="<?php echo "[".trim($subdataArr[1])."]".$subdataArr[2];?>";
      muteall();
<?php
//增加语音播放指示
      $u=array_search(trim($subdataArr[1]),$vname);//如果在某人说话时，不切屏，则在字幕文件中说话人的字段后面，添加一个空格。
      if($u!==false)
      {
         echo "      p".$u.".style.display=\"inline\";\r\n";	//说话指示器需要一直有
         if($subdataArr[1]==trim($subdataArr[1]))		//切屏则不一定
         {
            echo "      if(speaker!=p".$u."){ speaker=p".$u."; setme(v".$u.");}\r\n";
         }
      }
         if($i==$sc-1)//结尾淡出效果
         {
            for($j=0;$j<$vcount;$j++)
            {
               echo "      document.getElementById(\"v".$j."\").style.position=\"relative\"; $(\"#v".$j."\").fadeOut(3000);\r\n";
            }
         }
?>
   }
<?php
      } 
   }
}

?>

   $("#current").text(currentTime);
   $("#duration").text(duration);
}
</script>
<?php
for($i=0;$i<$vcount;$i++)
{
?>
    <div id="d<?php echo $i;?>" onclick=setme(this) style="display:none;position:absolute;left:0px;top:0px;border:solid;border-color:gray">
      <div style="position:relative;left:12px;top:11px;z-index:9;background:snow;font-size:20px;width:100px;text-align:center;"><?php echo $vname[$i];?><img id="p<?php echo $i;?>" style="display:none;position:absolute;top:3px;" src=./img/play.gif height=20></div>
      <video  id="v<?php echo $i;?>" class=minv  src="<?php echo $vfile[$i];?>" autoplay="false" autostart=false style="display:none;position:relative;left:0px;top:-27px;">
        <param name="autostart" value="false"/>
        <param src="<?php echo $vfile[$i];?>"/>
      </video>
    </div>
<?php
}
?>
    <div id=comments style="position: absolute;  font-size: 28px; left:7px; top:<?php echo floor($video_height-$video_height%100);?>px;    width: <?php echo ($mainframe_width-14);?>px;    height: 40px;    background: whitesmoke;  z-index:99999;   text-align: center;    opacity: 80%;"></div>
  </div>
</div>
</body>
</html>