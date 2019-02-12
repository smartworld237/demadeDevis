<?php
/**
 * Created by PhpStorm.
 * User: ballack
 * Date: 09/02/2019
 * Time: 20:22
 */

class DemandeDevisdevisModuleFrontController extends ModuleFrontController
{
    private $variables = [];
    /**
     * @see FrontController::postProcess()
     */
    public function postProcess()
    {

        $this->variables['value'] = Tools::getValue('email', '');
        $this->variables['msg'] = 'test';
        $this->variables['conditions'] = Configuration::get('NW_CONDITIONS', $this->context->language->id);


        /*if (Tools::isSubmit('submitNewsletter')) {
                    $this->module->newsletterRegistration();
                    if ($this->module->error) {
                        $this->variables['msg'] = $this->module->error;
                        $this->variables['nw_error'] = true;
                    } elseif ($this->module->valid) {
                        $this->variables['msg'] = $this->module->valid;
                        $this->variables['nw_error'] = false;
                    }
                }*/
    }
    /**
     * @see FrontController::initContent()
     */
    public function initContent()
    {
        parent::initContent();
        $this->context->smarty->assign(array(
            'orders' => $this->getProducts(),
            'test' => 'reza'
        ));
        $this->variables['products'] = $this->getProducts();
        $this->variables['msg'] = 'test';
        $this->context->smarty->assign('variables', $this->variables);
       $this->setTemplate('module:demandedevis/views/templates/front/devis.tpl');
    }
    public function getProducts(){
        $products=[];
        $all=Product::getSimpleProducts($this->context->language->id);
        return $all;
    }
}