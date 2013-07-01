<?php
/*
Plugin Name: Zesty WP Query Iterator
Plugin URI: https://github.com/cmwelsh/zesty-wp-query
Description: Query WordPress With Zest
Version: 0.0.1
Author: Chris M. Welsh
Author URI: http://cmwelsh.com
License: GPL v2 or later
Copyright: Chris M. Welsh
*/

class Zesty_Post_Iterator implements Iterator {
    protected $index = 0;
    protected $posts;
    protected $original_post;

    public function __construct($posts) {
        $this->original_post = isset($GLOBALS['post']) ? $GLOBALS['post'] : null;
        $this->posts = $posts;
    }

    function rewind() {
        $this->index = 0;
        $this->current();
    }

    function current() {
        if (!isset($this->posts[$this->key()])) {
            return null;
        }

        $current_post = $this->posts[$this->index];
        $GLOBALS['post'] = $current_post;
        setup_postdata($current_post);
        return $current_post;
    }

    function key() {
        return $this->index;
    }

    function next() {
        $this->index++;
        $valid = isset($this->posts[$this->key()]);
    }

    function valid() {
        $valid = isset($this->posts[$this->key()]);
        if (!$valid) {
            // reset post data
            if ($this->original_post !== null) {
                $GLOBALS['post'] = $this->original_post;
                setup_postdata($this->original_post);
            }
        }

        return $valid;
    }
}

class Zesty_Query_Iterator extends Zesty_Post_Iterator implements Countable {
    protected $query;

    public function __construct($options = null) {
        $defaults = array(
            'post_status' => 'publish',
            'order' => 'ASC',
            'orderby' => 'menu_order',
            'posts_per_page' => -1,
        );
        if ($options === null) {
            $this->query = $GLOBALS['wp_query'];
        }
        else {
            $query_arguments = array_merge($defaults, $options);
            $this->query = new WP_Query($query_arguments);
        }
        parent::__construct($this->query->posts);
    }

    public function count() {
        return $this->query->post_count;
    }

    // act as proxy for WP_Query
    public function __call($method, $arguments) {
        if (method_exists($this->query, $method)) {
            return call_user_func_array(array($this->query, $method), $arguments);
        }
    }

    public function __get($property) {
        if (isset($this->query->$property)) {
            return $this->query->$property;
        }
    }
}

/**
 * If you've read this far, perhaps you have some suggestions to make the
 * plugin even better.
 * Please fork the entire project on GitHub and add your changes there.
 * Then send a pull request or create an Issue, if you'd like.
 */
class ZWP_Query extends Zesty_Query_Iterator {

}
