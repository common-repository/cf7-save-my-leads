<?php

function leads_created_by_cf7_id_filter_RequestAdmin($request) {
	if( isset($_GET['_leads_created_by_cf7_id']) && !empty($_GET['_leads_created_by_cf7_id']) ) {
		$request['meta_key'] = '_leads_created_by_cf7_id';
		$request['meta_value'] = $_GET['_leads_created_by_cf7_id'];
	}
	return $request;
}

function leads_created_by_cf7_id_filter_RestrictManagePosts() {
	global $wpdb;
	$items = $wpdb->get_col("
		SELECT DISTINCT meta_value
		FROM ". $wpdb->postmeta ."
		WHERE meta_key = '_leads_created_by_cf7_id'
		ORDER BY meta_value
        ");
	?>

    <select name="_leads_created_by_cf7_id" id="_leads_created_by_cf7_id">
        <option value="">All CF7 Contacts</option>
        <?php foreach ($items as $item) { ?>
            <option value="<?php echo esc_attr( $item ); ?>" <?php if(isset($_GET[ '_leads_created_by_cf7_id']) && !empty($_GET[ '_leads_created_by_cf7_id']) ) selected($_GET[ '_leads_created_by_cf7_id'], $item); ?>>
                <?php //echo esc_attr($item); ?>
                    <?php 
                    $cf7_title = get_the_title(esc_attr($item));
                    echo  !empty($cf7_title)?$cf7_title:('CF7-id="'.esc_attr($item).'"');
                    ?>
            </option>
            <?php } ?>
    </select>
    <?php
}

function _leads_created_in_posts_filter_RequestAdmin($request) {
	if( isset($_GET['_leads_created_in_post_id']) && !empty($_GET['_leads_created_in_post_id']) ) {
		$request['meta_key'] = '_leads_created_in_post_id';
		$request['meta_value'] = $_GET['_leads_created_in_post_id'];
	}
	return $request;
}

function _leads_created_in_posts_filter_RestrictManagePosts() {
	global $wpdb;
	$items = $wpdb->get_col("
		SELECT DISTINCT meta_value
		FROM ". $wpdb->postmeta ."
		WHERE meta_key = '_leads_created_in_post_id'
        ");
	?>

        <select name="_leads_created_in_post_id" id="_leads_created_in_post_id">
            <option value="">All Posts</option>
            <?php foreach ($items as $item) { ?>
                <option value="<?php echo esc_attr( $item ); ?>" <?php if(isset($_GET[ '_leads_created_in_post_id']) && !empty($_GET[ '_leads_created_in_post_id']) ) selected($_GET[ '_leads_created_in_post_id'], $item); ?>>
                    <?php //echo esc_attr($item); ?>
                        <?php 
                        $cf7_title = get_the_title(esc_attr($item));
                        echo  !empty($cf7_title)?$cf7_title:('Post-id="'.esc_attr($item).'"');
                        ?>
                </option>
                <?php } ?>
        </select>
        <?php
}

function leads_status_posts_filter_RequestAdmin($request) {
	if( isset($_GET['leads_status']) && !empty($_GET['leads_status']) ) {
		$request['meta_key'] = 'leads_status';
		$request['meta_value'] = $_GET['leads_status'];
	}
	return $request;
}

function leads_status_posts_filter_RestrictManagePosts() {
	global $wpdb;
	$items = $wpdb->get_col("
		SELECT DISTINCT meta_value
		FROM ". $wpdb->postmeta ."
		WHERE meta_key = 'leads_status'
		ORDER BY meta_value
        ");
	?>

            <select name="leads_status" id="leads_status">
                <option value="">All Status</option>
                <?php foreach ($items as $item) { ?>
                    <option value="<?php echo esc_attr( $item ); ?>" <?php if(isset($_GET[ 'leads_status']) && !empty($_GET[ 'leads_status']) ) selected($_GET[ 'leads_status'], $item); ?>>
                        <?php echo ucwords(str_replace('-',' ',esc_attr($item))); ?>
                    </option>
                    <?php } ?>
            </select>
            <?php
}

function leads_handled_by_posts_filter_RequestAdmin($request) {
	if( isset($_GET['leads_handled_by']) && !empty($_GET['leads_handled_by']) ) {
		$request['meta_key'] = 'leads_handled_by';
		$request['meta_value'] = $_GET['leads_handled_by'];
	}
	return $request;
}

function leads_handled_by_posts_filter_RestrictManagePosts() {
	global $wpdb;
	$items = $wpdb->get_col("
		SELECT DISTINCT meta_value
		FROM ". $wpdb->postmeta ."
		WHERE meta_key = 'leads_handled_by'
		ORDER BY meta_value
        ");
	?>

    <select name="leads_handled_by" id="leads_handled_by">
        <option value="">Handled By All</option>
        <?php
        foreach ($items as $item) {
            if(!empty(esc_attr($item))) {
            ?>
            <option value="<?php echo esc_attr( $item ); ?>" <?php if(isset($_GET[ 'leads_handled_by']) && !empty($_GET[ 'leads_handled_by']) ) selected($_GET[ 'leads_handled_by'], $item); ?>>
                <?php echo esc_attr($item); ?>
            </option>
            <?php
            }
        }
        ?>
    </select>
    <?php
}

if( is_admin() && isset($_GET['post_type']) && $_GET['post_type'] == 'leads' ) {
    add_filter('request', 'leads_created_by_cf7_id_filter_RequestAdmin');
	add_filter('restrict_manage_posts', 'leads_created_by_cf7_id_filter_RestrictManagePosts');
    
    add_filter('request', '_leads_created_in_posts_filter_RequestAdmin');
	add_filter('restrict_manage_posts', '_leads_created_in_posts_filter_RestrictManagePosts');
    
	add_filter('request', 'leads_status_posts_filter_RequestAdmin');
	add_filter('restrict_manage_posts', 'leads_status_posts_filter_RestrictManagePosts');
    
    add_filter('request', 'leads_handled_by_posts_filter_RequestAdmin');
	add_filter('restrict_manage_posts', 'leads_handled_by_posts_filter_RestrictManagePosts');
}


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\// Quick Edit //\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

add_action( 'quick_edit_custom_box', 'display_custom_leads_quick_edit', 10, 2 );

function display_custom_leads_quick_edit( $column_name, $post_type ) {
    static $printNonce = TRUE;
    if ( $printNonce ) {
        $printNonce = FALSE;
        wp_nonce_field( plugin_basename( __FILE__ ), 'leads_edit_nonce' );
    }

    ?>
    <fieldset class="inline-edit-col-left">
      <div class="inline-edit-col column-<?php echo $column_name; ?>">
        <label class="inline-edit-group">
        <?php 
         switch ( $column_name ) {            
            case 'leadstatus':
                echo '<span class="title">Lead Status?</span><span class="input-text-wrap">'.
                            '<select name="leads_status" id="leads_status_set" style="width: 100%;" >'.
                                '<option value="not-attempted" >[ Not Attempted ] – you haven’t tried to reach the lead</option>'.
                                '<option value="attempted" >[ Attempted ] – you have tried (person-to-person) to reach the lead</option>'.
                                '<option value="contacted" >[ Contacted ] – you have had a person-to-person dialog with the lead</option>'.
                                '<option value="new-opportunity" >[ New Opportunity ] – new opportunity identified (convert lead)</option>'.
                                '<option value="additional-contact" >[ Additional Contact ] – new contact at existing opportunity (convert lead)</option>'.
                                '<option value="disqualified" >[ Disqualified ] – never going to be a prospect for your product or service because this lead is the incorrect industry, company, contact or data</option>'.
                        '</select>'.
                '</span>';
                break;
                
            case 'leadhandledby':
                echo '<span class="title">Handled By?</span><span class="input-text-wrap"><input type="text" name="leads_handled_by" /></span>';
                break;
         }
        ?>
        </label>
      </div>
    </fieldset>
    <?php
}

add_action( 'save_post', 'save_leads_meta_quick_edit' );

function save_leads_meta_quick_edit( $post_id ) {
    /* in production code, $slug should be set only once in the plugin,
       preferably as a class property, rather than in each function that needs it.
     */
    $slug = 'leads';
    if ( !isset($_POST['post_type'])  || $slug !== $_POST['post_type'] ) {
        return;
    }
    if ( !current_user_can( 'edit_post', $post_id ) ) {
        return;
    }
    $_POST += array("{$slug}_edit_nonce" => '');
    if ( !wp_verify_nonce( $_POST["{$slug}_edit_nonce"],
                           plugin_basename( __FILE__ ) ) )
    {
        return;
    }



    if ( isset( $_REQUEST['leads_status'] ) && !empty( $_REQUEST['leads_status'] ) ) {
        update_post_meta($post_id, 'leads_status', $_REQUEST['leads_status']);
    } else {
        update_post_meta($post_id, 'leads_status', 'not-attempted');
    }
    if ( isset( $_REQUEST['leads_handled_by'] ) && !empty( $_REQUEST['leads_handled_by'] )) {
        update_post_meta($post_id, 'leads_handled_by', $_REQUEST['leads_handled_by']);
    } else {
        global $current_user;
        update_post_meta($post_id, 'leads_handled_by', $current_user->user_login);
    }
}