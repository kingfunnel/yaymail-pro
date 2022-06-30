<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $wpdb;
$table_name = $wpdb->prefix . 'dpd_orders_switzerland';
$order_id   = $order->get_id();
$query      = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) );
if ( $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table_name ) ) ) == $table_name ) {
	$parcels = $wpdb->get_results( $wpdb->prepare( 'Select parcel_number FROM %s WHERE order_id = %s AND status_label = 1', $table_name, $order_id ) );
	if ( count( $parcels ) ) {
			$parc_num                = $parcels[0]->parcel_number;
			$shipping_date           = $wpdb->get_row( $wpdb->prepare( 'Select shipping_date FROM %s WHERE parcel_number = %s ORDER BY id DESC', $table_name, $parc_num ) );
			$formatted_shiiping_date = gmdate( 'd.m.Y', absint( $shipping_date->shipping_date ) );
			printf(
					/* translators: %s: Formatted shipping date */
				esc_html__( 'Die Bestellung wurde oder wird demn&auml;chst mit DPD verschickt. Hier der Link um die Sendung zu verfolgen: %s', 'dpd-shipping-label-switzerland' ),
				esc_html( $formatted_shiiping_date )
			);
		foreach ( $parcels as $parcel ) {
				echo wp_kses_post( '<a href="https://www.dpdgroup.com/ch/mydpd/tmp/basicsearch?parcel_id=' . $parcel->parcel_number . '" target="_blank">' . $parcel->parcel_number . '</a>' );
				echo wp_kses_post( '<br /><br />' );
		}
	}
}

