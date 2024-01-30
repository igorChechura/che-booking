<h1 class="chebooking-title"><?php echo esc_html__('Booking Settings', 'chebooking'); ?></h1>
<?php settings_errors(); ?>
<div class="chebooking-content">
    <form method="post" action="options.php">
        <?php
            settings_fields('booking_settings');
            do_settings_sections('chebooking');
            submit_button();
        ?>
    </form>
</div>