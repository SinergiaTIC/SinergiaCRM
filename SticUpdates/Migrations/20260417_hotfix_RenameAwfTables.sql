-- Renaming AWF tables to lowercase
RENAME TABLE IF EXISTS stic_AWF_Forms TO stic_awf_forms;
RENAME TABLE IF EXISTS stic_AWF_Responses TO stic_awf_responses;
RENAME TABLE IF EXISTS stic_AWF_Response_Details TO stic_awf_response_details;
RENAME TABLE IF EXISTS stic_AWF_Links TO stic_awf_links;
RENAME TABLE IF EXISTS stic_AWF_Deferred_Tickets TO stic_awf_deferred_tickets;
RENAME TABLE IF EXISTS stic_AWF_Incoming_Events TO stic_awf_incoming_events;

-- Renaming AWF audit tables to lowercase
RENAME TABLE IF EXISTS stic_AWF_Forms_audit TO stic_awf_forms_audit ; 
RENAME TABLE IF EXISTS stic_AWF_Responses_audit TO stic_awf_responses_audit;
RENAME TABLE IF EXISTS stic_AWF_Response_Details_audit TO stic_awf_response_details_audit;
RENAME TABLE IF EXISTS stic_AWF_Links_audit TO stic_awf_links_audit;
RENAME TABLE IF EXISTS stic_AWF_Deferred_Tickets_audit TO stic_awf_deferred_tickets_audit;
RENAME TABLE IF EXISTS stic_AWF_Incoming_Events_audit TO stic_awf_incoming_events_audit;
