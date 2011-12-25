<?php
// vim: set ts=4 sw=4 sts=4 et:

/**
 * LiteCommerce
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to licensing@litecommerce.com so we can send you a copy immediately.
 * 
 * PHP version 5.3.0
 * 
 * @category  LiteCommerce
 * @author    Creative Development LLC <info@cdev.ru> 
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @see       ____file_see____
 * @since     1.0.15
 */

namespace XLite\Module\CDev\SimpleAttributeSearch\View;

/**
 * Search 
 *
 * @see   ____class_see____
 * @since 1.0.15
 */
class Search extends \XLite\View\ItemsList\Product\Customer\Search implements \XLite\Base\IDecorator
{
    /**
     * Widget param names
     */
    const PARAM_ATTRIBUTES = 'attributes';

    /**
     * Return search parameters
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.0
     */
    static public function getSearchParams()
    {
        $list = parent::getSearchParams();
        $list[\XLite\Model\Repo\Product::P_ATTRIBUTES] = static::PARAM_ATTRIBUTES;

        return $list;
    }

     /**
     * Define widget parameters
     *
     * @return void
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function defineWidgetParams()
    {
        parent::defineWidgetParams();

        $this->widgetParams[static::PARAM_ATTRIBUTES] = new \XLite\Model\WidgetParam\Collection('Attributes', array());
    }

    /**
     * Return all attribute groups
     *
     * @return array
     * @see    ____func_see____
     * @since  1.0.15
     */
    protected function getAttributeGroups()
    {
        return \XLite\Core\Database::getRepo('\XLite\Model\Attribute\Group')->findAll();
    }
}