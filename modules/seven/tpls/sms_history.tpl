<div>
    {foreach from=$SEVEN_SMS_HISTORY item=sms}
        {if isset($sms->msg_text)}
            {assign var=inbound value=true}
        {else}
            {assign var=inbound value=false}
        {/if}
        <div>
            {if $inbound eq true}
                <span class="suitepicon suitepicon-action-left"></span>
                {$sms->msg_text}
            {else}
                <span class="suitepicon suitepicon-action-right"></span>
                {$sms->text}
            {/if}

            <small>{$sms->date_entered}</small>
        </div>
    {/foreach}
</div>
