<?php

if ( ! defined( 'ABSPATH' ) )
{
	exit( 'restricted access' );
}

/**
 * Class to get plugin settings values
 */
if ( ! class_exists( 'SOCHM_UTIL' ) )
{
	class SOCHM_UTIL
	{
	    /**
		 * Get the value of a settings field
		 *
		 * @param string  $option  settings field name
		 * @param string  $section the section name this field belongs to
		 * @param string  $default default text if it's not found
		 * @return string
		 */
		public static function get_option( $option, $section, $default = '' )
		{
		    $options = get_option( $section );

		    if ( isset( $options[$option] ) )
		    {
		        return $options[$option];
		    }

		    return $default;
		}

		public static function isStoreClosed()
		{
			$storeIsClosed = false;
			
			// check if plugin is active
			$enabled  = self::get_option( 'enable_manager', 'sochm_basic_settings' );
			
			$timezone = self::get_option( 'timezone', 'sochm_basic_settings' );
			
			$close_store = self::get_option( 'close_store', 'sochm_basic_settings' );

			if ( $close_store == 'on' ) return true;
			
			$hours_table = get_option( 'sochm_table_data', [] );

			if ( ! is_array( $hours_table ) OR empty( $hours_table ) )
			{
				return $storeIsClosed;
			}

			if ( $enabled !== 'on' OR empty( $timezone ) )
			{
				return $storeIsClosed;
			}

			$dt = new DateTime( 'now', new DateTimezone( $timezone ) );
			
			$today = $dt->format( 'l' );
			
			$currentTimestamp = (int)strtotime( $dt->format( 'Y-m-d H:i:s' ) );

			$todayOpeningClosingHours = array_filter( $hours_table, function( $arr ) use ( $today )
			{
				return isset( $arr['name'] ) && strtolower( $arr['name'] ) == strtolower( $today );
			} );

			if ( $todayOpeningClosingHours )
			{
				foreach ( $todayOpeningClosingHours as $value )
				{
					if ( $value['opening_time_hr'] == '00' && $value['opening_time_min'] == '00' && $value['closing_time_hr'] == '00' && $value['closing_time_min'] == '00'
					)
					{
						continue;
					}
					
					$openingTimestamp = strtotime( $dt->format( 'Y-m-d' ) . ' ' . $value['opening_time_hr'] . ':' . $value['opening_time_min'] . ':00' );
						
					$closingTimestamp = strtotime( $dt->format( 'Y-m-d' ) . ' ' . $value['closing_time_hr'] . ':' . $value['closing_time_min'] . ':00' );
					
					if ( $value['status'] == 'open' )
					{
						// Store is now closed
						if ( ( $currentTimestamp < $openingTimestamp ) OR ( $currentTimestamp > $closingTimestamp ) )
						{
							$storeIsClosed = true;
						}
						else
						{
							$storeIsClosed = false;
						}
					}
					elseif ( $value['status'] == 'closed' )
					{
						// Store is now closed
						if ( ( $currentTimestamp > $openingTimestamp ) AND ( $currentTimestamp < $closingTimestamp ) )
						{
							$storeIsClosed = true;
						}
						else
						{
							$storeIsClosed = false;
						}
					}
				}
			}
			
			return $storeIsClosed;
		}

		public static function isStoreGoingToCloseSoon()
		{
			$storeIsClosingSoon = false;
			
			// check if plugin is active
			$enabled  = self::get_option( 'enable_manager', 'sochm_basic_settings' );
			
			$timezone = self::get_option( 'timezone', 'sochm_basic_settings' );
			
			$hours_table = get_option( 'sochm_table_data', [] );

			if ( ! is_array( $hours_table ) OR empty( $hours_table ) )
			{
				return $storeIsClosingSoon;
			}

			$enable_store_going_to_close_soon_notice_enabled  = self::get_option( 'enable_store_going_to_close_soon_notice', 'sochm_basic_settings' );

			if ( $enabled !== 'on' OR empty( $timezone ) OR ! $hours_table OR $enable_store_going_to_close_soon_notice_enabled !== 'on' )
			{
				return $storeIsClosingSoon;
			}

			$minutes_before_store_going_to_close_soon_notice  = self::get_option( 'minutes_before_store_going_to_close_soon_notice', 'sochm_basic_settings', 30 );

			$dt = new DateTime( 'now', new DateTimezone( $timezone ) );

			$dt->add( new DateInterval( 'PT' . ( $minutes_before_store_going_to_close_soon_notice * 60 ) . 'S' ) );
			
			$today = $dt->format( 'l' );
			
			$currentTimestamp = (int)strtotime( $dt->format( 'Y-m-d H:i:s' ) );

			$todayOpeningClosingHours = array_filter( $hours_table, function( $arr ) use ( $today )
			{
				return isset( $arr['name'] ) && strtolower( $arr['name'] ) == strtolower( $today );
			} );

			if ( $todayOpeningClosingHours )
			{
				foreach ( $todayOpeningClosingHours as $value )
				{
					if ( $value['opening_time_hr'] == '00' && $value['opening_time_min'] == '00' && $value['closing_time_hr'] == '00' && $value['closing_time_min'] == '00'
					)
					{
						continue;
					}
					
					$openingTimestamp = strtotime( $dt->format( 'Y-m-d' ) . ' ' . $value['opening_time_hr'] . ':' . $value['opening_time_min'] . ':00' );
						
					$closingTimestamp = strtotime( $dt->format( 'Y-m-d' ) . ' ' . $value['closing_time_hr'] . ':' . $value['closing_time_min'] . ':00' );
					
					if ( $value['status'] == 'open' )
					{
						// Store is now closed
						if ( ( $currentTimestamp < $openingTimestamp ) OR ( $currentTimestamp > $closingTimestamp ) )
						{
							$storeIsClosingSoon = true;
						}
						else
						{
							$storeIsClosingSoon = false;
						}
					}
					elseif ( $value['status'] == 'closed' )
					{
						// Store is now closed
						if ( ( $currentTimestamp > $openingTimestamp ) AND ( $currentTimestamp < $closingTimestamp ) )
						{
							$storeIsClosingSoon = true;
						}
						else
						{
							$storeIsClosingSoon = false;
						}
					}
				}
			}
			
			return $storeIsClosingSoon;
		}

		public static function storeGoingToCloseSoonRemainingSeconds()
		{
			$remainingTime = 0;

			// check if plugin is active
			$enabled  = self::get_option( 'enable_manager', 'sochm_basic_settings' );
			
			$timezone = self::get_option( 'timezone', 'sochm_basic_settings' );
			
			$hours_table = get_option( 'sochm_table_data', [] );

			$enable_store_going_to_close_soon_notice_enabled  = self::get_option( 'enable_store_going_to_close_soon_notice', 'sochm_basic_settings' );

			if ( $enabled !== 'on' OR empty( $timezone ) OR ! $hours_table OR $enable_store_going_to_close_soon_notice_enabled !== 'on' )
			{
				return $remainingTime;
			}

			$minutes_before_store_going_to_close_soon_notice  = self::get_option( 'minutes_before_store_going_to_close_soon_notice', 'sochm_basic_settings', 30 );

			$dt_cloned = new DateTime( 'now', new DateTimezone( $timezone ) );

			$dt = new DateTime( 'now', new DateTimezone( $timezone ) );

			$dt->add( new DateInterval( 'PT' . ( $minutes_before_store_going_to_close_soon_notice * 60 ) . 'S' ) );
			
			$today = $dt->format( 'l' );
			
			$currentTimestamp = (int)strtotime( $dt->format( 'Y-m-d H:i:s' ) );

			$todayOpeningClosingHours = array_filter( $hours_table, function( $arr ) use ( $today )
			{
				return isset( $arr['name'] ) && strtolower( $arr['name'] ) == strtolower( $today );
			} );

			if ( $todayOpeningClosingHours )
			{
				foreach ( $todayOpeningClosingHours as $value )
				{
					if ( $value['opening_time_hr'] == '00' && $value['opening_time_min'] == '00' && $value['closing_time_hr'] == '00' && $value['closing_time_min'] == '00'
					)
					{
						continue;
					}
					
					$openingTimestamp = strtotime( $dt->format( 'Y-m-d' ) . ' ' . $value['opening_time_hr'] . ':' . $value['opening_time_min'] . ':00' );

					$closingTimestamp = strtotime( $dt->format( 'Y-m-d' ) . ' ' . $value['closing_time_hr'] . ':' . $value['closing_time_min'] . ':00' );
					
					if ( $value['status'] == 'open' )
					{
						// Store is now closed
						if ( ( $currentTimestamp < $openingTimestamp ) OR ( $currentTimestamp > $closingTimestamp ) )
						{
							$remainingTime = $closingTimestamp - (int)strtotime( $dt_cloned->format( 'Y-m-d H:i:s' ) );
						}
					}
					elseif ( $value['status'] == 'closed' )
					{
						// Store is now closed
						if ( ( $currentTimestamp > $openingTimestamp ) AND ( $currentTimestamp < $closingTimestamp ) )
						{
							$remainingTime = $openingTimestamp - (int)strtotime( $dt_cloned->format( 'Y-m-d H:i:s' ) );
						}
					}
				}
			}
			
			return round( $remainingTime );
		}

		public static function storeOpeningRemainingSeconds()
		{			
			$remainingTime = 0;

			// check if plugin is active
			$enabled  = self::get_option( 'enable_manager', 'sochm_basic_settings' );
			
			$timezone = self::get_option( 'timezone', 'sochm_basic_settings' );
			
			$hours_table = get_option( 'sochm_table_data', [] );

			if ( ! is_array( $hours_table ) OR empty( $hours_table ) )
			{
				return $remainingTime;
			}

			if ( $enabled !== 'on' OR empty( $timezone ) OR ! $hours_table )
			{
				return $remainingTime;
			}

			$todayOpeningClosingHours = [];

			$i = 0;

			$storeIsOpen = false;

			while( ! $storeIsOpen )
			{
				$dt = ( new DateTime( 'now', new DateTimezone( $timezone ) ) )->add( new DateInterval( "P{$i}D" ) );

				$today = $dt->format( 'l' );

				$dt_cloned = new DateTime( 'now', new DateTimezone( $timezone ) );

				$currentTimestamp = (int)strtotime( $dt_cloned->format( 'Y-m-d H:i:s' ) );

				$todayOpeningClosingHours = array_filter( $hours_table, function( $arr ) use ( $today )
				{
					return isset( $arr['name'] ) && strtolower( $arr['name'] ) == strtolower( $today );
				} );

				$storeIsOpen = self::isStoreOpen( $todayOpeningClosingHours, $currentTimestamp, $dt->format( 'Y-m-d' ) );

				if ( ! $storeIsOpen )
				{
					$i++;
				}

				if ( $i > 60 )
				{
					break;	
				}
			}

			return intval( $storeIsOpen ) ? round( intval( $storeIsOpen ) - $currentTimestamp ) : 0;
		}

		public static function isStoreOpen( $todayOpeningClosingHours = [], $currentTimestamp, $currentDate )
		{
			$storeIsOpen = 0;

			if ( $todayOpeningClosingHours )
			{
				foreach ( $todayOpeningClosingHours as $value )
				{
					if ( $value['opening_time_hr'] == '00' && $value['opening_time_min'] == '00' && $value['closing_time_hr'] == '00' && $value['closing_time_min'] == '00'
					)
					{
						continue;
					}
					
					$openingTimestamp = strtotime( $currentDate . ' ' . $value['opening_time_hr'] . ':' . $value['opening_time_min'] . ':00' );
						
					$closingTimestamp = strtotime( $currentDate . ' ' . $value['closing_time_hr'] . ':' . $value['closing_time_min'] . ':00' );

					if ( $value['status'] == 'open' && $openingTimestamp > $currentTimestamp )
					{
						return $openingTimestamp;
					}
					elseif ( $value['status'] == 'closed' )
					{
						// same day
						if ( date( 'l', $currentTimestamp ) == date( 'l', $openingTimestamp ) )
						{
							return $closingTimestamp;
						}
						
						if ( $openingTimestamp > $currentTimestamp )
						{
							return strtotime( $currentDate . ' 00:00:00' );
						}
					}
				}
			}
			
			return $storeIsOpen;
		}

		public static function get_table_settings()
		{
			$weekDaysTable = [];

			$week_days = get_option( 'sochm_table_data', array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' ) );

			if ( ! is_array( $week_days ) OR empty( $week_days ) )
			{
				return $weekDaysTable;
			}

			foreach ( $week_days as $week_day )
			{
				$tmp = [];
				
				$tmp['weekName'] = isset( $week_day['name'] ) ? $week_day['name'] : $week_day;
				
				$tmp['weekFullName'] = ucfirst( $tmp['weekName'] );

				$tmp['status'] = isset( $week_day['status'] ) ? $week_day['status'] : 'open';
		    	
		    	foreach ( range( 0, 23 ) as $num )
		    	{	
		    		$tmp['opening_time_hr'][$num] = sprintf( "%02d", $num );

		    		$tmp['closing_time_hr'][$num] = sprintf( "%02d", $num );

		    		if ( isset( $week_day['opening_time_hr'] ) && $week_day['opening_time_hr'] == sprintf( "%02d", $num ) )
		    		{
		    			$tmp['selected_opening_time_hr'] = sprintf( "%02d", $num );
		    		}

		    		if ( isset( $week_day['closing_time_hr'] ) && $week_day['closing_time_hr'] == sprintf( "%02d", $num ) )
		    		{
		    			$tmp['selected_closing_time_hr'] = sprintf( "%02d", $num );
		    		}
		    	}
				
				foreach ( range( 0, 59 ) as $num )
		    	{
		    		$tmp['opening_time_min'][$num] = sprintf( "%02d", $num );

		    		$tmp['closing_time_min'][$num] = sprintf( "%02d", $num );

		    		if ( isset( $week_day['opening_time_min'] ) && $week_day['opening_time_min'] == sprintf( "%02d", $num ) )
		    		{
		    			$tmp['selected_opening_time_min'] = sprintf( "%02d", $num );
		    		}

		    		if ( isset( $week_day['closing_time_min'] ) && $week_day['closing_time_min'] == sprintf( "%02d", $num ) )
		    		{
		    			$tmp['selected_closing_time_min'] = sprintf( "%02d", $num );
		    		}
		    	}

		    	$weekDaysTable[] = $tmp;
			}

			return $weekDaysTable;
		}
	}
}
