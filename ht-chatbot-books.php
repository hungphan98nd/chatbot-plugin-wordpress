
<?php
// Function to search data according to user's question
function chatbot_search_books_by_question() {
    global $wpdb;

    // Kiểm tra câu hỏi có chứa từ khóa "Nhà xuất bản" hay không
    if (!isset($_POST['question']) || empty($_POST['question'])) {
        wp_send_json_error('Không có câu hỏi.');
        wp_die();
    }

    $question = sanitize_text_field($_POST['question']);

    // Tìm kiếm sách theo nhà xuất bản
    if (strpos($question, 'Nhà xuất bản') !== false) {
        $publisherName = trim(str_replace('Nhà xuất bản', '', $question));
        $table_name = $wpdb->prefix . 'books';

        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE publisherName LIKE %s LIMIT 5", '%' . $wpdb->esc_like($publisherName) . '%'
            )
        );  

        if (empty($results)) {
            $logo_url = plugins_url('assets/images/logo.png', __FILE__);
            $error_message = '
                <img src="' . esc_url($logo_url) . '" alt="Logo" class="logo-main logo-message">
                <div class="text-message">
                    Không tìm thấy thông tin bạn muốn tìm kiếm. Vui lòng nhập lại.
                </div>
            ';
            wp_send_json_error($error_message);
        } else {
            wp_send_json_success($results);
        }
    } else {
        $logo_url = plugins_url('assets/images/logo.png', __FILE__);
        $error_message = '
            <img src="' . esc_url($logo_url) . '" alt="Logo" class="logo-main logo-message">
            <div class="text-message">
                Vui lòng nhập thông tin bạn muốn tìm kiếm.
            </div>  
        ';
        wp_send_json_error($error_message);
    }   

    wp_die();
}

add_action('wp_ajax_chatbot_search_books', 'chatbot_search_books_by_question');
add_action('wp_ajax_nopriv_chatbot_search_books', 'chatbot_search_books_by_question');


// Function enqueue script cho chatbot load ajax
function chatbot_enqueue_search_books_script() {
    // Enqueue JavaScript file (chatbot-search-books.js)
    wp_enqueue_script(
        'chatbot-search-books', // Tên script
        plugin_dir_url(__FILE__) . 'assets/js/chatbot-search-books.js',
        ['jquery'],
        '1.0',
        true
    );

    wp_localize_script('chatbot-search-books', 'chatbotAjax', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'logoUrl' => plugins_url('assets/images/logo.png', __FILE__),
    ]);
}
add_action('wp_enqueue_scripts', 'chatbot_enqueue_search_books_script');