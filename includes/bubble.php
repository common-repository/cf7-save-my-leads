<?php

function add_leads_menu_bubble() {
    
    global $menu;
    
    $leads_count = 0;
    
    $args = array(
                'post_type' => 'leads',
                'posts_per_page' => -1,
                'meta_query'	=> array(
                                            'relation'		=> 'OR',
                                            array(
                                                'key'	 	=> 'leads_handled_by',
                                                'value'	  	=> '- - -',
                                                'compare' 	=> 'LIKE',
                                            ),
                                            array(
                                                'key'	  	=> 'leads_handled_by',
                                                'value'	  	=> '',
                                                'compare' 	=> '=',
                                            ),
                                        ),
            );

    $the_query = new WP_Query( $args );
    
    if ( $the_query->have_posts() ) {
        $leads_count = $the_query->post_count;
    }
    wp_reset_postdata();

    

    foreach ( $menu as $key => $value ) {

        if (strpos($menu[$key][2], "edit.php?post_type=leads") === 0){
            
            if ( $menu[$key][2] == 'edit.php?post_type=leads' ) 
                $menu[$key][2] .= '&orderby=date&order=desc';
            
            if ( $leads_count > 0) {
                $menu[$key][0] .= ' <span class="update-plugins"><span class="plugin-count">'.$leads_count.'</span></span>';
            }
            
            return;
        }

    }

    

}
add_action( 'admin_menu', 'add_leads_menu_bubble' );