<?php

/**
 * Provide a public-facing view for the widget
 *
 * This file is used to markup the public-facing aspects of the widget.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Pinboard_Linkroll
 * @subpackage Pinboard_Linkroll/widget/partials
 */
?>

<?php echo $args['before_widget']; ?>

<?php if ( ! empty( $instance['title'] ) ) {
  echo 
    $args['before_title'] 
    . apply_filters( 'widget_title', $instance['title'] )
    . $args['after_title'];
} ?>

<?php if ( count( $items ) > 0 && $items ) : ?>

        <ul class="list-group">
        <?php foreach ( $items as $item ) : ?>
          <li class="list-group-item">

            <a href="<?php echo $item[ 'permalink' ]; ?>">
              <h4 class="list-group-item-heading"><?php echo $item[ 'title' ]; ?></h4>
            </a>

            <p class="pinboard--date-saved">
              <?php _e( 'Saved on ', $this->get_pinboard_linkroll() ); ?>
              <?php echo date_i18n( get_option( 'date_format' ), strtotime( $item[ 'date' ] ) ); ?>
            </p>

            <?php if ( count( $item[ 'tags' ] ) > 0 ) : ?>
            
            <h5 class="pinboard--tags-heading"><?php _e( 'Tags: ', $this->get_pinboard_linkroll() ); ?></h5>
            <ul class="pinboard--tags">
            <?php foreach ( $item[ 'tags' ] as $tag => $link ) : ?>

              <li>
                <a href="<?php echo $link; ?>"><?php echo $tag; ?></a>
              </li>

            <?php endforeach; ?>
            </ul>
            
            <?php endif; ?>

          </li>
        <?php endforeach; ?>
        </ul>

<?php else: ?>

        <div class="alert alert-warning" role="alert">
          <?php _e( 'No links found.', $this->get_pinboard_linkroll() ); ?>
        </div>

<?php endif; ?>

<?php echo $args['after_widget']; ?>