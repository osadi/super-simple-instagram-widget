<?php
/*
Plugin Name: Super Simple Instagram Widget
Plugin URI: http://github.com/osadi/super-simple-instagram-widget
Version: 0.2
Description: Just want your Instagram data to play with? Here it is. (Well, at least the 24 latest pictures)
Author: osadi
Author URI: http://github.com/osadi
License: GPLv2 or later
*/

class SuperSimpleInstagramWidget extends WP_Widget {

    const HTTP_TIMEOUT = 60;

    public $template_name = 'ssiw-template.php';
    public $instagram_url = 'https://instagram.com/';

    function __construct() {
        // Instantiate the parent object
        parent::__construct( false, 'Super Simple Instagram Widget' );
    }

    /**
     * Displays the chosen users 20 latest pictures in a list.
     * Unless you create your own template, which is strongly encouraged.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    function widget( $args, $instance ) {
        $username = ( ! empty ( $instance['username'] ) ) ? $instance['username'] : 'instagram';
        $data     = $this->load_instagram_data( $username );

        $this->load_template( $data );
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['username'] = ( ! empty( $new_instance['username'] ) ) ? strip_tags( $new_instance['username'] ) : '';

        return $instance;
    }

    /**
     * The only available setting is the Instagram username that we want to get the data from.
     *
     * @see WP_Widget::form()
     *
     * @param  array $instance Previously saved values from database.
     * @return string|void
     */
    function form( $instance ) {
        $username = $instance['username'];
        ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e( 'Username:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" type="text" value="<?php echo esc_attr( $username ); ?>">
        </p>
        <?php
    }

    /**
     * Issues a GET to Instagram for the specified user. All pictures are available inside a <script></script>
     * tag in the source, so all we have to do is parses it and put the found json data into an array.
     *
     * @param  string $username
     * @return array|mixed|null Decoded json data as an array or null if something went wrong.
     */
    private function load_instagram_data( $username ) {
        $response = wp_remote_get( $this->instagram_url . $username, array(
            'sslverify' => false,
            'timeout'   => self::HTTP_TIMEOUT
        ));

        if ( ! is_wp_error( $response ) && $response['response']['code'] == 200 ) {
            preg_match( '/window\._sharedData = (.*)\;<\/script>/', $response['body'], $matches);
            $json = json_decode( $matches[1], true );

            if ( ( $json ) && is_array( $json ) ) {
                return $json;
            }
        }

        return null;
    }

    /**
     * Looks for a template file in STYLESHEETPATH and then TEMPLATEPATH.
     * If none of them exists, load the default template from the plugin dir.
     *
     * It's implied that you use the included template as just that, a template, and copy it to your theme directory.
     * Change and style as you see fit.
     *
     * @param array $data Will contain all Instagram data and is available to the template.
     */
    private function load_template( $data ) {
        $template = locate_template( array( $this->template_name ) );
        if ( $template ) {
            require $template;
        } else {
            require plugin_dir_path( __FILE__ ) . $this->template_name;
        }
    }
}

function ssiw_register_widget() {
    register_widget( 'SuperSimpleInstagramWidget' );
}
add_action( 'widgets_init', 'ssiw_register_widget' );
