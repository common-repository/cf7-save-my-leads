<?php

function process_form( $cf7 ) {
     $submission = WPCF7_Submission::get_instance();

    if ( $submission ) {
        
        $posted_data = $submission->get_posted_data();
        
        //GET URL AND ID OF POST
		$url = $submission->get_meta( 'url' );
        $postid = url_to_postid( $url );
    
        $post_id = wp_insert_post(
            array(
                'comment_status'  => 'closed',
                'ping_status'   => 'closed',
                'post_status'   => 'publish',
                'post_title'   => 'lead item' ,
                'post_type'   => 'leads'
            )
        );

        add_post_meta($post_id, '_leads_created_by_cf7_id', $cf7->id() , true);
        add_post_meta($post_id, '_leads_created_in_post_id', $postid , true);
        add_post_meta($post_id, 'leads_handled_by', "", true);
        add_post_meta($post_id, 'leads_status', "not-attempted", true);
        
        foreach($posted_data as $key=>$val)
        {
            if($key[0]!='_'){

                if(is_array($val) )
                    $val =implode(" , ", array_flatten($val));

                add_post_meta($post_id, 'leads_'.$key, $val, true);

            }else{
                add_post_meta($post_id, $key, $val, true);
            }
        }
    
    }
}
add_action( 'wpcf7_before_send_mail', 'process_form' );

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

function array_flatten($array) { 
  if (!is_array($array)) { 
    return false; 
  } 
  $result = array(); 
  foreach ($array as $key => $value) { 
    if (is_array($value)) { 
      $result = array_merge($result, array_flatten($value)); 
    } else { 
      $result[$key] = $value; 
    } 
  } 
  return $result; 
}