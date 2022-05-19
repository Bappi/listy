<?php

/* THEME SETUP
------------------------------------------------ */

if ( ! function_exists( 'listy_setup' ) ) {
	function listy_setup() {

		// Automatic feed
		add_theme_support( 'automatic-feed-links' );

		// Set content-width
		global $content_width;
		if ( ! isset( $content_width ) ) {
			$content_width = 560;
		}

		// Post thumbnail support
		add_theme_support( 'post-thumbnails' );

		// Post thumbnail size
		set_post_thumbnail_size( 1200, 9999 );

		// Custom image sizes
		add_image_size( 'listy_preview-image', 600, 9999 );

		// Background color
		add_theme_support( 'custom-background', array(
			'default-color' => 'ffffff',
		) );

		// Title tag support
		add_theme_support( 'title-tag' );

		// Add nav menu
		register_nav_menu( 'main-menu', __( 'Main menu', 'listy' ) );
		register_nav_menu( 'social-menu', __( 'Social links', 'listy' ) );

		// Add excerpts to pages
		add_post_type_support( 'page', array( 'excerpt' ) );

		// HTML5 semantic markup
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

		// Make the theme translation ready
		load_theme_textdomain( 'listy', get_template_directory() . '/languages' );

	}
} // End if().
add_action( 'after_setup_theme', 'listy_setup' );


/* INCLUDE REQUIRED FILES
------------------------------------------------ */

// Handle Customizer settings
require get_template_directory() . '/inc/classes/class-listy-customize.php';


/*	-----------------------------------------------------------------------------------------------
	ENQUEUE STYLES
--------------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'listy_load_style' ) ) :
	function listy_load_style() {

		$dependencies = array();
		$theme_version = wp_get_theme( 'listy' )->get( 'Version' );

		/**
		 * Translators: If there are characters in your language that are not
		 * supported by the theme fonts, translate this to 'off'. Do not translate
		 * into your own language.
		 */
		$google_fonts = _x( 'on', 'Google Fonts: on or off', 'listy' );

		if ( 'off' !== $google_fonts ) {

			// Register Google Fonts
			wp_register_style( 'listy-fonts', '//fonts.googleapis.com/css?family=Archivo:400,400i,600,600i,700,700i&amp;subset=latin-ext', false, 1.0, 'all' );
			$dependencies[] = 'listy-fonts';

		}

		wp_register_style( 'fontawesome', get_template_directory_uri() . '/assets/css/font-awesome.css', null );
		$dependencies[] = 'fontawesome';

		wp_enqueue_style( 'listy-style', get_template_directory_uri() . '/style.css', $dependencies, $theme_version );
	}
	add_action( 'wp_enqueue_scripts', 'listy_load_style' );
endif;


/*	-----------------------------------------------------------------------------------------------
	ADD EDITOR STYLES
--------------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'listy_add_editor_styles' ) ) :
	function listy_add_editor_styles() {

		$editor_styles = array( 'assets/css/listy-classic-editor-styles.css' );

		/**
		 * Translators: If there are characters in your language that are not
		 * supported by the theme fonts, translate this to 'off'. Do not translate
		 * into your own language.
		 */
		$google_fonts = _x( 'on', 'Google Fonts: on or off', 'listy' );

		if ( 'off' !== $google_fonts ) {
			$editor_styles[] = '//fonts.googleapis.com/css?family=Archivo:400,400i,600,700,700i&amp;subset=latin-ext';
		}

		add_editor_style( $editor_styles );

	}
	add_action( 'init', 'listy_add_editor_styles' );
endif;


/*	-----------------------------------------------------------------------------------------------
	DEACTIVATE DEFAULT CORE GALLERY STYLES
	Only applies to the shortcode gallery.
--------------------------------------------------------------------------------------------------- */

add_filter( 'use_default_gallery_style', '__return_false' );


/* ENQUEUE SCRIPTS
------------------------------------------------ */

if ( ! function_exists( 'listy_enqueue_scripts' ) ) :
	function listy_enqueue_scripts() {

		$theme_version = wp_get_theme( 'listy' )->get( 'Version' );

		wp_enqueue_script( 'listy_global', get_template_directory_uri() . '/assets/js/global.js', array( 'jquery', 'imagesloaded', 'masonry' ), $theme_version, true );

		// Enqueue comment reply
		if ( ( ! is_admin() ) && is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}

		global $wp_query;

		// AJAX PAGINATION
		wp_localize_script( 'listy_global', 'listy_ajaxpagination', array(
			'ajaxurl'		=> admin_url( 'admin-ajax.php' ),
			'query_vars'	=> wp_json_encode( $wp_query->query ),
		) );

	}
	add_action( 'wp_enqueue_scripts', 'listy_enqueue_scripts' );
