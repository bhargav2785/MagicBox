<?php
require_once 'MagicBox.php';

/**
 * A test suite for MagicBox.php
 * 
 * @author Bhargav Vadher <bhargav@bhargavvadher.com>
 * @version 1.0 [2013.07.25 11:20:42PM]
 * @copyright (c) 2013, Bhargav Vadher
 * 
 * @todo Add exception tests 
 * 
 */
class MagicBoxTest extends PHPUnit_Framework_TestCase
{
  private $magicBox = null;
	
	protected function setUp(){
		$this->magicBox = new MagicBox();
		date_default_timezone_set('America/Los_Angeles');
	}

	protected function tearDown(){
		$this->magicBox = null;
	}

	public function testConstructor(){
		$magicBox2 = new MagicBox(20);
		$magicBox3 = new MagicBox(20);
		$magicBox4 = new MagicBox(30);

		$this->assertTrue( $this->magicBox->key() !== $magicBox2->key() );
		$this->assertTrue( $magicBox2->key() === $magicBox3->key() );
		$this->assertTrue( $magicBox3->key() !== $magicBox4->key() );
	}

	public function testBool(){
		$this->assertTrue( is_bool($this->magicBox->bool()) );
		$this->assertFalse( is_null($this->magicBox->bool()) );
	}

	public function testInteger(){
		$int1 = $this->magicBox->integer(true);
		$int2 = $this->magicBox->integer(false);
		$int3 = $this->magicBox->integer();
		
		$this->assertTrue( is_int($int1) );
		$this->assertTrue( is_int($int2) && $int2 > 0 );
		$this->assertTrue( is_int($int3) && $int3 > 0 );
	}

	public function testNatural(){
		$this->assertTrue( $this->magicBox->natural() > 0);
		$this->assertTrue( $this->magicBox->natural(10, 334) >= 10 );
		$this->assertTrue( $this->magicBox->natural(9, -73) >= 9 );
		$this->assertTrue( $this->magicBox->natural(73, 100) >= 73 );
		$this->assertTrue( $this->magicBox->natural(-10) > 0 );
		$this->assertTrue( $this->magicBox->natural(334) > 0 );
	}

	public function testFloat(){
		$float1 = $this->magicBox->float();
		$this->assertTrue(is_float($float1) && $float1 < 1 && $float1 > 0 );

		$float2 = $this->magicBox->float(0);
		$this->assertTrue(is_float($float2) && $float2 > 0 );
		
		$float3 = $this->magicBox->float(4);
		$this->assertTrue(is_float($float3) && $float3 > 0 );
	}

