<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<style>
* {
  box-sizing: border-box;
}

body {
  background-color: #ffffff;
  font-family: Arial;
  margin:0px;
}

/* Center website */
.main {
  max-width: 1000px;
  margin: auto;
}

h1 {
  font-size: 50px;
  word-break: break-all;
}


/* Create three equal columns that floats next to each other */
.column {
  float: left;
  width: 30.33%;
  display: none; /* Hide all elements by default */
  margin: 0 15px 30px 15px;

}

/* Clear floats after rows */ 
.row:after {
  content: "";
  display: table;
  clear: both;
}

/* Content */
.content {
  background-color: #F8F9FA;
  padding: 0px;
  border-radius: 6px;
}
.content h4{
  padding: 2px 5px;
    margin: 0;
    font-size: 18px;
    line-height: 2;
}
.content p{
  padding: 2px 5px;
  margin: 0;
  font-size: 15px;
    line-height: 1.2;
}
.content a{
  color: #4a4a4a;
    text-decoration: none;
}
/* The "show" class is added to the filtered elements */
.show {
  display: block;
}
#myBtnContainer{
  padding: 0 18%;
  background: #F8F9FA;
  position: fixed;
    width: 100%;
}

/* Style the buttons */
.btn {
  border: none;
  outline: none;
  background: #fff0;
  padding: 12px 30px;
  cursor: pointer;
}

.btn:hover {
  background-color: #ddd;
}

.btn.active {
  background-color: #CD0D2D;
  color: white;
}
.resturanrt-logo{
  padding: 25px 0;
  z-index: 99;
}
.cats-sec{
  margin-top: 5%;
}
.menu-sec{
margin-top: 8%;
    margin-bottom: 6%;
}
.resturanrt-logo2 {
    height: 112px;
    background: #fff;
    position: fixed;
    top: 0;
    left: 0%;
    z-index: 99999;
    width: 100%;
    text-align: center;
}
@media only screen and (max-width: 1024px){
  .resturanrt-logo2 {
    height: 154px;
    background: #fff;
    position: fixed;
    top: 0;
    left: 0%;
    z-index: 99999;
    width: 100%;
    text-align: center;
}
  .column {
  float: left;
  width: 30%;
  display: none; /* Hide all elements by default */
  margin: 0 14px 30px 11px;
}
.main{
margin: 80px auto !important;
    max-width: 90%;
} 
.btn {
    border: none;
    outline: none;
    background: #fff0;
    padding: 15px 30px;
    cursor: pointer;
}
#myBtnContainer {
    padding: 0 20%;
    background: #F8F9FA;
    position: fixed;
    width: 100%;
}
.menu-sec {
    margin-top: 15%;
    margin-bottom: 6%;
}
}
@media only screen and (max-width: 768px){
  .resturanrt-logo2 {
    height: 137px;
    background: #fff;
    position: fixed;
    top: 0;
    left: 0%;
    z-index: 99999;
    width: 100%;
    text-align: center;
}
  .column {
  float: left;
  width: 30%;
  display: none; /* Hide all elements by default */
  margin: 0 14px 30px 11px;
}
.main{
margin: 80px 15px !important;
    max-width: 100%;
} 
.btn {
    border: none;
    outline: none;
    background: #fff0;
    padding: 12px 15px;
    cursor: pointer;
}
#myBtnContainer {
    padding: 0 20%;
    background: #F8F9FA;
    position: fixed;
    width: 100%;
}
.menu-sec {
    margin-top: 18%;
    margin-bottom: 6%;
}
}
@media only screen and (max-width: 480px){
  .column {
  float: left;
  width: 44%;
  display: none; /* Hide all elements by default */
  margin: 0 7px 30px 11px;
}
.main{
margin: 80px 15px !important;
    max-width: 100%;
} 
.btn {
    border: none;
    outline: none;
    background: #fff0;
    padding: 12px 15px;
    cursor: pointer;
}
#myBtnContainer {
    padding: 0 1%;
    background: #F8F9FA;
    position: fixed;
    width: 100%;
}
.menu-sec {
    margin-top: 29%;
    margin-bottom: 6%;
}
}
@media only screen and (max-width: 340px){
.menu-sec {
    margin-top: 35%;
    margin-bottom: 6%;
}
.main {
    margin: 65px 15px !important;
    max-width: 100%;
}
}
</style>
</head>
<body>
  <!-- MAIN (Center website) -->

  <section>
  <div class="container-fluid">
    <div class="row">
      <div class="resturanrt-logo2">
      <div class="col-md-12">
       <img src="<?php echo "https://www.cherrymenu.com/login/public/restaurants/".$restid."/".$rest_image;?>" class="resturanrt-logo" style="width: 100px;height: 130px;">
     </div>
      </div>
    </div>
  </div>
