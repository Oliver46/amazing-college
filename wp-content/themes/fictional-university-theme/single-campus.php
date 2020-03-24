<?php
//the_ID(); //Program ID
get_header();

//single post
    while(have_posts()) {
    //Keep looping as long as we still have post

        the_post(); //keep track of each post      
        
        pageBanner(); //Display Banner
?>

  <div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('campus'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Campuses</a> <span class="metabox__main"><?php the_title(); ?></span></p>
    </div>  
     <div class="generic-content"><?php the_content(); ?></div>

        <?php $mapLocation = get_field('map_location');?>

    <!-- Google Map API -->
     <div class="acf-map">
            
            <div class="marker" data-lat="<?php echo $mapLocation['lat']?>" data-lng="<?php echo $mapLocation['lng']?>">
            <h3><?php the_title();?></h3>
            <?php echo $mapLocation['address'];?>
            </div>
                
    </div> 

     <?php
          //Displays Professor related to the program
          $relatedPrograms = new WP_query(array( //Query data from database loop through
            'post_type' => 'program',
            'posts_per_page' => -1,  //Displays all of them
            //'orderby' => 'rand',
            'orderby' => 'title', // order by title
            'order' => 'ASC',
            'meta_query' => array( //Don't display old date events
                array( //FILTER | Only return events if the array of related programs contains (LIKE) the ID number of the current post
                  'key' => 'related_campus',
                  'compare' => 'LIKE',
                  'value' => '"' . get_the_ID() . '"'
                )
            )
          ));
          
          if($relatedPrograms->have_posts()) { //if program has related upcoming events
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">Programs Available At this Campus</h2>';
            
            echo '<ul class="min-list link-list">';
            while($relatedPrograms->have_posts()) {
              $relatedPrograms->the_post(); ?>
  
              <li >
                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
              </li>
  
          <?php   
            }
            echo '</ul>';
          }

          //when running multiple custom queries
          wp_reset_postdata(); //Reset data and global variables like IDs

        ?>
  </div>    
<?php        
    }
get_footer();
?>