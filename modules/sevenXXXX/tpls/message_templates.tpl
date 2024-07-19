<label for='seven_templates'>{$MOD.LBL_SEVEN_TEMPLATES}</label>
<select id='seven_templates'>
    <option></option>
    {foreach $templates as $template}
        <option value='{$template->id}' data-content='{$template->text}'>{$template->label}</option>
    {/foreach}
</select>
<button class='button' id='seven_template_create'>{$MOD.LBL_SEVEN_CREATE}</button>
<button class='button' disabled id='seven_template_edit'>{$MOD.LBL_SEVEN_EDIT}</button>
{include file='modules/seven/tpls/template_delete.tpl'}

<form
        action='/index.php?module=seven&action=message_templates'
        class='yui-hidden'
        id='seven_template_dialog'
>
    <input name='seven_module_type' type='hidden' />
    <input name='seven_template_id' type='hidden' />
    <input name='seven_action' type='hidden' value='save_template' />

    <table border='0' cellpadding='0' cellspacing='0' class='edit view' width='100%' >
        <tr>
            <td scope='row'>
                <label for='seven_template_text'>{$MOD.LBL_SEVEN_TEXT}</label>
                <span class='required'></span>
            </td>
            <td>
                <textarea
                        cols='80'
                        id='seven_template_text'
                        name='seven_template_text'
                        required
                        rows='10'
                        tabindex='0'
                >{$template_text}</textarea>
            </td>
        </tr>
        <tr>
            <td scope='row'>
                <label for='seven_template_label'>{$MOD.LBL_SEVEN_LABEL}</label>
                <span class='required'></span>
            </td>
            <td>
                <input
                        id='seven_template_label'
                        maxlength='255'
                        name='seven_template_label'
                        required
                        value='{$template_label}'
                />
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

{literal}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('seven_template_dialog')
        const templates = document.getElementById('seven_templates')
        const createTemplate = document.getElementById('seven_template_create')
        const editTemplate = document.getElementById('seven_template_edit')
        const text = document.getElementById('seven_text')
        const moduleType = form.elements.namedItem('seven_module_type')
        const templateText = form.elements.namedItem('seven_template_text')
        const templateLabel = form.elements.namedItem('seven_template_label')
        const templateId = form.elements.namedItem('seven_template_id')
        const getSelected = () => templates.options[templates.selectedIndex]
        const getOptionContent = () => getSelected().dataset.content
        const getOptionLabel = () => getSelected().textContent
        const getOptionID = () => getSelected().value

        moduleType.value = window.action_sugar_grp1

        templates.addEventListener('change', e => {
            editTemplate.disabled = !e.target.value

            text.value = getOptionContent() ?? ''
        })

        form.addEventListener('submit', e => {
            e.preventDefault()

            console.log('onSubmit')
        })

        createTemplate.addEventListener('click', e => {
            e.preventDefault()

            renderModal('{/literal}{$MOD.LBL_SEVEN_TEMPLATES_CREATE}{literal}')
        })

        editTemplate.addEventListener('click', e => {
            e.preventDefault()

            templateText.value = getOptionContent()
            templateLabel.value = getOptionLabel()
            templateId.value = getOptionID()

            renderModal('{/literal}{$MOD.LBL_SEVEN_TEMPLATES_EDIT}{literal}')
        })

        function renderModal(header) {
            const dialog = new YAHOO.widget.Dialog(form, {
                constraintoviewport: true,
                fixedcenter: true,
                height: 600,
                modal: true,
                visible: true,
                width: 800,
            })
            form.classList.toggle('yui-hidden')
            dialog.setHeader(header)
            dialog.render()
            dialog.show()
        }
    })
</script>
{/literal}
