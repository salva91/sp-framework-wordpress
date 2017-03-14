<?php

namespace salva_powa;

class sp_module
{
	/**
	 * the name of the module
	 * @var string
	 */
	var $name;
	/**
	 * the version of the module
	 * @var string
	 */
	var $version;
	/**
	 * the name of author
	 * @var string
	 */
	var $author;
	/**
	 * The font awesome icon
	 * @var string
	 */
	var $icon;
	/**
	 * The name un the menu
	 * @var string
	 */
	var $menu_name;
	/**
	 * the patch of the class
	 * @var string
	 */
	var $file_path;

	function __construct()
	{
			$this->file_path = dirname(__FILE__);
	}
	/**
	 * Use the framework twig like template motor
	 * @param  [string] $template_name the name of file html
	 * @param  [array] $array_info  the array content informations for the doc html
	 * @return [string]   return the file html with information of array
	 */
	function twig_render( $template_name, $array_info)
	{

    if ( !isset( $this->twig ) )
				$this->twig_constructor();

		return  $this->twig->render( $template_name, $array_info );

	}
	// construct the motor twig
	function twig_constructor()
	{
		\Twig_Autoloader::register();

		$loader = new \Twig_Loader_Filesystem( $this->dir_file_class() . '/template'); // Dossier contenant les templates

    $this->twig = new \Twig_Environment($loader, array(
      'cache' => false
    ));

	}
	/**
	 * Return le path of extend class
	 * @return [string] Return le path of extend class
	 */
	function dir_file_class()
	{
		$a = new \ReflectionClass($this);
		return dirname( $a->getFileName() );
	}
}