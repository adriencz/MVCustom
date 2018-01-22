<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>MVCustomError</title>
    <link rel="stylesheet" href="<?php echo WEBROOT; ?>/system/errors/error.css">
  </head>
  <body>

<p>
  <span>MVCustom Error(s) :</span>
  <br><br>

<?php

if (is_array($error))
{
  foreach ($error as $err) {
    echo "- {$err} <br>";
  }
}else {
  echo "- {$error}";
}
?>

</p>


  </body>
</html>
