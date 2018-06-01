<?php
if (!defined('FW')) {
    die('Forbidden');
}
/**
 * @var $atts
 */
$uni_flag = fw_unique_increment();
$teams = $atts['team_member'];
?>
<div class="sc-teams-grid">
    <div class="tg-team-members">
        <?php if ( !empty($atts['heading']) || !empty($atts['team_description']) ) { ?>
            <div class="col-sm-10 col-sm-offset-1 col-xs-12">
                <div class="tg-section-head">
                    <?php if (!empty($atts['heading'])) { ?>
                        <div class="tg-theme-heading">
                            <h2><?php echo esc_attr($atts['heading']); ?></h2>
                            <span class="tg-roundbox"></span>
                        </div>
                    <?php } ?>
                    <?php if (!empty($atts['team_description'])) { ?>
                        <div class="tg-description">
                            <p><?php echo nl2br(($atts['team_description'])); ?></p>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if (!empty($teams)) { ?>
            <div class="tg-teams-member">
                <?php foreach ($teams as $team_member) { ?>
                    <div class="col-sm-4">
                        <div class="tg-member">
                            <figure>
                                <?php if (!empty($team_member['avatar']['url'])) { ?>
                                    <img src="<?php echo esc_url($team_member['avatar']['url']) ?>" alt="<?php esc_attr_e('team','docdirect');?>">
                                <?php } ?>
                                <?php if (!empty($team_member['social_icons'])) { ?>
                                    <figcaption class="tg-share-icons">
                                        <ul class="tg-socialicon">
                                            <?php
                                            foreach ($team_member['social_icons'] as $socials) {
                                                $color = isset($socials['color']) && $socials['color'] != '' ? $socials['color'] : '#7dbb00';
                                                if (!empty($socials['url'])) {
                                                    $social_url = $socials['url'];
                                                }
                                                ?>
                                                <li class="tg-facebook"><a style="background:<?php echo esc_attr($color); ?>" href="<?php echo esc_url( $social_url ); ?>"><i class="<?php echo esc_attr($socials['icon']); ?>"></i></a></li>
                                            <?php } ?>
                                        </ul>
                                    </figcaption>
                                <?php } ?>
                            </figure>
                            <?php if (!empty($team_member['name']) || !empty($team_member['designation'])) { ?>
                                <div class="tg-contentbox">
                                    <h2><?php echo esc_attr($team_member['name']) ?><span><?php echo esc_attr($team_member['designation']) ?></span></h2>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>
