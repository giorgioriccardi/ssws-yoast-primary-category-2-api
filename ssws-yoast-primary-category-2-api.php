<?php

/**
 * @package SSWS Yoast Primary Category 2 WP REST API
 * @version 1.0.0
 */
/*
Plugin Name: SSWS Yoast Primary Category 2 WP REST API
Plugin URI: https://github.com/giorgioriccardi/
Description: Add Yoast post primary category to the WP REST API
Author: Giorgio Riccardi
Version: 1.0.5
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
    }
}

$rest_api_primary_cat = new Rest_Api_Posts_By_Primary_Category();
// https://whoischris.com/yoast-primary-category-endpoint-wp-rest-api/
// https://gist.github.com/ChrisFlannagan/a6f63a02ea16268a25bc5d386e9ac63a#file-primary-cat-php