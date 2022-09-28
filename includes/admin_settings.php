<?php

if ( ! defined( 'ABSPATH' ) )
{
	exit( 'restricted access' );
}

/**
 * Admin Settings Page
 *
 * @author Sajjad Hossain Sagor
 */
class SOCHM_ADMIN_SETTINGS
{
    private $settings_api;

    private $timezones;

    function __construct()
    {
    	$this->timezones = array(
		    'Pacific/Midway'       => "(GMT-11:00) Midway Island",
		    'US/Samoa'             => "(GMT-11:00) Samoa",
		    'US/Hawaii'            => "(GMT-10:00) Hawaii",
		    'US/Alaska'            => "(GMT-09:00) Alaska",
		    'US/Pacific'           => "(GMT-08:00) Pacific Time (US &amp; Canada)",
		    'America/Tijuana'      => "(GMT-08:00) Tijuana",
		    'US/Arizona'           => "(GMT-07:00) Arizona",
		    'US/Mountain'          => "(GMT-07:00) Mountain Time (US &amp; Canada)",
		    'America/Chihuahua'    => "(GMT-07:00) Chihuahua",
		    'America/Mazatlan'     => "(GMT-07:00) Mazatlan",
		    'America/Mexico_City'  => "(GMT-06:00) Mexico City",
		    'America/Monterrey'    => "(GMT-06:00) Monterrey",
		    'Canada/Saskatchewan'  => "(GMT-06:00) Saskatchewan",
		    'US/Central'           => "(GMT-06:00) Central Time (US &amp; Canada)",
		    'US/Eastern'           => "(GMT-05:00) Eastern Time (US &amp; Canada)",
		    'US/East-Indiana'      => "(GMT-05:00) Indiana (East)",
		    'America/Bogota'       => "(GMT-05:00) Bogota",
		    'America/Lima'         => "(GMT-05:00) Lima",
		    'America/Caracas'      => "(GMT-04:30) Caracas",
		    'Canada/Atlantic'      => "(GMT-04:00) Atlantic Time (Canada)",
		    'America/La_Paz'       => "(GMT-04:00) La Paz",
		    'America/Santiago'     => "(GMT-04:00) Santiago",
		    'Canada/Newfoundland'  => "(GMT-03:30) Newfoundland",
		    'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
		    'Greenland'            => "(GMT-03:00) Greenland",
		    'Atlantic/Stanley'     => "(GMT-02:00) Stanley",
		    'Atlantic/Azores'      => "(GMT-01:00) Azores",
		    'Atlantic/Cape_Verde'  => "(GMT-01:00) Cape Verde Is.",
		    'Africa/Casablanca'    => "(GMT) Casablanca",
		    'Europe/Dublin'        => "(GMT) Dublin",
		    'Europe/Lisbon'        => "(GMT) Lisbon",
		    'Europe/London'        => "(GMT) London",
		    'Africa/Monrovia'      => "(GMT) Monrovia",
		    'Europe/Amsterdam'     => "(GMT+01:00) Amsterdam",
		    'Europe/Belgrade'      => "(GMT+01:00) Belgrade",
		    'Europe/Berlin'        => "(GMT+01:00) Berlin",
		    'Europe/Bratislava'    => "(GMT+01:00) Bratislava",
		    'Europe/Brussels'      => "(GMT+01:00) Brussels",
		    'Europe/Budapest'      => "(GMT+01:00) Budapest",
		    'Europe/Copenhagen'    => "(GMT+01:00) Copenhagen",
		    'Europe/Ljubljana'     => "(GMT+01:00) Ljubljana",
		    'Europe/Madrid'        => "(GMT+01:00) Madrid",
		    'Europe/Paris'         => "(GMT+01:00) Paris",
		    'Europe/Prague'        => "(GMT+01:00) Prague",
		    'Europe/Rome'          => "(GMT+01:00) Rome",
		    'Europe/Sarajevo'      => "(GMT+01:00) Sarajevo",
		    'Europe/Skopje'        => "(GMT+01:00) Skopje",
		    'Europe/Stockholm'     => "(GMT+01:00) Stockholm",
		    'Europe/Vienna'        => "(GMT+01:00) Vienna",
		    'Europe/Warsaw'        => "(GMT+01:00) Warsaw",
		    'Europe/Zagreb'        => "(GMT+01:00) Zagreb",
		    'Europe/Athens'        => "(GMT+02:00) Athens",
		    'Europe/Bucharest'     => "(GMT+02:00) Bucharest",
		    'Africa/Cairo'         => "(GMT+02:00) Cairo",
		    'Africa/Harare'        => "(GMT+02:00) Harare",
		    'Europe/Helsinki'      => "(GMT+02:00) Helsinki",
		    'Europe/Istanbul'      => "(GMT+02:00) Istanbul",
		    'Asia/Jerusalem'       => "(GMT+02:00) Jerusalem",
		    'Europe/Kiev'          => "(GMT+02:00) Kyiv",
		    'Europe/Minsk'         => "(GMT+02:00) Minsk",
		    'Europe/Riga'          => "(GMT+02:00) Riga",
		    'Europe/Sofia'         => "(GMT+02:00) Sofia",
		    'Europe/Tallinn'       => "(GMT+02:00) Tallinn",
		    'Europe/Vilnius'       => "(GMT+02:00) Vilnius",
		    'Asia/Baghdad'         => "(GMT+03:00) Baghdad",
		    'Asia/Kuwait'          => "(GMT+03:00) Kuwait",
		    'Africa/Nairobi'       => "(GMT+03:00) Nairobi",
		    'Asia/Riyadh'          => "(GMT+03:00) Riyadh",
		    'Europe/Moscow'        => "(GMT+03:00) Moscow",
		    'Asia/Tehran'          => "(GMT+03:30) Tehran",
		    'Asia/Baku'            => "(GMT+04:00) Baku",
		    'Europe/Volgograd'     => "(GMT+04:00) Volgograd",
		    'Asia/Muscat'          => "(GMT+04:00) Muscat",
		    'Asia/Tbilisi'         => "(GMT+04:00) Tbilisi",
		    'Asia/Yerevan'         => "(GMT+04:00) Yerevan",
		    'Asia/Kabul'           => "(GMT+04:30) Kabul",
		    'Asia/Karachi'         => "(GMT+05:00) Karachi",
		    'Asia/Tashkent'        => "(GMT+05:00) Tashkent",
		    'Asia/Kolkata'         => "(GMT+05:30) Kolkata",
		    'Asia/Kathmandu'       => "(GMT+05:45) Kathmandu",
		    'Asia/Yekaterinburg'   => "(GMT+06:00) Ekaterinburg",
		    'Asia/Almaty'          => "(GMT+06:00) Almaty",
		    'Asia/Dhaka'           => "(GMT+06:00) Dhaka",
		    'Asia/Novosibirsk'     => "(GMT+07:00) Novosibirsk",
		    'Asia/Bangkok'         => "(GMT+07:00) Bangkok",
		    'Asia/Jakarta'         => "(GMT+07:00) Jakarta",
		    'Asia/Krasnoyarsk'     => "(GMT+08:00) Krasnoyarsk",
		    'Asia/Chongqing'       => "(GMT+08:00) Chongqing",
		    'Asia/Hong_Kong'       => "(GMT+08:00) Hong Kong",
		    'Asia/Kuala_Lumpur'    => "(GMT+08:00) Kuala Lumpur",
		    'Australia/Perth'      => "(GMT+08:00) Perth",
		    'Asia/Singapore'       => "(GMT+08:00) Singapore",
		    'Asia/Taipei'          => "(GMT+08:00) Taipei",
		    'Asia/Ulaanbaatar'     => "(GMT+08:00) Ulaan Bataar",
		    'Asia/Urumqi'          => "(GMT+08:00) Urumqi",
		    'Asia/Irkutsk'         => "(GMT+09:00) Irkutsk",
		    'Asia/Seoul'           => "(GMT+09:00) Seoul",
		    'Asia/Tokyo'           => "(GMT+09:00) Tokyo",
		    'Australia/Adelaide'   => "(GMT+09:30) Adelaide",
		    'Australia/Darwin'     => "(GMT+09:30) Darwin",
		    'Asia/Yakutsk'         => "(GMT+10:00) Yakutsk",
		    'Australia/Brisbane'   => "(GMT+10:00) Brisbane",
		    'Australia/Canberra'   => "(GMT+10:00) Canberra",
		    'Pacific/Guam'         => "(GMT+10:00) Guam",
		    'Australia/Hobart'     => "(GMT+10:00) Hobart",
		    'Australia/Melbourne'  => "(GMT+10:00) Melbourne",
		    'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
		    'Australia/Sydney'     => "(GMT+10:00) Sydney",
		    'Asia/Vladivostok'     => "(GMT+11:00) Vladivostok",
		    'Asia/Magadan'         => "(GMT+12:00) Magadan",
		    'Pacific/Auckland'     => "(GMT+12:00) Auckland",
		    'Pacific/Fiji'         => "(GMT+12:00) Fiji",
		);
    	
    	// add settings api wrapper
		require_once SOCHM_PLUGIN_PATH . 'includes/vendor/class.settings-api.php';
        
        $this->settings_api = new SOCHM_Settings_API;

        add_action( 'admin_init', array( $this, 'admin_init') );
        
        add_action( 'admin_menu', array( $this, 'admin_menu') );
    }

