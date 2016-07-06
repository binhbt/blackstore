<div class="infomation">
	<?php if(get_post_meta($post->ID, 'bbit_thumb', true)) : ?>
        <img width="55" height="55" src="<?php echo get_post_meta($post->ID, 'bbit_thumb', true); ?>" class="photo" alt="<?php the_title(); ?> icon">
		<label><?php the_title(); ?></label><br>
	<?php else: ?>
	<?php the_post_thumbnail('thumbnail', array('class'=>'photo' , 'alt' => get_the_title() )); ?>	
	<?php endif; ?>
    <?php if(get_post_meta($post->ID, 'bbit_phathanh', true)): ?>
    <label>Phát hành:</label> <a href="<?php bloginfo('url'); ?>/tag/<?php echo sanitize_title(get_post_meta($post->ID, 'bbit_phathanh', true)); ?>"><?php echo get_post_meta($post->ID, 'bbit_phathanh', true); ?></a><br>
    <?php endif; ?>
	<?php if(get_post_meta($post->ID, 'bbit_file_size', true)): ?>
    <label>Dung lượng:</label> <?php echo get_post_meta($post->ID, 'bbit_file_size', true); ?><br>
    <?php endif; ?>
	<?php if(get_post_meta($post->ID, 'bbit_support', true)): ?>
    <label>Hỗ trợ:</label> <?php echo get_post_meta($post->ID, 'bbit_support', true); ?><br>
    <?php endif; ?>
    <?php if(get_post_meta($post->ID, 'bbit_author2', true)): ?>
    <label>Tác giả:</label> <a href="<?php bloginfo('url'); ?>/tag/<?php echo sanitize_title(get_post_meta($post->ID, 'bbit_author2', true)); ?>"><?php echo get_post_meta($post->ID, 'bbit_author2', true); ?></a><br>
    <?php endif; ?>
</div>
<h1 class="item fn center-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php if(get_post_meta($post->ID, 'bbit_heading', true)): ?><?php echo get_post_meta($post->ID, 'bbit_heading', true); ?><?php else: ?><?php the_title(); ?><?php endif; ?></a></h1>