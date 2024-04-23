{literal}
    <style>
        .layer {
            margin: 15px 0px;
        }
        .pagination-container {
            margin-top: 20px;
        }
        .page-link {
            padding: 5px 10px;
            margin: 0 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
            cursor: pointer;
        }
        .page-link:hover {
            background-color: #f0f0f0;
        }
        .row {
            min-width: 200px;
            text-align: center;
            padding: 5px 10px;
            display: inline-block; /* Cambio a inline-block para que aparezcan en la misma línea */
            margin-right: 10px; /* Añado margen derecho para separarlas un poco */
        }

        .strong {
            font-weight: bold;
        }
    </style>
{/literal}

<h2>{$MOD.LBL_PERIODIC_WORK_CALENDAR_BUTTON}</h2>
<br /><br />
<p class="strong">{$MOD.LBL_PERIODIC_WORK_CALENDAR_SUMMARY_TITLE}</p>

<div class="layer">
    - <span class="strong" style='color:green'>{$MOD.LBL_PERIODIC_WORK_CALENDAR_SUMMARY_RECORDS_CREATED} = {$TOTAL_RECORDS_CREATED}</span>
    <br /><br />
    - <span class="strong" style='color:red'>{$MOD.LBL_PERIODIC_WORK_CALENDAR_SUMMARY_RECORDS_NOT_CREATED} = {$TOTAL_RECORDS_NOT_CREATED}</span>
</div>
<br /><br />

{if ($TOTAL_RECORDS_NOT_CREATED != 0)}
    <div class="layer">
        <h2>{$MOD.LBL_PERIODIC_WORK_CALENDAR_SUMMARY_RECORDS_NOT_CREATED_TITLE}</h2>
        <span>{$MOD.LBL_PERIODIC_WORK_CALENDAR_SUMMARY_RECORDS_NOT_CREATED_TEXT}</span>
        <br /><br />
        <div> 
            <span class='row strong'>Nombre de usuario</span>
            <span class='row strong'>Tipo</span>
            <span class='row strong'>Fecha y hora de inicio</span>
            <span class='row strong'>Fecha y hora de finalización</span>
        </div>
        <div id="list"></div>
    </div>

    <div class="pagination-container" id="pagination"></div>
    <br /><br />
{/if}

<div class="layer">
    <a href="index.php?module=Employees&action=index">
        <button type='button' class='button'>{$MOD.LBL_PERIODIC_WORK_CALENDAR_SUMMARY_BUTTON_EMPLOYEES}</button>
    </a>

    <a href="index.php?module=stic_Work_Calendar&action=index">
        <button type='button' class='button'>{$MOD.LBL_PERIODIC_WORK_CALENDAR_SUMMARY_BUTTON_WOK_CALENDAR}</button>
    </a>
</div>
<script>
    var data = {$RECORDS_NOT_CREATED};
    const pageSize = {$RECORDS_PER_PAGE}; 
</script>
<script>
    {literal}
        // Función para renderizar la lista
        function renderList(pageNumber, pageSize) 
        {
            const listContainer = document.getElementById('list');
            listContainer.innerHTML = '';

            const startIndex = (pageNumber - 1) * pageSize;
            const pageData = data.slice(startIndex, startIndex + pageSize);

            pageData.forEach(item => {
                spanItem = document.createElement("span");
                spanItem.classList.add("row");
                spanItem.textContent = `${item.username}`;
                listContainer.appendChild(spanItem);
                
                spanItem = document.createElement("span");
                spanItem.classList.add("row");
                spanItem.textContent = `${item.type}`;
                listContainer.appendChild(spanItem);

                spanItem = document.createElement("span");
                spanItem.classList.add("row");
                spanItem.textContent = `${item.startDate}`;
                listContainer.appendChild(spanItem);

                spanItem = document.createElement("span");
                spanItem.classList.add("row");
                spanItem.textContent = `${item.endDate}`;
                listContainer.appendChild(spanItem);
                
                brItem = document.createElement("br");
                listContainer.appendChild(brItem);
            });
        }

        // Función para renderizar el paginador
        function renderPagination(pageNumber, pageSize, totalItems) 
        {
            const paginationContainer = document.getElementById('pagination');
            paginationContainer.innerHTML = '';

            const totalPages = Math.ceil(totalItems / pageSize);

            for (let i = 1; i <= totalPages; i++) {
                const pageButton = document.createElement('span');
                pageButton.textContent = i;
                pageButton.classList.add('page-link');
                pageButton.addEventListener('click', () => {
                    renderList(i, pageSize);
                });
                paginationContainer.appendChild(pageButton);
            }
        }

        // Renderizar la lista y el paginador inicialmente
        renderList(1, pageSize);
        renderPagination(1, pageSize, data.length);
    {/literal}
</script>


