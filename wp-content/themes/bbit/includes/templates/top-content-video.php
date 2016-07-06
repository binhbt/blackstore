<div class="infomation">
    <?php if(get_post_meta($post->ID, 'bbit_video_time', true)): ?>
    <label>Thời lượng:</label> <?php echo get_post_meta($post->ID, 'bbit_video_time', true); ?> | 
    <?php endif; ?>
    <label>Lượt xem:</label> <?php echo get_bbit_views(get_the_ID()); ?>
</div>
<h1 class="item fn center-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
<div id="video-player">
	<iframe width="500" height="281" src="http://www.youtube.com/embed/<?php echo get_post_meta($post->ID, 'bbit_video', true); ?>?feature=oembed" frameborder="0" allowfullscreen=""></iframe>
</div>