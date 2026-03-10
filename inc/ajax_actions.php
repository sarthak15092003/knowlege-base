<?php
/**
 * Docy search result item markup
 */
function docy_search_result_html($post_type, $id){
    if ( $post_type === 'product' ) :
        ?>
        <a class="search-result-item shop-search-result-item" href="<?php echo get_the_permalink($id); ?>">
            <div class="shop-search-thumbnail-wrap">
                <?php
                if ( docy_opt('is_search_result_thumbnail') ) :
                    if ( has_post_thumbnail() ) :
                        the_post_thumbnail('docy_60x60');
					else:
                        ?>
                        <svg width="16px" aria-labelledby="title" viewBox="0 0 17 17" fill="currentColor" class="block h-full" role="img"><title id="title"><?php the_title(); ?></title>
                            <path d="M14.72,0H2.28A2.28,2.28,0,0,0,0,2.28V14.72A2.28,2.28,0,0,0,2.28,17H14.72A2.28,2.28,0,0,0,17,14.72V2.28A2.28,2.28,0,0,0,14.72,0ZM2.28,1H14.72A1.28,1.28,0,0,1,16,2.28V5.33H1V2.28A1.28,1.28,0,0,1,2.28,1ZM1,14.72V6.33H5.33V16H2.28A1.28,1.28,0,0,1,1,14.72ZM14.72,16H6.33V6.33H16v8.39A1.28,1.28,0,0,1,14.72,16Z"></path>
                        </svg>
                        <?php
                    endif;
                endif;
                ?>
            </div>
            <div class="shop-search-content-wrap">
                <h6 class="title">
                    <span class="topic-section"><?php the_title(); ?></span>
                    <svg viewBox="0 0 24 24" fill="none" color="white" stroke="white" width="16px" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="block h-auto w-16">
                        <polyline points="9 10 4 15 9 20"></polyline>
                        <path d="M20 4v7a4 4 0 0 1-4 4H4"></path>
                    </svg>
                </h6>
                <div class="price">
                    <?php
                    global $product;
                    if ( $product ) {
                        echo wp_kses_post($product->get_price_html());
                    }
                    ?>
                </div>
            </div>
        </a>
        <?php
     else :
        ?>
        <div class="search-result-item" onclick="document.location='<?php echo get_permalink($id); ?>'">
            <a href="<?php the_permalink(); ?>" class="title">
                <svg width="16px" aria-labelledby="title" viewBox="0 0 17 17" fill="currentColor" class="block h-full w-auto" role="img"><title id="title"><?php the_title(); ?></title>
                    <path d="M14.72,0H2.28A2.28,2.28,0,0,0,0,2.28V14.72A2.28,2.28,0,0,0,2.28,17H14.72A2.28,2.28,0,0,0,17,14.72V2.28A2.28,2.28,0,0,0,14.72,0ZM2.28,1H14.72A1.28,1.28,0,0,1,16,2.28V5.33H1V2.28A1.28,1.28,0,0,1,2.28,1ZM1,14.72V6.33H5.33V16H2.28A1.28,1.28,0,0,1,1,14.72ZM14.72,16H6.33V6.33H16v8.39A1.28,1.28,0,0,1,14.72,16Z"></path>
                </svg>
                <span class="doc-section"><?php the_title(); ?></span>
                <svg viewBox="0 0 24 24" fill="none" color="white" stroke="white" width="16px" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="block h-auto w-16">
                    <polyline points="9 10 4 15 9 20"></polyline>
                    <path d="M20 4v7a4 4 0 0 1-4 4H4"></path>
                </svg>
            </a>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <?php 
                    $post_type_object   = get_post_type_object(get_post_type($id));
                    $post_type_name     = $post_type_object ? $post_type_object->labels->singular_name : get_post_type($id);
                    $post_type_name     = ucwords(preg_replace('/[-_]+/', ' ', $post_type_name));
                    echo ucfirst( $post_type_name ); 
                    ?>
                </li>
                <li class="breadcrumb-item">
                    <a href="<?php the_permalink(); ?>" class="bbp-breadcrumb-root">
                        <?php the_title(); ?>
                    </a>
                </li>
                <li class="breadcrumb-item"></li>
            </ol>
        </div>
    <?php
    endif;
}

add_action('wp_ajax_ajax_search', 'ajax_search_handler');
add_action('wp_ajax_nopriv_ajax_search', 'ajax_search_handler');

