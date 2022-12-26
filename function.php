<?php

function get($string = null){
    if($_GET[$string]) return htmlspecialchars($_GET[$string]);   
}

function post($string = null){
    if($_POST[$string]) return htmlspecialchars($_POST[$string]);
}

function get_curl($url)
{
    $browser = $_SERVER['HTTP_USER_AGENT'];
    $head[] = "Connection: keep-alive";
    $head[] = "Keep-Alive: 300";
    $head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
    $head[] = "Accept-Language: en-us,en;q=0.5";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, $browser);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
    $content = curl_exec($ch);
    curl_close($ch);
    return htmlspecialchars($content);
}

function return_curl($url)
{
    if (empty(get_curl($url)) && isset($url)) {
        $url = 'https://SITE_NAME.workers.dev/?url=' . $url;
    }
    return get_curl($url);
}
