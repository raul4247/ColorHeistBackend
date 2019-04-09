<?php
    /* Get request returns url's content*/
    require_once 'simple_html_dom.php';

    function findColors($content){
        $pattern = '/(#([0-9a-f]{3}){1,2}|(rgba|hsla)\(\d{1,3}%?(,\s?\d{1,3}%?){2},\s?(1|0|0?\.\d+)\)|(rgb|hsl)\(\d{1,3}%?(,\s?\d{1,3}%?){2}\))/i';

        preg_match_all($pattern, $content, $matches);
        return $matches;
    }
    function filterColors($colors){
        $colors = array_filter($colors); 
        $colors = array_unique($colors); 
        
        return $colors;
    }

    $url = $_GET['url'];
    $file = @file_get_html($url);

    if(!$file)
        echo 'URL Error!';
    else{
        $colors = [];

        $colors = call_user_func_array("array_merge", findColors($file));

        foreach ($file->find('link[type="text/css"]') as $stylesheet){
            $stylesheet_url = $stylesheet->href;
            $newFile = @file_get_html($stylesheet_url);

            if(!$newFile)
                echo 'URL Error!';
            else
            $colors = call_user_func_array("array_merge", findColors($newFile));
        }    
        $colors = filterColors($colors);
        echo json_encode($colors);
    }
    
?>