function ajax_search_handler() {

    check_ajax_referer('ajax_search_nonce', 'security');
    $post_type 		= $_POST['post_type'];
    $search_term 	= sanitize_text_field($_POST['keyword']);

    if (is_array($post_type)) {
        foreach ($post_type as $type) {
             $args = [
                's'                 => $search_term,
                'post_type'         => $type,
                'posts_per_page'    => 10,
                'orderby'           => 'post_date',
                'order'             => 'DESC'
            ];            
            $query = new WP_Query($args);
            if ($query->have_posts()) :
				docy_set_search_keywords($search_term);
                ?>
                <div class="docy-search-results-heading">
                    <?php
                    $post_type_object   = get_post_type_object($type);
                    $post_type_name     = $post_type_object ? $post_type_object->labels->singular_name : $type;
                    $post_type_name     = ucwords(preg_replace('/[-_]+/', ' ', $post_type_name));
                    echo ucfirst($post_type_name); 
                    ?>
                </div>    
                <?php
                while ( $query->have_posts() ) { $query->the_post();
					docy_search_result_html($type, get_the_ID());               
                }
                wp_reset_postdata();
            endif;            
        }        
    } else {
        $args = [
            's'                 => $search_term,
            'post_type'         => sanitize_text_field($post_type),
            'posts_per_page'    => 10,
            'orderby'           => 'post_date',
            'order'             => 'DESC'
        ];
        $query = new WP_Query($args);
        if ($query->have_posts()) :			
			docy_set_search_keywords($search_term);
            ?>
            <div class="docy-search-results-heading">
                <?php
                $post_type_object   = get_post_type_object($post_type);
                $post_type_name     = $post_type_object ? $post_type_object->labels->singular_name : $post_type;
                $post_type_name     = ucwords(preg_replace('/[-_]+/', ' ', $post_type_name));
                echo ucfirst($post_type_name); 
                ?>
            </div>
            <?php
            while ($query->have_posts()) {
                $query->the_post();
                docy_search_result_html($post_type, get_the_ID());                
            }
            wp_reset_postdata();
            ?>
            <a href="<?php echo esc_url( home_url( '/?s=' ) . $search_term ?? '' ); ?>&post_type=<?php echo $post_type; ?>" class="view-more-btn">
                <?php esc_html_e( 'Show More Results', 'docy' ); ?>
            </a>
            <?php
        endif;
    }
    wp_die();
}

add_action('wp_ajax_lex_chat_query', 'lex_chat_query_handler');
add_action('wp_ajax_nopriv_lex_chat_query', 'lex_chat_query_handler');
add_action('wp_ajax_lex_live_search', 'lex_live_search_handler');
add_action('wp_ajax_nopriv_lex_live_search', 'lex_live_search_handler');

function lex_call_openai($query, $force_direct = false) {
    // Using Pollinations AI - a free, open OpenAI-compatible endpoint. No 
    // 
    // 
    // API key required!
    // This circumvents quota limits from standard OpenAI / Gemini
    $api_url = 'https://text.pollinations.ai/openai';

    if ($force_direct) {
        $prompt = "You are Lex, a technical expert for CMGalaxy. ANSWER THE USER'S QUESTION DIRECTLY AND EXPERTLY: \"" . addslashes($query) . "\".\n\n" .
                  "1. Provide a clear technical guide or explanation.\n" .
                  "2. Use bullet points.\n" .
                  "3. NO greeting filler.\n\n" .
                  "Always respond in this exact JSON format only:\n" .
                  "{\"answer\":\"your substantive technical answer here\",\"keywords\":\"\"}";
    } else {
        $prompt = "You are Lex, the AI assistant for 'CMGalaxy'.\n\n" .
                  "GOAL: Extract the core search keywords from the user's question so we can search our knowledge base.\n\n" .
                  "STRICT RULES:\n" .
                  "1. 'answer': Leave this EMPTY string. Do NOT generate any answer, explanation, or article content. Do NOT make up articles or CMGalaxy features. Only the knowledge base search results will be shown.\n" .
                  "2. 'keywords': Extract ONLY the 1-2 most important technical words from the query (e.g. 'whatsapp', 'dv360', 'report'). Ignore filler words like 'is there', 'any', 'related to', 'article about'.\n\n" .
                  "IMPORTANT: Always respond in this exact JSON format only:\n" .
                  "{\n" .
                  "  \"answer\": \"\",\n" .
                  "  \"keywords\": \"technical word(s) only\"\n" .
                  "}";
    }

    $body = json_encode([
        'model' => 'openai',
        'messages' => [
            ['role' => 'system', 'content' => 'You always reply in valid JSON using the requested schema.'],
            ['role' => 'user', 'content' => $prompt]
        ],
        'temperature' => 0.1,
        'jsonMode' => true
    ]);

    $response = wp_remote_post($api_url, [
        'headers'   => [
            'Content-Type'  => 'application/json'
        ],
        'body'      => $body,
        'timeout'   => 25,
        'sslverify' => false,
    ]);

    if (is_wp_error($response)) {
        error_log('Lex OpenAI Error: ' . $response->get_error_message());
        return null; // Fallback to normal search
    }

    $status_code = wp_remote_retrieve_response_code($response);
    $body_response = trim(wp_remote_retrieve_body($response));

    if (empty($body_response)) return null;

    // Is it a direct JSON string? (Sometimes proxies like Pollinations return text directly)
    if (strpos($body_response, '{') === 0) {
        $json_body = json_decode($body_response, true);
        if (isset($json_body['choices'][0]['message']['content'])) {
            $text = $json_body['choices'][0]['message']['content'];
        } else {
            // Assume the body itself is the JSON command
            $text = $body_response; 
        }
    } else {
        // Assume raw text response
        $text = $body_response;
    }

    if (!$text) return null;

    // Strip markdown code fences if wrapped in ```json
    $clean = trim($text);
    $clean = preg_replace('/^```(?:json)?\s*/i', '', $clean);
    $clean = preg_replace('/\s*```$/i', '', $clean);
    
    if (preg_match('/\{.*\}/s', $clean, $matches)) {
        $clean = $matches[0];
    }
    
    $result = json_decode($clean, true);

    // If json_decode failed but we have a non-empty string, treat it as a direct answer
    if (!$result && !empty($clean)) {
        return [
            'answer' => $clean,
            'keywords' => ''
        ];
    }
    
    return $result;
}

