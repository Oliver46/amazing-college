<?php
get_header();

//single post
    while(have_posts()) {
    //Keep looping as long as we still have post

        the_post(); //keep track of each post    
        
        pageBanner(); //Display Banner
?>

  <div class="container container--narrow page-section">

     <div class="generic-content">
         <div class="row group">

             <div class="one-third">
                 <?php the_post_thumbnail('professorPortrait'); ?>
            </div>

            <div class="two-thirds">
                <?php
                //new instance LIKE
                $likeCount = new WP_Query(array(
                    'post_type' => 'like',
                    'meta_query' => array( //liked_professor_id == professor_id
                        array(
                            'key' => 'liked_professor_id', //custom field
                            'compare' => '=', //matches
                            'value' => get_the_ID(), //professor_id
                        )
                    )
                ));

                $exitStatus = 'no';

                if(is_user_logged_in()) {
                    $existQuery = new WP_Query(array(
                        'author' => get_current_user_id(), //current user viewing page | Get ID
                        'post_type' => 'like', //check like post
                        'meta_query' => array( //liked_professor_id == professor_id
                            array(
                                'key' => 'liked_professor_id', //custom field
                                'compare' => '=', //matches
                                'value' => get_the_ID(), //professor_id
                            )
                        )
                    ));
                    //LIKE RED HEART
                    if($existQuery->found_posts) { //As long as it is greater than 0 it will evaluate to true
                        $exitStatus = 'yes';
                    }
                }

                //print_r($existQuery);

                ?>
                <span class="like-box" data-like="<?php echo $existQuery->posts[0]->ID;?>" data-professor="<?php the_ID();?>" data-exists="<?php echo $exitStatus;?>">
                    <i class="fa fa-heart-o" aria-hidden="true"></i>
                    <i class="fa fa-heart" aria-hidden="true"></i>
                    <span class="like-count">
                        <?php
                            //found_posts gives us the total number of posts | it ignores pagination
                         echo $likeCount->found_posts;
                        ?></span>
                </span>
                <?php the_content(); ?>
            </div>

        </div>
     </div>  
     <?php
     //Displays programs realted to the Professor
      $relatedPrograms = get_field('related_programs');
      if($relatedPrograms){ //if related fields has data print it
          echo '<hr class="section-break">';
          echo '<h2 class="headline headline--medium">Subject(s) Taught</h2>';
          echo '<ul class="link-list min-list">';
          //print_r($relatedPrograms); //array
          foreach($relatedPrograms as $program){ ?>
          <li><a href="<?php echo get_the_permalink($program);?>"><?php  echo get_the_title($program); ?></a></li>
      <?php 
          }  
          echo '</ul>';   
      } 
       ?>
  
  </div>    
<?php        
    }
get_footer();
?>