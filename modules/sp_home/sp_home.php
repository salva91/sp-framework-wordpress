<?php

use \salva_powa\sp_module;

class sp_home extends sp_module
{
		/**
		 * The content of menu left
		 * @var array
		 */
		var $menu_left = array();
		/**
		 * The content of menu top
		 * @var array
		 */
		var $menu_top = array();

		/**
		* The arguments for create template
		* @var array
		 */
		var $template_args = array();
		/**
		 * [__construct]
		 */
    function __construct()
    {

        $this->icon = 'fa-home';
				$this->name = 'Home';
				$this->slug = 'sp_home';
				$this->description = "the home for the back of the salva_back";
				$this->show_in_menu = true;
				$this->menu_position = 0;

    }
    public function view_back_sp()
    {
				//	Generate the loader during the chargement
				$this->generate_view_loader();

			// the instance of the sp_template
			$view = sp_get_module('sp_template');

			// convertie the current module in js
			$this->convert_in_js( 'current_module',
					array(
						'name' => $this->current_module->name,
						'slug' => $this->current_module->slug,
						'categorie' => $this->current_module->categorie
					)
			);

			if ( !empty( $this->current_sub_module )) {
				$current_sub_module = array(
					'name' => $this->current_sub_module['name'],
					'slug' => $this->current_sub_module['slug']
				);
			}
			else
			{
				$current_sub_module = false;
			}
			// convertie the current_sub_module module in js
			$this->convert_in_js( 'current_sub_module', $current_sub_module );

			$this->add_module_js( 'sp_home.js' );
			$this->add_module_css( 'sp_home.css' );

			$this->generate_header();

			$this->generate_menu_top();

			$this->generate_menu_left();

			$this->generate_content();

			$this->generate_javascript_var();

			$view->args = $this->template_args;

			$view->generate( array( 'angular_apps' => 'app_sp_powa' ) );

    }
		function generate_javascript_var()
		{
				echo $this->twig_render( 'convert_in_js.html', array( 'convert_in_js' => $this->core->ajax->convert_in_js ) ) ;
		}
		function view_back()
		{
			return $this->twig_render( 'home.html', array(
				'list_modules' => $this->core->modules->list_modules
			));
		}
		function generate_header()
		{

			$this->template_args['header'] = [
				'second_title' => 'Module : ' . $this->current_module->name,
				'img_backgroung' => $this->core->url_folder . '/assets/img/header.jpg'
			];

		}
		function generate_menu_top()
		{

				if ( empty( $this->current_module->sub_module ) )
								return false;

				foreach ( $this->current_module->sub_module as $key => $value) {

					$args = $value;

					if( $args['show_in_menu'] )
								$this->menu_top []= $args;

				}

				if ( empty( $this->current_sub_module['slug'] ) ) {
					  $this->menu_top[ 0 ]['selected'] = true;
				}
				else
				{
						$find = array_find( $this->menu_top, 'slug',  $this->current_sub_module['slug'] );
						$this->menu_top[ $find ]['selected'] = true;
				}

				$this->template_args['top_content'] = array(
					'content' => [array(
						'id' => 'top_admin_salva_powa',
						'url' => $this->twig_render(
							'menu_top.html',
							array( 'menu_list' => $this->menu_top)
						),
						'method' =>'echo'
					)]
				);

		}
		function generate_menu_left()
		{

			$menu_left['logo_url'] =  $this->core->url_folder . '/assets/img/logo-salva-powa.png';
			$menu_left['site_name'] =  get_bloginfo( 'name');

			$this->template_args['menu_left'] = array(
				'content' => [array(
					'id' => 'col_right',
					'url' => $this->twig_render( 'menu_left.html', $menu_left),
					'method' =>'echo'
				)]
			);

			$menu_left_js = array();

			foreach ( $this->core->modules->list_modules as $current_module ) {

					$current_item = array(
						'slug' => $current_module->slug,
						'name' => $current_module->name,
						'url'  => $current_module->url,
						'icon' => $current_module->icon,
						'categorie' => $current_module->categorie,
						'show_in_menu' => $current_module->show_in_menu
					);

					if ( !empty( $current_module->sub_module ) ) {

							$current_item['sub_module'] = array();

							foreach ( $current_module->sub_module as $key => $current_sub_module ) {

									$current_sub_item = array(
										'slug' => $current_sub_module['slug'],
										'name' => $current_sub_module['name'],
										'url'  => $current_sub_module['url']
									);

									$current_item['sub_module'][] = $current_sub_item;
							}
					}

					$menu_left_js []= $current_item;
			}
			$this->convert_in_js( 'menu_left', $menu_left_js );

			$this->menu_left = $menu_left;

		}
		function generate_content()
		{

			if ( !empty( $this->current_sub_module['slug'] ) ) {

				$call_back = $this->current_sub_module['call_back'];
				$content = $this->current_module->$call_back();

			}
			else{
				$content = $this->current_module->view_back();
			}

			$this->template_args['content'] = array(
				'container' => 'fluid-container',
				'main_content' => [array(
					'url' => $content,
					'method' =>'echo',
				)]
			);

		}
		public function generate_view_loader()
		{
			echo "
				<div class='uil-ring-css' id='animation_loader'>
					<div>
					</div>
				</div>
			";
		}

}
