
{*block for linked event *}    
<h3>Linked Event</h3>
<div class="crm-block crm-form-block crm-civirule-rule_event-block">
  <div class="crm-section">
    <div class="label">{$form.rule_event_label.value}</div>
    <div class="clear"></div>    
  </div>
  {if $action eq 1}
    <div class="crm-submit-buttons">
      <span class="crm-button crm-button-type-next crm-button_qf_Rule_next">
        <input id="_qf_Rule_next-bottom" class="validate form-submit" type="submit" value="Add Event" name="_qf_Rule_next">
      </span>
    </div>
  {/if}
</div>
