<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Paiements use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
global $themeObject;
$shopping = '<img src="../modules/Paiements/images/paiement.png" class="systemicon" alt="Réglez" title="Réglez">';
$details_facture = '<img src="../modules/Paiements/images/billing.jpg" class="systemicon" alt="Détails de la facture" title="Détails de la facture">';
$smarty->assign('details_facture', $details_facture);
$smarty->assign('add_edit_paiements',
		$this->CreateLink($id, 'add_edit_paiements', $returnid,$contents='Ajouter un paiement'));
$result= array ();
$query = "SELECT pay.id, pay.nom,pay.date_created, pay.tarif,pay.module,pay.actif, pay.ref_action,pay.licence,pay.statut, CONCAT_WS(' ', adh.nom, adh.prenom) AS joueur FROM ".cms_db_prefix()."module_paiements_paiements AS pay, ".cms_db_prefix()."module_adherents_adherents AS adh WHERE adh.licence = pay.licence";
$dbresult= $db->Execute($query);
	
	//echo $query;
	$rowarray= array();
	$rowclass = '';
	$paiement_ops = new paiementsbis();
	
		if ($dbresult && $dbresult->RecordCount() > 0)
  		{
    			while ($row= $dbresult->FetchRow())
      			{
				$onerow= new StdClass();
				$onerow->rowclass= $rowclass;

				//les champs disponibles : 
				$ref_action = $row['ref_action'];
				$statut = $row['statut'];
				$licence = $row['licence'];
				$onerow->id= $row['id'];
				$onerow->ref_action= $ref_action;
				$onerow->module= $row['module'];
				$onerow->joueur= $row['joueur'];
				$onerow->nom = $row['nom'];
				$onerow->licence = $row['licence'];
				$onerow->date_created = $row['date_created'];
				$onerow->tarif = $row['tarif'];	
				$reglement = $paiement_ops->is_paid($ref_action);
				//var_dump($reglement);
				$restant_du = '0.00';
				$du = '0.00';
				if(TRUE === $reglement)
				{
					$onerow->statut = $themeObject->DisplayImage('icons/system/true.gif', $this->Lang('delete'), '', '', 'systemicon');
					$onerow->restant_du = $du;
					$onerow->view_reglement = $this->CreateLink($id, 'view_reglements',$returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view'), '', '', 'systemicon'), array('record_id'=>$row['ref_action']));
				}
				elseif(FALSE === $reglement)
				{
					$restant_du = $paiement_ops->restant_du($ref_action);
					//$restant_du = number_format($restant_du,2);
				//	var_dump($restant_du);
					if(is_null($restant_du) || $restant_du > 0)//il n'y a pas encore de reglement ou pas la totalité du montant
					{
						$du = $row['tarif'] - $restant_du;
					//	$du = number_format($du,2);
					//	var_dump($du);
					//	var_dump($du);
						if($du >0)
						{
							$onerow->statut = $themeObject->DisplayImage('icons/system/false.gif', $this->Lang('delete'), '', '', 'systemicon');
							$onerow->add_reglement= $this->CreateLink($id, 'add_edit_reglement', $returnid, $shopping, array('record_id'=>$row['ref_action']));
							$onerow->restant_du = $du;
							$onerow->editlink= $this->CreateLink($id, 'add_edit_paiement', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'), array('record_id'=>$row['ref_action']));
						}
						else
						{

							$onerow->statut = $themeObject->DisplayImage('icons/system/true.gif', $this->Lang('delete'), '', '', 'systemicon');
							$onerow->restant_du = $du;
							//$onerow->view_details_commande=  $this$themeObject->DisplayImage('icons/system/view.gif', $this->Lang('delete'), '', '', 'systemicon');
							$onerow->view_reglement = $this->CreateLink($id, 'view_reglements',$returnid, $themeObject->DisplayImage('icons/system/view.gif', $this->Lang('view'), '', '', 'systemicon'), array('record_id'=>$row['ref_action']));
						}


					}
					
					
				}
				
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
      			}
			
  		}

		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
		
		

echo $this->ProcessTemplate('paiements.tpl');


#
# EOF
#
?>