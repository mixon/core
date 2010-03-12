<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * CMS connector
 *  
 * @category  Litecommerce
 * @package   Core
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2009 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://www.qtmsoft.com/xpayments_eula.html X-Payments license agreement
 * @version   SVN: $Id$
 * @link      http://www.qtmsoft.com/
 * @see       ____file_see____
 * @since     3.0.0 EE
 */

/**
 * Singleton to connect to a CMS
 *                         
 * @package    Core
 * @since      3.0                   
 */
abstract class XLite_Core_CMSConnector extends XLite_Base implements XLite_Base_ISingleton
{
    /**
     * Current CMS name
     * 
     * @var    booln
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0 EE
     */
    protected static $currentCMS = null;

	/**
	 * List of widgets which can be exported
	 * 
	 * @var    array
	 * @access protected
	 * @since  3.0
	 */
	protected $widgetsList = array(
		'XLite_View_TopCategories'    => 'Categories list',
        'XLite_View_Minicart'         => 'Minicart',
        'XLite_View_Subcategories'    => 'Subcategories',
        'XLite_View_CategoryProducts' => 'Category products list',
        'XLite_View_ProductBox'       => 'Product block',
	);

    /**
     * Page types 
     * 
     * @var    array
     * @access protected
     * @see    ____var_see____
     * @since  3.0.0
     */
    protected $pageTypes = array(
        'XLite_Controller_Customer_Category'  => 'Category page',
        'XLite_Controller_Customer_Product'   => 'Product page',
        'XLite_Controller_Customer_Cart'      => 'Shopping cart',
        'XLite_Controller_Customer_Checkout'  => 'Checkout',
        'XLite_Controller_Customer_OrderList' => 'Orders list',
    );

	/**
	 * List of CSS files to export 
	 * 
	 * @var    array
	 * @access protected
	 * @since  3.0
	 */
	protected $cssFiles = array();

    /**
     * List of Javascript files to export 
     * 
     * @var    array
     * @access protected
     * @since  3.0
     */
    protected $jsFiles = array('js/common.js');

    /**
     * Initialized instance of the XLite singleton 
     * 
     * @var    XLite
     * @access protected
     * @since  3.0.0 EE
     */
    protected $initializedApplication = null;


	/**
	 * It's not possible to instantiate this class using the "new" operator
	 * 
	 * @return void
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0 EE
	 */
	protected function __construct()
	{
	}

    /**
     * Return initialized instance of the XLite singleton 
     * 
     * @param array $request request data
     *  
     * @return XLite
     * @access protected
     * @since  3.0.0 EE
     */
    protected function runApplication(array $request = array())
    {
        if (!empty($request)) {
            XLite_Core_Request::getInstance()->mapRequest($request);
        }

        if (!isset($this->initializedApplication)) {
            $this->initializedApplication = XLite::getInstance();
            $this->initializedApplication->init();
        }

        return $this->initializedApplication;
    }

    /**
     * getFlags 
     * 
     * @return void
     * @access protected
     * @since  3.0.0 EE
     */
    protected function getFlags()
    {
        return array(XLite_View_Abstract::PARAM_IS_EXPORTED => true);
    }

	/**
	 * Prepare attributes before set them to a widget
	 * 
	 * @param array $attributes attributes to prepare
	 *  
	 * @return array
	 * @access protected
	 * @since  3.0.0 EE
	 */
	protected function prepareAttributes(array $attributes)
	{
        return $attributes + $this->getFlags();
	}


	/**
	 * Method to access the singleton 
	 * 
	 * @return XLite_Core_CMSConnector
	 * @access public
	 * @since  3.0
	 */
	public static function getInstance()
    {
        return self::_getInstance(__CLASS__);
    }

	/**
     * Return currently used CMS name
     *
     * @return string
     * @access public
     * @since  3.0.0 EE
     */
    abstract public function getCMSName();


    /**
     * Handler should called this function first to prevent any possible conflicts
     * 
     * @return void
     * @access public
     * @since  3.0.0 EE
     */
    public function init()
    {
        self::$currentCMS = $this->getCMSName();
    }

