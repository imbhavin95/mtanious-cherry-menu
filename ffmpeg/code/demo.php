<?php

 /*** convert video to flash ***/
 exec("ffmpeg.exe"); // load ffmpeg.exe
 // exec("ffmpeg -i ../input/drop.avi -an ../output/video.mp4"); // Convert .avi to mp4

 // echo "Mp4 Converted";
 // exec("ffmpeg -i ../input/drop.avi  ../output/mpeg/1-1.mpeg"); // Convert .avi to mpeg
 // echo "Mpeg Converted";

 // exec("ffmpeg -i ../input/drop.avi  ../output/mp4/1-1.mp4"); // Convert .avi to mp4
 // echo "Mp4 Same Name Converted";
$path = "/var/www/html/login/public/restaurants/171/items/316/5c9510f72c18b1553273079.mp4";
$path1 = "/var/www/html/login/public/restaurants/171/items/316/123.mp4";
$a = exec("ffmpeg -n -loglevel error -i ".$path." -vcodec libx264 -crf 28 -preset faster -tune film ".$path1);
//print_r($a);
// exec("ffmpeg -ss 00:00:02 -i ../input/drop.avi -vf scale=800:-1 -vframes 1 ../output/image.jpg"); // It Takes screen shot of 00:00:02 sec of video
 echo "Image Captured";
 //  exec("ffmpeg -i ../input/drop.avi -vn -ab 256 ../output/audio.mp3"); // Convert Video  to audio
  // exec("ffmpeg -i ../input/drop.avi -vf scale=500:-1 -t 10 -r 10 ../output/image.gif"); // Convert Video  to audio

 echo "Success";
?>
