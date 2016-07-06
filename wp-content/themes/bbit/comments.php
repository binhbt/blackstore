<div class="widget">
<?php
// Do not delete these lines
	if (!empty($_SERVER['SCRIPT_FILENAME']) && 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']))
		die ('Please do not load this page directly. Thanks!');

	if ( post_password_required() ) { 
		return;
	}
?>

<?php if ( have_comments() ) : ?>

<div class="phdr">Bình luận (<?php comments_number(__('0'), __('1'), __('%'));?>)</div>

<?php wp_list_comments('type=comment&callback=bbit_comment'); ?>

<?php if(is_user_logged_in()) : ?>
<div class="content-bottom"> 
	<ul>  
		<li><?php previous_comments_link('Bình luận cũ hơn'); ?></li> 
		<li><?php next_comments_link('Bình luận mới hơn'); ?></li> 
	</ul> 
</div>
<?php endif; ?>

<?php endif; ?>

<?php if ( comments_open() ) : ?>

<div class="phdr">Đăng bình luận</div>
<div class="list1">
<div id="respond">
	<div id="cancel-comment-reply"><?php cancel_comment_reply_link(); ?></div>

	<?php if ( get_option('comment_registration') && !is_user_logged_in() ) : ?>
	<p>Bạn phải <a href="<?php echo wp_login_url( get_permalink() ); ?>">Đăng nhập</a> để gửi bình luận.</p>
	<?php else : ?>

	<form action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post">

		<?php if ( is_user_logged_in() ) : ?>

		<p>Chào <a href="<?php echo get_option('siteurl'); ?>/wp-admin/profile.php"><?php echo $user_identity; ?></a>, mời bạn để lại một lời bình luận.</p>
		<textarea name="comment" id="comment" cols="22" rows="4" tabindex="4"></textarea><br>	
		<button name="submit" type="submit" id="submit">Gửi</button>
		<?php comment_id_fields(); ?>
		<?php do_action('comment_form', $post->ID); ?>

		<?php else : ?>
        <div id="cmm">
		<input type="text" name="author" id="author" placeholder="Tên của bạn" value="<?php echo esc_attr($comment_author); ?>" size="22" tabindex="1" class="form">
		<br>
		<input type="text" name="email" id="email" placeholder="Email" value="<?php echo esc_attr($comment_author_email); ?>" size="22" tabindex="2" class="form">
        </div>		
		<textarea style="width: 98%" name="comment" id="comment" placeholder="Nội dung bình luận" cols="22" rows="4" tabindex="4" class="form"></textarea>
		<br>
		<button name="submit" type="submit" id="submit">Gửi</button>
		<?php comment_id_fields(); ?>
		<?php do_action('comment_form', $post->ID); ?>

		<?php endif; ?>

	</form>

	<?php endif;?>

<?php endif; ?>
</div></div>
</div>