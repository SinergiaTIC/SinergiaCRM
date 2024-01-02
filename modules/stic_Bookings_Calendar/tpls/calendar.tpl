<div id='loading'>{$APP.LBL_LOADING_PAGE}</div>

<div id='calendar'></div>
{literal}
    <style>
        .option {
            cursor: pointer !important;
        }

        #loading {
            display: none;
            position: absolute;
            top: 10px;
            right: 10px;
        }

        #calendar {
            max-width: 1400px;
            margin: 0 auto;
            /* padding: 0 10px; */
        }

        :root {
            --fc-list-event-hover-bg-color: none;
        }
        .fc-header-toolbar {
            visibility: visible;
            height: auto;
        }

        .fc-col-header-cell {
            display: table-cell !important;
        }

        @media screen and (max-width:767px) {
            .fc-toolbar.fc-header-toolbar {
                flex-direction: column;
                display: contents;
            }

            .fc-toolbar-chunk {
                display: table-row;
                text-align: center;
                padding: 5px 0;
            }
        }

        .fc-button-primary {
            background-color: #353535 !important;
        }

        .fc-button-active {
            background-color: #0A0A0A !important;
        }

        .fc-col-header-cell {
            background-color: #353535 !important;
        }

        .fc-col-header-cell-cushion {
            color: white !important;
        }
    </style>
{/literal}