{* block for linked event *}
<h3>Linked Event</h3>
<div class="crm-block crm-form-block crm-civirule-event-block">
  {if empty($form.rule_event_label.value)}
    <div class="crm-section">
      <div class="label">{$form.rule_event_select.label}</div>
      <div class="content">{$form.rule_event_select.html}</div>
      <div class="clear"></div>
    </div>
  {else}
    <div class="crm-section">
      <div id="civirule_eventBlock-wrapper" class="dataTables_wrapper">
        <table id="civirule-eventBlock-table" class="display">
          <tbody>
            <tr class="odd-row">
              <td>{$form.rule_event_label.value}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  {/if}
</div>

