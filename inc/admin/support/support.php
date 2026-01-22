<?php
/**
* Liquid Themes Theme Framework
* The Liquid_Admin_Import class
*/

if ( !defined( 'ABSPATH' ) )
	exit; // Exit if accessed directly

class Liquid_Admin_Support extends Liquid_Admin_Page {

	/**
	 * [__construct description]
	 * @method __construct
	 */
	public function __construct() {

		$this->id = 'docy-support';
		$this->page_title = esc_html__( 'Liquid Support', 'docy' );
		$this->menu_title = esc_html__( 'Support', 'docy' );
		$this->parent = 'docy';
		$this->position = '15';

		parent::__construct();
	}

	/**
	 * [display description]
	 * @method display
	 * @return [type]  [description]
	 */
	public function display() {
		include_once( get_template_directory() . '/liquid/admin/views/liquid-support.php' );
	}

	/**
	 * [sdocy description]
	 * @method sdocy
	 * @return [type] [description]
	 */
	public function sdocy() {

	}
}
new Liquid_Admin_Support;