	public function testCharacter(){
		// default
		$char0 = $this->magicBox->character();
		$this->assertTrue(strlen($char0) === 1);
		$this->assertGreaterThanOrEqual( 0, strpos($this->magicBox->characterString(), $char0) );

		// lower case
		$opt1 = array('case'=>'lower');
		$char1 = $this->magicBox->character($opt1);
		$this->assertTrue(ctype_lower($char1));
		$this->assertContains($char1, $this->magicBox->lowerLetters(false));

		// upper case
		$opt2 = array('case'=>'upper');
		$char2 = $this->magicBox->character($opt2);
		$this->assertTrue(ctype_upper($char2));
		$this->assertContains($char2, $this->magicBox->upperLetters(false));

		// alpha
		$opt3 = array('alpha'=>true);
		$char3 = $this->magicBox->character($opt3);
		$this->assertTrue(ctype_alpha($char3));
		// change it to alpha() function
		$alphaArr = array_merge($this->magicBox->lowerLetters(false), $this->magicBox->upperLetters(false));
		$this->assertContains($char3, $alphaArr);

		// digit
		$opt4 = array('digit'=>true);
		$char4 = $this->magicBox->character($opt4);
		$this->assertTrue(ctype_digit($char4));
		$this->assertContains($char4, $this->magicBox->digits(false));

		// symbol
		$opt5 = array('symbol'=>true);
		$char5 = $this->magicBox->character($opt5);
		$this->assertTrue(in_array($char5, $this->magicBox->symbols(false)));
		
		// pool
		$pool = 'abcde';
		$opt6 = array('pool'=>$pool);
		$char6 = $this->magicBox->character($opt6);
		$this->assertTrue( false !== strpos($pool, $char6) );
	}
	/**
	 * @group string
	 */
	public function testString(){
		// default
		$str0 = $this->magicBox->string();
		$this->assertTrue(strlen($str0) > 1);
		$this->assertTrue( $this->__isValidStringWithinPool($str0, str_split($this->magicBox->characterString())) );

		// lower case
		$opt1 = array('case'=>'lower');
		$str1 = $this->magicBox->string($opt1);
		$this->assertTrue(ctype_lower($str1));
		$this->assertTrue( $this->__isValidStringWithinPool($str1, $this->magicBox->lowerLetters(false)) );

		// upper case
		$opt2 = array('case'=>'upper');
		$str2 = $this->magicBox->string($opt2);
		$this->assertTrue(ctype_upper($str2));
		$this->assertTrue( $this->__isValidStringWithinPool($str2, $this->magicBox->upperLetters(false)) );

		// alpha
		$opt3 = array('alpha'=>true);
		$str3 = $this->magicBox->string($opt3);
		$this->assertTrue(ctype_alpha($str3));
		$this->assertTrue( $this->__isValidStringWithinPool($str3, $this->magicBox->alpha(false)) );

		// digit
		$opt4 = array('digit'=>true);
		$str4 = $this->magicBox->string($opt4);
		$this->assertTrue(ctype_digit($str4));
		$this->assertTrue( $this->__isValidStringWithinPool($str4, $this->magicBox->digits(false)) );

		// symbol
		$opt5 = array('symbol'=>true);
		$str5 = $this->magicBox->string($opt5);
		$this->assertTrue( $this->__isValidStringWithinPool($str5, $this->magicBox->symbols(false)) );

		// pool
		$pool = 'abcde';
		$opt6 = array('pool'=>$pool);
		$str6 = $this->magicBox->string($opt6);
		$this->assertTrue( $this->__isValidStringWithinPool($str6, str_split($pool)) );
		
		// upper + alpha
		$opt7 = ['case'=>'upper', 'alpha'=>true];
		$str7 = $this->magicBox->string($opt7);
		$this->assertTrue( ctype_upper($str7) );
		$this->assertTrue( $this->__isValidStringWithinPool($str7, $this->magicBox->alpha(false)) );
		
		// lower + 9
		$opt8 = ['case'=>'lower', 'length'=>9];
		$str8 = $this->magicBox->string($opt8);
		$this->assertTrue(ctype_lower($str8) );
		$this->assertTrue( strlen($str8) === 9 );
		$this->assertTrue( $this->__isValidStringWithinPool($str8, $this->magicBox->lowerLetters(false)) );

		// upper + 12
		$opt9 = ['case'=>'upper', 'length'=>12];
		$str9 = $this->magicBox->string($opt9);
		$this->assertTrue(ctype_upper($str9) );
		$this->assertTrue( strlen($str9) === 12 );
		$this->assertTrue( $this->__isValidStringWithinPool($str9, $this->magicBox->upperLetters(false)) );

		// alpha + 6
		$opt10 = ['alpha'=>true, 'length'=>6];
		$str10 = $this->magicBox->string($opt10);
		$this->assertTrue(ctype_alpha($str10) );
		$this->assertTrue( strlen($str10) === 6 );
		$this->assertTrue( $this->__isValidStringWithinPool($str10, $this->magicBox->alpha(false)) );

		// digit + 5
		$opt11 = ['digit'=>true, 'length'=>5];
		$str11 = $this->magicBox->string($opt11);
		$this->assertTrue(ctype_digit($str11) );
		$this->assertTrue( strlen($str11) === 5 );
		$this->assertTrue( $this->__isValidStringWithinPool($str11, $this->magicBox->digits(false)) );

		// symbol + 5
		$opt12 = ['symbol'=>true, 'length'=>5];
		$str12 = $this->magicBox->string($opt12);
		$this->assertTrue( strlen($str12) === 5 );
		$this->assertTrue( $this->__isValidStringWithinPool($str12, $this->magicBox->symbols(false)) );
		
		// pool + 8
		$pool13 = 'wxyz';
		$opt13 = ['pool'=>$pool13, 'length'=>8];
		$str13 = $this->magicBox->string($opt13);
		$this->assertTrue( strlen($str13) === 8 );
		$this->assertTrue( $this->__isValidStringWithinPool($str13, str_split($pool13)) );
	}
	
