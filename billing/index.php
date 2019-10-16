<?php  
    error_reporting(0);
    if(isset($_POST['submit'])){
        
   
    $img = $_POST['image'];
    $folderPath = "upload/";
  
    $image_parts = explode(";base64,", $img);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
  
    $image_base64 = base64_decode($image_parts[1]);
    $fileName = uniqid() . '.jpg';
  
    $file = $folderPath . $fileName;
    file_put_contents($file, $image_base64);
  
   # print_r(gettype($fileName));
    $p="activate tensorflow1 & cd C:\\tensorflow1\\models\\research\\object_detection & python Object_detection_image.py C:\\xampp\\htdocs\\billing\\upload\\".$fileName;
	$out=shell_exec($p);
	//echo $out;
    $nout = explode("],",$out);
    
    $pnames = explode(",",trim($nout[0],"[]()"));
    $pareas = explode(",",trim($nout[1],"[]()"));
    //print_r($pnames);
    $pareas[0]=substr($pareas[0], 2);
    $l=sizeof($pareas)-1;
    $pareas[$l]=substr($pareas[$l], 0,-3);
    $pnamef=array();
        
    
    
        //echo (explode(",",$nout[0]))[0];
    $i=0;
    $pnames[0]=substr($pnames[0], 1, -1);
    for($i=1;$i<=$l;$i++){
        $pnames[$i]= substr($pnames[$i], 2, -1);
    }
    for($i=0;$i<=$l;$i++){
        if ($pnames[$i]=="dettol"){
            if((float)$pareas[$i]<0.105){
                $pnamef[$i]='Dettol_small';
            }else{$pnamef[$i]='Dettol_large';}
        }
        else if ($pnames[$i]=="rin"){
            if((float)$pareas[$i]<0.13){
                $pnamef[$i]='Rin_small';
            }else{$pnamef[$i]='Rin_large';}
        }
        else if ($pnames[$i]=="hideandseek"){
            if((float)$pareas[$i]<0.18){
                $pnamef[$i]='Hideandseek_small';
            }else{$pnamef[$i]='Hideandseek_large';}
        }
        else if ($pnames[$i]=="colgate"){
            if((float)$pareas[$i]<0.1){
                $pnamef[$i]='Colgate_small';
            }else{$pnamef[$i]='Colgate_large';}
        }
        else $pnamef[$i]= $pnames[$i];
        
    }
    //print_r($pnamef);
    $puc = array_count_values($pnamef);
    $pu = array_unique($pnamef);
    $costs=array();
    $fcosts=array();
    $conn = new mysqli('localhost', 'root', '', 'test');
    $sql = "SELECT pid,pname,cost FROM products";
    /*
    
    $c=0;
    foreach($pu as $value){
        echo "<br>";
        $result = $conn->query($sql." WHERE pname=".'"'.$value.'"');
        //echo ($sql." WHERE pname=".'"'.$value.'"');
        $row = $result->fetch_assoc();
        $fcosts[$c]=$puc[$value]*$row["cost"];
        echo "pid ".$row["pid"]." name: $value ".$puc[$value]." ".$row["cost"]." final cost".$fcosts[$c]."<br>";
        
        $c++;
    }*/
        
   }

?>
<html>
<head><title> Kirana Product Billing</title>
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
  
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
        <script src="jquery.min.js"></script>
    <script src="webcam.min.js"></script>
</head>
<body background="images.jpg">
<style>
    video{ padding-left: 100px; max-width: 500px;max-height: 450px;}
        img{ max-width: 500px;max-height: 450px;}
h1{
	margin-top:100px;
	font-size:75px;
	font-color:black;
	}
body{
	background-repeat:no-repeat;
	
	background-size:cover;
	
	}
table,th,td{
	border:1px solid black;
	border-spacing:0px;
	margin-top:20px;
	}

</style>
<h1><center><b><i>Kirana Product Billing</b></i></center></h1>
    <form method="POST" action="index.php">
        <div class="row">
            <div class="col-md-6">
                <div id="my_camera" style="max-height:370px;z-index:99"></div>
                <br/>
                
                <input type="hidden" name="image" class="image-tag">
            </div>
            <div class="col-md-6" style="margin-top:70px">
                <div id="results">Your captured image will appear here...</div>
            </div>
        </div>

            <div class="col-md-12 text-center">
                <br/>
                <input type=button class="btn btn-rose" value="Capture" onClick="take_snapshot()">
                <input type=submit class="btn btn-success" name="submit" value="Validate">
            </div>
    </form>
    <script language="JavaScript">
   Webcam.set({
      width: 1280,
     height: 720,
     dest_width: 1280,
     dest_height: 720,
     image_format: 'jpeg',
     jpeg_quality: 100,
     force_flash: false
    });
    Webcam.attach( '#my_camera' );
  
    function take_snapshot() {
        Webcam.snap( function(data_uri) {
            $(".image-tag").val(data_uri);
            document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
            
        } );
    }
</script>
<?php
      if(isset($_POST['submit'])){
          echo "<div class=table-responsive><center><br><br>
<table style=width:75% class=table table-striped>
<tr class=success> <th colspan=6><center>Cost Of Purchase</center></th></tr>
<tr> <th>Sr.No.</th>
	<th class=text-center>Product ID</th>
	<th class=text-center> Name of the product</th>
	<th class=text-center>Quantity</th>
	<th class=text-center>Cost of product</th>
	<th class=text-center>Final cost of product</th>
	
</tr>";
$c=0;
    foreach($pu as $value){
        
        echo "<tr class='text-center'> <td>".((int)($c)+1).".</td>";
	$result = $conn->query($sql." WHERE pname=".'"'.$value.'"');
        //echo ($sql." WHERE pname=".'"'.$value.'"');
        $row = $result->fetch_assoc();
        $fcosts[$c]=$puc[$value]*$row["cost"];
        echo "<td class=text-center>".$row["pid"]."</td><td class=text-center>";
        echo $value;
        echo "</td><td class=text-center>".$puc[$value]."</td><td class=text-center>".$row["cost"]."</td><td class=text-center>".$fcosts[$c]."</td>";
        
        $c++;
    
    echo "</tr>";
    }
    echo "<tr><td class=text-center colspan=6 style=padding-left:70% color=red><b>Total: ".array_sum($fcosts)."/-</b></td></tr></table></div>";
    
      }

?>
<h4><center>Thank You.<br>Please visit again!!!</center></h4>
</body>
</html>