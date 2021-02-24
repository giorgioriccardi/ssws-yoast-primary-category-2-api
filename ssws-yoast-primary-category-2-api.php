<?php

/**
 * @package SSWS Yoast Primary Category 2 WP REST API
 * @version 1.0.2
 */
/*
Plugin Name: SSWS Yoast Primary Category 2 WP REST API
Plugin URI: https://github.com/giorgioriccardi/
Description: Add Yoast post primary category to the WP REST API
Author: Giorgio Riccardi
Version: 1.0.2
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
            register_rest_route('primary-cat/v1', '/yoastprimary', array(
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
            $children[] = (string) $child->term_id;
        }
        $children[] = $data['id'];

        $posts = new WP_Query(
            array(
                'post_status' => array('publish', 'draft'),
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key' => '_yoast_wpseo_primary_category',
                        'value' => $children,
                        'compare' => 'IN',
                    ),
                ),
            )
        );
        $return = array();
        while ($posts->have_posts()) {
            $posts->the_post();
            $return_post = array();

            $return_post['primary-category-dataset'] = get_the_category($posts->ID)[0];
            $return_post['link'] = get_the_permalink();
            $return_post['title'] = get_the_title();
            $return_post['id'] = get_the_ID();
            $return_post['slug'] = get_post_field('post_name', get_post());
            $return_post['status'] = get_post_status();
            $return[] = $return_post;
        }
        return $return;
    }
}

$rest_api_primary_cat = new Rest_Api_Posts_By_Primary_Category();
// https://whoischris.com/yoast-primary-category-endpoint-wp-rest-api/
// https://gist.github.com/ChrisFlannagan/a6f63a02ea16268a25bc5d386e9ac63a#file-primary-cat-php