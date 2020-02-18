<?php
//require "/welcome-panel.php";


class SwMapsInit{
    private $page_title = "SW Maps Directions";
    private $menu_title = "SW Maps Directions";
    private $capability = "edit_theme_options";
    private $menu_slug =  "sw-maps-directions";

    public function __construct(){
       add_action('admin_enqueue_scripts', array($this,'swmaps_styles') );
       add_action('init', array($this, 'swmaps_enqueue_scripts'));
       $this->createDashboard();
    }

    public function swmaps_styles()
    {
       
        wp_register_style('swmaps-style', SWMAPS_PLUGIN_URL .'/css/admin/style.css', false, '1.0.0');
        wp_enqueue_style('swmaps-style');
        
        
    }

    public function swmaps_enqueue_scripts()
    {
        wp_register_style('swmaps-css', SWMAPS_PLUGIN_URL . 'css/style.css', false, '1.0.0');
        wp_enqueue_style('swmaps-css');
        $key = "AIzaSyCWzA5DjyKbSijEjcFNJwOOiXzxQeYbTjk";
        wp_register_script("google-maps", esc_url(add_query_arg('key', $key, '//maps.googleapis.com/maps/api/js')), array(), null, false);
        wp_enqueue_script("google-maps");
        add_filter('script_loader_tag', array($this,'swmaps_add_async_defer_attribute'), 10, 2); 

    }
    
    function swmaps_add_async_defer_attribute($tag, $handle) {
	if ( 'google-maps' !== $handle )
	return $tag;
	return str_replace( ' src', ' async defer src', $tag );
}

    
    public function createDashboard(){
        add_action('admin_menu', array($this, 'add_main_menu' ) );
    }
    
    public function add_main_menu(){
    add_menu_page( 
        $this->page_title, 
        $this->menu_title, 
        $this->capability, 
        $this->menu_slug, 
        array($this, "render_main_menu"), 
        "dashicons-editor-expand",
        25);
    
    add_submenu_page(
        $this->menu_slug,
        'Impostazioni',
        'Impostazioni',
        'manage_options',
        $this->menu_slug
    );
    add_submenu_page(
        $this->menu_slug,
        'Itinerari',
        'Itinerari',
        'manage_options',
        'edit.php?post_type=sw-itineraries'
    );

    }

    public function render_main_menu(){
     //swmaps_welcome();
    }
}