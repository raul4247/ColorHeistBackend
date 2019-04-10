<?php

function find_colors($content){
    $pattern = '/(#([0-9a-f]{3}){1,2}|(rgba|hsla)\(\d{1,3}%?(,\s?\d{1,3}%?){2},\s?(1|0|0?\.\d+)\)|(rgb|hsl)\(\d{1,3}%?(,\s?\d{1,3}%?){2}\))/i';

    preg_match_all($pattern, $content, $matches);
    return $matches[0];
}

function filter_colors($colors){
    // remove empty items
    $colors = array_filter($colors); 
    // remove repeated items
    $colors = array_keys(array_flip($colors));

    return $colors;
}

?>