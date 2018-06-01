<?php
if (!defined('FW'))
    die('Forbidden');
/**
 * @var $atts
 */
?>
<div class="sc-video ">
    <div class="tg-videobox">
        <figure>
             <?php if (!empty($atts['video_image']['url'])) { ?>
             	<img src="<?php echo esc_url($atts['video_image']['url']); ?>" alt="<?php echo esc_attr($atts['video_title']); ?>">
             <?php } ?>
            <?php if (!empty($atts['video_link'])) { ?>
            <figcaption>
                <a class="tg-playbtn" href="<?php echo esc_url($atts['video_link']);?>" data-rel="prettyPhoto[iframe]"><i class="fa fa-play"></i></a>
            </figcaption>
            <?php } ?>
        </figure>
    </div>
</div>