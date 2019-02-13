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
$(document).ready(function () {
    var $searchWidget = $('#url');
    //var $searchBox    = $("select[class=dd_select]", $element).val()
    var searchURL     = $searchWidget.attr('data-search-controller-url');
    //getQuestion(3);
    $('.dd_select').on('change',function(){
        var $searchBox    = $(".dd_select").val();
        //$( this ).val();
        //alert($searchBox);
       // $('.contener').append('<div>hello</div>');
        $.ajax({
            url : searchURL,
            type : 'GET',
            dataType : 'json',
            data:{
                action_name:'action_name',
                id_product:$searchBox
            },
            success : function(resultat, statut){ // success est toujours en place, bien sûr !
                console.log(resultat);
                $( ".question" ).remove();
                $.each( resultat, function( index, value ){

                    $('.contener').append('</br><div class="col-md-6 question" id="quest'+value.id_questionnaireDevis+'">'+value.libelle+'</div>');
                    getQuestion(value.id_questionnaireDevis);
                });
            },

            error : function(resultat, statut, erreur){

            }

        });
    });
    function getQuestion(question){
        $.ajax({
            url : searchURL,
            type : 'GET',
            dataType : 'json',
            data:{
                    action_reponse:'action_reponse',
                    id_question:question
                },
            success : function(resultats, statut){ // success est toujours en place, bien sûr !
                console.log(resultats);
                $('#quest'+question).append('<select class="form-control" id="response'+question+'">');
               // $('#quest'+question).append("<option value='"+res[i].id_fonction+"'>" + res[i].lib_fonction);
                $.each( resultats, function( index, value ){
                   // alert(value.id_reponse_question);
                    //$('#reponse'+question).append("<a>");
                    $('#response'+question).append("<option value='"+value.id_reponse_question+"'>" + value.libelle);
                });
               /* let $input=$(resultats).find('#response'+question);

                $('#response'+question).replaceWith($input);*/
                /*$.each( resultats, function( index, value ){
                    $('.contener').append('</br><div class="col-md-6 reponse" id="reponse'+value.id_questionnaireDevis+'">'+value.libelle+'</div>');
                    // Append the text to <h1>
                });*/
            },

            error : function(resultat, statut, erreur){

            }

        });
    }
    $('.js-hide-modal1').on('click',function(){
        /*$('.h3').addClass('hide');*/
        //alert(searchURL);
        $.ajax({
            url : searchURL,
            type : 'GET',
            dataType : 'json',
            data:'action_name',
            success : function(resultat, statut){ // success est toujours en place, bien sûr !
                console.log(resultat);
               // swal(nameProduct, "is added to cart !", "success");
            },

            error : function(resultat, statut, erreur){

            }

        });
    });
    /*$searchBox.psBlockSearchAutocomplete({
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
    });*/
});