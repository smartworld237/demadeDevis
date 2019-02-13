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
        $responses = DemandeDevisQuestionaire::getQuestionnaireByProduit((int)Tools::getValue('id_product'),$this->context->language->id);


        if (Tools::isSubmit('submitdevis')) {
            $customer = $this->context->customer;
               /* for ($i=0;$i<=Tools::getValue('size');$i++){

                }*/
               foreach ($responses as $question){
                   $devis=new DemandeDevisModel();
                   $devis->id_client=$customer->id;

                   $resp=(int)Tools::getValue('response'.$question->id_questionnaireDevis);
                   if($resp){
                       $devis->id_reponse_question=$resp;
                       $devis->save();
                       $this->context->smarty->assign(array('notification'=>'valider'));
                   }else{
                       $this->context->smarty->assign(array('notification'=>'ereur'));
                   }

            }
                }
    }
    /**
     * @see FrontController::initContent()
     */
    public function initContent()
    {
        parent::initContent();
        $parameters = array();
        $this->context->smarty->assign(array(
            'orders' => $this->getProducts(),
            'test' => 'reza',
            'controller'=>$this->context->link->getPageLink('devis'),
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
        }else if (Tools::getValue('action_name')){
            $response = DemandeDevisQuestionaire::getQuestionnaireByProduit((int)Tools::getValue('id_product'),$this->context->language->id);
            // Classic json response
            $json = Tools::jsonEncode($response);
            $this->ajaxDie($json);
        }else if (Tools::isSubmit('submitMessage')){
           // $this->context->smarty->assign(array('notification'=>'ereur'));
            $customer = $this->context->customer;
            $responses = DemandeDevisQuestionaire::getQuestionnaireByProduit((int)Tools::getValue('id_product'),$this->context->language->id);
            $this->context->smarty->assign(array('notification'=>'test'));
            foreach ($responses as $question){
                $devis=new DemandeDevisModel();
                $devis->id_client=$customer->id;

                $resp=(int)Tools::getValue('response'.$question->id_questionnaireDevis);
                if($resp){
                    $devis->id_reponse_question=$resp;
                    $devis->save();
                    $this->context->smarty->assign(array('notification'=>'valider'));
                }else{
                    $this->context->smarty->assign(array('notification'=>'ereur'));
                }

            }
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