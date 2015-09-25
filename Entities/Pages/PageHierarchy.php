<?php  namespace App\Entities\Pages;

use Weloquent\Presenters\PagePresenter;

/**
 * PageHierarchy
 *
 * @author Bruno Barros  <bruno@brunobarros.com>
 * @copyright    Copyright (c) 2015 Bruno Barros
 */
class PageHierarchy
{

    /**
     * @var \WP_Post
     */
    private $page;

    /**
     * Get pages from the main page
     * @var bool
     */
    private $fromTop = false;

    /**
     * @var null
     */
    private $topPageId = null;

    /**
     * @param \WP_Post $page
     */
    function __construct(\WP_Post $page = null)
    {
        global $post;

        $this->page = (!$page) ? $post : $page;

    }

    /**
     * Statical access
     * @param null|\WP_Post $page
     * @return PageHierarchy
     */
    public static function make(\WP_Post $page = null)
    {
        return new self($page);
    }

    /**
     * Return all pages as flat array of WP_Post
     * @return array
     */
    public function flat()
    {
        return get_page_children($this->getMainParentId(), $this->getAllPages());
    }

    /**
     * Return all pages as <li> hierarchy
     * @return string
     */
    public function lis($args = [])
    {
        return wp_list_pages(array_merge([
            'authors'     => '',
            'child_of'    => $this->getMainParentId(),
            'date_format' => get_option('date_format'),
            'depth'       => 0,
            'echo'        => false,
            'exclude'     => '',
            'include'     => '',
            'link_after'  => '',
            'link_before' => '',
            'post_type'   => 'page',
            'post_status' => 'publish',
            'show_date'   => '',
            'sort_column' => 'menu_order, post_title',
            'sort_order'  => '',
            'title_li'    => '',
            'walker'      => ''
        ], $args));
    }


    /**
     * Starts from the first main page
     * @param bool $fromTop
     * @return $this
     */
    public function fromTop($fromTop = true)
    {
        $this->fromTop = $fromTop;

        return $this;
    }


    public function getAllPages()
    {
        $args = array(
            'sort_order'   => 'ASC',
            'sort_column'  => 'menu_order',
            'hierarchical' => true,
            'exclude'      => '',
            'include'      => '',
            'meta_key'     => '',
            'meta_value'   => '',
            'authors'      => '',
            'child_of'     => $this->getMainParentId(),
            'parent'       => -1,
            'exclude_tree' => '',
            'number'       => '',
            'offset'       => 0,
            'post_type'    => 'page',
            'post_status'  => 'publish'
        );

        return get_pages($args);
    }

    /**
     * Retrieve the to page of the hierarchy
     * @return null|\WP_Post
     */
    public function getMain()
    {
        return new PagePresenter(get_post($this->getMainParentId()));
    }

    /**
     * @return bool
     */
    public function hasParent()
    {
        return $this->page->post_parent > 0;
    }

    /**
     * Return the ID of the first page of the hierarchy
     *
     * @return int
     */
    public function getMainParentId()
    {

        if (!$this->fromTop)
        {
            return $this->page->ID;
        }

        if($this->topPageId)
        {
            return $this->topPageId;
        }

        if(!$this->hasParent())
        {
            return $this->topPageId = $this->page->ID;
        }

        $ancestors = get_ancestors($this->page->ID, 'page');

        if (empty($ancestors))
        {
            return $this->topPageId = $this->page->ID;
        }

        return $this->topPageId = end($ancestors);
    }
}