	/**
	 * @todo add more cases
	 */
	public function testWord(){
		$len1 = 8;
		$this->assertEquals( strlen($this->magicBox->word(array('length'=>$len1))), $len1);
		
		$sylLen = 3;
		$this->assertLessThanOrEqual( 
			(MagicBox::SYLLABLE_MAX_LENGTH * $sylLen), 
			$this->magicBox->word(array('syllables'=>$sylLen))
		);
	}

	public function testSentence(){
		// default
		$sen1 = $this->magicBox->sentence();
		$words1 = str_word_count($sen1);
		$this->assertTrue( $words1 >= MagicBox::SENTENCE_MIN_WORD_LENGTH && $words1 <= MagicBox::SENTENCE_MAX_WORD_LENGTH);
		
		// length
		$len2 = 6;
		$sen2 = $this->magicBox->sentence( array('length'=>$len2) );
		$words2 = str_word_count($sen2);
		$this->assertEquals($len2, $words2);
	}
	
	public function testParagraph(){
		// default
		$para1 = $this->magicBox->paragraph();
		$senCount1 = ( count(explode('.', $para1)) - 1 );
		$this->assertTrue( $senCount1 >= MagicBox::PARAGRAPH_MIN_SENTENCE_LENGTH && $senCount1 <= MagicBox::PARAGRAPH_MAX_SENTENCE_LENGTH);

		// length
		$senLen2 = 4;
		$para2 = $this->magicBox->paragraph( array('sentences'=>$senLen2) );
		$senCount2 = ( count(explode('.', trim($para2))) - 1 );
		$this->assertEquals($senLen2, $senCount2);
	}

	public function testPrefixName(){
		$prefixNames = $this->magicBox->namePrefixes();
		$prefixNamesFull = array_values($prefixNames);
		$prefixNamesShort = array_keys($prefixNames);

		// default
		$pname1 = $this->magicBox->prefixName();
		$this->assertTrue( strlen($pname1) > 1 );
		$this->assertTrue(in_array($pname1, $prefixNamesShort) );

		// $full=false
		$pname2 = $this->magicBox->prefixName($full=false);
		$this->assertTrue( strlen($pname2) > 1 );
		$this->assertTrue(in_array($pname1, $prefixNamesShort) );

		// $full=true
		$pname3 = $this->magicBox->prefixName($full=true);
		$this->assertTrue( strlen($pname3) > 1 );
		$this->assertTrue( in_array($pname3, $prefixNamesFull) );
	}
	
	public function testFirstName(){
		$fname1 = $this->magicBox->firstName();
		$this->assertTrue(ctype_alpha($fname1) && strlen($fname1) > 1);

		$fname2 = $this->magicBox->firstName($initial=true);
		$this->assertTrue(strlen($fname2) === 2);
	}

	public function testMiddleName(){
		$mname1 = $this->magicBox->middleName();
		$this->assertTrue(strlen($mname1) === 2);

		$mname2 = $this->magicBox->middleName($initial=false);
		$this->assertTrue( ctype_alpha($mname2) && strlen($mname2) > 1 );
	}

	public function testLastName(){
		$lname1 = $this->magicBox->lastName();
		$this->assertTrue(ctype_alpha($lname1) && strlen($lname1) > 1);
	}

