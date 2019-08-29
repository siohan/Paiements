<h3>Ajouter/Modifier un paiement</h3>
{form_start module=Paiements action=add_edit_produit}

{if $edit == 1}
<input type="hidden" name="{$actionid}record_id" value="{$record_id}">
<div class="pageoverflow">
  <p class="pagetext">Libellé</p>
  <input type="text" name="{$actionid}nom" value="{$nom}" size="35"/>
</div>
</div>
<div class="pageoverflow">
  <p class="pagetext">Tarif</p>
  <input type="text" name="{$actionid}tarif" value="{$tarif}"/>
</div>
{else}
<div class="pageoverflow">
  <p class="pagetext">Libellé</p>
  <input type="text" name="{$actionid}nom" value="" size="35"/>
</div>
</div>
<div class="pageoverflow">
  <p class="pagetext">Tarif</p>
  <input type="text" name="{$actionid}tarif" value="{$tarif}"/>
</div>
{/if}
<div class="pageoverflow">
    <p class="pagetext">&nbsp;</p>
    <input type="submit" name="{$actionid}submit" value="Envoyer"/>
<input type="submit" name="{$actionid}cancel" value="Annuler"/>
  </div>
{form_end}

