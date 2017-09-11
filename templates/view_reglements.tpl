<div class="pageoptions"><p class="pageoptions">{$itemcount}&nbsp;{$itemsfound}</p></div>
{$retour}
{if $itemcount > 0}

<table cellpadding="0" class="pagetable cms_sortable tablesorter" id="articlelist">
 <thead>
	<tr>
		<th>Id</th>
		<th>Date</th>
		<th>référence</th>
		<th>Montant paiement</th>
		<th>Moyen de paiement</th>
		<th>Commentaires</th>
		<th colspan="2">Actions</th>
	</tr>
 </thead>
 <tbody>
{foreach from=$items item=entry}
  <tr class="{$entry->rowclass}">
	<td>{$entry->id}</td>
	<td>{$entry->date_created|date_format:"%d-%m-%Y"}</td>
	<td>{$entry->ref_action}</td>
	<td>{$entry->montant_paiement}</td>
	<td>{$entry->moyen_paiement}</td>
	<td>{$entry->commentaires}</td>
	<td>{$entry->edit}</td>
	<td>{$entry->delete}</td>
  </tr>
{/foreach}
 </tbody>
</table>

{/if}

