<?php

global $bbit;

/*
from: http://schema.org/docs/full.html

var $lb = $('a[href="../LocalBusiness"]').eq(1),
$parent = $lb.parents('table.h').eq(0),
$rows = $parent.find('> tbody > tr').filter(function(i) {
    return i>0;
});

$rows.each(function (i, elem) {
    var $that = $(this),
    $group = $that.find('> td > table.h > tbody > tr'),
    $groupTitle = $group.find('> td.tc'),
    groupTitle = $groupTitle.find('a').text(),
    $groupItems = $group.find('> td > table.h > tbody > tr');
    
    //console.log('<optgroup label="'+groupTitle+'">');
    console.log("'" + groupTitle + "' => array(");
    
    var len = $groupItems.length;
    if (len == 0)
        console.log("\t'" + groupTitle
        + "' => __('" + groupTitle + "', $bbit->localizationName)");

    $groupItems.each(function (i2, e2) {
        var $item = $(this),
        $item2 = $item.find('> td.tc'),
        itemTitle = $item2.find('a').text();
        
        //console.log('\t<option value="'+itemTitle+'">'+itemTitle+'</option>');
        console.log("\t'" + itemTitle
        + "' => __('" + itemTitle + "', $bbit->localizationName)"
        + ( i2 == len - 1 ? '' : ',' ));
    });
    
    //console.log('</optgroup>');
    console.log("),");
    
});

*/

