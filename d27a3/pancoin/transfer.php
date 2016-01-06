<?php 
  require_once("includes/common.php"); 
  nav_start_outer("Transfer");
  nav_start_inner();
  if($_POST['submission']) {
    $recipient = $_POST['recipient'];
    $pancoins = (int) $_POST['pancoins'];
    $sql = "SELECT PanCoins FROM Person WHERE PersonID=$user->id";
    $rs = $db->executeQuery($sql);
    $sender_balance = $rs->getValueByNr(0,0) - $pancoins;
    $sql = "SELECT PersonID FROM Person WHERE Username='$recipient'";
    $rs = $db->executeQuery($sql);
    $recipient_exists = $rs->getValueByNr(0,0);
    if($pancoins > 0 && $sender_balance >= 0 && $recipient_exists) {
      $sql = "UPDATE Person SET PanCoins = $sender_balance " .
             "WHERE PersonID=$user->id";
      $db->executeQuery($sql);
      $sql = "SELECT PanCoins FROM Person WHERE Username='$recipient'";
      $rs = $db->executeQuery($sql);
      $recipient_balance = $rs->getValueByNr(0,0) + $pancoins;
      $sql = "UPDATE Person SET PanCoins = $recipient_balance " .
             "WHERE Username='$recipient'";
      $db->executeQuery($sql);
      $result = "Sent $pancoins pancoins";
    }
    else $result = "Transfer to $recipient failed.";
  }
?>
<p><strong>Balance:</strong>
<span id="myPanCoins"></span> PanCoins</p>
<form method=POST name=transferform
  action="<?php echo $_SERVER['PHP_SELF']?>">
<p>Send <input name=pancoins type=text value="<?php 
  echo $_POST['pancoins']; 
?>" size=4> PanCoins</p>
<p><span style="color:white">To</span> <input name=recipient type=text size=15
value="<?php 
  echo $_POST['recipient']; 
?>"></p>
<input type=submit name=submission value="Send">
</form>
<br/>
<span style="border:solid 1px white;padding: 1px; color:yellow"><?php 
  echo $result; 
?></span>
<?php 
  nav_end_inner();
?>
<script type="text/javascript" src="pancoin.js.php"></script>
<?php
  nav_end_outer(); 
?>
