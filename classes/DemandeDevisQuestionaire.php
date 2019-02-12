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
}