<article id="post=<?php the_ID(); ?>" <?php post_class(); ?>>
    <?php
    if (get_the_post_thumbnail(get_the_ID(), 'large')) {
        echo '<div class="image">' . get_the_post_thumbnail(get_the_ID(), 'large') . '</div>';
    }
    ?>
    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

    <div class="description">
        <?php the_excerpt(); ?>
    </div>

    <div class="metabox">
        <?php echo esc_html__('Room Price: ', 'chebooking') . get_post_meta(get_the_ID(), 'chebooking_price', true); ?>
        <?php echo esc_html__('Room Beds: ', 'chebooking') . get_post_meta(get_the_ID(), 'chebooking_beds_count', true); ?>
    </div>

    <?php
    $locations = get_the_terms(get_the_ID(), 'location');
    if (!empty($locations)) {
        foreach ($locations as $location) {
            echo '<div class="location">' . esc_html__('Location: ', 'chebooking') . $location->name . '</div>';
        }
    }

    $types = get_the_terms(get_the_ID(), 'type');
    if (!empty($types)) {
        foreach ($types as $type) {
            echo '<div class="type">' . esc_html__('Type: ', 'chebooking') . $type->name . '</div>';
        }
    }
    ?>
</article>