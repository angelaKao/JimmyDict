<?php
echo <<< EOD
<html>
<head>
  <title>Dictionary</title>
  <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
 
  
     
</head>

<body onLoad="LoadAlert()">


<div class="navbar navbar-inverse navbar-fixed-top">
  <div class="navbar-inner">
    <div class="container">
      <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <a class="brand" href="#">Jimmys Site</a>
      <div class="nav-collapse collapse">
         <ul class="nav">
           <li class="active"><a href="#">Dictionary</a></li>
           <li><a href="#about">About</a></li>
           <li><a href="#contact">Contact</a></li>
         </ul>
      </div><!--/.nav-collapse -->
    </div>
  </div>
</div>

<div class="container">
    <div class = "hero-unit">
EOD;

include "db_conn.php";
// ==================================Searching main page==============================
if ($_SERVER['REQUEST_METHOD']=="GET")
{
 echo <<<EOD
    
    <form action="$_SERVER[SCRIPT_NAME]" method="post">
    <div><big>Jimmy's Dictionary</big></div>
      <input align="center" type="text" name="find" width="60px"/>
      <input type="submit" value="submit"/>
    </form>
    
EOD;
 } 
else 
{
  echo "<div id='main'>";
  $word=$_POST['find'];
  //=================database search=======================
    $sql="select count from record where word = '$word'";
    $result= mysql_query($sql) ;
    $rows = mysql_fetch_array($result);
    //tell the times you search
    if ($rows!= NULL) {
        $num = $rows['count'] + 1;
        $comm = "UPDATE record set count ='$num' where word = '$word'";
        echo <<<EOD
        <SCRIPT language = "JavaScript">
            LoadAlert(){
                alert("You have been search this word "+ $num +"times\nPlease Try to remember it!!");
            }
        </SCRIPT>
EOD;
        
    } else {
        $comm = "insert into record values (default,'$word',1)";
       echo <<< EOD
        <SCRIPT language = "Javascript">
            LoadAlert(){
                alert("This is the first time you search this word!");
            }
        </SCRIPT>
EOD;
    }
    
    $update = mysql_query($comm);
    
    
    
  //===============Cambridge====================================================
  $ch2 = curl_init();
  curl_setopt($ch2, CURLOPT_RETURNTRANSFER,true);
  curl_setopt($ch2, CURLOPT_URL,"dictionary.cambridge.org/dictionary/american-english/".$word."?q=".$word);
  curl_setopt($ch2, CURLOPT_HEADER,false);
  $return= curl_exec($ch2);
  //echo $return;

  $findStWd1="<div class=\"di-head\">";
  $startLoc1= stripos($return,$findStWd1);
  //echo $s1."<br>";
  $findEndWd1="<!-- End of DIV di-head-->";
  $endLoc1= stripos($return,$findEndWd1);
  //echo  $e1;

  $strLens= $endLoc1-$startLoc1;
  $cutStr= substr ($return,$startLoc1,$strLens);
  echo "<table aglin=\"center\" border=\"1\" ><br><tr><td>Cambridge</td></tr>";
  echo "<tr><td>".$cutStr."</tr></td>";

  $w3="<div class=\"gwblock_b \">";
  $s3= stripos($return,$w3);
  echo $s3."<br>";

  //yahoo ==========================================================
  echo "<tr><td>Yahoo</td></tr>";

  $ch= curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch,CURLOPT_URL,"hk.dictionary.yahoo.com/dictionary?p=".$word);
  curl_setopt($ch, CURLOPT_HEADER, false);
  $return_str = curl_exec($ch);

  //find the begin pos;
  $findStWd2= "<div id=\"summary-card\">";
  $startPos =  stripos($return_str,$findStWd2);
  //echo $start."<br>";

  //find the end pos
  $findEndWd2= "<div id=\"ad-be\">";
  $endPos = stripos($return_str,$findEndWd2);

  $strLens2=$endPos-$startPos;
  $cutStr2 = substr($return_str,$startPos,$strLens2);
  echo "<tr><td>".$cutStr2."</td></tr><br></table></div>"; 
    
}
echo
"</div></div> </body></html>";
?>
