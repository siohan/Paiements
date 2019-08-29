<?php
if (!isset($gCms)) exit;
debug_display($params, 'Parameters');

	if (!$this->CheckPermission('Paiements add'))
	{
		$designation .=$this->Lang('needpermission');
		$this->SetMessage("$designation");
		$this->RedirectToAdminTab('paiements');
	}
if(isset($params['cancel']) && $params['cancel'] != '')
{
	exit;
}
//on récupère les valeurs
//pour l'instant pas d'erreur
$aujourdhui = date('Y-m-d ');
$error = 0;
$edit = 0;//pour savoir si on fait un update ou un insert; 0 = insert
	
		
		
		if (isset($params['record_id']) && $params['record_id'] !='')
		{
			$record_id = $params['record_id'];
			
		}	
		
		$montant_paiement = '';
		if (isset($params['montant_paiement']) && $params['montant_paiement'] !='')
		{
			$montant_paiement = $params['montant_paiement'];
		}
		else
		{
			$error++;
		}
		
		$commentaires = '';
		if (isset($params['commentaires']) && $params['commentaires'] !='')
		{
			$commentaires = $params['commentaires'];
		}
		$module = '';
		if (isset($params['module']) && $params['module'] !='')
		{
			$module = $params['module'];
		}
		$moyen_paiement = '';
		if (isset($params['moyen_paiement']) && $params['moyen_paiement'] !='')
		{
			$moyen_paiement = $params['moyen_paiement'];
		}
		
		
			
				
		//on calcule le nb d'erreur
		if($error>0)
		{
			$this->Setmessage('Parametres requis manquants !');
			$this->RedirectToAdminTab('groups');
		}
		else // pas d'erreurs on continue
		{
				$query = "INSERT INTO ".cms_db_prefix()."module_paiements_reglements (ref_action, date_created, montant_paiement, moyen_paiement, commentaires) VALUES ( ?, ?, ?, ?, ?)";
				$dbresult = $db->Execute($query, array($record_id, time(), $montant_paiement, $moyen_paiement, $commentaires));
				
				//faut-il déstocker le produit ?
				if($dbresult)
				{
					
					if($module == 'Commandes')
					{
						//on change le staut de la commande
						$commandes = new commandes_ops;
						$commandes->change_statut_cc($statut='Payée', $record_id);
						$commandes->cc_items_status($statut='3',$record_id);
						//on redirige vers une page du style oui, non
						$this->Redirect($id, 'destockage',$returnid, array("ref_action"=>$record_id));
					}
				}
				
			
		}		
	//	echo "la valeur de edit est :".$edit;
		
		
	
			
		

$this->SetMessage('Règlement modifié ou ajouté');
$this->RedirectToAdminTab('groups',$params='');

?>