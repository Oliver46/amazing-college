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
        <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>"><i class="fa fa-home" aria-hidden="true"></i> All Programs</a> <span class="metabox__main"><?php the_title(); ?></span></p>
    </div>  
     <div class="generic-content"><?php /*the_content();*/ the_field('main_body_content'); ?></div>
     <?php
          //Displays Professor related to the program
          $relatedProfessors = new WP_query(array( //Query data from database loop through
            'post_type' => 'professor',
            'posts_per_page' => -1,  //Displays all of them
            //'orderby' => 'rand',
            'orderby' => 'title', // order by title
            'order' => 'ASC',
            'meta_query' => array( //Don't display old date events
                array( //FILTER | Only return events if the array of related programs contains (LIKE) the ID number of the current post
                  'key' => 'related_programs',
                  'compare' => 'LIKE',
                  'value' => '"' . get_the_ID() . '"'
                )
            )
          ));
          
          if($relatedProfessors->have_posts()) { //if program has related upcoming events
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">' . get_the_title() . ' Professors</h2>';
            
            echo '<ul class="professor-cards">';
            while($relatedProfessors->have_posts()) {
              $relatedProfessors->the_post(); ?>
  
              <li class="professor-card__list-item">
                <a class="professor-card" href="<?php the_permalink(); ?>">
                  <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); ?>">
                  <span class="professor-card__name"><?php the_title(); ?></span>
                </a>
              </li>
  
          <?php   
            }
            echo '</ul>';
          }

          //when running multiple custom queries
          wp_reset_postdata(); //Reset data and global variables like IDs


          //print_r(get_the_ID());
          //Display the Upcoming Events that are related to the program
          $today = date('Ymd');
          $homePageEvents = new WP_query(array( //Query data from database loop through
            'post_type' => 'event',
            'posts_per_page' => 2, 
            //'orderby' => 'rand',
            'meta_key' => 'event_date', //order by custom field
            'orderby' => 'meta_value_num', // custom data associate with post (custom field)
            'order' => 'ASC',
            'meta_query' => array( //Don't display old date events
                array( //FILTER | Only return events if the date (key) is equal or greater than today's day (value)
                  'key' => 'event_date', //custom field key
                  'compare' => '>=',
                  'value' => $today, //today's current date
                  'type' => 'numeric'
                ),
                array( //FILTER | Only return events if the array of related programs contains (LIKE) the ID number of the current post
                  'key' => 'related_programs',
                  'compare' => 'LIKE',
                  'value' => '"' . get_the_ID() . '"' //get the id of the page
                )
            )
          ));
          
          if($homePageEvents->have_posts()) { //if program has related upcoming events
            echo '<hr class="section-break">';
            echo '<h2 class="headline headline--medium">Upcoming ' . get_the_title() . ' Events</h2>';
  
            while($homePageEvents->have_posts()) {
              $homePageEvents->the_post(); 
              get_template_part('template-parts/content-event');   
            }
          }

          wp_reset_postdata(); //reset data and global variables

          $relatedCampuses = get_field('related_campus');

          if($relatedCampuses) { // If related campuses exits

            echo '<hr class="section-break">';

            echo '<h2 class="headline headline--medium">'. get_the_title() .' is Available At these Campuses:</h2>';
          
            echo '<ul class="min-list link-list">';

            foreach($relatedCampuses as $campus){ ?>
            <li><a href="<?php echo get_the_permalink($campus); ?>"><?php echo get_the_title($campus); ?></a></li>

      <?php  }
            echo '</ul>';
          }

        ?>
  </div>    
<?php        
    }
get_footer();
?>