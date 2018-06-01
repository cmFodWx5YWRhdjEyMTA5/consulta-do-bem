<?php
if (!defined('FW'))
    die('Forbidden');
/**
 * @var atts
 */
$uni_flag = fw_unique_increment();
$counters = $atts['counter_popup'];
?>
<div class="sc-counters tg-counter-facts">
    <div id="tg-counters-<?php echo esc_attr($uni_flag); ?>" class="tg-counterarea">
        <div class="row">
            <?php
            if (isset($counters) && !empty($counters)) {
                foreach ($counters as $counter) {
                    $start_from = isset($counter['counter_start']) && $counter['counter_start'] != '' ? $counter['counter_start'] : 0;
                    $counter_icons = isset($counter['counter_icons']) && $counter['counter_icons'] != '' ? $counter['counter_icons'] : '';
                    $counter_end = isset($counter['counter_end']) && $counter['counter_end'] != '' ? $counter['counter_end'] : 1000;
                    $counter_interval = isset($counter['counter_interval']) && $counter['counter_interval'] != '' ? $counter['counter_interval'] : 50;
                    $counter_speed = isset($counter['counter_speed']) && $counter['counter_speed'] != '' ? $counter['counter_speed'] : 8000;
                    ?>
                    <div class="col-sm-6">
                        <div class="tg-counter">
                            <?php if (!empty($counter_icons)) { ?>
                                <i class="<?php echo esc_attr($counter_icons); ?>"></i>
                            <?php } ?>
                            <div class="tg-contentbox">
                                <div class="tg-heading-border">
                                    <span class="timer" data-from="<?php echo intval($start_from); ?>" data-to="<?php echo esc_attr($counter_end); ?>" data-speed="<?php echo esc_attr($counter_speed); ?>" data-refresh-interval="<?php echo esc_attr($counter_interval); ?>"><?php echo esc_attr($counter_end); ?></span>
                                    <?php if (!empty($counter['counter_title'])) { ?>
                                        <h3><?php echo esc_attr($counter['counter_title']) ?></h3>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
    <script>
        jQuery(document).ready(function () {

            /* -------------------------------------
             COUNTER
             -------------------------------------- */
            try {
                jQuery('#tg-counters-<?php echo esc_js($uni_flag); ?>').appear(function () {
                    jQuery('.timer').countTo()
                });
            } catch (err) {
            }
        });

    </script>
</div>

