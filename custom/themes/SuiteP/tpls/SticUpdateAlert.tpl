{php}
include_once 'SticInclude/SticUpdateAlert.php';
{/php}

{if $showUpdateAlert == 1}
    <div id="stic-notice" class="content hidden">
        <div class="alert alert-danger alert-dismissible" role="alert">
 
            <a title="{$APP.LBL_STIC_UPDATE_ALERT_CLOSE}" id="close-stic-notice" type="button" class="pull-right btn btn-xs btn-info"
                data-dismiss="alert" aria-label="Close">{$APP.LBL_HIDE} <span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
            <p>
                <strong>{$APP.LBL_STIC_UPDATE_ALERT_TITLE} [v {$lastSticVersion} - {$lastSticVersionDateTime}]</strong>
            </p>


            <p>{$APP.LBL_STIC_UPDATE_ALERT_INFO}</p>

            <p>
                <a class="btn btn-default btn-sm" href="https://forums.sinergiacrm.org/viewforum.php?f=17" target="_blank"
                    title="v.{$lastSticVersion} ">
                    <i class="glyphicon glyphicon-link" data-hasqtip="3"></i> {$APP.LBL_STIC_UPDATE_ALERT_LINK}
                </a>
            </p>
        </div>
    </div>
    {literal}
        <script>
            // Set cookie on closing update alert 
            $('#close-stic-notice').on('click', function() {
                Set_Cookie('SticVersion', {/literal}{$lastSticVersion}{literal},10000 , '/', false, false);
                $('#stic-notice').remove()
            })
           
            if ($('#bootstrap-container #stic-notice').length == 0) {
                setTimeout(function() {
                    $('#stic-notice').prependTo('#bootstrap-container').removeClass('hidden')
                }, 1) 

            }
           
        </script>
    {/literal}
{/if}
