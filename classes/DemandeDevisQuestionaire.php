<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08/02/2019
 * Time: 12:46
 */

class DemandeDevisQuestionaire extends ObjectModel
{
    public $id_questionnaireDevis;
    public $id_produit;
    public static $definition = array(
        'table' => 'demandeDevisQuestionaire',
        'primary' => 'id_questionnaireDevis',
        'multilang' => true,
        'fields' => array(
            'id_produit' =>	array('type' => self::TYPE_NOTHING, 'validate' => 'isUnsignedId'),
            // Lang fields
            'libelle' => array('type' => self::TYPE_STRING,'lang' => true)
        ),

    );
    public static function getQuestionnaireByProduit($produit,$id_lang){
        //$id_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $sql = 'SELECT d.`id_questionnaireDevis`, dl.`libelle`
            FROM `' . _DB_PREFIX_ . 'demandeDevisQuestionaire`d
            LEFT JOIN `' . _DB_PREFIX_ . 'product` p ON (p.`id_product` = d.`id_produit`)
            LEFT JOIN '._DB_PREFIX_.'demandeDevisQuestionaire_lang dl ON (d.id_questionnaireDevis = dl.id_questionnaireDevis)
           where dl.id_lang = '.$id_lang.' and p.id_product ='.$produit;
        $sql1='SELECT *
            FROM `' . _DB_PREFIX_ . 'demandeDevisQuestionaire`';


        $content = Db::getInstance()->executeS($sql);

        return $content;
}
}