function lex_chat_query_handler() {
    $original_query = sanitize_text_field($_POST['query']);

    // Ask OpenAI to classify and respond
    $ai = lex_call_openai($original_query);
    
    $answer = isset($ai['answer']) ? $ai['answer'] : '';
    $search_term = isset($ai['keywords']) ? $ai['keywords'] : '';

    // If AI explicitly gave an error
    if (isset($ai['type']) && $ai['type'] === 'error') {
        wp_send_json_error(['message' => $ai['text']]);
        wp_die();
    }

    // Determine the search term: use AI keywords or fall back to manual cleaning
    if (!$search_term && empty($answer)) {
        $stop_phrases = [
            'open article which tell', 'open article which tells', 'open article about',
            'open article in', 'open article', 'find article about', 'find article in',
            'find article', 'tell me about', 'search for', 'look for', 'how to',
            'where is', 'what is', 'show me', 'which tell', 'which tells', 'i want to',
            'can you', 'please', 'help me with', 'about', 'total number of', 'total number',
            'count of', 'how many', 'number of', 'articles in', 'article in', 'articles', 'article'
        ];
        $q = str_ireplace($stop_phrases, '', $original_query);
        $short_stops = ['the', 'a', 'an', 'in', 'on', 'to', 'for', 'of', 'with', 'at', 'by', 'is'];
        foreach ($short_stops as $word) {
            $q = preg_replace('/\b' . $word . '\b/i', '', $q);
        }
        $mappings = [
            'on board' => 'onboarding', 'platfrom' => 'platform',
            'getting stated' => 'getting started',
        ];
        foreach ($mappings as $wrong => $right) {
            $q = str_ireplace($wrong, $right, $q);
        }
        $search_term = trim(preg_replace('/\s+/', ' ', $q)) ?: $original_query;
    }

    // Dynamically get public post types
    $post_types = ['post', 'docs', 'onepage-docs'];

    $args = [
        's'              => $search_term,
        'post_type'      => $post_types,
        'posts_per_page' => 10,
        'orderby'        => 'relevance'
    ];

    $query   = new WP_Query($args);
    $candidates = [];

    // Combine results from multiple match attempts for broader coverage
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $candidates[get_the_ID()] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'excerpt' => get_the_excerpt(),
                'content' => get_the_content(), // Store content for deeper matching
                'url' => get_permalink(),
                'type' => get_post_type()
            ];
        }
    }

    // Attempt fuzzy taxonomy/original match to find missed "Sharing" etc
    $cleaned_q = str_ireplace(['how to', 'share', 'report'], '', strtolower($original_query));
    if (strlen(trim($cleaned_q)) > 2) {
        $args['s'] = $original_query;
        $query2 = new WP_Query($args);
        while ($query2->have_posts()) {
            $query2->the_post();
            $candidates[get_the_ID()] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'excerpt' => get_the_excerpt(),
                'content' => get_the_content(),
                'url' => get_permalink(),
                'type' => get_post_type()
            ];
        }
    }

    $scored_results = [];
    $search_words = explode(' ', strtolower($search_term . ' ' . $original_query));
    $search_words = array_unique(array_filter($search_words, function($w) {
        return strlen($w) > 2 && !in_array($w, ['how', 'the', 'for', 'and', 'with', 'setup']);
    }));

    foreach ($candidates as $post_id => $data) {
        $score = 0;
        $title = strtolower($data['title']);
        $excerpt = strtolower($data['excerpt']);
        $content = strtolower($data['content'] ?? '');

        // 1. Title Match Priority for the AI Search Term
        if (stripos($title, strtolower($search_term)) !== false) {
            $score += 80; // Massive boost for AI-identified core noun
            if (preg_match('/\b' . preg_quote(strtolower($search_term), '/') . '\b/i', $title)) {
                $score += 40; // Extra boost for word boundary match
            }
        }
        
        $word_matches = 0;
        foreach ($search_words as $w) {
            if (stripos($title, $w) !== false) {
                $score += 30; // Increased boost
                $word_matches++;
            }
            if (stripos($excerpt, $w) !== false) $score += 15;
            if (!empty($content) && stripos($content, $w) !== false) $score += 10; // Increased boost
        }

        // 2. Strict Requirement check content too
        if (!empty($search_term) && strlen($search_term) > 3) {
            $in_title = (stripos($title, $search_term) !== false);
            $in_excerpt = (stripos($excerpt, $search_term) !== false);
            $in_content = (stripos($content, $search_term) !== false);

            if (!$in_title && !$in_excerpt && !$in_content) {
                $score -= 150; 
            } elseif ($in_content && !$in_title) {
                $score += 60; // Doubled boost for content finding
            }
        }

        // 3. Perfect Title Start
        if (stripos(trim($title), strtolower($search_term)) === 0) $score += 50;

        // 4. Safety Filter: generic pages block
        $exact_generic_pages = ['Meta Ads', 'Reporting Hub', 'Documentation', 'CMGalaxy Knowledge Base'];
        foreach ($exact_generic_pages as $gp) {
            if (trim($data['title']) === $gp || stripos($title, $gp) !== false) {
                if (stripos($original_query, $gp) === false && count($search_words) > 1) {
                    $score -= 60; // Penalize generic landing pages
                }
            }
        }

        if ($score > 30) { // Lowered from 40 to ensure content-only matches still surface
            $scored_results[] = [
                'score' => $score,
                'title' => $data['title'],
                'url' => $data['url'],
                'excerpt' => wp_trim_words($data['excerpt'], 20),
                'type' => $data['type']
            ];
        }
    }

    // Sort by score DESC
    usort($scored_results, function ($a, $b) {
        return $b['score'] - $a['score'];
    });

    $results = $scored_results;
    wp_reset_postdata();

    if (!empty($results)) {
        $actual_total = count($results);
        $display_results = array_slice($results, 0, 5);
        $article_text = $actual_total == 1 ? 'article' : 'articles';

        // With deep content matching, if it passed the > 30 score threshold and 
        // strict keyword checks, it's a solid match even if not in the title.
        // We only fetch a fallback AI answer if one wasn't provided earlier.
        if (empty($answer)) {
            $ai_fb = lex_call_openai($original_query, true);
            $answer = $ai_fb['answer'] ?? '';
        }

        if (!empty($results)) {
            $suggest_msg = " I also found {$actual_total} {$article_text} that might help you:";
            if (preg_match('/(how many|total|number|count)/i', trim($original_query))) {
                $suggest_msg = " There are {$actual_total} {$article_text} related to \"{$search_term}\". Here's the list:";
            }

            wp_send_json_success([
                'message' => (!empty($answer) ? $answer . "\n\n" : "") . $suggest_msg,
                'results' => $display_results
            ]);
        } else {
            // Results were weak/filtered out
            wp_send_json_success([
                'message' => $answer ?: "I couldn't find a specific article for \"" . esc_html($original_query) . "\".",
                'results' => []
            ]);
        }
    } else {
        // No articles found — return honest message, never hallucinate
        wp_send_json_success([
            'message' => "I couldn't find any articles related to \"" . esc_html($original_query) . "\" in the knowledge base. Try different keywords or browse the categories.",
            'results' => []
        ]);
    }
    wp_die();
}

