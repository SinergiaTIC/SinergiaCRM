<button
        class='button'
        disabled
        id='seven_template_delete'
        type='button'
>{$MOD.LBL_SEVEN_TEMPLATES_DELETE}</button>

<div class='yui-hidden' id='seven_template_delete_dialog'>
    <form action='/index.php?module=seven&action=message_templates' method='post'>
        <input name='seven_module_type' type='hidden' />
        <input name='seven_template_id' type='hidden' />
        <input name='seven_action' type='hidden' value='delete_template' />

        <table border='0' cellpadding='0' cellspacing='0' class='edit view' width='100%' >
            <tr>
                <td colspan='2' scope='row'>
                    <p>{$MOD.LBL_SEVEN_ARE_YOU_SURE}</p>
                </td>
            </tr>
            <tr>
                <td colspan='2' scope='row'>
                    <button
                            class='button'
                            title='{$APP.LBL_SAVE_BUTTON_TITLE}'
                            type='submit'
                    >
                        {$APP.LBL_SAVE_BUTTON_LABEL}
                    </button>
                </td>
            </tr>
        </table>
    </form>
</div>

{literal}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('seven_template_delete_dialog')
        const templates = document.getElementById('seven_templates')
        const deleteButton = document.getElementById('seven_template_delete')
        const form = modal.querySelector('form')

        form.elements.namedItem('seven_module_type').value = window.action_sugar_grp1

        templates.addEventListener('change', e => {
            deleteButton.disabled = !e.target.value
        })

        deleteButton.addEventListener('click', e => {
            e.preventDefault()

            form.elements.namedItem('seven_template_id').value
                = templates.options[templates.selectedIndex].value

            modal.classList.toggle('yui-hidden')

            const dialog = new YAHOO.widget.Dialog(modal, {
                constraintoviewport: true,
                fixedcenter: true,
                height: 600,
                modal: true,
                visible: true,
                width: 800,
            })
            dialog.setHeader('{/literal}{$MOD.LBL_SEVEN_TEMPLATES_DELETE}{literal}')
            dialog.render()
            dialog.show()
        })
    })
</script>
{/literal}
