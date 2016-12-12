<?php
/*
Plugin Name: Shortcode Anywhere for Contact Form 7 
Plugin URI: https://github.com/seregahere/shortcode-anywhere-for-contact-form-7
Description: Enable shortcodes for Contact Form 7, for form and mail fields.
Author: Siarhei Razuvalau
Version: 0.1
License: GPLv2 or later
*/

if( !defined( 'ABSPATH' ) ) {
        exit( 'You are not allowed to access this file directly.' );
}


class WPCF7ShortcodeAnywhere
{
	
	protected static $instances = array();
	private $handlingFields = array('body');

    protected function __construct()
    {
		add_action('init', array(&$this, 'init'));
    }


	public static function getInstance()
    {
        $className = get_called_class();
        if (!isset(static::$instances[$className])) {
            static::$instances[$className] = new $className();
        }

        return static::$instances[$className];
    }

    public function init()
    {
        add_filter('wpcf7_form_elements', array(&$this, 'doShortCodeInForm'), 10, 1);
        add_filter('wpcf7_mail_components', array(&$this, 'doShortCodeMailFields'), 10, 3);
    }

    public function doShortCodeInForm($form)
    {
        return do_shortcode($form);
    }

    public function doShortCodeMailFields($components, $contactForm, $mailComponent)
    {
		foreach ($this->handlingFields as $field) {
		    if (array_key_exists($field, $components)) {
		            $components[$field] = do_shortcode($components[$field]);
		    }
		}

		return $components;
    }

	
}

WPCF7ShortcodeAnywhere::getInstance();