endif;


/*	-----------------------------------------------------------------------------------------------
	FILTER POST_CLASS
--------------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'listy_post_classes' ) ) {
	function listy_post_classes( $classes ) {

		// Class indicating presence/lack of post thumbnail
		$classes[] = ( has_post_thumbnail() ? 'has-thumbnail' : 'missing-thumbnail' );

		// Class indicating lack of title
		if ( ! get_the_title() ) $classes[] = 'no-title';

		return $classes;
	}
}
add_action( 'post_class', 'listy_post_classes' );


/*	-----------------------------------------------------------------------------------------------
	FILTER BODY_CLASS
--------------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'listy_body_classes' ) ) {
	function listy_body_classes( $classes ) {

		// Check whether we're in the customizer preview
		if ( is_customize_preview() ) {
			$classes[] = 'customizer-preview';
		}

		// Hide social buttons
		if ( get_theme_mod( 'listy_hide_social' ) ) {
			$classes[] = 'hide-social';
		}

		// White bg class
		if ( get_theme_mod( 'listy_accent_color' ) == '#ffffff' && ( ! get_background_color() || get_background_color() == 'ffffff' ) ) {
			$classes[] = 'white-bg';
		}

		// Check whether the custom backgrounds are both set to the same thing
		if ( get_theme_mod( 'listy_accent_color' ) && get_background_color() && ltrim( get_theme_mod( 'listy_accent_color' ), '#' ) == get_background_color() ) {
			$classes[] = 'same-custom-bgs';
		}

		// Dark sidebar text
		if ( get_theme_mod( 'listy_dark_sidebar_text' ) ) {
			$classes[] = 'dark';
		}

		// Add short class for resume page template
		if ( is_page_template( 'resume-page-template.php' ) ) {
			$classes[] = 'resume-template';
		}

		// Add short class for full width page template
		if ( is_page_template( 'full-width-page-template.php' ) ) {
			$classes[] = 'full-width-template';
		}

		return $classes;
	}
} // End if().
add_action( 'body_class', 'listy_body_classes' );


/*	-----------------------------------------------------------------------------------------------
	NO-JS CLASS
--------------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'listy_has_js' ) ) {
	function listy_has_js() {
		?>
		<script>jQuery( 'html' ).removeClass( 'no-js' ).addClass( 'js' );</script>
		<?php
	}
}
add_action( 'wp_head', 'listy_has_js' );


/*	-----------------------------------------------------------------------------------------------
	AJAX SEARCH RESULTS
	This function is called to load ajax search results on mobile.
--------------------------------------------------------------------------------------------------- */


if ( ! function_exists( 'listy_ajax_results' ) ) {
	function listy_ajax_results() {

		$string = json_decode( stripslashes( $_POST['query_data'] ), true );

		if ( $string ) :

			$args = array(
				's'					=> $string,
				'posts_per_page'	=> 5,
				'post_status'		=> 'publish',
			);

			$ajax_query = new WP_Query( $args );

			if ( $ajax_query->have_posts() ) {

				?>

				<p class="results-title"><?php _e( 'Search Results', 'listy' ); ?></p>

				<ul>

					<?php

					// Custom loop
					while ( $ajax_query->have_posts() ) :

						$ajax_query->the_post();

						// Load the appropriate content template
						get_template_part( 'content-mobile-search' );

					// End the loop
					endwhile;

					?>

				</ul>

				<?php if ( $ajax_query->max_num_pages > 1 ) : ?>

					<a class="show-all" href="<?php echo esc_url( home_url( '?s=' . $string ) ); ?>"><?php _e( 'Show all', 'listy' ); ?></a>

				<?php endif; ?>

				<?php

			} else {

				echo '<p class="no-results-message">' . __( 'We could not find anything that matches your search query. Please try again.', 'listy' ) . '</p>';

			} // End if().

		endif; // End if().

		die();
	}
} // End if().
add_action( 'wp_ajax_nopriv_ajax_pagination', 'listy_ajax_results' );
add_action( 'wp_ajax_ajax_pagination', 'listy_ajax_results' );


