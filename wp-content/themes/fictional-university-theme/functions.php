<?php

require get_theme_file_path('/inc/like-route.php'); //get custom (like) api url

require get_theme_file_path('/inc/search-route.php'); //get custom (search) api url

//customize rest API
function university_custom_rest(){
    register_rest_field('post', 'authorName', array(
        'get_callback' => function() {return get_the_author();} //create and add authorName field
    ));
    register_rest_field('note', 'userNoteCount', array(
        'get_callback' => function() {return count_user_posts(get_current_user_id(), 'note');} //count how many post 'notes' the current user has posted
    ));
}
add_action('rest_api_init', 'university_custom_rest'); //First argument is wordpress you want to hook up | Second aregument is the dunction you want to call

function pageBanner($args = NULL) {
    //php logic
    if(!$args['title']) {
        $args['title'] = get_the_title();
    }
    if(!$args['subtitle']) {
        $args['subtitle'] = get_field('page_banner_subtitle');
    }
    if(!$args['photo']) {
        if(get_field('page_banner_background_image')) {
            $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
        }else {
            $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
        }
    }
?>
<div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
    <div class="page-banner__content container container--narrow">
        <?php  //print_r($pageBannerImage); //debugg information?>
      <h1 class="page-banner__title"><?php echo $args['title'];?></h1>
      <div class="page-banner__intro">
        <p><?php echo $args['subtitle']; ?></p>
      </div>
    </div>  
  </div>
<?php    
}

function university_files() {
    //Loads Google Maps api (loads javascript that lives in google servers)
    wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyCqfx_qP-JtTrsNUwDb_tV_J9w5zZaKBGo', NULL, '1.0', true);
    
    //loads javascript
    wp_enqueue_script('main-university-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, microtime(), true);
    
    //loads fonts
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    
    //loads icons from font awesome
    wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    
    //loads ccs files / Loads css on frontend of our website
    wp_enqueue_style('university_main_styles', get_stylesheet_uri(), NULL, microtime());
    
    //Will let us output javascript data into the source url of the webpage
    wp_localize_script('main-university-js', 'universityData', array( //generate root url
        'root_url' => get_site_url(),
        'nonce' => wp_create_nonce('wp_rest') //wp creates a random number for our user session
    ));
}
add_action('wp_enqueue_scripts', 'university_files');


function university_features() {
    //register_nav_menu('headerMenuLocation', 'Header Menu Location');//Register Main Menu
    //register_nav_menu('footerLocationOne', 'Footer Location One');//Footer Menu one
    //register_nav_menu('footerLocationTwo', 'Footer Location Two'); //Footer Menu two
    
    add_theme_support('title-tag');//wordpress generates title tag
    add_theme_support('post-thumbnails'); //Enable feature images (tumbnails) for blog post
    add_image_size('professorLandscape', 400, 260, true); //Crop image | (Random Name, width, height, crop) | single-program.php | - Regenerate Thumbnail
    add_image_size('professorPortrait', 480, 650, true); // Crop image | single-professor.php | - Regenerate Thumbnail
    add_image_size('pageBanner', 1500, 350, true); // Crop Image Banner
}
//after_setup_theme -> fires after the theme is loaded
add_action('after_setup_theme', 'university_features');

//Manipulate queries for the archives
function university_adjust_queries($query) { //pass a query object

    //customized the query for the archive-campus.php (All Campuses)
        if(!is_admin() AND is_post_type_archive('campus') AND $query->is_main_query()) {
            $query->set('posts_per_page', -1);
     }

    //customized the query for the archive-program.php (All Programs)
    if(!is_admin() AND is_post_type_archive('program') AND $query->is_main_query()) {
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
        $query->set('posts_per_page', -1);
    }

    //customized the query for the archive-event.php (All Events)
    if(!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) { //is_main_query only work in current url
        $today = date('Ymd');
        $query->set('meta_key', 'event_date');
        $query->set('orderby','meta_value_num');
        $query->set('order','ASC');
        $query->set('meta_query', array( //excludes events that took place in the pass
            array( //Only return events if the date (key) is equal or greater than today's day (value)
                'key' => 'event_date', //custom field
                'compare' => '>=',
                'value' => $today, //today's current date
                'type' => 'numeric'
            )
        ));
    }
    
}
//Fires after the query variable object is created,
add_action('pre_get_posts', 'university_adjust_queries');

//In order to talk to the google map service(uses javascript) I need a google map api key
function universityMapKey($api) {
    $api['key'] = 'AIzaSyCqfx_qP-JtTrsNUwDb_tV_J9w5zZaKBGo'; //Google map api key
    return $api;
}
add_filter('acf/fields/google_map/api', 'universityMapKey');



//Redirect subscriber accounts out of admin and onto homepage
add_action('admin_init', 'redirectSubToFrontend');

function redirectSubToFrontend() {
    $ourCurrentUser = wp_get_current_user();
    if(count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0]=='subscriber') {
        wp_redirect(site_url('/'));
        exit;
    }
}

//Hide the black top admin bar for subscribers
add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar() {
    $ourCurrentUser = wp_get_current_user();
    if(count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0]=='subscriber') {
        show_admin_bar(false);
    }
}

//Customize Login Screen
add_filter('login_headerurl', 'ourHeaderUrl'); //Change url on wp login/signup page

function ourHeaderUrl(){
    return esc_url(site_url('/'));
}

add_action('login_enqueue_scripts', 'ourLoginCSS');

function ourLoginCSS(){
    //loads ccs files
    wp_enqueue_style('university_main_styles', get_stylesheet_uri(), NULL, microtime());

      //loads google fonts
    wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
}

//login screen
add_filter('login_headertitle', 'ourLoginTitle'); 

function ourLoginTitle() {
    return get_bloginfo('name'); //fetch the official site name from database
}

//Force Note Post to be "Private" | Create Note (MyNotes.js)
add_filter("wp_insert_post_data", 'makeNotePrivate', 10, 2); // Filter | intercept a request before it gets store in db
//wp_insert_post_data runs when you try to insert and update a post in the db
function makeNotePrivate($data, $postarr) {
    if($data['post_type'] == 'note'){
        if(count_user_posts(get_current_user_id(), 'note') > 4 AND !$postarr['ID']){ //limit post note type
            die("You have reached your note limit.");
        }
        $data['post_content'] = sanitize_textarea_field($data['post_content']);
        $data['post_title'] = sanitize_text_field($data['post_title']);
    }
    if($data['post_type'] == 'note' AND $data['post_status'] != 'trash'){
        $data['post_status'] = "private"; 
    }
    return $data;
}

?>