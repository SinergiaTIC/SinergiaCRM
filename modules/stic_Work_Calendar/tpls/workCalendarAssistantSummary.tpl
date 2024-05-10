{literal}
    <style>
        .layer {
            margin: 1em;
            font-weight: bold;
            font-size: 1.2em;          
        }

        .recordsContainer {
            margin: 20px 0px;
        }

        .pagination-container {
            margin-top: 20px;
        }
        
        .box {
            padding: 5px 10px;
            margin: 2px 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
            cursor: pointer;
        }

        .box:hover {
            background-color: #f0f0f0;
        }

        .row {
            min-width: 200px;
            text-align: center;
            padding: 5px 10px;
            display: inline-block; /* Cambio a inline-block para que aparezcan en la misma línea */
            margin-right: 10px; /* Añado margen derecho para separarlas un poco */
        }
    </style>
{/literal}

<script>
    var data = {$RECORDS_NOT_CREATED_BY_EMPLOYEE};
    const pageSize = {$RECORDS_PER_PAGE}; 
</script>


<h1>{$MOD.LBL_PERIODIC_WORK_CALENDAR_BUTTON}</h1>
<h2>{$MOD.LBL_PERIODIC_WORK_CALENDAR_SUMMARY_TITLE}</h2>

<div class="layer">
    <span>{$MOD.LBL_PERIODIC_WORK_CALENDAR_SUMMARY_RECORDS_PROCESSED} = {$DATA.totalRecordsProcessed}</span>
    <br /><span style='color:green'>{$MOD.LBL_PERIODIC_WORK_CALENDAR_SUMMARY_RECORDS_CREATED} = {$DATA.totalRecordsCreated ?? 0}</span>
    <br /><span style='color:red'>{$MOD.LBL_PERIODIC_WORK_CALENDAR_SUMMARY_RECORDS_NOT_CREATED} = {$DATA.totalRecordsNotCreated}</span>
</div>
<br />

<h2>{$MOD.LBL_PERIODIC_WORK_CALENDAR_SUMMARY_TITLE_BY_USER}:</h2>
<br />

<ul id="usersContainer" class="nav nav-tabs"> 
</ul>

<div style="margin: 2em 1em;">
    <span class="box" >{$MOD.LBL_PERIODIC_WORK_CALENDAR_SUMMARY_RECORDS_PROCESSED} = <span id="userRecordsProcessed"></span></span>
    <span class="box" style='color:green'>{$MOD.LBL_PERIODIC_WORK_CALENDAR_SUMMARY_RECORDS_CREATED} = <span id="userRecordsCreated"></span></span>
    <span class="box" style='color:red'>{$MOD.LBL_PERIODIC_WORK_CALENDAR_SUMMARY_RECORDS_NOT_CREATED} = <span id="userRecordsNotCreated"</span></span>
</div>
<br />

{if ($DATA.totalRecordsNotCreated > 0)}
    <div id="listContainer">
        <h3>{$MOD.LBL_PERIODIC_WORK_CALENDAR_SUMMARY_RECORDS_NOT_CREATED_TITLE}</h3>
        <span>{$MOD.LBL_PERIODIC_WORK_CALENDAR_SUMMARY_RECORDS_NOT_CREATED_TEXT}</span>
        <br>
        <span>{$MOD.LBL_PERIODIC_WORK_CALENDAR_SUMMARY_RECORDS_NOT_CREATED_TEXT2}</span>
        <br />

        <div id="recordsContainer" class="recordsContainer">
            <div style="font-weight:bold"> 
                <span class='row'>Nombre de usuario</span>
                <span class='row'>Fecha y hora de inicio</span>
                <span class='row'>Fecha y hora de finalización</span>
                <span class='row'>Tipo</span>
            </div>
            <div id="list"></div>
        </div>

        <div class="pagination-container" id="pagination"></div>
    </div>
{/if}
<br /><br />    

<div>
    <a href="index.php?module=Employees&action=index" style="margin-right: 2em">
        <button type='button' class='button'>{$MOD.LBL_PERIODIC_WORK_CALENDAR_SUMMARY_BUTTON_EMPLOYEES}</button>
    </a>

    <a href="index.php?module=stic_Work_Calendar&action=index">
        <button type='button' class='button'>{$MOD.LBL_PERIODIC_WORK_CALENDAR_SUMMARY_BUTTON_WOK_CALENDAR}</button>
    </a>
