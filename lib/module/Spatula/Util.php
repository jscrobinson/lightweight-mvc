<?php

class Spatula_Util {

  public static function isSerialized($data) {
	// if it isn't a string, it isn't serialized
	if (!is_string($data))
	  return false;
	$data = trim($data);
	if ('N;' == $data)
	  return true;
	$length = strlen($data);
	if ($length < 4)
	  return false;
	if (':' !== $data[1])
	  return false;
	$lastc = $data[$length - 1];
	if (';' !== $lastc && '}' !== $lastc)
	  return false;
	$token = $data[0];
	switch ($token) {
	  case 's' :
		if ('"' !== $data[$length - 2])
		  return false;
	  case 'a' :
	  case 'O' :
		return (bool) preg_match("/^{$token}:[0-9]+:/s", $data);
	  case 'b' :
	  case 'i' :
	  case 'd' :
		return (bool) preg_match("/^{$token}:[0-9.E-]+;\$/", $data);
	}
	return false;
  }

  public static function camelize($string, $pascalCase = false) {
	$string = str_replace(array('-', '_'), ' ', $string);
	$string = ucwords($string);
	$string = str_replace(' ', '', $string);

	if (!$pascalCase) {
	  return lcfirst($string);
	}
	return $string;
  }

  public static function underscore($str) {
	return str_replace(' ', '_', $str);
  }

  public static function underscoreize($camel_cased_word) {
	$tmp = $camel_cased_word;
	$tmp = str_replace('::', '/', $tmp);
	$tmp = self::pregtr($tmp, array('/([A-Z]+)([A-Z][a-z])/' => '\\1_\\2',
				'/([a-z\d])([A-Z])/' => '\\1_\\2'));

	return strtolower($tmp);
  }
  
  public static function humanize($str)
  {
	return ucfirst(str_replace('_', ' ', $str));
  }

  public static function pregtr($search, $replacePairs) {
	return preg_replace(array_keys($replacePairs), array_values($replacePairs), $search);
  }

  public static function toStrictBool($val = false) {
	$trueValues = array(
		'Y',
		'y',
		'Yes',
		'yes',
		1,
		'1',
		'true',
		true
	);
	return in_array($val, $trueValues);
  }

  public static function uuid() {
	return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
					// 32 bits for "time_low"
					mt_rand(0, 0xffff), mt_rand(0, 0xffff),
					// 16 bits for "time_mid"
					mt_rand(0, 0xffff),
					// 16 bits for "time_hi_and_version",
					// four most significant bits holds version number 4
					mt_rand(0, 0x0fff) | 0x4000,
					// 16 bits, 8 bits for "clk_seq_hi_res",
					// 8 bits for "clk_seq_low",
					// two most significant bits holds zero and one for variant DCE1.1
					mt_rand(0, 0x3fff) | 0x8000,
					// 48 bits for "node"
					mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
	);
  }

  public static function stringToSeconds($string, $float = false) {
	$matches = array();
	$patt = '/([0-9]*)?(.*)([a-z]*)$/';
	preg_match($patt, $string, $matches);
	$int = (int) $matches[1];
	$string = trim($matches[2]);
	switch ($string) {
	  case 'm':
	  case 'minute':
	  case 'minutes':
		$seconds = 60;
		break;

	  case 'h':
	  case 'hour':
	  case 'hourly':
	  case 'hours':
		$seconds = 3600;
		break;

	  case 'd':
	  case 'day':
	  case 'days':
	  case 'daily':
		$seconds = 86400;
		break;

	  case 'm':
	  case 'month':
	  case 'months':
	  case 'monthly':
		$seconds = 2629743.83;
		break;

	  case 'y':
	  case 'year':
	  case 'years':
	  case 'yearly':
		$seconds = 31556926;
		break;

	  default:
		$seconds = null;
		break;
	}

	if (!$float) {
	  $seconds = ceil($seconds);
	}

	if ($int != 0) {
	  $seconds = $int * $seconds;
	}

	return $seconds;
  }

  public static function timeDiffConvert($start, $s) {
	$t = array(//suffixes
		'd' => 86400,
		'h' => 3600,
		'm' => 60,
	);

	$s = abs($s - $start);
	$string = '';

	foreach ($t as $key => &$val) {
	  $$key = floor($s / $val);
	  $s -= ($$key * $val);
	  $string .= ($$key == 0) ? '' : $$key . "$key ";
	}
	return $string . $s . 's';
  }

  public static function secondsToWords($seconds) {
	/*	 * * number of days ** */
	$days = (int) ($seconds / 86400);
	/*	 * * if more than one day ** */
	$dayplural = $days > 1 ? 'days' : 'day';
	/*	 * * number of hours ** */
	$hours = (int) (($seconds - ($days * 86400)) / 3600);
	$hourplural = $hours > 1 ? 'hours' : 'hour';
	/*	 * * number of mins ** */
	$mins = (int) (($seconds - $days * 86400 - $hours * 3600) / 60);
	$minplural = $mins > 1 ? 'minutes' : 'minute';
	/*	 * * number of seconds ** */
	$secs = (int) ($seconds - ($days * 86400) - ($hours * 3600) - ($mins * 60));
	$secsplural = $secs > 1 ? 'seconds' : 'second';
	/*	 * * return the string ** */

	$days = $days != 0 ? sprintf('%d %s ', $days, $dayplural) : '';
	$hours = $hours != 0 ? sprintf('%d %s ', $hours, $hourplural) : '';
	$mins = $mins != 0 ? sprintf('%d %s ', $mins, $minplural) : '';
	$secs = $secs != 0 ? sprintf('%d %s ', $secs, $secsplural) : '';
	$string = sprintf("%s%s%s%s", $days, $hours, $mins, $secs);
	if (empty($string)) {
	  return '0 sec';
	}
	return $string;
  }
  
  public static function booleanise($val)
  {
	$trueValues = array(
		'on',
		'yes',
		1,
		'true',
		'y'		
	);
	
	if(in_array(strtolower($val), $trueValues))
	{
	  return true;
	}
	return false;
  }

}