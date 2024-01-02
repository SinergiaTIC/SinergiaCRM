<script src="SticInclude/vendor/jqColorPicker/jqColorPicker.min.js"></script>
{if strval($parentFieldArray.$col) == ""}
    {sugar_fetch object=$parentFieldArray key=$col}
{else}
    <input disabled="disabled" type="text" name="color" size="8" maxlength="255" title id="color-{$parentFieldArray.ID}" value="{sugar_fetch object=$parentFieldArray key=$col}">
{/if}

<script>
    {literal}
    $(document).ready(function(){
        $('#color-{/literal}{$parentFieldArray.ID}{literal}').colorPicker({
            opacity: false,
            renderCallback: function($elm, toggled) {
                if ($elm.val() != '') {
                    $elm.val('#' + this.color.colors.HEX);
                }
            }
        });
    });
    {/literal}
</script>