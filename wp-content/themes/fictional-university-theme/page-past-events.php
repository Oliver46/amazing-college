<?php
get_header();
pageBanner(array(
  'title' => 'Pass Events',
  'subtitle' => 'A recap of our past Events'
));
?>

<div class="container container--narrow page-section">
   <?php
    //Display old Events
    $today = date('Ymd');
    $pastEvents = new WP_query(array( //Query data from database loop through
    'paged' => get_query_var('paged', 1),  //get the number at the end of url if there is one.  
    'post_type' => 'event',
    //'posts_per_page' => 1,
    //'orderby' => 'rand',
    'meta_key' => 'event_date', //order by custom field
    'orderby' => 'meta_value_num', // custom data associate with post (custom field)
    'order' => 'ASC',
    'meta_query' => array( //Don't display old date events
        array( //Only return events if the date (key) is equal or greater than today's day (value)
            'key' => 'event_date', //custom field key
            'compare' => '<',
            'value' => $today, //today's current date
            'type' => 'numeric'
        )
    )
    ));

   while($pastEvents->have_posts()) {
    $pastEvents->the_post(); //get the data ready for each post 
    get_template_part('template-parts/content-event');
  
   }
   echo paginate_links(array('total' => $pastEvents->max_num_pages));
   ?>
   
</div>
<?php
get_footer();
?>