	public function testFullName(){
		// default [no prefix, full firstname, initial middlename, full lastname]
		$fullName1 = $this->magicBox->fullName();
		$this->assertEquals(3, count(explode(' ', $fullName1)));

		// prefix
		$opt2 = array('prefix'=>true);
		$fullName2 = $this->magicBox->fullName($opt2);
		$fullName2Parts = explode(' ', $fullName2);
		$this->assertEquals(4, count($fullName2Parts));
		$this->assertTrue( strlen($fullName2Parts[0]) >= 3 );	// prefix
		$this->assertTrue( strlen($fullName2Parts[1]) >= 2 );	// first name
		$this->assertTrue( strlen($fullName2Parts[2]) === 2 );	// middle name
		$this->assertTrue( strlen($fullName2Parts[3]) >= 2 );	// last name

		// prefixFull
		$opt22 = array('prefixFull'=>true);
		$fullName22 = $this->magicBox->fullName($opt22);
		$fullName22Parts = explode(' ', $fullName22);
		$this->assertEquals(4, count($fullName22Parts));
		$this->assertTrue( strlen($fullName22Parts[0]) >= 3 && strpos($fullName22Parts[0], '.') === false );	// full prefix
		$this->assertTrue( strlen($fullName22Parts[1]) >= 2 );	// first name
		$this->assertTrue( strlen($fullName22Parts[2]) === 2 );	// middle name
		$this->assertTrue( strlen($fullName22Parts[3]) >= 2 );	// last name

		// firstInitial only
		$opt3 = array('firstInitial'=>true);
		$fullName3 = $this->magicBox->fullName($opt3);
		$fullName3Parts = explode(' ', $fullName3);
		$this->assertEquals(3, count($fullName3Parts));
		$this->assertTrue( strlen($fullName3Parts[0]) === 2 );	// first intial name
		$this->assertTrue( strlen($fullName3Parts[1]) === 2 );	// middle initial name
		$this->assertTrue( strlen($fullName3Parts[2]) >= 2 );	// last name

		// middleFull only
		$opt4 = array('middleFull'=>true);
		$fullName4 = $this->magicBox->fullName($opt4);
		$fullName4Parts = explode(' ', $fullName4);
		$this->assertEquals(3, count($fullName4Parts));
		$this->assertTrue( strlen($fullName4Parts[0]) >= 2 );	// first full name
		$this->assertTrue( strlen($fullName4Parts[1]) >= 2 );	// middle full name
		$this->assertTrue( strlen($fullName4Parts[2]) >= 2 );	// last name

		// multiple options
		$opt5 = array('prefix'=>true, 'firstInitial'=>true);
		$fullName5 = $this->magicBox->fullName($opt5);
		$fullName5Parts = explode(' ', $fullName5);
		$this->assertEquals(4, count($fullName5Parts));
		$this->assertTrue( strlen($fullName5Parts[0]) >= 2 );	// prefix
		$this->assertTrue( strlen($fullName5Parts[1]) === 2 );	// first intial name
		$this->assertTrue( strlen($fullName5Parts[2]) === 2 );	// middle initial name
		$this->assertTrue( strlen($fullName5Parts[3]) >= 2 );	// last full name
	}

	/**
	 * @todo add more assertion with options
	 */
	public function testDomain(){
		$domain = $this->magicBox->domain();
		$this->assertTrue( false !== filter_var($domain, FILTER_VALIDATE_URL) );
	}

	public function testIp(){
		// default
		$ip1 = $this->magicBox->ip();
		$this->assertTrue( false !== filter_var($ip1, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) );

		// localhost
		$ip2 = $this->magicBox->ip(array('localhost'=>true));
		$this->assertTrue( false !== filter_var($ip2, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_IPV4) );
		$this->assertTrue(strpos($ip2, '127') === 0 );

		// private
		$ip3 = $this->magicBox->ip(array('private'=>true));
		$this->assertTrue( false !== filter_var($ip3, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) );
		$this->assertTrue( strpos($ip3, '10') === 0 || strpos($ip3, '192') === 0 );
	}

