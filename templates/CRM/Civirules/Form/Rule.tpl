<div class="crm-submit-buttons">
  {include file="CRM/common/formButtons.tpl" location="top"}
</div>

{include file="CRM/Civirules/Form/RuleBlocks/RuleBlock.tpl"}
{include file="CRM/Civirules/Form/RuleBlocks/EventBlock.tpl"}
{if $action ne 1}
  {include file="CRM/Civirules/Form/RuleBlocks/ConditionBlock.tpl"}
  {include file="CRM/Civirules/Form/RuleBlocks/ActionBlock.tpl"}
{/if}
  
<div class="crm-submit-buttons">
  {include file="CRM/common/formButtons.tpl" location="bottom"}
</div>
