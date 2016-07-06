<div class="infomation">
    <?php if(get_post_meta($post->ID, 'bbit_singer', true)): ?>
    <label>Ca sỹ:</label> <a href="<?php bloginfo('url'); ?>/tag/<?php echo sanitize_title(get_post_meta($post->ID, 'bbit_singer', true)); ?>"><?php echo get_post_meta($post->ID, 'bbit_singer', true); ?></a> |
    <?php endif; ?>
    <?php if(get_post_meta($post->ID, 'bbit_author', true)): ?>
    <label>Sáng tác:</label> <a href="<?php bloginfo('url'); ?>/tag/<?php echo sanitize_title(get_post_meta($post->ID, 'bbit_author', true)); ?>"><?php echo get_post_meta($post->ID, 'bbit_author', true); ?></a> |
    <?php endif; ?>
    <?php if(get_post_meta($post->ID, 'bbit_album', true)): ?>
    <label>Album:</label> <?php echo get_post_meta($post->ID, 'bbit_album', true); ?>
    <?php endif; ?>
    <div id="mp3-player">
    <audio controls>
    <source src="<?php echo get_post_meta($post->ID, 'bbit_link_download', true); ?>" type="audio/ogg">
    Trình duyệt của bạn không hỗ trợ nghe nhạc trực tuyến!
    </audio>
    </div>
</div>