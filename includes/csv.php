<?php

$list = array();
$old_mCF7_ID = -1;

$the_query = get_posts('post_type=leads&post_status=publish&posts_per_page=-1&orderby=meta_value%20date&meta_key=_leads_created_by_cf7_id&order=DESC');
foreach ( $the_query as $post ){
    setup_postdata( $post );

        $mCF7_ID = get_post_meta($post->ID, "_leads_created_by_cf7_id", true);
        $mCF7_Title = get_the_title($mCF7_ID);
        $mCF7_TagTitle = (!empty($mCF7_Title))?(" title='".$mCF7_Title."']"):("]");
        $mCF7 = sprintf("[contact-form-7 id='%s'",$mCF7_ID,$mCF7_TagTitle);
        $mPost_ID = get_post_meta($post->ID, "_leads_created_in_post_id", true);
        $mPost_Title = get_the_title($mPost_ID);
        $mPost = "id='".$mPost_ID."'".((!empty($mPost_Title))?(" title='".$mPost_Title."'"):(""));
        $mStatus = get_post_meta($post->ID, "leads_status", true);
        $mLeads_status = (!empty($mStatus))?(ucwords(str_replace('-',' ',$mStatus))):("Not Attempted");
        $mHandled_by = get_post_meta($post->ID, "leads_handled_by", true);
        $mHandled_by_string = (!empty($mHandled_by))? $mHandled_by : "NEW";

    $header = array(
        "Title",
        "Date",
        // "Created By CF7 Shortcode",
        "Created By Post",
        "Lead Status",
        "Handled By?",
        ""
    );
    
    $row = array(
        get_the_title($post->ID),
        get_the_date( 'Y-m-d H:i:s' ,$post->ID),
        // $mCF7,
        $mPost,
        $mLeads_status,
        $mHandled_by_string,
        ""
    );
    
    $meta = get_post_meta( $post->ID, '', true ); 
    
    foreach($meta as $key=>$val)
    {
        if(strpos($key, 'leads_') === 0 && $key !='leads_handled_by' && $key !='leads_status' && strpos($key, 'leads_comments_') !== 0){
            array_push($header,ucwords(str_replace('-',' ',substr($key,6))));
            $value = !empty($val)?str_replace( array("\r", "\n"), " ", $val ):"";
            array_push($row, (is_array($value) ? implode(",", $value) : $value) );
        }
    }
    foreach($meta as $key=>$val)
    {
        if(strpos($key, 'leads_comments_') === 0){
            array_push($row,sprintf("Comment %s:",substr($key,15)));
            $value = !empty($val)?str_replace( array("\r", "\n","&#13;&#10;"), " ", $val ):"";
            array_push($row, (is_array($value) ? implode(",", $value) : $value) );
        }
    }

    if($old_mCF7_ID != $mCF7_ID){
        array_push($list,array()); //new empty line 
        array_push($list,array()); //new empty line 
        array_push($list,array('Created By: ',$mCF7,$mCF7_TagTitle)); //new line  
        array_push($list,array()); //new empty line 
        array_push($list,$header); //new header
        array_push($list,array()); //new empty line 
        $old_mCF7_ID = $mCF7_ID;
    }
    array_push($list,$row); //new row
}
wp_reset_postdata();



$fp = fopen( ABSPATH . '/Save_My_Leads.csv', 'w+');
foreach ($list as $fields) {
    fputcsv($fp, $fields);
}
fclose($fp);
