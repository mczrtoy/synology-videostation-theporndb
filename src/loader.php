#!/usr/bin/php
<?php
require "search.php";

function arguments($argv) {
    $ret = array();
    for ($i = 0; $i < count($argv); $i++) {
        if (str_starts_with($argv[$i], '--')) {
            $ret[trim($argv[$i], '-')] = $argv[++$i];
        } else {
            $ret['.input'][] = $argv[$i];
        }
    }
  return $ret;
}

$parameters = arguments($argv);
if (!array_key_exists('apikey', $parameters)) {
    $parameters['apikey'] = getenv('METADATA_PLUGIN_APIKEY');
}

$input = json_decode($parameters['input']);
$result = NULL;
switch ($parameters['type']) {
    case 'tvshow':
        $result = search_site($input, $parameters['limit'], $parameters['apikey']);
        break;
    case 'tvshow_episode':
        $result = search_scene($input, $parameters['limit'], $parameters['apikey']);
        break;
    default:
        $result = search_movie($input, $parameters['limit'], $parameters['apikey']);
        break;
}

print_r(json_encode($result));
?>