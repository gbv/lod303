<?php

$config = 'config.ini';
$resources = parse_ini_file($config,true);

$path   = @$_REQUEST['path'];
$format = @$_REQUEST['format']; if (!$format) $format = 'html';
$debug  = (array_key_exists('debug',$_REQUEST) and $_REQUEST['debug'] != '0');

$id = $path;
$id = preg_replace('/\/.+/','',$id);

if ( $res = @$resources[$id] ) {
    $location = @$res[$format] ? @$res[$format] : 'html';
}

if ($debug) { ?>
  <dl>
      <dt>path</dt>
      <dd><tt><?php echo htmlspecialchars($path) ?></tt></dd>
      <dt>format</dt>
      <dd><tt><?php echo htmlspecialchars($format) ?></tt></dd>
      <dt>location</dt>
      <dd><tt><?php echo htmlspecialchars($location) ?></tt></dd>
  </dl>
  <hr>
  <b><a href="<?php print "$config\">$config"; ?></a></b>
  <pre><?php
    array_walk_recursive($resources, function(&$v) { 
        $v = htmlspecialchars($v); 
    });
    print_r($resources);
  ?><pre><?php 
} else {
    if ($location) {
        header("HTTP/1.1 303 See Other");
        header("Location: $location");
    } else {
        header("HTTP/1.1 404 Not Found");
        echo 'Not Found';
    }
} ?>
