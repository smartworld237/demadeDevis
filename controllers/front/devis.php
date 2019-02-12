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
        $parameters = array("action" => "action_name");
        $this->context->smarty->assign(array(
            'orders' => $this->getProducts(),
            'test' => 'reza',
            'devis_controller_url' => $this->context->link->getModuleLink('demandedevis','devis',$parameters)
        ));
        $this->variables['products'] = $this->getProducts();
        $this->variables['msg'] = 'test';
        $this->context->smarty->assign('variables', $this->variables);
        if(Tools::getValue('action_reponse')){

                $response = DemandeDevisReponseQuestionnaire::getReponseByQuestion((int)Tools::getValue('id_question'),$this->context->language->id);
                // Classic json response
                //  $response=Product::getSimpleProducts($this->context->language->id);
                $json = Tools::jsonEncode($response);
                $this->ajaxDie($json);
        }
        /*switch (Tools::getValue('reponse')) {
            case 'action_reponse':

                // Edit default response and do some work here
                $response = DemandeDevisReponseQuestionnaire::getReponseByQuestion((int)Tools::getValue('id_question'),$this->context->language->id);
                // Classic json response
                //  $response=Product::getSimpleProducts($this->context->language->id);
                $json = Tools::jsonEncode($response);
                $this->ajaxDie($json);
                break;
            default:
                break;
        }*/
        switch (Tools::getValue('action')) {
            case 'action_name':

                // Edit default response and do some work here
                $response = DemandeDevisQuestionaire::getQuestionnaireByProduit((int)Tools::getValue('id_product'),$this->context->language->id);
                // Classic json response
                $json = Tools::jsonEncode($response);
                $this->ajaxDie($json);
                break;
            default:
                break;

        }
        $this->setTemplate('module:demandedevis/views/templates/front/devis.tpl');
    }
    public function getProducts(){
        $products=[];
        $all=Product::getSimpleProducts($this->context->language->id);
        return $all;
    }
    public function displayAjaxDoSomeAction()
    {
        $all=Product::getSimpleProducts($this->context->language->id);
        return $all;
        // your action code ....
    }
}