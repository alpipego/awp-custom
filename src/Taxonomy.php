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
 * @method self label(string $label)
 * @method self labels(array $labels)
 * @method self public (bool $public)
 * @method self publicly_queryable(bool $publicly_queryable)
 * @method self show_ui(bool $show_ui)
 * @method self show_in_menu(bool $show_in_menu)
 * @method self show_in_nav_menus(bool $show_in_nav_menus)
 * @method self show_in_rest(bool $show_in_rest)
 * @method self rest_base(string $rest_base)
 * @method self rest_controller_class(\WP_REST_Controller $rest_controller_class)
 * @method self show_tagcloud(bool $show_tagcloud)
 * @method self show_in_quick_edit(bool $show_in_quick_edit)
 * @method self meta_box_cb(callable $meta_box_cb)
 * @method self show_admin_column(bool $show_admin_column)
 * @method self description(string $description)
 * @method self hierarchical(bool $hierarchical)
 * @method self update_count_callback(callable $update_count_callback)
 * @method self query_var(bool | string $query_var)
 * @method self rewrite(bool | array $rewrite)
 * @method self capabilities(array $capabilities)
 * @method self sort(bool $sort)
 */
class Taxonomy extends AbstractCustom
{
    protected $taxonomy;
    protected $objects;


    public function __construct(string $taxonomy, string $singular, string $plural, array $objects)
    {
        $this->taxonomy = $taxonomy;
        $this->singular = $singular;
        $this->plural   = $plural;
        $this->objects  = $objects;

        $this->labels       = $this->defaultLabels();
        $this->capabilities = $this->defaultCaps();
    }

    protected function defaultLabels() : array
    {
        return [
            'name'                       => sprintf(_x('%s', 'Taxonomy General Name', 'tg'), $this->plural),
            'singular_name'              => sprintf(_x('%s', 'Taxonomy Singular Name', 'tg'), $this->singular),
            'search_items'               => sprintf(__('Search %s', 'tg'), $this->plural),
            'popular_items'              => sprintf(__('Popular %s', 'tg'), $this->plural),
            'all_items'                  => sprintf(__('All %s', 'tg'), $this->plural),
            'parent_item'                => sprintf(__('Parent %s', 'tg'), $this->singular),
            'parent_item_colon'          => sprintf(__('Parent %s:', 'tg'), $this->singular),
            'edit_item'                  => sprintf(__('Edit %s', 'tg'), $this->singular),
            'update_item'                => sprintf(__('Update %s', 'tg'), $this->singular),
            'add_new_item'               => sprintf(__('Add New %s', 'tg'), $this->singular),
            'new_item_name'              => sprintf(__('New %s Name', 'tg'), $this->singular),
            'separate_items_with_commas' => sprintf(__('Separate %s with commas', 'tg'), $this->plural),
            'add_or_remove_items'        => sprintf(__('Add or remove %s', 'tg'), $this->plural),
            'choose_from_most_used'      => sprintf(__('Choose from the most used %s', 'tg'), $this->plural),
            'not_found'                  => sprintf(__('No %s found.', 'tg'), $this->plural),
            'menu_name'                  => sprintf(_x('%s', 'Taxonomy Menu Name', 'tg'), $this->plural),
        ];
    }

    protected function defaultCaps() : array
    {
        return [
            'manage_terms' => 'manage_' . $this->taxonomy,
            'edit_terms'   => 'edit_' . $this->taxonomy,
            'delete_terms' => 'delete_' . $this->taxonomy,
            'assign_terms' => 'assign_' . $this->taxonomy,
        ];
    }

    /**
     * @return \WP_Error|\WP_Taxonomy
     */
    public function create()
    {
        $taxonomy = register_taxonomy($this->taxonomy, $this->objects, $this->mapArgs());
        if (!is_wp_error($taxonomy)) {
            $taxonomy = get_taxonomy($this->taxonomy);
        }

        return $taxonomy;
    }
}