/*	-----------------------------------------------------------------------------------------------
	GET AND OUTPUT ARCHIVE TYPE
--------------------------------------------------------------------------------------------------- */

/* GET THE TYPE */

if ( ! function_exists( 'listy_get_archive_type' ) ) {
	function listy_get_archive_type() {
		if ( is_category() ) {
			$type = __( 'Category', 'listy' );
		} elseif ( is_tag() ) {
			$type = __( 'Tag', 'listy' );
		} elseif ( is_author() ) {
			$type = __( 'Author', 'listy' );
		} elseif ( is_year() ) {
			$type = __( 'Year', 'listy' );
		} elseif ( is_month() ) {
			$type = __( 'Month', 'listy' );
		} elseif ( is_day() ) {
			$type = __( 'Date', 'listy' );
		} elseif ( is_post_type_archive() ) {
			$type = __( 'Post Type', 'listy' );
		} elseif ( is_tax() ) {
			$term = get_queried_object();
			$taxonomy = $term->taxonomy;
			$taxonomy_labels = get_taxonomy_labels( get_taxonomy( $taxonomy ) );
			$type = $taxonomy_labels->name;
		} else if ( is_search() ) {
			$type = __( 'Search Results', 'listy' );
		} else if ( is_home() && get_theme_mod( 'listy_home_title' ) ) {
			$type = __( 'Introduction', 'listy' );
		} else {
			$type = __( 'Archives', 'listy' );
		}

		return $type;
	}
}

/* OUTPUT THE TYPE */

if ( ! function_exists( 'listy_the_archive_type' ) ) {
	function listy_the_archive_type() {
		$type = listy_get_archive_type();

		echo $type;
	}
}


/*	-----------------------------------------------------------------------------------------------
	FILTER ARCHIVE TITLE

	@param	$title string		The initial title.
--------------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'listy_remove_archive_title_prefix' ) ) :
	function listy_remove_archive_title_prefix( $title ) {

		// A duplicate of the core archive title conditional, but without the prefix.
		if ( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif ( is_tag() ) {
			$title = single_tag_title( '#', false );
		} elseif ( is_author() ) {
			$title = '<span class="vcard">' . get_the_author() . '</span>';
		} elseif ( is_year() ) {
			$title = get_the_date( 'Y' );
		} elseif ( is_month() ) {
			$title = get_the_date( 'F Y' );
		} elseif ( is_day() ) {
			$title = get_the_date( get_option( 'date_format' ) );
		} elseif ( is_tax( 'post_format' ) ) {
			if ( is_tax( 'post_format', 'post-format-aside' ) ) {
				$title = _x( 'Aside', 'post format archive title', 'listy' );
			} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
				$title = _x( 'Galleries', 'post format archive title', 'listy' );
			} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
				$title = _x( 'Images', 'post format archive title', 'listy' );
			} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
				$title = _x( 'Videos', 'post format archive title', 'listy' );
			} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
				$title = _x( 'Quotes', 'post format archive title', 'listy' );
			} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
				$title = _x( 'Links', 'post format archive title', 'listy' );
			} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
				$title = _x( 'Statuses', 'post format archive title', 'listy' );
			} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
				$title = _x( 'Audio', 'post format archive title', 'listy' );
			} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
				$title = _x( 'Chats', 'post format archive title', 'listy' );
			}
		} elseif ( is_post_type_archive() ) {
			$title = post_type_archive_title( '', false );
		} elseif ( is_tax() ) {
			$title = single_term_title( '', false );
		} elseif ( is_home() ) {
			if ( get_theme_mod( 'listy_home_title' ) ) {
				$title = get_theme_mod( 'listy_home_title' );
			} elseif ( get_option( 'page_for_posts' ) ) {
				$title = get_the_title( get_option( 'page_for_posts' ) );
			} else {
				$title = '';
			}
		} elseif ( is_search() ) {
			$title = '&ldquo;' . get_search_query() . '&rdquo;';
		} else {
			$title = __( 'Archives', 'listy' );
		}

		return $title;

	}
	add_filter( 'get_the_archive_title', 'listy_remove_archive_title_prefix' );
endif;


/*	-----------------------------------------------------------------------------------------------
	FILTER ARCHIVE DESCRIPTION

	@param	$description string		The initial description.
--------------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'listy_filter_archive_description' ) ) :
	function listy_filter_archive_description( $description ) {
		
		// On search, show a string describing the results of the search.
		if ( is_search() ) {
			global $wp_query;
			if ( $wp_query->found_posts ) {
				/* Translators: %s = Number of results */
				$description = sprintf( _x( 'We found %s matching your search query.', 'Translators: %s = the number of search results', 'listy' ), $wp_query->found_posts . ' ' . ( 1 == $wp_query->found_posts ? __( 'result', 'listy' ) : __( 'results', 'listy' ) ) );
			} else {
				/* Translators: %s = the search query */
				$description = sprintf( _x( 'We could not find any results for the search query "%s". You can try again through the form below.', 'Translators: %s = the search query', 'listy' ), get_search_query() );
			}
		}

		return $description;

	}
	add_filter( 'get_the_archive_description', 'listy_filter_archive_description' );
