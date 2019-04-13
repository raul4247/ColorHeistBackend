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
        //HSL values

		//HSLA values

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

    if(preg_match_all('#\((([^()]+|(?R))*)\)#', $color ,$matches))
    	$rgba = explode(',', implode(' ', $matches[1]));
    else
		$rgba = explode(',', $color);


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

function filter_size6($value){
    return sizeof($value) == 6;
}

function filter_size8($value){
    return sizeof($value) == 8;
}

?>
