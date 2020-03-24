<?php get_header(); ?>

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

    <?php
        $theParent =  wp_get_post_parent_id(get_the_ID());
        //if the current page has child display breadcrumb
        if($theParent) { ?>
          <!--  Display if it is a child page  -->
        <div class="metabox metabox--position-up metabox--with-home-link">
        <p><a class="metabox__blog-home-link" href="<?php echo get_permalink($theParent); ?>"><i class="fa fa-home" aria-hidden="true"></i> Back to <?php echo get_the_title($theParent);?></a> <span class="metabox__main"><?php the_title();?></span></p>
        </div>  
    <?php    } ?>    

    <?php
    $testArray = get_pages(array(
      'child_of' => get_the_ID()
    ));
    ?>
   
    <?php if($theParent or $testArray) { ?>      
    <div class="page-links">
      <h2 class="page-links__title"><a href="<?php echo get_permalink($theParent); ?>"><?php echo get_the_title($theParent); ?></a></h2>
      <ul class="min-list">
        <?php
          if($theParent) {
            $findChildrenOf = $theParent;
          }else {
            $findChildrenOf = get_the_ID();
          }
          wp_list_pages(array(
            'title_li' => NULL,
            'child_of' => $findChildrenOf,
          ));
        ?>
      </ul>
    </div>
    <?php }?>
  
    <div class="generic-content">
       <?php /*the_content();*/
       
          get_search_form();

       ?>

    </div>

  </div>
        

<?php        
    }
get_footer();
?>