</div>

<script>
    {literal}

        activeUser = '';

        // Función para renderizar la lista
        function renderUsersWithRecordsNotCreated(pageNumber, pageSize) 
        {
            const usersContainer = document.getElementById('usersContainer');
            let firstUser = true;
            for (let key in data) 
            {
                // User Not Created Records
                liItem = document.createElement("li");
                liItem.id = 'liUser' + key;
                aItem = document.createElement("a");
                aItem.textContent = data[key].name;
                aItem.id = 'aUser' + key;
                aItem.style.margin='2px';                
                aItem.setAttribute("onclick", "renderRecordsNotCreatedAndPagination('" + key + "', 1, pageSize);");
                liItem.appendChild(aItem)
                usersContainer.appendChild(liItem);                

                // Activate the first user
                if (firstUser) {
                    firstUser = false;
                    activeUser = key;
                    renderRecordsNotCreatedAndPagination(activeUser, 1, pageSize);
                }
            }

        }

        // Función para renderizar la lista
        function renderRecordsNotCreatedAndPagination(currentUserId, pageNumber, pageSize) 
        {
            // User summary
            document.getElementById('userRecordsProcessed').innerText = ' ' + data[currentUserId].numRecordsProcessed;
            document.getElementById('userRecordsCreated').innerText = ' ' + data[currentUserId].numRecordsCreated;
            document.getElementById('userRecordsNotCreated').innerText = ' ' + data[currentUserId].numRecordsNotCreated;
            
            // Manage activeUser
            document.getElementById('liUser' + activeUser).classList.remove("active");
            document.getElementById('aUser' + activeUser).classList.remove("current");
            document.getElementById('liUser' + currentUserId).classList.add("active");
            document.getElementById('aUser' + currentUserId).classList.add("current");
            activeUser = currentUserId;


            // User Records Not Created
            if (data[currentUserId].numRecordsNotCreated > 0) {
                renderRecordsNotCreated(currentUserId, pageNumber, pageSize);
                renderPagination(currentUserId, pageNumber, pageSize, data[currentUserId].numRecordsNotCreated);
            } else {
               document.getElementById('listContainer').style.display='none';
            }
        }


        // Función para renderizar la lista
        function renderRecordsNotCreated(currentUserId, pageNumber, pageSize) 
        {
            document.getElementById('listContainer').style.display='block';
            const listContainer = document.getElementById('list');
            listContainer.innerHTML = '';
            const startIndex = (pageNumber - 1) * pageSize;
            const pageData = data[currentUserId].recordsNotCreated.slice(startIndex, startIndex + pageSize);

            pageData.forEach(item => {
                spanItem = document.createElement("span");
                spanItem.classList.add("row");
                spanItem.textContent = `${item.username}`;
                listContainer.appendChild(spanItem);
                
                spanItem = document.createElement("span");
                spanItem.classList.add("row");
                spanItem.textContent = `${item.startDate}`;
                listContainer.appendChild(spanItem);

                spanItem = document.createElement("span");
                spanItem.classList.add("row");
                spanItem.textContent = `${item.endDate}`;
                listContainer.appendChild(spanItem);
                
                spanItem = document.createElement("span");
                spanItem.classList.add("row");
                spanItem.textContent = `${item.type}`;
                listContainer.appendChild(spanItem);

                brItem = document.createElement("br");
                listContainer.appendChild(brItem);
            });           
        }

        // Función para renderizar el paginador
        function renderPagination(currentUserId, pageNumber, pageSize, totalItems) 
        {
            const paginationContainer = document.getElementById('pagination');
            paginationContainer.innerHTML = '';

            const totalPages = Math.ceil(totalItems / pageSize);

            for (let i = 1; i <= totalPages; i++) {
                const pageButton = document.createElement('span');
                pageButton.textContent = i;
                pageButton.classList.add('box');
                pageButton.addEventListener('click', () => {
                    renderRecordsNotCreatedAndPagination(currentUserId, i, pageSize);
                });
                paginationContainer.appendChild(pageButton);
            }
        }

        // Renderizar la lista y el paginador inicialmente
        renderUsersWithRecordsNotCreated(1, pageSize);
    {/literal}
</script>


