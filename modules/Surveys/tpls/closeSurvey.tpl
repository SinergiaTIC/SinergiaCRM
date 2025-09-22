{$HEADER}
<div class="container">
    <div class="row">
        <div class="col-md-offset-3 col-md-6">
            {* STIC-Custom 20250922 - JBL - Adapt image size *}
            {* https://github.com/SinergiaTIC/SinergiaCRM/pull/800 *}
            {* <img src="{$LOGO}"/> *}
            <img class="center-block" style="max-width: 450px; max-height: 250px;" src="{$LOGO}" />
            {* END STIC-Custom *}
        </div>
    </div>
    <div class="row well">
        <div class="col-md-offset-2 col-md-8">
            <h1>{$SURVEY->name}</h1>
            <p>
                <strong>
                    {$MESSAGE}
                </strong>
            </p>
        </div>
    </div>
</div>
{$FOOTER}

