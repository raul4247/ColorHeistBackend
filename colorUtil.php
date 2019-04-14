<?php

function find_colors($content){
    $pattern = '/(#([0-9a-f]{3}){1,2}|(rgba|hsla)\(\d{1,3}%?(,\s?\d{1,3}%?){2},\s?(1|0|0?\.\d+)\)|(rgb|hsl)\(\d{1,3}%?(,\s?\d{1,3}%?){2}\))/i';

    preg_match_all($pattern, $content, $matches);

    return call_user_func_array('array_merge', $matches);
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

		//RGB and RGBA values
		if(substr($color, 0, 3) == 'rgb'){
            $c = RGB_to_HEX($color);
            if(strlen($c)!=0)
    			$color = $c;
        }
        //HSL and HSLA values
		if(substr($color, 0, 3) == 'hsl'){
            $c = HSL_to_HEX($color);
            if(strlen($c)!=0)
    			$color = $c;
        }
    }

    // remove repeated items
    $colors = array_keys(array_flip($colors));

    return $colors;
}

function RGB_to_HEX($color) {
	$rgba  = [];
	$hex   = '';

    if(strlen($color) < 11)
        return "";

    preg_match_all('#\((([^()]+|(?R))*)\)#', $color ,$matches);
    $rgba = explode(',', implode(' ', $matches[1]));


    $r = dechex($rgba[0]);
    if(strlen($r) == 1)
        $r = $r . $r;

    $g = dechex($rgba[1]);  
    if(strlen($g) == 1)
        $g = $g . $g;

    $b = dechex($rgba[2]);
    if(strlen($b) == 1)
        $b = $b . $b;

    $a = '';

	if(array_key_exists('3', $rgba))
		$a = dechex($rgba['3'] * 255);

    if(strlen($a) == 1)
        $a = '0' . $a;

	return strtoupper("#$a$r$g$b");
}

function HSL_to_HEX ($color) {
	$hsl = [];

    preg_match_all('#\((([^()]+|(?R))*)\)#', $color ,$matches);
    $hsl = explode(',', implode(' ', $matches[1]));

    $h = str_replace('%', '', $hsl[0]);
    $s = str_replace('%', '', $hsl[1]);
    $l = str_replace('%', '', $hsl[2]);

    if(sizeof($hsl) == 4)
        $a = $hsl[3];

    $h /= 60;
    if ($h < 0) $h = 6 - fmod(-$h, 6);
    $h = fmod($h, 6);

    $s = max(0, min(1, $s / 100));
    $l = max(0, min(1, $l / 100));

    $c = (1 - abs((2 * $l) - 1)) * $s;
    $x = $c * (1 - abs(fmod($h, 2) - 1));

    if ($h < 1) {
        $r = $c;
        $g = $x;
        $b = 0;
    } 
    elseif ($h < 2) {
        $r = $x;
        $g = $c;
        $b = 0;
    } 
    elseif ($h < 3) {
        $r = 0;
        $g = $c;
        $b = $x;
    } 
    elseif ($h < 4) {
        $r = 0;
        $g = $x;
        $b = $c;
    } 
    elseif ($h < 5) {
        $r = $x;
        $g = 0;
        $b = $c;
    }
    else {
        $r = $c;
        $g = 0;
        $b = $x;
    }

    $m = $l - $c / 2;
    $r = round(($r + $m) * 255);
    $g = round(($g + $m) * 255);
    $b = round(($b + $m) * 255);

    if(sizeof($hsl) == 3)
        return RGB_to_HEX("rgb($r, $g, $b)");
    else if(sizeof($hsl) == 4)
        return RGB_to_HEX("rgba($r, $g, $b, $a)");
}

function filter_size6($value){
    return sizeof($value) == 6;
}

function filter_size8($value){
    return sizeof($value) == 8;
}

?>
