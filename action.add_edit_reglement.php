<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Paiements add') )
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
global $themeObject;
$edit = 0;
if(isset($params['edit']) && $params['edit'] !='')
{
	$edit = $params['edit'];
}
if(isset($params['record_id']) && $params['record_id'] !="")
{
		$record_id = $params['record_id'];
}
//$paiements_ops = cms_utils::get_module('Paiements');
$paiements_ops = new paiementsbis();
$restant = $paiements_ops->restant_du($record_id);
$montant_tarif = $paiements_ops->montant_tarif($record_id);
if(is_null($restant) || $restant>0)
{
	$du = $montant_tarif - $restant;
	$du = number_format($du,2);
}
//var_dump($restant);
if($edit == 0)
{
	//on va chercher les renseignements dans la table paiements
	$query = "SELECT pay.id, pay.module, pay.ref_action,pay.nom, pay.tarif FROM ".cms_db_prefix()."module_paiements_produits AS pay WHERE ref_action = ?";
	$dbresult = $db->Execute($query, array($record_id));
	$compt = 0;
	while ($dbresult && $row = $dbresult->FetchRow())
	{
		$compt++;
		$id = $row['id'];
		$nom = $row['nom'];
		$tarif = $row['tarif'];
		$module = $row['module'];
		//$actif = $row['actif'];
		
	}
}
elseif($edit == 1)
{
	$query = "SELECT reg.id, pay.ref_action,pay.nom, pay.tarif,pay.module, reg.moyen_paiement, reg.statut, reg.commentaires FROM ".cms_db_prefix()."module_paiements_reglements AS reg, ".cms_db_prefix()."module_paiements_produits AS pay WHERE pay.ref_action = reg.ref_action AND pay.ref_action = ?";
	$dbresult = $db->Execute($query, array($record_id));
	$compt = 0;
	while ($dbresult && $row = $dbresult->FetchRow())
	{
		$compt++;
		$id = $row['id'];
		$nom = $row['nom'];
		$moyen_paiement = $row['moyen_paiement'];
		//$nom = $row['nom'];
		$module = $row['module'];
		$commentaires = $row['commentaires'];
		$actif = $row['actif'];
		
	}
}

$OuiNon = array('Oui'=>'1', 'Non'=>'0');	
$paiements_types = array("Espèces"=>"Espèces", "Chèque"=>"Chèque", "Chèques vacances"=>"Chèques vacances", "Coupon sport"=>"Coupon Sport", "Virement"=>"Virement");	
	
	//on construit le formulaire
	$smarty->assign('formstart',
			    $this->CreateFormStart( $id, 'do_add_edit_reglement', $returnid ) );

	$smarty->assign('record_id',
			$this->CreateInputHidden($id,'record_id',$record_id));
	$smarty->assign('module',
			$this->CreateInputHidden($id,'module',$module));		

	
	
	$smarty->assign('nom',
			$this->CreateInputText($id,'nom',(isset($nom)?$nom:""),50,200));
	$smarty->assign('montant_paiement',
			$this->CreateInputText($id,'montant_paiement',(isset($montant_paiement)?$montant_paiement:$du),50,200));			
	$smarty->assign('commentaires',
			$this->CreateInputText($id,'commentaires',(isset($commentaires)?$commentaires:""),50,200));
	$smarty->assign('moyen_paiement',
					$this->CreateInputDropdown($id,'moyen_paiement',$paiements_types));		
	$smarty->assign('submit',
			$this->CreateInputSubmit($id, 'submit', $this->Lang('submit'), 'class="button"'));
	$smarty->assign('cancel',
			$this->CreateInputSubmit($id,'cancel',
						$this->Lang('cancel')));


	$smarty->assign('formend',
			$this->CreateFormEnd());
echo $this->ProcessTemplate('add_edit_reglement.tpl');

#
# EOF
#
?>