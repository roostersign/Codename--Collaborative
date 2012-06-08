<?php
//load the DB functions...
require_once './functions/flat.php';

$ucTitleString = ucwords($_REQUEST['table']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $ucTitleString ; ?> Flash Cards</title>
<style type="text/css">
<!--
.input { font-size: 250%; font-weight: bold; color: #0099FF; text-align: right;}
.style5 {font-size: xx-large; font-weight: bold; }
.style6 {font-size: xx-large; font-weight: bold; color: #0099FF; }
.style7 {font-size: xx-large; font-weight: bold; font-style: italic; }
.style8 {color: #00CC00}
.style9 {
	color: #FF0000;
	font-size: xx-large;
	font-weight: bold;
}
.style11 {
	color: #00CC00;
	font-size: xx-large;
	font-weight: bold;
}
.style12 {font-size: xx-large; font-weight: bold; color: #0000FF; }
.style15 {font-size: 150px; font-style: italic; font-family: Geneva, Arial, Helvetica, sans-serif; color: #9900CC; }
body {
	background-color: #f7f7f7;
}
.style16 {color: #00FF66}
.style17 {
	color: #00FFFF;
	font-size: 18px;
}
#debugContainer {
 font:small "Terminal","Lucida Console","System";
}
.style18 {
	color: #0066FF;
	font-size: 16px;
}

-->
</style>
<script type="text/javascript" src="script/soundmanager.js"></script>

<script type="text/javascript">

var formInUse = false;

function setFocus()
{
 if(!formInUse) {
  document.flash.answerF.focus();
 }
}

</script>
<script language=javascript type='text/javascript'>
function hidediv() {
if (document.getElementById) { // DOM3 = IE5, NS6
document.getElementById('hideshow').style.visibility = 'hidden';
}
else {
if (document.layers) { // Netscape 4
document.hideshow.visibility = 'hidden';
}
else { // IE 4
document.all.hideshow.style.visibility = 'hidden';
}
}
}

function showdiv() {
if (document.getElementById) { // DOM3 = IE5, NS6
document.getElementById('hideshow').style.visibility = 'visible';
}
else {
if (document.layers) { // Netscape 4
document.hideshow.visibility = 'visible';
}
else { // IE 4
document.all.hideshow.style.visibility = 'visible';
}
}
}
</script> 
</head>
  <?php
function get_percentage ( $total, $analized )
{
		return @round ( $analized / ( $total / 100 ), 2 );
}

//get the data for the current problem set.
$dbdata = read_file("./data/".$_REQUEST['table'].".db");

// get total number of problems done.
$total_num = get_insert_id("./data/".$_REQUEST['table'].".db");
$total_num = ($total_num - 1);

//Get total number of correct problems
$correct = 0;
if (isset($dbdata['correct'])) {
	foreach ($dbdata['correct'] as $value) {
		if ($value == 1) {
		$correct++;
		}
	}
}


$percent = get_percentage ($total_num , $correct );

//load the settings...
$settings = read_file("./data/settings.db");

//get the average time spent per problem
$avg_tot = 0;
if (isset($dbdata['time'])) {
	foreach ($dbdata['time'] as $value) {
		$avg_tot = $avg_tot + $value;
	}
}

if ($avg_tot != 0){
	if ($total_num != 0) {
$final_avg = ($avg_tot / $total_num);
$final_avg = round($final_avg, 2);
	}
}
  
//get the numbers, can be double digit
$random1 = rand(1,$settings['x_num'][1]);
$random2 = rand(1,$settings['y_num'][1]);

$ansR = 0;

switch($_REQUEST['table']) {
    case "addition":
        $opperand = "+";
		$ans = ($random1 + $random2);
        break;
    case "subtraction":
        $opperand = "-";
		while ($random2 > $random1) {
		$random2 = rand(1,$settings['y_num'][1]);
		}
		$ans = ($random1 - $random2);
        break;
    case "multiplication":
        $opperand = "X";
		$ans = ($random1 * $random2);
        break;
    case "division":
        $opperand = "÷";
		while ($random2 > $random1) {
		$random2 = rand(1,$settings['y_num'][1]);
		}
		$ans = ($random1 / $random2);
		$splode = explode (".", $ans);
		if (count($splode) > 1) {
		$ans = $splode[0];
		$ansR = ($random1 % $random2);
		} 
        break;
}

if (isset($_POST['answerF'])) {
$myans = $_POST['answerF']; 
} else {
$myans = "";
}

?>


<?php
if ($myans == "") {
?>
<body onload="setFocus()">
<div>
 <!-- soundManager appends "hidden" Flash to the first DIV on the page. -->
</div>
<script type="text/javascript">soundManagerInit();</script>

<div align="center">
  <form action="flash.php" method="post" enctype="application/x-www-form-urlencoded" name="flash" id="flash" target="_self">
  <input name="X" type="hidden" value="<?php echo $random1 ;?>" />
  <input name="Y" type="hidden" value="<?php echo $random2 ;?>" />
  <input name="answer" type="hidden" value="<?php echo $ans ;
  if ($_REQUEST['table'] == "division") {
		echo " R " . $ansR;
  }
  ?>" />
  
  <input name="table" type="hidden" value="<?php echo $_REQUEST['table'] ;?>" />
  
  
  <table width="800" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td bgcolor="#FFFFFF"><h1>Your Score: <?php
	//set the color
	$percent_color = "00dc1f";  //this is the default green...
	
	if ($percent < 90) {
	$percent_color = "96dc00";
	}
	if ($percent < 85) {
	$percent_color = "d9dc00";
	}
	if ($percent < 80) {
	$percent_color = "dc9b00";
	}
	if ($percent < 75) {
	$percent_color = "dc6200";
	}
	if ($percent < 70) {
	$percent_color = "dc0000";
	}
	
	$color_me = "<font color=\"#".$percent_color."\">";
	echo $color_me;
	echo $percent ;
	echo "<font color=\"#000000\">"
	?>%</h1></td>
    <td width="488" valign="top"><div align="right"><?php
    	  $arr0 = str_split($random1);
	  foreach ($arr0 as &$value) {
	   echo "<img src=\"./images/nums/".$value.".jpg\">";
	  } 
      ?>
	</div></td>
    </tr>
  <tr>
    <td valign="top" bgcolor="#000000"><div align="left"><h2 align="center" class="style16"><?php echo $correct; ?> problems correct <br />
      out of<br />
      <?php echo $total_num; ?> total problems.    </h2>
        <p align="center" class="style17"><input class="input" type="text" size="5" name="d2">
        </p>
        <?php if (isset($final_avg)) { ?>
        <p align="center" class="style17">On average it takes you <?php echo $final_avg ; ?> seconds to finish a problem.</p>
        <?php } ?>
    </div></td>
    <td valign="bottom"><div align="right">
    <span class="style15"><?php echo $opperand ; ?>&nbsp;</span>
    <?php
    	  $arr0 = str_split($random2);
	  foreach ($arr0 as &$value) {
	   echo "<img src=\"./images/nums/".$value.".jpg\">";
	  } 
      ?>
    </div><hr /></td>
    </tr>
  <tr>
    <td><div align="center"><a href="index.php">Select a different module.</a></div></td>
    <td><div align="right">
    	<?php if($_REQUEST['table'] != "division") { ?>
      <input class="input" name="answerF" type="text" id="answerF" onfocus="hidediv();" size="10"  autocomplete="off"/>
      <?php } else { 
	  			if ($ansR > 0) {
	  ?>
      Q : <input class="input" name="answerF" type="text" id="answerF" onfocus="hidediv();" size="5"  autocomplete="off"/>
      R : <input class="input" name="answerFR" type="text" id="answerFR" size="5"  autocomplete="off"/>
      <?php } else { ?>
      Q : <input class="input" name="answerF" type="text" id="answerF" onfocus="hidediv();" size="10"  autocomplete="off"/>
      <input class="input" name="answerFR" type="hidden" value="0" />
      <?php } ?>
      <?php } ?>
    </div></td>
    </tr>
    <tr><td colspan="3"><div align="center">
      <input class="input" type="submit" name="submit2" id="button" value="Submit" />
    </div></td></tr>
</table>
  </form>
        <script> 
<!-- 
// 
 var milisec=0 
 var seconds=<?php echo $settings['timer'][1] ;?> 
 document.flash.d2.value='<?php echo $settings['timer'][1] ;?>' 

function display(){ 
 if (milisec<=0){ 
    milisec=9 
    seconds-=1 
 } 
 if (seconds<=-1){ 
    milisec=0 
    seconds+=1 
 } 
 else 
    milisec-=1 
    document.flash.d2.value=seconds+"."+milisec 
    setTimeout("display()",100)
	if (seconds==0 && milisec==0) {
    document.flash.answerF.value="X" 
	 document.getElementById("flash").submit()
	}
	if (seconds<=5 && milisec==1 && seconds>0) {
	soundManager.play('beep')
	}
  } 
  
  
display() 
--> 
</script> 
  
  
</div>
<?php
} else {
?>

<body onload="hidediv()">

<div>
 <!-- soundManager appends "hidden" Flash to the first DIV on the page. -->
</div>
<script type="text/javascript">soundManagerInit();</script>

<div align="center">
<?php
$sound = "";
$time_left = $_POST['d2'];

$used_time = $settings['timer'][1] - $time_left;
if ($time_left == "0.0") {
$sound = "smash2";
echo "<h1>You ran out of time!</h1>";
}
?>

  <form action="flash.php" method="post" enctype="application/x-www-form-urlencoded" name="flashA" target="_self">
    <input name="table" type="hidden" value="<?php echo $_REQUEST['table'] ;?>" />

  <?php
  $prob = $_POST['X'] . " ".$opperand." " . $_POST['Y'];
  if ($_REQUEST['table'] == "division") {
  $_POST['answerF'] = $_POST['answerF'] . " R " . $_POST['answerFR'];
  }
  if ($_POST['answerF'] == $_POST['answer']){
    $sound = "win";
	$data = "1|".$prob."|".$_POST['answerF']."|NULL|".$used_time;
  
  ?>
  
  <?php
  $output_correct = array("Great job", "Keep up the good work", "Nice try", "You go girl!");
  $rand_num = rand(0,3);
  ?>
  
  <table width="400" border="1" cellspacing="2" cellpadding="2">
  <tr>
    <td bgcolor="#000000"><div align="center"><img src="images/frwrks.gif" alt="Yay!" width="72" height="54" /></div></td>
    <td><div align="center"><span class="style11"><?php echo $output_correct["$rand_num"];///?></span></div></td>
    <td bgcolor="#000000"><div align="center"><img src="images/frwrks.gif" alt="Yay!" width="72" height="54" /></div></td>
  </tr>
</table>
   
  <?php
  } else {
  if ($sound == "") {
  $sound = "lose";
  }
  $data = "0|".$prob."|".$_POST['answerF']."|".$_POST['answer']."|".$used_time;
  ?>
    <table width="400" border="1" cellspacing="2" cellpadding="2">
  <tr>
    <td bgcolor="#000000"><div align="center"><img src="images/Sad03.gif" alt="Yay!" width="72" height="54" /></div></td>
    <td><div align="center"><span class="style9">Incorrect, try again!</span></div></td>
    <td bgcolor="#000000"><div align="center"><img src="images/Sad03.gif" alt="Yay!" width="72" height="54" /></div></td>
  </tr>
</table>

  
  <?php
  }
  //write to the db file
  append_file("./data/".$_REQUEST['table'].".db", $data);
  ?>
  
<script type="text/javascript">
<!-- 
// 
 var milisec=0 
 var seconds=2 

  function display(){ 
 if (milisec<=0){ 
    milisec=9 
    seconds-=1 
 } 
 if (seconds<=-1){ 
    milisec=0 
    seconds+=1 
 } 
 else 
    milisec-=1 
    setTimeout("display()",100)
	
	<?php
	if ($_SERVER['SERVER_NAME'] == "localhost") {
	?>
	if (seconds==1 && milisec==5) {
	<?php }	else { ?>
	if (seconds==0 && milisec==7) {
	<?php } ?>
	soundManager.play('<?php echo $sound ; ?>')
	}
  } 
  
  
display() 
--> 
</script> 

<script type="text/javascript">


</script>

  <table width="400" border="0" cellspacing="2" cellpadding="2">
    <tr>
      <td width="142"><div align="center" class="style6"><?php echo $_POST['X'] ;?></div></td>
      <td width="119"><div align="center" class="style7"><?php echo $opperand ;?></div></td>
      <td width="183"><div align="center" class="style6"><?php echo $_POST['Y'] ;?></div></td>
      <td width="136"><div align="center" class="style5">=</div></td>
      <td width="188"><div align="center" class="style12"><?php echo $_POST['answer']?></div></td>
    </tr>
    <tr>
      <td colspan="5"><div align="center" class="style5">You answered: <span class="style8"><?php echo $_POST['answerF'];?></span> </div></td>
      </tr>
    <tr>
      <td colspan="5"><div align="center">
      
        <input class="input" type="submit" name="next" id="next" value="NEXT" />
<!---->
<form action="submit_scores.php">
<input type="hidden" value="<?php echo $percent;?>"></input>
<input type="hidden" value="<?php echo $final_avg;?>"></input>
<p style="font-size: 12; font-weight: normal; color: black;">Submit Scores:<input name="submit scores" type="submit"></input></p>
</form>


<script type="text/javascript">
 document.getElementById("next").focus()
</script>
      </div></td>
      </tr>
  </table>
  </form>
</div>
<?php
}
?>
<hr /><br /><br />

<br /><br />

<a href="javascript:showdiv()">Show</a> <a href="javascript:hidediv()">Hide</a> 
<div id="hideshow"> 
<?php
$dbdata = read_file("./data/".$_REQUEST['table'].".db");

if (isset($dbdata['correct'])) {
	$num = 1;
	$counter = count($dbdata['id']);
	while ($num <= $counter) {
		if ($dbdata['correct'][$num] == 0) {
			echo "<font color=\"#FF0000\">" . $dbdata['problem'][$num] . " = " . $dbdata['your_ans'][$num] . "</font> The right answer is : <font color =\"#00CC66\">".$dbdata['right_ans'][$num]."<br></font>";
		}
		$num++;
	}
}
?>
<hr />
Program Settings:
<form action="update_settings.php" method="post" enctype="application/x-www-form-urlencoded" name="update" target="_self">
<input name="table" type="hidden" value="<?php echo $_REQUEST['table'] ;?>" />
  <p>&nbsp;</p>
  <div align="center">
    <table width="400" border="0" cellspacing="2" cellpadding="2">
        <tr>
          <td><div align="center">
            <input name="x_num" type="text" value="<?php echo $settings['x_num'][1] ;?>" size="4" />
          </div></td>
          <td><div align="center">
            <input name="y_num" type="text" id="y_num" value="<?php echo $settings['y_num'][1] ;?>" size="4" />
          </div></td>
          <td><div align="center">
            <input name="seconds" type="text" id="seconds" value="<?php echo $settings['timer'][1] ;?>" size="5" />
          </div></td>
        </tr>
        <tr>
          <td><div align="center">Maximum value of first digit</div></td>
          <td><div align="center">Maximum value of second digit</div></td>
          <td><div align="center">Timer length in seconds</div></td>
        </tr>
        <tr>
          <td colspan="3"><div align="center">
            <input type="submit" name="submitsettings" id="submitsettings" value="Submit" />
          </div></td>
        </tr>
      </table>
  </div>
  <p align="center">&nbsp;  </p>
</form>

<form action="reset_flash.php" method="post" enctype="application/x-www-form-urlencoded" name="reset" target="_self">
<input name="table" type="hidden" value="<?php echo $_REQUEST['table'] ;?>" />
  <div align="center">
    <table width="400" border="0" cellspacing="2" cellpadding="2">
        <tr>
          <td><div align="center">
            <input name="submit" type="submit" value="Reset Scores" />
          </div></td>
          <td>&nbsp;</td>
        </tr>
      </table>
  </div>
</form>
</div>
</body>
</html>
