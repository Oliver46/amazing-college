<?php
get_header();

//single post
    while(have_posts()) {
    //Keep looping as long as we still have post

        the_post(); //keep track of each post  
        
        pageBanner();
?>

  <div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('event'); ?>"><i class="fa fa-home" aria-hidden="true"></i> Events Home</a> <span class="metabox__main"><?php the_title(); ?></span></p>
    </div>  
     <div class="generic-content"><?php the_content(); ?></div>  
     <?php
      $relatedPrograms = get_field('related_programs');
      if($relatedPrograms){ //if related fields has data print it
          echo '<hr class="section-break">';
          echo '<h2 class="headline headline--medium">Related Programs</h2>';
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