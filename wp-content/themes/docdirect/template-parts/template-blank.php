<?php
/*
Template Name: Blank Page
*/
get_header();
?>
<div class="full-width">
    <div class="col-md-12">
        <?php
        while(have_posts()) : the_post();
            the_content();
        endwhile;
        ?>
    </div>
</div>
<?php
get_footer();
?>