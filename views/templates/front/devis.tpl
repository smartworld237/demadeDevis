{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{extends file='page.tpl'}
{block name='content'}
  <div class="container" id="url" data-search-controller-url="{$devis_controller_url}">
    <header>
          <h1 class="h3">{l s='Send a Devis' d='Modules.demandedevis'}</h1>
          <p>{l s='If you would like to add a comment about your order, please write it in the field below.' d='Modules.Contactform.Shop'}</p>
      </header> {* *}
      {*<button name="submitNewsletter" class="js-hide-modal1 btn btn-success" > text</button>*}
        <form action="{$urls.pages.index}" method="post">
            <div class=" contener">
                <div class="form-group col-md-6">
                    <label for="pwd">Question 1</label>
                    <input type="text" class="form-control" id="pwd1">
                </div>
                <div class="form-group col-md-6">
                    <label for="pwd">Question 2</label>
                    <input type="text" class="form-control" id="pwd2">
                </div>
                <div class="form-group col-md-6">
                    <label for="pwd">Question 2</label>
                    <input type="text" class="form-control" id="pwd3">
                </div>
                <div class="form-group col-md-6">
                    <label for="pwd">Question 2</label>
                    <input type="text" class="form-control" id="pwd4">
                </div>
                <div class="col-md-12">
                    <label for="id_order" class="">{l s='Product' d='Modules.demandedevis'}</label>
                <select name="id_order" class="form-control dd_select">
                    <option value="">{l s='Select reference' d='Modules.demandedevis'}</option>
                    {foreach from=$orders item=order}
                        <option value="{$order.id_product}">{$order.name}</option>
                    {/foreach}
                </select>
                </div>

{*            <div class="form-group">
                <label for="city" class="col-md-4 control-label">Ville :</label>
                <div class="col-md-6">
                    <select name="question" id="question" class="form-control"></select>
                </div>
            </div>*}
            {*<input type="text" name="email" value="{$value}" placeholder="{l s='Your e-mail' d='Modules.Emailsubscription.Shop'}" />
          *}
           {* <input type="submit" value="ok" name="submitNewsletter" class="test btn btn-success" />*}

            <button type="submit" class="btn btn-default">Submit</button>
            </div>
        </form>
  </br> </br> </br> </br> </br> </br>
    </div>
{/block}
