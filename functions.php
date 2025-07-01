// Localize script with the AJAX URL
wp_localize_script('ajax-search', 'ajax_object', array(
'ajax_url' => admin_url('admin-ajax.php'),
));

/************************************************************************/
/* AJAX Search */
/************************************************************************/
add_action('wp_ajax_getbytext', 'fetch_products_by_text');
add_action('wp_ajax_nopriv_getbytext', 'fetch_products_by_text');

function fetch_products_by_text() {
global $wpdb;

$search_text = isset($_POST['textword']) ? sanitize_text_field($_POST['textword']) : '';

if (empty($search_text)) {
echo json_encode('<li>' . __('يرجى إدخال كلمة للبحث', 'saint') . '</li>');
wp_die();
}

$args = array(
'post_type' => 'product',
'posts_per_page' => 10,
'post_status' => 'publish'
);

// فلتر مخصص يبحث في بداية عنوان المنتج فقط
$custom_filter = function($where) use ($search_text, $wpdb) {
$like = $wpdb->esc_like($search_text) . '%'; // يبدأ بالكلمة
$where .= $wpdb->prepare(" AND {$wpdb->posts}.post_title LIKE %s", $like);
return $where;
};

add_filter('posts_where', $custom_filter);

$query = new WP_Query($args);

remove_filter('posts_where', $custom_filter);

$results = '';

if ($query->have_posts()) {
while ($query->have_posts()) {
$query->the_post();
$thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') ?: 'https://via.placeholder.com/50';
$results .= '<li><a href="' . get_permalink() . '" class="result-item"><img src="' . esc_url($thumbnail) . '" alt="' . esc_attr(get_the_title()) . '" class="result-thumbnail"><span class="result-title">' . get_the_title() . '</span></a></li>';
}
} else {
$results = '<li>' . __('لا توجد نتائج مطابقة', 'saint') . '</li>';
}

wp_reset_postdata();
echo json_encode($results);
wp_die();
}

/****** for search page***********/

function filter_search_results_to_start_with_title($where) {
global $wpdb;

if (is_search() && !is_admin()) {
$search_term = get_search_query();
if (!empty($search_term)) {
$like = '%' . $wpdb->esc_like($search_text) . '%';
$where .= $wpdb->prepare(" AND {$wpdb->posts}.post_title LIKE %s", $like);
}
}

return $where;
}
add_filter('posts_where', 'filter_search_results_to_start_with_title');
