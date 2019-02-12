/**
* 2007-2019 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/
alert("text2");
$(document).ready(function () {
    $('.js-hide-modal1').on('click',function(){
        $('.h3').addClass('hide');
        alert("text");
    });
    $('.test').each(function(){
        $(this).on('click', function(){
            alert("text");
         /*   $.ajax({
                url : '',
                type : 'POST',
                dataType : 'json',
                data:{
                    quantity:$('.num-product').val(),
                    name:nameProduct
                },
                success : function(resultat, statut){ // success est toujours en place, bien sûr !
                    console.log(resultat);
                    swal(nameProduct, "is added to cart !", "success");
                },

                error : function(resultat, statut, erreur){

                }

            });*/

        });
    });
    $searchBox.psBlockSearchAutocomplete({
        source: function (query, response) {
            $.post(searchURL, {
                s: query.term,
                resultsPerPage: 10
            }, null, 'json')
                .then(function (resp) {
                    response(resp.products);
                })
                .fail(response);
        },
        select: function (event, ui) {
            var url = ui.item.url;
            window.location.href = url;
        },
    });
});