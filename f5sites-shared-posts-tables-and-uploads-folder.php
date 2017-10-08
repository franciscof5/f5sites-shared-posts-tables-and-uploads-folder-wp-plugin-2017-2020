<?php
/**
 * Plugin Name: F5 Sites | Shared Posts Tables & Uploads Folder
 * Plugin URI: https://www.f5sites.com/software/wordpress/f5sites-shared-posts-tables-and-uploads-folder/
 * Description: Hacks WordPress databases, sharing posts and taxonomies tables for multiple wp install under the same database, by default wp only can share tables users and usermeta. Made for use in fnetwor
 * Tags: woocommerce, pdfcatalog, wpmu, buddypress, bp xprofile, projectimer ready
 * Version: 1.0
 * Author: Francisco Matelli Matulovic
 * Author URI: https://www.franciscomat.com
 * License: GPLv3
 */

 
if(!is_network_admin()) {
	#if ( !is_woocommerce() ) {
		#add_action( 'wp_head', 'force_database_aditional_tables_share', 10, 2 );
		#if (! is_admin() )
		//add_action( 'after_setup_theme', 'force_database_aditional_tables_share', 10, 2 );	
	#}	
	/*add_action( 'after_setup_theme', 'force_database_aditional_tables_share', 10, 2 );
	add_action( 'woocommerce_loaded', 'force_database_aditional_tables_share', 10, 2 );
	add_action( 'plugins_loaded', 'force_database_aditional_tables_share', 10, 2 );
	add_action( 'setup_theme', 'force_database_aditional_tables_share', 10, 2 );
	add_action( 'pre_get_sites', 'force_database_aditional_tables_share', 10, 2 );
	add_action( 'woocommerce_integrations_init', 'force_database_aditional_tables_share', 10, 2 );
	add_action( 'register_sidebar', 'force_database_aditional_tables_share', 10, 2 );*/
	//add_action( 'before_woocommerce_init', 'force_database_aditional_tables_share', 10, 2 );
	//add_action( 'switch_blog', 'force_database_aditional_tables_share', 10, 2 );
	//add_action( 'before_woocommerce_init', 'setWooFilters', 10, 2 );
	//woocommerce_loaded
	//if(!is_buddypress())
	//if(is_admin())
	//$_SERVER['REQUEST_URI'];
	//$inPageWoo = strpos($_SERVER['REQUEST_URI'], "woocommerce");
	//$inPageProduto = strpos($_SERVER['REQUEST_URI'], "produto");
	//echo $inPageCrateTeams;die;
	//if(is_admin() || $inPageWoo || $inPageProduto)
	//var_dump(is_admin() || is_tax() || is_archive() || function_exists("is_woocommerce"));die;
	add_action( 'pre_get_posts', 'force_database_aditional_tables_share', 10, 2 );//FOR BLOG POSTS
	
	//THIS IS ONLY FOR A BUDDYPRESS SPECIFIC PAGE INTEGRATION
	$inPageCrateTeams = strpos($_SERVER['REQUEST_URI'], "create");
	if(!$inPageCrateTeams) {	
		add_action( 'switch_blog', 'force_database_aditional_tables_share', 10, 2 );
		//on franciscomat tests it shows need for 2 filters at same time
		add_action( 'plugins_loaded', 'force_database_aditional_tables_share', 10, 2 );
	} else {
		add_action( 'plugins_loaded', 'force_database_aditional_tables_share', 10, 2 );
	}
	
	//in admin always share
	if(is_admin()) {
		add_action( 'switch_blog', 'force_database_aditional_tables_share', 10, 2 );
		add_action( 'plugins_loaded', 'force_database_aditional_tables_share', 10, 2 );
	}
	

	//shared upload dir, comment to un-share
	add_filter( 'upload_dir', 'shared_upload_dir' );
	//
	add_filter( 'nav_menu_link_attributes', 'filter_function_name', 10, 3 );
	#Work in progress for buddypress integration, some problems might occur with sensitivy user data, like user_blogs table, making impossible to cross-share between multiple installs, but it is a good start point
	#add_action( 'bp_loaded', 'buddypress_tables_share', 10, 2 );

	#ULTIMO ESTAGIO, precisa funcionar os widgets e os nav links abaixo dos posts e tudo fica joia 2017-10-06
	#add_action( 'widgets_init', 'asda', 10, 2 );	
}
/*function asda() {
	#die;
	global $wp_the_query;
	var_dump($wp_the_query);
	die;
}*/
function filter_function_name( $atts, $item, $args ) {
    // Manipulate attributes
    //var_dump($args);
    return $atts;
}

