
<?php
// Hàm xử lý tìm kiếm sách theo câu hỏi của người dùng
function chatbot_search_books_by_question() {
    global $wpdb;

    // Kiểm tra câu hỏi có chứa từ khóa "sách của nhà xuất bản" hay không
    if (!isset($_POST['question']) || empty($_POST['question'])) {
        wp_send_json_error('Không có câu hỏi.');
        wp_die();
    }

    $question = sanitize_text_field($_POST['question']);

    // Tìm kiếm tên nhà xuất bản từ câu hỏi (ví dụ: "sách của nhà xuất bản Giáo Dục")
    if (strpos($question, 'sách của nhà xuất bản') !== false) {
        // Lấy tên nhà xuất bản từ câu hỏi
        $publisherName = trim(str_replace('sách của nhà xuất bản', '', $question));
        
        // Truy vấn tìm sách theo nhà xuất bản
        $table_name = $wpdb->prefix . 'books';
        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE publisherName LIKE %s LIMIT 3",
                '%' . $wpdb->esc_like($publisherName) . '%'
            )
        );

        if (empty($results)) {
            wp_send_json_error('Không tìm thấy sách của nhà xuất bản ' . $publisherName);
        } else {
            wp_send_json_success($results);
        }
    } else {
        wp_send_json_error('Câu hỏi không hợp lệ.');
    }

    wp_die();
}

// Đăng ký action AJAX cho cả người dùng đã đăng nhập và chưa đăng nhập
add_action('wp_ajax_chatbot_search_books', 'chatbot_search_books_by_question');
add_action('wp_ajax_nopriv_chatbot_search_books', 'chatbot_search_books_by_question');


// Hàm enqueue script cho chatbot load ajax
function chatbot_enqueue_search_books_script() {
    // Enqueue JavaScript file (chatbot-search-books.js)
    wp_enqueue_script(
        'chatbot-search-books', // Tên script
        plugin_dir_url(__FILE__) . 'assets/js/chatbot-search-books.js', // Đường dẫn đến file JS trong plugin
        ['jquery'], // Phụ thuộc vào jQuery
        '1.0', // Phiên bản
        true // Đặt vào footer
    );

    // Truyền ajaxUrl từ PHP sang JavaScript
    wp_localize_script('chatbot-search-books', 'chatbotAjax', [
        'ajaxUrl' => admin_url('admin-ajax.php') // URL AJAX của WordPress
    ]);
}
add_action('wp_enqueue_scripts', 'chatbot_enqueue_search_books_script');