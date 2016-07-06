<?php
if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

function taqyeem_get_option( $name ) {
	$get_options = get_option( 'taqyeem_options' );
	
	if( !empty( $get_options[$name] ))
		return $get_options[$name];
		
	return false ;
}

function taqyeem_get_review( $position = "review-top" ){

	if( !is_singular() && taqyeem_get_option('taq_singular') ) return false;
	
	global $post ;
	
	$image_style ='stars';
	$users_rate = taqyeem_get_user_rate();
	
	return $users_rate;
}

add_action('wp_ajax_nopriv_taqyeem_rate_post', 'taqyeem_rate_post');
add_action('wp_ajax_taqyeem_rate_post', 'taqyeem_rate_post');
function taqyeem_rate_post(){
	global $user_ID;
	if( taqyeem_get_option('allowtorate') == 'none' || ( !empty($user_ID) && taqyeem_get_option('allowtorate') == 'guests' ) ||	( empty($user_ID) && taqyeem_get_option('allowtorate') == 'users' ) ){ 
		return false ;
	}else{	
		$count = $rating = $rate = 0;
		$postID = $_REQUEST['post'];
		$rate = abs($_REQUEST['value']);
		if($rate > 5 ) $rate = 5;
		
		if( is_numeric( $postID ) ){
			$rating = get_post_meta($postID, 'bbit_user_rate' , true);
			$count = get_post_meta($postID, 'bbit_users_num' , true);
			if( empty($count) || $count == '' ) $count = 0;
			
			$count++;
			$total_rate = $rating + $rate;
			$total = round($total_rate/$count , 2);
			if ( $user_ID ) {
				$user_rated = get_the_author_meta( 'bbit_rated', $user_ID  );

				if( empty($user_rated) ){
					$user_rated[$postID] = $rate;
					
					update_user_meta( $user_ID, 'bbit_rated', $user_rated );
					update_post_meta( $postID, 'bbit_user_rate', $total_rate );
					update_post_meta( $postID, 'bbit_users_num', $count );
					
					echo $total;
				}
				else{
					if( !array_key_exists($postID , $user_rated) ){
						$user_rated[$postID] = $rate;
						update_user_meta( $user_ID, 'bbit_rated', $user_rated );
						update_post_meta( $postID, 'bbit_user_rate', $total_rate );
						update_post_meta( $postID, 'bbit_users_num', $count );
						
						echo $total;
					}
				}
			}else{
				$user_rated = $_COOKIE["bbit_rate_".$postID];
				if( empty($user_rated) ){
					setcookie( 'bbit_rate_'.$postID , $rate , time()+31104000 , '/');
					update_post_meta( $postID, 'bbit_user_rate', $total_rate );
					update_post_meta( $postID, 'bbit_users_num', $count );
				}
			}
		}
	}
    die;
}

function taqyeem_get_user_rate(){
	global $post , $user_ID; 
	$disable_rate = false ;
		
	if( !empty($disable_rate) ){
		$rate_active = false ;
	}
	else{
		$rate_active = ' user-rate-active' ;
	}
		
	$image_style ='stars';
	
	$rate = get_post_meta( $post->ID , 'bbit_user_rate', true );
	$count = get_post_meta( $post->ID , 'bbit_users_num', true );
	if( !empty($rate) && !empty($count)){
		$total = (($rate/$count)/5)*100;
		$totla_users_score = round($rate/$count,2);
	}else{
		$totla_users_score = $total = $count = 0;
	}
	
	if ( $user_ID ) {
		$user_rated = get_the_author_meta( 'bbit_rated' , $user_ID ) ;
		if( !empty($user_rated) && is_array($user_rated) && array_key_exists($post->ID , $user_rated) ){
			$user_rate = round( ($user_rated[$post->ID]*100)/5 , 2);
			return $output = '<span class="user-rating-text">Xếp hạng <span class="taq-score">'.$totla_users_score.'</span>/5 (<span class="votes taq-count">'.$count.'</span> phiếu)</span><div data-rate="'. $user_rate .'" class="user-rate rated-done" title=""><span class="average user-rate-image post-large-rate '.$image_style.'-large"><span style="width:'. $user_rate .'%"></span></span></div>';
		}
	}else{
		$user_rate = $_COOKIE["bbit_rate_".$post->ID] ;
		
		if( !empty($user_rate) ){
			return $output = '<span class="user-rating-text">Xếp hạng <span class="taq-score">'.$totla_users_score.'</span>/5 (<span class="votes taq-count">'.$count.'</span> phiếu)</span><div class="user-rate rated-done" title=""><span class="average user-rate-image post-large-rate '.$image_style.'-large"><span style="width:'. (($user_rate*100)/5) .'%">'.$user_rate.'</span></span></div>';
		}
		
	}
	if( $total == 0 && $count == 0) {
		$user_rate = '';
		return $output = '<span class="user-rating-text">Xếp hạng <span class="taq-score">'.$totla_users_score.'</span>/5 (<span class="votes taq-count">'.$count.'</span> phiếu)</span><div data-rate="'. $total .'" data-id="'.$post->ID.'" class="user-rate'.$rate_active.'"><span class="average user-rate-image post-large-rate '.$image_style.'-large"><span style="width:'. $total .'%">'.$user_rate.'</span></span></div>';
	} else {
		$user_rate = '';
		return $output = '<span class="user-rating-text">Xếp hạng <span class="taq-score">'.$totla_users_score.'</span>/5 (<span class="votes taq-count">'.$count.'</span> phiếu)</span><div data-rate="'. $total .'" data-id="'.$post->ID.'" class="user-rate'.$rate_active.'"><span class="average user-rate-image post-large-rate '.$image_style.'-large"><span style="width:'. $total .'%">'.$user_rate.'</span></span></div>';
		}
}

function taqyeem_shortcode_review( $atts, $content = null ) {
	$output = taqyeem_get_review( 'review-bottom' );
	return $output; 
}
add_shortcode('taq_review', 'taqyeem_shortcode_review');

add_action('wp_head', 'taqyeem_wp_head');
function taqyeem_wp_head() {
	global $taqyeem_typography; 
	?>
<script type='text/javascript'>
/* <![CDATA[ */
var taqyeem = {"ajaxurl":"<?php echo admin_url('admin-ajax.php'); ?>" , "your_rating":"<?php _e( 'Your Rating:' , 'taq' ) ?>"};
/* ]]> */
</script>
<?php
}

?>