
{*block for linked event *}    
<h3>Linked Event</h3>
<div class="crm-block crm-form-block crm-civirule-event-block">
  {if empty($form.rule_event_label.value)}
    <div class="crm-submit-buttons">
      <span class="crm-button crm-button-type-next crm-button_qf_Rule_next">
        <input id="_qf_Rule_next-bottom" class="validate form-submit" type="submit" value="Add Event" name="_qf_Rule_next">
      </span>
    </div>
  {else}
    <div class="crm-section">
      <div id="civirule_eventBlock-wrapper" class="dataTables_wrapper">
        <table id="civirule-eventBlock-table" class="display">
          <tbody>
            <tr class="odd-row">
              <td>{$form.rule_event_label.value}</td>
              <td><span>{$deleteEventUrl}</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  {/if}
</div>
