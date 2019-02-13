<?php
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
*/

if (!defined('_PS_VERSION_')) {
    exit;
}
require_once _PS_MODULE_DIR_ . 'demandedevis/classes/DemandeDevisQuestionaire.php';
require_once _PS_MODULE_DIR_ . 'demandedevis/classes/DemandeDevisReponseQuestionnaire.php';
class DemandeDevis extends Module
{
    protected $config_form = false;
    private $html = '';
    public function __construct()
    {
        $this->name = 'demandedevis';
        $this->tab = 'others';
        $this->version = '1.0.0';
        $this->author = 'smartworld';
        $this->need_instance = 0;
        $this->controllers = array('devis');

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Demandez un Devis');
        $this->description = $this->l('demandez un un devis specific');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('DEMANDEDEVIS_LIVE_MODE', false);

        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayHome');
    }

    public function uninstall()
    {
        Configuration::deleteByName('DEMANDEDEVIS_LIVE_MODE');

        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitDemandeDevisModule')) == true) {
            $this->postProcess();
        }
        $output = '';

        $this->context->smarty->assign('module_dir', $this->_path);

     /*   $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderList();*/
        if (Tools::isSubmit('savequestionDevis')) {
                return $this->processSaveQuestion();
        } elseif (Tools::isSubmit('updatequestionDevis') || Tools::isSubmit('addquestionDevis')) {
            $this->html .= $this->renderForm();
            return $this->html;
        } else if (Tools::isSubmit('deletequestionDevis')) {
            Tools::redirectAdmin(AdminController::$currentIndex . '&configure=' . $this->name . '&token=' .
                Tools::getAdminTokenLite('AdminModules'));
        } elseif (Tools::isSubmit('savereponseDevis')) {
            $this->html .= $this->processSaveReponse();
        } elseif (Tools::isSubmit('updatereponseDevis') || Tools::isSubmit('addreponseDevis')) {
            $this->html .= $this->renderFormDevis();
            return $this->html;
        } else {
            //$this->html .= $this->renderList();
            $output .= $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');
            //return $this->html;
            return $output . $this->renderList(). $this->renderListQuestion().$this->renderListReponse();
        }
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderFormR()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitDemandeDevisModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            .'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }
    public function getProduitList()
    {
        $hooks = array();
            $id_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $sql = 'SELECT  pl.`name` as name,p.`id_product`
            FROM `' . _DB_PREFIX_ . 'product`p 
            LEFT JOIN '._DB_PREFIX_.'product_lang pl ON (p.id_product = pl.id_product) where pl.id_lang = '.$id_lang;


        $content = Db::getInstance()->executeS($sql);
        foreach ($content as $row=>$hook) {
            $hooks[$row]['key'] = $hook['id_product'];
            $hooks[$row]['name'] = $hook['name'];
        }
        //return $content;
        return $hooks;
    }
    public function getQuestionList()
    {
        $hooks = array();
        $id_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $sql = 'SELECT  pl.`libelle` as name,p.`id_questionnaireDevis`
            FROM `' . _DB_PREFIX_ . 'demandeDevisquestionaire`p 
            LEFT JOIN '._DB_PREFIX_.'demandeDevisquestionaire_lang pl ON (p.id_questionnaireDevis = pl.id_questionnaireDevis) where pl.id_lang = '.$id_lang;


        $content = Db::getInstance()->executeS($sql);
        foreach ($content as $row=>$hook) {
            $hooks[$row]['key'] = $hook['id_questionnaireDevis'];
            $hooks[$row]['name'] = $hook['name'];
        }
        //return $content;
        return $hooks;
    }
    protected function renderForm()
    {
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $produts = $this->getProduitList();
        $fields_form = array(
            'tinymce' => true,
            'legend' => array(
                'title' => $this->l('New Questionnaire product')
            ),
            'input' => array(
                'id_questionnaireDevis' => array(
                    'type' => 'hidden',
                    'name' => 'id_questionnaireDevis'
                ),
                array(
                    'type'    => 'select',
                    'label'   => $this->l('Select Produit'),
                    'name'    => 'id_produit',
                    'hint'    => $this->l('Select Produit'),
                    'options' => array(
                        'query' => $produts,
                        'id'    => 'key',
                        'name'  => 'name'
                    )
                ),
                array(
                    'type' => 'text',
                    'lang' => true,
                    'label' => $this->l('Question:'),
                    'name' => 'libelle',
                    'required' => true
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'name'=>'savequestionDevis'
            ),
            'buttons' => array(
                array(
                    'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                    'title' => $this->l('Back to list'),
                    'icon' => 'process-icon-back'
                )
            )
        );

        $helper                  = new HelperForm();
        $helper->module          = $this;
        //$helper->name_controller = 'faq';
        $helper->identifier      = $this->identifier;
        $helper->token           = Tools::getAdminTokenLite('AdminModules');
        foreach (Language::getLanguages(false) as $lang)
            $helper->languages[] = array(
                'id_lang' => $lang['id_lang'],
                'iso_code' => $lang['iso_code'],
                'name' => $lang['name'],
                'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
            ); /**/

        $helper->currentIndex             = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language    = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;/**/
        $helper->toolbar_scroll           = true;
        $helper->title                    = $this->displayName;
        $helper->submit_action            = 'savequestionDevis';

        $helper->fields_value = $this->getFormValues();

        return $helper->generateForm(array(
            array(
                'form' => $fields_form
            )
        ));
    }
    protected function renderFormDevis()
    {
        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        $questions = $this->getQuestionList();
        $fields_form = array(
            'tinymce' => true,
            'legend' => array(
                'title' => $this->l('New Reponse Questionnaire')
            ),
            'input' => array(
                'id_reponse_question' => array(
                    'type' => 'hidden',
                    'name' => 'id_reponse_question'
                ),
                array(
                    'type'    => 'select',
                    'label'   => $this->l('Select Questionnaire'),
                    'name'    => 'id_questionnaireDevis',
                    'hint'    => $this->l('Select Questionnaire'),
                    'options' => array(
                        'query' => $questions,
                        'id'    => 'key',
                        'name'  => 'name'
                    )
                ),
                array(
                    'type' => 'text',
                    'lang' => true,
                    'label' => $this->l('Reponse:'),
                    'name' => 'libelle',
                    'required' => true
                )
            ),
            'submit' => array(
                'title' => $this->l('Save'),
                'name'=>'savereponseDevis'
            ),
            'buttons' => array(
                array(
                    'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                    'title' => $this->l('Back to list'),
                    'icon' => 'process-icon-back'
                )
            )
        );

        $helper                  = new HelperForm();
        $helper->module          = $this;
        //$helper->name_controller = 'faq';
        $helper->identifier      = $this->identifier;
        $helper->token           = Tools::getAdminTokenLite('AdminModules');
       foreach (Language::getLanguages(false) as $lang)
            $helper->languages[] = array(
                'id_lang' => $lang['id_lang'],
                'iso_code' => $lang['iso_code'],
                'name' => $lang['name'],
                'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
            ); /**/

        $helper->currentIndex             = AdminController::$currentIndex . '&configure=' . $this->name;
        $helper->default_form_language    = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;/**/
        $helper->toolbar_scroll           = true;
        $helper->title                    = $this->displayName;
        $helper->submit_action            = 'savereponseDevis';

        $helper->fields_value = $this->getFormValuesReponse();

        return $helper->generateForm(array(
            array(
                'form' => $fields_form
            )
        ));
    }
    public function getFormValues()
    {
        $fields_value = array();
        $id_questionnaireDevis        = (int) Tools::getValue('id_questionnaireDevis');
        /*       $info                                             = new DemandeDevisQuestionaire((int) $id_questionnaireDevis);
         $fields_value['libelle'] = $info->libelle;
              $fields_value['id_produit']   = $info->id_produit;*/
        foreach (Language::getLanguages(false) as $lang) {
            if ($id_questionnaireDevis) {
                $info                                             = new DemandeDevisQuestionaire((int) $id_questionnaireDevis);
                $fields_value['libelle'][(int) $lang['id_lang']] = $info->libelle[(int) $lang['id_lang']];
                $fields_value['id_produit']   = $info->id_produit;
            } else {
                $fields_value['libelle'][(int) $lang['id_lang']] = Tools::getValue('libelle_' . (int) $lang['id_lang'], '');
                $fields_value['id_produit']   = Tools::getValue('id_produit');
            }
        }/**/

        $fields_value['id_questionnaireDevis'] = (int) Tools::getValue('id_questionnaireDevis');

        return $fields_value;
    }
    public function getFormValuesReponse()
    {
        $fields_value = array();
        $id_reponse_question        = (int) Tools::getValue('id_reponse_question');

        foreach (Language::getLanguages(false) as $lang) {
            if ($id_reponse_question) {
                $info                                             = new DemandeDevisReponseQuestionnaire((int) $id_reponse_question);
                $fields_value['libelle'][(int) $lang['id_lang']] = $info->libelle[(int) $lang['id_lang']];
                $fields_value['id_questionnaireDevis']   = $info->id_questionnaireDevis;
            } else {
                $fields_value['libelle'][(int) $lang['id_lang']] = Tools::getValue('libelle_' . (int) $lang['id_lang'], '');
                $fields_value['id_questionnaireDevis']   = Tools::getValue('id_questionnaireDevis');
            }
        }/**/

        $fields_value['id_reponse_question'] = (int) Tools::getValue('id_reponse_question');

        return $fields_value;
    }
    protected function renderList(){
        $this->fields_list          = array();
        $this->fields_list['id_demandeDevis'] = array(
            'title' => $this->l('id'),
            'type' => 'text',
            'search' => false,
            'orderby' => false
        );
        $this->fields_list['prix_total'] = array(
            'title' => $this->l('Prix Total'),
            'type' => 'text',
            'search' => false,
            'orderby' => false
        );
        $this->fields_list['client'] = array(
            'title' => $this->l('Client'),
            'type' => 'text',
            'search' => false,
            'orderby' => false
        );
        $helper = new HelperList();
        $helper->shopLinkType   = '';
        $helper->simple_header      = false;
        $helper->identifier         = 'id_demandeDevis';
        $helper->actions            = array(
            'edit',
            'delete'
        );
        $helper->show_toolbar       = true;
        $helper->imageType          = 'jpg';
       /* $helper->toolbar_btn['new'] = array(
            'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&add' . $this->name . '&token='
                . Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->l('Add new')
        )*/;

        $helper->title        = $this->displayName;
        $helper->table        = $this->name;
        $helper->token        = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        $content = $this->getListContent($this->context->language->id);

        return $helper->generateList($content, $this->fields_list);
    }
    protected function renderListQuestion(){
        $this->fields_list          = array();
        $this->fields_list['id_questionnaireDevis'] = array(
            'title' => $this->l('id'),
            'type' => 'text',
            'search' => false,
            'orderby' => false
        );
        $this->fields_list['libelle'] = array(
            'title' => $this->l('Libelle'),
            'type' => 'text',
            'search' => false,
            'orderby' => false
        );
        $this->fields_list['name'] = array(
            'title' => $this->l('Product'),
            'type' => 'text',
            'search' => false,
            'orderby' => false
        );
        $helper = new HelperList();
        $helper->shopLinkType   = '';
        $helper->simple_header      = false;
        $helper->identifier         = 'id_questionnaireDevis';
        $helper->actions            = array(
            'edit',
            'delete'
        );
        $helper->show_toolbar       = true;
        $helper->imageType          = 'jpg';
        $helper->toolbar_btn['new'] = array(
           /* 'href' => AdminController::$currentIndex . '&configure=' . $this->name .'addquestionDevis' . '&token='
                . Tools::getAdminTokenLite('AdminModules'),*/
            'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&addquestionDevis'. '&token='
                . Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->l('Add new')
        );

        $helper->title        = 'Questionnaire Devis';
        $helper->table        = $this->name;
        $helper->token        = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        $content = $this->getListContentQuestion($this->context->language->id);

        return $helper->generateList($content, $this->fields_list);
    }
    protected function renderListReponse(){
        $this->fields_list          = array();
        $this->fields_list['id_reponse_question'] = array(
            'title' => $this->l('id'),
            'type' => 'text',
            'search' => false,
            'orderby' => false
        );
        $this->fields_list['libelle'] = array(
            'title' => $this->l('Libelle'),
            'type' => 'text',
            'search' => false,
            'orderby' => false
        );
        $this->fields_list['question'] = array(
            'title' => $this->l('questionnaire'),
            'type' => 'text',
            'search' => false,
            'orderby' => false
        );
        $helper = new HelperList();
        $helper->shopLinkType   = '';
        $helper->simple_header      = false;
        $helper->identifier         = 'id_reponse_question';
        $helper->actions            = array(
            'edit',
            'delete'
        );
        $helper->show_toolbar       = true;
        $helper->imageType          = 'jpg';
        $helper->toolbar_btn['new'] = array(
            'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&addreponseDevis'. '&token='
                . Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->l('Add new')
        );

        $helper->title        = 'Reponses Questionnaire';
        $helper->table        = $this->name;
        $helper->token        = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&configure=' . $this->name;

        $content = $this->getListContentReponse($this->context->language->id);

        return $helper->generateList($content, $this->fields_list);
    }
    protected function getListContent($id_lang = null)
    {
        if (is_null($id_lang))
            $id_lang = (int) Configuration::get('PS_LANG_DEFAULT');

        $sql = 'SELECT d.`id_demandeDevis`, d.`prix_total`, d.`id_reponse_question`, c.`firstname` as client
            FROM `' . _DB_PREFIX_ . 'demandedevis`d LEFT JOIN `' . _DB_PREFIX_ . 'customer` c ON (d.`id_client` = c.`id_customer`) ' ;


        $content = Db::getInstance()->executeS($sql);

       /* foreach ($content as $key => $value) {
            $content[$key]['question'] = substr(strip_tags($value['question']), 0, 200);
        }*/

        return $content;
    }
    protected function getListContentQuestion($id_lang = null)
    {
        if (is_null($id_lang))
            $id_lang = (int) Configuration::get('PS_LANG_DEFAULT');

        $sql = 'SELECT d.`id_questionnaireDevis`, dl.`libelle`, d.`id_produit`, pl.`name` as name
            FROM `' . _DB_PREFIX_ . 'demandeDevisQuestionaire`d 
            LEFT JOIN `' . _DB_PREFIX_ . 'product` p ON (p.`id_product` = d.`id_produit`)
            LEFT JOIN '._DB_PREFIX_.'demandeDevisQuestionaire_lang dl ON (d.id_questionnaireDevis = dl.id_questionnaireDevis)
            LEFT JOIN '._DB_PREFIX_.'product_lang pl ON (p.id_product = pl.id_product) where pl.id_lang =dl.id_lang and pl.id_lang = '.$id_lang;


        $content = Db::getInstance()->executeS($sql);

        /* foreach ($content as $key => $value) {
             $content[$key]['question'] = substr(strip_tags($value['question']), 0, 200);
         }*/

        return $content;
    }
    protected function getListContentReponse($id_lang = null)
    {
        if (is_null($id_lang))
            $id_lang = (int) Configuration::get('PS_LANG_DEFAULT');

        $sql = 'SELECT d.`id_reponse_question`, dl.`libelle`, d.`id_questionnaireDevis`, ql.`libelle` as question
            FROM `' . _DB_PREFIX_ . 'demandeDevisReponse`d
             LEFT JOIN '._DB_PREFIX_.'demandeDevisReponse_lang `dl` ON (d.`id_reponse_question` = dl.`id_reponse_question`)
             LEFT JOIN '._DB_PREFIX_.'demandeDevisQuestionaire `q` ON (d.`id_questionnaireDevis` = q.`id_questionnaireDevis`)
             LEFT JOIN '._DB_PREFIX_.'demandeDevisQuestionaire_lang ql ON (q.id_questionnaireDevis = ql.id_questionnaireDevis)
             where ql.id_lang = dl.id_lang and  dl.id_lang ='.$id_lang ;


        $content = Db::getInstance()->executeS($sql);

        /* foreach ($content as $key => $value) {
             $content[$key]['question'] = substr(strip_tags($value['question']), 0, 200);
         }*/

        return $content;
    }
    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                'title' => $this->l('Settings'),
                'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Live mode'),
                        'name' => 'DEMANDEDEVIS_LIVE_MODE',
                        'is_bool' => true,
                        'desc' => $this->l('Use this module in live mode'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'col' => 3,
                        'type' => 'text',
                        'prefix' => '<i class="icon icon-envelope"></i>',
                        'desc' => $this->l('Enter a valid email address'),
                        'name' => 'DEMANDEDEVIS_ACCOUNT_EMAIL',
                        'label' => $this->l('Email'),
                    ),
                    array(
                        'type' => 'password',
                        'name' => 'DEMANDEDEVIS_ACCOUNT_PASSWORD',
                        'label' => $this->l('Password'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'DEMANDEDEVIS_LIVE_MODE' => Configuration::get('DEMANDEDEVIS_LIVE_MODE', true),
            'DEMANDEDEVIS_ACCOUNT_EMAIL' => Configuration::get('DEMANDEDEVIS_ACCOUNT_EMAIL', 'contact@prestashop.com'),
            'DEMANDEDEVIS_ACCOUNT_PASSWORD' => Configuration::get('DEMANDEDEVIS_ACCOUNT_PASSWORD', null),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }
    public function processSaveQuestion()
    {
        if ($id_questionnaireDevis = Tools::getValue('id_questionnaireDevis'))
            $info = new DemandeDevisQuestionaire((int) $id_questionnaireDevis);
        else {
            $info = new DemandeDevisQuestionaire();
        }

        $languages = Language::getLanguages(false);

        $text  = array();
        $text1 = array();

        foreach ($languages AS $lang) {
            $text1[$lang['id_lang']]=Tools::getValue('libelle_' . $lang['id_lang']);
        }

        $info->id_produit = (int)Tools::getValue('id_produit');
        //$info->libelle   = Tools::getValue('libelle');
        $info->libelle   = $text1;
            $saved = $info->save();

        if ($saved)
            $this->html .= $this->renderForm();
        else
            $this->html .= '<div class="alert alert-danger conf error">' . $this->l('An error occurred while attempting to save.') . '</div>';

        return $this->html;

    }
    public function processSaveReponse()
    {
        if ($id_reponse_question = Tools::getValue('id_reponse_question'))
            $info = new DemandeDevisReponseQuestionnaire((int) $id_reponse_question);
        else {
            $info = new DemandeDevisReponseQuestionnaire();
        }

        $languages = Language::getLanguages(false);

        $text  = array();
        $text1 = array();

        foreach ($languages AS $lang) {
            $text1[$lang['id_lang']]=Tools::getValue('libelle_' . $lang['id_lang']);
        }

        $info->id_questionnaireDevis = (int)Tools::getValue('id_questionnaireDevis');
        $info->libelle   = $text1;
        $saved = $info->save();

        if ($saved)
            $this->html .= $this->renderFormDevis();
        else
            $this->html .= '<div class="alert alert-danger conf error">' . $this->l('An error occurred while attempting to save.') . '</div>';

        return $this->html;

    }
    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    public function hookDisplayHome()
    {
    /*    $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');*/
    }
}
