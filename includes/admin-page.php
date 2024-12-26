
<?php function chatbot_admin_page() {
     global $wpdb;
    $table_name = $wpdb->prefix . 'techbookapi_items'; ?>

    <div class="hte-api-wrapper">
        <h1>HT Chatbot setting</h1>

        <form method="post" action="options.php">

            <?php submit_button(); ?>
        </form>
    </div>
<?php } ?>
