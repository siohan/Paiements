<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Paiements use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
global $themeObject;
$error = 0; //on incrémente un compteur d'erreurs
$ref_action = ';'
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
else
{
	$error++;
}
if(!$error)
{
	//on fait le job !
	$service = new commandes_ops();
	$en_stock = $service->en_stock($libelle_commande,$ep_manche_taille,$couleur);
	$decrement = $service->decremente_stock($libelle_commande, $quantite, $ep_manche_taille, $couleur);
	$refresh = $service->refresh_stock();
}

#
# EOF
#
?>