	public function testUrl(){
		// default
		$url1 = $this->magicBox->url();
		$this->assertTrue( false !== filter_var($url1, FILTER_VALIDATE_URL) );

		// path
		$urlOpt2 = array('path'=>true);
		$url2 = $this->magicBox->url($urlOpt2);
		$this->assertTrue( false !== filter_var($url2, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) );

		// qs
		$urlOpt3 = array('qs'=>true);
		$url3 = $this->magicBox->url($urlOpt3);
		$this->assertTrue( false !== filter_var($url3, FILTER_VALIDATE_URL, FILTER_FLAG_QUERY_REQUIRED) );

		// mixed
		$urlOpt4 = array('path'=>true, 'qs'=>true);
		$url4 = $this->magicBox->url($urlOpt4);
		$this->assertTrue( false !== filter_var($url4, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED | FILTER_FLAG_QUERY_REQUIRED) );
	}
	
	public function testEmail(){
		// default
		$email1 = $this->magicBox->email();
		$this->assertTrue( false !== filter_var($email1, FILTER_VALIDATE_EMAIL) );
		
		// domain
		$domain = 'chegg.com';
		$email2 = $this->magicBox->email(array('domain'=>$domain));
		$this->assertTrue( false !== filter_var($email2, FILTER_VALIDATE_EMAIL) );
		$this->assertTrue( false !== strpos($email2, $domain) );
	}

	public function testCreditCard(){
		// default
		$card1 = $this->magicBox->creditcard();
		$this->assertTrue( ctype_digit($card1) && strlen($card1) > 10 );

		// type
		$card2Opt = array('type'=>'mc');
		$card2 = $this->magicBox->creditcard($card2Opt);
		$this->assertTrue( ctype_digit($card2) && strlen($card2) === 16 );
		$this->assertTrue( 0 === strpos($card2, '51') );

		// full
		$card3Opt = array('full'=>true);
		$card3 = $this->magicBox->creditcard($card3Opt);
		$this->assertTrue(is_array($card3));
		$this->assertArrayHasKey('number', $card3);
		$number3 = $card3['number'];
		$this->assertArrayHasKey('name', $card3);
		$this->assertArrayHasKey('length', $card3);
		$this->assertArrayHasKey('exp', $card3);
		$this->assertTrue( ctype_digit($number3) && strlen($number3) > 10 );

		// name
		$card4Opt = array('name'=>true);
		$card4 = $this->magicBox->creditcard($card4Opt);
		$this->assertTrue(is_string($card4) );
		
		// expiryDate
		$card5Opt = array('expiryDate'=>true);
		$card5 = $this->magicBox->creditcard($card5Opt);
		$parts = explode('/', $card5);
		$this->assertTrue(ctype_digit($parts[0]) && $parts[0] >= 1 && $parts[0] <= 12 );
		$this->assertTrue(ctype_digit($parts[1]) && strlen($parts[1]) === 4);
	}

	/**
	 * @expectedException MagicBoxException
	 */
	public function testCreditCardException(){
		// invalid card type
		$cardOpt = array('type'=>'dummy');
		$card = $this->magicBox->creditcard($cardOpt);
		$this->assertTrue( ctype_digit($card) && strlen($card) === 16 );
	}
	
	public function testCity(){
		$city = $this->magicBox->city();
		$this->assertTrue( is_string($city) && strlen($city) >= 6 );
	}
	
	public function testState(){
		$states = $this->magicBox->states();
		
		// default - short
		$state1 = $this->magicBox->state();
		$this->assertTrue( strlen($state1) == 2 );
		$this->assertArrayHasKey(trim($state1), $states);

		// full
		$state2 = $this->magicBox->state(true);
		$this->assertTrue( !empty($state2) && is_string($state2) );
		$this->assertTrue( in_array($state2, $states) );
	}
	
