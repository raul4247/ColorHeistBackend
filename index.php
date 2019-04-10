<?php
    require_once 'simple_html_dom.php';
    require_once 'cors.php';
    require_once 'colorUtil.php';

    allow_cors();

    $url = $_GET['url'];
    $index = @file_get_html($url);

    $result = new stdClass();
    $result->status = 'ok';
    $result->colors = []; 

    if(!$index)
        $result->status = 'error';
    else{
        $colors = find_colors($index);
        $result->colors = array_merge($colors);

        foreach ($index->find('link[type="text/css"]') as $css_links){
            $link = $css_links->href;
            $content = @file_get_html($link);

            if($content){
                $colors = find_colors($content);
                $result->colors = array_merge($colors);
            }
        }    
        $result->colors = filter_colors($result->colors);
    }
    echo json_encode($result);    

?>