<?php
/**
 * A utility libray for PHP unittest.
 * @license public
 * @author Bhargav Vadher <bhargav@bhargavvadher.com>
 * @version 1.0 [2013.07.25 11:18:22PM]
 * @copyright (c) 2013, Bhargav Vadher
 * 
 */
final class MagicBox
{
  private $key = null;
	
	private static $lowerLetters = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
	private static $upperLetters = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	private static $digits = array('0','1','2','3','4','5','6','7','8','9');
	private static $symbols = array('!','@','#','$','%','^','&','*','(',',',')','[',']');
	private static $vowels = array('a','e','i','o','u');
	private static $consonants = array('b','c','d','f','g','h','j','k','l','m','n','p','q','r','s','t','v','w','x','y','z');
	private static $namePrefixes = array(
		'Mr.' => 'Mister',
		'Miss' => 'Miss',
		'Mrs.' => 'Misses'
	);
	private static $tldList = array('com', 'org', 'edu', 'me', 'gov', 'in', 'ca', 'info', 'net', 'mobi', 'us', 'biz', 'co.in', 'co.uk');
	private static $subDomainList = array('www', 'ftp', 'images', 'sftp');
	private static $creditCardList = '{
		"amex": {"name": "American Express","prefix": "34","length": 15},
		"bankcard": {"name": "Bankcard","prefix": "5610","length": 16},
		"chinaunion": {"name": "China UnionPay","prefix": "62","length": 16},
		"dccarte": {"name": "Diners Club Carte Blanche","prefix": "300","length": 14},
		"dcenroute": {"name": "Diners Club enRoute","prefix": "2014","length": 15},
		"dcintl": {"name": "Diners Club International","prefix": "36","length": 14},
		"dcusc": {"name": "Diners Club United States & Canada","prefix": "54","length": 16},
		"discover": {"name": "Discover Card","prefix": "6011","length": 16},
		"instapay": {"name": "Insta Payment","prefix": "637","length": 16},
		"jcb": {"name": "JCB","prefix": "3528","length": 16},
		"laser": {"name": "Laser","prefix": "6304","length": 16},
		"maestro": {"name": "Maestro","prefix": "5018","length": 16},
		"mc": {"name": "Mastercard","prefix": "51","length": 16},
		"solo": {"name": "Solo","prefix": "6334","length": 16},
		"switch": {"name": "Switch","prefix": "4903","length": 16},
		"visa": {"name": "Visa","prefix": "4","length": 16},
		"electron": {"name": "Visa Electron","prefix": "4026","length": 16}
	}';

	private static $streetSuffixList = '{
		"Ave": "Avenue", "Blvd": "Boulevard", "Ctr": "Center", "Cir": "Circle",
		"Ct": "Court", "Dr": "Drive", "Ext": "Extension", "Gln": "Glen", "Grv": "Grove",
		"Hts": "Heights", "Hwy": "Highway", "Jct": "Junction", "Key": "Key", "Ln": "Lane",
		"Loop": "Loop", "Mnr": "Manor", "Mill": "Mill", "Park": "Park", "Pkwy": "Parkway",
		"Pass": "Pass", "Path": "Path", "Pike": "Pike", "Pl": "Place", "Plz": "Plaza",
		"Pt": "Point", "Rdg": "Ridge", "Riv": "River", "Rd": "Road", "Sq": "Square", "St": "Street", 
		"Ter": "Terrace", "Trl": "Terrace", "Tpke": "Turnpike", "Vw": "View", "Way": "Way"
	}';

	private static $stateList = '{
		"AL": "Alabama", "AK": "Alaska", "AZ": "Arizona", "AR": "Arkansas", "CA": "California",
		"CO": "Colorado", "CT": "Connecticut", "DE": "Delaware","DC": "District of Columbia",
		"FL": "Florida", "GA": "Georgia", "HI": "Hawaii", "ID": "Idaho", "IL": "Illinois",
		"IN": "Indiana", "IA": "Iowa", "KS": "Kansas", "KY": "Kentucky", "LA": "Louisiana",
		"ME": "Maine", "MD": "Maryland", "MA": "Massachusetts", "MI": "Michigan", "MN": "Minnesota",
		"MO": "Missouri", "MT": "Montana", "NE": "Nebraska", "NV": "Nevada", "NH": "New Hampshire",
		"NJ": "NewJersey","NM": "New Mexico", "NY": "New York", "NC": "North Carolina",
		"ND": "North Dakota", "OH": "Ohio", "OK": "Oklahoma", "OR": "Oregon", "PA": "Pennsylvania",
		"RI": "Rhode Island", "SC": "South Carolina", "SD": "South Dakota", "TN": "Tennessee",
		"TX": "Texas", "UT": "Utah", "VT": "Vermont", "VA": "Virginia", "WA": "Washington",
		"WV": "West Virginia", "WI": "Wisconsin", "WY": "Wyoming"
	}';

	const MAGIC_BOX_VERSION = 1.0;
	const FLOAT_DECIMAL_POINTS = 7;
	const SYLLABLE_MIN_LENGTH = 2;
	const SYLLABLE_MAX_LENGTH = 3;
	const WORD_MIN_LENGTH = 2;
	const WORD_MAX_LENGTH = 10;
	const SENTENCE_MIN_WORD_LENGTH = 12;
	const SENTENCE_MAX_WORD_LENGTH = 18;
	const PARAGRAPH_MIN_SENTENCE_LENGTH = 1;
	const PARAGRAPH_MAX_SENTENCE_LENGTH = 7;

	public function __construct() {
		date_default_timezone_set('America/Los_Angeles');
		
		if( func_num_args() == 1 ) {
			$this->key = func_get_arg(0);
		} else {
			$this->key = $this->__random();
		}
		return $this;
	}

	
	// ------------------------------------------------------------- //
	// ------------------ Internal Private Helpers ----------------- //
	// ------------------------------------------------------------- //
	private function __random( $min=0, $max=0 ){
		if($min > $max){
			$this->__exception("min cannot be greater than max. Provided min {$min} which is < max {$max}");
		}
		
		if($max === 0){
			$max = PHP_INT_MAX;
		}

		// mt_rand(0, 10) return random value between 0 and 10 both inclusive
		return mt_rand($min, $max);
	}

	private function __exception($msg){
		throw new MagicBoxException($msg);
	}

	private function __getSyllable(){
		return $this->consonant() . $this->vowel() . ( $this->bool() ? $this->consonant() : '' );
	}
	
	private function __getCreditCards(){
		return json_decode(self::$creditCardList, true);
	}
	
	private function __getCreditCardNumber($prefix, $length){
		$number = $prefix;
		
		for( $i=0; $i<($length - strlen($prefix)); $i++ ) {
			$number .= $this->character(array('digit'=>true));
		}
		
		return $number;
	}

	// ------------------------------------------------------------- //
	// --------------------- Common utility functions -------------- //
	// ------------------------------------------------------------- //
	
	/**
	 * @todo Add unittests for Common utility function	 
	 */

	public function version(){
		return self::MAGIC_BOX_VERSION;
	}

	public function random( $min=0, $max=0 ) {
		return $this->__random($min, $max);
	}

	public function key() {
		return $this->key;
	}
	
	public function lowerLetters($asString=true){
		return $asString ? implode('', self::$lowerLetters) : self::$lowerLetters;
	}
	
	public function upperLetters($asString=true){
		return $asString ? implode('', self::$upperLetters) : self::$upperLetters;
	}
	
	public function symbols($asString=true){
		return $asString ? implode('', self::$symbols) : self::$symbols;
	}
	
	public function digits($asString=true){
		return $asString ? implode('', self::$digits) : self::$digits;
	}
	
	public function characterString(){
		return $this->lowerLetters() . $this->upperLetters() . $this->digits() . $this->symbols();
	}
	
	public function alpha(){
		return array_merge($this->lowerLetters(false), $this->upperLetters(false));
	}
	
	public function vowels($asString=true){
		return $asString ? implode('', self::$vowels) : self::$vowels;
	}
	
	public function vowel(){
		return $this->character( array('pool' => $this->vowels()) );
	}
	
	public function consonants($asString=true){
		return $asString ? implode('', self::$consonants) :self::$consonants;
	}
	
	public function consonant(){
		return $this->character( array('pool' => $this->consonants()) );
	}
	
	public function namePrefixes(){
		return self::$namePrefixes;
	}
	
	public function tldList(){
		return self::$tldList;
	}
	
	public function subDomainList(){
		return self::$subDomainList;
	}

	public function creditcards(){
		return json_decode(self::$creditCardList, true);
	}
	
	public function states(){
		return json_decode(self::$stateList, true);
	}
	
	public function streets(){
		return json_decode(self::$streetSuffixList, true);
	}
	
	/**
	 * @see http://www.blogger.bhargavvadher.com/2013/03/21/reflection-in-php/
	 */
	public function methods(){
		$class = new ReflectionClass(__CLASS__);
		$methods = $class->getMethods(ReflectionMethod::IS_PUBLIC);
		$returnMethods = array();

		foreach( $methods as $key => $method ) {
			array_push($returnMethods, $method->name);
		}

		return $returnMethods;
	}

	
	// ------------------------------------------------------------- //
	// --------------- Type utility functions ---------------------- //
	// ------------------------------------------------------------- //
	public function bool() {
		return $this->__random() < $this->__random();
	}

	public function integer($negative=false) {
		$positive = !$negative;
		
		if($positive){
			$integer = $this->__random(0, PHP_INT_MAX);
		} else if($negative){
			$integer = $this->__random(-(PHP_INT_MAX), 0);
		} else {
			$integer = $this->__random(-PHP_INT_MAX, PHP_INT_MAX);
		}
		
		return $integer;
	}

	public function natural($min=0, $max=PHP_INT_MAX) {
		$min = (!$min || $min <= 0) ? 0 : $min;
		$max = (!$max || $max <= 0) ? PHP_INT_MAX : $max;
		
		return $this->__random($min, $max);
	}

	public function float($fixed=false) {
		$fixed = $fixed ? $fixed : self::FLOAT_DECIMAL_POINTS;
		$float = number_format((mt_rand() / mt_getrandmax()), $fixed, '.', '');

		return floatval($float);
	}

	
	// ------------------------------------------------------------- //
	// --------------- Text utility functions ---------------------- //
	// ------------------------------------------------------------- //

	public function character( $options=[] ){
		// defaults
		$set = $case = '';

		// case
		if( !empty($options['case']) && in_array(strtolower($options['case']), array('lower', 'upper')) ){
			$case = strtolower($options['case']);
		}

		$digit = !empty($options['digit']) ? true : false;
		$alpha = !empty($options['alpha']) || !empty($options['case']) ? true : false;
		$symbol = !empty($options['symbol']) ? true : false;
		$pool = !empty($options['pool']) ? $options['pool'] : false;

		// determine set
		if($pool){
			$set = $pool;
		} else if($digit){
			$set = $this->digits();
		} else if($alpha) {
			if(empty($case)){
				$set = $this->lowerLetters() . $this->upperLetters();
			} else {
				$set = $case === 'lower' ? $this->lowerLetters() : $this->upperLetters();
			}
		} else if($symbol) {
			$set = $this->symbols();
		} else {
			$set = $this->characterString();
		}

		return $set[ $this->random(0, strlen($set)-1) ];
	}
	
	/**
	 * All options for $this->character() are valid for string
	 * @param type $options
	 * @return type
	 */
	public function string( $options=[] ) {
		$string = '';
		$length = !empty($options['length']) ? $options['length'] : $this->random(4,10);

		for( $i=0; $i<$length; $i++ ) {
			$string .= $this->character($options);
		}

		return $string;
	}
	
	public function word( $options=[] ){
		$word = '';
		$length = !empty($options['length']) ? $options['length'] : false;
		$syllables = !empty($options['syllables']) ? $options['syllables'] : 2;

		// both length and syllables can not be present together
		if( !empty($options['length']) && !empty($options['syllables']) ){
			$this->__exception("Both length and syllables can not be present together");
		}

		if($length){
			$syllables = floor($length / self::SYLLABLE_MAX_LENGTH);
		}

		for( $i=0; $i<$syllables; $i++ ) {
			$word .= $this->__getSyllable();
		}
		
		// max word length is self::WORD_MAX_LENGTH
		if($length > self::WORD_MAX_LENGTH){
			$this->__exception("Word length can not be more than " . self::WORD_MAX_LENGTH);
		}

		// strlen(word) >= strlen(syllables * self::SYLLABLE_MAX_LENGTH)
		return $length ? str_pad($word, $length, $this->__getSyllable(), STR_PAD_RIGHT) : $word;
	}

	public function sentence( $options=[] ){
		$sentence = '';
		$length = !empty($options['length']) 
			? $options['length'] 
			: $this->random(self::SENTENCE_MIN_WORD_LENGTH, self::SENTENCE_MAX_WORD_LENGTH - 1);

		for( $i=0; $i<$length; $i++ ) {
			$wordLength = $this->random(1,6);
			$syllableLength = $this->random(1,3);

			$wordOptions = $this->bool() ? array('length' => $wordLength) : array('syllables' => $syllableLength);
			$sentence .= $this->word($wordOptions) . ' ';
		}

		return ucfirst(trim($sentence)) . '.';
	}
	
	public function paragraph( $options=[] ){
		$paragraph = '';
		$sentences = !empty($options['sentences']) 
			? $options['sentences'] 
			: $this->random(1, self::PARAGRAPH_MAX_SENTENCE_LENGTH);

		for( $i=0; $i<$sentences; $i++ ) {
			$paragraph .= $this->sentence() . ' ';
		}

		return trim($paragraph);
	}
	
	
	// ------------------------------------------------------------- //
	// --------------- Person utility functions -------------------- //
	// ------------------------------------------------------------- //
	
	public function firstName($initial=false){
		$syllableLen = $this->random(1,3);

		$first = $initial
			? $this->character(array('case'=>'upper')) . '.'
			: $this->word( array('syllables'=>$syllableLen) );

		return ucfirst($first);
	}

	public function lastName(){
		$syllableLen = $this->random(1,4);;
		return ucfirst($this->word( array('syllables'=>$syllableLen) ));
	}

	public function middleName($initial=true){
		$middle = $initial 
			? $this->character(array('case'=>'upper')) . '.'
			: $this->firstName();

		return ucfirst($middle);
	}

	/**
	 * @param type $full
	 * @return type
	 * @see http://notes.ericwillis.com/2009/11/common-name-prefixes-titles-and-honorifics/
	 */
	public function prefixName($full=false){
		$prefixes = $this->namePrefixes();
		$shortPrefixes = array_keys($prefixes);
		$index = $this->random(0, count($prefixes)-1);

		return $full ? $prefixes[$shortPrefixes[$index]] : $shortPrefixes[$index];
	}

	public function fullName( $options=[] ){
		$prefixShort = !empty($options['prefix']) ? $this->prefixName(false) : '';
		$prefixFull = !empty($options['prefixFull']) ? $this->prefixName(true) : '';
		$prefix = empty($prefixShort) ? $prefixFull : $prefixShort;

		$first = !empty($options['firstInitial']) ? $this->firstName(true) : $this->firstName(false);
		$middle = empty($options['middleFull']) ? $this->middleName(true) : $this->middleName(false);
		$last = $this->lastName();

		return trim("{$prefix} {$first} {$middle} {$last}");
	}
	
	/**
	 * @todo add more symbols and patterns
	 * @param type $options
	 * @return type
	 */
	public function email($options=[]){
		$domainStrOptions = array('case'=>'lower', 'length'=>$this->random(2, 6));
		$userStrOptions = array('case'=>'lower', 'length'=>$this->random(2, 9));
		
		$domain = empty($options['domain']) 
			? $this->string($domainStrOptions) . '.' . $this->tld()
			: $options['domain'];
		$user = $this->string($userStrOptions);

		return "{$user}@{$domain}";
	}
	
	/**
	 * @param type $formatted
	 * @see http://www.bennetyee.org/ucsd-pages/area.html
	 */
	public function phone($formatted=true, $countryCode=false){
		$areaCode = $this->random(201, 999);
		$set1 = str_pad($this->random(0, 999), 3, '0', STR_PAD_LEFT);
		$set2 = str_pad($this->random(0, 9999), 4, '0', STR_PAD_LEFT);

		$countryCode = !$countryCode ? '' : "+1 ";

		return $formatted
			? "{$countryCode}({$areaCode}) {$set1}-{$set2}"
			: "{$countryCode}{$areaCode}{$set1}{$set2}";
	}


	// ------------------------------------------------------------- //
	// ------------------ Web utility functions -------------------- //
	// ------------------------------------------------------------- //

	/**
	 * @see http://netforbeginners.about.com/od/d/f/domain_name.htm
	 */
	public function domain($options=[]){
		$subdomain = empty($options['host']) ? $this->subdomain() : $options['host'];
		$site = $this->string(array('case'=>'lower', 'alpha'=>true));
		$tld = empty($options['tld']) ? $this->tld() : $options['tld'];
		$protocol = $this->bool() ? 'http' : 'https';

		return "{$protocol}://{$subdomain}.{$site}.{$tld}";
	}

	/**
	 * @todo add testcase
	 * @return type
	 */
	public function subdomain(){
		$subDomainList = $this->subDomainList();
		return $subDomainList[ $this->random(0, count($subDomainList)-1) ];
	}

	/**
	 * @todo add testcase
	 * @return type
	 */
	public function tld(){
		$tldList = $this->tldList();
		return $tldList[ $this->random(0, count($tldList)-1) ];
	}

	/**
	 * @todo both private and localhost can not be together - exception
	 * @param type $options
	 * @return string
	 */
	public function ip($options=[]){
		if(!empty($options['localhost'])){
			return '127.0.0.1';
		} else if(!empty($options['private'])) {
			if($this->bool()){
				$privateIpBlocks1 = array();
				for( $i=0; $i<3; $i++ ) {
					array_push($privateIpBlocks1, $this->random(0, 255));
				}
				return "10." . join('.', $privateIpBlocks1);
			} else {
				$privateIpBlocks2 = array();
				for( $i=0; $i<2; $i++ ) {
					array_push($privateIpBlocks2, $this->random(0, 255));
				}
				return "192.168." . join('.', $privateIpBlocks2);
			}
		} else {
			$ipBlocks = array();
			for( $i=0; $i<4; $i++ ) {
				array_push($ipBlocks, $this->random(10, 255));
			}
			return join('.', $ipBlocks);
		}
	}

	public function url($options=[]){
		$domainOptions = array(
			'host' => 'www',
			'tld' => 'com'
		);

		$path = $qs = '';

		if( !empty($options['path']) ){
			for( $i=0; $i<$this->random(2,4); $i++ ) {
				$path .= '/' . $this->string(array('case'=>'lower', 'length'=>4));
			}
		}

		if( !empty($options['qs']) ){
			$queryData = [];
			$strOptions = array('case'=>'lower', 'length'=>4);
			for( $i=0; $i<$this->random(2,4); $i++ ) {
				$queryData[$this->string($strOptions)] = $this->string($strOptions);
			}
			$qs = '/?' . http_build_query($queryData);
		}

		return $this->domain($domainOptions) . $path . $qs;
	}
	
	
	// ------------------------------------------------------------- //
	// --------------- CreditCard utility functions ---------------- //
	// ------------------------------------------------------------- //
	
	/**
	 * 
	 * @param type $options
	 * @return type
	 * @see https://gist.github.com/troelskn/1287893
	 */
	public function creditcard($options=[]){
		$cards = $this->__getCreditCards();
		$card = [];
		$full = empty($options['full']) ? false : true;
		$name = !empty($options['name']) ? true : false;
		$type = empty($options['type']) ? false : trim(strtolower($options['type']));
		$expDate = empty($options['expiryDate']) ? false : true;

		if($type){
			if( !empty($cards[$type]) ) {
				$card = $cards[$type];
			} else {
				$this->__exception("card type '{$type}' is not valid.");
			}
			$card = $cards[$type];
		} else {
			// GET random card
			$cardKeys = array_keys($cards);
			$card = $cards[ $cardKeys[$this->random(0, count($cardKeys)-1)] ];
		}

		$year = date('Y');
		$card['exp'] = str_pad($this->random(1, 12), 2, '0', STR_PAD_LEFT) . '/' . $this->random($year, $year+5);

		if($full){
			$card['number'] = $this->__getCreditCardNumber($card['prefix'], $card['length']);
			return $card;
		} else if($name) {
			return $card['name'];
		} else if($expDate) {
			return $card['exp'];
		} else {
			return $this->__getCreditCardNumber($card['prefix'], $card['length']);
		}
	}


	// ------------------------------------------------------------- //
	// --------------- Geographic utility functions ---------------- //
	// ------------------------------------------------------------- //

	public function city(){
		return ucfirst( $this->word(array('syllables'=>3)) );
	}

	public function state($full=false){
		$states = $this->states();

		// GET random state
		$stateKeys = array_keys($states);
		$key = $stateKeys[$this->random(0, count($stateKeys)-1)];
		$state = $full ? $states[$key] : $key;

		return $state;
	}

	public function zip($full=false){
		$zip = str_pad($this->random(1000, 99999), 5, '0', STR_PAD_LEFT);
		$subZip = str_pad($this->random(100, 9999), 4, '0', STR_PAD_LEFT);
		
		return $full ? "{$zip}-{$subZip}" : $zip;
	}

	public function address($fullStreet=false){
		$streets = $this->streets();

		// GET random street suffix
		$streetKeys = array_keys($streets);
		$key = $streetKeys[$this->random(0, count($streetKeys)-1)];
		$street = $fullStreet ? $streets[$key] : $key;

		$buidingNo = $this->random(10, 9999);
		$random = ucfirst( $this->word(array('syllables'=>3)) );

		return "{$buidingNo} {$random} {$street}";
	}
	
	public function latitude($fixed = self::FLOAT_DECIMAL_POINTS){
		$integer = $this->random(-90, 89);
		$decimal = $this->float($fixed);

		return floatval($integer+$decimal);
	}

	public function longitude($fixed = self::FLOAT_DECIMAL_POINTS){
		$integer = $this->random(0, 179);
		$decimal = $this->float($fixed);

		return floatval($integer+$decimal);
	}


	// ------------------------------------------------------------- //
	// -------------- Miscellaneous utility functions -------------- //
	// ------------------------------------------------------------- //

	public function guid(){
		$pool = "ABCDEF" . $this->digits();

		return
			$this->string(array('pool'=>$pool, 'length'=>8)) . '-' .
			$this->string(array('pool'=>$pool, 'length'=>4)) . '-' .
			$this->string(array('pool'=>$pool, 'length'=>4)) . '-' .
			$this->string(array('pool'=>$pool, 'length'=>4)) . '-' .
			$this->string(array('pool'=>$pool, 'length'=>12));
	}
}

class MagicBoxException extends Exception{}
