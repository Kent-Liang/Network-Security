<?php 
  require_once("includes/common.php"); 
  nav_start_outer("Users");
  nav_start_inner();
?>
 <form name="profileform" method="GET"
  action="<?php echo $_SERVER['PHP_SELF']?>">
 <nobr>User:
 <input type="text" name="user" value="<?php 
   // Beware: Stripping slashes is equivalent 
   // to running PHP with magic_quotes_gpc off. 
   echo stripslashes($_GET['user']); 
 ?>" size=10>
 <input type="submit" value="View"></nobr>
</form>
<div id="profileheader" style="margin: 8px"><!-- user data appears here --></div>
<?php 
  $selecteduser = $_GET['user']; 
  $sql = "SELECT Profile, Username, PanCoins FROM Person " . 
         "WHERE Username='$selecteduser'";
  $rs = $db->executeQuery($sql);
  if ( $rs->next() ) { // Sanitize and display profile
    list($profile, $username, $pancoins) = $rs->getCurrentValues();
    echo "<div class=profilecontainer><b>Profile</b>";
    $allowed_tags = 
      '<a><br><b><h1><h2><h3><h4><i><img><li><ol><p><strong><table>' .
      '<tr><td><th><u><ul><em><span>';
    $profile = strip_tags($profile, $allowed_tags);
    $disallowed = 
      'javascript:|window|eval|setTimeout|setInterval|target|'.
      'onAbort|onBlur|onChange|onClick|onDblClick|'.
      'onDragDrop|onError|onFocus|onKeyDown|onKeyPress|'.
      'onKeyUp|onLoad|onMouseDown|onMouseMove|onMouseOut|'.
      'onMouseOver|onMouseUp|onMove|onReset|onResize|'.
      'onSelect|onSubmit|onUnload';
    $profile = preg_replace("/$disallowed/i", " ", $profile);
    echo "<p id=profile>$profile</p></div>";
  } else if($selecteduser) {  // user parameter present but user not found
    echo '<p class="warning" id="baduser">Cannot find that user.</p>';
  }
  $pancoins = ($pancoins > 0) ? $pancoins : 0;
  echo "<span id='pancoins' class='$pancoins'/>";	
?><script type="text/javascript">
  var coins = eval(document.getElementById('pancoins').className);
  function showPanCoins(pancoins) {
    document.getElementById("profileheader").innerHTML =
      "<?php echo $selecteduser ?>'s <b>PanCoins</b>:" + pancoins;
    if (pancoins < coins) {
      setTimeout("showPanCoins(" + (pancoins + 1) + ")", 50);
    }
  }
  if (coins > 0) showPanCoins(0);  // count up to total
</script>
<?php 
  nav_end_inner();
  nav_end_outer(); 
?>
