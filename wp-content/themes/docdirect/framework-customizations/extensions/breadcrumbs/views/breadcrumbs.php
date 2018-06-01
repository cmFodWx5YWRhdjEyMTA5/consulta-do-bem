<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

?>
<?php if ( ! empty( $items ) ) : ?>
	<ol class="tg-breadcrumb">
		<?php for ( $i = 0; $i < count( $items ); $i ++ ) : ?>
			<?php if ( $i == ( count( $items ) - 1 ) ) : ?>
				<li class="active"><?php echo esc_attr( $items[ $i ]['name']); ?></li>
			<?php elseif ( $i == 0 ) : ?>
				<li class="first-item">
				<?php if( isset( $items[ $i ]['url'] ) ) : ?>
					<a href="<?php echo esc_url( $items[ $i ]['url']); ?>"><?php echo esc_attr( $items[ $i ]['name']); ?></a></li>
				<?php else : echo esc_attr( $items[ $i ]['name']); endif ?>
			<?php
			else : ?>
				<li class="<?php echo( $i - 1 ) ?>-item">
					<?php if( isset( $items[ $i ]['url'] ) ) : ?>
						<a href="<?php echo esc_url( $items[ $i ]['url']); ?>"><?php echo esc_attr( $items[ $i ]['name']); ?></a></li>
					<?php else : echo esc_attr( $items[ $i ]['name']); endif ?>
			<?php endif ?>
		<?php endfor ?>
	</ol>
<?php endif ?>