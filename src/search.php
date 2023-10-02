<?php
define('PLUGIN_USER_AGENT', 'io.github.mczrtoy.ThePornDB');

const API_URL_MOVIES = 'https://api.metadataapi.net/movies?';
const API_URL_SCENES = 'https://api.metadataapi.net/scenes?';
const API_URL_SITES = 'https://api.metadataapi.net/sites?';

function search_movie($input, $limit)
{
    $movieMapper = function($r) {
        $actorMapper = function($p) {
            return $p->name;
        };
        $genreMapper = function($t) {
            return $t->name;
        };

        $ret = new stdClass;
        $ret->title = $r->title;
        $ret->summary = $r->description;
        $ret->original_available = $r->date;
        $ret->genre = array_map($genreMapper, $r->tags);
        $ret->actor = array_map($actorMapper, $r->performers);
        $ret->directors = array();
        $ret->writer = array();

        $extra = new stdClass;
        $extra->backdrop = array_values(get_object_vars($r->background));
        $extra->poster = array($r->poster);

        $ret->extra = new stdClass;
        $ret->extra->{'io.github.mczrtoy.ThePornDB'} = $extra;
        return $ret;
    };

    $curl = curl_init();
    $url = API_URL_MOVIES.http_build_query(array(
            'parse' => trim($input->title),
            'page' => 1,
            'per_page' => max(10, $limit)
    ));
    curl_setopt_array($curl, array(
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Authorization: Bearer '.getenv('METADATA_PLUGIN_APIKEY')
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_URL => $url,
        CURLOPT_USERAGENT => PLUGIN_USER_AGENT,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
    ));
    $response = curl_exec($curl);
    curl_close($curl);

    $json = json_decode($response);
    $result = array_map($movieMapper, array_slice($json->data, 0, $limit));

    $ret = new stdClass;
    $ret->success = true;
    $ret->result = $result;
    return $ret;
}

function search_scene($input, $limit)
{
    $sceneMapper = function($r) {
        $actorMapper = function($p) {
            return $p->name;
        };

        $ret = new stdClass;
        $ret->certificate = 'TV-MA';
        $ret->title = $r->site->name;
        $ret->tagline = $r->title;
        $ret->original_available = $r->date;
        $ret->summary = $r->description;
        $ret->genre = array();
        $ret->actor = array_map($actorMapper, $r->performers);
        $ret->directors = array();
        $ret->writer = array();
        $ret->episode = $r->site_id;
        $ret->season = 1;

        $extra = new stdClass;
        $extra->poster = array($r->image);

        $ret->extra = new stdClass;
        $ret->extra->{'io.github.mczrtoy.ThePornDB'} = $extra;
        return $ret;
    };

    $curl = curl_init();
    $url = API_URL_SCENES.http_build_query(array(
            'parse' => trim($input->title.' '.$input->original_available),
            'page' => 1,
            'per_page' => max(10, $limit)
    ));
    curl_setopt_array($curl, array(
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Authorization: Bearer '.getenv('METADATA_PLUGIN_APIKEY')
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_URL => $url,
        CURLOPT_USERAGENT => PLUGIN_USER_AGENT,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
    ));
    $response = curl_exec($curl);
    curl_close($curl);

    $json = json_decode($response);
    $result = array_map($sceneMapper, array_slice($json->data, 0, $limit));

    $ret = new stdClass;
    $ret->success = true;
    $ret->result = $result;
    return $ret;
}

function search_site($input, $limit)
{
    $showMapper = function($r) {
        $ret = new stdClass;
        $ret->title = $r->name;
        $ret->summary = $r->description;

        $extra = new stdClass;
        $extra->poster = array($r->poster);

        $ret->extra = new stdClass;
        $ret->extra->{'io.github.mczrtoy.ThePornDB'} = $extra;
        return $ret;
    };

    $curl = curl_init();
    $url = API_URL_SITES.http_build_query(array(
            'q' => trim($input->title),
            'page' => 1,
            'per_page' => max(10, $limit)
    ));
    curl_setopt_array($curl, array(
        CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'Authorization: Bearer '.getenv('METADATA_PLUGIN_APIKEY')
        ],
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_URL => $url,
        CURLOPT_USERAGENT => PLUGIN_USER_AGENT,
        CURLOPT_SSL_VERIFYHOST => false,
        CURLOPT_SSL_VERIFYPEER => false,
    ));
    $response = curl_exec($curl);
    curl_close($curl);

    $json = json_decode($response);
    $result = array_map($showMapper, array_slice($json->data, 0, $limit));

    $ret = new stdClass;
    $ret->success = true;
    $ret->result = $result;
    return $ret;
}
?>