#$settings = {#	"post_table":"1fnetwork_posts", "postmeta_table":"1fnetwork_postmeta"#}

function set_shared_database_schema() {
	global $wpdb;
	#var_dump($wpdb);
	#$olddbname = DB_NAME;
	#$wpdb = new wpdb( DB_USER, DB_PASSWORD, "pomodoros", DB_HOST );
	#define('DB_NAME', "pomodoros2");
	#require_wp_db();
	#wp_set_wpdb_vars();
	#die;
	#var_dump(dirname(__FILE__)."/config.php");
	if(is_file(dirname(__FILE__)."/config.php")) {
		include("config.php");
		#echo $config["posts"];die;
	} else {
		echo "F5 Sites Shared posts warning: please enter plugin folder and configure it: copy config.example.php and rename it to config.php with you changes";die;	
	}
	#var_dump($config);die;
	#
	$wpdb->posts 				= $config["posts"];
	$wpdb->postmeta 			= $config["postmeta"];
	#
	$wpdb->terms 				= $config["terms"];
	$wpdb->term_taxonomy 		= $config["term_taxonomy"];
	$wpdb->term_relationships 	= $config["term_relationships"];
	$wpdb->termmeta 			= $config["termmeta"];
	$wpdb->taxonomy 			= $config["taxonomy"];
	#
	$wpdb->comments 			= $config["comments"];
	$wpdb->commentmeta 			= $config["commentmeta"];
	#
	$wpdb->links 				= $config["links"];
	#var_dump($wpdb->posts);die;
}
function setWooFilters() {
	if(function_exists("is_woocommerce")) {
		add_action( 'woocommerce_before_shop_loop_item', 'redirect_to_correct_store_in_shop_loop_title' );
		add_filter( 'woocommerce_loop_add_to_cart_link', 'redirect_to_correct_store_in_shop_loop_cart', 10, 2 ); 
		#
		add_action( 'woocommerce_before_main_content', 'redirect_to_correct_store_in_single_view', 10, 2);
	}
}
function force_database_aditional_tables_share($query) {
	#revert previous altered function
	//global $interrupt_database_share;
	//if($interrupt_database_share)return;

	global $wpdb;
	global $wp_the_query;
	#echo !is_int($query);
	#echo !isset($query);die;
	

	if(!isset($query)) {
		#echo "NAO VEIO QUERY";
		if($wp_the_query!=NULL) {
			#echo "SETOU A GLOBAL";
			$query = $wp_the_query;
		}
	} else {
		#echo "VEIO QUERY";
		if(is_object($query)) {
			#var_dump($query);
			#echo "VEIO UM OBJETO (query)"; #OBS SEMPRE VEM UM INTEIRO, por isso use NUMERICO
			#if($wp_the_query!=NULL) {
			#	echo "SETOU A GLOBAL";
			#	$query = $wp_the_query;
			#}
		} else {
			#var_dump($query);
			#echo "SETOU A GLOBAL PORQUE VEIO VAZIO";
			$query = $wp_the_query;
			#var_dump($query);
			#return;
		}
	}
	
	#else
	#	return;
	#echo $query;
	#if($GLOBALS['table_prefix'])
	#$wpdb->base_prefix=$GLOBALS['table_prefix'];

	#settype($query, "WP_Query");
	setWooFilters();

	$types_not_shared = array("projectimer_focus", "projectimer_rest", "projectimer_lost");
		
	#var_dump($query->query["post_type"]);
	#die;
	if(isset($query->query["post_type"])) {
		$type = $query->query["post_type"];
	} else {
		#return;
		$type="notknow";#(post or page problably, but maybe menu)
	}
	
	global $last_type;
	
	#echo "type: ".$type. ", in array types not shared:".in_array($type, $types_not_shared).", last_type: ".$last_type;
	#echo "<hr />";
	#if($last_type=="notknow") {
		if(!in_array($type, $types_not_shared)) {
			#echo("$type is shared");
			set_shared_database_schema();

			if($type!="page" and $type!="nav_menu_item") {
				filter_posts_by_cat($query);
			}
		} else {
			#echo("$type is not not shared");
			revert_database_schema("");
		}
	#}
	$last_type=$type;

	#var_dump($wp_the_query);

	#get current domain
	#get post type
	#var_dump(gettype($query));
	#if($query) {
	#if(gettype($query)=="object") {
		#echo "TTTTTTTTTTTT".$query->post_type;
		#var_dump($query);
		

		#$types_shared = array("notknow", "post", "page", "product");
		
		#if(in_array($type, $types_shared)) {
			#if(gettype($query)=="WP_Query") {
				#set_shared_database_schema();
				#if(gettype($query)=="object") {
					#filter_posts_by_cat($query);
				#}
			#}
		#}
		#
		
		#$is_defined_post_type = isset($type);
	#}	
			
		#$query->query_vars["category__in"] = $current_server_name_shared_category_id;
		#$query->query_vars["category__in"] = 357;
		
		#if(!$is_defined_post_type)
			


		/*if($type=="projectimer_focus") {
			$wpdb = new wpdb( DB_USER, DB_PASSWORD, "pomodoros", DB_HOST );
			revert_database_schema("pomodoros_");
		}*/

		#the magic happens here
		#if(in_array($type, $types_shared)) {
			#
			#set_shared_database_schema();
			#
			
			#specific queryes
			#if($type=="product" || $type=="notknow") { # && !is_pdf_catalog
				#WHY THAT? CANT REMEMBER if(gettype($query)!="string" && gettype($query)!="integer") {
					#filter_posts_by_cat();
				#}
			#}
			
			#revert_database_schema();
		#}
		#if($type=="projectimer_focus") {
			#global $wpdb;
			#var_dump($wpdb);
			#$wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST );
			#revert_database_schema();
		#}
	#}
	#if ( $query->is_home ) {#product, shop_order, shop_coupon
}
function filter_posts_by_cat($queryReceived) {
	global $wp_the_query;
	global $query;
	
	//var_dump($wp_the_query);
	//if($wp_the_query!=NULL)
	//	$query = $wp_the_query;
	//else
	//	return;
	//$query = $wp_the_query;

	if($queryReceived==NULL) {
		if($wp_the_query!=NULL) {
			$query = $wp_the_query;
		} else if($query!=NULL) {
			$query = $query;
		} else {
			return;
		}
	} else {
		$query = $queryReceived;
	}
		//var_dump($query);
		//$query = new WP_Query($query);
	
	//die;
	$current_server_name = $_SERVER['SERVER_NAME'];
	#$current_server_name = "br.f5sites.com";
	$current_server_name_shared_category = get_category_by_slug($current_server_name);
	//echo "1ASDASDAS";
	

	if(isset($current_server_name_shared_category->term_id))
		$current_server_name_shared_category_id = $current_server_name_shared_category->term_id;
	else
		return;
	#echo "12222DAS";

	$type="notknow";#(post or page problably)	
	if(isset($query->query["post_type"])) 
		$type = $query->query["post_type"];
	//else
		
	
	$is_category = "";
	if(isset($query->query["is_category"]))
		$is_category = $query->query["is_category"];
	//else
			
	
	$category = "";
	if(isset($query->query["product_cat"]))
		$category = $query->query["product_cat"];

	$product_tag = "";
	if(isset($query->query["product_tag"]))
		$product_tag = $query->query["product_tag"];

	//else
	//	$category = "";
	#$is_pdf_catalog = isset($_GET["pdfcat"]);
	#$is_pdf_catalog_all = isset($_GET["all"]);
	
	#if(function_exists("is_woocommerce"))
	#$is_woocommerce = is_woocommerce();
	#else
	#$is_woocommerce = false;
	#
	#if($is_woocommerce || $is_pdf_catalog) { # || $is_pdf_catalog
		#if(!$is_pdf_catalog_all and !is_admin()) #not
		#$query->set( 'product_cat', $current_server_name );
		#echo $current_server_name;die;
	#} else {
		
		#if(isset($current_server_name_shared_category_id)) {
		#if(!isset($category) && !isset($is_category)) {
		
		//if(!isset($category)) {
		//if(gettype($query)=="WP_Query") {
		
		//if(!$is_woocommerce) {
			
			#echo "333333S:".$current_server_name_shared_category_id;
			#$current_server_name_shared_category_id = 3;
			//var_dump($query);die;

			//if($type=="product")
			
			//if($product_tag!="")
			//	$query->set( 'product_tag', $product_tag );	
			//var_dump("<br /> type: ".$type. ", <br /> is_shop: ".is_shop(). ", <br /> domain: ".$current_server_name. ", <br /> is_woocommerce(): ".is_woocommerce(). ", <br /> pdfcat: ". ", <br /> gettype: ".gettype($query).", <br /> current_server_name_shared_category_id:".$current_server_name_shared_category_id.", <br /> category:".$category.", <br /> is_category:".$is_category.", <br /> typequery:".gettype($query)." <br />product_tag:".$product_tag);
			
			#if($category!="") {
				
				//$query->set( 'cat', $current_server_name_shared_category_id );
				//$query->set( 'product_cat', $current_server_name_shared_category_id );
			
			if($category=="") {
				if(!is_admin()) {
					##IS FRONT-END
					#var_dump("product_tag: $product_tag");
					#var_dump($query);

					if($product_tag!="") {
						$query->set( 'product_tag', $product_tag );
					} else {
						$query->set( 'cat', $current_server_name_shared_category_id );
						#$query->set( 'category__in', $current_server_name_shared_category_id );
						#var_dump($query);
					}
					//if($type!="product")
					//if($type!="projectimer_focus")
					
				}
			} else {
			}
				
			#$query->set( 'category', $current_server_name_shared_category_id );
		//}
			
			//	$query->set( 'product_cat', $current_server_name_shared_category_id );
			/*if(is_admin()) {
				if(is_woocommerce())
					$query->set( 'product_cat', $current_server_name_shared_category_id );
			}*/
	#}
}
function buddypress_tables_share() {
	#
	global $wpdb;
	#var_dump($bp);
	$wpdb->base_prefix = "1fnetwork_";
	#var_dump($wpdb->base_prefix);die;
	/*
	$bp->bp_activity 			="1fnetwork_bp_activity";
	$bp->bp_activity_meta 	="1fnetwork_bp_activity_meta";
	$bp->bp_friends 			="1fnetwork_bp_friends";
	$bp->bp_group_livechat 	="1fnetwork_bp_group_livechat";
	$bp->bp_group_livechat_online 	="1fnetwork_bp_group_livechat_online";
	$bp->bp_groups 			="1fnetwork_bp_groups";
	$bp->bp_groups_members 	="1fnetwork_bp_groups_members";
	$bp->bp_messages_messages ="1fnetwork_bp_messages_messages";
	$bp->bp_messages_meta 	="1fnetwork_bp_messages_meta";
	$bp->bp_messages_notices 	="1fnetwork_bp_messages_notices";
	$bp->bp_messages_recipients ="1fnetwork_bp_messages_recipients";
	$bp->bp_notifications 	="1fnetwork_bp_notifications";
	$bp->bp_user_blogs 		="1fnetwork_bp_user_blogs";
	$bp->bp_user_blogs_blogmeta 	="1fnetwork_bp_user_blogs_blogmeta";
	$bp->bp_xprofile_data 	="1fnetwork_bp_xprofile_data";
	$bp->bp_xprofile_fields 	="1fnetwork_bp_xprofile_fields";
	$bp->bp_xprofile_groups 	="1fnetwork_bp_xprofile_groups";
	$bp->bp_xprofile_meta 	="1fnetwork_bp_xprofile_meta";
	/*$wpdb->bp_activity 			="1fnetwork_bp_activity";
	$wpdb->bp_activity_meta 	="1fnetwork_bp_activity_meta";
	$wpdb->bp_friends 			="1fnetwork_bp_friends";
	$wpdb->bp_group_livechat 	="1fnetwork_bp_group_livechat";
	$wpdb->bp_group_livechat_online 	="1fnetwork_bp_group_livechat_online";
	$wpdb->bp_groups 			="1fnetwork_bp_groups";
	$wpdb->bp_groups_members 	="1fnetwork_bp_groups_members";
	$wpdb->bp_messages_messages ="1fnetwork_bp_messages_messages";
	$wpdb->bp_messages_meta 	="1fnetwork_bp_messages_meta";
	$wpdb->bp_messages_notices 	="1fnetwork_bp_messages_notices";
	$wpdb->bp_messages_recipients ="1fnetwork_bp_messages_recipients";
	$wpdb->bp_notifications 	="1fnetwork_bp_notifications";
	$wpdb->bp_user_blogs 		="1fnetwork_bp_user_blogs";
	$wpdb->bp_user_blogs_blogmeta 	="1fnetwork_bp_user_blogs_blogmeta";
	$wpdb->bp_xprofile_data 	="1fnetwork_bp_xprofile_data";
	$wpdb->bp_xprofile_fields 	="1fnetwork_bp_xprofile_fields";
	$wpdb->bp_xprofile_groups 	="1fnetwork_bp_xprofile_groups";
	$wpdb->bp_xprofile_meta 	="1fnetwork_bp_xprofile_meta";*/
	# OLD WP SETTINGS
	#$wpdb->categories="1fnetwork_categories"; OLD WP SETTINGS
	#$wpdb->term_post2cat="1fnetwork_post2cat"; OLD WP SETTINGS
}
function revert_database_schema($prefix) {
	#
	global $wpdb;
	#in wp-config and wp-settings.php
	if($prefix=="") {
		if(table_prefix)
			$prefix=table_prefix;
		else
			$prefix = "pomodoros";	
	}
	
	#
	$wpdb->posts=$prefix."posts";
	$wpdb->postmeta=$prefix."postmeta";
	#
	$wpdb->comments=$prefix."comments";
	$wpdb->commentmeta=$prefix."commentmeta";
	#
	$wpdb->links=$prefix."links";
	#
	$wpdb->terms=$prefix."terms";
	$wpdb->term_taxonomy=$prefix."term_taxonomy";
	$wpdb->term_relationships=$prefix."term_relationships";
	$wpdb->termmeta=$prefix."termmeta";
	$wpdb->taxonomy=$prefix."taxonomy";
	# OLD WP SETTINGS
	#$wpdb->categories="1fnetwork_categories"; OLD WP SETTINGS
	#$wpdb->term_post2cat="1fnetwork_post2cat"; OLD WP SETTINGS
}