    /**
     * Determines if we export content into a CMS
     *
     * @return bool 
     * @access public
     * @since  3.0.0 EE
     */
    public static function isCMSStarted()
    {
        return isset(self::$currentCMS);
    }

    /**
     * Check if a widget requested from certain CMS
     *
     * @return bool
     * @access public
     * @since  3.0.0 EE
     */
    public function checkCurrentCMS()
    {
        return $this->getCMSName() === self::$currentCMS;
    }

	/**
	 * Return list of widgets which can be exported 
	 * 
	 * @return array
	 * @access public
	 * @since  3.0
	 */
	public function getWidgetsList()
	{
		return $this->widgetsList;
	}

    /**
     * Return object by class name 
     * 
     * @param string $name       widget class name
     * @param array  $attributes widget attributes
     *  
     * @return XLite_View_Abstract
     * @access public
     * @since  3.0.0 EE
     */
	public function getWidgetObject($name, array $attributes = array())
	{
        $result = null;

        if (class_exists($name)) {
            $result = XLite_Model_CachingFactory::getObject($name);
            $result->init($this->prepareAttributes($attributes));
        }

        return $result;
	}

	/**
	 * Validate widget arguments 
	 * 
	 * @param string $name       widget identifier
	 * @param array  $attributes widget attributes
	 *  
	 * @return array
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0 EE
	 */
	public function validateWidgetArguments($name, array $attributes)
	{
        $widget = $this->getWidgetObject($name, $attributes);

		return $widget ? $widget->validateAttributes($attributes) : array();
	}

	/**
     * Check if widget is visible or not
     *
     * @param string $name       widget identifier
     * @param array  $attributes widget attributes
     *
     * @return bool
     * @access public
     * @see    ____func_see____
     * @since  3.0.0 EE
     */
	public function isWidgetVisible($name, array $attributes)
	{
        $widget = $this->getWidgetObject($name, $attributes);

		return $widget ? $widget->isVisible() : false;
	}

	/**
	 * Return HTML code of a widget 
	 * 
	 * @param string $name           Widget name
	 * @param array  $attributes     Parameters list defined in CMS
     * @param array  $inputArguments Input arguments (request data) defined in CMS
	 * 
	 * @return string
	 * @access public
	 * @since  3.0
	 */
	public function getWidgetHTML($name, array $attributes = array(), array $inputArguments = array())
	{
        // Run the XLite singleton if it hasn't beed run yet
        $this->runApplication($inputArguments);

        XLite_View_Abstract::cleanupResources();

        return new XLite_Core_WidgetDataTransport($this->getWidgetObject($name, $attributes));
	}

	/**
	 * Prepare and return list of CSS files to export 
	 * 
	 * @return array
	 * @access public
	 * @since  3.0
	 */
	public function getCSSList()
	{
		return $this->prepareResources($this->cssFiles);
	}

    /**
     * Prepare and return list of Javascript files to export 
     * 
     * @return array
     * @access public
     * @since  3.0
     */
    public function getJSList()
    {
        return $this->prepareResources($this->jsFiles);
    }

	/**
	 * Set user data 
	 * 
	 * @param string  $email Email
	 * @param array   $data  User data
	 *  
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0 EE
	 */
	public function setUserData($email, array $data)
	{
        $result = false;

		// Translation profile field names
        $transTable = $this->getUserTranslationTable();

        $transData = array();
        foreach ($transTable as $k => $v) {
            if (isset($data[$k])) {
                $transData[$v] = $data[$k];
            }
        }

        $profile = new XLite_Model_Profile();

    	if ($profile->find('login = \'' . addslashes($email) . '\'')) {

			// Update
			if ($transData) {
	            $profile->modifyProperties($transData);
				$result = (bool)$profile->update();
			}

		} else {

			// Create
			$transData['login'] = $email;
			$profile->modifyProperties($transData);
			$result = (bool)$profile->create();
		}

        return $result;
	}

