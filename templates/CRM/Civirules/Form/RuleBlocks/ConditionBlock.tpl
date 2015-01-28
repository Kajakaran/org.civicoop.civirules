
{*block for linked condition *}    
<h3>Linked Condition(s)</h3>
<div class="crm-block crm-form-block crm-civirule-rule_condition-block">
  <div class="crm-section">
    <div id="civirule_wrapper" class="dataTables_wrapper">
      <table id="civirule-table" class="display">
      <tbody>
        {assign var="row_class" value="odd-row"}
          {foreach from=$conditions key=condition_id item=condition}
          <tr id="row1" class={$row_class}>
            <td>{literal}{{/literal}{$condition.name}&nbsp;{$condition.comparison}&nbsp;{$condition.value}{literal}}{/literal}</td>
            <td>
              <span>
                {foreach from=$condition.actions item=action_link}
                  {$action_link}
                {/foreach}
              </span>
            </td>
          </tr>
          {if $row_class eq "odd-row"}
            {assign var="row_class" value="even-row"}
          {else}
            {assign var="row_class" value="odd-row"}                        
          {/if}
        {/foreach}
      </tbody>
    </table>    
  </div>  </div>
  {if $action eq 1}
    <div class="crm-submit-buttons">
      <span class="crm-button crm-button-type-next crm-button_qf_Rule_next">
        <input id="_qf_Rule_next-bottom" class="validate form-submit" type="submit" value="Add Condition" name="_qf_Rule_next">
      </span>
    </div>
  {else}
    <div class="crm-submit-buttons">
      <span class="crm-button crm-button-type-next crm-button_qf_Rule_next">
        <input id="_qf_Rule_next-bottom" class="validate form-submit" type="submit" value="Add Or" name="_qf_Rule_next">
      </span>
      <span class="crm-button crm-button-type-next crm-button_qf_Rule_next">
        <input id="_qf_Rule_next-bottom" class="validate form-submit" type="submit" value="Add And" name="_qf_Rule_next">
      </span>

    </div>
  {/if}
</div>
