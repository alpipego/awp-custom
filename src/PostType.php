<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 04.05.18
 * Time: 14:02
 */
declare(strict_types = 1);

namespace Alpipego\AWP\Custom;

/**
 * Class PostType
 * @package Alpipego\AWP\Custom
 *
 * @method self label(string $label)
 * @method self labels(array $labels)
 * @method self description(string $description)
 * @method self public (bool $public)
 * @method self exclude_from_search(bool $exclude_from_search)
 * @method self publicly_queryable(bool $publicly_queryable)
 * @method self show_ui(bool $show_ui)
 * @method self show_in_nav_menus(bool $show_in_nav_menus)
 * @method self show_in_menu(bool $show_in_menu)
 * @method self show_in_admin_bar(bool $show_in_admin_bar)
 * @method self menu_position(int $public)
 * @method self menu_icon(string $menu_icon)
 * @method self capability_type(string | array $capability_type)
 * @method self capabilities(array $capabilities)
 * @method self map_meta_cap(bool $map_meta_cap)
 * @method self hierarchical(bool $hierarchical)
 * @method self supports(bool | array $supports)
 * @method self register_meta_box_cb(callable $register_meta_box_cb)
 * @method self taxonomies(array $taxonomies)
 * @method self has_archive(bool $has_archive)
 * @method self rewrite(bool | array $rewrite)
 * @method self permalink_epmask(bool $permalink_epmask)
 * @method self query_var(bool | string $query_var)
 * @method self can_export(bool $can_export)
 * @method self delete_with_user(bool $delete_with_user)
 * @method self show_in_rest(bool $show_in_rest)
 * @method self rest_base(string $rest_base)
 * @method self rest_controller_class(\WP_REST_Controller $rest_controller_class)
 */
class PostType extends AbstractCustom
{
	private $postType;
	protected $capability_type = 'post';

	public function __construct(string $postType, string $singular, string $plural)
	{
		$this->postType = $postType;
		$this->singular = $singular;
		$this->plural   = $plural;

		$this->labels = $this->defaultLabels();
	}

	protected function defaultLabels() : array
	{
		return [
			'name'               => sprintf(_x('%s', 'General CPT Name', 'tg'), $this->plural),
			'singular_name'      => sprintf(_x('%s', 'Singular CPT Name', 'tg'), $this->singular),
			'add_new'            => __('Add New', 'tg'),
			'add_new_item'       => sprintf(__('Add new %s', 'tg'), $this->singular),
			'edit_item'          => sprintf(__('Edit %s', 'tg'), $this->singular),
			'new_item'           => sprintf(__('New %s', 'tg'), $this->singular),
			'view_item'          => sprintf(__('View %s', 'tg'), $this->singular),
			'search_items'       => sprintf(__('Search %s', 'tg'), $this->plural),
			'not_found'          => sprintf(__('No %s found', 'tg'), $this->plural),
			'not_found_in_trash' => sprintf(__('No %s found in Trash', 'tg'), $this->plural),
			'parent_item_colon'  => sprintf(__('Parent: %s', 'tg'), $this->singular),
			'all_items'          => sprintf(__('All %s', 'tg'), $this->plural),
			'archives'           => sprintf(__('%s Archive', 'tg'), $this->singular),
			'menu_name'          => sprintf(_x('%s', 'Menu Name', 'tg'), $this->plural),
			'name_admin_bar'     => sprintf(_x('%s', 'Admin Bar Name', 'tg'), $this->singular),
		];
	}

	protected function defaultCaps() : array
	{
		if (is_array($this->capability_type)) {
			$capabilityTypeSingular = $this->capability_type[0];
			$capabilityTypePlural   = $this->capability_type[1];
		} else {
			$capabilityTypeSingular = $this->capability_type;
			$capabilityTypePlural   = $this->capability_type . 's';
		}

		return [
			// meta caps
			'edit_post'              => 'edit_' . $capabilityTypeSingular,
			'read_post'              => 'read_' . $capabilityTypeSingular,
			'delete_post'            => 'delete_' . $capabilityTypeSingular,
			// primitive
			'edit_posts'             => 'edit_' . $capabilityTypePlural,
			'edit_others_posts'      => 'edit_others_' . $capabilityTypePlural,
			'read_private_posts'     => 'read_private_' . $capabilityTypeSingular,
			'publish_posts'          => 'publish_' . $capabilityTypePlural,
			// additional primitive
			'read'                   => 'read',
			'delete_posts'           => 'delete_' . $capabilityTypePlural,
			'delete_private_posts'   => 'delete_private_' . $capabilityTypePlural,
			'delete_published_posts' => 'delete_published_' . $capabilityTypePlural,
			'delete_others_posts'    => 'delete_others_' . $capabilityTypePlural,
			'edit_private_posts'     => 'edit_private_' . $capabilityTypePlural,
			'edit_published_posts'   => 'edit_published_' . $capabilityTypePlural,
			'create_posts'           => 'create_' . $capabilityTypePlural,
		];
	}

	/**
	 * @return \WP_Error|\WP_Post_Type
	 */
	public function create()
	{
		if ($this->capability_type !== 'post' && empty($this->capabilities)) {
			$this->capabilities = $this->defaultCaps();
		}

		$postType               = register_post_type($this->postType, $this->mapArgs());
		$postType->capabilities = $this->capabilities;

		return $postType;
	}
}
