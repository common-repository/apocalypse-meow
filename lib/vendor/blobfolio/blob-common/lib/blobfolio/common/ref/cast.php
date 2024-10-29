<?php
 namespace blobfolio\wp\meow\vendor\common\ref; use \blobfolio\wp\meow\vendor\common\constants; use \blobfolio\wp\meow\vendor\common\data; class cast { public static function array(&$value=null) { if (\is_array($value)) { return; } try { $value = (array) $value; } catch (\Throwable $e) { $value = array(); } } public static function to_array(&$value=null) { static::array($value); } public static function bool(&$value=false, bool $flatten=false) { if (\is_bool($value)) { return; } if (! $flatten && \is_array($value)) { foreach ($value as $k=>$v) { static::bool($value[$k]); } } else { if (\is_string($value)) { $value = \strtolower($value); if (\in_array($value, constants::TRUE_BOOLS, true)) { $value = true; } elseif (\in_array($value, constants::FALSE_BOOLS, true)) { $value = false; } } elseif (\is_array($value)) { $value = !! \count($value); } if (! \is_bool($value)) { try { $value = (bool) $value; } catch (\Throwable $e) { $value = false; } } } } public static function to_bool(&$value=null, bool $flatten=false) { static::bool($value, $flatten); } public static function boolean(&$value=null, bool $flatten=false) { static::bool($value, $flatten); } public static function float(&$value=0, bool $flatten=false) { if (\is_float($value)) { return; } if (! $flatten && \is_array($value)) { foreach ($value as $k=>$v) { static::float($value[$k]); } } else { static::number($value, true); try { $value = (float) $value; } catch (\Throwable $e) { $value = 0.0; } } } public static function double(&$value=null, bool $flatten=false) { static::float($value, $flatten); } public static function to_float(&$value=null, bool $flatten=false) { static::float($value, $flatten); } public static function int(&$value=0, bool $flatten=false) { if (\is_int($value)) { return; } if (! $flatten && \is_array($value)) { foreach ($value as $k=>$v) { static::int($value[$k]); } } else { if (\is_array($value) && (1 === \count($value))) { $value = data::array_pop_top($value); } if (\is_string($value)) { $value = \strtolower($value); if (\in_array($value, constants::TRUE_BOOLS, true)) { $value = 1; } elseif (\in_array($value, constants::FALSE_BOOLS, true)) { $value = 0; } } if (! \is_int($value)) { static::number($value, true); $value = (int) $value; } } } public static function to_int(&$value=null, bool $flatten=false) { static::int($value, $flatten); } public static function integer(&$value=null, bool $flatten=false) { static::int($value, $flatten); } public static function number(&$value=0, bool $flatten=false) { if (\is_float($value)) { return; } if (! $flatten && \is_array($value)) { foreach ($value as $k=>$v) { static::number($value[$k]); } } else { if (\is_array($value) && (1 === \count($value))) { $value = data::array_pop_top($value); } if (\is_string($value)) { static::string($value); $from = \array_keys(constants::NUMBER_CHARS); $to = \array_values(constants::NUMBER_CHARS); $value = \str_replace($from, $to, $value); if (\preg_match('/^\-?[\d,]*\.?\d+¢$/', $value)) { $value = \preg_replace('/[^\-\d\.]/', '', $value); static::number($value); $value /= 100; } elseif (\preg_match('/^\-?[\d,]*\.?\d+%$/', $value)) { $value = \preg_replace('/[^\-\d\.]/', '', $value); static::number($value); $value /= 100; } } if (! \is_float($value)) { try { $value = (float) \filter_var( $value, \FILTER_SANITIZE_NUMBER_FLOAT, \FILTER_FLAG_ALLOW_FRACTION ); } catch (\Throwable $e) { $value = 0.0; } } } } public static function to_number(&$value=null, bool $flatten=false) { static::number($value, $flatten); } public static function string(&$value='', bool $flatten=false) { if (! $flatten && \is_array($value)) { foreach ($value as $k=>$v) { static::string($value[$k]); } } else { if (\is_array($value) && (1 === \count($value))) { $value = data::array_pop_top($value); } if (\is_array($value)) { $value = ''; return; } try { $value = (string) $value; if ( $value && ( ! \function_exists('mb_check_encoding') || ! \mb_check_encoding($value, 'ASCII') ) ) { sanitize::utf8($value); } } catch (\Throwable $e) { $value = ''; } } } public static function to_string(&$value=null, bool $flatten=false) { static::string($value, $flatten); } public static function to_type(&$value, string $type='', bool $flatten=false) { switch (\strtolower($type)) { case 'string': static::string($value, $flatten); break; case 'int': case 'integer': static::int($value, $flatten); break; case 'double': case 'float': case 'number': static::float($value, $flatten); break; case 'bool': case 'boolean': static::bool($value, $flatten); break; case 'array': static::array($value); break; } } public static function constringent(&$value=null, bool $light=false) { static::string($value, true); } } 