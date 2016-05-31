<?php
/*
 * @component VM2wishlists
 * @copyright Copyright (C) 2010-2015 Adrien Roussel Nordmograph.com
 * @license : GNU/GPL v3
 * @Website : http://www.nordmograph.com/extensions
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');
class Vm2wishlistsControllerRecommend extends JControllerForm
{
    /**
     * Custom Constructor
     */
    function __construct()  {
        parent::__construct( );
    }
    
    public function getModel($name = '', $prefix = '', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, array('ignore_request' => false));
    }
    
    public function send()
    {
        // Check for request forgeries.
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $model               = $this->getModel('recommend');
        // Get the data from POST
        $data  = $this->input->post->get('jform', array(), 'array');
        $myname         = $data['myname'];
        $email          = $data['email'];
        
        $subject        = $data['subject'];
        $message        = $data['message'];
        // Validate the posted data.
        $form = $model->getForm();
        if (!$form)
        {
            JError::raiseError(500, $model->getError());
            return false;
        }
        $user           = JFactory::getUser();
        $db             = JFactory::getDBO();
        $doc            = JFactory::getDocument();
        $juri           = JURI::base();
        $app            = JFactory::getApplication();
        $validate 		= $model->validate($form, $data);
        $listid     	= $app->input->post->getId('listid');
        $userid         = $app->input->post->getId('userid');
        $Itemid         = $app->input->post->getId('Itemid');
        if ($validate === false)
        {
            // Get the validation messages.
            $errors = $model->getErrors();
            // Push up to three validation messages out to the user.
            for ($i = 0, $n = count($errors); $i < $n && $i < 5; $i++)
            {
                if ($errors[$i] instanceof Exception)
                {
                    $app->enqueueMessage( $errors[$i]->getMessage(), 'warning');
                }
                else
                {
                    $app->enqueueMessage($errors[$i], 'warning');
                }
            }
            // Save the data in the session.
            $app->setUserState('com_vm2wishlists.recommend.data', $data);
            // Redirect back to the form.
            $this->setRedirect(JRoute::_('index.php?option=com_vm2wishlists&view=recommend&listid='.$listid.'&userid='.$userid.'&sent=0&tmpl=component&email='.$email.'&subject='.$subject.'&message='.$message.'&Itemid=.'.$Itemid, false));
            return false;
        }
        
        
        $cparams        = JComponentHelper::getParams('com_vm2wishlists');
        
        /*$fromname         = $app->input->post->get('formname');
        $mailfrom       = $app->input->post->get('formemail');
        $subject        = $app->input->post->get('formsubject');
        $formmessage    = $app->input->post->get('formmessage');
        $product_url    = $app->input->post->get('formhref');
        $emailto        = $app->input->post->get('emailto');
        $format         = $app->input->get->get('format','html');*/
        
        
    
        
        if( $myname !='' && $email !='' &&  $subject!='' &&   $message!='' )
        {
            $uri 	 = JFactory::getURI();
			$list_url = $uri->getScheme().'://'.$uri->getHost()
			.JRoute::_('index.php?option=com_vm2wishlists&view=list&id='.$listid.'&userid='.$userid.'&Itemid='.$Itemid );

            $mailer = JFactory::getMailer(); 
            $config = JFactory::getConfig();
            $body .= $message." <br /><br />";
            $body .= ucwords($myname)." <br /><br />";
            $body .= '<a href="'.$list_url.'">'.$list_url.'</a>';
            
            
            $mailerror = JText::_('COM_VM2WISHLIST_RECOMMEND_EMAILFAILED');
            $email          =  str_replace(';',',',$email);
            $emails = explode(',' ,$email);
            if(count($emails)==1 && filter_var( $email , FILTER_VALIDATE_EMAIL) )
                $mailer->addRecipient($email);
            elseif(count($emails)>1)
            {
                for($i = 0 ; $i<5;$i++)
                {
                    if (filter_var( $emails[$i], FILTER_VALIDATE_EMAIL))
                        $mailer->addBCC( $emails[$i] );
                }
            }
            $mailer->setSubject($subject);
            $mailer->setBody($body);
			$mailer->isHTML(true);
			$mailer->Encoding = 'base64';
            $sender = array( $user->email, $myname );
            $mailer->setSender($sender);
            $sent = $mailer->send();
            
            
            if ($sent != 1) 
                $sent = 2;
            $app->redirect(JRoute::_('index.php?option=com_vm2wishlists&view=recommend&listid='.$data->listid.'&userid='.$data->userid.'&sent='.$sent.'&tmpl=component&Itemid='.$Itemid, false) );
        }
    }
    
}
?>