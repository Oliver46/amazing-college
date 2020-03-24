<?php
if(!is_user_logged_in()){ //if user is not logged in redirect them
    wp_redirect(esc_url(site_url('/')));
    exit;
}
get_header(); ?>

<?php //single/individual page
    while(have_posts()) {
    //Keep looping as long as we still have post
        the_post(); //keep track of each post
        
        pageBanner(array(
          //'title' => 'Hello there',
          //'subtitle' => 'Hi, this is the subtitle', 
          //'photo' => 'https://images.unsplash.com/photo-1505490096310-204ef067fe6b?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=crop&w=1050&q=80'
        ));
        ?>


  <div class="container container--narrow page-section">
    <div class="create-note">
        <h2 class="headline headline--medium">Create New Note</h2>
        <input class="new-note-title" placeholder="Title">
        <textarea class="new-note-body" placeholder="Your note here..."></textarea>
        <span class="submit-note">Create Note</span>
        <span class="note-limit-message">Note limit reached: delete and existing note to make room for a new one.</span>
    </div>
        <ul class="min-list link-list" id="my-notes">
            <?php 
            $userNotes = new WP_query(array(
               'post_type' => 'note', 
               'posts_per_page' => -1, 
               'author' => get_current_user_id(),//only gives post that where created by the current user
            ));

            while($userNotes->have_posts()){
                $userNotes->the_post(); ?>
                <li data-id="<?php the_ID(); ?>">
                <?php //echo get_current_user_id();
                //echo '<script>console.log(' . json_encode(the_ID()) . ')</script>';
                ?>
                <input readonly class="note-title-field" value="<?php echo str_replace('Private:', '', esc_attr(get_the_title()));?>">
                <span class="edit-note"><i class="fa fa-pencil" aria-hidden="true"></i> Edit</span>
                <span class="delete-note"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</span>
                <textarea readonly class="note-body-field"><?php echo esc_textarea(get_the_content());?></textarea>
                <span class="update-note btn btn--blue btn--small"><i class="fa fa-arrow-right" aria-hidden="true"></i> Save</span>    
                </li>
        <?php    
            }
            ?>
        </ul>

  </div>
        

<?php        
    }
get_footer();
?>