<?php

function add_lead_logo_meta_box() {		
	add_meta_box(
		'logo_meta_box', // $id
		'Developed by bosonet', // $title
		'show_leads_logo_meta_box', // $callback function
		'leads', // $screen
		'side', // $context
		'high' // $priority
	);
}
add_action( 'add_meta_boxes', 'add_lead_logo_meta_box' );		
function show_leads_logo_meta_box() {		
        echo '<a href="http://www.bosonet.com/" target="_blank"><img src="'.plugin_dir_url(__FILE__) .'img/bosonet-logo.png" alt="bosonet"></a>';		
}		
//==========================================================================================================
function add_lead_meta_box() {
	add_meta_box(
		'lead_fields_meta_box', // $id
		'Fields of Lead', // $title
		'show_leads_meta_box', // $callback function
		'leads', // $screen
		'normal', // $context
		'high' // $priority
	);
}
add_action( 'add_meta_boxes', 'add_lead_meta_box' );
function show_leads_meta_box() {
	global $post;  
    global $current_user;
    
    $meta = get_post_meta( $post->ID, '', true ); 
    
    echo '<input type="hidden" name="_leads_meta_box_nonce" value="'.wp_create_nonce( basename(__FILE__) ).'">';
    
    foreach($meta as $key=>$val)
    {
        if(strpos($key, 'leads_') === 0 && $key !='leads_handled_by' && $key !='leads_status' && strpos($key, 'leads_comments_') !== 0){
    //        echo $key . ' : ' . $val . '<br/>';
            echo '<p>'.'<label for="'.$key.'_">'.ucwords(str_replace('-',' ',substr($key,6))).'</label><br>'.
            '<textarea name="'.$key.'" id="'.$key.'" rows="1" cols="30" style="width:100%;">'.(is_array($val) ? implode(",", $val) : $val).'</textarea></p>';
               
        }
    }
}
//==========================================================================================================
function add_lead_handled_data_meta_box() {
	add_meta_box(
		'lead_handled_data_meta_box', // $id
		'Handled Data', // $title
		'show_lead_handled_data_meta_box', // $callback function
		'leads', // $screen
		'normal', // $context
		'high' // $priority
	);
}
add_action( 'add_meta_boxes', 'add_lead_handled_data_meta_box' );
function show_lead_handled_data_meta_box() {
	global $post;  
    global $current_user;
    
    $meta = get_post_meta( $post->ID, '', true ); 
    
    $leads_status = is_null($meta['leads_status'])?'not-attempted':implode(" ",$meta['leads_status']);
    $leads_handled_by =  is_null($meta['leads_handled_by'])?'':implode(" ",$meta['leads_handled_by']);
     echo '<p><table style="width: 100%;" >'.
         
         '<tr>'.
                '<th><label for="leads_status">Status:</label></th>'.
                '<th>'.
                     '<select name="leads_status" id="leads_status" style="width: 100%;" >'.
         
         '<option value="not-attempted" '. (( $leads_status == 'not-attempted' ) ? 'selected="selected"' : '') .'>[ Not Attempted ] – you haven’t tried to reach the lead</option>'.
         '<option value="attempted" '. (( $leads_status == 'attempted' ) ? 'selected="selected"' : '') .'>[ Attempted ] – you have tried (person-to-person) to reach the lead</option>'.
         '<option value="contacted" '. (( $leads_status == 'contacted' ) ? 'selected="selected"' : '') .'>[ Contacted ] – you have had a person-to-person dialog with the lead</option>'.
         '<option value="new-opportunity" '. (( $leads_status == 'new-opportunity' ) ? 'selected="selected"' : '') .'>[ New Opportunity ] – new opportunity identified (convert lead)</option>'.
         '<option value="additional-contact" '. (( $leads_status == 'additional-contact' ) ? 'selected="selected"' : '') .'>[ Additional Contact ] – new contact at existing opportunity (convert lead)</option>'.
         '<option value="disqualified" '. (( $leads_status == 'disqualified' ) ? 'selected="selected"' : '') .'>[ Disqualified ] – never going to be a prospect for your product or service because this lead is the incorrect industry, company, contact or data</option>'.
         
                    '</select>'.
                '</th>'.
            '</tr>'.
         
            '<tr>'.
                '<th><label for="leads_handled_by">Handled By:</label></th>'.
                '<th style="    padding-left: 2px;"><textarea name="leads_handled_by" id="leads_handled_by" rows="1" cols="30" >'.
                 (((strcmp($leads_handled_by,"- - -")==0) || (strcmp($leads_handled_by,"")==0)) ? $current_user->user_login : $leads_handled_by) .
                 '</textarea></th>'.
            '</tr>'.
            
         '</table></p>';
}

//==========================================================================================================
    function add_lead_comments_meta_box() {
	add_meta_box(
		'lead_comments_meta_box', // $id
		'Comments', // $title
		'show_lead_comments_meta_box', // $callback function
		'leads', // $screen
		'normal', // $context
		'low' // $priority
	);
}
add_action( 'add_meta_boxes', 'add_lead_comments_meta_box' );
function show_lead_comments_meta_box() {
	global $post;  
    
    $meta = get_post_meta( $post->ID, '', true ); 
    
    //comments
    $comments_nun = 0;
    foreach($meta as $key=>$val)
    {
        if(strpos($key, 'leads_comments_') === 0){
    //        echo $key . ' : ' . $val . '<br/>';
            echo '<p>'.'<textarea name="'.$key.'" id="'.$key.'" rows="1"  style="width:100%;" readonly>'.(is_array($val) ? implode(",", $val) : $val).'</textarea></p>';
            
            $num = intval(substr($key,15));
            if($num > $comments_nun)
                $comments_nun = $num;   
        }
    }
    echo '<div id="new_comments" data-comments-number="'.$comments_nun.'">';
    echo '</div>';
    
    echo '<div id="add_comment_button"><input type="button" class="button button-primary button-large" value="Add Comment" onclick="add_comment()" ></div>';//<div onclick="add_comment()">Add Comment</div>';
    
}
    //=======================================================================================================
 //=======================================================================================================
    function save_leads_meta( $post_id ) {   
	// verify nonce
	if (!isset($_POST['_leads_meta_box_nonce']) || !wp_verify_nonce( $_POST['_leads_meta_box_nonce'], basename(__FILE__) ) ) {
		return $post_id; 
	}
	// check autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}
	// check permissions
	if ( 'page' === $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_page', $post_id ) ) {
			return $post_id;
		} elseif ( !current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}  
	}
	
    

    global $current_user;
    
    foreach($_POST as $key => $value) {
        if (strpos($key, 'leads_') === 0) {
            $old = get_post_meta( $post_id, $key, true );
            $new = $value;
            
            if ( $new && $new !== $old ) {
                if (strpos($key, 'leads_new_leads_comments_') === 0) {
                    $new = $current_user->user_login. current_time('  -  d/m/Y  H:i:s' ). "&#13;&#10;&#13;&#10;".$new;
                    $key = substr($key,10);
                    update_post_meta( $post_id, $key, $new );
                }
                else
                    update_post_meta( $post_id, $key, $new );
            } elseif ( '' === $new && $old ) {
                //delete_post_meta( $post_id, $key, $old );
            }
                
        }
    }

    
}
add_action( 'save_post', 'save_leads_meta' );

 //=======================================================================================================
 