<?
// SMS text wall - Sample for the 46elks cloud telephony platform
//
// Need a way for customers to compliment the kitchen?
// Maybe your sales team want report back sales on the go?
//


// 1) Be sure you web server has write access to current directory
// 2) Change this key to something unique
$key = 'uzh789j123';
// 3) Configure sms_url to: http://myserver.se/textwall.php?key=uzh789j123
// 4) Change HTML layout at the end to your liking


// One file for each month. You can change this to daily if you want.
$filename = date('Ym') .'.txt';

/////////////////////////
// Handle incoming SMS //
/////////////////////////
if (isset($_POST['from'])) {
  if (!isset($_GET['key']) || $_GET['key'] != $key) die;

  // Write a line to 201107.txt for each incoming SMS
  $line = $_POST['from'] .' '. base64_encode($_POST['message'])."\n";
  file_put_contents( $filename, $line, FILE_APPEND );

  die;
}

///////////////////////////////
// Load current message file //
///////////////////////////////
$lines = explode("\n", file_get_contents($filename) );
array_pop( $lines );
$lines = array_reverse( $lines );


///////////////////////////////////////
// Map phone numbers to people names //
///////////////////////////////////////
$peoplenames = array(
  '+46704508449' => 'Johannes L',
  '+46701234124' => 'Mike Douglas'
);

?>
<html>
<head>
  <title>Text wall sample using 46elks</title>
  <style type="text/css">
p,h1 {
  font-family: Verdana;
  text-align: center;
  width: 95%;
}
p {
  padding: 10px 20px 10px 20px;
  font-size: 20px;
}
  </style>
  <meta http-equiv="refresh" content="5">
</head>
<body>

  <h1>SMS wall for +46766861354</h1>

  <?
    foreach (array_slice($lines, 0, 20) as $line):
      list ($from, $text) = explode(' ', $line);
      $message = htmlspecialchars( base64_decode($text) );

      if (isset($peoplenames[$from]))
        $from = $peoplenames[$from];
      else
        $from = 'Phone'. substr($from,-4);
  ?>

    <p><?=$message?> <em><?=$from?></em></p>

  <?
    endforeach;
  ?>

</body>
</html>
