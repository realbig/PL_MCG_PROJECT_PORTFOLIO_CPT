<?php
/**
 * Provides helper functions.
 *
 * @since	  1.0.0
 *
 * @package	PL_MCG_PROJECT_PORTFOLIO_CPT
 * @subpackage PL_MCG_PROJECT_PORTFOLIO_CPT/core
 */
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Returns the main plugin object
 *
 * @since		1.0.0
 *
 * @return		PL_MCG_PROJECT_PORTFOLIO_CPT
 */
function MCGPROJECTPORTFOLIOCPT() {
	return MCG_PROJECT_PORTFOLIO_CPT::instance();
}