<?php

if ( ! defined( 'ABSPATH' ) )
{
	exit( 'restricted access' );
}

/**
 * Class to handle plugin public functionality
 */
if ( ! class_exists( 'SOCHM_ADMIN' ) )
{
	class SOCHM_ADMIN
	{
		public static function run()
		{
			// check if plugin is active
			$enabled  = SOCHM_UTIL::get_option( 'enable_manager', 'sochm_basic_settings' );

			if ( $enabled !== 'on' )
			{
				return;
			}

			// check if show wp admin bar notice is checked
			if ( SOCHM_UTIL::get_option( 'show_notice_in_wp_admin', 'sochm_basic_settings' ) == 'on' )
			{
				add_action( 'admin_bar_menu', array( 'SOCHM_ADMIN', 'admin_menu_bar_item' ), 500 );
			}

			// check if enable widget is checked
			if ( SOCHM_UTIL::get_option( 'enable_widget', 'sochm_basic_settings' ) == 'on' )
			{
				add_action( 'widgets_init', array( 'SOCHM_ADMIN', 'register_widget' ) );
			}
		}

		public static function admin_menu_bar_item( WP_Admin_Bar $admin_bar )
		{
		    if ( ! current_user_can( 'manage_options' ) )
		    {
		        return;
		    }

		    $isStoreClosed = SOCHM_UTIL::isStoreClosed();
		    
		    $admin_bar->add_menu( array(
		        'id'	 => $isStoreClosed ? 'sochm_notice_in_wp_admin_closed' : 'sochm_notice_in_wp_admin_open',
		        'parent' => null,
		        'group'  => null,
		        'title'  => $isStoreClosed ? __( 'Store Status : Closed', 'store-opening-closing-hours-manager' ) : __( 'Store Status : Open', 'store-opening-closing-hours-manager' ),
		        'href'   => null,
		    ) );
		}

		public static function register_widget()
		{
			register_widget( 'sochm_widget' );
		}
	}
	
	SOCHM_ADMIN::run();
}
