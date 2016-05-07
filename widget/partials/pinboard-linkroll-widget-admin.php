<?php

/**
 * Provide an admin view for the widget
 *
 * This file is used to markup the admin aspects of the widget.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Pinboard_Linkroll
 * @subpackage Pinboard_Linkroll/widget/partials
 */
?>

      <!-- Widget Title -->
      <p>
        <label for="<?php echo $this->get_field_id('title'); ?>">
          <?php _e('Title (optional):', $this->get_pinboard_linkroll() ); ?>
        </label>
        <input class="widefat" 
          id="<?php echo $this->get_field_id('title'); ?>" 
          name="<?php echo $this->get_field_name('title'); ?>" 
          type="text" 
          value="<?php echo $values['title']; ?>" />
      </p>
      
      <!-- pinboard.in username -->
      <p>
        <label for="<?php echo $this->get_field_id('username'); ?>">
          <?php _e('Pinboard.in Username:', $this->get_pinboard_linkroll() ); ?>
        </label>
        <input class="widefat" 
          id="<?php echo $this->get_field_id('username'); ?>" 
          name="<?php echo $this->get_field_name('username'); ?>" 
          type="text" 
          value="<?php echo $values['username']; ?>" /><br>
          <small><?php _e('Leave blank to show links of all users. Optional, if tags are set.', $this->get_pinboard_linkroll() ); ?></small>
      </p>
      
      <!-- Tags to show -->
      <p>
        <label for="<?php echo $this->get_field_id('tags'); ?>">
          <?php _e('Show only items with those tags:', $this->get_pinboard_linkroll() ); ?>
        </label>
        <input class="widefat" 
          id="<?php echo $this->get_field_id('tags'); ?>" 
          name="<?php echo $this->get_field_name('tags'); ?>" 
          type="text" 
          value="<?php echo $values['tags']; ?>" /><br>
          <small><?php 
          _e('Space separated. Leave blank to show all items. Optional, if user is set.', 
          $this->get_pinboard_linkroll() ); ?></small>
      </p>
      
      <!-- Choose tag operator -->
      <p>
        <label for="<?php echo $this->get_field_id('operator'); ?>">
          <?php _e('Items must have:', $this->get_pinboard_linkroll() ); ?>
        </label>
        <select 
          id="<?php echo $this->get_field_id('operator'); ?>" 
          name="<?php echo $this->get_field_name('operator'); ?>">
          <option value="and" <?php selected( $values['operator'], 'and' ); ?>>
            <?php _e('...all of the above tags.', $this->get_pinboard_linkroll() ); ?>
          </option>
          <option value="or" <?php selected( $values['operator'], 'or' ); ?>>
            <?php _e('...at least one of the above tags.', $this->get_pinboard_linkroll() ); ?>
          </option>
        </select>
      </p>

      <!-- How many items -->
      <p>
        <label for="<?php echo $this->get_field_id('count'); ?>">
          <?php _e('How many items would you like to display?', $this->get_pinboard_linkroll() ); ?>
        </label>
        <select 
          id="<?php echo $this->get_field_id( 'count' ); ?>" 
          name="<?php echo $this->get_field_name( 'count' ); ?>">
          <?php for( $count = 1; $count <= self::LINK_COUNT; $count++ ) : ?>
          <option value="<?php echo $count; ?>" <?php selected( $values['count'], $count ); ?>>
            <?php echo $count; ?>
          </option>
          <?php endfor; ?>
        </select>

      </p>

      <!-- Show 'more' link? -->
      <p>
        <label for="<?php echo $this->get_field_id('show_more'); ?>">
          <?php _e('Display a \'Watch all links\'-Link?', $this->get_pinboard_linkroll() ); ?>
        </label>
        <input class="widefat" 
          id="<?php echo $this->get_field_id('show_more'); ?>" 
          name="<?php echo $this->get_field_name('show_more'); ?>" 
          type="text" 
          value="<?php echo $values['show_more']; ?>" /><br>
          <small><?php 
          _e('Leave blank to show no link, otherwise link name.', 
          $this->get_pinboard_linkroll() ); ?></small>

      </p>

<?php  ?>