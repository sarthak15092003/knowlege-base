<?php
$featured_video = docy_meta('featured_video');

$video_thumb_url = !empty($featured_video['video_thumbnail']['url']) ? $featured_video['video_thumbnail']['url'] : '';

$video_id = !empty($featured_video['video_url']) ? docy_get_youtube_video_id($featured_video['video_url']) : '';

if ( !empty($video_id) ) : ?>
	<div class="banner-video-container">
		<div class="video-wrapper">
			<!-- Thumbnail Image -->
			<?php if ( $video_thumb_url ): ?>
				<div class="video-thumbnail" id="videoThumbnail">
					<img src="<?php echo esc_url($video_thumb_url); ?>" alt="<?php esc_attr_e('Video Thumbnail', 'docy'); ?>">
				</div>
			<?php endif; ?>
			<iframe id="banner-video" src="https://www.youtube.com/embed/<?php echo esc_attr($video_id); ?>?rel=0&enablejsapi=1"
			        title="Featured Video"
			        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
			        allowfullscreen
			></iframe>

			<!-- Copy Link Button -->
			<div class="copy-link-btn" id="copyLinkBtn" onclick="copyVideoLink('<?php echo esc_url($featured_video['video_url']); ?>')">
				<?php esc_html_e( 'Copy link', 'docy' ); ?>
			</div>

			<div class="play-overlay" id="video-playId">
				<button class="play-button">
					<img src="<?php echo DOCY_DIR_IMG . '/play.svg' ?>" alt="<?php esc_attr_e('Play Icon', 'docy'); ?>">
					<span class="paragraph-medium video-button_initial-text">
                        <?php esc_html_e( 'Click to play', 'docy' ) ?>
                    </span>
				</button>
			</div>
		</div>
	</div>
<?php endif; ?>