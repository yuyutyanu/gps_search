<!DOCTYPE HTML>
<html lang="ja">

<head>
	<meta charset="utf-8">
	<title>gps_search</title>
  <link rel="stylesheet" href="./gps.css" media="screen" title="no title" charset="utf-8">
	<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
	<meta name=viewport content="width=device-width, initial-scale=1">
	<style>
@media(min-width:700px){
		#map{
			width:100%;
			height:506px;
			 border-top:solid 7px;
			 border-bottom: solid 7px;
		}
	}

@media(max-width:700px){
	#map{
		width:100%;
		height:376px;
		 border-top:solid 7px;
		 border-bottom: solid 7px;
	}
	@media(max-width:350px){
		#map{
			width:100%;
			height:376px;
			 border-top:solid 7px;
			 border-bottom: solid 7px;
		}
	}
}

	</style>
</head>

<body>
	<?php
	//jpg,pngファイルが送信されたら./photo/test.jpgにアップロード
	  if(isset($_FILES['file'])){
	    move_uploaded_file($_FILES['file']['tmp_name'], './photos/test.jpg');
	  }
		echo "<img src='./photos/test.jpg' id='photo'>"
	?>
	<div id="title">
		<p>&nbsp Photo</p>
		<P>Checker</p>
	</div>
<div class="sidebar">
			<?php
			//exifdataを取り出す
		if(isset($_FILES['file'])){
		$img = "./photos/test.jpg";
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
	//gps情報の書式をfloat型に変更
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
?>

</div>

<div id="gps">
  <form  id="input_gps_files" name="fileform" enctype="multipart/form-data" action="#" method="post">
    <input name="file"type="file"accept="image/jpeg,image/png">
    <input type="submit"value="送信">
  </form>

  <div id="lat_and_long">
  <?php
  //　 緯度  + 経度　を表示
  if(isset($gps_n_or_s)||isset($latitude_data)||isset($gps_e_or_w)||isset($longitude_data)){
  echo "<div id='gpsposition'>GPSposition <br> ".$latitude_data.",".$longitude_data."</div>";
  }
  //緯度・経度の初期値と画像にgpsdataが入っていないときの処理
  if(empty($latitude_data)||empty($longitude_data)){
  	$latitude_data = 36.228397222222;
  	$longitude_data =139.53346388889;
  	echo '<div id="not_photo"><p>画像にGPS情報が入っていない、<br><br>又は画像が選択されていない状態です。</p></div>';
  }
  ?>
  </div>
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
