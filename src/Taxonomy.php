<?php
/**
 * Created by PhpStorm.
 * User: alpipego
 * Date: 10.07.18
 * Time: 14:45
 */
declare(strict_types = 1);

namespace Alpipego\AWP\Custom;

/**
 * Class Taxonomy
 * @package Alpipego\AWP\Custom
 *
 * @method Taxonomy label(string $label)
 * @method Taxonomy labels(array $labels)
 * @method Taxonomy public (bool $public)
 * @method Taxonomy publicly_queryable(bool $publicly_queryable)
 * @method Taxonomy show_ui(bool $show_ui)
 * @method Taxonomy show_in_menu(bool $show_in_menu)
 * @method Taxonomy show_in_nav_menus(bool $show_in_nav_menus)
 * @method Taxonomy show_in_rest(bool $show_in_rest)
 * @method Taxonomy rest_base(string $rest_base)
 * @method Taxonomy rest_controller_class(\WP_REST_Controller $rest_controller_class)
 * @method Taxonomy show_tagcloud(bool $show_tagcloud)
 * @method Taxonomy show_in_quick_edit(bool $show_in_quick_edit)
 * @method Taxonomy meta_box_cb(callable $meta_box_cb)
 * @method Taxonomy show_admin_column(bool $show_admin_column)
 * @method Taxonomy description(string $description)
 * @method Taxonomy hierarchical(bool $hierarchical)
 * @method Taxonomy update_count_callback(callable $update_count_callback)
 * @method Taxonomy query_var(bool | string $query_var)
 * @method Taxonomy rewrite(bool | array $rewrite)
 * @method Taxonomy capabilities(array $capabilities)
 * @method Taxonomy sort(bool $sort)
 */
class Taxonomy extends AbstractCustom
{
    protected $taxonomy;
    protected $objects;
    protected $capability_type = 'categories';
    protected $map_meta_cap = false;

    public function __construct(string $taxonomy, string $singular, string $plural, array $objects)
    {
        $this->taxonomy = $taxonomy;
        $this->singular = $singular;
        $this->plural   = $plural;
        $this->objects  = $objects;

        $this->labels       = $this->defaultLabels();
        $this->capabilities = $this->defaultCaps();
    }

    protected function defaultLabels(): array
    {
        return [
            'name'                       => sprintf(_x('%s', 'Taxonomy General Name', 'awp-custom'), $this->plural),
            'singular_name'              => sprintf(_x('%s', 'Taxonomy Singular Name', 'awp-custom'), $this->singular),
            'search_items'               => sprintf(__('Search %s', 'awp-custom'), $this->plural),
            'popular_items'              => sprintf(__('Popular %s', 'awp-custom'), $this->plural),
            'all_items'                  => sprintf(__('All %s', 'awp-custom'), $this->plural),
            'parent_item'                => sprintf(__('Parent %s', 'awp-custom'), $this->singular),
            'parent_item_colon'          => sprintf(__('Parent %s:', 'awp-custom'), $this->singular),
            'edit_item'                  => sprintf(__('Edit %s', 'awp-custom'), $this->singular),
            'update_item'                => sprintf(__('Update %s', 'awp-custom'), $this->singular),
            'add_new_item'               => sprintf(__('Add New %s', 'awp-custom'), $this->singular),
            'new_item_name'              => sprintf(__('New %s Name', 'awp-custom'), $this->singular),
            'separate_items_with_commas' => sprintf(__('Separate %s with commas', 'awp-custom'), $this->plural),
            'add_or_remove_items'        => sprintf(__('Add or remove %s', 'awp-custom'), $this->plural),
            'choose_from_most_used'      => sprintf(__('Choose from the most used %s', 'awp-custom'), $this->plural),
            'not_found'                  => sprintf(__('No %s found.', 'awp-custom'), $this->plural),
            'menu_name'                  => sprintf(_x('%s', 'Taxonomy Menu Name', 'awp-custom'), $this->plural),
        ];
    }

    protected function defaultCaps(): array
    {
        return [
            'manage_terms' => 'manage_' . $this->capability_type,
            'edit_terms'   => 'edit_' . $this->capability_type,
            'delete_terms' => 'delete_' . $this->capability_type,
            'assign_terms' => 'edit_posts',
        ];
    }

    /**
     * @return \WP_Error|\WP_Taxonomy
     */
    public function create()
    {
        if ($this->capability_type !== 'categories' && empty($this->capabilities)) {
            $this->capabilities = $this->defaultCaps();
            $this->map_meta_cap = true;
        }

        $taxonomy = register_taxonomy($this->taxonomy, $this->objects, $this->mapArgs());
        if (!is_wp_error($taxonomy)) {
            $taxonomy = get_taxonomy($this->taxonomy);
        }

        return $taxonomy;
    }
}
