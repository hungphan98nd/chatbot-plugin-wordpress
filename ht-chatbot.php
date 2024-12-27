<?php
/*
Plugin Name: Ht-Chatbot
Plugin URI: https://htecom.vn/ht-chatbot
Description: Chatbot Plugin is a powerful tool that integrates AI-powered chatbots directly into your WordPress website. With 24/7 customer support capabilities, this plugin helps automate frequently asked questions, enhance user engagement, and improve the overall customer experience.
Version: 1.0.0
Author: Jason Phan
Author URI: https://htecom.vn
License: 1.0.0
Text Domain: ht-chatbot
*/

// block direct access
if (!defined('ABSPATH')) {
    exit;
}

// declare url
define('HT_CHATBOT_PLUGIN_PATH', plugin_dir_path(__FILE__));

// active and ds deactivate plugin
register_activation_hook(__FILE__, 'ht_chatbot_activate');
register_deactivation_hook(__FILE__, 'ht_chatbot_deactivate');

// Perform actions when activing plugin
function ht_chatbot_activate() {
    chatbot_conversations_table(); 
    create_books_table();
}

function ht_chatbot_deactivate() {
    // Perform actions when deactivating plugin
}

function chatbot_add_admin_menu() {
    $capability = (current_user_can('shop_manager')) ? 'shop_manager' : 'manage_options';

    add_menu_page(
        'Chatbot Settings',
        'Chatbot',
        $capability,
        'chatbot',
        'chatbot_admin_page',
        'dashicons-format-status',
        11
    );
}
add_action('admin_menu', 'chatbot_add_admin_menu');

function ht_chatbot_frontend_interface() {
    // get rrl file template
    ob_start();
    include HT_CHATBOT_PLUGIN_PATH . 'templates/ht-chatbot-interface.php';
    return ob_get_clean();
}
add_shortcode('ht_chatbot', 'ht_chatbot_frontend_interface');

// Html  
function ht_chatbox_render_html() { ?>
    <!-- Button Icon Messenger -->
    <div id="chat-toggle" class="chat-toggle">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="30" height="30" fill="#FFFFFF">
	        <path d="M12 0C5.373 0 0 4.943 0 11.048c0 3.448 1.552 6.49 4.059 8.55v3.694c0 .384.437.598.747.376l4.352-3.27c.938.236 1.927.365 2.842.365 6.627 0 12-4.943 12-11.048S18.627 0 12 0zm5.768 7.598l-3.16 5.094c-.18.292-.547.389-.843.218l-2.834-1.715a.5.5 0 0 0-.527 0l-4.501 2.722c-.4.242-.887-.232-.638-.646l3.16-5.093c.18-.292.547-.389.843-.218l2.834 1.715a.5.5 0 0 0 .527 0l4.501-2.722c.4-.242.887.232.638.646z"/>
	    </svg>	
    </div>	

    <div id="chatbot-message" class="chatbox">
    	<div class="chat-header">
    		<div class="header-left">
    			<?php $img_url = plugins_url('assets/images/logo.png', __FILE__); ?>
				<img class="logo-main" src="<?php echo esc_url($img_url); ?>" alt="Logo">
				<span class="chat-title">Htecom</span>
    		</div>
    		<button class="chatbot-ai-button-close">&times;</button>
    	</div>
    	<div class="chat-body">
    		<div class="message received default">
    			Xin chào! Tôi là người hỗ trợ AI Chat Bot của Htecom. Tôi có thể giúp gì cho bạn?
    		</div>
    		<div class="ai-chatbot-questions-default">
    			<div class="lable">Các câu hỏi thường gặp:</div>
    			<div class="ai-chatbot-questions questions-default-1">Tôi muốn tìm sách của nhà xuất bản Giáo Dục</div>
    			<div class="ai-chatbot-questions questions-default-2">Tôi muốn tìm sách về lĩnh vực Khoa Học</div>
    			<div class="ai-chatbot-questions questions-default-3">Tôi muốn tìm sách Thời gian là vàng</div>
    		</div>
    	</div>
    	<div id="chat-footer" class="chat-footer">
    		<input type="text" id="chatInput" placeholder="Nhập câu hỏi..." autocomplete="off">
    		<button id="sendButton">
    			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white" width="24px" height="24px">
    				<path d="M2,21L23,12L2,3v7l15,2l-15,2V21z"/>
    			</svg>
    		</button>
    	</div>
    </div>
<?php }

add_action('wp_footer', 'ht_chatbox_render_html');

function ht_chatbot_enqueue_assets() {
    wp_enqueue_style('ht-chatbot-style', plugin_dir_url(__FILE__) . 'assets/css/chatbox-style.css' , array(), null, 'all');
    wp_enqueue_style('ht-admin-style', plugin_dir_url(__FILE__) . 'assets/css/admin-style.css' , array(), null, 'all');
    wp_enqueue_script('ht-chatbot-script', plugin_dir_url(__FILE__) . 'assets/js/main.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'ht_chatbot_enqueue_assets');

// Create new table for database
function chatbot_conversations_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'ht_chatbot_conversations';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        user_id BIGINT(20) UNSIGNED DEFAULT NULL,
        session_id VARCHAR(255) NOT NULL,
        message TEXT NOT NULL,
        sender ENUM('user', 'chatbot') NOT NULL,
        timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY session_id (session_id),
        KEY user_id (user_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// Create new table books
function create_books_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'books';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        title VARCHAR(255) NOT NULL,
        description TEXT NOT NULL,
        category VARCHAR(255) NOT NULL,
        keyword VARCHAR(255) NOT NULL,
        publisherDate DATE NOT NULL,
        publisherName VARCHAR(255) NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}

// reaquire file
require_once(HT_CHATBOT_PLUGIN_PATH . 'includes/admin-page.php');
require_once (HT_CHATBOT_PLUGIN_PATH . 'ht-chatbot-books.php');