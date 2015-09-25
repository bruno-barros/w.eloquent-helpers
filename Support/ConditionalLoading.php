<?php namespace App\Support;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

/**
 * ConditionalLoading
 *
 * ConditionalLoading::start('/uri')
 * ConditionalLoading::if('/uri')
 * ConditionalLoading::isNot('/uri')
 * ConditionalLoading::is('/uri')
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2015 Bruno Barros
 */
class ConditionalLoading
{

	private static $stoped = false;
	/**
	 * @var string
	 */
	protected $url;

	/**
	 * @var array
	 */
	protected $modules;

	private static $instance;

	public static function getInstance()
	{
		if (!self::$instance)
		{
			$cl = new static;

			return $cl;
		}

		return self::$instance;
	}

	public static function start($module)
	{

        if(defined('WEL_ENV')){
            return true;
        }

		$cl = static::getInstance();
		$cl->loadModules();

		// do not apply rules o admin side
		if(function_exists('is_admin') && is_admin() )
		{
			return true;
		}

		if (!$cl->canModulePass($module) && count($cl->getModules()) > 0)
		{
			$cl->consoleLog($module);

			return false;
		}

		return true;
	}

	public function consoleLog($module)
	{
		if (WP_DEBUG)
		{
			add_action('wp_footer', function () use ($module)
			{
				echo '<script>console.info("NOT LOADED: ' . $module . '")</script>';
			});
		}
	}

	/**
	 * Check if module rules match configuration
	 *
	 * @param $module
	 * @return bool
	 */
	public function canModulePass($module)
	{
		$rules = $this->getModuleRules($module);

		if (!$rules)
		{
			return true;
		}

		foreach ($rules as $path => $verbs)
		{

			$checkUri = trim($path, '/');

			$negate = substr($checkUri, 0, 1) == '!' ? true : false;

			if ($negate)
			{
				$checkUri = substr($checkUri, 1);
			}

			$actualUri = trim($this->getPath(), '/');

			// same URI and same verb
			if ($checkUri === $actualUri && in_array($this->getMethod(), $verbs))
			{
				if ($negate)
				{
					return true;// can run plugin
				}
				else
				{
					return false;// can't run
				}
			}
		}

		return true;// run whatever
	}


	/**
	 * @param $module
	 * @return null
	 */
	public function getModuleRules($module)
	{
		if (isset($this->modules[$module]))
		{
			return $this->modules[$module];
		}

		return null;
	}


	/**
	 * @param $url
	 */
	public function setUrl($url)
	{
		$this->url = $url;
	}


	public function getModules()
	{
		return $this->modules;
	}

	/**
	 * @return string
	 */
	public function getUrl()
	{
		return is_null($this->url) ? Request::url() : $this->url;
	}

	/**
	 * @return mixed
	 */
	public function getPath()
	{
		// remove base path
		$path = str_replace(env('WP_HOME'), '', $this->getUrl());
		// remove query strings
		$queries = explode('?', $path);

		return $queries[0];
	}

	/**
	 * @return mixed
	 */
	public function getMethod()
	{
		return $_SERVER['REQUEST_METHOD'];
	}

	/**
	 * Load modules rules
	 *
	 * @return mixed
	 */
	public function loadModules()
	{
		if (self::$stoped)
		{
			return [];
		}

		return $this->modules = Config::get('conditional-modules');
	}

	public function stoped($isStoped = true)
	{
		$cl          = static::getInstance();
		$cl::$stoped = (bool)$isStoped;
	}

	/**
	 * Set rules manually
	 * ATTENTION it will overwrite original rules
	 *
	 * @param array $modules
	 */
	public function setModules(array $modules)
	{
		$this->modules = $modules;
	}

}