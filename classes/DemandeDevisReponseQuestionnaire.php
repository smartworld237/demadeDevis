<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08/02/2019
 * Time: 12:47
 */

class DemandeDevisReponseQuestionnaire extends ObjectModel
{
    public $id_questionnaireDevis;
    public $id_reponse_question;
    public static $definition = array(
        'table' => 'demandeDevisReponse',
        'primary' => 'id_reponse_question',
        'multilang' => true,
        'fields' => array(
            'id_questionnaireDevis' =>array('type' => self::TYPE_NOTHING, 'validate' => 'isUnsignedId'),
            // Lang fields
            'libelle' => array('type' => self::TYPE_STRING,'lang' => true)
        ),

    );
    public static function getReponseByQuestion($question,$id_lang){
        //$id_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $sql = 'SELECT d.`id_reponse_question`, dl.`libelle`
            FROM `' . _DB_PREFIX_ . 'demandeDevisReponse`d
            LEFT JOIN '._DB_PREFIX_.'demandeDevisReponse_lang`dl` ON (d.`id_reponse_question` = dl.`id_reponse_question`)
           where dl.id_lang ='.$id_lang.' and d.id_questionnaireDevis='.$question ;
        $sql1 = 'SELECT *
            FROM ' . _DB_PREFIX_ . 'demandeDevisReponse';


        $content = Db::getInstance()->executeS($sql);

        return $content;
    }
}