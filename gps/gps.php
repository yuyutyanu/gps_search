<!DOCTYPE HTML>
<html lang="ja">

<head>
	<meta charset="utf-8">
	<title>gps_search</title>
	<link href="./gps.css" rel="stylesheet">
	<style>
	#map{
		margin-top:15px;
		width:100%;
		height:550px;
		box-shadow: 10px 10px 10px ;
	}
	</style>

</head>

<body>

	<div id="Container" class="container">
		<form id="gps_input" action="#" method="post">
			<input type="file" name="name" value="">
			<input type="submit"value="GO">
		</form>
<div id="gps">
			<?php
			//exifdataを取り出す
		if(!empty($_POST["name"])){
		$img = './photos/'.$_POST["name"];
		$exif = @exif_read_data($img);

		//exifdataにgpsdataがあれば取り出す
		if(isset($exif['GPSLatitudeRef'])||isset($exif['GPSLatitude'])||isset($exif['GPSLongitudeRef'])||isset($exif['GPSLongitude'])){
		$gps_n_or_s = $exif['GPSLatitudeRef'];
		$gps_lati = $exif['GPSLatitude'];
		$gps_e_or_w = $exif['GPSLongitudeRef'];
		$gps_longi = $exif['GPSLongitude'];

//緯度
		$i=0;
		$latitude_data="";
		while($i<3){

			//gps情報を　○○/△△　から　小数点の値に変更
			$latitude = explode("/",$gps_lati[$i]);

			if($i==0){
			$data1 = $latitude[0] / $latitude[1];
		}
		if($i==1){
			$data2 = ($latitude[0] / $latitude[1])/60;
		}
		if($i==2){
			$data3 = ($latitude[0] / $latitude[1])/3600;
		}
			$i+=1;
		}
		$latitude_data = $data1+$data2+$data3;
		//方角が南なら緯度をマイナスに
		if($gps_n_or_s=='S'){
			$latitude=$latitude*-1;
		}

//経度
$j=0;
$longitude_data="";
while($j<3){
	//gps情報を　○○/△△　から　小数点の値に変更
	$longitude = explode("/",$gps_longi[$j]);

	if($j==0){
	$data1 = $longitude[0] / $longitude[1];
}
if($j==1){
	$data2 = ($longitude[0] / $longitude[1])/60;
}
if($j==2){
	$data3 = ($longitude[0] / $longitude[1])/3600;
}
	$j+=1;
}
$longitude_data = $data1+$data2+$data3;
	//方角が西なら経度をマイナスに
	if($gps_e_or_w=='W'){
		$longitude_data = $longitude*-1;
	}
}
}

//　 緯度  + 経度
if(isset($gps_n_or_s)||isset($latitude_data)||isset($gps_e_or_w)||isset($longitude_data)){
echo "GPSposition  :".$latitude_data.",".$longitude_data;
}
//緯度・経度の初期値と画像にgpsdataが入っていないときの処理
if(empty($latitude_data)||empty($longitude_data)){
	$latitude_data = 36.228397222222;
	$longitude_data =139.53346388889;
	echo '<p>画像にgps情報が入っていない、又は画像が選択されていない状態です。</p>';
}

?>
</div>
		 <div id="map"></div>
		 <script>
		 var map;
function initMap() {
  map = new google.maps.Map(document.getElementById('map'), {

    center: {lat: <?=$latitude_data?>, lng: <?=$longitude_data?>},
    zoom: 18
  });
}
</script>
<script async defer
 	src="http://maps.google.com/maps/api/js?key=AIzaSyCP_uYrL9C5iUgcoNbOuk1U-pCh9PpijbU&language=ja&callback=initMap"></script>
</script>
</body>
</html>
