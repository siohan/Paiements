<?php
if( !isset($gCms) ) exit;
//use paiements\paiementsbis;
if (!$this->CheckPermission('Paiements use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
global $themeObject;
$shopping = 'Régler';//'<img src="../assets/modules/Paiements/images/paiement.png" class="systemicon" alt="Réglez" title="Réglez">';
$relance = 'Relance';//'<img src="../assets/modules/Paiements/images/forward-email-16.png" class="systemicon" alt="Envoyer une relance" title="Envoyer une relance">';
$details_facture = '<img src="../assets/modules/Paiements/images/billing.jpg" class="systemicon" alt="Détails de la facture" title="Détails de la facture">';
$smarty->assign('details_facture', $details_facture);
$smarty->assign('add_edit_produit',
		$this->CreateLink($id, 'add_edit_produit', $returnid,$contents='Ajouter une recette'));
$smarty->assign('admin_categories',
		$this->CreateLink($id, 'admin_categorie', $returnid,$contents='Ajouter une catégorie de recette', array("objet"=>"recette")));
$result= array ();
$query = "SELECT pay.id, pay.nom,pay.categorie,pay.date_created, pay.tarif,pay.module,pay.actif, pay.ref_action,pay.licence,pay.statut ";
// , CONCAT_WS(' ', adh.nom, adh.prenom) AS joueur 
$query.=" FROM ".cms_db_prefix()."module_paiements_produits AS pay";
// , ".cms_db_prefix()."module_adherents_adherents AS adh WHERE adh.licence = pay.licence";
$query.=" ORDER BY pay.date_created DESC";
$dbresult= $db->Execute($query);
	
	//echo $query;
	$rowarray= array();
	$rowclass = '';
	$paiement_ops = new paiementsbis;
	
		if ($dbresult && $dbresult->RecordCount() > 0)
  		{
    			$adh_ops = new Asso_adherents;
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
				$onerow->categorie= $row['categorie'];
				$onerow->joueur= $adh_ops->get_name($licence);
				$onerow->nom = $row['nom'];
				$onerow->licence = $row['licence'];
				$onerow->date_created = date('d/m/Y H:i:s',$row['date_created']);
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
							
							//on envoie une relance ? si on a bien un mail
						
							$onerow->relance=$this->CreateLink($id, 'relance_email',$returnid,$relance, array("licence"=>$row['licence'], "ref_action"=>$row['ref_action']));
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
				$onerow->editlink= $this->CreateLink($id, 'add_edit_produit', $returnid, $themeObject->DisplayImage('icons/system/edit.gif', $this->Lang('edit'), '', '', 'systemicon'), array('record_id'=>$row['ref_action']));
				//$onerow->delete = $this->CreateLink($id, '', $returnid, $themeObject->DisplayImage('icons/system/delete.gif', $this->Lang('delete'), '', '', 'systemicon'),array() );
				
				($rowclass == "row1" ? $rowclass= "row2" : $rowclass= "row1");
				$rowarray[]= $onerow;
      			}
			
  		}

		$smarty->assign('itemsfound', $this->Lang('resultsfoundtext'));
		$smarty->assign('itemcount', count($rowarray));
		$smarty->assign('items', $rowarray);
		
		

echo $this->ProcessTemplate('produits.tpl');


#
# EOF
#
?>