<?php
/**
 * Class sp_core
 */

namespace Chocolatine;

class Core
{
			/**
			 * __construct first action
			 */
			public static $sp_core;
			/**
			 *  Name of the application
			 * @var string
			 */
			public $application;
			/**
			 *  this is the root folder
			 * @var string
			 */
			var $path_folder;
			/**
			 * this a web url
			 * @var string
			 */
			var $url_folder;
			/**
			* The etat of core, he can have three etat
			* front | api | admin
			* @var string
			 */
			var $etat = 'front';
			/**
			* The default is false, if i true than is dev mode
			* @var boolean
			 */
			var $is_dev = false;
			/**
			 * [$manager contain the managers]
			 * @var [stdclass]
			 */
			var $manager;

			public function __construct( $pathApplication )
			{
					$this->path_folder = dirname( dirname(__FILE__) );
					$this->pathApplication = $pathApplication;
			}
			/**
			 * Function create the core
			 * @return object Create the core
			 */
			public static function create_core(){
					self::$sp_core = new Core();
					return self::$sp_core->init();
			}
			public static function get_core(){
					return self::$sp_core;
			}
			/**
			 * This function load all modules
			 */
			public function init()
			{
					$this->manager = new Managers\ManagerMaster();
					/**
					 * Find and load all Managers
					 */
					$this->manager->loadManager();
					/**
					 * Find and load all Modules
					 */
					$this->manager->module->search_modules();

					$router = get_service('Router');
					/**
					 *  Declare and Apllic all routes http
					 */
					$router->declare_routes();

					$router->use_routes();
			}

}
