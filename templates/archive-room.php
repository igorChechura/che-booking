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

            </form>
        </div>

        <?php
        if (have_posts()) {
            while (have_posts()) {
                the_post(); ?>

                <article id="post=<?php the_ID(); ?>" <?php post_class(); ?>>
                    <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                    <?php the_excerpt(); ?>
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