/**
 * Handle live search suggestions from the header
 */
function lex_live_search_handler() {
    $q = isset($_POST['query']) ? sanitize_text_field($_POST['query']) : '';
    
    if (strlen($q) < 2) {
        wp_send_json_success(['results' => []]);
    }

    $args = [
        's'                      => $q,
        'post_type'              => ['post', 'docs'],
        'posts_per_page'         => 5,
        'orderby'                => 'relevance',
        'no_found_rows'          => true,   // skip COUNT(*) query — big speed boost
        'update_post_meta_cache' => false,  // skip loading post meta
        'update_post_term_cache' => false,  // skip loading terms
    ];

    $query = new WP_Query($args);
    $results = [];

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $results[] = [
                'title' => get_the_title(),
                'url'   => get_permalink(),
                'type'  => get_post_type()
            ];
        }
        wp_reset_postdata();
    }

    wp_send_json_success(['results' => $results]);
}




/**
 * Set searched keywords
 */
function docy_set_search_keywords() {
	if ( isset( $_POST['keyword'] ) && ! empty( $_POST['keyword'] ) ) {
		$keywords   = get_option( 'docy_search_keyword', [] );
		$keyword    = $_POST['keyword'] ?? '';// Fetch previous keywords, default to an empty array if it doesn't exist
		$keyword    = sanitize_text_field( $keyword );
		$keyword    = strtolower( $keyword );
		$keywords[] = $keyword;
		update_option( 'docy_search_keyword', $keywords );
	}
}

/**
 * Get searched keywords
 */
function docy_get_search_keywords() {
	$keywords        = get_option( 'docy_search_keyword' );
	$stored_keywords = [];
	if ( ! empty( $keywords ) ) {
		$keyword_counts = array_count_values( $keywords );
		arsort( $keyword_counts );
		$displayed_keywords = [];
		foreach ( $keyword_counts as $word => $count ) {
			if ( ! in_array( $word, $displayed_keywords ) ) {
				$stored_keywords[] = $word;
			}
		}
	}

	return $stored_keywords;
}

add_action('wp_head', function(){
	$keywords        = get_option( 'docy_search_keyword' );
	// print_r( $keywords	);
});

/**
 * Loading Post
 *
 * @return string
 */
add_action( 'wp_ajax_docy_loading_post', 'docy_loading_post' );
add_action( 'wp_ajax_nopriv_docy_loading_post', 'docy_loading_post' );

/**
 * Loading forum posts
 */
