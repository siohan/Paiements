<?php
if( !isset($gCms) ) exit;

if (!$this->CheckPermission('Paiements use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
//debug_display($_POST, 'Parameters');
$db = cmsms()->GetDb();
if(!empty($_POST))
{
	if( isset($_POST['cancel']) ) 
	{
            $this->RedirectToAdminTab();
        }
	//on récupère les valeurs

	//$edit = 0;//pour savoir si on fait un update ou un insert; 0 = insert



			if (isset($_POST['record_id']) && $_POST['record_id'] !='')
			{
				$record_id = $_POST['record_id'];
			}
			if (isset($_POST['nom']) && $_POST['nom'] !='')
			{
				$nom = $_POST['nom'];
			}	

			$tarif = '';
			if (isset($_POST['tarif']) && $_POST['tarif'] !='')
			{
				$tarif = $_POST['tarif'];
			}

			$query = "UPDATE  ".cms_db_prefix()."module_paiements_produits SET tarif = ? , nom = ? WHERE ref_action = ?";
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
}
else
{
	//debug_display($params, 'Parameters');
	//les valeurs par défaut
	$edit = 0; //variable pour savoir s'il s'agit d'un ajout ou d'une modification
	$nom = '';
	$tarif = '0.00';
	$categorie = "";
	$record_id = 0;
	$paie_ops = new paiements\paiementsbis;
	if(isset($params['record_id']) && $params['record_id'] !="")
	{
			$record_id = $params['record_id'];
			$edit = 1;
			$details = $paie_ops->details_paiement($record_id);
			$nom = $details['nom'];
			$tarif = $details['tarif'];
			$categorie = $details['categorie'];	

	}
	else
	{
		//
	}
		//on construit le formulaire
		$tpl = $smarty->CreateTemplate($this->GetTemplateResource('add_edit_produit.tpl'), null, null, $smarty);
		$tpl->assign('edit', $edit);
		$tpl->assign('nom', $nom);
		$tpl->assign('tarif', $tarif);
		$tpl->assign('record_id', $record_id);		
		$tpl->display();
}
#
# EOF
#
?>