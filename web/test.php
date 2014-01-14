<?php
header('Content-Type: text/html;charset=utf-8');
$text = 'Сдам 3 шку на промавтоматике, мебель, бытовая техника, 50 \90к 3000+свет';
parse($text);
$text = '85/42/8,5, комнаты раздельные, с/у раздельный, м/п окна, счётчик на горячую воду, лоджия на две комнаты 14 м.кв., хорошее жилое состояние.';
parse($text);


function parse($txt)
{
    echo $txt;
    $txt = preg_replace("~(\d)\s*[/\\\]\s*(\d)~","$1/$2", $txt);
    preg_match("/(?<!\d|\d\/)(\d+)\/(\d+)(?!\d|\/\d)/", $txt, $m);

    echo "<pre>";
    var_dump($m);
}