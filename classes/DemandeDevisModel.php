<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 08/02/2019
 * Time: 12:44
 */

class DemandeDevisModel extends ObjectModel
{
    public $prix_total;
    public $id_reponse_question;
    public $id_client;
    public static $definition = array(
        'table' => 'demandeDevis',
        'primary' => 'id_demandeDevis',
        'multilang' => true,
        'fields' => array(
            'id_shop' =>			array('type' => self::TYPE_NOTHING, 'validate' => 'isUnsignedId'),
            // Lang fields
            'id_client' => array('type' => self::TYPE_INT),
            'id_reponse_question'=>array('type' => self::TYPE_INT),
            'prix_total'=>array('type' => self::TYPE_FLOAT)
        ),

    );
}