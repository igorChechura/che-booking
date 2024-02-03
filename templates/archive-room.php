<?php
get_header();
$options = get_option('booking_settings_options');
$instance = new CheBooking();
?>

<div class="wrapper">
    <div class="booking_rooms">

        <h1><?php if (isset($options['title_for_rooms_page'])) {
                echo $options['title_for_rooms_page'];
            } ?></h1>

        <div class="filter">
            <form method="POST" action="<?php echo get_post_type_archive_link('room'); ?>">

                <select name="location">
                    <option value=""><?php esc_html_e('Select location', 'chebooking'); ?></option>

                    <?php
                    $instance->get_terms_hierarhical('location');
                    ?>
                </select>

                <select name="type">
                    <option value=""><?php esc_html_e('Select type', 'chebooking'); ?></option>

                    <?php
                    $instance->get_terms_hierarhical('type');
                    ?>
                </select>

                <input type="submit" name="submit" value="<?php esc_html_e('Filter', 'chebooking'); ?>">

            </form>
        </div>

        <?php
        if (have_posts()) {
            while (have_posts()) {
                the_post(); ?>

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

                    <?php
                    $locations = get_the_terms(get_the_ID(), 'location');
                    if (!empty($locations)) {
                        foreach ($locations as $location) {
                            echo '<div class="location">' . esc_html__('Location', 'chebooking') . $location->name . '</div>';
                        }
                    }

                    $types = get_the_terms(get_the_ID(), 'type');
                    if (!empty($types)) {
                        foreach ($types as $type) {
                            echo '<div class="type">' . esc_html__('Type', 'chebooking') . $type->name . '</div>';
                        }
                    }
                    ?>
                </article>
        <?php }

            echo paginate_links();
        } else {
            echo esc_html__('No posts', 'chebooking');
        }
        ?>
    </div>
</div>

<?php get_footer(); ?>