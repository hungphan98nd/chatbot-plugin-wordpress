
<?php
// Function to search data according to user's questions
function chatbot_search_books_by_question() {
    global $wpdb;

    // Kiểm tra câu hỏi
    if (!isset($_POST['question']) || empty($_POST['question'])) {
        wp_send_json_error('Không có câu hỏi.');
        wp_die();
    }

    $question = sanitize_text_field($_POST['question']);
    $logo_url = plugins_url('assets/images/logo.png', __FILE__);
    $table_name = $wpdb->prefix . 'books';

    // Hàm xử lý truy vấn
    function search_books($column, $searchValue, $table_name, $logo_url) {
        global $wpdb;
        $results = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE $column LIKE %s LIMIT 5",
                '%' . $wpdb->esc_like($searchValue) . '%'
            )
        );

        if (empty($results)) {
            $error_message = '
                <img src="' . esc_url($logo_url) . '" alt="Logo" class="logo-main logo-message">
                <div class="text-message">
                    Không tìm thấy kết quả phù hợp. Vui lòng thử lại.
                </div>
            ';
            wp_send_json_error($error_message);
        }

        return $results;
    }

    // Xử lý câu hỏi
    $results = null;
    if (strpos($question, 'Nhà xuất bản') !== false) {
        $publisherName = trim(str_replace('Nhà xuất bản', '', $question));
        $results = search_books('publisherName', $publisherName, $table_name, $logo_url);
    } elseif (strpos($question, '') !== false) {
        $category = trim(str_replace('Danh mục', '', $question));
        $results = search_books('category', $category, $table_name, $logo_url);
    } elseif (strpos($question, '') !== false) {
        $title = trim(str_replace('Tên sách', '', $question));
        $results = search_books('title', $title, $table_name, $logo_url);
    } else {
        $error_message = '
            <img src="' . esc_url($logo_url) . '" alt="Logo" class="logo-main logo-message">
            <div class="text-message">
                Vui lòng nhập thông tin bạn muốn tìm kiếm.
            </div>  
        ';
        wp_send_json_error($error_message);
    }

    wp_send_json_success($results);
    wp_die();
}

add_action('wp_ajax_chatbot_search_books', 'chatbot_search_books_by_question');
add_action('wp_ajax_nopriv_chatbot_search_books', 'chatbot_search_books_by_question');


// Function enqueue script cho chatbot load ajax
function chatbot_enqueue_search_books_script() {
    // Enqueue JavaScript file (chatbot-search-books.js)
    wp_enqueue_script(
        'chatbot-search-books', 
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