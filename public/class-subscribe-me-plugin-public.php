<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://mahesh-d.wisdmlabs.net
 * @since      1.0.0
 *
 * @package    Subscribe_Me_Plugin
 * @subpackage Subscribe_Me_Plugin/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Subscribe_Me_Plugin
 * @subpackage Subscribe_Me_Plugin/public
 * @author     Mahesh Dubal <mahesh.dubal@wisdmlabs.com>
 */
class Subscribe_Me_Plugin_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Subscribe_Me_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Subscribe_Me_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/subscribe-me-plugin-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Subscribe_Me_Plugin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Subscribe_Me_Plugin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/subscribe-me-plugin-public.js', array('jquery'), $this->version, false);
	}



	//TO define shortcode
	public function email_subscriber_form_shortcode_fun()
	{
		add_shortcode('my-shortcode', array($this, 'email_subscriber_form_shortcode'));
	}

	//Callback for shortcode
	function email_subscriber_form_shortcode()
	{
		$output = '<div class="wrap subs-wrap">
                    <form class="subscribe-me-form" method="post">
                        <input type="hidden" name="action" value="subs_form">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" required/><br />
                        <input type="submit" name="submit" value="Subscribe"/>
                    </form>
                </div>';
		return $output;
	}


	//To show form on head section of every page
	function add_shortcode_to_header()
	{
		echo do_shortcode('[my-shortcode]');
	}


	// Save subscriber email to database
function save_subscriber_email()
{

    //To check input pattern 
    if (isset($_POST['email'])) {
        $email = sanitize_email($_POST['email']);
        $pattern = '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/';

        if (preg_match($pattern, $email)) {
            if (isset($_POST['submit'])) {

                $subscribed_mails = get_option('subscribed_mails');

                if (!$subscribed_mails) {
                    $subscribed_mails = array();
                }

                if (in_array($email, $subscribed_mails)) {
                    echo '<script>alert("You are already subscribed!");</script>';
                } else {
                    $subscribed_mails[] = $email;
                    update_option('subscribed_mails', $subscribed_mails);

                    // Display a success message
                    echo '<script>alert("You have been subscribed Successfully!");</script>';

                }
            }
        } else {
            //For invalid email
            echo '<script>alert("Please Enter a valid email!");</script>';
        }
    }
}
}
