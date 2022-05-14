<?php

$vcount=4;						//视频总数
$vname=Array("吴老师","学生一","学生二","学生三");	//对应的视频用户
$vfile=Array("./data1/1.mp4","./data1/2.mp4","./data1/3.mp4","./data1/4.mp4");		//对应的视频文件

$video_height=642;					//视频高
$video_width=1024;					//视频宽

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
}
?>

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
   echo "   d".$i.".style.display=\"block\";\r\n";
   echo "   v".$i.".style.display=\"block\";\r\n";
   if($i==0)								//主视频
   {
      echo "   v".$i.".style.width=\"".$video_width."px\";\r\n";
      echo "   v".$i.".style.height=\"".$video_height."px\";\r\n";
      echo "   d".$i.".style.left=\"0px\";\r\n";
      echo "   d".$i.".style.top=\"0px\";\r\n";
      echo "   d".$i.".style.width=\"".$video_width."px\";\r\n";
      echo "   d".$i.".style.height=\"".$video_height."px\";\r\n";
      echo "   d".$i.".style.borderColor=\"red\";\r\n";
      echo "   posArr[".$i."]=Array(".($video_width).",".($video_height).",0,0);\r\n";	//主视频：{宽width，高height，左偏移量left，顶部偏移量top}
   }
   else									//分视频
   {
      echo "   v".$i.".style.width=\"".($video_s_width)."px\";\r\n";
      echo "   v".$i.".style.height=\"".($video_s_height)."px\";\r\n";
      echo "   d".$i.".style.left=\"".($video_width+6)."px\";\r\n";
      echo "   d".$i.".style.top=\"".(($i-1)*($video_s_height+2))."px\";\r\n";
      echo "   d".$i.".style.width=\"".($video_s_width)."px\";\r\n";
      echo "   d".$i.".style.height=\"".($video_s_height-1)."px\";\r\n";
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

         $("#v"+j).animate({"width":(posArr[0][0]),"height":(posArr[0][1]),"left":0,"top":-26},3000);	//在Chrome里，top为-26；在IE里，top的偏移量为-23.
         $("#d"+j).animate({"width":posArr[0][0],"height":posArr[0][1],"left":0,"top":0},3000);
         $("#d"+j).css("borderColor","red");
      }
      else//其它分屏
      {
         i++;

         if(currentMainDiv.id.charAt(1)==j)	//刚才在主屏的，在右偏缩小变成副屏时，应该在其他缩小副屏的上方
            $("#d"+j).css("zIndex",999); 
         else
            $("#d"+j).css("zIndex",99);

         $("#v"+j).animate({"width":(posArr[i][0]),"height":(posArr[i][1]),"left":0,"top":-26},3000);					//在Chrome里，top为-26；在IE里，top的偏移量为-23.
         $("#d"+j).animate({"width":(posArr[i][0]),"height":(posArr[i][1]-1),"left":(posArr[i][2]+6),"top":(posArr[i][3])},3000);
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

//分视频和字幕的控制
function onTrackedVideoFrame(currentTime, duration){
   if(currentTime>6.99 && currentTime <7.12)//时序
   {
      v1_play=1;
      v1.play();
   }
   else if(currentTime>8.19&& currentTime <8.21)
   {
      v2_play=1;
      v2.play();
   }
   else if(currentTime>7.42&& currentTime <7.47)
   {
      v3_play=1;
      v3.play();
   }

<?php
$subtitle=@file_get_contents("./subtitle.txt");
if($subtitle!="")
{
   $subArrs=explode("\r\n",trim($subtitle,"\r\n"));

   $sc=count($subArrs);
   for($i=0;$i<$sc;$i++)
   {
      $subdataArr=explode("\t",$subArrs[$i]);
      if(count($subdataArr)==3)
      {
?>
   else if(currentTime><?php echo $subdataArr[0];?> && currentTime <<?php echo ($subdataArr[0]+0.3);?>)
   {
      comments.innerHTML="<?php echo $subdataArr[1]."".$subdataArr[2];?>";
<?php
         if($i==$sc-1)
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
      <div style="position:relative;left:12px;top:11px;z-index:9;background:snow;font-size:20px;width:100px;text-align:center;"><?php echo $vname[$i];?></div>
      <video  id="v<?php echo $i;?>" class=minv  src="<?php echo $vfile[$i];?>" autoplay="false" autostart=false style="display:none;position:relative;left:0px;top:-26px;">
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