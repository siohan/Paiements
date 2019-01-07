{* email pour relance une facture impayée *}
{* Liste des variables disponibles*}
{* $libelle : le libelle de la facture*}
{* $numero_facture : la référence interne de la facture*}
{* $montant_total : ben le montant total quoi !*}
{* $restant_du : ce qu'il reste à payer si plusieurs reglements*}

<p>Salut,<br />
<p>Sauf erreur de ma part, tu n'as pas réglé la facture {$numero_facture} dont les détails sont les suivants : 
<ul>
<li>Désignation : {$libelle}</li>
<li>Montant total : {$montant_total}€</li>
<li>Restant dû : {$restant_du}€</li>
</ul>
Merci de nous faire parvenir ton règlement rapidement.<br />
Sportivement.</p>