function docy_loading_post() {
	global $wpdb;

	$nonce   = sanitize_text_field( $_POST['nonce'] );
	$type    = sanitize_text_field( $_POST['type'] );
	$post_in = sanitize_text_field( $_POST['a_t_id'] );
	$count   = sanitize_text_field( $_POST['count'] );
	$parent  = sanitize_text_field( $_POST['parent'] );
	if ( ! wp_verify_nonce( $nonce, 'docy-nonce' ) ) {
		die( '-1' );
	}
	$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
	$q     = [
		'post_type'           => 'topic',
		'post_parent'         => $parent,
		'order'               => 'DESC',
		'orderby'             => 'post_date',
		'post_status'         => 'publish',
		'posts_per_page'      => - 1,
		'ignore_sticky_posts' => 1,
	];
	if ( $type == 'author' ) {
		$auth_ids = [
			'author' => $post_in,
		];
		$q        = array_merge( $q, $auth_ids );
	} elseif ( $type == 'tag' ) {
		$tax_query[] = [
			'taxonomy' => 'topic-tag',
			'field'    => 'term_id',
			'terms'    => $post_in,
		];
	}
	$tax_query[] = [
		'taxonomy' => 'post_format',
		'field'    => 'slug',
		'terms'    => [ 'post-format-quote', 'post-format-link' ],
		'operator' => 'NOT IN',
	];
	if ( ! empty( $tax_query ) ) {
		$tax_query = array_merge( [ 'relation' => 'AND' ], $tax_query );
		$q         = array_merge( $q, [ 'tax_query' => $tax_query ] );
	}
	$query = new WP_Query( $q );

	if ( $query->have_posts() ):
		echo '<div class="community-posts-wrapper bb-radius">';
		while ( $query->have_posts() ): $query->the_post();
			global $post;
			$author_id      = $post->post_author;
			$parent_post_id = $parent;
			$favoriters     = get_post_meta( get_the_ID(), '_bbp_favorite', true );
			$favorite_count = ! empty( $favoriters ) ? $favoriters[0] : '0';
			$get_reply      = get_post_meta( get_the_ID(), '_bbp_reply_count', true );
			$_reply_count   = isset( $get_reply ) && ! empty( $get_reply ) ? $get_reply : 0;
			?>
            <div class="community-post style-two <?php the_author_meta( 'user_nicename', $author_id ); ?>">
                <div class="post-content">
                    <div class="author-avatar">
						<?php
						echo bbp_get_topic_author_link(
							array(
								'post_id' => get_the_ID(),
								'type'    => 'avatar',
								'size'    => 40
							)
						);
						?>
                    </div>
                    <div class="entry-content">
						<?php the_title( sprintf( '<a href="%s" rel="bookmark"> <h3 class="post-title">', esc_url( get_permalink() ) ), '</h3></a>' ); ?>
                        <ul class="meta">
                            <li>
								<?php echo get_the_post_thumbnail( bbp_get_topic_forum_id(), array( 40, 40 ) ); ?>
                                <a href="<?php echo get_permalink( bbp_get_topic_forum_id() ); ?>">
									<?php echo get_the_title( bbp_get_topic_forum_id() ); ?>
                                </a>
                            </li>
                            <li><i class="icon_clock_alt"></i> <?php bbp_topic_post_date( get_the_ID() ); ?> </li>
                        </ul>
                    </div>
                </div>
                <div class="post-meta-wrapper">
                    <ul class="post-meta-info">
                        <li><a href="#"><i class="icon_chat_alt"></i><?php echo esc_html( $_reply_count ); ?></a></li>
                        <li><a href="#"><i class="icon_star"></i><?php echo esc_html( $favorite_count ); ?></a></li>
                    </ul>
                </div>
            </div>
		<?php
		endwhile;
		wp_reset_postdata();

		echo '</div>';
	else:
		echo '<div class="community-post-error bug">';
		echo '<div class="error-content">';
		echo '<svg height="40" class="docy-error error-icon" viewBox="0 0 24 24" version="1.1" width="40" aria-hidden="true"><path d="M12 7a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0112 7zm1 9a1 1 0 11-2 0 1 1 0 012 0z"></path><path fill-rule="evenodd" d="M12 1C5.925 1 1 5.925 1 12s4.925 11 11 11 11-4.925 11-11S18.075 1 12 1zM2.5 12a9.5 9.5 0 1119 0 9.5 9.5 0 01-19 0z"></path></svg>';
		echo '<h3 class="error">' . esc_html__( 'Oops! No results matched your search.', 'docy' ) . '</h3>';
		echo '<p class="error">' . esc_html__( 'You could search again.', 'docy' ) . '</p>';
		echo '</div>';
		echo '</div>';
	endif;

	die;
}

/**
 * Loading Post
 *
 * @return string
 */
add_action( 'wp_ajax_docy_open_post', 'docy_open_post' );
add_action( 'wp_ajax_nopriv_docy_open_post', 'docy_open_post' );

