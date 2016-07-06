<?php
if ( !defined( 'DB_NAME' ) ) {
	header( 'HTTP/1.0 403 Forbidden' );
	die;
}

function bbit_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment; ?>
<article class="comment">
    <span <?php if($comment->user_id == 1) {echo'style="color:red"';} ?> class="icon_user"><?php echo get_comment_author_link() ?></span>: 
	<?php comment_text() ?>
	<?php edit_comment_link(__('<em>Chỉnh sửa bình luận</em>'),'  ','') ?>
	<?php if ($comment->comment_approved == '0') : ?>
	<em><?php _e('Bình luận chờ duyệt.') ?></em>
	<?php endif; ?>
</article>
<?php }