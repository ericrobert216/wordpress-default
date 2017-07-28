<?php
/**
 * @version  1.0.0
 * @package  clever-swatch/templates/admin
 * @author   cleversoft.co <hello.cleversoft@gmail.com>
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<li class="image" data-attachment_id="<?php echo( esc_attr( $attachment_id ) ); ?>">
    <?php echo( $attachment ); ?>
    <ul class="actions">
        <li>
            <a href="javascrip:void(0)" class="zoo-cw-delete-gallery-image tips" data-tip="<?php echo( esc_attr__( 'Delete image', 'clever-swatch' ) ); ?>"><?php echo( __( 'Delete', 'clever-swatch' ) ); ?></a>
        </li>
    </ul>
</li>