function docy_open_post() {
	global $wpdb;

	$is_queried_obj = is_singular( 'forum' ) ? get_queried_object_id() : false;
	$nonce          = sanitize_text_field( $_POST['nonce'] );
	$type           = sanitize_text_field( $_POST['type'] );
	$post_in        = sanitize_text_field( $_POST['a_t_id'] );
	$count          = sanitize_text_field( $_POST['count'] );
	$parent         = sanitize_text_field( $_POST['parent'] );
	$userid         = sanitize_text_field( $_POST['userid'] );

	if ( ! wp_verify_nonce( $nonce, 'docy-nonce' ) ) {
		die( '-1' );
	}
	$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
	$q     = [
		'post_type'           => 'topic',
		'post_parent'         => $parent,
		'order'               => 'DESC',
		'orderby'             => 'post_date',
		'posts_per_page'      => get_option( '_bbp_topics_per_page', 10 ),
		'ignore_sticky_posts' => 1,
		'author'              => $userid
	];
	if ( $type == 'open' ) {
		$status = [
			'post_status' => 'publish',
		];
		$q      = array_merge( $q, $status );
	} elseif ( $type == 'closed' ) {
		$status = [
			'post_status' => 'closed',
		];
		$q      = array_merge( $q, $status );
	}
	$tax_query[] = [
		'taxonomy' => 'post_format',
		'field'    => 'slug',
		'terms'    => [ 'post-format-quote', 'post-format-link' ],
		'operator' => 'NOT IN',
	];
	if ( ! empty( $tax_query ) ) {
		$tax_query = array_merge( [ 'relation' => 'AND' ], $tax_query );
		$q         = array_merge( $q, [ 'tax_query' => $tax_query ] );
	}
	$query = new WP_Query( $q );
	if ( $query->have_posts() ):
		echo '<div class="community-posts-wrapper bb-radius">';
		while ( $query->have_posts() ): $query->the_post();
			global $post;
			$author_id = $post->post_author;
			//$parent_post_id = get_post_meta( get_the_ID(), '_bbp_topic_id', true );
			$parent_post_id = $parent;
			$favoriters     = get_post_meta( get_the_ID(), '_bbp_favorite', true );
			$favorite_count = ! empty( $favoriters ) ? $favoriters[0] : '0';
			$get_reply      = get_post_meta( get_the_ID(), '_bbp_reply_count', true );
			$_reply_count   = isset( $get_reply ) && ! empty( $get_reply ) ? $get_reply : 0;
			?>

            <div class="community-post style-two <?php the_author_meta( 'user_nicename', $author_id ); ?>">
                <div class="post-content">
                    <div class="author-avatar">
						<?php
						echo bbp_get_topic_author_link(
							array(
								'post_id' => get_the_ID(),
								'type'    => 'avatar',
								'size'    => 40
							)
						);
						?>
                    </div>
                    <div class="entry-content">
						<?php the_title( sprintf( '<a href="%s" rel="bookmark"> <h3 class="post-title">', get_permalink() ), '</h3></a>' ); ?>
                        <ul class="meta">
                            <li>
								<?php echo get_the_post_thumbnail( bbp_get_topic_forum_id(), array( 40, 40 ) ); ?>
                                <a href="<?php echo get_permalink( bbp_get_topic_forum_id() ); ?>">
									<?php echo get_the_title( bbp_get_topic_forum_id() ); ?>
                                </a>
                            </li>
                            <li><i class="icon_clock_alt"></i> <?php bbp_topic_post_date( get_the_ID() ); ?> </li>
                        </ul>
                    </div>
                </div>
                <div class="post-meta-wrapper">
                    <ul class="post-meta-info">
                        <li><a href="#"><i class="icon_chat_alt"></i><?php echo esc_html( $_reply_count ); ?></a></li>
                        <li><a href="#"><i class="icon_star"></i><?php echo esc_html( $favorite_count ); ?></a></li>
                    </ul>
                </div>
            </div>
		<?php
		endwhile;
		wp_reset_postdata();

		echo '</div>';
	else:
		echo '<div class="community-post-error bug">';
		echo '<div class="error-content">';
		echo '<svg height="40" class="docy-error error-icon" viewBox="0 0 24 24" version="1.1" width="40" aria-hidden="true"><path d="M12 7a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0112 7zm1 9a1 1 0 11-2 0 1 1 0 012 0z"></path><path fill-rule="evenodd" d="M12 1C5.925 1 1 5.925 1 12s4.925 11 11 11 11-4.925 11-11S18.075 1 12 1zM2.5 12a9.5 9.5 0 1119 0 9.5 9.5 0 01-19 0z"></path></svg>';
		echo '<h3 class="error">' . esc_html__( 'Oops! No results matched your search.', 'docy' ) . '</h3>';
		echo '<p class="error">' . esc_html__( 'You could search again.', 'docy' ) . '</p>';
		echo '</div>';
		echo '</div>';
	endif;
	die;
}

add_action( 'wp_ajax_docy_loading_sort_post', 'docy_loading_sort_post' );
add_action( 'wp_ajax_nopriv_docy_loading_sort_post', 'docy_loading_sort_post' );