$bbit_business_type_list = array(
	'AnimalShelter' => array(
	
		'AnimalShelter' => __('AnimalShelter', $bbit->localizationName)
	
	),
	'AutomotiveBusiness' => array(
	
		'AutoBodyShop' => __('AutoBodyShop', $bbit->localizationName),
	
		'AutoDealer' => __('AutoDealer', $bbit->localizationName),
	
		'AutoPartsStore*' => __('AutoPartsStore*', $bbit->localizationName),
	
		'AutoRental' => __('AutoRental', $bbit->localizationName),
	
		'AutoRepair' => __('AutoRepair', $bbit->localizationName),
	
		'AutoWash' => __('AutoWash', $bbit->localizationName),
	
		'GasStation' => __('GasStation', $bbit->localizationName),
	
		'MotorcycleDealer' => __('MotorcycleDealer', $bbit->localizationName),
	
		'MotorcycleRepair' => __('MotorcycleRepair', $bbit->localizationName)
	
	),
	'ChildCare' => array(
	
		'ChildCare' => __('ChildCare', $bbit->localizationName)
	
	),
	'DryCleaningOrLaundry' => array(
	
		'DryCleaningOrLaundry' => __('DryCleaningOrLaundry', $bbit->localizationName)
	
	),
	'EmergencyService' => array(
	
		'FireStation*' => __('FireStation*', $bbit->localizationName),
	
		'Hospital*' => __('Hospital*', $bbit->localizationName),
	
		'PoliceStation*' => __('PoliceStation*', $bbit->localizationName)
	
	),
	'EmploymentAgency' => array(
	
		'EmploymentAgency' => __('EmploymentAgency', $bbit->localizationName)
	
	),
	'EntertainmentBusiness' => array(
	
		'AdultEntertainment' => __('AdultEntertainment', $bbit->localizationName),
	
		'AmusementPark' => __('AmusementPark', $bbit->localizationName),
	
		'ArtGallery' => __('ArtGallery', $bbit->localizationName),
	
		'Casino' => __('Casino', $bbit->localizationName),
	
		'ComedyClub' => __('ComedyClub', $bbit->localizationName),
	
		'MovieTheater*' => __('MovieTheater*', $bbit->localizationName),
	
		'NightClub' => __('NightClub', $bbit->localizationName)
	
	),
	'FinancialService' => array(
	
		'AccountingService*' => __('AccountingService*', $bbit->localizationName),
	
		'AutomatedTeller' => __('AutomatedTeller', $bbit->localizationName),
	
		'BankOrCreditUnion' => __('BankOrCreditUnion', $bbit->localizationName),
	
		'InsuranceAgency' => __('InsuranceAgency', $bbit->localizationName)
	
	),
	'FoodEstablishment' => array(
	
		'Bakery' => __('Bakery', $bbit->localizationName),
	
		'BarOrPub' => __('BarOrPub', $bbit->localizationName),
	
		'Brewery' => __('Brewery', $bbit->localizationName),
	
		'CafeOrCoffeeShop' => __('CafeOrCoffeeShop', $bbit->localizationName),
	
		'FastFoodRestaurant' => __('FastFoodRestaurant', $bbit->localizationName),
	
		'IceCreamShop' => __('IceCreamShop', $bbit->localizationName),
	
		'Restaurant' => __('Restaurant', $bbit->localizationName),
	
		'Winery' => __('Winery', $bbit->localizationName)
	
	),
	'GovernmentOffice' => array(
	
		'PostOffice' => __('PostOffice', $bbit->localizationName)
	
	),
	'HealthAndBeautyBusiness' => array(
	
		'BeautySalon' => __('BeautySalon', $bbit->localizationName),
	
		'DaySpa' => __('DaySpa', $bbit->localizationName),
	
		'HairSalon' => __('HairSalon', $bbit->localizationName),
	
		'HealthClub*' => __('HealthClub*', $bbit->localizationName),
	
		'NailSalon' => __('NailSalon', $bbit->localizationName),
	
		'TattooParlor' => __('TattooParlor', $bbit->localizationName)
	
	),
	'HomeAndConstructionBusiness' => array(
	
		'Electrician*' => __('Electrician*', $bbit->localizationName),
	
		'GeneralContractor*' => __('GeneralContractor*', $bbit->localizationName),
	
		'HVACBusiness' => __('HVACBusiness', $bbit->localizationName),
	
		'HousePainter*' => __('HousePainter*', $bbit->localizationName),
	
		'Locksmith*' => __('Locksmith*', $bbit->localizationName),
	
		'MovingCompany' => __('MovingCompany', $bbit->localizationName),
	
		'Plumber*' => __('Plumber*', $bbit->localizationName),
	
		'RoofingContractor*' => __('RoofingContractor*', $bbit->localizationName)
	
	),
	'InternetCafe' => array(
	
		'InternetCafe' => __('InternetCafe', $bbit->localizationName)
	
	),
	'Library' => array(
	
		'Library' => __('Library', $bbit->localizationName)
	
	),
	'LodgingBusiness' => array(
	
		'BedAndBreakfast' => __('BedAndBreakfast', $bbit->localizationName),
	
		'Hostel' => __('Hostel', $bbit->localizationName),
	
		'Hotel' => __('Hotel', $bbit->localizationName),
	
		'Motel' => __('Motel', $bbit->localizationName)
	
	),
	'MedicalOrganization' => array(
	
		'Dentist*' => __('Dentist*', $bbit->localizationName),
	
		'DiagnosticLab' => __('DiagnosticLab', $bbit->localizationName),
	
		'Hospital*' => __('Hospital*', $bbit->localizationName),
	
		'MedicalClinic' => __('MedicalClinic', $bbit->localizationName),
	
		'Optician' => __('Optician', $bbit->localizationName),
	
		'Pharmacy' => __('Pharmacy', $bbit->localizationName),
	
		'Physician' => __('Physician', $bbit->localizationName),
	
		'VeterinaryCare' => __('VeterinaryCare', $bbit->localizationName)
	
	),
	'ProfessionalService' => array(
	
		'AccountingService*' => __('AccountingService*', $bbit->localizationName),
	
		'Attorney' => __('Attorney', $bbit->localizationName),
	
		'Dentist*' => __('Dentist*', $bbit->localizationName),
	
		'Electrician*' => __('Electrician*', $bbit->localizationName),
	
		'GeneralContractor*' => __('GeneralContractor*', $bbit->localizationName),
	
		'HousePainter*' => __('HousePainter*', $bbit->localizationName),
	
		'Locksmith*' => __('Locksmith*', $bbit->localizationName),
	
		'Notary' => __('Notary', $bbit->localizationName),
	
		'Plumber*' => __('Plumber*', $bbit->localizationName),
	
		'RoofingContractor*' => __('RoofingContractor*', $bbit->localizationName)
	
	),
	'RadioStation' => array(
	
		'RadioStation' => __('RadioStation', $bbit->localizationName)
	
	),
	'RealEstateAgent' => array(
	
		'RealEstateAgent' => __('RealEstateAgent', $bbit->localizationName)
	
	),
	'RecyclingCenter' => array(
	
		'RecyclingCenter' => __('RecyclingCenter', $bbit->localizationName)
	
	),
	'SelfStorage' => array(
	
		'SelfStorage' => __('SelfStorage', $bbit->localizationName)
	
	),
	'ShoppingCenter' => array(
	
		'ShoppingCenter' => __('ShoppingCenter', $bbit->localizationName)
	
	),
	'SportsActivityLocation' => array(
	
		'BowlingAlley' => __('BowlingAlley', $bbit->localizationName),
	
		'ExerciseGym' => __('ExerciseGym', $bbit->localizationName),
	
		'GolfCourse' => __('GolfCourse', $bbit->localizationName),
	
		'HealthClub*' => __('HealthClub*', $bbit->localizationName),
	
		'PublicSwimmingPool' => __('PublicSwimmingPool', $bbit->localizationName),
	
		'SkiResort' => __('SkiResort', $bbit->localizationName),
	
		'SportsClub' => __('SportsClub', $bbit->localizationName),
	
		'StadiumOrArena*' => __('StadiumOrArena*', $bbit->localizationName),
	
		'TennisComplex' => __('TennisComplex', $bbit->localizationName)
	
	),
	'Store' => array(
	
		'AutoPartsStore*' => __('AutoPartsStore*', $bbit->localizationName),
	
		'BikeStore' => __('BikeStore', $bbit->localizationName),
	
		'BookStore' => __('BookStore', $bbit->localizationName),
	
		'ClothingStore' => __('ClothingStore', $bbit->localizationName),
	
		'ComputerStore' => __('ComputerStore', $bbit->localizationName),
	
		'ConvenienceStore' => __('ConvenienceStore', $bbit->localizationName),
	
		'DepartmentStore' => __('DepartmentStore', $bbit->localizationName),
	
		'ElectronicsStore' => __('ElectronicsStore', $bbit->localizationName),
	
		'Florist' => __('Florist', $bbit->localizationName),
	
		'FurnitureStore' => __('FurnitureStore', $bbit->localizationName),
	
		'GardenStore' => __('GardenStore', $bbit->localizationName),
	
		'GroceryStore' => __('GroceryStore', $bbit->localizationName),
	
		'HardwareStore' => __('HardwareStore', $bbit->localizationName),
	
		'HobbyShop' => __('HobbyShop', $bbit->localizationName),
	
		'HomeGoodsStore' => __('HomeGoodsStore', $bbit->localizationName),
	
		'JewelryStore' => __('JewelryStore', $bbit->localizationName),
	
		'LiquorStore' => __('LiquorStore', $bbit->localizationName),
	
		'MensClothingStore' => __('MensClothingStore', $bbit->localizationName),
	
		'MobilePhoneStore' => __('MobilePhoneStore', $bbit->localizationName),
	
		'MovieRentalStore' => __('MovieRentalStore', $bbit->localizationName),
	
		'MusicStore' => __('MusicStore', $bbit->localizationName),
	
		'OfficeEquipmentStore' => __('OfficeEquipmentStore', $bbit->localizationName),
	
		'OutletStore' => __('OutletStore', $bbit->localizationName),
	
		'PawnShop' => __('PawnShop', $bbit->localizationName),
	
		'PetStore' => __('PetStore', $bbit->localizationName),
	
		'ShoeStore' => __('ShoeStore', $bbit->localizationName),
	
		'SportingGoodsStore' => __('SportingGoodsStore', $bbit->localizationName),
	
		'TireShop' => __('TireShop', $bbit->localizationName),
	
		'ToyStore' => __('ToyStore', $bbit->localizationName),
	
		'WholesaleStore' => __('WholesaleStore', $bbit->localizationName)
	
	),
	'TelevisionStation' => array(
	
		'TelevisionStation' => __('TelevisionStation', $bbit->localizationName)
	
	),
	'TouristInformationCenter' => array(
	
		'TouristInformationCenter' => __('TouristInformationCenter', $bbit->localizationName)
	
	),
	'TravelAgency' => array(
	
		'TravelAgency' => __('TravelAgency', $bbit->localizationName)
	
	)
);


