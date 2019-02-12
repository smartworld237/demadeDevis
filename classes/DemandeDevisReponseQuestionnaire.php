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
}