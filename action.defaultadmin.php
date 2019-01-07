<?php
   if ( !isset($gCms) ) exit; 
	if (!$this->CheckPermission('Paiements use'))
	{
		echo $this->ShowErrors($this->Lang('needpermission'));
		return;
	}
//	debug_display($params, 'Parameters');

echo $this->StartTabheaders();
if (FALSE == empty($params['active_tab']))
  {
    $tab = $params['active_tab'];
  } else {
  $tab = 'paiements';
 }	
	echo $this->SetTabHeader('paiements', 'Recettes', ('produits' == $tab)?true:false);
	echo $this->SetTabHeader('depenses', 'Dépenses', ('charges' == $tab)?true:false);
//	echo $this->SetTabHeader('feu', 'Espace privé' , ('feu' == $tab)?true:false);
	echo $this->SetTabHeader('email', 'Emails' , ('email' == $tab)?true:false);



echo $this->EndTabHeaders();

echo $this->StartTabContent();
	
	
	echo $this->StartTab('paiements', $params);
    	include(dirname(__FILE__).'/action.admin_paiements_tab.php');
   	echo $this->EndTab();

	echo $this->StartTab('depenses', $params);
    	include(dirname(__FILE__).'/action.admin_depenses_tab.php');
   	echo $this->EndTab();

	echo $this->StartTab('email', $params);
    	include(dirname(__FILE__).'/action.admin_emails_tab.php');
   	echo $this->EndTab();




echo $this->EndTabContent();
//on a refermé les onglets
?>