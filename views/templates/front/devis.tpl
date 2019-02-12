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
  <div class="container">
    <header>
          <h1 class="h3">{l s='Send a Devis' d='Modules.demandedevis'}</h1>
          <p>{l s='If you would like to add a comment about your order, please write it in the field below.' d='Modules.Contactform.Shop'}</p>
      </header> {* *}
      <button name="submitNewsletter" class="js-hide-modal1 btn btn-success" > text</button>
        <form action="{$urls.pages.index}" method="post">
            <div class="form-group">
                <label for="email">{l s='Order reference' d='Modules.Contactform.Shop'}</label>
                <select name="id_order">
                    <option value="">{l s='Select reference' d='Modules.Contactform.Shop'}</option>
                    {foreach from=$orders item=order}
                        <option value="{$order.id_product}">{$order.name}</option>
                    {/foreach}
                </select>
            </div>
            {*<input type="text" name="email" value="{$value}" placeholder="{l s='Your e-mail' d='Modules.Emailsubscription.Shop'}" />
          *}  {if $conditions}
                <p>{$conditions}</p>
            {/if}
           {* <input type="submit" value="ok" name="submitNewsletter" class="test btn btn-success" />*}

            <input type="hidden" name="action" value="0" />
        </form>
    </div>
    <script type="text/javascript">
    {literal}
    $('.js-hide-modal1').on('click',function(){
        $('.h3').addClass('hide');
        alert("text");
    });
   /* $(document).ready(function()
    {
        alert("text");
        $('.js-hide-modal1').on('click',function(){
            $('.h3').addClass('hide');
            alert("text");
        });
    });*/
    {/literal}
</script>
{/block}
