<?php
if (!isset($gCms)) exit;
//debug_display($params, 'Parameters');

	if (!$this->CheckPermission('Paiements use'))
	{
		$designation .=$this->Lang('needpermission');
		$this->SetMessage("$designation");
		$this->RedirectToAdminTab('paiements');
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
		if (isset($params['nom']) && $params['nom'] !='')
		{
			$nom = $params['nom'];
			
		}	
		
		$tarif = '';
		if (isset($params['tarif']) && $params['tarif'] !='')
		{
			$tarif = $params['tarif'];
		}
				
		$query = "UPDATE  ".cms_db_prefix()."module_paiements_paiements SET tarif = ? , nom = ? WHERE ref_action = ?";
		$dbresult = $db->Execute($query, array($tarif, $nom,$record_id));
		
		if($dbresult)
		{
			$this->SetMessage('Paiement modifié');
		}
		else
		{
			$this->SetMessage('Erreur : paiement non modifié');
		}

		
$this->RedirectToAdminTab('paiements');

?>