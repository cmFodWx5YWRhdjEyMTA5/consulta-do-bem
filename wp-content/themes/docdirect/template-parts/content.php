<?php
/**
 * @package Doctor Directory
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="block-list">
        <?php the_title( sprintf( '<h2><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
        <?php if ( 'post' == get_post_type() ) : ?>
            <div class="entry-meta">
                <?php docdirect_posted_on(); ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
        <div class="entry-content">
            <?php
            /* translators: %s: Name of current post */
            the_content( sprintf(
                wp_kses( __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'docdirect' ), array( 'span' => array( 'class' => array() ) ) ),
                the_title( '<span class="screen-reader-text">"', '"</span>', false )
            ) );
            ?>

        </div><!-- .entry-content -->
    </div>
    <?php
    wp_link_pages( array(
        'before' => '<div class="page-links">' . esc_attr( 'Pages:', 'docdirect' ),
        'after'  => '</div>',
    ) );
    ?>
</article><!-- #post-## -->