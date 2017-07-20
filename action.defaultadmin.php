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
	echo $this->SetTabHeader('Paiements', 'Paiements', ('paiements' == $tab)?true:false);
	//echo $this->SetTabHeader('groups', 'Groupes', ('groups' == $tab)?true:false);
//	echo $this->SetTabHeader('feu', 'Espace privé' , ('feu' == $tab)?true:false);
//	echo $this->SetTabHeader('email', 'Emails' , ('email' == $tab)?true:false);



echo $this->EndTabHeaders();

echo $this->StartTabContent();
	
	
	echo $this->StartTab('adherents', $params);
    	include(dirname(__FILE__).'/action.admin_paiements_tab.php');
   	echo $this->EndTab();




echo $this->EndTabContent();
//on a refermé les onglets
?>