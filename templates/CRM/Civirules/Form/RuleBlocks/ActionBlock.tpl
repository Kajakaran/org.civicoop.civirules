
{*block for linked condition *}    
<h3>Linked Action(s)</h3>
<div class="crm-block crm-form-block crm-civirule-rule_action-block">
  <div class="crm-section">
    <div id="civirule_wrapper" class="dataTables_wrapper">
      <table id="civirule-table" class="display">
      <tbody>
        {assign var="row_class" value="odd-row"}
          {foreach from=$actions key=action_id item=action}
          <tr id="row1" class={$row_class}>
            <td>{$action.label}</td>
            <td>{$action.comparison}</td>
            <td>{$action.value}</td>
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
        <input id="_qf_Rule_next-bottom" class="validate form-submit" type="submit" value="Add Action" name="_qf_Rule_next">
      </span>
    </div>
  {/if}
</div>
