<?php
//Custom API Endpoints URL (Like.js)
add_action('rest_api_init', 'universityLikeRoutes'); //rest_api_init adds a new custom route or new field to a route

//when click the like heart box javascript will send a request to one of those routes
function universityLikeRoutes() {
    register_rest_route('university/v1','manageLike', array(
        'methods' => 'POST', // Type of http request
        'callback' => 'createLike' // Run function when request is sent to createLike
    )); //url namespace, name of the url, array

    register_rest_route('university/v1','manageLike', array(
        'methods' => 'DELETE', // Type of http request
        'callback' => 'deleteLike' // Run function when request is sent to deleteLike
    )); //url namespace, name of the url, array
}

function createLike($data) {

    if(is_user_logged_in()){
        $professor = sanitize_text_field($data['professorId']);

        $existQuery = new WP_Query(array(
            'author' => get_current_user_id(), //current user viewing page | Get ID
            'post_type' => 'like', //check like post
            'meta_query' => array( //liked_professor_id == professor_id
                array(
                    'key' => 'liked_professor_id', //custom field
                    'compare' => '=', //matches
                    'value' => $professor, //professor_id
                )
            )
        ));
        //like a professor once
        if($existQuery->found_posts == 0 AND get_post_type($professor) == 'professor') { // There is not Like post create one
            return wp_insert_post(array( //return 
                'post_type' => 'like',
                'post_status' => 'publish',
                'post_title' => 'PHP Create Post Test',
                'meta_input' => array(
                    'liked_professor_id' => $professor
                ),
                //'post_content' => 'Hello example 123.'
            ));
        }else{
            die("Invalid professor id");
        }

    }else {
        die("Only Logged in users can create a like.");
    }


}

function deleteLike($data) {
    $likeId = sanitize_text_field($data['like']); 
    
    if(get_current_user_id() == get_post_field('post_author', $likeId) AND get_post_type($likeId) == 'like'){
        wp_delete_post($likeId, true); //delete post
        return 'Congrats, like deleted.';
    }else{
        die("You do not have permission to delete that.");
    }
}