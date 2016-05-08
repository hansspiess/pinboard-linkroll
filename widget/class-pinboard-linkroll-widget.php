<?php

/**
 * Provides widget functionality for the plugin by using the Wordpress Widget API.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Pinboard_Linkroll
 * @subpackage Pinboard_Linkroll/widget
 */

/**
 * Provides widget functionality fpr the plugin by using the Wordpress Widget API.
 *
 * Defines the nessesary functions provided by the Wordpress Widget API.
 *
 * @package    Pinboard_Linkroll
 * @subpackage Pinboard_Linkroll/widget
 * @author     Hans Spieß <info@hansspiess.de>
 */
if ( defined( 'ABSPATH' ) && ! class_exists( 'Pinboard_Linkroll_Widget' ) ) {

  class Pinboard_Linkroll_Widget extends WP_Widget {


    /*--------------------------------------------------*/
    /* Constants & Properties
    /*--------------------------------------------------*/

    /**
     * Set widget defaults.
     *
     * @since    0.5.0
     */
    const BASE_RSS        = 'https://feeds.pinboard.in/rss/';
    const BASE            = 'https://pinboard.in/';
    const LINK_COUNT      = 20;
    const TEMPLATE_PATH   = 'plugin-pinboard.php';

    /**
     * The ID of this plugin.
     *
     * Is used as the text domain when internationalizing strings of text. 
     * Its value should match the Text Domain file header in the main widget file.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $pinboard_linkroll    The ID of this plugin.
     */
    private $pinboard_linkroll;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;


    /*--------------------------------------------------*/
    /* Constructor
    /*--------------------------------------------------*/

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @var      string    $pinboard_linkroll       The name of this plugin.
     * @var      string    $version                 The version of this plugin.
     */
    public function __construct() {

      /**
       * Inherit the widget slug from plugin class.
       *
       * @since    1.0.0
       * @param    string    $pinboard_linkroll    The widget slug used as text domain and identifier.
       */
      $this->pinboard_linkroll  = Pinboard_Linkroll::$pinboard_linkroll;
      $this->version            = Pinboard_Linkroll::$version;
      
      /**
       * Wordpress Widget API Constructor 
       */
      parent::__construct(
        $this->get_pinboard_linkroll(),
        __( 'Pinboard Linkroll', $this->get_pinboard_linkroll() ),
        array(
          'classname'  => $this->get_pinboard_linkroll(),
          'description' => __( 'Returns a list of Pinboard Links.', $this->get_pinboard_linkroll() )
        ),
        array( 'width' => 400 )
      );

    } // end constructor
    

    /*--------------------------------------------------*/
    /* Widget API Functions
    /*--------------------------------------------------*/

    /**
     * Outputs the content of the widget.
     *
     * @param array   args           The array of form elements
     * @param array   instance       The current instance of the widget
     */
    public function widget( $args, $instance ) {

      if ( $instance[ 'uri' ] ) {

        $items = $this->_get_feed_items( $instance );

      } else {

        $items = array();

      }

      $more_link = $this->_get_more_link( $instance );

      /**
       * Load the default public view of the widget, if no template file is found.
       * The view uses $items, $instance, $args.
       */
      if ( FALSE === $this->filtered_load_template( self::TEMPLATE_PATH, $items, $instance, $args ) ) {
        include plugin_dir_path( dirname( __FILE__ ) ) . 'widget/partials/pinboard-linkroll-widget-public.php';
      }

    }

    /**
     * Generates the administration form for the widget.
     *
     * @param   array  instance       The array of keys and values for the widget.
     */
    public function form( $instance ) {

      /**
       * Merging default values with incoming instance.
       */
      $defaults = array(
        'title'       => '',
        'username'    => '',
        'tags'        => '',
        'operator'    => 'and',
        'count'       => self::LINK_COUNT,
        'uri'         => false,
        'show_more'   => ''
      );
      $values = wp_parse_args( $instance, $defaults );

      /**
       * Load the admin view of the widget.
       */
      include plugin_dir_path( dirname( __FILE__ ) ) . 'widget/partials/pinboard-linkroll-widget-admin.php';

    }

    /** 
     * Processes the widget's options to be saved.
     * Fires whenever widget is saved (including first time dropped in a sidebar).
     *
     * @access  public
     * @param   array   new_instance  The new instance of values to be generated via the update
     * @param   array   old_instance  The previous instance of values before the update
     * @return  $instance
     */
    public function update( $new_instance, $old_instance ) {

      foreach( array( 'title', 'username', 'tags', 'show_more' ) as $field ) {
        $instance[ $field ]   = sanitize_text_field( $new_instance[ $field ] );
      }
      $instance[ 'operator' ] = $new_instance[ 'operator' ];
      $instance[ 'count' ]    = $new_instance[ 'count' ] < 0 || $new_instance[ 'count' ] > self::LINK_COUNT 
                              ? self::LINK_COUNT / 2
                              : $new_instance[ 'count' ];
      $instance[ 'uri' ]      = $this->_get_uri( $instance, 'rss' );

      if ( $instance[ 'uri' ] != $old_instance[ 'uri' ] && $instance[ 'uri' ] != '' ) {

        $this->_delete_feed_cache( $old_instance[ 'uri' ] );
        $this->_cache_feed( $instance[ 'uri' ] );

      }

      return $instance;

    }


    /*--------------------------------------------------*/
    /* Public Methods
    /*--------------------------------------------------*/

    /**
     * Return widget slug.
     *
     * @since   0.7.0
     * @return  Plugin slug variable.
     */
    public function get_pinboard_linkroll() {
      return $this->pinboard_linkroll;
    }

    /**
     * Returns filtered cache lifetime.
     *
     * @since   1.0.0
     * @return  int   seconds   Filtered TTL for feed cache.
     */
    public function filtered_cache_lifetime( $seconds ) {
      return apply_filters( 'pinboard_linkroll_cache_lifetime', $seconds );
    }

    /**
     * Loads filtered template path.
     *
     * @since   1.0.0
     * @return  string  path  path to template.
     */
    public function filtered_load_template( $path, $items, $instance, $args ) {
      $path = locate_template( apply_filters( 'pinboard_linkroll_template', $path ) );
      if ( $path != '' ) {
        include $path;
        return true;
      } else {
        return false;
      }
    }


    /*--------------------------------------------------*/
    /* Private Methods
    /*--------------------------------------------------*/

    /**
     * Fetches feed.
     *
     * @since   1.0.0
     * @return  false on error.
     */
    private function _get_feed_items( $instance ) {

      $rss = $this->_cache_feed( $instance['uri'] );

      if ( ! is_wp_error( $rss ) ) {

        $count = intval( $instance['count'] ) > 0 ? $instance['count'] : self::LINK_COUNT;
        $maxitems = $rss->get_item_quantity( $count ); 
        $items = $rss->get_items( 0, $maxitems );

        if ( count( $items ) > 0 ) {

          $return = array();

          foreach ( $items as $item ) {

            $i[ 'permalink' ]   = esc_url( strip_tags( $item->get_permalink() ) );
            $i[ 'title' ]       = esc_html( strip_tags( $item->get_title() ) );
            $i[ 'date' ]        = $item->get_date();

            if ( $item->get_category() ) {

              if ( $item->get_category()->term != '' ) {

                $tags = array();

                foreach ( $this->_get_tags_array( $instance, $item->get_category()->term ) as $tag => $link ) {
                  $tags[ $tag ] = $link;
                }

                $i[ 'tags' ] = $tags;

              }

            }

            $return[ $i[ 'permalink' ] ] = $i;

          }

          $rss->__destruct();
          unset( $rss );

          return $return;

        } else {

          return false;

        }

      } else {

        return false;

      }

    }

    /**
     * Get array of tag links.
     *
     * @since   1.0.0
     * @return  array( $tag => $uri )
     */
    private function _get_tags_array( $instance, $tags ) {

      $instance[ 'tags' ] = $tags;
      $instance[ 'operator' ] = 'or';

      return $this->_get_uri( $instance );

    }

    /**
     * Get 'more' link.
     *
     * @since   1.0.3
     * @return  null on error, else str url (user or pinboard base when no username is given)
     */
    private function _get_more_link( $instance ) {

      if ( 
        ( false === $this->_is_pinboard_username( $instance[ 'username' ] ) && empty( $instance[ 'tags' ] ) )
        ||
        empty( $instance[ 'show_more' ] ) 
        ) {
        return null;
      } else {
        //  temporarily delete tags to get user url from _get_uri()
        $more_instance = $instance;
        unset( $more_instance[ 'tags' ] );
        return array( 
          'url'   => $this->_get_uri( $more_instance ),
          'label' => $instance[ 'show_more' ]
        );
      }

    }

    /**
     * Check if usermane complies to the pinboard.in restrictions.
     *
     * @since   1.0.1
     * @return  boolean
     */
    private function _is_pinboard_username( $username ) {
      return preg_match( '/^[a-zA-Z0-9_\-\.]{2,29}$/i', $username ) === 1 ? true : false;
    }

    /**
     * Deletes unused feed transients.
     *
     * @since   1.0.0
     * @return  false on error.
     */
    private function _delete_feed_cache( $uri ) {

      if ( !is_array( $uri ) ) {
        $u[] = $uri;
      } else {
        $u = $uri;
      }

      foreach ( $u as $link ) {
        if ( 
          !delete_transient( 'feed_'     . md5( $link ) ) ||
          !delete_transient( 'feed_mod_' . md5( $link ) )
          ) {
          return false;
        }
      }

    }

    /**
     * Fetch feed and cache it.
     *
     * @since    1.0.0
     */
    private function _cache_feed( $uri ) {

      /**
       * Set feed cache lifetime according to custom filter,
       * get feed,
       * remove filter immediately.
       */
      add_filter( 'wp_feed_cache_transient_lifetime', array( $this, 'filtered_cache_lifetime' ) );
      $rss = fetch_feed( $uri );
      remove_filter( 'wp_feed_cache_transient_lifetime', 'filtered_cache_lifetime' );

      return $rss;

    }

    /**
     * Build URI for fetch_feed() or build link array for get_tags_array().
     *
     * This method is used to build rss uri arrays for update() method as well as 
     * link arrays for tags beeing displayed in the public view of the widget.
     *
     * @since   1.0.0
     * @return  Plugin slug variable.
     */
    private function _get_uri( $instance, $rss = '' ) {

      if ( 
        ( $instance[ 'username' ] == '' && $instance[ 'tags' ] == '' )
        ||
        ( $instance[ 'username' ] != '' && false === $this->_is_pinboard_username( $instance[ 'username' ] ) )
        ) {
        return false;
      }

      if ( 'rss' === $rss ) {
        $base = self::BASE_RSS;
      } else {
        $base = self::BASE;
      }
      
      $uri_user = $instance[ 'username' ] != '' ? 'u:' . $instance[ 'username' ] . '/' : '';
      $uri_base = $base . $uri_user;

      if ( $instance[ 'tags' ] != '' ) {

        $tags = explode( ' ', trim( $instance[ 'tags' ] ) );

        if ( $instance[ 'operator' ] == 'and' ) {

          $uri_tags = '';

          foreach ( $tags as $tag ) {
            $uri_tags .= 't:' . urlencode( $tag ) . '/';
          }

          $uri = $uri_base . $uri_tags;

        } else {

          foreach ( $tags as $tag ) {
            if ( 'rss' === $rss ) {
              $uri[] = $uri_base . 't:' . urlencode( $tag ) . '/';
            } else {
              $uri[ $tag ] = $uri_base . 't:' . urlencode( $tag ) . '/';
            }
          }

        }
        return $uri;

      } else if ( !empty( $uri_user ) ) {

        return $uri_base;

      } else {

        return BASE;

      }
      
    }


  }

}
