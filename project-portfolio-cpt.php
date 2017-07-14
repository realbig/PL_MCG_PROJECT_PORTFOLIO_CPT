<?php
/**
 * Plugin Name: Project Portfolio CPT
 * Plugin URI: https://github.com/realbig/PL_MCG_PROJECT_PORTFOLIO_CPT/
 * Description: Creates the Project Portfolio CPT and its associated Frontend Templates (Overridable by the Parent/Child Theme)
 * Version: 0.1.0
 * Text Domain: mcg-project-portfolio-cpt
 * Author: Eric Defore
 * Author URI: https://realbigmarketing.com/
 * Contributors: d4mation
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'MCG_PROJECT_PORTFOLIO_CPT' ) ) {

	/**
	 * Main MCG_PROJECT_PORTFOLIO_CPT class
	 *
	 * @since	  1.0.0
	 */
	class MCG_PROJECT_PORTFOLIO_CPT {
		
		/**
		 * @var			MCG_PROJECT_PORTFOLIO_CPT $plugin_data Holds Plugin Header Info
		 * @since		1.0.0
		 */
		public $plugin_data;
		
		/**
		 * @var			MCG_PROJECT_PORTFOLIO_CPT $admin_errors Stores all our Admin Errors to fire at once
		 * @since		1.0.0
		 */
		private $admin_errors;
		
		/**
		 * @var			MCG_CPT_Project $cpt Holds the CPT and its related functions
		 * @since		1.0.0
		 */
		public $cpt;

		/**
		 * Get active instance
		 *
		 * @access	  public
		 * @since	  1.0.0
		 * @return	  object self::$instance The one true MCG_PROJECT_PORTFOLIO_CPT
		 */
		public static function instance() {
			
			static $instance = null;
			
			if ( null === $instance ) {
				$instance = new static();
			}
			
			return $instance;

		}
		
		protected function __construct() {
			
			$this->setup_constants();
			$this->load_textdomain();
			
			if ( version_compare( get_bloginfo( 'version' ), '4.4' ) < 0 ) {
				
				$this->admin_errors[] = sprintf( _x( '%s requires v%s of %s or higher to be installed!', 'Outdated Dependency Error', 'mcg-project-portfolio-cpt' ), '<strong>' . $this->plugin_data['Name'] . '</strong>', '4.4', '<a href="' . admin_url( 'update-core.php' ) . '"><strong>WordPress</strong></a>' );
				
				if ( ! has_action( 'admin_notices', array( $this, 'admin_errors' ) ) ) {
					add_action( 'admin_notices', array( $this, 'admin_errors' ) );
				}
				
				return false;
				
			}
			
			$this->require_necessities();
			
			// Register our CSS/JS for the whole plugin
			add_action( 'init', array( $this, 'register_scripts' ) );
			
		}

		/**
		 * Setup plugin constants
		 *
		 * @access	  private
		 * @since	  1.0.0
		 * @return	  void
		 */
		private function setup_constants() {
			
			// WP Loads things so weird. I really want this function.
			if ( ! function_exists( 'get_plugin_data' ) ) {
				require_once ABSPATH . '/wp-admin/includes/plugin.php';
			}
			
			// Only call this once, accessible always
			$this->plugin_data = get_plugin_data( __FILE__ );

			if ( ! defined( 'PL_MCG_PROJECT_PORTFOLIO_CPT_VER' ) ) {
				// Plugin version
				define( 'PL_MCG_PROJECT_PORTFOLIO_CPT_VER', $this->plugin_data['Version'] );
			}

			if ( ! defined( 'PL_MCG_PROJECT_PORTFOLIO_CPT_DIR' ) ) {
				// Plugin path
				define( 'PL_MCG_PROJECT_PORTFOLIO_CPT_DIR', plugin_dir_path( __FILE__ ) );
			}

			if ( ! defined( 'PL_MCG_PROJECT_PORTFOLIO_CPT_URL' ) ) {
				// Plugin URL
				define( 'PL_MCG_PROJECT_PORTFOLIO_CPT_URL', plugin_dir_url( __FILE__ ) );
			}
			
			if ( ! defined( 'PL_MCG_PROJECT_PORTFOLIO_CPT_FILE' ) ) {
				// Plugin File
				define( 'PL_MCG_PROJECT_PORTFOLIO_CPT_FILE', __FILE__ );
			}

		}

		/**
		 * Internationalization
		 *
		 * @access	  private 
		 * @since	  1.0.0
		 * @return	  void
		 */
		private function load_textdomain() {

			// Set filter for language directory
			$lang_dir = PL_MCG_PROJECT_PORTFOLIO_CPT_DIR . '/languages/';
			$lang_dir = apply_filters( 'mcg_project_portfolio_cpt_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale', get_locale(), 'mcg-project-portfolio-cpt' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'mcg-project-portfolio-cpt', $locale );

			// Setup paths to current locale file
			$mofile_local   = $lang_dir . $mofile;
			$mofile_global  = WP_LANG_DIR . '/mcg-project-portfolio-cpt/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/mcg-project-portfolio-cpt/ folder
				// This way translations can be overridden via the Theme/Child Theme
				load_textdomain( 'mcg-project-portfolio-cpt', $mofile_global );
			}
			else if ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/mcg-project-portfolio-cpt/languages/ folder
				load_textdomain( 'mcg-project-portfolio-cpt', $mofile_local );
			}
			else {
				// Load the default language files
				load_plugin_textdomain( 'mcg-project-portfolio-cpt', false, $lang_dir );
			}

		}
		
		/**
		 * Include different aspects of the Plugin
		 * 
		 * @access	  private
		 * @since	  1.0.0
		 * @return	  void
		 */
		private function require_necessities() {
			
			require_once PL_MCG_PROJECT_PORTFOLIO_CPT_DIR . '/core/cpt/class-cpt-mcg-project.php';
			$this->cpt = new MCG_CPT_Project();
			
		}
		
		/**
		 * Show admin errors.
		 * 
		 * @access	  public
		 * @since	  1.0.0
		 * @return	  HTML
		 */
		public function admin_errors() {
			?>
			<div class="error">
				<?php foreach ( $this->admin_errors as $notice ) : ?>
					<p>
						<?php echo $notice; ?>
					</p>
				<?php endforeach; ?>
			</div>
			<?php
		}
		
		/**
		 * Register our CSS/JS to use later
		 * 
		 * @access	  public
		 * @since	  1.0.0
		 * @return	  void
		 */
		public function register_scripts() {
			
			wp_register_style(
				'mcg-project-portfolio-cpt',
				PL_MCG_PROJECT_PORTFOLIO_CPT_URL . 'assets/css/style.css',
				null,
				defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : PL_MCG_PROJECT_PORTFOLIO_CPT_VER
			);
			
			wp_register_script(
				'mcg-project-portfolio-cpt',
				PL_MCG_PROJECT_PORTFOLIO_CPT_URL . 'assets/js/script.js',
				array( 'jquery' ),
				defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : PL_MCG_PROJECT_PORTFOLIO_CPT_VER,
				true
			);
			
			wp_localize_script( 
				'mcg-project-portfolio-cpt',
				'mcGProjectPortfolioCPT',
				apply_filters( 'mcg_project_portfolio_cpt_localize_script', array() )
			);
			
			wp_register_style(
				'mcg-project-portfolio-cpt-admin',
				PL_MCG_PROJECT_PORTFOLIO_CPT_URL . 'assets/css/admin.css',
				null,
				defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : PL_MCG_PROJECT_PORTFOLIO_CPT_VER
			);
			
			wp_register_script(
				'mcg-project-portfolio-cpt-admin',
				PL_MCG_PROJECT_PORTFOLIO_CPT_URL . 'assets/js/admin.js',
				array( 'jquery' ),
				defined( 'WP_DEBUG' ) && WP_DEBUG ? time() : PL_MCG_PROJECT_PORTFOLIO_CPT_VER,
				true
			);
			
			wp_localize_script( 
				'mcg-project-portfolio-cpt-admin',
				'mcGProjectPortfolioCPT',
				apply_filters( 'mcg_project_portfolio_cpt_localize_admin_script', array() )
			);
			
		}
		
	}
	
} // End Class Exists Check

/**
 * The main function responsible for returning the one true MCG_PROJECT_PORTFOLIO_CPT
 * instance to functions everywhere
 *
 * @since	  1.0.0
 * @return	  \MCG_PROJECT_PORTFOLIO_CPT The one true MCG_PROJECT_PORTFOLIO_CPT
 */
add_action( 'plugins_loaded', 'mcg_project_portfolio_cpt_load' );
function mcg_project_portfolio_cpt_load() {

	require_once __DIR__ . '/core/project-portfolio-cpt-functions.php';
	MCGPROJECTPORTFOLIOCPT();

}