/* WOOCOMMERCE FNETWORK */
function redirect_to_correct_store_in_shop_loop_title() {
	$purl = get_product_correct_url_by_id();
	if(!$purl) {
		$purl = get_permalink();
	} else {
		echo "<marquee> ! FNETWORK - LOJA PARCEIRA ! </marquee>";
	}
	echo '<a href="' . $purl . '" class="woocommerce-LoopProduct-link">';
}

function redirect_to_correct_store_in_shop_loop_cart( $array, $int ) { 
	$purl = get_product_correct_url_by_id();
	if(!$purl) {
		return $array;
	} else {
		echo "Produto disponível somente em: <br />";
		$parse = parse_url($purl);
		echo "<a href=$purl class='button product_type_booking add_to_cart_button'>".$parse['host']."</a>";
	}
}

#
function get_product_correct_url_by_id($postid=0) {
	$current_server_name = $_SERVER['SERVER_NAME'];
	$categories=array();
	if($postid==0) {
		global $post;
		$product_id = $post->ID;
	}
	$terms = wp_get_post_terms( $product_id, 'product_cat' );
	#$terms = wp_get_post_terms( $product_id, 'product_cat' );
	foreach ( $terms as $term ) {
		$categories[] = $term->name;
		#child of
		if($term->parent==235) {
			#echo $parent_slug = get_term_by('id', $term->parent, 'product_cat');
			#if($parent_slug==$current_server_name) {
			$current_product_base_url=$term->name;
		}
	}
	if ( !in_array( $current_server_name, $categories ) ) {
		#$prouct_is_being_viewed_outside_home_url
		$perm = get_permalink( $post->ID );
		$fullurlr = str_replace($current_server_name, $current_product_base_url, $perm);
		#var_dump($fullurlr);
		#wp_redirect($fullurlr);
		return $fullurlr = str_replace($current_server_name, $current_product_base_url, $perm);
	} else {
		return;
	}
}

function redirect_to_correct_store_in_single_view () {
	if(is_product()) {
		$purl = get_product_correct_url_by_id();
		if($purl) {
			wp_redirect($purl);
		}
	}
}

/* UPLOAD FOLDER */
function shared_upload_dir( $dirs ) {
    $dirs['baseurl'] = network_site_url( '/wp-content/uploads/shared-wp-posts-uploads-dir' );
    $dirs['basedir'] = ABSPATH . 'wp-content/uploads/shared-wp-posts-uploads-dir';
    $dirs['path'] = $dirs['basedir'] . $dirs['subdir'];
    $dirs['url'] = $dirs['baseurl'] . $dirs['subdir'];

    return $dirs;
}

?>
