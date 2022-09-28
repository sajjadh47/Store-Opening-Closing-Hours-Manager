<?php

if ( ! defined( 'ABSPATH' ) )
{
	exit( 'restricted access' );
}

if ( ! class_exists( 'SOCHM_WIDGET' ) )
{
	class SOCHM_WIDGET extends WP_Widget
	{
		function __construct()
		{
			parent::__construct(
			
			'sochm_widget', __( 'Store Opening & Closing Times Table', 'store-opening-closing-hours-manager' ), array( 'description' => __( 'Add A Table Of Your Store Opening & Closing Times.', 'store-opening-closing-hours-manager' ), )
			);
		}
		
		public function widget( $args, $instance )
		{
			$title = apply_filters( 'widget_title', $instance['title'] );
			
			echo $args['before_widget'];
			
			//if title is present
			if ( ! empty( $title ) ) echo $args['before_title'] . $title . $args['after_title'];

			if ( apply_filters( 'sochm_show_timezone_in_widget', true ) )
			{
				echo '<p class=" ' . apply_filters( 'sochm_timezone_widget_classes', 'sochm_timezone' ) . ' ">' . apply_filters( 'sochm_timezone_title_in_widget', 'Timezone : ' ), SOCHM_UTIL::get_option( 'timezone', 'sochm_basic_settings' ) . '</p>';
			}

			$weekDaysTable = SOCHM_UTIL::get_table_settings();

			$daysCount = array( 'monday' => 0, 'tuesday' => 0, 'wednesday' => 0, 'thursday' => 0, 'friday' => 0, 'saturday' => 0, 'sunday' => 0 );

			//output
			?>
				<table class='week_days_table'>
		    		<thead>
		    			<tr>
		    				<th><?php echo __( 'Day', 'store-opening-closing-hours-manager' );  ?></th>
		    				<th><?php echo __( 'Status', 'store-opening-closing-hours-manager' ); ?></th>
		    				<th><?php echo __( 'From', 'store-opening-closing-hours-manager' ); ?></th>
		    				<th><?php echo __( 'To', 'store-opening-closing-hours-manager' ); ?></th>
		    			</tr>
		    		</thead>
		    		<tbody>
		    		<?php foreach( $weekDaysTable as $key => $week ) : $daysCount[$week["weekName"]]++; ?>
			    		<tr class='<?php echo esc_html( $week["weekName"] ); ?>'>
							<td <?php echo esc_html( $daysCount[$week["weekName"]] ) > 1 ? 'style="color:transparent;"' : ''; ?>><?php echo esc_html( $week["weekFullName"] ); ?></td>
							<td><?php echo ucfirst( esc_html( $week["status"] ) ); ?></td>
							<td><?php echo esc_html( $week["selected_opening_time_hr"] ) . ':' . esc_html( $week["selected_opening_time_min"] ); ?></td>
							<td><?php echo esc_html( $week["selected_closing_time_hr"] ) . ':' . esc_html( $week["selected_closing_time_min"] ); ?></td>
						</tr>
					<?php endforeach; ?>
					</tbody>
		    	</table>
			<?php
			
			echo $args['after_widget'];
		}
		
		public function form( $instance )
		{
			if ( isset( $instance[ 'title' ] ) )
				$title = $instance[ 'title' ];
			else
				$title = __( 'Store Opening & Closing Times', 'store-opening-closing-hours-manager' );
			?>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<?php
		}

		public function update( $new_instance, $old_instance )
		{
			$instance = array();
			
			$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
			
			return $instance;
		}
	}
}
