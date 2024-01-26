<?php get_header(); ?>

<div class="wrapper">
    <?php
        if(have_posts()) {
            while(have_posts()) { the_post(); ?>

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

<?php get_footer(); ?>