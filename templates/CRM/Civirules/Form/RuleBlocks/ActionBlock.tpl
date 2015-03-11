
{*block for linked condition *}    
<h3>Linked Action(s)</h3>
<div class="crm-block crm-form-block crm-civirule-rule_action-block">
  <div class="crm-section">
    <div id="civirule_wrapper" class="dataTables_wrapper">
      <table id="civirule-table" class="display">
        <thead>
          <tr>
            <th>{ts}Name{/ts}</th>
            <th>{ts}Extra parameters{/ts}</th>
            <th id="nosort">&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          {assign var="row_class" value="odd-row"}
          {foreach from=$ruleActions key=action_id item=ruleAction}
            <tr id="row1" class={$row_class}>
              <td>{$ruleAction.label}</td>
              {if !empty($ruleAction.action_params)}
                <td>{$ruleAction.action_params}</td>
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
  <div class="crm-submit-buttons">
    <a class="add button" title="Add Action" href="{$ruleActionAddUrl}">
      <span><div class="icon add-icon"></div>Add Action</span></a>
  </div>
</div>
