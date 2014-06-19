<?php
/**
 * Setup the metabox for pages
 *
 * @copyright   Copyright 2014, Jeremy Vossman
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       0.1.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}


class PK_Custom_Page_Label_Meta {

   /**
     * Instance of this class.
     *
     * @since    0.1.0
     *
     * @var      object
     */
    protected static $instance = null;

   /**
    * Load it up
    *
    * @since  0.1.0
    * @access public
    */
   private function __construct() {

        if ( !current_user_can( 'edit_pages' ) )
            return;

        add_action( 'load-post.php', array( $this, 'load_metaboxes' ) );
        add_action( 'load-post-new.php', array( $this, 'load_metaboxes' ) );

        add_filter('manage_pages_columns', array($this, 'add_page_column'));
        add_action('manage_pages_custom_column', array($this, 'page_column_data'), 10, 2);
   }

   /**
    * Start our metabox functions
    *
    * @since  0.1.0
    * @access public
    */
   public function load_metaboxes() {
         
         add_action( 'add_meta_boxes', array( $this, 'create_metaboxes' ) );
         add_action( 'save_post', array( $this, 'save_metaboxes' ), 10, 2 );
   
   }

   /**
    * Creates the custom header meta box.
    *
    * @since  0.1.0
    * @access public
    */
   public function create_metaboxes() {

      add_meta_box( 'pk-page-label', __( 'Page Label', 'pk_custom_page_label' ), array( $this, 'build_metaboxes' ), 'page', 'side', 'high' );

   }

   /**
    * BUild the metabox
    *
    * @since  0.1.0
    * @access public
    * @param  object  $post
    */
   public function build_metaboxes( $post, $box ) {
   
       wp_nonce_field( basename( __FILE__ ), 'pk_cpl_page_label_nonce' ); ?>
            
            <!-- build metabox open -->
            <p>
              <label for="pk-page-label"><?php _e( "Add a custom page label.", 'pk_cpl' ); ?></label>
              <input class="widefat" type="text" name="_pk_page_label" id="pk-page-label" value="<?php echo esc_attr( get_post_meta( $post->ID, '_pk_page_label', true ) ); ?>" size="30" />
            </p>
            <!-- close metabox -->

   <?php 
   }

   /**
    * Saves the data from the custom headers meta box.
    *
    * @since  0.1.0
    * @access public
    * @param  int     $post_id
    * @param  object  $post
    */
   public function save_metaboxes( $post_id, $post ) {

        //* Verify the nonce
        if ( !isset( $_POST['pk_cpl_page_label_nonce'] ) || !wp_verify_nonce( $_POST['pk_cpl_page_label_nonce'], basename( __FILE__ ) ) )
          return $post_id;

        // If this is an autosave don't do anything
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
          return;
        }

        //* Get the post type object.
        $post_type = get_post_type_object( $post->post_type );

        //* Check if the current user has permission to edit
        if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
          return $post_id;

        //* Get the posted data and sanitize it as a text field
        $new_meta_value = ( isset( $_POST['_pk_page_label'] ) ? sanitize_text_field( $_POST['_pk_page_label'] ) : '' );

        //* Store the meta key
        $meta_key = '_pk_page_label';

        //* Get the meta value 
        $meta_value = get_post_meta( $post_id, $meta_key, true );

        //* Add it
        if ( $new_meta_value && '' == $meta_value )
          add_post_meta( $post_id, $meta_key, $new_meta_value, true );

        //* Edit it
        elseif ( $new_meta_value && $new_meta_value != $meta_value )
          update_post_meta( $post_id, $meta_key, $new_meta_value );

        //* Delete it
        elseif ( '' == $new_meta_value && $meta_value )
          delete_post_meta( $post_id, $meta_key, $meta_value );
   }

   /**
    * Adds page label column
    *
    * @since 0.1.0
    * @access public
    * @param array $columns
    */
    public function add_page_column( $columns ) {
        $labelcolumn = array(
          'label_column' => __( 'Page Label', 'threewire' )
        );
        
        $columns = array_merge( $columns, $labelcolumn );

        return $columns;
    }

    /**
    * Loads column data
    *
    * @since 0.1.0
    * @access public
    * @param int $post_id
    * @param string $column_name
    */
    public function page_column_data( $column_name, $post_id ) {
        
        if ( $column_name == 'label_column' ) {

            $page_label = esc_attr( get_post_meta( $post_id, '_pk_page_label', true ) );
            
            echo $page_label;
        }
    }

   /**
     * Returns the instance.
     *
     * @since  0.1.0
     * @access public
     * @return object
     */
    public static function get_instance() {

        if ( !self::$instance )
            self::$instance = new self;

        return self::$instance;
    }

} // end class

// Instantiate our class
PK_Custom_Page_Label_Meta::get_instance();