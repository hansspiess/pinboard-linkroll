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
          <?php _e('Title:', $this->get_pinboard_linkroll() ); ?>
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
          <small><?php _e('Leave blank to show links of all users.', $this->get_pinboard_linkroll() ); ?></small>
      </p>
      
      <!-- Tags to show -->
      <p>
        <label for="<?php echo $this->get_field_id('tags'); ?>">
          <?php _e('Tags:', $this->get_pinboard_linkroll() ); ?>
        </label>
        <input class="widefat" 
          id="<?php echo $this->get_field_id('tags'); ?>" 
          name="<?php echo $this->get_field_name('tags'); ?>" 
          type="text" 
          value="<?php echo $values['tags']; ?>" /><br>
          <small><?php _e('Space separated, leave blank to not filter links.', $this->get_pinboard_linkroll() ); ?></small>
      </p>
      
      <!-- Choose tag operator -->
      <p>
        <label for="<?php echo $this->get_field_id('operator'); ?>">
          <?php _e('Show links with:', $this->get_pinboard_linkroll() ); ?>
        </label>
        <select class="widefat" 
          id="<?php echo $this->get_field_id('operator'); ?>" 
          name="<?php echo $this->get_field_name('operator'); ?>">
          <option value="and" <?php selected( $values['operator'], 'and' ); ?>>
            <?php _e('...all of the given tags.', $this->get_pinboard_linkroll() ); ?>
          </option>
          <option value="or" <?php selected( $values['operator'], 'or' ); ?>>
            <?php _e('...any of the given tags.', $this->get_pinboard_linkroll() ); ?>
          </option>
        </select>
      </p>

      <!-- How many links -->
      <p>
        <input 
          id="<?php echo $this->get_field_id('count'); ?>" 
          name="<?php echo $this->get_field_name('count'); ?>" 
          type="number" 
          value="<?php echo $values['count']; ?>" 
          min="1" max="100" />
        <label for="<?php echo $this->get_field_id('count'); ?>">
          <?php _e('How many links?', $this->get_pinboard_linkroll() ); ?>
        </label>
      </p>

<?php  ?>