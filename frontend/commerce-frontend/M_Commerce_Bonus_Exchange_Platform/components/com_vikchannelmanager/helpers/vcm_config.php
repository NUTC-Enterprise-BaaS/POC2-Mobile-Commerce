<?php
/**------------------------------------------------------------------------
 * com_vikchannelmanager - VikChannelManager
 * ------------------------------------------------------------------------
 * author    e4j - Extensionsforjoomla.com
 * copyright Copyright (C) 2016 e4j - Extensionsforjoomla.com. All Rights Reserved.
 * @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 * Websites: http://www.extensionsforjoomla.com
 * Technical Support:  tech@extensionsforjoomla.com
 * ------------------------------------------------------------------------
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

class VikChannelManagerConfig {
	
	const EXPEDIA = '1';
	const TRIP_CONNECT = '2';
	const TRIVAGO = '3';
    const BOOKING = '4';
    const AIRBNB = '5';
    const FLIPKEY = '6';
    const HOLIDAYLETTINGS = '7';
    const AGODA = '8';
    const WIMDU = '9';
    const HOMEAWAY = '10';
    const VRBO = '11';
    const YCS50 = '12';
	const VCM_CONNECTION_SERIAL = 52089858; 
	
	public static $TA_HOTEL_AMENITIES = array(
			'ACTIVITIES_OLDER_CHILDREN',
			'ACTIVITIES_YOUNG_CHILDREN',
			'ADJOINING_ROOMS',
			'ALL_INCLUSIVE',
			'ALL_SUITES',
			'APARTMENTS',
			'BAR_LOUNGE',
			'BEACH',
			'BED_AND_BREAKFAST',
			'BUSINESS_SERVICES',
			'CAR_RENTAL_DESK',
			'CASTLE',
			'CONVENTIONS',
			'CREDIT_CARDS_ACCEPTED',
			'DATA_PORT',
			'DINING',
			'DRY_CLEANING',
			'EARLY_ARRIVAL',
			'ECONOMY',
			'ELDER_ACCESS',
			'EXTENDED_STAY',
			'FAMILY_ROOMS',
			'FARM_RANCH',
			'FIRST_CLASS',
			'FITNESS_CENTER',
			'FOOD_AVAILABLE',
			'FREE_BREAKFAST',
			'FREE_CANCELATION',
			'FREE_INTERNET',
			'FREE_LOCAL_CALLS',
			'FREE_PARKING',
			'FREE_WIFI',
			'GAME_ROOM',
			'GOLF',
			'HOT_TUB',
			'KIDS_ACTIVITIES',
			'LATE_ARRIVAL',
			'LATE_CHECK_OUT',
			'LOCKERS_STORAGE',
			'LOYALTY_REWARDS_AVAILABLE',
			'LUXURY',
			'MEALS_INCLUDED',
			'MEETING_ROOM',
			'MOTEL',
			'NON_SMOKING',
			'PARKING_AVAILABLE',
			'PAID_PARKING',
			'PETS_ALLOWED',
			'RESORT',
			'RESTAURANT',
			'ROOM_SERVICE',
			'SHUTTLE',
			'STAIRS_ELEVATOR',
			'STROLLER_PARKING',
			'SUITES',
			'SWIMMING_POOL',
			'TENNIS_COURT',
			'VALET_PARKING',
			'WHEELCHAIR_ACCESS'
	);
	
	public static $TA_ROOM_AMENITIES = array(
			'ALL_INCLUSIVE',
			'BATHROOMS',
			'DATA_PORT',
			'DINING',
			'ECONOMY',
			'ELDER_ACCESS',
			'EXTENDED_STAY',
			'FIRST_CLASS',
			'FREE_BREAKFAST',
			'FREE_CANCELATION',
			'FREE_INTERNET',
			'FREE_LOCAL_CALLS',
			'FREE_WIFI',
			'HOT_TUB',
			'KITCHEN_KITCHENETTE',
			'KITCHENETTE',
			'LUXURY',
			'MEALS_INCLUDED',
			'NON_SMOKING',
			'PAID_PARKING',
			'PETS_ALLOWED',
			'PRIVATE_BATH',
			'ROOM_SERVICE',
			'ROOM_WITH_A_VIEW',
			'SHARED_BATH',
			'WHEELCHAIR_ACCESS'
	);
	
	public static $TA_ROOM_CODES = array(
			'SINGLE',
			'QUEEN',
			'2_QUEEN',
			'KING',
			'SUITE',
			'SHARED',
			'OTHER'
	);
	
	public static $TRI_ROOM_CODES = array(
			'SINGLE',
			'DOUBLE',
			'OTHER'
	);
	
	public static $AVAILABLE_CHANNELS = array(
		VikChannelManagerConfig::BOOKING => 'booking',
		VikChannelManagerConfig::AGODA => 'agoda',
		VikChannelManagerConfig::EXPEDIA => 'expedia',
		VikChannelManagerConfig::TRIP_CONNECT => 'tripconnect',
		VikChannelManagerConfig::TRIVAGO => 'trivago',
		VikChannelManagerConfig::AIRBNB => 'airbnb',
		VikChannelManagerConfig::WIMDU => 'wimdu',
		VikChannelManagerConfig::FLIPKEY => 'flipkey',
		VikChannelManagerConfig::HOLIDAYLETTINGS => 'holidaylettings',
		VikChannelManagerConfig::HOMEAWAY => 'homeaway',
		VikChannelManagerConfig::VRBO => 'vrbo',
		VikChannelManagerConfig::YCS50 => 'ycs50',
	);
	
	public static $ERRORS_MAP = array(
		"e4j" => array(
			"_default" => "MSG_BASE",
			"error" => array(
				"_default" => "MSG_BASE_ERROR",
				"Authentication" => array(
					"_default" => "MSG_BASE_ERROR_AUTH",
					"TripConnect" => array(
					   "_default" => "MSG_BASE_ERROR_AUTH_TRIPCONNECT",
                    ),
				),
				"NoChannels" => array(
				    "_default" => "MSG_BASE_ERROR_NOCHANNELS",
                ),
				"Curl" => array(
				    "_default" => "MSG_BASE_ERROR_CURL",
					"Request" => array(
						"_default" => "MSG_BASE_ERROR_CURL_REQUEST",
					),
					"Connection" => array(
						"_default" => "MSG_BASE_ERROR_CURL_CONNECTION"
					),
					"Broken" => array(
						"_default" => "MSG_BASE_ERROR_CURL_BROKEN"
					),
                ),
				"Expedia" => array(
					"_default" => "MSG_BASE_ERROR_EXPEDIA",
					"RAR" => array(
						"_default" => "MSG_BASE_ERROR_EXPEDIA_RAR",
					),
					"BC_RS" => array(
						"_default" => "MSG_BASE_ERROR_EXPEDIA_BC_RS",
					),
					"CUSTAR_RS" => array(
						"_default" => "MSG_BASE_ERROR_EXPEDIA_CUSTAR_RS",
					),
					"AR_RS" => array(
						"_default" => "MSG_BASE_ERROR_EXPEDIA_AR_RS",
					),
				),
				"Agoda" => array(
					"_default" => "MSG_BASE_ERROR_AGODA",
					"RAR" => array(
						"_default" => "MSG_BASE_ERROR_AGODA_RAR",
					),
					"BC_RS" => array(
						"_default" => "MSG_BASE_ERROR_AGODA_BC_RS",
					),
					"CUSTAR_RS" => array(
						"_default" => "MSG_BASE_ERROR_AGODA_CUSTAR_RS",
					),
					"AR_RS" => array(
						"_default" => "MSG_BASE_ERROR_AGODA_AR_RS",
					),
				),
				"Booking" => array(
					"_default" => "MSG_BASE_ERROR_BOOKING",
					"RAR" => array(
						"_default" => "MSG_BASE_ERROR_BOOKING_RAR",
					),
					"BC_RS" => array(
						"_default" => "MSG_BASE_ERROR_BOOKING_BC_RS",
					),
					"CUSTAR_RS" => array(
						"_default" => "MSG_BASE_ERROR_BOOKING_CUSTAR_RS",
					),
					"AR_RS" => array(
						"_default" => "MSG_BASE_ERROR_BOOKING_AR_RS",
					),
				),
				"Channels" => array(
					"_default" => "MSG_BASE_ERROR_CHANNELS",
					"NoSynchRooms" => array(
						"_default" => "MSG_BASE_ERROR_CHANNELS_NOSYNCHROOMS",
					),
					"BookingDownload" => array(
						"_default" => "MSG_BASE_ERROR_CHANNELS_BOOKINGDOWNLOAD",
					),
					"InvalidBooking" => array(
						"_default" => "MSG_BASE_ERROR_CHANNELS_INVALIDBOOKING",
					),
					"BookingModification" => array(
						"_default" => "MSG_BASE_ERROR_CHANNELS_BOOKINGMODIFICATION",
					),
					"ACMP_Busy" => array(
						"_default" => "MSG_BASE_ERROR_CHANNELS_ACMPBUSY",
					),
				),
				"File" => array(
					"_default" => "MSG_BASE_ERROR_FILE",
					"Permissions" => array(
						"_default" => "MSG_BASE_ERROR_FILE_PERMISSIONS",
						"Write" => array(
							"_default" => "MSG_BASE_ERROR_FILE_PERMISSIONS_WRITE",
						),
					),
					"NotFound" => array(
						"_default" => "MSG_BASE_ERROR_FILE_NOTFOUND",
					),
				),
				"Max31days" => array(
					"_default" => "MSG_BASE_ERROR_MAX31DAYSREQ",
				),
                "ParRequestResponseIntegrity" => array(
                    "_default" => "MSG_BASE_ERROR_PAR_RR"
                ),
				"Query" => array(
					"_default" => "MSG_BASE_ERROR_QUERY",
				),
				"RequestIntegrity" => array(
					"_default" => "MSG_BASE_ERROR_REQUEST",
				),
				"Schema" => array(
					"_default" => "MSG_BASE_ERROR_SCHEMA",
				),
				"Settings" => array(
                    "_default" => "MSG_BASE_ERROR_SETTINGS",
                ),
                "Pcidata" => array(
                    "_default" => "MSG_BASE_ERROR_PCIDATA",
                ),
			),
			"warning" => array(
				"_default" => "MSG_BASE_WARNING",
				"Expedia" => array(
					"_default" => "MSG_BASE_WARNING_EXPEDIA",
					"RAR" => array(
						"_default" => "MSG_BASE_WARNING_EXPEDIA_RAR",
					),
					"CUSTAR_RS" => array(
						"_default" => "MSG_BASE_WARNING_EXPEDIA_CUSTAR_RS",
					),
					"AR_RS" => array(
						"_default" => "MSG_BASE_WARNING_EXPEDIA_AR_RS",
					),
					"BC_RS" => array(
						"_default" => "MSG_BASE_WARNING_EXPEDIA_BC_RS",
					),
				),
				"Agoda" => array(
					"_default" => "MSG_BASE_WARNING_AGODA",
					"RAR" => array(
						"_default" => "MSG_BASE_WARNING_AGODA_RAR",
					),
					"CUSTAR_RS" => array(
						"_default" => "MSG_BASE_WARNING_AGODA_CUSTAR_RS",
					),
					"AR_RS" => array(
						"_default" => "MSG_BASE_WARNING_AGODA_AR_RS",
					),
					"BC_RS" => array(
						"_default" => "MSG_BASE_WARNING_AGODA_BC_RS",
					),
				),
				"Booking" => array(
					"_default" => "MSG_BASE_WARNING_BOOKING",
					"RAR" => array(
						"_default" => "MSG_BASE_WARNING_BOOKING_RAR",
					),
					"CUSTAR_RS" => array(
						"_default" => "MSG_BASE_WARNING_BOOKING_CUSTAR_RS",
					),
					"AR_RS" => array(
						"_default" => "MSG_BASE_WARNING_BOOKING_AR_RS",
					),
					"BC_RS" => array(
						"_default" => "MSG_BASE_WARNING_BOOKING_BC_RS",
					),
				),
				"NoUpdates" => array(
					"_default" => "MSG_BASE_WARNING_NOUPD",
				),
			),
			"OK" => array(
				"_default" => "MSG_BASE_SUCCESS",
				"Channels" => array(
					"_default" => "MSG_BASE_SUCCESS",
					"CUSTAR_RQ" => array(
						"_default" => "MSG_BASE_SUCCESS_CHANNELS_CUSTAR_RQ",
					),
					"NewBookingDownloaded" => array(
						"_default" => "MSG_BASE_SUCCESS_CHANNELS_NEWBOOKINGDOWNLOADED",
					),
					"BookingModified" => array(
						"_default" => "MSG_BASE_SUCCESS_CHANNELS_BOOKINGMODIFIED",
					),
					"BookingCancelled" => array(
						"_default" => "MSG_BASE_SUCCESS_CHANNELS_BOOKINGCANCELLED",
					),
				),
				"Expedia" => array(
					"_default" => "MSG_BASE_SUCCESS",
					"CUSTAR_RS" => array(
						"_default" => "MSG_BASE_SUCCESS_EXPEDIA_CUSTAR_RS",
					),
					"AR_RS" => array(
						"_default" => "MSG_BASE_SUCCESS_EXPEDIA_AR_RS",
					),
					"BC_RS" => array(
						"_default" => "MSG_BASE_SUCCESS_EXPEDIA_BC_RS",
					),
				),
				"Agoda" => array(
					"_default" => "MSG_BASE_SUCCESS",
					"CUSTAR_RS" => array(
						"_default" => "MSG_BASE_SUCCESS_AGODA_CUSTAR_RS",
					),
					"AR_RS" => array(
						"_default" => "MSG_BASE_SUCCESS_AGODA_AR_RS",
					),
					"BC_RS" => array(
						"_default" => "MSG_BASE_SUCCESS_AGODA_BC_RS",
					),
				),
				"Booking" => array(
					"_default" => "MSG_BASE_SUCCESS",
					"CUSTAR_RS" => array(
						"_default" => "MSG_BASE_SUCCESS_BOOKING_CUSTAR_RS",
					),
					"AR_RS" => array(
						"_default" => "MSG_BASE_SUCCESS_BOOKING_AR_RS",
					),
					"BC_RS" => array(
						"_default" => "MSG_BASE_SUCCESS_BOOKING_BC_RS",
					),
				),
			),
		),
	);
	
	
}

?>