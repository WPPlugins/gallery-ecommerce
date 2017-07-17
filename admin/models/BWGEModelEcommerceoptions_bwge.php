<?php

class BWGEModelEcommerceoptions_bwge extends BWGEModel {
  ////////////////////////////////////////////////////////////////////////////////////////
  // Events                                                                             //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constants                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Variables                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Constructor & Destructor                                                           //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Public Methods                                                                     //
  ////////////////////////////////////////////////////////////////////////////////////////
	public function get_options(){
		global $wpdb;
  
    $query = "SELECT * FROM ". $wpdb->prefix . "bwge_ecommerceoptions ";
		$rows = $wpdb->get_results($query);	

    $options = new stdClass();
    foreach ($rows as $row) {
        $name = $row->name;
        $value = $row->value;
        $options->$name = $value;
    }
   
    return $options;

	}

	public function get_lists(){
		$lists = array();
		//countries
		$countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
        
        // currencies

        $currencies = array (
        "AED" => "United Arab Emirates Dirham",
		"AFN" => "Afghan Afghani",
		"ALL" => "Albanian Lek",
		"AMD" => "Armenian Dram",
		"ANG" => "Netherlands Antillean Gulden",
		"AOA" => "Angolan Kwanza",
		"ARS" => "Argentine Peso",
		"AUD" => "Australian Dollar",
		"AWG" => "Aruban Florin",
		"AZN" => "Azerbaijani Manat",
		"BAM" => "Bosnia & Herzegovina Convertible Mark",
		"BBD" => "Barbadian Dollar",
		"BDT" => "Bangladeshi Taka",
		"BGN" => "Bulgarian Lev",
		"BIF" => "Burundian Franc",
		"BMD" => "Bermudian Dollar",
		"BND" => "Brunei Dollar",
		"BOB" => "Bolivian Boliviano",
		"BRL" => "Brazilian Real",
		"BSD" => "Bahamian Dollar",
		"BWP" => "Botswana Pula",
		"BZD" => "Belize Dollar",
		"CAD" => "Canadian Dollar",
		"CDF" => "Congolese Franc",
		"CHF" => "Swiss Franc",
		"CLP" => "Chilean Peso",
		"CNY" => "Chinese Renminbi Yuan",
		"COP" => "Colombian Peso",
		"CRC" => "Costa Rican Colón",
		"CVE" => "Cape Verdean Escudo",
		"CZK" => "Czech Koruna",
		"DJF" => "Djiboutian Franc",
		"DKK" => "Danish Krone",
		"DOP" => "Dominican Peso",
		"DZD" => "Algerian Dinar",
		"EEK" => "Estonian Kroon",
		"EGP" => "Egyptian Pound",
		"ETB" => "Ethiopian Birr",
		"EUR" => "Euro",
		"FJD" => "Fijian Dollar",
		"FKP" => "Falkland Islands Pound",
		"GBP" => "British Pound",
		"GEL" => "Georgian Lari",
		"GIP" => "Gibraltar Pound",
		"GMD" => "Gambian Dalasi",
		"GNF" => "Guinean Franc",
		"GTQ" => "Guatemalan Quetzal",
		"GYD" => "Guyanese Dollar",
		"HKD" => "Hong Kong Dollar",
		"HNL" => "Honduran Lempira",
		"HRK" => "Croatian Kuna",
		"HTG" => "Haitian Gourde",
		"HUF" => "Hungarian Forint",
		"IDR" => "Indonesian Rupiah",
		"ILS" => "Israeli New Sheqel",
		"INR" => "Indian Rupee",
		"ISK" => "Icelandic Króna",
		"JMD" => "Jamaican Dollar",
		"JPY" => "Japanese Yen",
		"KES" => "Kenyan Shilling",
		"KGS" => "Kyrgyzstani Som",
		"KHR" => "Cambodian Riel",
		"KMF" => "Comorian Franc",
		"KRW" => "South Korean Won",
		"KYD" => "Cayman Islands Dollar",
		"KZT" => "Kazakhstani Tenge",
		"LAK" => "Lao Kip",
		"LBP" => "Lebanese Pound",
		"LKR" => "Sri Lankan Rupee",
		"LRD" => "Liberian Dollar",
		"LSL" => "Lesotho Loti",
		"LTL" => "Lithuanian Litas",
		"LVL" => "Latvian Lats",
		"MAD" => "Moroccan Dirham",
		"MDL" => "Moldovan Leu",
		"MGA" => "Malagasy Ariary",
		"MKD" => "Macedonian Denar",
		"MNT" => "Mongolian Tögrög",
		"MOP" => "Macanese Pataca",
		"MRO" => "Mauritanian Ouguiya",
		"MUR" => "Mauritian Rupee",
		"MVR" => "Maldivian Rufiyaa",
		"MWK" => "Malawian Kwacha",
		"MXN" => "Mexican Peso",
		"MYR" => "Malaysian Ringgit",
		"MZN" => "Mozambican Metical",
		"NAD" => "Namibian Dollar",
		"NGN" => "Nigerian Naira",
		"NIO" => "Nicaraguan Córdoba",
		"NOK" => "Norwegian Krone",
		"NPR" => "Nepalese Rupee",
		"NZD" => "New Zealand Dollar",
		"PAB" => "Panamanian Balboa",
		"PEN" => "Peruvian Nuevo Sol",
		"PGK" => "Papua New Guinean Kina",
		"PHP" => "Philippine Peso",
		"PKR" => "Pakistani Rupee",
		"PLN" => "Polish Złoty",
		"PYG" => "Paraguayan Guaraní",
		"QAR" => "Qatari Riyal",
		"RON" => "Romanian Leu",
		"RSD" => "Serbian Dinar",
		"RUB" => "Russian Ruble",
		"RWF" => "Rwandan Franc",
		"SAR" => "Saudi Riyal",
		"SBD" => "Solomon Islands Dollar",
		"SCR" => "Seychellois Rupee",
		"SEK" => "Swedish Krona",
		"SGD" => "Singapore Dollar",
		"SHP" => "Saint Helenian Pound",
		"SLL" => "Sierra Leonean Leone",
		"SOS" => "Somali Shilling",
		"SRD" => "Surinamese Dollar",
		"STD" => "São Tomé and Príncipe Dobra",
		"SVC" => "Salvadoran Colón",
		"SZL" => "Swazi Lilangeni",
		"THB" => "Thai Baht",
		"TJS" => "Tajikistani Somoni",
		"TOP" => "Tongan Paʻanga",
		"TRY" => "Turkish Lira",
		"TTD" => "Trinidad and Tobago Dollar",
		"TTD" => "New Taiwan Dollar",
		"TZS" => "Tanzanian Shilling",
		"UAH" => "Ukrainian Hryvnia",
		"UGX" => "Ugandan Shilling",
		"USD" => "United States Dollar",
		"UYU" => "Uruguayan Peso",
		"UZS" => "Uzbekistani Som",
		"VEF" => "Venezuelan Bolívar",
		"VND" => "Vietnamese Đồng",
		"VUV" => "Vanuatu Vatu",
		"WST" => "Samoan Tala",
		"XAF" => "Central African Cfa Franc",
		"XCD" => "East Caribbean Dollar",
		"XOF" => "West African Cfa Franc",
		"XPF" => "Cfp Franc",
		"YER" => "Yemeni Rial",
		"ZAR" => "South African Rand",
		"ZMW" => "Zambian Kwacha"
        );
		
        asort($currencies);
		//pages

		$pages_array_of_objects = BWGEHelper::get_pages(); 
		
		foreach($pages_array_of_objects as $page){
			$pages[$page->ID] = $page->post_title;
		}
			
		$lists["countries"] = $countries;
		$lists["currencies"] = $currencies;
		$lists["pages"] = $pages;
		
		return $lists;
	}

    
    public function get_payments_row($type){
 		global $wpdb;	
		
		
		$row = $wpdb->get_row("SELECT * FROM ".$wpdb->prefix. "bwge_payment_systems WHERE short_name='".$type."'");

		$row->field_types = array();	
		$row->fields = array();	
		if($type!='without_online_payment'){		
			$class_name = 'BWGE'.ucfirst($row->short_name);	
                
			$field_types = $class_name::$field_types;
			
			// additional data

			$row_options = json_decode(htmlspecialchars_decode($row->options));
			$row->fields = $row_options;	
			$row->field_types = $field_types;	
			$row->class_name = $class_name;	
				
			
		}
		
		return $row;   
    
    }
    
    public function get_payments_lists($row){
      $lists = array();

        $class_name = $row->class_name ;
        $fields = $class_name::$field_types;
        // checkout mode
        $radio_list = array();
        foreach($fields as $k => $field){				
            if($field['type'] == 'radio'){
                $radio_list[$k] = array();
                foreach( $field['options'] as $key => $value )
                    $radio_list[$k][] = array('value' => $key, 'text' => $value);
            }		
        }				
        $lists["radio"] = $radio_list;
		
      return $lists;
	
	}
   

  ////////////////////////////////////////////////////////////////////////////////////////
  // Getters & Setters                                                                  //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Private Methods                                                                    //
  ////////////////////////////////////////////////////////////////////////////////////////
  ////////////////////////////////////////////////////////////////////////////////////////
  // Listeners                                                                          //
  ////////////////////////////////////////////////////////////////////////////////////////
}