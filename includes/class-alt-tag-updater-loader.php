<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       http://yourwebsite.com
 * @since      1.0.0
 *
 * @package    Alt_Tag_Updater
 * @subpackage Alt_Tag_Updater/includes
 */

/**
 * Registers all actions and filters for the plugin.
 *
 * Manages a list of all hooks registered throughout the plugin,
 * and executes them with the WordPress API when the plugin runs.
 *
 * @package    Alt_Tag_Updater
 * @subpackage Alt_Tag_Updater/includes
 * @author     Ryan S. <email@example.com>
 */
class Alt_Tag_Updater_Loader {

    /**
     * The array of actions registered with WordPress.
     *
     * @since    1.0.0
     * @access   protected
     * @var      array $actions Registered actions.
     */
    protected array $actions;

    /**
     * The array of filters registered with WordPress.
     *
     * @since    1.0.0
     * @access   protected
     * @var      array $filters Registered filters.
     */
    protected array $filters;

    /**
     * Initializes the collections for actions and filters.
     *
     * @since    1.0.0
     */
    public function __construct() {
        $this->actions = [];
        $this->filters = [];
    }

    /**
     * Adds a new action to the collection.
     *
     * @since    1.0.0
     * @param    string   $hook           The WordPress action hook name.
     * @param    object   $component      The instance of the class where the action is defined.
     * @param    string   $callback       The callback method on the $component.
     * @param    int      $priority       (Optional) Priority for the action. Default is 10.
     * @param    int      $accepted_args  (Optional) Number of accepted arguments. Default is 1.
     */
    public function add_action(string $hook, object $component, string $callback, int $priority = 10, int $accepted_args = 1): void {
        $this->actions = $this->add($this->actions, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * Adds a new filter to the collection.
     *
     * @since    1.0.0
     * @param    string   $hook           The WordPress filter hook name.
     * @param    object   $component      The instance of the class where the filter is defined.
     * @param    string   $callback       The callback method on the $component.
     * @param    int      $priority       (Optional) Priority for the filter. Default is 10.
     * @param    int      $accepted_args  (Optional) Number of accepted arguments. Default is 1.
     */
    public function add_filter(string $hook, object $component, string $callback, int $priority = 10, int $accepted_args = 1): void {
        $this->filters = $this->add($this->filters, $hook, $component, $callback, $priority, $accepted_args);
    }

    /**
     * Registers hooks (actions/filters) into a collection.
     *
     * @since    1.0.0
     * @access   private
     * @param    array    $hooks          The collection of hooks.
     * @param    string   $hook           The hook name.
     * @param    object   $component      The class instance.
     * @param    string   $callback       The callback method name.
     * @param    int      $priority       Priority of the hook.
     * @param    int      $accepted_args  Number of accepted arguments.
     * @return   array                    Updated hooks collection.
     */
    private function add(array $hooks, string $hook, object $component, string $callback, int $priority, int $accepted_args): array {
        $hooks[] = [
            'hook'          => $hook,
            'component'     => $component,
            'callback'      => $callback,
            'priority'      => $priority,
            'accepted_args' => $accepted_args,
        ];
        return $hooks;
    }

    /**
     * Registers all filters and actions with WordPress.
     *
     * @since    1.0.0
     */
    public function run(): void {
        if (!empty($this->filters)) {
            foreach ($this->filters as $hook) {
                add_filter($hook['hook'], [$hook['component'], $hook['callback']], $hook['priority'], $hook['accepted_args']);
            }
        }

        if (!empty($this->actions)) {
            foreach ($this->actions as $hook) {
                add_action($hook['hook'], [$hook['component'], $hook['callback']], $hook['priority'], $hook['accepted_args']);
            }
        }
    }
}
