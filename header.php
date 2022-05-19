<!DOCTYPE html>

<html class="no-js" <?php language_attributes(); ?>>

	<head>

		<meta http-equiv="content-type" content="<?php bloginfo( 'html_type' ); ?>" charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" >

		<link rel="profile" href="http://gmpg.org/xfn/11">

		<?php wp_head(); ?>

	</head>

	<body <?php body_class(); ?>>

		<?php 
		if ( function_exists( 'wp_body_open' ) ) {
			wp_body_open(); 
		}
		?>

		<a class="skip-link button" href="#site-content"><?php _e( 'Skip to the content', 'listy' ); ?></a>

		<header class="site-header group">

            <div class="container">

                <?php $site_title_elem = is_front_page() || ( is_home() && get_option( 'show_on_front' ) == 'posts' ) ? 'h1' : 'p'; ?>

                <div class="custom-header">
                    <div class="header-left">
                        <<?php echo $site_title_elem; ?> class="site-title"><a href="<?php echo esc_url( home_url() ); ?>" class="site-name"><?php bloginfo( 'name' ); ?></a></<?php echo $site_title_elem; ?>>

                        <?php if ( get_bloginfo( 'description' ) ) : ?>

                            <div class="site-description"><?php echo wpautop( get_bloginfo( 'description' ) ); ?></div>

                        <?php endif; ?>
                    </div>


                    <div class="menu-wrapper custom-desktop">

                        <ul class="main-menu desktop">

                            <?php

                            if ( has_nav_menu( 'main-menu' ) ) {

                                $main_menu_args = array(
                                    'container' 		=> '',
                                    'items_wrap' 		=> '%3$s',
                                    'theme_location' 	=> 'main-menu',
                                );

                                wp_nav_menu( $main_menu_args );

                            } else {

                                $fallback_args = array(
                                    'container' => '',
                                    'title_li' 	=> '',
                                );

                                wp_list_pages( $fallback_args );
                            }
                            ?>
                        </ul>

                    </div><!-- .menu-wrapper -->

                </div>

                <div class="nav-toggle">
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
            </div>
		</header><!-- header -->

		<div class="mobile-menu-wrapper">

			<ul class="main-menu mobile">
				<?php
				if ( has_nav_menu( 'main-menu' ) ) {
					wp_nav_menu( $main_menu_args );
				} else {
					wp_list_pages( $fallback_args );
				}
				if ( ! get_theme_mod( 'listy_hide_social', false ) ) : ?>
					<li class="toggle-mobile-search-wrapper"><a href="#" class="toggle-mobile-search"><?php _e( 'Search', 'listy' ); ?></a></li>
				<?php endif; ?>
			</ul><!-- .main-menu.mobile -->

			<?php if ( has_nav_menu( 'social-menu' ) && ( ! get_theme_mod( 'listy_hide_social', false ) || is_customize_preview() ) ) : ?>

				<div class="social-menu mobile">

					<ul class="social-menu-inner">

						<?php wp_nav_menu( $social_args ); ?>

					</ul><!-- .social-menu-inner -->

				</div><!-- .social-menu -->

			<?php endif; ?>

		</div><!-- .mobile-menu-wrapper -->

<!--		--><?php //if ( ! get_theme_mod( 'listy_hide_social', false ) ) : ?>

			<div class="mobile-search">

				<div class="untoggle-mobile-search"></div>

				<?php get_search_form(); ?>

				<div class="mobile-results">

					<div class="results-wrapper"></div>

				</div>

			</div><!-- .mobile-search -->

			<div class="search-overlay">

				<?php get_search_form(); ?>

			</div><!-- .search-overlay -->

<!--		--><?php //endif; ?>

		<main class="site-content" id="site-content">
            <div class="container">
