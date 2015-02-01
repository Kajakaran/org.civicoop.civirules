
{*block for linked condition *}    
<h3>Linked Action(s)</h3>
<div class="crm-block crm-form-block crm-civirule-rule_action-block">
  <div class="crm-section">
    <div id="civirule_wrapper" class="dataTables_wrapper">
      <table id="civirule-table" class="display">
        <thead>
          <tr>
            <th>{ts}Description{/ts}</th>
            <th>{ts}Extra parameters{/ts}</th>
            <th id="nosort">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          {assign var="row_class" value="odd-row"}
          {foreach from=$ruleActions key=action_id item=ruleAction}
            <tr id="row1" class={$row_class}>
              <td>{$ruleAction.label}&nbsp;{$ruleAction.action_value}</td>
              {if !empty($ruleAction.extra_params)}
                <td>{$ruleAction.extra_params}</td>
              {else}
                <td>&nbsp;</td>
              {/if}
              <td>
                <span>
                  {foreach from=$ruleAction.actions item=action_link}
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
    </div>
  </div>
  {if $action eq 1}
    <div class="crm-submit-buttons">
      <span class="crm-button crm-button-type-next crm-button_qf_Rule_next">
        <input id="_qf_Rule_next-bottom" class="validate form-submit" type="submit" value="Add Action" name="_qf_Rule_next">
      </span>
    </div>
  {/if}
</div>
