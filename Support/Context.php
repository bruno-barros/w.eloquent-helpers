<?php namespace App\Support;

use App\Entities\Pages\PageHierarchy;
use Illuminate\Support\Facades\Request;
use Weloquent\Core\Application;

/**
 * Context
 *
 * @author       Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2015 Bruno Barros
 */
class Context
{

	static public $instance;
	private $app;


	function __construct(Application $app = null)
	{
		$this->app = $app;
	}


	/**
	 * string representing architecture
	 */
	public function get()
	{
		$type = $this->type();

		if ($type == 'page')
		{
			//			$m = PageHierarchy::make();
			//			$type = $m->fromTop()->getMain()->post_name;

			$type = 'page';

		}
		else if ($type == 'post')
		{
			$type = 'blog';
		}

		return $type;
	}


	/**
	 * Post type
	 *
	 * @return false|string
	 */
	public function type($output = 'slug')
	{
		global $wp_query;

		if (is_search())
		{
			if ($output == 'object' && isset($_GET['post_type']))
			{
				return $this->typeObject($_GET['post_type']);
			}

			return 'search';
		}

		$queriedType = get_post_type();

		if (!$queriedType && isset($wp_query->query['post_type']))
		{

			if ($output == 'object')
			{
				return $this->typeObject($wp_query->query['post_type']);
			}

			return $wp_query->query['post_type'];
		}

		if ($output == 'object')
		{
			return $this->typeObject($queriedType);
		}

		return $queriedType;
	}


	/**
	 * Taxonomy or category
	 */
	public function taxonomy()
	{
		global $wp_query;

		if (get_queried_object() && isset(get_queried_object()->taxonomy))
		{
			return get_queried_object()->taxonomy;
		}

		return null;
	}


	/**
	 * Taxonomy term o r category term
	 */
	public function term($output = 'slug')
	{
		global $wp_query;

		if (isset($wp_query->query_vars['term']))
		{
			if ($output == 'object')
			{
				return $this->termObject($wp_query->query_vars['term'], get_query_var('taxonomy'));
			}

			return $wp_query->query_vars['term'];
		}

		return null;
	}


	public function termObject($term = '', $tax = '')
	{
		$term            = get_term_by('slug', $term, $tax);
		
		if (!$term)
		{
			return $this->sudoTerm();
		}
		
		$term->permalink = get_term_link($term);

		return $term;
	}

	public function typeObject($typeSlug = '')
	{
		$type = get_post_type_object($typeSlug);

		$type->permalink = get_post_type_archive_link($typeSlug);

		return $type;
	}
	
	private function sudoTerm()
	{
		$term                   = new \stdClass();
		$term->name             = 'desconhecido';
		$term->description      = 'desconhecido';
		$term->slug             = 'desconhecido';
		$term->term_id          = 0;
		$term->term_group       = 0;
		$term->parent           = 0;
		$term->count            = 0;
		$term->taxonomy         = '';
		$term->term_taxonomy_id = 0;
		$term->permalink        = '#';

		return $term;
	}

	public function single()
	{
		return is_singular();
	}

	public function page()
	{
		return is_page();
	}

	public function loop()
	{
		return in_the_loop();
	}

}
