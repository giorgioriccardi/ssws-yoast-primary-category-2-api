<?php

/**
 * @package SSWS Yoast Primary Category 2 WP REST API
 * @version 1.0.1
 */
/*
Plugin Name: SSWS Yoast Primary Category 2 WP REST API
Plugin URI: https://github.com/giorgioriccardi/
Description: Add Yoast post primary category to the WP REST API
Author: Giorgio Riccardi
Version: 1.0.1
Author URI: https://www.seatoskywebsolutions.ca/
Require: Wordpress Seo plugin by Yoast
 */

/**
 * Enable custom endpoints for post primary category
 *
 * Custom fields are provided by the Yoast SEO plugin
 */

class Rest_Api_Posts_By_Primary_Category
{
    public function __construct()
    {
        add_action('rest_api_init', function () {
            register_rest_route(SITE_PREFIX . '/v1', '/yoastprimary/(?P<id>\d+)', array(
                'methods' => 'GET',
                'callback' => array($this, 'get_posts_by_primary_cat'),
            ));
        });
    }

    public function get_posts_by_primary_cat($data)
    {
        $child_cats = get_categories(array('parent' => $data['id']));
        $children = array();
        foreach ($child_cats as $child) {
            $children[] = (string)$child->term_id;
        }
        $children[] = $data['id'];

        $posts = new WP_Query(
            array(
                'post_status' => 'publish',
                'meta_query' => array(
                    array(
                        'key' => '_yoast_wpseo_primary_category',
                        'value' => $children,
                        'compare' => 'IN',
                    ),
                )
            )
        );
        $return = array();
        while ($posts->have_posts()) {
            $posts->the_post();
            $return_post = array();
            if (!get_the_post_thumbnail_url()) {
                // I set this constant in my theme's function.php
                $return_post['thumb'] = CHILD_THEME_URI . '/assets/imgs/default-thumbnail.jpg';
            } else {
                $return_post['thumb'] = get_the_post_thumbnail_url();
            }
            $return_post['link'] = get_the_permalink();
            $return_post['title'] = get_the_title();
            $return_post['time'] = get_the_time('F d, Y');
            $return[] = $return_post;
        }
        return $return;
    }
}

$rest_api_primary_cat = new Rest_Api_Posts_By_Primary_Category();
// https://whoischris.com/yoast-primary-category-endpoint-wp-rest-api/
// https://gist.github.com/ChrisFlannagan/a6f63a02ea16268a25bc5d386e9ac63a#file-primary-cat-php