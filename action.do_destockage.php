<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Paiements use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
debug_display($params, 'Parameters');
global $themeObject;
$error = 0; //on incrémente un compteur d'erreurs
$ref_action = '';
if(isset($params['ref_action']) && $params['ref_action'] !="")
{
		$ref_action = $params['ref_action'];
}
else
{
	$error++;
}
$choix = '';
if(isset($params['choix']) && $params['choix'] !="")
{
		$choix = $params['choix'];
}
else
{
	$error++;
}
if($error<1)
{
	//on fait le job !
	echo 'no error !';
		if($choix =='Oui')
		{
			//$service = cms_utils::get_module('Commandes');
			$service = new commandes_ops();

			$decrement = $service->decremente_stock_commande($ref_action);
			if(true === $decrement)
			{
				$this->RedirectToAdminTab('paiements');
			}
		}
	
}

#
# EOF
#
?>