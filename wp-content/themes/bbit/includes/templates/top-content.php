<div class="postinfo">
	<?php if(get_post_meta($post->ID, 'bbit_thumb', true)) : ?>
        <img width="55" height="55" src="<?php echo get_post_meta($post->ID, 'bbit_thumb', true); ?>" class="thumb wp-post-image" alt="<?php the_title(); ?>">
		<div class="vpro"><h1><?php the_title(); ?></h1>
		<div style="float: right;"><?php edit_post_link('Chỉnh sửa', '<h3 class="right">', '</h3><br>'); ?></div>
		<br>
	<?php else: ?>
	<?php the_post_thumbnail('thumbnail', array('class'=>'thumb wp-post-image' , 'alt' => get_the_title() )); ?>	
	<?php endif; ?>
    <?php if(get_post_meta($post->ID, 'bbit_phathanh', true)): ?>
    <label>Phát hành:</label> <a href="<?php bloginfo('url'); ?>/tag/<?php echo sanitize_title(get_post_meta($post->ID, 'bbit_phathanh', true)); ?>"><?php echo get_post_meta($post->ID, 'bbit_phathanh', true); ?></a><br>
    <?php endif; ?>
	<?php if(get_post_meta($post->ID, 'bbit_file_size', true)): ?>
    <label>Dung lượng:</label> <?php echo get_post_meta($post->ID, 'bbit_file_size', true); ?><br>
    <?php endif; ?>
    <?php if(get_post_meta($post->ID, 'bbit_author2', true)): ?>
    <label>Tác giả:</label> <a href="<?php bloginfo('url'); ?>/tag/<?php echo sanitize_title(get_post_meta($post->ID, 'bbit_author2', true)); ?>"><?php echo get_post_meta($post->ID, 'bbit_author2', true); ?></a><br>
    <?php endif; ?>
	
	</div>
</div>
<ul class="u_table info_items">
					<li> 
					
					<span class="info_items_title">Hỗ Trợ</span> <span class="info_items_val"><b><?php echo get_post_meta($post->ID, 'bbit_support', true); ?></b></span></li>
					 					<li> 
					<span class="info_items_title">Dung lượng</span> <span class="info_items_val"><b><?php if(get_post_meta($post->ID, 'bbit_file_size', true)): ?>(<?php echo get_post_meta($post->ID, 'bbit_file_size', true); ?>)<?php endif; ?></b></span></li>
					 					<li>
					<span class="info_items_title">Lượt tải</span> <span class="info_items_val"><b>None</b></span></li>   
					<li class="act_link">
					<a href="<?php echo get_post_meta($post->ID, 'bbit_link_download', true); ?>" rel="nofollow" class="downloadfree" target="_blank">Download</a></li> 
				 
			        </ul>