function docy_loading_sort_post() {
	global $wpdb;

	$nonce  = sanitize_text_field( $_POST['nonce'] );
	$sort   = sanitize_text_field( $_POST['sort'] );
	$parent = sanitize_text_field( $_POST['parent'] );

	if ( ! wp_verify_nonce( $nonce, 'docy-nonce' ) ) {
		die( '-1' );
	}

	$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
	$q     = [
		'post_type'           => 'topic',
		'post_parent'         => $parent,
		'post_status'         => 'publish',
		'posts_per_page'      => get_option( '_bbp_topics_per_page', 10 ),
		'ignore_sticky_posts' => 1,
	];
	if ( $sort == 'newest_posts' ) {
		$newest_posts = [
			'order' => 'DESC',
		];
		$q            = array_merge( $q, $newest_posts );
	} elseif ( $sort == 'oldest_posts' ) {
		$oldest_posts = [
			'order' => 'ASC',
		];
		$q            = array_merge( $q, $oldest_posts );
	} elseif ( $sort == 'comment_count' ) {
		$comment_count = [
			'meta_key' => '_bbp_reply_count',
			'orderby'  => 'meta_value_num',
			'order'    => 'DESC',
		];
		$q             = array_merge( $q, $comment_count );
	} elseif ( $sort == 'comment_date' ) {
		$comment_count = [
			'meta_key'  => '_bbp_reply_count',
			'meta_type' => 'NUMERIC',
			'orderby'   => 'meta_value_num',
			'order'     => 'ASC',
		];
		$q             = array_merge( $q, $comment_count );
	} elseif ( $sort == 'recent_updated_post' ) {
		$post_date = [
			'orderby' => 'post_modified',
			'order'   => 'DESC',
		];
		$q         = array_merge( $q, $post_date );
	} elseif ( $sort == 'last_recent_updated_post' ) {
		$post_modified = [
			'orderby' => 'post_modified',
			'order'   => 'ASC',
		];
		$q             = array_merge( $q, $post_modified );
	}
	$tax_query[] = [
		'taxonomy' => 'post_format',
		'field'    => 'slug',
		'terms'    => [ 'post-format-quote', 'post-format-link' ],
		'operator' => 'NOT IN',
	];
	if ( ! empty( $tax_query ) ) {
		$tax_query = array_merge( [ 'relation' => 'AND' ], $tax_query );
		$q         = array_merge( $q, [ 'tax_query' => $tax_query ] );
	}
	$query = new WP_Query( $q );
	if ( $query->have_posts() ):
		echo '<div class="community-posts-wrapper bb-radius">';
		while ( $query->have_posts() ): $query->the_post();
			global $post;

			$author_id      = $post->post_author;
			$parent_post_id = $parent;
			$favoriters     = get_post_meta( get_the_ID(), '_bbp_favorite', true );
			$favorite_count = ! empty( $favoriters ) ? $favoriters[0] : '0';
			$get_reply      = get_post_meta( get_the_ID(), '_bbp_reply_count', true );
			$_reply_count   = isset( $get_reply ) && ! empty( $get_reply ) ? $get_reply : 0;
			?>
            <div class="community-post style-two <?php the_author_meta( 'user_nicename', $author_id ); ?>">
                <div class="post-content">
                    <div class="author-avatar">
						<?php
						echo bbp_get_topic_author_link(
							array(
								'post_id' => get_the_ID(),
								'type'    => 'avatar',
								'size'    => 40
							)
						);
						?>
                    </div>
                    <div class="entry-content">
						<?php the_title( sprintf( '<a href="%s" rel="bookmark"> <h3 class="post-title">', esc_url( get_permalink() ) ), '</h3></a>' ); ?>
                        <ul class="meta">
                            <li>
								<?php
								if ( get_the_post_thumbnail_url( $parent_post_id ) ) :
									?>
                                    <img src="<?php echo get_the_post_thumbnail_url( $parent_post_id ); ?>"
                                         alt="<?php echo get_the_title( $parent_post_id ); ?>">
								<?php
								endif;
								?>
                                <a href="<?php echo get_permalink( $parent_post_id ); ?>"> <?php echo get_the_title( $parent_post_id ); ?> </a>
                            </li>
                            <li><i class="icon_clock_alt"></i> <?php bbp_topic_post_date( get_the_ID() ); ?> </li>
                        </ul>
                    </div>
                </div>
                <div class="post-meta-wrapper">
                    <ul class="post-meta-info">
                        <li><a href="#"><i class="icon_chat_alt"></i><?php echo esc_html( $_reply_count ); ?></a></li>
                        <li><a href="#"><i class="icon_star"></i><?php echo esc_html( $favorite_count ); ?></a></li>
                    </ul>
                </div>
            </div>
		<?php endwhile;
		wp_reset_postdata();

		echo '</div>';
	else:
		echo '<div class="community-post-error bug">';
		echo '<div class="error-content">';
		echo '<svg height="40" class="docy-error error-icon" viewBox="0 0 24 24" version="1.1" width="40" aria-hidden="true"><path d="M12 7a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0112 7zm1 9a1 1 0 11-2 0 1 1 0 012 0z"></path><path fill-rule="evenodd" d="M12 1C5.925 1 1 5.925 1 12s4.925 11 11 11 11-4.925 11-11S18.075 1 12 1zM2.5 12a9.5 9.5 0 1119 0 9.5 9.5 0 01-19 0z"></path></svg>';
		echo '<h3 class="error">' . esc_html__( 'Oops! No results matched your search.', 'docy' ) . '</h3>';
		echo '<p class="error">' . esc_html__( 'You could search again.', 'docy' ) . '</p>';
		echo '</div>';
		echo '</div>';
	endif;

	die;
}

add_action( 'wp_ajax_docy_loading_pagination', 'docy_loading_pagination' );
add_action( 'wp_ajax_nopriv_docy_loading_pagination', 'docy_loading_pagination' );

