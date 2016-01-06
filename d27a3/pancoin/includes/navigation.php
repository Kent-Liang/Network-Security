<?php 
  function nav_start_outer($page_title = null) {
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3. org/TR/html4/loose.dtd">
<html>
<head>
<link rel="stylesheet" type="text/css" href="pancoin.css">
<title><?php echo "$page_title - " ?>PanCoin Digital Currency
</title>
</head>
<div id="header">
<div>CSCD27 Assignment 3</div>
<div><a href="?action=logout"><?php 
  global $user;
  if($user->id) 
    echo "Log out " . htmlspecialchars($user->username); 
?></a></div>
</div>  <!-- header -->
<br/>
<?php
  // Pick a random title for the page.  May be funny for about 3 seconds.
  srand();
  $adjectives = array( "Safe", "Secure", "Top Notch", "Trustworthy",
                       "Sustainable", "Ethical", "Coveted","Shiny","Top Drawer",
		       "Quality", "Awesome", "Green", "Sophisticated" );
  $nouns = array( "Barter", "Transactions", "Services", "Swag","Booty",
                  "Gifts", "Souvenirs", "Mementos", "Prizes" );
  $adverbs = array( "greatest", "brightest", "friendliest", "most-gullible",
		    "most-energized", "fastest-rising", "most-indebted",
		    "best-governed", "most self-centred" );
  $pluralnouns = array ( "city", "games", "athletes", "spectators", "stars",
                         "organizers", "events", "parathletes", "visionaries" );
  $concepts = array( "hemisphere", "globe", "continent", "planet", "world",
			"galaxy", "universe" );
  $adjective = $adjectives[array_rand($adjectives)];
  $noun = $nouns[array_rand($nouns)];
  $adverb = $adverbs[array_rand($adverbs)];
  $pluralnoun = $pluralnouns[array_rand($pluralnouns)];
  $concept = $concepts[array_rand($concepts)];
  echo "<h1><a href='index.php'>PanCoin Exchange for " .
       "$adjective $noun</a></h1>";
  echo "<h2>Supporting the $adverb $pluralnoun in the $concept</h2>";

} function nav_start_inner() { ?>

<div id="main" class="centerpiece">
<table>
<tr><td>
<p><?php
// Output a pipe-delimited list of available pages
$pages = array( "Home" => "index.php", 
                "Users" => "users.php", 
                "Transfer" => "transfer.php" );
foreach($pages as $name => $page) {
  $script = $_SERVER['SCRIPT_NAME'];
  if(strpos($script, $page, strlen($script) - strlen($page)) === false) {
    echo "<a href=$page>$name</a>";
  } else {
    echo "<b>$name</b>";
  }
  if($name != "Transfer") echo " | ";
}
?></p>
</td></tr>
<tr><td class=main>

<?php } function nav_end_inner() { ?>

</td></tr>
</table>
</div>

<?php } function nav_end_outer() { ?>

</body>
</html>

<?php } ?>
