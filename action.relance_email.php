<?php
if(!isset($gCms)) exit;
if (!$this->CheckPermission('Paiements use'))
{
	echo $this->ShowErrors($this->Lang('needpermission'));
	return;
}
//debug_display($params, 'Parameters');
$error = 0;
$licence = '';
if(isset($params['licence']) && $params['licence'] != '')
{
	$licence = $params['licence'];
}
else
{
	$error++;
}
$ref_action = '';
if(isset($params['ref_action']) && $params['ref_action'] != '')
{
	$ref_action = $params['ref_action'];
}
else
{
	$error++;
}
if($error>0)
{
	$this->SetMessage('Des erreurs sont apparues !');
}
else
{
	
	//on va chercher les détails de la facture
	$query="SELECT nom, ref_action, tarif FROM ".cms_db_prefix()."module_paiements_produits WHERE ref_action = ?";
	$dbresult = $db->Execute($query, array($ref_action));
	if($dbresult && $dbresult->RecordCount() >0)
	{
		$row = $dbresult->FetchRow();
		$libelle = $row['nom'];
		$montant_total = $row['tarif'];
	}
	//le restant dû
	$numero_facture = $ref_action;
	$paiements = new paiementsbis();
	$reglements = $paiements->restant_du($ref_action);
	if(NULL == $reglements)
	{
		$restant_du = $montant_total;
	}
	elseif($reglements >0)
	{
		$restant_du = $montant_total - $reglements;
	}
	//var_dump($restant_du);
	//on envoie tout à smarty 
	$smarty->assign('numero_facture', $numero_facture);
	$smarty->assign('libelle', $libelle);
	$smarty->assign('montant_total', $montant_total);
	$smarty->assign('restant_du', $restant_du);
	$adherents = new contact();
	$user_email = $adherents->email_address($licence);
	$admin_email = $this->GetPreference('admin_email'); 

	if(FALSE !== $user_email)
	{
		$subject = $this->GetPreference('sujet_relance_email');
		$message = $this->GetTemplate('relance_email');
		$body = $this->ProcessTemplateFromData($message);
		$headers = "From: ".$admin_email."\n";
		$headers .= "Reply-To: ".$admin_email."\n";
		$headers .= "Content-Type: text/html; charset=\"utf-8\"";

		$cmsmailer = new \cms_mailer();
		$cmsmailer->reset();
		$cmsmailer->SetFrom($admin_email);//$this->GetPreference('admin_email'));
		$cmsmailer->AddAddress($user_email);
		$cmsmailer->SetBody($body);
		$cmsmailer->SetSubject($subject);
		$cmsmailer->IsHTML(true);
		$cmsmailer->SetPriority(1);
		$cmsmailer->Send();
		if( !$cmsmailer->Send() ) {
		    audit('',$this->GetName(),'Problem sending email to '.$user_email);
		$this->SetMessage('Relance non envoyée !');
		}
		else
		{
			$this->SetMessage('Relance envoyée !');
		}
		
	}
	else
	{
		$this->SetMessage( 'adresse email introuvable !');
	}

}
$this->RedirectToAdminTab('paiements');

?>