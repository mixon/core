{* vim: set ts=2 sw=2 sts=2 et: *}

{**
 * Main part of the search form
 *  
 * @author    Creative Development LLC <info@cdev.ru>
 * @copyright Copyright (c) 2011 Creative Development LLC <info@cdev.ru>. All rights reserved
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.litecommerce.com/
 * @since     1.0.15
 *
 * @ListChild (list="itemsList.product.search.form.options", weight="200")
 *}

{*displayViewListContent(#itemsList.product.search.form.attributes#)*}

<ul IF="getAttributeGroups()" class="advanced-search-options">
  <li FOREACH="getAttributeGroups(),group">
    {group.getTitle():h}

    <ul>
      <li FOREACH="group.getAttributes(),attribute">{attribute.getTitle():h}</li>
    </ul>
  </li>
</ul>