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

        // STYLES

        $output .= "<style>";
        $output .= ".entry-content .list-articles-summary {-webkit-column-count: 3;-moz-column-count: 3;column-count: 3;}";
        $output .= ".entry-content .list-articles-summary li {margin-bottom:10px;display:block;}";
        $output .= ".entry-content .list-articles-summary li a {text-decoration: none;}";
        $output .= ".entry-content .list-articles-summary li a:hover {text-decoration: underline;}";
        $output .= ".entry-content a.title-link {text-decoration: none;}";
        $output .= ".entry-content a.title-link:hover {text-decoration: underline;}";
        $output .= "</style>";

        // THEMES

        $output .= "<h3>$data[summary]</h3><ol class=\"list-articles-summary\">";
        $theme_index = 0;
        foreach ($data['themes'] as $theme){
            $output .= "<li><a href=\"#theme-$theme_index\">";
            $j = 0;
            foreach ($theme['title'] as $title){
                if($j > 0) {
                    $output .= "<br><small>$title</small>";
                } else {
            if($theme_index>0){
                $output .= "<strong>$theme_index. $title</strong>";
            } else {
                $output .= "<strong>$title</strong>";
            }
                }
                $j++;
            }
            $output .= "</a></li>";
            $theme_index++;
        }
        $output .= "</ol>";

        // ARTICLES GROUPED BY THEMES

        $theme_index = 0;

        foreach ($data['themes'] as $theme){

            // SECTION HEAD

            $output .= "<hr id=\"theme-$theme_index\" style=\"margin: 24px 0;\"/>";
            if($theme_index>0){
                $output .= "<h3>";
                $j = 0;
                foreach ($theme['title'] as $title){
                    if($j > 0) {
                        $output .= "<br><small>$title</small>";
                    } else {
                        $output .= "<strong>$theme_index. $title</strong>";
                    }
                    $j++;
                }
                $output .= "</h3>";
            }
            // FILTER ARTICLES BY THEME

            $articles = array_filter($data['articles'], function($p) {
                global $theme_index;
                return $p['theme'] == $theme_index;
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
                $tag = $theme_index > 0 ? "p" : "h3";
        
                $output .= "<$tag style=\"margin-bottom:10px;\">";
                
                foreach ($article['title'] as $title_index => $title){
                    if($title_index == 0){
                        $index = "";
                        if($theme_index>0){
                            $index = $theme_index . "." . $i . ". ";
                        }
                        $output .= "<a class=\"title-link\" href=\"$data[basepath]$article[file]\" target=\"_blank\"><strong>$index $title [pdf]</strong></a>";
                    } else {
                        $output .= "<br/><small>$title</small>";
                    }
                }
                $output .= "</$tag>";

                // AUTHORS

                $output .= "<p style=\"margin-bottom:20px;\"><small><strong>$article[authors]</strong>";

                // PAGE

                $output .= "<br/>pg. $article[page]</small></p>";
                $i++;
            }


            $theme_index++;
        }


        return $output;
    }
}
add_shortcode( 'list_articles', 'list_articles_func' );