/*
from: http://www.state.gov/misc/list/

var $blocks = $('blockquote[dir="ltr"]');
$blocks.each(function (i,e) {
    var $that = $(this), $rows = $that.find('> p > a');
    $rows.each(function (i2, e2) {
        var country = $(this).text();
        country = $.trim(country);
        country = country.replace(/'/g, "\\'");
        console.log( "'" + country + "' => '" + country
        + "',");
    });
});
*/

$bbit_countries_list = array(
	'Afghanistan' => 'Afghanistan',
	'Albania' => 'Albania',
	'Algeria' => 'Algeria',
	'Andorra' => 'Andorra',
	'Angola' => 'Angola',
	'Antigua and Barbuda' => 'Antigua and Barbuda',
	'Argentina' => 'Argentina',
	'Armenia' => 'Armenia',
	'Aruba' => 'Aruba',
	'Australia' => 'Australia',
	'Austria' => 'Austria',
	'Azerbaijan' => 'Azerbaijan',
	'Bahamas, The' => 'Bahamas, The',
	'Bahrain' => 'Bahrain',
	'Bangladesh' => 'Bangladesh',
	'Barbados' => 'Barbados',
	'Belarus' => 'Belarus',
	'Belgium' => 'Belgium',
	'Belize' => 'Belize',
	'Benin' => 'Benin',
	'Bhutan' => 'Bhutan',
	'Bolivia' => 'Bolivia',
	'Bosnia and Herzegovina' => 'Bosnia and Herzegovina',
	'Botswana' => 'Botswana',
	'Brazil' => 'Brazil',
	'Brunei' => 'Brunei',
	'Bulgaria' => 'Bulgaria',
	'Burkina Faso' => 'Burkina Faso',
	'Burma' => 'Burma',
	'Burundi' => 'Burundi',
	'Cambodia' => 'Cambodia',
	'Cameroon' => 'Cameroon',
	'Canada' => 'Canada',
	'Cape Verde' => 'Cape Verde',
	'Central African Republic' => 'Central African Republic',
	'Chad' => 'Chad',
	'Chile' => 'Chile',
	'China' => 'China',
	'Colombia' => 'Colombia',
	'Comoros' => 'Comoros',
	'Congo, Democratic Republic of the' => 'Congo, Democratic Republic of the',
	'Congo, Republic of the' => 'Congo, Republic of the',
	'Costa Rica' => 'Costa Rica',
	'Cote d\'Ivoire' => 'Cote d\'Ivoire',
	'Croatia' => 'Croatia',
	'Cuba' => 'Cuba',
	'Curacao' => 'Curacao',
	'Cyprus' => 'Cyprus',
	'Czech Republic' => 'Czech Republic',
	'Denmark' => 'Denmark',
	'Djibouti' => 'Djibouti',
	'Dominica' => 'Dominica',
	'Dominican Republic' => 'Dominican Republic',
	'Timor-Leste' => 'Timor-Leste',
	'Ecuador' => 'Ecuador',
	'Egypt' => 'Egypt',
	'El Salvador' => 'El Salvador',
	'Equatorial Guinea' => 'Equatorial Guinea',
	'Eritrea' => 'Eritrea',
	'Estonia' => 'Estonia',
	'Ethiopia' => 'Ethiopia',
	'Fiji' => 'Fiji',
	'Finland' => 'Finland',
	'France' => 'France',
	'Gabon' => 'Gabon',
	'Gambia, The' => 'Gambia, The',
	'Georgia' => 'Georgia',
	'Germany' => 'Germany',
	'Ghana' => 'Ghana',
	'Greece' => 'Greece',
	'Grenada' => 'Grenada',
	'Guatemala' => 'Guatemala',
	'Guinea' => 'Guinea',
	'Guinea-Bissau' => 'Guinea-Bissau',
	'Guyana' => 'Guyana',
	'Haiti' => 'Haiti',
	'Holy See' => 'Holy See',
	'Honduras' => 'Honduras',
	'Hong Kong' => 'Hong Kong',
	'Hungary' => 'Hungary',
	'Iceland' => 'Iceland',
	'India' => 'India',
	'Indonesia' => 'Indonesia',
	'Iran' => 'Iran',
	'Iraq' => 'Iraq',
	'Ireland' => 'Ireland',
	'Israel' => 'Israel',
	'Italy' => 'Italy',
	'Jamaica' => 'Jamaica',
	'Japan' => 'Japan',
	'Jordan' => 'Jordan',
	'Kazakhstan' => 'Kazakhstan',
	'Kenya' => 'Kenya',
	'Kiribati' => 'Kiribati',
	'Korea, North' => 'Korea, North',
	'Korea, South' => 'Korea, South',
	'Kosovo' => 'Kosovo',
	'Kuwait' => 'Kuwait',
	'Kyrgyzstan' => 'Kyrgyzstan',
	'Laos' => 'Laos',
	'Latvia' => 'Latvia',
	'Lebanon' => 'Lebanon',
	'Lesotho' => 'Lesotho',
	'Liberia' => 'Liberia',
	'Libya' => 'Libya',
	'Liechtenstein' => 'Liechtenstein',
	'Lithuania' => 'Lithuania',
	'Luxembourg' => 'Luxembourg',
	'Macau' => 'Macau',
	'Macedonia' => 'Macedonia',
	'Madagascar' => 'Madagascar',
	'Malawi' => 'Malawi',
	'Malaysia' => 'Malaysia',
	'Maldives' => 'Maldives',
	'Mali' => 'Mali',
	'Malta' => 'Malta',
	'Marshall Islands' => 'Marshall Islands',
	'Mauritania' => 'Mauritania',
	'Mauritius' => 'Mauritius',
	'Mexico' => 'Mexico',
	'Micronesia' => 'Micronesia',
	'Moldova' => 'Moldova',
	'Monaco' => 'Monaco',
	'Mongolia' => 'Mongolia',
	'Montenegro' => 'Montenegro',
	'Morocco' => 'Morocco',
	'Mozambique' => 'Mozambique',
	'Namibia' => 'Namibia',
	'Nauru' => 'Nauru',
	'Nepal' => 'Nepal',
	'Netherlands' => 'Netherlands',
	'Netherlands Antilles' => 'Netherlands Antilles',
	'New Zealand' => 'New Zealand',
	'Nicaragua' => 'Nicaragua',
	'Niger' => 'Niger',
	'Nigeria' => 'Nigeria',
	'North Korea' => 'North Korea',
	'Norway' => 'Norway',
	'Oman' => 'Oman',
	'Pakistan' => 'Pakistan',
	'Palau' => 'Palau',
	'Palestinian Territories' => 'Palestinian Territories',
	'Panama' => 'Panama',
	'Papua New Guinea' => 'Papua New Guinea',
	'Paraguay' => 'Paraguay',
	'Peru' => 'Peru',
	'Philippines' => 'Philippines',
	'Poland' => 'Poland',
	'Portugal' => 'Portugal',
	'Qatar' => 'Qatar',
	'Romania' => 'Romania',
	'Russia' => 'Russia',
	'Rwanda' => 'Rwanda',
	'Saint Kitts and Nevis' => 'Saint Kitts and Nevis',
	'Saint Lucia' => 'Saint Lucia',
	'Saint Vincent and the Grenadines' => 'Saint Vincent and the Grenadines',
	'Samoa' => 'Samoa',
	'San Marino' => 'San Marino',
	'Sao Tome and Principe' => 'Sao Tome and Principe',
	'Saudi Arabia' => 'Saudi Arabia',
	'Senegal' => 'Senegal',
	'Serbia' => 'Serbia',
	'Seychelles' => 'Seychelles',
	'Sierra Leone' => 'Sierra Leone',
	'Singapore' => 'Singapore',
	'Sint Maarten' => 'Sint Maarten',
	'Slovakia' => 'Slovakia',
	'Slovenia' => 'Slovenia',
	'Solomon Islands' => 'Solomon Islands',
	'Somalia' => 'Somalia',
	'South Africa' => 'South Africa',
	'South Korea' => 'South Korea',
	'South Sudan' => 'South Sudan',
	'Spain' => 'Spain',
	'Sri Lanka' => 'Sri Lanka',
	'Sudan' => 'Sudan',
	'Suriname' => 'Suriname',
	'Swaziland' => 'Swaziland',
	'Sweden' => 'Sweden',
	'Switzerland' => 'Switzerland',
	'Syria' => 'Syria',
	'Taiwan' => 'Taiwan',
	'Tajikistan' => 'Tajikistan',
	'Tanzania' => 'Tanzania',
	'Thailand' => 'Thailand',
	'Timor-Leste' => 'Timor-Leste',
	'Togo' => 'Togo',
	'Tonga' => 'Tonga',
	'Trinidad and Tobago' => 'Trinidad and Tobago',
	'Tunisia' => 'Tunisia',
	'Turkey' => 'Turkey',
	'Turkmenistan' => 'Turkmenistan',
	'Tuvalu' => 'Tuvalu',
	'Uganda' => 'Uganda',
	'Ukraine' => 'Ukraine',
	'United Arab Emirates' => 'United Arab Emirates',
	'United Kingdom' => 'United Kingdom',
	'United States of America' => 'United States of America',
	'Uruguay' => 'Uruguay',
	'Uzbekistan' => 'Uzbekistan',
	'Vanuatu' => 'Vanuatu',
	'Venezuela' => 'Venezuela',
	'Vietnam' => 'Vietnam',
	'Yemen' => 'Yemen',
	'Zambia' => 'Zambia',
	'Zimbabwe' => 'Zimbabwe'
);


$bbit_days_list = array(
	'monday'		=> __('Monday', $bbit->localizationName),
	'tuesday'		=> __('Tuesday', $bbit->localizationName),
	'wednesday'		=> __('Wednesday', $bbit->localizationName),
	'thursday'		=> __('Thursday', $bbit->localizationName),
	'friday'		=> __('Friday', $bbit->localizationName),
	'saturday'		=> __('Saturday', $bbit->localizationName),
	'sunday'		=> __('Sunday', $bbit->localizationName)
);

?>