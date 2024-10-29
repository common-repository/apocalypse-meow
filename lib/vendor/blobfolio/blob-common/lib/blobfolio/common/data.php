<?php
 namespace blobfolio\wp\meow\vendor\common; class data { public static function array_compare(&$arr1, &$arr2) { if (! \is_array($arr1) || ! \is_array($arr2)) { return false; } $length = \count($arr1); if (\count($arr2) !== $length) { return false; } if (\count(\array_intersect_key($arr1, $arr2)) !== $length) { return false; } if ( (cast::array_type($arr1) !== 'associative') && (cast::array_type($arr2) !== 'associative') ) { return \count(\array_intersect($arr1, $arr2)) === $length; } foreach ($arr1 as $k=>$v) { if (! isset($arr2[$k])) { return false; } if (\is_array($arr1[$k]) && \is_array($arr2[$k])) { if (! static::array_compare($arr1[$k], $arr2[$k])) { return false; } } elseif ($arr1[$k] !== $arr2[$k]) { return false; } } return true; } public static function array_idiff($arr1, $arr2) { $arrays = \func_get_args(); if (! isset($arrays[1])) { return array(); } foreach ($arrays as $a) { if (! \is_array($a)) { return array(); } } $length = \count($arrays); for ($x = 1; $x < $length; ++$x) { $common = array(); if (! \count($arrays[$x])) { continue; } $arr1 = mb::strtolower($arrays[0], true); $arr2 = mb::strtolower($arrays[$x], true); foreach ($arr1 as $k=>$v) { if (! \is_array($v) && ! \in_array($v, $arr2, true)) { $common[$k] = $arrays[0][$k]; } } if (! \count($common)) { return $common; } $arrays[0] = $common; } return $arrays[0]; } public static function array_iintersect($arr1, $arr2) { $arrays = \func_get_args(); if (! isset($arrays[1])) { return array(); } foreach ($arrays as $a) { if (! \is_array($a) || ! \count($a)) { return array(); } } $length = \count($arrays); for ($x = 1; $x < $length; ++$x) { $common = array(); $arr1 = mb::strtolower($arrays[0], true); $arr2 = mb::strtolower($arrays[$x], true); foreach ($arr1 as $k=>$v) { if (! \is_array($v) && \in_array($v, $arr2, true)) { $common[$k] = $arrays[0][$k]; } } if (! \count($common)) { return $common; } $arrays[0] = $common; } return $arrays[0]; } public static function array_ikey_exists($needle, $haystack) { if (! \is_array($haystack) || ! \count($haystack)) { return false; } $haystack = \array_keys($haystack); return (false !== static::array_isearch($needle, $haystack)); } public static function array_isearch($needle, array $haystack, bool $strict=true) { if (! \count($haystack)) { return false; } ref\mb::strtolower($needle, true); ref\mb::strtolower($haystack, true); return array_search($needle, $haystack, $strict); } public static function array_map_recursive(callable $func, array $arr) { return \filter_var($arr, \FILTER_CALLBACK, array('options'=>$func)); } public static function array_otherize(array $arr, int $length=5, $other='Other') { if ('associative' !== cast::array_type($arr)) { return false; } foreach ($arr as $k=>$v) { if (! \is_int($arr[$k]) && ! \is_float($arr[$k])) { ref\cast::float($arr[$k], true); } } \arsort($arr); ref\sanitize::to_range($length, 1); if (\count($arr) <= $length) { return $arr; } ref\cast::string($other, true); if (! $other) { $other = 'Other'; } if (1 === $length) { return array($other=>\array_sum($arr)); } $out = \array_slice($arr, 0, $length - 1); $out[$other] = \array_sum(\array_slice($arr, $length - 1)); return $out; } public static function array_pop(array &$arr) { if (! \count($arr)) { return false; } $reversed = \array_reverse($arr); return static::array_pop_top($reversed); } public static function array_pop_rand(array &$arr) { $length = \count($arr); if (! $length) { return false; } if (1 === $length) { return static::array_pop_top($arr); } $keys = \array_keys($arr); $index = static::random_int(0, $length - 1); return $arr[$keys[$index]]; } public static function array_pop_top(array &$arr) { if (! \count($arr)) { return false; } \reset($arr); return $arr[\key($arr)]; } public static function cc_exp_months(string $format='m - M') { $months = array(); for ($x = 1; $x <= 12; ++$x) { $months[$x] = \date($format, \strtotime('2000-' . \sprintf('%02d', $x) . '-01')); } return $months; } public static function cc_exp_years(int $length=10) { if ($length < 1) { $length = 10; } $years = array(); for ($x = 0; $x < $length; ++$x) { $year = (int) (\date('Y') + $x); $years[$year] = $year; } return $years; } public static function datediff($date1, $date2) { ref\sanitize::date($date1); ref\sanitize::date($date2); if ( ! \is_string($date1) || ! \is_string($date2) || ($date1 === $date2) || ('0000-00-00' === $date1) || ('0000-00-00' === $date2) ) { return 0; } if (\class_exists('DateTime')) { $date1 = new \DateTime($date1); $date2 = new \DateTime($date2); $diff = $date1->diff($date2); return \abs($diff->days); } $date1 = \strtotime($date1); $date2 = \strtotime($date2); return \ceil(\abs($date2 - $date1) / 60 / 60 / 24); } public static function iin_array($needle, $haystack, bool $strict=true) { return (false !== static::array_isearch($needle, $haystack, $strict)); } public static function in_range($value, $min=null, $max=null) { return sanitize::to_range($value, $min, $max) === $value; } public static function ip_in_range(string $ip, $min, $max=null) { ref\sanitize::ip($ip, true); if (! \is_string($min)) { return false; } if (! $ip) { return false; } if (false !== \strpos($min, '/')) { if (false === ($range = format::cidr_to_range($min))) { return false; } $min = $range['min']; $max = $range['max']; } elseif (null === $max) { return false; } ref\format::ip_to_number($ip); ref\format::ip_to_number($min); ref\format::ip_to_number($max); if ( (false !== $ip) && (false !== $min) && (false !== $max) ) { return static::in_range($ip, $min, $max); } return false; } public static function is_json($str, bool $empty=false) { if (! \is_string($str) || (! $empty && ! $str)) { return false; } if ($empty && ! $str) { return true; } $json = \json_decode($str); return (null !== $json); } public static function is_utf8($str) { if (\is_numeric($str) || \is_bool($str)) { return true; } elseif (\is_string($str)) { return (bool) \preg_match('//u', $str); } return false; } public static function json_decode_array($json, $defaults=null, bool $strict=true, bool $recursive=true) { ref\format::json_decode($json); if ((null === $json) || (\is_string($json) && ! $json)) { $json = array(); } else { ref\cast::array($json); } if (\is_array($defaults)) { return static::parse_args($json, $defaults, $strict, $recursive); } else { return $json; } } public static function length_in_range(string $str, $min=null, $max=null) { if ((null !== $min) && ! \is_int($min)) { ref\cast::int($min, true); } if ((null !== $max) && ! \is_int($max)) { ref\cast::int($max, true); } $length = mb::strlen($str); if ((null !== $min) && (null !== $max) && $min > $max) { static::switcheroo($min, $max); } if ((null !== $min) && $min > $length) { return false; } if ((null !== $max) && $max < $length) { return false; } return true; } public static function parse_args($args, $defaults, bool $strict=true, bool $recursive=true) { ref\cast::array($defaults); if (! \count($defaults)) { return array(); } ref\cast::array($args); if (! \count($args)) { return $defaults; } foreach ($defaults as $k=>$v) { if (\array_key_exists($k, $args)) { if ( $recursive && \is_array($defaults[$k]) && (cast::array_type($defaults[$k]) === 'associative') ) { $defaults[$k] = static::parse_args($args[$k], $defaults[$k], $strict, $recursive); } else { $defaults[$k] = $args[$k]; if ($strict && (null !== $v)) { ref\cast::to_type($defaults[$k], \gettype($v), true); } } } } return $defaults; } public static function random_int(int $min=0, int $max=1) { if ($min > $max) { static::switcheroo($min, $max); } return \random_int($min, $max); } public static function random_string(int $length=10, $soup=null) { if ($length < 1) { return ''; } if (\is_array($soup) && \count($soup)) { ref\cast::string($soup); $soup = \implode('', $soup); ref\sanitize::printable($soup); $soup = \preg_replace('/\s/u', '', $soup); $soup = \array_unique(mb::str_split($soup, 1, true)); $soup = \array_values($soup); if (! \count($soup)) { return ''; } } if (! \is_array($soup) || ! \count($soup)) { $soup = constants::RANDOM_CHARS; } $salt = ''; $max = \count($soup) - 1; for ($x = 0; $x < $length; ++$x) { $salt .= $soup[static::random_int(0, $max)]; } return $salt; } public static function switcheroo(&$var1, &$var2) { $tmp = $var1; $var1 = $var2; $var2 = $tmp; return true; } public static function unsetcookie(string $name, string $path='', string $domain='', bool $secure=false, bool $httponly=false) { if (! \headers_sent()) { \setcookie($name, false, -1, $path, $domain, $secure, $httponly); if (isset($_COOKIE[$name])) { unset($_COOKIE[$name]); } return true; } return false; } } 