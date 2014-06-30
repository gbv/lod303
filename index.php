<?php

$config = 'config.ini';
$resources = parse_ini_file($config,true);

$path   = @$_REQUEST['path'];
$format = @$_REQUEST['format']; if (!$format) $format = 'html';
$debug  = (array_key_exists('debug',$_REQUEST) and $_REQUEST['debug'] != '0');
$base   = strtok($_SERVER["REQUEST_URI"],'?');
;
$id = $path;
$id = preg_replace('/\/.*/','',$id);

if ( $res = @$resources[$id] ) {
    $location = @$res[$format] ? @$res[$format] : 'html';
}

if ($debug or !$path) { ?>
  <h1><?php echo htmlspecialchars($base); ?></h1>
  <h2>request</h2>
  <dl>
      <dt>path</dt>
      <dd><tt><?php echo htmlspecialchars($path) ?></tt></dd>
      <dt>format</dt>
      <dd><tt><?php echo htmlspecialchars($format) ?></tt></dd>
      <dt>location</dt>
      <dd><tt><?php echo htmlspecialchars($location) ?></tt></dd>
  </dl>
  <hr>
  <h2><a href="<?php print "$config\">$config"; ?></a></h2>
<?php
    foreach ($resources as $key => $value) {
      echo "<h3><a href='//".$_SERVER['SERVER_NAME']."$base$key'>$key</h3>\n<ul>\n";
      foreach ($value as $f => $url) {
        echo "<li><a href='$url'>$f</li>\n";
      }
      echo "</ul>\n";
    }
} else {
    if ($location) {
        header("HTTP/1.1 303 See Other");
        header("Location: $location");
    } else {
        header("HTTP/1.1 404 Not Found");
        echo 'Not Found';
    }
} ?>
