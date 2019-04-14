<?php
    require_once 'simple_html_dom.php';
    require_once 'cors.php';
    require_once 'colorUtil.php';

    allow_cors();

    function fix_path($rel, $base){
        if(substr($rel, 0, 2) == "//")
            return (parse_url($base)['scheme'] . ':' . $rel);
    }

    $url = $_GET['url'];
    $index = @file_get_html($url);

    $result = new stdClass();
    $result->status = 'ok';
    $result->status = 'ok';
    $result->status = 'ok';
    $result->title  = '';
    $result->rgb_colors = [];
    $result->rgba_colors = [];


    if(!$index)
        $result->status = 'error';
    else{
        $result->title  = $index->find('title',0)->innertext;
        $colors = find_colors($index);
        $colors_arr = array_merge($colors);


        $css_links = preg_match_all('/"([^"]+?\.css)"/', $index, $matches);
        if ($css_links !== FALSE && $css_links > 0){
            $links = call_user_func_array('array_merge', $matches);
            foreach ($matches[1] as $link){
                $content = @file_get_html(fix_path($link, $url));

                if($content){
                    $colors = find_colors($content);
                    $colors_arr = array_merge($colors_arr, $colors);
                }
                $colors_arr = filter_colors($colors_arr);
            }
        }

        $colors_arr = filter_colors($colors_arr);

        $result->rgb_colors = array_filter($colors_arr, function($color) {
            return strlen($color) == 7;
        });
        $result->rgb_colors = array_values($result->rgb_colors);

        $result->rgba_colors = array_filter($colors_arr, function($color) {
            return strlen($color) == 9;
        });
        $result->rgba_colors = array_values($result->rgba_colors);
        
    }
    echo json_encode($result);    

?>