# Zesty WP Query Iterator

Query WordPress With Zest

Thanks to Øystein Riiser Gundersen for allowing me to use
[his original code](https://gist.github.com/1343203).

-----------------------

### Overview

This plugin allows you to query WordPress very easily, using the built-in
`WP_Query` class behind the scenes. Here is an example of the usage:

    <?php foreach (new ZWP_Query(array('post_type' => 'page')) as $page) : ?>
        <h1><?php the_title(); ?></h1>
        <?php the_content(); ?>
    <?php endforeach; ?>

As you can see, `ZWP_Query` takes the same arguments as `WP_Query`. For
additional information, please read the
[WP_Query documentation](https://codex.wordpress.org/Class_Reference/WP_Query).

### License

GPLv2 or later