function docy_loading_pagination() {
	global $wpdb;
	$nonce  = sanitize_text_field( $_POST['nonce'] );
	$list   = sanitize_text_field( $_POST['list'] );
	$parent = sanitize_text_field( $_POST['parent'] );
	if ( ! wp_verify_nonce( $nonce, 'docy-nonce' ) ) {
		die( '-1' );
	}
	$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	$q     = [
		'post_type'           => 'topic',
		'post_parent'         => $parent,
		'order'               => 'DESC',
		'orderby'             => 'post_date',
		'posts_per_page'      => get_option( '_bbp_topics_per_page', 10 ),
		'ignore_sticky_posts' => 1,
		'paged'               => sanitize_text_field( $_POST['paged'] ),
		'page'                => sanitize_text_field( $_POST['paged'] ),

	];

	$query = new WP_Query( $q );
	if ( $query->have_posts() ):
		echo '<div class="community-posts-wrapper bb-radius">';
		while ( $query->have_posts() ): $query->the_post();
			global $post;
			$author_id      = $post->post_author;
			$parent_post_id = $parent;
			$favoriters     = get_post_meta( get_the_ID(), '_bbp_favorite', true );
			$favorite_count = ! empty( $favoriters ) ? $favoriters[0] : '0';
			$get_reply      = get_post_meta( get_the_ID(), '_bbp_reply_count', true );
			$_reply_count   = isset( $get_reply ) && ! empty( $get_reply ) ? $get_reply : 0;
			?>
            <div class="community-post style-two <?php the_author_meta( 'user_nicename', $author_id ); ?>">
                <div class="post-content">
                    <div class="author-avatar">
						<?php
						echo bbp_get_topic_author_link(
							array(
								'post_id' => get_the_ID(),
								'type'    => 'avatar',
								'size'    => 40
							)
						);
						?>
                    </div>
                    <div class="entry-content">
						<?php the_title( sprintf( '<a href="%s" rel="bookmark"> <h3 class="post-title">', get_permalink() ), '</h3></a>' ); ?>
                        <ul class="meta">
                            <li><img src="<?php echo get_the_post_thumbnail_url( $parent_post_id ); ?>" alt="<?php echo get_the_title( $parent_post_id ); ?>">
                                <a href="<?php echo get_permalink( $parent_post_id ); ?>"> <?php echo get_the_title( $parent_post_id ); ?> </a>
                            </li>
                            <li><i class="icon_clock_alt"></i> <?php bbp_topic_post_date( get_the_ID() ); ?> </li>
                        </ul>
                    </div>
                </div>
                <div class="post-meta-wrapper">
                    <ul class="post-meta-info">
                        <li><a href="#"><i class="icon_chat_alt"></i><?php echo esc_html( $_reply_count ); ?></a></li>
                        <li><a href="#"><i class="icon_star"></i><?php echo esc_html( $favorite_count ); ?></a></li>
                    </ul>
                </div>
            </div>
		<?php
		endwhile;
		wp_reset_postdata();
		echo '</div>';

	else:
		echo '<div class="community-post-error bug">';
		echo '<div class="error-content">';
		echo '<svg height="40" class="docy-error error-icon" viewBox="0 0 24 24" version="1.1" width="40" aria-hidden="true"><path d="M12 7a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0112 7zm1 9a1 1 0 11-2 0 1 1 0 012 0z"></path><path fill-rule="evenodd" d="M12 1C5.925 1 1 5.925 1 12s4.925 11 11 11 11-4.925 11-11S18.075 1 12 1zM2.5 12a9.5 9.5 0 1119 0 9.5 9.5 0 01-19 0z"></path></svg>';
		echo '<h3 class="error">' . esc_html__( 'Oops! No results matched your search.', 'docy' ) . '</h3>';
		echo '<p class="error">' . esc_html__( 'You could search again.', 'docy' ) . '</p>';
		echo '</div>';
		echo '</div>';
	endif;

	die;
}

add_action( 'wp_ajax_docy_tooltip_post', 'docy_tooltip_post' );
add_action( 'wp_ajax_nopriv_docy_tooltip_post', 'docy_tooltip_post' );

function docy_tooltip_post() {
	global $wpdb;
	$slug_id          = url_to_postid( $_POST['slug_id'] );
	$p_query          = get_post( $slug_id );
	$featured_img_url = get_the_post_thumbnail_url( $p_query->ID, 'full' );
	$image_alt        = get_post_meta( $p_query->ID, '_wp_attachment_image_alt', true );

	if ( ! empty( $featured_img_url ) ):
		?>
        <img src="<?php echo esc_url( $featured_img_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>">
	<?php
	endif;
	?>
    <div class="text">
        <h6>
            <a href="<?php echo esc_url( get_page_link( $p_query->ID ) ); ?>">
				<?php echo esc_url( $p_query->post_title ); ?>
            </a>
        </h6>
        <p><?php echo wp_trim_words( $p_query->post_content, 40, '...' ); ?></p>
    </div>

	<?php
	die();
}

/**
 * Get next category posts for infinite scroll
 */
add_action( 'wp_ajax_docy_get_category_posts_ajax', 'docy_get_category_posts_ajax' );
add_action( 'wp_ajax_nopriv_docy_get_category_posts_ajax', 'docy_get_category_posts_ajax' );

function docy_get_category_posts_ajax() {
    if ( ! wp_verify_nonce( $_POST['security'], 'docy-nonce' ) ) {
        wp_send_json_error( 'Security check failed' );
    }

    $cat_slug = sanitize_text_field( $_POST['cat_slug'] );
    $category = get_category_by_slug( $cat_slug );

    if ( ! $category ) {
        wp_send_json_error( 'Category not found' );
    }

    $cat_id = $category->term_id;
    $cat_data = docy_prepare_category_header_data( $cat_id );

    ob_start();
    
    // Render Header Card (Pass true to indicate AJAX context)
    docy_render_category_header_card( $cat_data, true );

    // Render Posts
    echo '<div class="row category-posts-row" data-cat-slug="' . esc_attr($cat_slug) . '">';
    $args = array(
        'cat'            => $cat_id,
        'posts_per_page' => -1, // Load all posts for this category
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
    $query = new WP_Query($args);

    if ( $query->have_posts() ) :
        while ( $query->have_posts() ) : $query->the_post();
            get_template_part('template-parts/contents/content-grid');
        endwhile;
        wp_reset_postdata();
    else:
        echo '<div class="col-12"><p>No articles found in this category.</p></div>';
    endif;
    echo '</div>';

    $html = ob_get_clean();

    wp_send_json_success( array(
        'html' => $html,
        'cat_id' => $cat_id,
        'cat_name' => $category->name
    ));
}