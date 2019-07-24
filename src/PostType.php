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
 * @method self show_in_menu(bool|string $show_in_menu)
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
 * @method self has_archive(bool|string $has_archive)
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
			'name'                     => sprintf(_x('%s', 'General CPT Name', 'awp-custom'), $this->plural),
			'singular_name'            => sprintf(_x('%s', 'Singular CPT Name', 'awp-custom'), $this->singular),
			'add_new'                  => __('Add New', 'awp-custom'),
			'add_new_item'             => sprintf(__('Add new %s', 'awp-custom'), $this->singular),
			'edit_item'                => sprintf(__('Edit %s', 'awp-custom'), $this->singular),
			'new_item'                 => sprintf(__('New %s', 'awp-custom'), $this->singular),
			'view_item'                => sprintf(__('View %s', 'awp-custom'), $this->singular),
			'view_items'               => sprintf(__('View %s', 'awp-custom'), $this->plural),
			'search_items'             => sprintf(__('Search %s', 'awp-custom'), $this->plural),
			'not_found'                => sprintf(__('No %s found', 'awp-custom'), $this->plural),
			'not_found_in_trash'       => sprintf(__('No %s found in Trash', 'awp-custom'), $this->plural),
			'parent_item_colon'        => sprintf(__('Parent: %s', 'awp-custom'), $this->singular),
			'all_items'                => sprintf(__('All %s', 'awp-custom'), $this->plural),
			'archives'                 => sprintf(__('%s Archive', 'awp-custom'), $this->singular),
			'attributes'               => sprintf(__('%s Attributes', 'awp-custom'), $this->singular),
			'insert_into_item'         => sprintf(__('Insert into %s', 'awp-custom'), $this->singular),
			'uploaded_to_this_item'    => sprintf(__('Uploaded to this %s', 'awp-custom'), $this->singular),
			'featured_image'           => __('Featured Image', 'awp-custom'),
			'set_featured_image'       => __('Set featured image', 'awp-custom'),
			'remove_featured_image'    => __('Remove featured image', 'awp-custom'),
			'use_featured_image'       => __('Use featured image', 'awp-custom'),
			'menu_name'                => sprintf(_x('%s', 'Menu Name', 'awp-custom'), $this->plural),
			'name_admin_bar'           => sprintf(_x('%s', 'Admin Bar Name', 'awp-custom'), $this->singular),
			'item_published'           => sprintf(__('%s published.', 'awp_custom'), $this->singular),
			'item_published_privately' => sprintf(__('%s published privately.', 'awp_custom'), $this->singular),
			'item_reverted_to_draft'   => sprintf(__('%s reverted to draft.', 'awp_custom'), $this->singular),
			'item_scheduled'           => sprintf(__('%s scheduled.', 'awp_custom'), $this->singular),
			'item_updated'             => sprintf(__('%s updated.', 'awp_custom'), $this->singular),
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
