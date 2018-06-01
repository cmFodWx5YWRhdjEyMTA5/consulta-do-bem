<?php
/**
 * @Booking Dummy data
 * All the functions will be in this file
 */



/**
 * @Data Importer
 * @return 
 */
if (!function_exists('docdirect_update_users')) {

    function docdirect_update_users() {
        $query_args = array(
            'role' => 'professional',
        );
        
		$user_query = new WP_User_Query($query_args);
        $booking_confirmed_default = 'Hey %customer_name%!<br/>

						This is confirmation that you have booked "%service%"<br/> with %provider%.<br/>
						We will let your know regarding your booking soon.<br/><br/>
						
						Thank you for choosing our company.<br/><br/>
						
						Sincerely,<br/>
						%logo%';

        //Default template for booking cancellation
        $booking_cancelled_default = 'Hi %customer_name%!<br/>

							 This is confirmation that your booking regarding "%service%" with %provider% has cancelled.<br/>

							 We are very sorry to process your booking right now.<br/><br/>

							 Sincerely,<br/>
							 %logo%';

        //Default template for booking Approved
        $booking_approved_default = 'Hey %customer_name%!<br/>

						This is confirmation that your booking regarding "%service%" with %provider% has approved.<br/>
						
						We are waiting you at "%address%" on %appointment_date% at %appointment_time%.<br/><br/><br/>
						
						Sincerely,<br/>
						%logo%';

        $categories['generals'] = array(
            'privacy' => array(
                'appointments' => 'on',
                'phone' => 'on',
                'email' => 'on',
                'contact_form' => 'on',
            ),
            'custom_slots' => '{"custom_time_slots":"{\"1800-1830\":0,\"1835-1905\":0,\"1910-1940\":0,\"1945-2015\":0,\"2020-2050\":0,\"2055-2125\":0,\"2130-2200\":0,\"2205-2235\":0,\"2240-2310\":0}","custom_time_slot_details":"{\"1800-1830\":{\"slot_title\":\"Booking\"},\"1835-1905\":{\"slot_title\":\"Booking\"},\"1910-1940\":{\"slot_title\":\"Booking\"},\"1945-2015\":{\"slot_title\":\"Booking\"},\"2020-2050\":{\"slot_title\":\"Booking\"},\"2055-2125\":{\"slot_title\":\"Booking\"},\"2130-2200\":{\"slot_title\":\"Booking\"},\"2205-2235\":{\"slot_title\":\"Booking\"},\"2240-2310\":{\"slot_title\":\"Booking\"}}","cus_start_date":"2017-09-01","cus_end_date":"2017-09-20","disable_appointment":"enable"}',
            'default_slots' => 'a:14:{s:3:"sun";a:16:{s:9:"0900-0930";i:0;s:9:"0935-1005";i:0;s:9:"1010-1040";i:0;s:9:"1045-1115";i:0;s:9:"1120-1150";i:0;s:9:"1155-1225";i:0;s:9:"1230-1300";i:0;s:9:"1305-1335";i:0;s:9:"1340-1410";i:0;s:9:"1415-1445";i:0;s:9:"1450-1520";i:0;s:9:"1525-1555";i:0;s:9:"1600-1630";i:0;s:9:"1635-1705";i:0;s:9:"1710-1740";i:0;s:9:"1745-1815";i:0;}s:11:"sun-details";a:16:{s:9:"0900-0930";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"0935-1005";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1010-1040";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1045-1115";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1120-1150";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1155-1225";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1230-1300";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1305-1335";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1340-1410";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1415-1445";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1450-1520";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1525-1555";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1600-1630";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1635-1705";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1710-1740";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1745-1815";a:1:{s:10:"slot_title";s:7:"Booking";}}s:3:"mon";a:16:{s:9:"0900-0930";i:0;s:9:"0935-1005";i:0;s:9:"1010-1040";i:0;s:9:"1045-1115";i:0;s:9:"1120-1150";i:0;s:9:"1155-1225";i:0;s:9:"1230-1300";i:0;s:9:"1305-1335";i:0;s:9:"1340-1410";i:0;s:9:"1415-1445";i:0;s:9:"1450-1520";i:0;s:9:"1525-1555";i:0;s:9:"1600-1630";i:0;s:9:"1635-1705";i:0;s:9:"1710-1740";i:0;s:9:"1745-1815";i:0;}s:11:"mon-details";a:16:{s:9:"0900-0930";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"0935-1005";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1010-1040";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1045-1115";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1120-1150";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1155-1225";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1230-1300";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1305-1335";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1340-1410";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1415-1445";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1450-1520";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1525-1555";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1600-1630";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1635-1705";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1710-1740";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1745-1815";a:1:{s:10:"slot_title";s:7:"Booking";}}s:3:"tue";a:16:{s:9:"0900-0930";i:0;s:9:"0935-1005";i:0;s:9:"1010-1040";i:0;s:9:"1045-1115";i:0;s:9:"1120-1150";i:0;s:9:"1155-1225";i:0;s:9:"1230-1300";i:0;s:9:"1305-1335";i:0;s:9:"1340-1410";i:0;s:9:"1415-1445";i:0;s:9:"1450-1520";i:0;s:9:"1525-1555";i:0;s:9:"1600-1630";i:0;s:9:"1635-1705";i:0;s:9:"1710-1740";i:0;s:9:"1745-1815";i:0;}s:11:"tue-details";a:16:{s:9:"0900-0930";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"0935-1005";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1010-1040";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1045-1115";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1120-1150";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1155-1225";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1230-1300";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1305-1335";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1340-1410";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1415-1445";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1450-1520";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1525-1555";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1600-1630";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1635-1705";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1710-1740";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1745-1815";a:1:{s:10:"slot_title";s:7:"Booking";}}s:3:"wed";a:16:{s:9:"0900-0930";i:0;s:9:"0935-1005";i:0;s:9:"1010-1040";i:0;s:9:"1045-1115";i:0;s:9:"1120-1150";i:0;s:9:"1155-1225";i:0;s:9:"1230-1300";i:0;s:9:"1305-1335";i:0;s:9:"1340-1410";i:0;s:9:"1415-1445";i:0;s:9:"1450-1520";i:0;s:9:"1525-1555";i:0;s:9:"1600-1630";i:0;s:9:"1635-1705";i:0;s:9:"1710-1740";i:0;s:9:"1745-1815";i:0;}s:11:"wed-details";a:16:{s:9:"0900-0930";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"0935-1005";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1010-1040";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1045-1115";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1120-1150";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1155-1225";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1230-1300";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1305-1335";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1340-1410";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1415-1445";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1450-1520";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1525-1555";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1600-1630";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1635-1705";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1710-1740";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1745-1815";a:1:{s:10:"slot_title";s:7:"Booking";}}s:3:"thu";a:16:{s:9:"0900-0930";i:0;s:9:"0935-1005";i:0;s:9:"1010-1040";i:0;s:9:"1045-1115";i:0;s:9:"1120-1150";i:0;s:9:"1155-1225";i:0;s:9:"1230-1300";i:0;s:9:"1305-1335";i:0;s:9:"1340-1410";i:0;s:9:"1415-1445";i:0;s:9:"1450-1520";i:0;s:9:"1525-1555";i:0;s:9:"1600-1630";i:0;s:9:"1635-1705";i:0;s:9:"1710-1740";i:0;s:9:"1745-1815";i:0;}s:11:"thu-details";a:16:{s:9:"0900-0930";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"0935-1005";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1010-1040";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1045-1115";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1120-1150";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1155-1225";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1230-1300";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1305-1335";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1340-1410";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1415-1445";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1450-1520";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1525-1555";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1600-1630";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1635-1705";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1710-1740";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1745-1815";a:1:{s:10:"slot_title";s:7:"Booking";}}s:3:"fri";a:16:{s:9:"0900-0930";i:0;s:9:"0935-1005";i:0;s:9:"1010-1040";i:0;s:9:"1045-1115";i:0;s:9:"1120-1150";i:0;s:9:"1155-1225";i:0;s:9:"1230-1300";i:0;s:9:"1305-1335";i:0;s:9:"1340-1410";i:0;s:9:"1415-1445";i:0;s:9:"1450-1520";i:0;s:9:"1525-1555";i:0;s:9:"1600-1630";i:0;s:9:"1635-1705";i:0;s:9:"1710-1740";i:0;s:9:"1745-1815";i:0;}s:11:"fri-details";a:16:{s:9:"0900-0930";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"0935-1005";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1010-1040";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1045-1115";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1120-1150";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1155-1225";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1230-1300";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1305-1335";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1340-1410";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1415-1445";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1450-1520";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1525-1555";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1600-1630";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1635-1705";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1710-1740";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1745-1815";a:1:{s:10:"slot_title";s:7:"Booking";}}s:3:"sat";a:16:{s:9:"0900-0930";i:0;s:9:"0935-1005";i:0;s:9:"1010-1040";i:0;s:9:"1045-1115";i:0;s:9:"1120-1150";i:0;s:9:"1155-1225";i:0;s:9:"1230-1300";i:0;s:9:"1305-1335";i:0;s:9:"1340-1410";i:0;s:9:"1415-1445";i:0;s:9:"1450-1520";i:0;s:9:"1525-1555";i:0;s:9:"1600-1630";i:0;s:9:"1635-1705";i:0;s:9:"1710-1740";i:0;s:9:"1745-1815";i:0;}s:11:"sat-details";a:16:{s:9:"0900-0930";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"0935-1005";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1010-1040";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1045-1115";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1120-1150";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1155-1225";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1230-1300";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1305-1335";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1340-1410";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1415-1445";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1450-1520";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1525-1555";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1600-1630";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1635-1705";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1710-1740";a:1:{s:10:"slot_title";s:7:"Booking";}s:9:"1745-1815";a:1:{s:10:"slot_title";s:7:"Booking";}}}',
            'approved_title' => 'Your Appointment Approved',
            'confirmation_title' => 'Your Appointment Confirmation',
            'cancelled_title' => 'Your Appointment Cancelled',
            'thank_you' => 'Thank you very much for your appointment. We have received your appointment and soon we will let you know regarding your appointment. You will receive a phone call or email regarding to your booking.',
            'schedule_message' => '<span style="color: #5d5955; font-family: \'Open Sans\', Arial, Helvetica, sans-serif;"><span style="font-size: 14px; line-height: 20px;">Consectetur adipisicing elit sed do eiusmod tempor incididunt ut labore et dolore magna aliqua enim ad minim veniam, <strong>Dr. Jhon Doe</strong> nostrud alitaia exercitation ullamco<strong> Invisalign (invisable braces)</strong> dolor in reprehenderit.</span></span>',
            'booking_approved' => $booking_approved_default,
            'booking_confirmed' => $booking_confirmed_default,
            'booking_cancelled' => $booking_cancelled_default,
            'currency_symbol' => '$',
            'currency' => 'USD',
        );
		
		//Doctors
        $categories['127'] = array(
            'services_cats' => array(
                'dentist' => 'Dentist',
                'cardiologist' => 'Cardiologist',
                'gynecologist' => 'Gynecologist',
            ),
            'booking_services' => array(
                'gnathology' => array(
                    'title' => 'Gnathology',
                    'price' => '205',
                    'category' => 'dentist',
                ),
                'orthodontics' => array(
                    'title' => 'Orthodontics',
                    'price' => '238',
                    'category' => 'dentist',
                ),
                'abiomed' => array(
                    'title' => 'Abiomed',
                    'price' => '150',
                    'category' => 'cardiologist',
                ),
                'bendopnea' => array(
                    'title' => 'Bendopnea',
                    'price' => '350',
                    'category' => 'cardiologist',
                ),
                'endre-czeizel' => array(
                    'title' => 'Endre Czeizel',
                    'price' => '130',
                    'category' => 'gynecologist',
                ),
                'torgrim-sornes' => array(
                    'title' => 'Torgrim Sornes',
                    'price' => '785',
                    'category' => 'gynecologist',
                )
            ),
        );

		//Blood Bank
        $categories['122'] = array(
            'services_cats' => array(
                'general-blood' => 'General Blood',
                'relicord' => 'Relicord',
                'blood-assurance' => 'Blood Assurance',
            ),
            'booking_services' => array(
                'hemorheology' => array(
                    'title' => 'Hemorheology',
                    'price' => '205',
                    'category' => 'general-blood',
                ),
                'fructosamine' => array(
                    'title' => 'Fructosamine',
                    'price' => '238',
                    'category' => 'general-blood',
                ),
                'hematocrit' => array(
                    'title' => 'Hematocrit',
                    'price' => '150',
                    'category' => 'relicord',
                ),
                'serology' => array(
                    'title' => 'Serology',
                    'price' => '350',
                    'category' => 'relicord',
                ),
                'blood-culture' => array(
                    'title' => 'Blood culture',
                    'price' => '785',
                    'category' => 'blood-assurance',
                ),
                'blood-film' => array(
                    'title' => 'Blood film',
                    'price' => '785',
                    'category' => 'blood-assurance',
                )
            ),
        ); 
		
		//Pharmacy
        $categories['123'] = array(
            'services_cats' => array(
                'albarello' => 'Albarello',
                'excipient' => 'Excipient',
                'telemedicine' => 'Telemedicine',
            ),
            'booking_services' => array(
                'reverse' => array(
                    'title' => 'Reverse',
                    'price' => '205',
                    'category' => 'albarello',
                ),
                'swatow-porcelain' => array(
                    'title' => 'Swatow Porcelain',
                    'price' => '238',
                    'category' => 'albarello',
                ),
                'acetone' => array(
                    'title' => 'Acetone',
                    'price' => '150',
                    'category' => 'excipient',
                ),
                'lactose' => array(
                    'title' => 'Lactose',
                    'price' => '350',
                    'category' => 'excipient',
                ),
                'teledentistry' => array(
                    'title' => 'Teledentistry',
                    'price' => '130',
                    'category' => 'telemedicine',
                ),
                'teleradiology' => array(
                    'title' => 'Teleradiology',
                    'price' => '785',
                    'category' => 'telemedicine',
                ),
            ),
        );
		
		//clinic
        $categories['125'] = array(
            'services_cats' => array(
                'abortion' => 'Abortion',
                'polyclinic' => 'Polyclinic',
                'walk-in-clinic' => 'Walk-in Clinic',
            ),
            'booking_services' => array(
                'forced-abortion' => array(
                    'title' => 'Forced abortion',
                    'price' => '205',
                    'category' => 'abortion',
                ),
                'abortion-law' => array(
                    'title' => 'Abortion law',
                    'price' => '238',
                    'category' => 'abortion',
                ),
                'polyclinic-hospital' => array(
                    'title' => 'Polyclinic Hospital',
                    'price' => '150',
                    'category' => 'polyclinic',
                ),
                'stuyvesant-polyclinic' => array(
                    'title' => 'Stuyvesant Polyclinic',
                    'price' => '350',
                    'category' => 'polyclinic',
                ),
                'target-clinic' => array(
                    'title' => 'Target Clinic',
                    'price' => '130',
                    'category' => 'walk-in-clinic',
                ),
                'careSpot' => array(
                    'title' => 'CareSpot',
                    'price' => '785',
                    'category' => 'walk-in-clinic',
                ),
            ),
        ); 
		
		//hospital
        $categories['126'] = array(
            'services_cats' => array(
                'children-hospital' => 'children hospital',
                'cardiology-hospital' => 'Cardiology Hospital',
                'gynecologist-hospital' => 'Gynecologist hospital',
            ),
            'booking_services' => array(
                'gynecologist-hospital ' => array(
                    'title' => 'Gynecologist hospital ',
                    'price' => '205',
                    'category' => 'children-hospital',
                ),
                'Orthodontics' => array(
                    'title' => 'Orthodontics',
                    'price' => '238',
                    'category' => 'children-hospital',
                ),
                'abiomed' => array(
                    'title' => 'Abiomed',
                    'price' => '150',
                    'category' => 'cardiology-hospital',
                ),
                'bendopnea' => array(
                    'title' => 'Bendopnea',
                    'price' => '350',
                    'category' => 'cardiology-hospital',
                ),
                'endre-czeizel' => array(
                    'title' => 'Endre Czeizel',
                    'price' => '130',
                    'category' => 'gynecologist-hospital',
                ),
                'torgrim-sornes' => array(
                    'title' => 'Torgrim Sornes',
                    'price' => '785',
                    'category' => 'gynecologist-hospital',
                ),
            ),
        ); 
		
		//Fitness Center	
        $categories['121'] = array(
            'services_cats' => array(
                'aerobic-centers' => 'Aerobic Centers',
                'yoga-centers' => 'Yoga Centers',
                'pilates-centers' => 'Pilates Centers',
            ),
            'booking_services' => array(
                'aerial-hoop' => array(
                    'title' => 'Aerial Hoop',
                    'price' => '205',
                    'category' => 'aerobic-centers',
                ),
                'aerial-silks' => array(
                    'title' => 'Aerial Silks',
                    'price' => '238',
                    'category' => 'aerobic-centers',
                ),
                'jivamukti-yoga' => array(
                    'title' => 'Jivamukti Yoga',
                    'price' => '150',
                    'category' => 'yoga-centers',
                ),
                'kripalvananda' => array(
                    'title' => 'Kripalvananda',
                    'price' => '350',
                    'category' => 'yoga-centers',
                ),
                'pontius-pilate' => array(
                    'title' => 'Pontius Pilate',
                    'price' => '130',
                    'category' => 'pilates-centers',
                ),
                'stott-pilates' => array(
                    'title' => 'Stott Pilates',
                    'price' => '785',
                    'category' => 'pilates-centers',
                ),
            ),
        ); 		
		
		$experience	= array(
			array(
				'title' => 'Lecturer, Department of gastroenterology',
				'company' => 'Co-ed/Women/Boys',
				'start_date' => '2010-05-07',
				'end_date' => '2012-07-17',
				'start_date_formated' => date('M,Y',strtotime('2010-05-07')),
				'end_date_formated'   => date('M,Y',strtotime('2012-07-17')),
				'description' => 'The Cardiovascular & Respiratory Systems category covers resources concerned with all aspects of cardiovascular and thoracic surgery and respiratory diseases. Topics include circulation, cardiovascular technology and measurement, cardiovascular pharmacology and therapy, hypertension, heart and lung transplantation, arteries, arteriosclerosis, thrombosis, angiology, perfusion, stroke, as well as all types of respiratory and lung diseases.',
			),
			array(
				'title' => 'Sr Consultant at Gastroentology Hospital',
				'company' => 'Adams State College',
				'start_date' => '2012-09-22',
				'end_date' => '2014-08-17',
				'start_date_formated' => date('M,Y',strtotime('2012-09-22')),
				'end_date_formated'   => date('M,Y',strtotime('2014-08-17')),
				'description' => 'The Clinical Immunology & Infectious Diseases category covers resources that focus on basic research in clinical and applied allergy, immunology, and infectious disease. Microbiology and virology resources are included in this category as are resources on HIV, AIDS, sexually transmitted diseases (STDs), and hospital infections.',
			),
			array(
				'title' => 'Present Consultant, Department of Gastroenterology at Apollo Hospital',
				'company' => 'Florida Hospital College of Health Sciences',
				'start_date' => '2014-03-27',
				'end_date' => '2016-08-03',
				'start_date_formated' => date('M,Y',strtotime('2014-03-27')),
				'end_date_formated'   => date('M,Y',strtotime('2016-08-03')),
				'description' => 'Oral Surgery & Medicine resources are concerned with basic, applied, and clinical aspects of oral infections and diseases, including their epidemiology, diagnosis, treatment, and rehabilitation. Specialties such as oral pathology/biology, oral epidemiology, oral rehabilitation, and oral implants are also included. Facial pain and craniomandibular resources are also covered in this category.',
			)
		);
		
		
		
		//Profile Banners
		$banners	= array(
						121 => '1206', //Fintness Center
						122 => '1203', //Blood Bank
						123 => '1207', //Pharmacy
						125 => '1204', //Clinics
						126 => '1205', //Hospital
						127 => '1205', //Doctors
					);
		
		$privacy	= array(
			'appointments'	=> 'on',
			'phone'	=> 'on',
			'email'	=> 'on',
			'contact_form'	=> 'on',
			'opening_hours'	=> 'on',
		);
		
		$teams	= array(333,334,335,336,337,340);
        
		$price_list	= 'a:5:{i:0;a:3:{s:5:"title";s:17:"BIPOLAR DISORDERS";s:5:"price";s:4:"$225";s:11:"description";s:398:"Oral Surgery &amp; Medicine resources are concerned with basic, applied, and clinical aspects of oral infections and diseases, including thseir epidemiology, diagnosis, treatment, and rehabilitation. Specialties such as oral pathology/biology, oral epidemiology, oral rehabilitation, and oral implants are also included. Facial pain and craniomandibular resources are also covered in this category.";}i:1;a:3:{s:5:"title";s:21:"Medical/Surgical Unit";s:5:"price";s:5:"$1500";s:11:"description";s:397:"Oral Surgery &amp; Medicine resources are concerned with basic, applied, and clinical aspects of oral infections and diseases, including their epidemiology, diagnosis, treatment, and rehabilitation. Specialties such as oral pathology/biology, oral epidemiology, oral rehabilitation, and oral implants are also included. Facial pain and craniomandibular resources are also covered in this category.";}i:2;a:3:{s:5:"title";s:24:"Inpatient Rehabilitation";s:5:"price";s:8:"$1091.44";s:11:"description";s:397:"Oral Surgery &amp; Medicine resources are concerned with basic, applied, and clinical aspects of oral infections and diseases, including their epidemiology, diagnosis, treatment, and rehabilitation. Specialties such as oral pathology/biology, oral epidemiology, oral rehabilitation, and oral implants are also included. Facial pain and craniomandibular resources are also covered in this category.";}i:3;a:3:{s:5:"title";s:25:"Cesarean Section Delivery";s:5:"price";s:9:"$13182.79";s:11:"description";s:397:"Oral Surgery &amp; Medicine resources are concerned with basic, applied, and clinical aspects of oral infections and diseases, including their epidemiology, diagnosis, treatment, and rehabilitation. Specialties such as oral pathology/biology, oral epidemiology, oral rehabilitation, and oral implants are also included. Facial pain and craniomandibular resources are also covered in this category.";}i:4;a:3:{s:5:"title";s:32:"Pediatric Evaluation – Level 1";s:5:"price";s:7:"$387.91";s:11:"description";s:397:"Oral Surgery &amp; Medicine resources are concerned with basic, applied, and clinical aspects of oral infections and diseases, including their epidemiology, diagnosis, treatment, and rehabilitation. Specialties such as oral pathology/biology, oral epidemiology, oral rehabilitation, and oral implants are also included. Facial pain and craniomandibular resources are also covered in this category.";}}';
		
		$professional_statement	= 'In just three simple steps, DocDirect will help you find your nearest healthcare setting without having to signup. We aim to facilitate you in finding your right doctor with just three clicks without having to ask around or wander to find your nearest healthcare facility.';
		foreach ($user_query->results as $user) {
            $directory_type = get_user_meta($user->ID, 'directory_type', true);
			$username = docdirect_get_username($user->ID);

			update_user_meta( $user->ID, 'show_admin_bar_front', false );
			update_user_meta( $user->ID, 'rich_editing', 'true' );
			update_user_meta( $user->ID, 'professional_statements', $professional_statement );
			
			//Update privacy settings
			update_user_meta( $user->ID, 'privacy', $privacy );
		
			//update privacy for search
			if( !empty( $privacy ) ) {
				foreach( $privacy as $key => $value ) {
					update_user_meta( $user->ID, $key, $value );
				}
			}
			
			$package_expiry	= date('2018-06-06');
			
			update_user_meta( $user->ID, 'prices_list', unserialize($price_list) );
			update_user_meta( $user->ID, 'full_name', $username );
			update_user_meta( $user->ID, 'show_admin_bar_front', 'false' );

			update_user_meta($user->ID,'user_current_package_expiry',strtotime( $package_expiry )); //package duration
			update_user_meta($user->ID,'user_featured',strtotime( '2017-12-31' )); //featured Expiry
			update_user_meta($user->ID,'user_current_package',34); //Current package
			
			//Update Banners
			if (!empty($directory_type) && $directory_type == 121) {
				update_user_meta($user->ID, 'userprofile_banner', $banners[121]); //Fintness Center
			} else if (!empty($directory_type) && $directory_type == 122) {
				update_user_meta($user->ID, 'userprofile_banner', $banners[122]); //Blood Bank
			} else if (!empty($directory_type) && $directory_type == 123) {
				update_user_meta($user->ID, 'userprofile_banner', $banners[123]); //Pharmacy
			} else if (!empty($directory_type) && $directory_type == 125) {
				update_user_meta($user->ID, 'userprofile_banner', $banners[125]); //Clinics
			} else if (!empty($directory_type) && $directory_type == 126) {
				update_user_meta($user->ID, 'userprofile_banner', $banners[126]); //Hospital
			} else if (!empty($directory_type) && $directory_type == 127) {
				update_user_meta($user->ID, 'userprofile_banner', $banners[127]); //Doctors
			}
			
			//Update Experience
			if (!empty($directory_type) && $directory_type == 127) {
				
				$experiences	= array();
				if( !empty( $experience ) ){
					$counter	= 0;
					foreach( $experience as $key => $value ){
						if( !empty( $value['title'] ) && !empty( $value['company'] ) ) {
							$experiences[$counter]['title']	= 	esc_attr( $value['title'] ); 
							$experiences[$counter]['company']	 = 	esc_attr( $value['company'] ); 
							$experiences[$counter]['start_date']	= 	esc_attr( $value['start_date'] ); 
							$experiences[$counter]['end_date']	  = 	esc_attr( $value['end_date'] ); 
							$experiences[$counter]['start_date_formated']  = date('M,Y',strtotime($value['start_date'])); 
							$experiences[$counter]['end_date_formated']	= date('M,Y',strtotime($value['end_date'])); 
							$experiences[$counter]['description']	= 	esc_attr( $value['description'] ); 
							$counter++;
						}
					}
					$json['experience']	= $experiences;
				}
				
				update_user_meta( $user->ID, 'experience', $experiences );
			}
			
			update_user_meta( $user->ID, 'paypal_enable', 'on' );
			update_user_meta( $user->ID, 'paypal_email_id', 'wordpress@themographics.com' );
			update_user_meta( $user->ID, 'stripe_enable', 'on' );
			update_user_meta( $user->ID, 'stripe_secret', 'sk_test_GQmHllLhCKNDNx6f0T2cIfTM' );
			update_user_meta( $user->ID, 'stripe_publishable', 'pk_test_v9JXtjELddI4r9unGBShp8TX' );
			update_user_meta( $user->ID, 'stripe_site', 'DocDirect Stripe Payment' );
			update_user_meta( $user->ID, 'stripe_decimal', 2 );
			
			//Import Timings, categories and services
			if (!empty($categories[$directory_type])) {
				
				update_user_meta($user->ID, 'approved_title', $categories['generals']['approved_title']);
                update_user_meta($user->ID, 'confirmation_title', $categories['generals']['confirmation_title']);
                update_user_meta($user->ID, 'cancelled_title', $categories['generals']['cancelled_title']);
                update_user_meta($user->ID, 'thank_you', $categories['generals']['thank_you']);
                update_user_meta($user->ID, 'schedule_message', $categories['generals']['schedule_message']);
                update_user_meta($user->ID, 'booking_approved', $categories['generals']['booking_approved']);
                update_user_meta($user->ID, 'booking_confirmed', $categories['generals']['booking_confirmed']);
                update_user_meta($user->ID, 'booking_cancelled', $categories['generals']['booking_cancelled']);
                update_user_meta($user->ID, 'currency_symbol', $categories['generals']['currency_symbol']);
                update_user_meta($user->ID, 'currency', $categories['generals']['currency']);
                update_user_meta($user->ID, 'privacy', $categories['generals']['privacy']);
                update_user_meta($user->ID, 'custom_slots', addslashes($categories['generals']['custom_slots']));
                update_user_meta($user->ID, 'default_slots', unserialize($categories['generals']['default_slots']));
                update_user_meta($user->ID, 'services_cats', $categories[$directory_type]['services_cats']);
                update_user_meta($user->ID, 'booking_services', $categories[$directory_type]['booking_services']);
            }
			
			//Update Teams
			$team_id	= array();
			$team_id[]  = $user->ID;
			$teamsdata = array_diff( $teams , $team_id );	
			update_user_meta($user->ID,'teams_data',$teamsdata);
        }
    }

   //docdirect_update_users();
}


//update demo articles
if (!function_exists('docdirect_update_articles')) {
	function docdirect_update_articles(){
		$data	= array(
			3083 => array(
				'user'	=> 330,
				'category' => 127
			),
			587 => array(
				'user'	=> 330,
				'category' => 127
			),
			3086 => array(
				'user'	=> 330,
				'category' => 127
			),
			585 => array(
				'user'	=> 330,
				'category' => 127
			),
			583 => array(
				'user'	=> 331,
				'category' => 127
			),
			578 => array(
				'user'	=> 335,
				'category' => 127
			),
			3084 => array(
				'user'	=> 334,
				'category' => 127
			),
			581 => array(
				'user'	=> 333,
				'category' => 127
			),
			3085 => array(
				'user'	=> 332,
				'category' => 127
			),
			3082 => array(
				'user'	=> 340,
				'category' => 127
			),

		);

		foreach( $data as $key => $val){

			update_post_meta($key, 'provider_category', $val['category']);
			$arg = array(
				'ID' => intval($key ),
				'post_author' => intval($val['user']),
			);
            
			wp_update_post($arg);
		}
	}

	//docdirect_update_articles();
}