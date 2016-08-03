<?php

add_theme_support('automatic-feed-links');

// Registers Navigation menu
add_action('init','register_menu');
function register_menu(){
    register_nav_menu('main-menu',__('Navigation'));
}

// Changes default excerpt length to avoid breaking posts on front page.
add_filter('excerpt_length', 'my_excerpt_length');
function my_excerpt_length($length) {
    return 10; 
}

add_action( 'widgets_init', 'my_register_sidebars' );

function my_register_sidebars() {

	/* Register the upcoming events "sidebar" */
	register_sidebar(
		array(
			'id' => 'upcoming-events',
			'name' => __( 'Events Sidebar' ),
            'description' => __( 'Home page list of upcoming events' ),
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>'
		)
	);
    

}

function add_flexslider() { // display attachment images as a flexslider gallery on single posting
     
    global $post; // don't forget to make this a global variable inside your function
    
    $attachments = get_children(array('post_parent' => $post->ID, 'order' => 'ASC', 'orderby' => 'menu_order', 'post_type' => 'attachment', 'post_mime_type' => 'image', ));
    
    if ($attachments) { // if there are images attached to posting, start the flexslider markup
        
        echo '<div class="flexslider">';
        echo '<ul class="slides">';
    
        foreach ( $attachments as $attachment_id => $attachment ) { // create the list items for images with captions
        
            echo '<li>';
            echo wp_get_attachment_image($attachment_id, 'large');
            echo '<p>';
            echo get_post_field('post_excerpt', $attachment->ID);
            echo '</p>';
            echo '</li>';
            
        }
    
        echo '</ul>';
        echo '</div>';
        
    } // end see if images
    
} // end add flexslider


// Get Child Pages 
function get_child_pages() {
	
	global $post;
	
	rewind_posts(); // stop any previous loops 
	query_posts(array('post_type' => 'page', 'posts_per_page' => -1, 'post_status' => publish,'post_parent' => $post->ID,'order' => 'ASC','orderby' => 'menu_order')); // query and order child pages 
    
	while (have_posts()) : the_post(); 
	
		$childPermalink = get_permalink( $post->ID ); // post permalink
		$childID = $post->ID; // post id
		$childTitle = $post->post_title; // post title
		$childExcerpt = $post->post_excerpt; // post excerpt
        
		echo '<article id="page-excerpt-'.$childID.'" class="page-excerpt">';
		echo '<h3><a href="'.$childPermalink.'">'.$childTitle.' &raquo;</a></h3>';
		echo '<p>'.$childExcerpt.' <a href="'.$childPermalink.'">Read More&nbsp;&raquo;</a></p>';
		echo '</article>';
        
	endwhile;
	
	wp_reset_query(); // reset query
        
}