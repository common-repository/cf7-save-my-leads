<?php

// ADD NEW COLUMN
function leads_custom_columns_head($defaults) {
//    $defaults['id'] = 'ID';
    $defaults['leadcf7id'] = 'Created By CF7 Shortcode';
    $defaults['leadpostid'] = 'Created By Post';
    $defaults['leadstatus'] = 'Lead Status';
    $defaults['leadhandledby'] = 'Handled By?';
	return $defaults;
}

// SHOW THE FEATURED IMAGE
function leads_custom_columns_content($column_name, $post_ID) {
	global $post;
    global $current_user;
	switch ( $column_name ) {
//		case 'id':
//			echo $post_ID;
//			break;
        case 'leadcf7id':
            $mPostID = get_post_meta($post_ID, "_leads_created_by_cf7_id", true);
            $mTitle = get_the_title($mPostID);
            $mTagTitle = (!empty($mTitle))?(' title="'.$mTitle.'"'):('');
			echo '<a href="admin.php?page=wpcf7&post='.$mPostID.'" target="_blank" title="Show and Edit this Contact">[contact-form-7 id="'.$mPostID.'"'.$mTagTitle.']</a>';
			break;
        case 'leadpostid':
            $mPostID = get_post_meta($post_ID, "_leads_created_in_post_id", true);
            $mPostTitle = get_the_title($mPostID);
            $mThePostTitle = (!empty($mPostTitle))?($mPostTitle):('Post-id="'.$mPostID.'"');
            $mPermalink = get_permalink($mPostID);
            if(!empty( $mPermalink))
			    echo '<a href="'.$mPermalink.'" target="_blank" title="View this Post">'.$mThePostTitle.'</a>';
            else
                echo $mThePostTitle;
			break;
        case 'leadstatus':
            $mStatus = get_post_meta($post_ID, "leads_status", true);
            $leads_status = is_null($mStatus)?'not-attempted':$mStatus;
			echo ucwords(str_replace('-',' ',$leads_status));
			break;
        case 'leadhandledby':
            $handled_by_string = get_post_meta($post_ID, "leads_handled_by", true);
            $handled_by_user = get_user_by( 'login', $handled_by_string );
            if(!empty($handled_by_user)){
                $handled_by_user_link = get_edit_user_link($handled_by_user->ID);
                if(!empty($handled_by_user_link)){
                    echo '<a href="'.$handled_by_user_link.'" target="_blank" title="Show and Edit this User">'.$handled_by_string.'</a>';
                    break; 
                }
            }
            if(!empty($handled_by_string))
                echo $handled_by_string;
            else
                echo '<span class="handled-new" data-user="'.$current_user->user_login.'" >NEW</span>';
			break;   
	}
}
add_filter('manage_leads_posts_columns', 'leads_custom_columns_head');
add_action('manage_leads_posts_custom_column', 'leads_custom_columns_content', 10, 2);