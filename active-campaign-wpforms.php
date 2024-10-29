<?php
/**
 * Plugin Name: Active Campaign & WPForms
 * Description: Add WPForms Data to Active Campiagn Contact lists.
 * Author: WPoperation
 * Plugin URI: https://wordpress.org/plugins/ac-wpforms
 * Author URI: https://wpoperation.com
 * Version: 1.1.1
 * Tested up to: 6.2.2
 * Text Domain: active-campaign-wpforms
 * Domain Path: /languages/
 * Requires Plugins: wpforms-lite
 **/
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;
if (!class_exists('ACWPFORMS_Integration')) {
    class ACWPFORMS_Integration
    {
        public function __construct(){
        
            /**
             * check for contact form 7
             */
            add_action('init', array($this,'acwf_plugin_dependencies'));

            add_action( 'wpforms_builder_enqueues',array($this,'acwf_register_backend_assets') );
            add_action('init', array($this,'init'));

            add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array($this,'acwf_pro_plugin_action_links') );
        }

        public function init(){
            load_plugin_textdomain('active-campaign-wpforms', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        }

        public function acwf_plugin_dependencies() {
            define("ACWPFORMS_PATH", plugin_dir_path(__FILE__));
            define("ACWPFORMS_URL", plugin_dir_url(__FILE__));
            if (!defined('WPFORMS_VERSION')) {
                add_action('admin_notices',  array($this, 'acwf_admin_notices'));
            } else {
                /**
                 * include settings
                 */
                require_once( ACWPFORMS_PATH . 'includes/acwf-settings.php' );

                /**
                 * contact form 7 Subscribe class
                 */
                require_once( ACWPFORMS_PATH . 'includes/acwf-subscribe.php' );                
            }
        }
        
        //Registering of backend js and css
        public function acwf_register_backend_assets() {
            
            wp_enqueue_style( 'acwf-admin-css', ACWPFORMS_URL.'assets/admin.css');   
        }

        public function acwf_admin_notices() {
            $class = 'notice notice-error';
            $message = __('Active Campaign & WPForms  requires WPForms to be installed and active.', 'active-campaign-wpforms');
            printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);
        }

        function acwf_pro_plugin_action_links( $links ) {
         
            $links[] = '<a href="https://wpoperation.com/plugins/active-campaign-wpforms-pro/" target="_blank" style="color:#05c305; font-weight:bold;">'.esc_html__('Go Pro','active-campaign-wpforms').'</a>';
            return $links;
        }
    }
    new ACWPFORMS_Integration();
}
