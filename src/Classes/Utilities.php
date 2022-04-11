<?php

namespace App\Classes;

class Utilities
{

	public static function env(string $name, $default = NULL)
	{
		$value = $_ENV[$name];
		if ($default !== NULL) {
			if (!empty($value))
				return $value;
			return $default;
		}
		return (empty($value) && $default === NULL) ? die('Environment variable ' . $name . ' not found or has no value') : $value;
	}

	public static function hasValue(array $array, string $key): bool
	{
		return !empty($array[$key]);
	}

	public static function dump($data)
	{
		echo '<pre>';
		var_dump($data);
		echo '</pre>';
	}
}
