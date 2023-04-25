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
		
		wp_enqueue_script('jquery');
		wp_enqueue_script('my-script', plugin_dir_url(__FILE__) . 'js/myjquery.js', array('jquery'), '1.0', true);
		wp_localize_script('my-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
	
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



	

	//Callback for shortcode
	function email_subscriber_form_shortcode()
	{
		ob_start();
?>
		<div class="wrap subs-wrap mail-form">
			<form id="myform" method="post">
				<input type="hidden" name="action" value="subs_form">
				<label for="email">Email:</label>
				<input type="email" name="email" id="email" required />
				<input type="submit" name="submit" id="subscribe-button" value="Subscribe Me" />
				<div id="success-message"></div>
			</form>
		</div>

<?php
		$output = ob_get_contents();
		ob_get_clean();
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
			$pattern = '/^[a-zA-Z0-9._]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,}$/';

			if (preg_match($pattern, $email)) {
					if (!get_option('subscribed_mails')) {
						add_option('subscribed_mails', array());
					}

					$subscribed_mails = get_option('subscribed_mails');

					if (!$subscribed_mails) {
						$subscribed_mails = array();
					}

					if (in_array($email, $subscribed_mails)) {
						echo '<p>"You are already subscribed!");</p>';
					} else {
						$subscribed_mails[] = $email;
						update_option('subscribed_mails', $subscribed_mails);

						// Display a success message
						echo '<p>You have been subscribed Successfully!</p>';
						
						//To send latest post details
						$this->send_subscription_mail($email);

					}
					
				}
			} else {
				//For invalid email
				echo '<p>aPlease Enter a valid email!</p>';
			}
		}
	}

	function send_subscription_mail($to)
	{
		$subject = 'Congratulations! You are Subscribed';
		$summary = $this->get_daily_post_details();
		$message = 'You are Successfully added to our Daily Update List';
		$message .= "\n\n";
		$message .= "Here are our Top latest Posts";
		$message .= "\n";

		foreach ($summary as $post_data) {
			$message .= 'Title: ' . $post_data['title'] . "\n";
			$message .= 'URL: ' . $post_data['url'] . "\n";
			$message .= "\n";
		}

		$headers = array(
			'From: mahesh.dubal@wisdmlabs.com',
			'Content-Type: text/html; charset=UTF-8'
		);

		wp_mail($to, $subject, $message, $headers);
	}

	function get_daily_post_details()
	{
		//To send latest n posts 
		$args = array(
			'post_type' => 'post',
			'posts_per_page' => get_option('no_of_posts'),
			'post_status' => 'publish'
		);

		$query = new WP_Query($args);
		$posts = $query->posts;
		$mail_list = array();

		foreach ($posts as $post) {
			$post_data = array(
				'title' => $post->post_title,
				'url' => get_permalink($post->ID),
			);
			array_push($mail_list, $post_data);
		}
		return $mail_list;
	}

}
