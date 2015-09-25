<?php namespace App\Support\Formatters;

use App\Support\Formatters\Contract\FormatInterface;

abstract class BaseFormatter implements FormatInterface
{

	/**
	 * @var bool
	 */
	protected $isValid = false;

	/**
	 * The data to be formatted
	 * @var null|string
	 */
	protected $data = null;

	/**
	 * The format pattern
	 * @var string
	 */
	protected $format = "";


	function __construct($data = null)
	{
		$this->data = $data;
		$this->run($data);
	}

	/**
	 * Make format instance
	 *
	 * @param $str
	 * @return FormatInterface
	 */
	public static function make($str)
	{
		$pf = new static($str);

		return $pf;

	}

	/**
	 * Check if the number is valid
	 * @return bool
	 */
	public function isValid()
	{
		return (bool) $this->isValid;
	}



	/**
	 * get the formatted data
	 *
	 * @return null|string
	 */
	public function get()
	{
		return $this->data;
	}

	/**
	 * Pass the pattern format and return formatted
	 *
	 * @param string $format
	 * @return null|string
	 */
	public function format($format = '')
	{
		$this->format = $format;

		$this->run($this->data);
		return $this->data;
	}

	/**
	 * Run the parser
	 * Apply error, or formatted data
	 *
	 * @param $data
	 */
	private function run($data)
	{
		$transformedData = $this->parse($data);

		if($transformedData === false || $transformedData === null)
		{
			$this->isValid = false;
			$this->data    = null;

			return;
		}

		$this->isValid = true;

		$this->data = $transformedData;
	}

	/**
	 * The algorithm to format the data
	 *
	 * MUST return false if fails, or formatted data
	 *
	 * @param $str
	 * @return bool|mixed
	 */
	abstract protected function parse($data);

}