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

                <select name="location_option">
                    <option value=""><?php esc_html_e('Select location', 'chebooking'); ?></option>

                    <?php
                    $instance->get_terms_hierarhical('location', $_POST['location_option']);
                    ?>
                </select>

                <select name="type_option">
                    <option value=""><?php esc_html_e('Select type', 'chebooking'); ?></option>

                    <?php
                    $instance->get_terms_hierarhical('type', $_POST['type_option']);
                    ?>
                </select>

                <input type="submit" name="submit" value="<?php esc_html_e('Filter', 'chebooking'); ?>">

            </form>
        </div>

        <?php
        $posts_per_page = -1;

        if ($options['posts_per_page']) {
            $posts_per_page = $options['posts_per_page'];
        }

        $args = [
            'post_type' => 'room',
            'posts_per_page' => esc_attr($posts_per_page),
            'tax_query' => array('relation' => 'AND'),
        ];

        if (isset($_POST['location_option']) && $_POST['location_option'] != '') {
            array_push($args['tax_query'], [
                'taxonomy' => 'location',
                'terms' => $_POST['location_option'],
            ]);
        }

        if (isset($_POST['type_option']) && $_POST['type_option'] != '') {
            array_push($args['tax_query'], [
                'taxonomy' => 'type',
                'terms' => $_POST['type_option'],
            ]);
        }

        if (!empty($_POST['submit'])) {
            $search_listing = new WP_Query($args);

            if ($search_listing->have_posts()) {
                while ($search_listing->have_posts()) {
                    $search_listing->the_post(); ?>

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
                <?php }

                // echo paginate_links();
            } else {
                echo esc_html__('No posts', 'chebooking');
            }
        } else {
            $paged = 1;

            if (get_query_var('paged')) {
                $paged = get_query_var('paged');
            }
            if (get_query_var('page')) {
                $paged = get_query_var('page');
            }

            $default_listing = [
                'post_type' => 'room',
                'posts_per_page' => esc_attr($posts_per_page),
                'paged' => $paged
            ];

            $rooms_listing = new WP_Query($default_listing);

            if ($rooms_listing->have_posts()) {
                while ($rooms_listing->have_posts()) {
                    $rooms_listing->the_post(); ?>

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
        <?php }

                $big = 999999999; // уникальное число

                echo paginate_links(array(
                    'base'    => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                    'current' => max(1, get_query_var('paged')),
                    'total'   => $rooms_listing->max_num_pages
                ));
            } else {
                echo esc_html__('No posts', 'chebooking');
            }
        }


        ?>
    </div>
</div>

<?php get_footer(); ?>