endif;


/*	-----------------------------------------------------------------------------------------------
	PRE_GET_POSTS
--------------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'listy_sort_search_posts_by_date' ) ) {
	function listy_sort_search_posts_by_date( $query ) {

		// In search, order results by date
		if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
			$query->set( 'orderby', 'date' );
		}

	}
}
add_action( 'pre_get_posts', 'listy_sort_search_posts_by_date' );


/*	-----------------------------------------------------------------------------------------------
	CUSTOM COMMENT OUTPUT
--------------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'listy_comment' ) ) :
	function listy_comment( $comment, $args, $depth ) {

		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
				global $post;
				?>

				<div <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
					<?php _e( 'Pingback:', 'listy' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( 'Edit', 'listy' ) ); ?>

				<?php

				break;

			default :
				global $post;
				?>
				<div <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">

					<div id="comment-<?php comment_ID(); ?>">

						<header class="comment-meta">

							<span class="comment-author">
								<cite>
									<?php echo get_comment_author_link(); ?>
								</cite>

								<?php
								if ( $comment->user_id === $post->post_author ) {
									echo '<span class="comment-by-post-author"> (' . __( 'Author', 'listy' ) . ')</span>';
								}
								?>
							</span>

							<span class="comment-date">
								<a class="comment-date-link" href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ) ?>" title="<?php echo get_comment_date() . ' ' . __( 'at', 'listy' ) . ' ' . get_comment_time(); ?>"><?php echo get_comment_date( get_option( 'date_format' ) ); ?></a>
							</span>

							<?php
							comment_reply_link( array(
								'after'			=> '</span>',
								'before'		=> '<span class="comment-reply">',
								'depth'			=> $depth,
								'max_depth' 	=> $args['max_depth'],
								'reply_text' 	=> __( 'Reply', 'listy' ),
							) );
							?>

						</header>

						<div class="comment-content entry-content">

							<?php comment_text(); ?>

						</div><!-- .comment-content -->

						<div class="comment-actions">
							<?php if ( '0' == $comment->comment_approved ) : ?>
								<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'listy' ); ?></p>
							<?php endif; ?>
						</div><!-- .comment-actions -->

					</div><!-- .comment -->

			<?php
			break;
		endswitch;

	}
endif; // End if().


/*	-----------------------------------------------------------------------------------------------
	ADMIN NOTICES
--------------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'listy_admin_notices' ) ) :
	function listy_admin_notices() {

		// Show notice about posts per page on theme activation, if the setting isn't set already
		if ( isset( $_GET['activated'] ) && true == $_GET['activated'] && 'listy' == get_option( 'template' ) && get_option( 'posts_per_page' ) < 999 ) : ?>

			<div class="notice notice-info is-dismissible">
				<?php /* Translators: %1$1s = opening link to the demo site, %2$2s = closing link tag, %3$3s = link to the reading options, %4$4s = closing link tag */ ?>
				<p><?php printf( _x( 'To make listy display like the %1$1sdemo site%2$2s, with all posts listed on archive pages, you need to change the "Blog pages show at most" setting in %3$3sSettings > Reading%4$4s to a value exceeding the number of posts on your site.', 'Translators: %1$1s = opening link to the demo site, %2$2s = closing link tag, %3$3s = link to the reading options, %4$4s = closing link tag', 'listy' ), '<a href="https://www.andersnoren.se/themes/listy/">', '</a>', '<a href="' . admin_url( 'options-reading.php' ) . '">', '</a>' ); ?></p>
			</div>

			<?php
		endif;

	}
	add_action( 'listy_admin_notices', 'showAdminMessages' );