    public function admin_init()
    {
        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    public function admin_menu()
    {
        add_options_page( __( 'Store O/C Hours Manager', 'store-opening-closing-hours-manager' ), __( 'Store O/C Hours Manager', 'store-opening-closing-hours-manager' ), 'manage_options' , 'store-opening-closing-hours-manager.php', array( $this, 'render_settings_page' ) );
    }

    public function get_settings_sections()
    {
        $sections = array(
            array(
                'id'    => 'sochm_basic_settings',
                'title' => __( 'General Settings', 'store-opening-closing-hours-manager' )
            ),
            array(
                'id'    => 'sochm_hours_table',
                'title' => __( 'Opening Closing Hours Table', 'store-opening-closing-hours-manager' )
            )
        );
        
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    public function get_settings_fields()
    {
    	$table = "
	    	<div ng-app='sochm'>
		    	<table class='week_days_table' ng-controller='sochmController'>
		    		<thead>
		    			<tr>
		    				<th>" . __( 'Day', 'store-opening-closing-hours-manager' ) . "</th>
		    				<th>" . __( 'Status', 'store-opening-closing-hours-manager' ) . "</th>
		    				<th>" . __( 'From', 'store-opening-closing-hours-manager' ) . "</th>
		    				<th>" . __( 'To', 'store-opening-closing-hours-manager' ) . "</th>
		    			</tr>
		    		</thead>
		    		<tbody>
			    		<tr ng-repeat='(key, week ) in weekDaysTable' class='weekDay{{week.weekName}}'>
							<td><input type='hidden' name='store_open_close[{{key}}][fullName]' value='{{week.weekFullName}}'><input type='hidden' name='store_open_close[{{key}}][name]' value='{{week.weekName}}'>{{week.weekFullName}}</td>
							<td>
			    				<select class='store_open_close_status' name='store_open_close[{{key}}][status]'>
			    					<option value='open' ng-selected='week.status == \"open\"'>" . __( 'Open', 'store-opening-closing-hours-manager' ) . "</option>
			    					<option value='closed' ng-selected='week.status == \"closed\"'>" . __( 'Closed', 'store-opening-closing-hours-manager' ) . "</option>
			    				</select>
							</td>
							<td>
								<select class='time_dropdown' name='store_open_close[{{key}}][opening_time_hr]'>
									<option ng-repeat='opening_time_hr in week.opening_time_hr' ng-selected='opening_time_hr == week.selected_opening_time_hr' value='{{opening_time_hr}}'>{{opening_time_hr}}</option>
								</select>
								<select class='time_dropdown' name='store_open_close[{{key}}][opening_time_min]'>
									<option ng-repeat='opening_time_min in week.opening_time_min' ng-selected='opening_time_min == week.selected_opening_time_min' value='{{opening_time_min}}'>{{opening_time_min}}</option>
								</select>
							</td>
							<td style='min-width: 20rem;'>
								<select class='time_dropdown' name='store_open_close[{{key}}][closing_time_hr]'>
									<option ng-repeat='closing_time_hr in week.closing_time_hr' ng-selected='closing_time_hr == week.selected_closing_time_hr' value='{{closing_time_hr}}'>{{closing_time_hr}}</option>
								</select>
								<select class='time_dropdown' name='store_open_close[{{key}}][closing_time_min]'>
									<option ng-repeat='closing_time_min in week.closing_time_min' ng-selected='closing_time_min == week.selected_closing_time_min' value='{{closing_time_min}}'>{{closing_time_min}}</option>
								</select>
								<button style='margin-left: 15px;' class='button addNewOpeningClosing' type='button'>{{addBtnText}}</button>
								<button style='margin-left: 15px;' class='button removeOpeningClosing' type='button'>" . __( 'Remove', 'store-opening-closing-hours-manager' ) . "</button>
							</td>
						</tr>
					</tbody>
		    	</table>
		    	<style type='text/css'>
		    		.form-table td {
					    padding: 15px 0px;
					}
		    	</style>
	    	</div>
	    ";
		
		$settings_fields = array(
            'sochm_basic_settings' => array(
                array(
                    'name'    => 'enable_manager',
                    'label'   => __( 'Enable The Manager', 'store-opening-closing-hours-manager' ),
                    'type'    => 'checkbox',
                    'desc'    => __( 'Checking this box will enable the plugin functionality.', 'store-opening-closing-hours-manager' )
                ),
                array(
                    'name'    => 'enable_widget',
                    'label'   => __( 'Enable Widget', 'store-opening-closing-hours-manager' ),
                    'type'    => 'checkbox',
                    'desc'    => __( 'Checking this box will add a new widget to show Store Opening & Closing Hours in a table.', 'store-opening-closing-hours-manager' )
                ),
                array(
                    'name'    => 'close_store',
                    'label'   => __( 'Close The Store Manually', 'store-opening-closing-hours-manager' ),
                    'type'    => 'checkbox',
                    'desc'    => __( 'Checking this box will put the store in a closed state immediately regardless of the time.', 'store-opening-closing-hours-manager' )
                ),
                array(
                    'name'    => 'enable_timer',
                    'label'   => __( 'Enable Timer', 'store-opening-closing-hours-manager' ),
                    'type'    => 'checkbox',
                    'desc'    => __( 'Checking this box will add a countdown timer for remaining time to open the store & store closing soon notice.', 'store-opening-closing-hours-manager' )
                ),
                array(
                    'name'    => 'timer_design',
                    'label'   => __( 'Timer Design', 'store-opening-closing-hours-manager' ),
                    'type'    => 'select',
                    'options' => array(
                    	'0' => 'Default',
                        '1' => 'Boxed',
                    	'2' => 'Boxed With Flipping',
                        '3' => 'Circular Border',
                        '4' => 'Circular Border With Filling',
                    ),
                    'desc'    => __( 'Select the timer design type for front end user to see when store is closed.', 'store-opening-closing-hours-manager' ),
                ),
                array(
                    'name'    => 'timezone',
                    'label'   => __( 'Timezone', 'store-opening-closing-hours-manager' ),
                    'type'    => 'select',
                    'options' => $this->timezones,
                    'desc'    => __( 'Select timezone for your store to use in calculating the store opening/closing hours.', 'store-opening-closing-hours-manager' ),
                ),
                array(
                    'name'    => 'show_notice_in_wp_admin',
                    'label'   => __( 'Show Notice In WP Admin', 'store-opening-closing-hours-manager' ),
                    'type'    => 'checkbox',
                    'desc'    => __( 'Checking this box will add a new "Menu Bar" showing Open/Closed status of store for the current time.', 'store-opening-closing-hours-manager' )
                ),
                array(
                    'name'    => 'show_notice_in_front',
                    'label'   => __( 'Show Notice In Front', 'store-opening-closing-hours-manager' ),
                    'type'    => 'checkbox',
                    'desc'    => __( 'Checking this box will show notice in front.', 'store-opening-closing-hours-manager' )
                ),
                array(
                    'name'    => 'notice_type',
                    'label'   => __( 'Notice Type', 'store-opening-closing-hours-manager' ),
                    'type'    => 'select',
                    'options' => array(
                    	'0' => 'Toast',
                    	'1' => 'Dialog',
                        '2' => 'Sticky Header',
                        '3' => 'Sticky Footer',
                        '4' => 'Static Single Page',
                        '5' => 'Woocommerce Notice',
                    ),
                    'desc'    => __( 'Select the notice type for front end user to see when store is closed.', 'store-opening-closing-hours-manager' ),
                ),
                array(
                    'name'    => 'notice_message',
                    'label'   => __( 'Store Closed Notice Message', 'store-opening-closing-hours-manager' ),
                    'type'    => 'textarea',
                    'desc'    => __( 'Add notice message for front end user to see when store is closed.', 'store-opening-closing-hours-manager' ),
                    'default'    => __( "Store is closed right now! You can't purchase any items. Please come back when store is open again!", 'store-opening-closing-hours-manager' ),
                ),
                array(
                    'name'    => 'enable_store_going_to_close_soon_notice',
                    'label'   => __( "Enable 'Store Going To Close Soon Notice'", 'store-opening-closing-hours-manager' ),
                    'type'    => 'checkbox',
                    'desc'    => __( 'Checking this box will show a notice that store is closing soon.', 'store-opening-closing-hours-manager' )
                ),
                array(
                    'name'    => 'minutes_before_store_going_to_close_soon_notice',
                    'label'   => __( "Minutes Before Enabling 'Store Going To Close Soon Notice'", 'store-opening-closing-hours-manager' ),
                    'type'    => 'number',
                    'desc'    => __( 'How many minutes before do you want to show the notice to end user?', 'store-opening-closing-hours-manager' ),
                    'default' => 30
                ),
                array(
                    'name'    => 'store_going_to_close_soon_notice_message',
                    'label'   => __( "'Store Going To Close Soon Notice' Message", 'store-opening-closing-hours-manager' ),
                    'type'    => 'textarea',
                    'desc'    => __( 'Add notice message for front end user to see when store is about to close.', 'store-opening-closing-hours-manager' ),
                    'default' => __( 'Store is going to close soon! Please complete your purchase before store get closed!', 'store-opening-closing-hours-manager' ),
                ),
                array(
                    'name'    => 'auto_clear_carts',
                    'label'   => __( 'Auto Clear Existed Carts', 'store-opening-closing-hours-manager' ),
                    'type'    => 'checkbox',
                    'desc'    => __( 'Do you want to clear old already added carts products when store is closed?', 'store-opening-closing-hours-manager' )
                ),
                array(
                    'name'    => 'disable_checkout',
                    'label'   => __( 'Disable Checkout', 'store-opening-closing-hours-manager' ),
                    'type'    => 'checkbox',
                    'desc'    => __( 'Do you want to disable the checkout option when store is closed?', 'store-opening-closing-hours-manager' )
                ),
                array(
                    'name'    => 'remove_proceed_to_checkout_button',
                    'label'   => __( 'Remove The "Proceed To Checkout" Button', 'store-opening-closing-hours-manager' ),
                    'type'    => 'checkbox',
                    'desc'    => __( 'Remove The "Proceed To Checkout" Button Entirely When Store is Closed.', 'store-opening-closing-hours-manager' )
                ),
                array(
                    'name'    => 'disable_add_to_cart',
                    'label'   => __( 'Disable Add To Cart', 'store-opening-closing-hours-manager' ),
                    'type'    => 'checkbox',
                    'desc'    => __( 'Do you want to prevent user from adding products to cart when store is closed?', 'store-opening-closing-hours-manager' )
                ),
                array(
                    'name'    => 'remove_add_to_cart_button',
                    'label'   => __( 'Remove The "Add To Cart" Button', 'store-opening-closing-hours-manager' ),
                    'type'    => 'checkbox',
                    'desc'    => __( 'Remove The "Add To Cart" Button Entirely When Store is Closed.', 'store-opening-closing-hours-manager' )
                ),
                array(
                    'name'    => 'disable_add_to_cart_message',
                    'label'   => __( 'Message When Clicked "Add To Cart" Button', 'store-opening-closing-hours-manager' ),
                    'type'    => 'textarea',
                    'desc'    => __( 'Show a custom message when user tries to click add to cart button when store is closed?', 'store-opening-closing-hours-manager' ),
                    'default'    => __( "Store is closed now! You can's purchase this item. Return back when store is open again!", 'store-opening-closing-hours-manager' ),
                ),
                array(
                    'name'    => 'notice_text_color',
                    'label'   => __( 'Notice Text Color', 'store-opening-closing-hours-manager' ),
                    'type'    => 'color',
                    'desc'    => __( 'What Color Should be the notice text? Default is White #FFFFFF.', 'store-opening-closing-hours-manager' ),
                ),
                array(
                    'name'    => 'notice_boxbg_color',
                    'label'   => __( 'Notice Box Background Color', 'store-opening-closing-hours-manager' ),
                    'type'    => 'color',
                    'desc'    => __( 'What Color Should be the notice Box Background? Default is Red #FF0000.', 'store-opening-closing-hours-manager' ),
                ),
            ),
			'sochm_hours_table' => array(
                array(
                    'name'    => 'hours_table',
                    'type'    => 'html',
                    'desc'    => $table,
                ),
            )
        );

        return $settings_fields;
    }

    /**
     * Render settings fields
     *
     */
    public function render_settings_page()
    {    
        echo '<div class="wrap">';

	        $this->settings_api->show_navigation();
	       
	        $this->settings_api->show_forms();

        echo '</div>';
    }

    /**
	 * Returns option value
	 *
	 * @return string|array option value
	 */
	static public function get_option( $option, $section, $default = '' )
    {
	    $options = get_option( $section );

	    if ( isset( $options[$option] ) )
        {
	        return $options[$option];
	    }

	    return $default;
	}
}

$SOCHM_ADMIN_SETTINGS = new SOCHM_ADMIN_SETTINGS();
