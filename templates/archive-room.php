<?php
get_header();
$options = get_option('booking_settings_options');
$instance = new CheBooking();
$templates = new Che_Booking_Template_Loader();
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

                <input type="text" name="price_down" value="<?php if(isset($_POST['price_down'])) {echo $_POST['price_down'];} ?>">
                <input type="text" name="price_up" value="<?php if(isset($_POST['price_up'])) {echo $_POST['price_up'];} ?>">

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
            'meta_query' => array('relation' => 'AND')
        ];

        if(isset($_POST['price_down']) && isset($_POST['price_up'])) {
            array_push($args['meta_query'], [
                'key' => 'chebooking_price',
                'value' => [$_POST['price_down'], $_POST['price_up']],
                'type' => 'numeric',
                'compare' => 'BETWEEN'
            ]);
        }

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
                    $search_listing->the_post();

                    $templates->get_template_part('content', 'room');
                }
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
                    $rooms_listing->the_post();

                    $templates->get_template_part('content', 'room');
                }

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