endif;


/* ---------------------------------------------------------------------------------------------
   SPECIFY BLOCK EDITOR SUPPORT
------------------------------------------------------------------------------------------------ */

if ( ! function_exists( 'listy_add_block_editor_features' ) ) :
	function listy_add_block_editor_features() {

		/* Block Editor Features ------------- */

		add_theme_support( 'align-wide' );

		/* Block Editor Palette -------------- */

		add_theme_support( 'editor-color-palette', array(
			array(
				'name' 	=> _x( 'Black', 'Name of the black color in the Gutenberg palette', 'listy' ),
				'slug' 	=> 'black',
				'color' => '#121212',
			),
			array(
				'name' 	=> _x( 'Dark Gray', 'Name of the dark gray color in the Gutenberg palette', 'listy' ),
				'slug' 	=> 'dark-gray',
				'color' => '#333',
			),
			array(
				'name' 	=> _x( 'Medium Gray', 'Name of the medium gray color in the Gutenberg palette', 'listy' ),
				'slug' 	=> 'medium-gray',
				'color' => '#555',
			),
			array(
				'name' 	=> _x( 'Light Gray', 'Name of the light gray color in the Gutenberg palette', 'listy' ),
				'slug' 	=> 'light-gray',
				'color' => '#777',
			),
			array(
				'name' 	=> _x( 'White', 'Name of the white color in the Gutenberg palette', 'listy' ),
				'slug' 	=> 'white',
				'color' => '#fff',
			),
		) );

		/* Block Editor Font Sizes ----------- */

		add_theme_support( 'editor-font-sizes', array(
			array(
				'name' 		=> _x( 'Small', 'Name of the small font size in Gutenberg', 'listy' ),
				'shortName' => _x( 'S', 'Short name of the small font size in the Gutenberg editor.', 'listy' ),
				'size' 		=> 16,
				'slug' 		=> 'small',
			),
			array(
				'name' 		=> _x( 'Normal', 'Name of the regular font size in Gutenberg', 'listy' ),
				'shortName' => _x( 'N', 'Short name of the regular font size in the Gutenberg editor.', 'listy' ),
				'size' 		=> 18,
				'slug' 		=> 'normal',
			),
			array(
				'name' 		=> _x( 'Large', 'Name of the large font size in Gutenberg', 'listy' ),
				'shortName' => _x( 'L', 'Short name of the large font size in the Gutenberg editor.', 'listy' ),
				'size' 		=> 24,
				'slug' 		=> 'large',
			),
			array(
				'name' 		=> _x( 'Larger', 'Name of the larger font size in Gutenberg', 'listy' ),
				'shortName' => _x( 'XL', 'Short name of the larger font size in the Gutenberg editor.', 'listy' ),
				'size' 		=> 28,
				'slug' 		=> 'larger',
			),
		) );

	}
	add_action( 'after_setup_theme', 'listy_add_block_editor_features' );
endif;


/* ---------------------------------------------------------------------------------------------
   BLOCK EDITOR STYLES
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'listy_block_editor_styles' ) ) :
	function listy_block_editor_styles() {

		$dependencies = array();
		$theme_version = wp_get_theme( 'listy' )->get( 'Version' );

		/**
		 * Translators: If there are characters in your language that are not
		 * supported by the theme fonts, translate this to 'off'. Do not translate
		 * into your own language.
		 */
		$google_fonts = _x( 'on', 'Google Fonts: on or off', 'listy' );

		if ( 'off' !== $google_fonts ) {

			// Register Google Fonts
			wp_register_style( 'listy-block-editor-styles-font', '//fonts.googleapis.com/css?family=Archivo:400,400i,600,600i,700,700i&amp;subset=latin-ext', false, 1.0, 'all' );
			$dependencies[] = 'listy-block-editor-styles-font';

		}

		// Enqueue the editor styles
		wp_enqueue_style( 'listy-block-editor-styles', get_theme_file_uri( '/assets/css/listy-block-editor-styles.css' ), $dependencies, $theme_version, 'all' );

	}
	add_action( 'enqueue_block_editor_assets', 'listy_block_editor_styles', 1 );
endif;

?>