</section>

<?php  
foreach ($item_data as $key => $value) {
	   $catname=$value['name'];  
	  $catnames[]=$value['name'];  
	foreach ($value as $key1 => $value2) {
		 
  if(is_array($value2)){
		foreach ($value2 as $key => $value3) {
		 	 $imgname= $value3['thumbnail'];//echo "<br>"; 
			//$imgname= str_replace(".","_thumb.",$value3['image']);//echo "<br>";
			$item_id=$value3['id'];
			$url="https://www.cherrymenu.com/login/public/restaurants/".$restid."/items/"."$item_id"."/thumbnail/".$imgname;
	  "<img src='$url' style='width:100px;height:100px;'>";		
		}
	} 
	}
}
?>
<section class="menu-sec">
  <div class="container-fluid">
    <div class="row">
<div id="myBtnContainer">
  <button class="btn active" onclick="filterSelection('all')"> All</button>
  <?php foreach($catnames as $value){?>
  <button class="btn" onclick="filterSelection('<?php echo $value;?>')"><?php echo $value;?></button>
<?php }?> 
</div>
</div>
</div>
</section>
<div class="main">
<!-- Portfolio Gallery Grid -->
<div class="row">
	<?php 
foreach ($item_data as $key => $value) {
	   $catname=$value['name']; 
	foreach ($value as $key1 => $value2) {
	 if(is_array($value2)){
		foreach ($value2 as $key => $value3) {
			$imgname= $value3['thumbnail'];
			//$imgname= str_replace(".","_thumb.",$value3['thumbnail']); 
			$item_id=$value3['id'];
			$cat_id=$value3['category_id'];
			$url="https://www.cherrymenu.com/login/public/restaurants/".$restid."/items/"."$item_id"."/thumbnail/".$imgname;
            $aurl="https://www.cherrymenu.com/login/item_detail?cid=".$cat_id."&itid=".$item_id."&rid=".$restid;
 	        // $aurl='';
 	 ?>
  <div class="column <?php echo $catname;?>">
    <div class="content">
      <a target="_blank" href="<?php echo $aurl;?>">
      <img src="<?php echo $url;?>" alt="image not available" style="width:100%">
      <p><?php echo $value3['title'];?></p>
      <h4>AED <?php echo $value3['price'];?></h4>
    </a>
    </div>
  </div>
<?php 
} } }}
?>
  
<!-- END GRID -->
</div>

<!-- END MAIN -->
</div>

<script>
filterSelection("all")
function filterSelection(c) {
  var x, i;
  x = document.getElementsByClassName("column");
  if (c == "all") c = "";
  for (i = 0; i < x.length; i++) {
    w3RemoveClass(x[i], "show");
    if (x[i].className.indexOf(c) > -1) w3AddClass(x[i], "show");
  }
}

function w3AddClass(element, name) {
  var i, arr1, arr2;
  arr1 = element.className.split(" ");
  arr2 = name.split(" ");
  for (i = 0; i < arr2.length; i++) {
    if (arr1.indexOf(arr2[i]) == -1) {element.className += " " + arr2[i];}
  }
}

function w3RemoveClass(element, name) {
  var i, arr1, arr2;
  arr1 = element.className.split(" ");
  arr2 = name.split(" ");
  for (i = 0; i < arr2.length; i++) {
    while (arr1.indexOf(arr2[i]) > -1) {
      arr1.splice(arr1.indexOf(arr2[i]), 1);     
    }
  }
  element.className = arr1.join(" ");
}


// Add active class to the current button (highlight it)
var btnContainer = document.getElementById("myBtnContainer");
var btns = btnContainer.getElementsByClassName("btn");
for (var i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", function(){
    var current = document.getElementsByClassName("active");
    current[0].className = current[0].className.replace(" active", "");
    this.className += " active";
  });
}
</script>
</body>
</body>
</html> 