	public function testZip(){
		$zip1 = $this->magicBox->zip();
		$this->assertTrue( ctype_digit($zip1) && strlen($zip1) == 5 );
		$this->assertTrue( $zip1 >= 1000 && $zip1 <= 99999 );

		$zip2 = $this->magicBox->zip(true);
		$parts = explode('-', $zip2);
		$this->assertTrue( ctype_digit($parts[0]) && strlen($parts[0]) == 5 );
		$this->assertTrue( $parts[0] >= 1000 && $parts[0] <= 99999 );
		$this->assertTrue( ctype_digit($parts[1]) && strlen($parts[1]) == 4 );
		$this->assertTrue( $parts[1] >= 100 && $parts[1] <= 9999 );
	}

	public function testPhone(){
		// default
		$phone1 = $this->magicBox->phone();
		$this->assertTrue( false !== strpos($phone1, '-'));
		$this->assertTrue( false !== strpos($phone1, '('));
		$this->assertTrue( false !== strpos($phone1, ')'));
		$this->assertTrue( false === strpos($phone1, '+'));

		// non-formatted phone
		$phone2 = $this->magicBox->phone(false);
		$this->assertTrue( false === strpos($phone2, '-'));
		$this->assertTrue( false === strpos($phone2, '('));
		$this->assertTrue( false === strpos($phone2, ')'));
		$this->assertTrue( false === strpos($phone2, '+'));

		// non-formatted phone with country code
		$phone3 = $this->magicBox->phone(false, true);
		$this->assertTrue( false === strpos($phone3, '-'));
		$this->assertTrue( false === strpos($phone3, '('));
		$this->assertTrue( false === strpos($phone3, ')'));
		$this->assertTrue( false !== strpos($phone3, '+'));

		// formatted phone with country code
		$phone4 = $this->magicBox->phone(true, true);
		$this->assertTrue( false !== strpos($phone4, '-'));
		$this->assertTrue( false !== strpos($phone4, '('));
		$this->assertTrue( false !== strpos($phone4, ')'));
		$this->assertTrue( false !== strpos($phone4, '+'));
	}
	
	public function testAddress(){
		// default
		$add1 = $this->magicBox->address();
		$add1Parts = explode(' ', $add1);
		$this->assertTrue( ctype_digit($add1Parts[0]) );
		$this->assertTrue( is_string($add1Parts[1]) );
		$this->assertTrue( is_string($add1Parts[2]) );

		// fullStreet
		$add2 = $this->magicBox->address(true);
		$add2Parts = explode(' ', $add2);
		$this->assertTrue( ctype_digit($add2Parts[0]) );
		$this->assertTrue( is_string($add2Parts[1]) );
		$this->assertTrue( is_string($add2Parts[2]) );
	}

	
	public function testLatitude(){
		// default
		$lat1 = $this->magicBox->latitude();
		$this->assertTrue( is_float($lat1) );
		$this->assertTrue( $lat1 > -90 && $lat1 <= 90 );

		// fixed
		$lat2 = $this->magicBox->latitude(3);
		$this->assertTrue( is_float($lat2) );
		$this->assertTrue( $lat2 > -90 && $lat2 <= 90 );
	}

	public function testLongitude(){
		// default
		$lon1 = $this->magicBox->longitude();
		$this->assertTrue( is_float($lon1) );
		$this->assertTrue( $lon1 > 0 && $lon1 <= 180 );

		// fixed
		$lon2 = $this->magicBox->longitude(3);
		$this->assertTrue( is_float($lon2) );
		$this->assertTrue( $lon2 > 0 && $lon2 <= 180 );
	}

	public function testGuid(){
		$guid = $this->magicBox->guid();
		$parts = explode('-', $guid);
		
		$this->assertTrue( count($parts) == 5 );
		$this->assertTrue( ctype_alnum($parts[0]) && strlen($parts[0]) == 8 );
		$this->assertTrue( ctype_alnum($parts[1]) && strlen($parts[1]) == 4 );
		$this->assertTrue( ctype_alnum($parts[2]) && strlen($parts[2]) == 4 );
		$this->assertTrue( ctype_alnum($parts[3]) && strlen($parts[3]) == 4 );
		$this->assertTrue( ctype_alnum($parts[4]) && strlen($parts[4]) == 12 );
	}
	
