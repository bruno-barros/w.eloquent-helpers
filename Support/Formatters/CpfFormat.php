<?php namespace App\Support\Formatters;


use App\Support\Formatters\Contract\FormatInterface;

/**
 * Class CpfFormat
 *
 * Default format: 999.999.999-99
 *
 * @package Sig\Core\Formatters
 */
class CpfFormat extends BaseFormatter implements FormatInterface{


	protected $format = '999.999.999-99';


	/**
	 * The algorithm to format the data
	 *
	 * MUST return false if fails, or formatted data
	 *
	 * @param $data
	 * @return bool|mixed
	 */
	protected function parse($data)
	{
		// strip non numbers
		$numbers = preg_replace('/\D/', '', $data);

		if (strlen($numbers) != 11)
		{
			return false;
		}

		$formatted = '';

		$validDigit = 0;

		for($x = 0; $x < strlen($this->format); $x++)
		{
			$position = $this->format[$x];

			if($position == '9')
			{
				$formatted .= $numbers[$validDigit];
				$validDigit++;
			}
			else
			{
				$formatted .= $position;
			}
		}

		return $formatted;
	}
}