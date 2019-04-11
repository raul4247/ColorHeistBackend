<?php

function find_colors($content){
    $pattern = '/(#([0-9a-f]{3}){1,2}|(rgba|hsla)\(\d{1,3}%?(,\s?\d{1,3}%?){2},\s?(1|0|0?\.\d+)\)|(rgb|hsl)\(\d{1,3}%?(,\s?\d{1,3}%?){2}\))/i';

    preg_match_all($pattern, $content, $matches);
    return $matches[0];
}

function filter_colors($colors){
    // remove empty items
    $colors = array_filter($colors); 

    // Filtering color possibilities
    
    foreach ($colors as &$color) {
        // HEX value, size 6
        if($color[0] == '#' && strlen($color) == 7)
            $color = strtoupper($color);
        // HEX value, size 3
        if($color[0] == '#' && strlen($color) == 4)
            $color = strtoupper('#' . $color[1] . $color[1] . $color[2] . $color[2] . $color[3] . $color[3]);

        //HSL values, RGBA values, and HSLA values
    }

    // remove repeated items
    $colors = array_keys(array_flip($colors));

    return $colors;
}

?>