<?php
defined('ABSPATH') || exit;

/**
 * Get instance of helper.
 *
 * @return TFThemeHelper
 */
function helper()
{
    return TFThemeHelper::getInstance();
}

/**
 * Main class of all the settings.
 */
class TFThemeHelper
{
    private static $_instance = null;

    /** @var TabFinanceBackend */
    public $backend;

    /** @var TabFinanceCostTable */
    public $cost_table;

    /** @var TabFinanceCostAjax */
    public $cost_ajax;

    public function __construct()
    {

    }

    /**
     * Class instance.
     *
     * @return TFThemeHelper
     */
    static public function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Load files, plugins, add hooks and filters and do all magic.
     *
     * @return TFThemeHelper|void
     */
    function init()
    {
        $this->import();
        $this->load_classes();
        $this->registerHooks();

        $this->backend->init();
        $this->cost_table->init();
        $this->cost_ajax->init();

        return $this;
    }

    /**
     * Import files.
     */
    public function import()
    {
        include_once 'backend.php';
        include_once 'costs_DB_handler.php';
        include_once 'costs_ajax_handler.php';
    }

    /**
     * Load classes.
     */
    function load_classes()
    {
        $this->backend = TabFinanceBackend::getInstance();
        $this->cost_table = TabFinanceCostTable::getInstance();
        $this->cost_ajax = TabFinanceCostAjax::getInstance();
    }

    /**
     * Register all needed hooks.
     */
    public function registerHooks()
    {
        add_action('wp_enqueue_scripts', [$this, 'load_scripts_n_styles']);
        add_action('after_setup_theme', [$this, 'add_theme_support']);
        add_action('widgets_init', [$this, 'tf_widgets_init']);
        add_action('after_setup_theme', [$this, 'register_menus']);

        if( function_exists('acf_add_options_page') ) {
            acf_add_options_page();
        }
    }

    /**
     * Register menus.
     */
    function register_menus()
    {
        $menus = [
            'header_menu' => 'Главное меню',
        ];

        register_nav_menus($menus);
    }

    /**
     * Add theme support.
     */
    function add_theme_support()
    {
        add_theme_support('title-tag');
        add_theme_support('post-thumbnails');
        add_theme_support('html5', array(
            'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
        ));
    }

    /**
     * Register widgets.
     */
    function tf_widgets_init() {
        register_sidebar( array(
            'name'          => __( 'Главная-левая-колонка', 'tf_theme' ),
            'id'            => 'sidebar-1',
            'description'   => __( 'Add widgets here to appear in your sidebar.', 'tf_theme' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ) );
    }


    /**
     * Load scripts and styles.
     */
    function load_scripts_n_styles() {
        wp_enqueue_style( 'style_reset', get_template_directory_uri() . '/css/reset.css', array(), '3.2' );
        wp_enqueue_style( 'fonts', get_template_directory_uri() . '/css/fonts.css', array(), '3.2' );
        wp_enqueue_style( 'datepicker_css', get_template_directory_uri() . '/css/datepicker.min.css', array(), '3.2' );
        wp_enqueue_style( 'style', get_template_directory_uri() . '/style.css', array(), '3.2' );

        wp_enqueue_script( 'jQuery', get_template_directory_uri() . '/js/jquery-3.0.0.min.js', array(), '20150330', true );
        wp_enqueue_script( 'datepicker', get_template_directory_uri() . '/js/datepicker.min.js', array(), '20150330', true );
        wp_enqueue_script( 'cost_scripts', get_template_directory_uri() . '/js/cost_scripts.js', array(), '20150330', true );
        wp_localize_script( 'cost_scripts', 'costAjaxSettings', [
            'stylesheetdir' => get_stylesheet_directory_uri(),
            'ajax_url'      => admin_url('admin-ajax.php'),
            'nonce'         => wp_create_nonce('costAjaxNonce'),
        ]);
        wp_enqueue_script( 'scripts', get_template_directory_uri() . '/js/scripts.js', array(), '20150330', true );
    }

}