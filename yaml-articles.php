<?php
/**
 * Plugin Name: YAML Articles
 * Plugin URI: http://github.com/sulram/yaml-articles
 * Description: Easy article list with YAML.
 * Version: 1.0.0
 * Author: Marlus Araujo
 * Author URI: http://marlus.com
 * License: GPL2
 */


require "vendor/spyc.php";

function list_articles_func( $atts ) {
    
    global $attr_filter;
    global $theme_index;
    
    $a = shortcode_atts( array(
        'file' => null,
        'filter' => null
    ), $atts );

    $attr_filter = $a['filter'];
    
    if($a['file'] == null){
        return "list_articles error: file not informed";
    }
    else {

        $data = file_get_contents($a['file']);
        $data = Spyc::YAMLLoadString($data);

        $output = "";

        // THEMES

        $output = "<h3>$data[summary]</h3>";
        $theme_index = 1;
        foreach ($data['themes'] as $theme){
            $output .= "<li><a href=\"#theme-$theme_index\">$theme_index. ";
            $j = 0;
            foreach ($theme['title'] as $title){
                if($j > 0) $output .= " / ";
                $output .= $title;
                $j++;
            }
            $output .= "</a></li>";
            $theme_index++;
        }

        // ARTICLES GROUPED BY THEMES

        $theme_index = 1;

        foreach ($data['themes'] as $theme){

            // SECTION HEAD

            $output .= "<hr id=\"theme-$theme_index\" style=\"margin: 24px 0;\"/>";
            $output .= "<h3>";
            $j = 0;
            foreach ($theme['title'] as $title){
                if($j > 0) $output .= " / ";
                $output .= $title;
                $j++;
            }
            $output .= "</h3>";

            // FILTER ARTICLES BY THEME

            $articles = array_filter($data['articles'], function($p) {
                global $theme_index;
                return $p['theme'] == $theme_index - 1;
            });

            // FILTER ARTICLES BY SHORTCODE ATTR

            if($attr_filter != null){
                $articles = array_filter($articles, function($p) {
                    global $attr_filter;
                    return $p['filter'] == $attr_filter;
                });
            }

            // DISPLAY ARTICLES

            $i = 1;

            foreach ($articles as $article_index => $article){

                // TITLE

                $output .= "<p style=\"margin-bottom:10px;\">";
                
                foreach ($article['title'] as $title_index => $title){
                    if($title_index == 0){
                        $output .= "<a href=\"$data[basepath]$article[file]\" target=\"_blank\">$i. $title [pdf]</a>";
                    } else {
                        $output .= "<br/><small>$title</small>";
                    }
                }
                $output .= "</p>";

                // AUTHORS

                $output .= "<p style=\"margin-bottom:20px;\">$article[authors]";

                // PAGE

                $output .= "<br/><small>$article[page]</small></p>";
                $i++;
            }


            $theme_index++;
        }


        return $output;
    }
}
add_shortcode( 'list_articles', 'list_articles_func' );