    /**
     * Remove user profile
     * 
     * @param string $email Email
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0 EE
     */
    public function removeUser($email)
    {
		$result = false;

        $profile = new XLite_Model_Profile();

        if ($profile->find('login = \'' . addslashes($email) . '\'')) {
			$profile->delete();
			$result = true;
		}

		return $result;
    }

	/**
	 * Log-in user in LC 
	 * 
	 * @param string $email Email
	 *  
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0 EE
	 */
	public function logInUser($email)
	{
		$profile = XLite_Model_Auth::getInstance()->loginSilent($email);
        
		return !is_int($profile) || ACCESS_DENIED !== $profile;
	}

	/**
	 * Log-out user in LC 
	 * 
	 * @param string $email User email
	 *  
	 * @return void
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0 EE
	 */
	public function logOutUser($email = null)
	{
		XLite_Model_Auth::getInstance()->logoff();
	}

	/**
	 * Run a controller
	 *
	 * @param string $target controller target
	 * @param string $action controller action
	 * @param array  $args   controller arguments
	 *
	 * @return string
	 * @see    ____func_see____
	 * @since  3.0.0 EE
	 */
	public function runFrontController($target, $action, array $args = array())
	{
        $args = array(
            'target' => $target,
            'action' => $action,
        ) + $this->prepareAttributes($args);

        $application = $this->runApplication($args);
        $application->runController();
        $viewer = $application->getViewer();
        $viewer->init(array('template' => 'center_top.tpl') + $args);

        return new XLite_Core_WidgetDataTransport($viewer);
	}

	/**
	 * Get session TTL (in seconds) 
	 * 
	 * @return integer
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0 EE
	 */
	public function getSessionTtl()
	{
		return $this->session->getTtl();
	}

	/**
	 * Get landing link 
	 * 
	 * @return string
	 * @access public
	 * @see    ____func_see____
	 * @since  3.0.0 EE
	 */
	public function getLandingLink()
	{
	}

    /**
     * Get page types 
     * 
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageTypes()
    {
        return $this->pageTypes;
    }

    /**
     * Check - valid page instance settings or not
     * 
     * @param string $type     Page type code
     * @param array  $settings Page instance settings
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function checkPageInstanceSettings($type, array $settings)
    {
        $type = $this->getPageTypeObject($type);

        return $type ? $type->validatePageTypeAttributes($settings) : array();
    }

    /**
     * Check - visible page instance or not
     * 
     * @param string $type     Page type code
     * @param array  $settings Page instance settings
     *  
     * @return boolean
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function isPageInstanceVisible($type, array $settings)
    {
        $result = false;

        $type = $this->getPageTypeObject($type);

        if ($type) {
            $type->setAttributes($this->prepareAttributes($settings));
            $result = $type->isPageInstanceVisible();
        }

        return $result;
    }

    /**
     * Get page instance data 
     * 
     * @param string $type     Page type code
     * @param array  $settings Page instance settings
     *  
     * @return array
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageInstanceLink($type, array $settings)
    {
        $result = array(null, null);

        $type = $this->getPageTypeObject($type);

        if ($type) {
            $application = $this->runApplication($this->prepareAttributes($settings));
            $type->init();
            $result = $type->getPageInstanceData();
        }

        return $result;
    }

    /**
     * Get page type object 
     * 
     * @param string $type Class name
     *  
     * @return XLite_Controller_Abstract
     * @access public
     * @see    ____func_see____
     * @since  3.0.0
     */
    public function getPageTypeObject($type)
    {
        return class_exists($type) ? new $type : null;
    }

	/**
	 * Get translation table for profile data
	 * 
	 * @return array
	 * @access protected
	 * @see    ____func_see____
	 * @since  3.0.0 EE
	 */
	protected function getUserTranslationTable()
	{
		return array();
	}

    /**
     * Prepare widget resources 
     * 
     * @param array $resources Resources
     *  
     * @return array
     * @access protected
     * @see    ____func_see____
     * @since  3.0.0
     */
    protected function prepareResources(array $resources)
    {
        return $resources;
    }
}

