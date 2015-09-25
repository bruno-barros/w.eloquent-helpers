<?php namespace App\Support\Formatters\Contract;


/**
 * interface FormatInterface
 *
 * @package Sig\Core\Formaters\Contract
 */
interface FormatInterface
{
	/**
	 * Make format instance
	 *
	 * @param $str
	 * @return FormatInterface
	 */
	public static function make($str);

	/**
	 * Check if is valid
	 * @return bool
	 */
	public function isValid();

	/**
	 * get the formatted data
	 *
	 * @return null|string
	 */
	public function get();

	/**
	 * Pass the format and return formatted
	 *
	 * @param string $format
	 * @return null|string
	 */
	public function format($format = '');
}