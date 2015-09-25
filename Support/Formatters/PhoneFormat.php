<?php namespace App\Support\Formatters;

use App\Support\Formatters\Contract\FormatInterface;

/**
 * Class PhoneFormat
 *
 * Format and validate phone number
 *
 * Expected format: (99) 9?9999-?9999
 * PhoneFormat::make()->format('(DDD) PREF-SUF')
 *
 * @package Sig\Core\Formaters
 */
class PhoneFormat extends BaseFormatter implements FormatInterface
{

	/**
	 * The format of number
	 * @var string
	 */
	protected $format = "(DDD) PREF-SUF";


	/**
	 * Validate and format
	 * @param $data
	 * @return mixed|void
	 */
	protected function parse($data)
	{
		// strip non numbers
		$numbers = preg_replace('/\D/', '', $data);

		if (strlen($numbers) < 10 || strlen($numbers) > 11)
		{
			return false;
		}

		$ddd   = substr($numbers, 0, 2);
		$last = substr($numbers, 2);
		$length = strlen($last);

		$ddd = str_replace('DDD', $ddd, $this->format);

		// numbers except last 4 characters
		$pref = str_replace('PREF', substr($last, 0, $length-4), $ddd);

		return str_replace('SUF', substr($last, -4), $pref);

	}


}