	public function testKey(){
		$this->assertTrue( ctype_digit($this->magicBox->key()) );
		
		$key2 = 5;
		$instance2 = new MagicBox($key2);
		$this->assertTrue( $instance2->key() === $key2 );
	}
	
	public function testLowerLetters(){
		$lower1 = $this->magicBox->lowerLetters();
		$this->assertTrue( ctype_lower($lower1) );
		
		$lower2 = $this->magicBox->lowerLetters(false);
		$this->assertTrue( !ctype_lower($lower2) && is_array($lower2) );
	}
	
	public function testUppderLetters(){
		$upper1 = $this->magicBox->upperLetters();
		$this->assertTrue( ctype_upper($upper1) );
		
		$upper2 = $this->magicBox->upperLetters(false);
		$this->assertTrue( !ctype_upper($upper2) && is_array($upper2) );
	}
	
	public function testSymbols(){
		$syn1 = $this->magicBox->symbols();
		$this->assertTrue( !is_array($syn1) );
		
		$syn2 = $this->magicBox->symbols(false);
		$this->assertTrue( is_array($syn2) );
	}
	
	public function testDigits(){
		$dig1 = $this->magicBox->digits();
		$this->assertTrue( ctype_digit($dig1) && !is_array($dig1) );
		
		$dig2 = $this->magicBox->digits(false);
		$this->assertTrue( !ctype_digit($dig2) && is_array($dig2) );
	}
	
	public function testCharacters(){
		$cs1 = $this->magicBox->characterString();
		$this->assertTrue( !is_array($cs1) && !ctype_alpha($cs1) && !ctype_digit($cs1) && !ctype_alnum($cs1) );
	}
	
	public function testAlpha(){
		$alpha1 = $this->magicBox->alpha();
		$this->assertTrue( is_array($alpha1) );
		$this->assertTrue( !ctype_digit($alpha1) );
		$this->assertTrue( !ctype_alnum($alpha1) );
	}

	public function testVowels(){
		$vw1 = $this->magicBox->vowels();
		$this->assertTrue( ctype_alpha($vw1) && !is_array($vw1) );
		
		$vw2 = $this->magicBox->vowels(false);
		$this->assertTrue( is_array($vw2) );
	}
	
	public function testConsonants(){
		$cn1 = $this->magicBox->consonants();
		$this->assertTrue( ctype_alpha($cn1) && !is_array($cn1) );
		
		$cn2 = $this->magicBox->consonants(false);
		$this->assertTrue( is_array($cn2) );
	}
	
	public function testNamePrefixes(){
		$cn1 = $this->magicBox->namePrefixes();
		$this->assertTrue( is_array($cn1) );
	}
	
	public function testTldList(){
		$tld1 = $this->magicBox->tldList();
		$this->assertTrue( is_array($tld1) );
	}
	
	public function testCreditcards(){
		$cc1 = $this->magicBox->creditcards();
		$this->assertTrue( is_array($cc1) );
	}
	
	public function testStates(){
		$st1 = $this->magicBox->states();
		$this->assertTrue( is_array($st1) );
	}
	
	public function testStreetSuffixes(){
		$sts1 = $this->magicBox->streets();
		$this->assertTrue( is_array($sts1) );
	}
	
	public function testMethods(){
		$mt1 = $this->magicBox->methods();
		$this->assertTrue( is_array($mt1) && count($mt1) );
	}
	
	//  -------------------------------------------------------------- //
	// -------------------- Test Helper functions -------------------- //
	//  -------------------------------------------------------------- // 
	private function __isValidStringWithinPool($string, $charArr){
		if(empty($charArr) || !is_array($charArr) || empty($string)){
			return false;
		}

		foreach( str_split($string) as $character ) {
			if(!in_array($character, $charArr)){
				return false;
			}
		}

		return true;	// valid
	}
}
