{*
 * This file is part of SinergiaCRM.
 * SinergiaCRM is a work developed by SinergiaTIC Association, based on SuiteCRM.
 * Copyright (C) 2013 - 2023 SinergiaTIC Association
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SinergiaTIC Association at email address info@sinergiacrm.org.
 *}
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

        .box.active {
            background-color: #007cba;
            color: white;
        }

        .row {
            min-width: 200px;
            text-align: center;
            padding: 5px 10px;
            display: inline-block;
            margin-right: 10px;
        }

        .record-row {
            border-bottom: 1px solid #eee;
            padding: 10px 0;
        }

        .record-row:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .unavailable-resources {
            color: #d9534f;
            font-weight: bold;
        }

        .available-resources {
            color: #5cb85c;
            font-weight: bold;
        }

        .records-section {
            margin: 20px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
        }

        .records-section h4 {
            margin-top: 0;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .records-section.created {
            background-color: #f8fff8;
            border-color: #d4edda;
        }

        .records-section.not-created {
            background-color: #fff8f8;
            border-color: #f5c6cb;
        }

        .section-toggle {
            cursor: pointer;
            user-select: none;
        }

        .section-toggle:hover {
            text-decoration: underline;
        }

        .section-content {
            display: block;
        }

        .section-content.collapsed {
            display: none;
        }

        .toggle-icon {
            margin-right: 5px;
            font-weight: bold;
        }

        .no-records {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 20px;
        }

        .booking-link {
            color: #007cba;
            text-decoration: none;
        }

        .booking-link:hover {
            text-decoration: underline;
        }
    </style>
{/literal}

<script>
    var consolidatedSummaryData = {$CONSOLIDATED_SUMMARY};
    const pageSize = {$RECORDS_PER_PAGE};
    let createdRecords = [];
    let notCreatedRecords = [];
    let currentCreatedPage = 1;
    let currentNotCreatedPage = 1;
</script>

<h1>{$MOD.LBL_PERIODIC_BOOKINGS_BUTTON}</h1>
<h2>{$MOD.LBL_PERIODIC_BOOKINGS_SUMMARY_TITLE}</h2>

<div class="layer">
    <span>{$MOD.LBL_PERIODIC_BOOKINGS_SUMMARY_RECORDS_PROCESSED} = {$DATA.totalRecordsProcessed}</span>
    <br /><span class="available-resources">{$MOD.LBL_PERIODIC_BOOKINGS_SUMMARY_RECORDS_CREATED} = {$DATA.totalRecordsCreated|default:0}</span>
    <br /><span class="unavailable-resources">{$MOD.LBL_PERIODIC_BOOKINGS_SUMMARY_RECORDS_NOT_CREATED} = {$DATA.totalRecordsNotCreated}</span>
</div>
<br />

<div id="consolidatedSummaryContent">
    <div class="records-section created">
        <h4 class="section-toggle" onclick="toggleSection('created')">
            <span class="toggle-icon" id="created-icon">▼</span>
            <span class="available-resources">{$MOD.LBL_PERIODIC_BOOKINGS_SUMMARY_RECORDS_CREATED}</span> 
            (<span id="createdRecordsCount">0</span>)
        </h4>
        <div id="createdSection" class="section-content">
            <div id="createdRecordsContainer" class="recordsContainer">
                <div style="font-weight:bold"> 
                    <span class='row'>Booking</span>
                    <span class='row'>{$MOD.LBL_START_DATE}</span>
                    <span class='row'>{$MOD.LBL_END_DATE}</span>
                    <span class='row'>Recursos Solicitados</span>
                </div>
                <div id="createdRecordsList">
                    </div>
            </div>
            <div class="pagination-container" id="createdPagination">
                </div>
        </div>
    </div>

    <div class="records-section not-created">
        <h4 class="section-toggle" onclick="toggleSection('notCreated')">
            <span class="toggle-icon" id="notCreated-icon">▼</span>
            <span class="unavailable-resources">{$MOD.LBL_PERIODIC_BOOKINGS_SUMMARY_RECORDS_NOT_CREATED}</span> 
            (<span id="notCreatedRecordsCount">0</span>)
        </h4>
        <div id="notCreatedSection" class="section-content">
            <div id="notCreatedRecordsContainer" class="recordsContainer">
                <span>{$MOD.LBL_PERIODIC_BOOKINGS_SUMMARY_RECORDS_NOT_CREATED_TEXT}</span>
                <br>
                <span>{$MOD.LBL_PERIODIC_BOOKINGS_SUMMARY_RECORDS_NOT_CREATED_TEXT2}</span>
                <br /><br />
                
                <div style="font-weight:bold"> 
                    <span class='row'>{$MOD.LBL_START_DATE}</span>
                    <span class='row'>{$MOD.LBL_END_DATE}</span>
                    <span class='row'>Recursos Solicitados</span>
                    <span class='row'>{$MOD.LBL_UNAVAILABLE_RESOURCES}</span>
                </div>
                <div id="notCreatedRecordsList">
                    </div>
            </div>
            <div class="pagination-container" id="notCreatedPagination">
                </div>
        </div>
    </div>
</div>

<br /><br />    
<div>
    <a href="index.php?module=stic_Bookings" style="margin-right: 2em">
        <button type='button' class='button'>{$MOD.LBL_PERIODIC_BOOKINGS_SUMMARY_BUTTON_BOOKINGS}</button>
    </a>
</div>

<br /><br />    
<div>
    <a href="index.php?module=stic_Bookings_Calendar&action=index" style="margin-right: 2em">
        <button type='button' class='button'>{$MOD.LBL_PERIODIC_BOOKINGS_SUMMARY_BUTTON_BOOKINGS_CALENDAR}</button>
    </a>

    <a href="index.php?module=stic_Bookings_Places_Calendar&action=index">
        <button type='button' class='button'>{$MOD.LBL_PERIODIC_BOOKINGS_SUMMARY_BUTTON_PLACES_CALENDAR}</button>
    </a>
</div>

<script>
    {literal}
        document.addEventListener('DOMContentLoaded', function() {
            initializeConsolidatedSummary();
        });

        function initializeConsolidatedSummary() {
            if (!consolidatedSummaryData || consolidatedSummaryData.length === 0) {
                return;
            }
            
            createdRecords = consolidatedSummaryData.filter(record => record.status === 'created');
            notCreatedRecords = consolidatedSummaryData.filter(record => record.status === 'not_created');

            document.getElementById('createdRecordsCount').textContent = createdRecords.length || 0;
            document.getElementById('notCreatedRecordsCount').textContent = notCreatedRecords.length || 0;
            
            displayCreatedRecords(createdRecords, currentCreatedPage);
            displayNotCreatedRecords(notCreatedRecords, currentNotCreatedPage);
        }

        function displayCreatedRecords(records, page) {
            const recordsList = document.getElementById('createdRecordsList');
            
            if (!records || records.length === 0) {
                recordsList.innerHTML = '<div class="no-records">No se crearon registros.</div>';
                document.getElementById('createdPagination').innerHTML = '';
                return;
            }
            
            recordsList.innerHTML = '';
            const startIndex = (page - 1) * pageSize;
            const endIndex = startIndex + pageSize;
            const pageRecords = records.slice(startIndex, endIndex);
            pageRecords.forEach(record => {

                const recordRow = document.createElement('div');
                recordRow.className = 'record-row';

                const bookingSpan = document.createElement('span');
                bookingSpan.className = 'row';
                if (record.bookingId) {
                    const bookingLink = document.createElement('a');
                    bookingLink.href = `index.php?module=stic_Bookings&action=DetailView&record=${record.bookingId}`;
                    bookingLink.className = 'booking-link';
                    bookingLink.textContent = record.bookingName || `Booking ${record.bookingId}`;
                    bookingSpan.appendChild(bookingLink);
                } else {
                    bookingSpan.textContent = record.bookingName;
                }
                recordRow.appendChild(bookingSpan);

                const startDateSpan = document.createElement('span');
                startDateSpan.className = 'row';
                startDateSpan.textContent = formatDate(record.startDate) || '';
                recordRow.appendChild(startDateSpan);
                
                const endDateSpan = document.createElement('span');
                endDateSpan.className = 'row';
                endDateSpan.textContent = formatDate(record.endDate) || '';
                recordRow.appendChild(endDateSpan);

                                
                const requestedResourcesSpan = document.createElement('span');
                requestedResourcesSpan.className = 'row';
                requestedResourcesSpan.textContent = record.allRequestedResources.join(', ');
                recordRow.appendChild(requestedResourcesSpan);
                
                recordsList.appendChild(recordRow);
            });
            renderPagination('created', page, records.length);
        }

        function displayNotCreatedRecords(records, page) {
            const recordsList = document.getElementById('notCreatedRecordsList');
            
            if (!records || records.length === 0) {
                recordsList.innerHTML = '<div class="no-records">Todos los registros se crearon exitosamente.</div>';
                document.getElementById('notCreatedPagination').innerHTML = '';
                return;
            }
            
            recordsList.innerHTML = '';
            const startIndex = (page - 1) * pageSize;
            const endIndex = startIndex + pageSize;
            const pageRecords = records.slice(startIndex, endIndex);
            pageRecords.forEach(record => {
                const recordRow = document.createElement('div');
                recordRow.className = 'record-row';
                
                const startDateSpan = document.createElement('span');
                startDateSpan.className = 'row';
                startDateSpan.textContent = formatDate(record.startDate) || '';
                recordRow.appendChild(startDateSpan);
                
                const endDateSpan = document.createElement('span');
                endDateSpan.className = 'row';
                endDateSpan.textContent = formatDate(record.endDate) || '';
                recordRow.appendChild(endDateSpan);
                
                const requestedResourcesSpan = document.createElement('span');
                requestedResourcesSpan.className = 'row';
                requestedResourcesSpan.textContent = record.allRequestedResources.join(', ');
                recordRow.appendChild(requestedResourcesSpan);

                // Recursos no disponibles
                const unavailableSpan = document.createElement('span');
                unavailableSpan.className = 'row unavailable-resources';
                unavailableSpan.textContent = formatUnavailableResources(record.unavailableResources);
                recordRow.appendChild(unavailableSpan);
                
                recordsList.appendChild(recordRow);
            });
            // Renderizar paginación
            renderPagination('notCreated', page, records.length);
        }

        // Crear la paginación
        function renderPagination(type, currentPageNum, totalRecords) {
            const paginationContainer = document.getElementById(type + 'Pagination');
            paginationContainer.innerHTML = '';
            
            if (totalRecords <= pageSize) return;
            
            const totalPages = Math.ceil(totalRecords / pageSize);
            for (let i = 1; i <= totalPages; i++) {
                const pageButton = document.createElement('span');
                pageButton.className = 'box' + (i === currentPageNum ? ' active' : '');
                pageButton.textContent = i;
                pageButton.addEventListener('click', () => {
                    if (type === 'created') {
                        currentCreatedPage = i;
                        displayCreatedRecords(createdRecords, i);
                    } else {
                        currentNotCreatedPage = i;
                        displayNotCreatedRecords(notCreatedRecords, i);
                    }
                });
                paginationContainer.appendChild(pageButton);
            }
        }

        function toggleSection(sectionType) {
            const section = document.getElementById(sectionType + 'Section');
            const icon = document.getElementById(sectionType + '-icon');
            
            if (section.classList.contains('collapsed')) {
                section.classList.remove('collapsed');
                icon.textContent = '▼';
            } else {
                section.classList.add('collapsed');
                icon.textContent = '▶';
            }
        }

        function formatDate(dateString) {
            if (!dateString) return '';
            
            if (typeof dateString === 'string' && dateString.includes('/')) {
                return dateString;
            }
            
            try {
                const date = new Date(dateString);
                if (!isNaN(date.getTime())) {
                    return date.toLocaleDateString('es-ES');
                }
            } catch (e) {
                console.log('Error formateando fecha:', e);
            }
            
            return dateString;
        }

        function formatUnavailableResources(unavailableResources) {
            if (!unavailableResources || unavailableResources.length === 0) return 'Ninguno';
            return unavailableResources.join(', ');
        }
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeConsolidatedSummary);
        } else {
            initializeConsolidatedSummary();
        }
    {/literal}
</script>