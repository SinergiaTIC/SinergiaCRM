<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

/**
 * MMR calculated fields for the stic_Sessions module.
 *
 * Adds session attendee information (Contact first name and last name) by
 * traversing the relationship path:
 *
 *   stic_Sessions --(stic_attendances_stic_sessions)--> stic_Attendances
 *       --(stic_attendances_stic_registrations)--> stic_Registrations
 *       --(stic_registrations_contacts)--> Contacts
 *
 * This should apply to any MMR template based on stic_Sessions, allowing:
 *
 * 1) Full attendee list within a single variable (deduplicated and sorted names):
 *    [a.cf_attendees_list]
 *
 * 2) A table with one row per attendance, inside the stic_Attendances relational
 *    block (the main block declares sub1=stic_attendances_stic_sessions):
 *      table row (w:tr) marked as block:  [a_sub1;block=w:tr]
 *      and fields per cell:
 *        [a_sub1.cf_attendee_firstname]
 *        [a_sub1.cf_attendee_lastname]
 *        [a_sub1.cf_attendee_name]
 *
 * How it works:
 * - The full list is computed in CalcFields() (main module rows).
 * - The per-attendance-row fields are injected in BeforeMergeBlock() into the MMR
 *   $datos array: level-1 related rows are instantiated with the row's module
 *   (stic_Attendances), so they cannot use a stic_Sessions class.
 *   This is the same pattern used by custom/modules/intress_Diary_Threads in the
 *   GIA instance.
 * - The related rows in $datos do include the 'id' key (manually added in
 *   Generate_Document.php), but the main module rows do not reliably include it:
 *   that is why the contact resolution is done PER ATTENDANCE ROW (loading the
 *   stic_Attendances bean by its id) instead of through the session bean.
 * - In BeforeMergeBlock() the instance bean ($this->bean) points to the last
 *   processed row, so it cannot be used as the row's data source.
 *
 * Note: GetCalculatedFieldsClass() requires the class name to be
 * <MODULE>_DocumentTemplatesCalculatedFields, and it also takes into account a
 * possible 'Custom'-prefixed class if it exists (e.g. for point overrides).
 */

require_once 'modules/DHA_PlantillasDocumentos/DHA_DocumentTemplatesCalculatedFields_base.php';

class stic_Sessions_DocumentTemplatesCalculatedFields extends DHA_DocumentTemplatesCalculatedFields
{
    /** Name of the Sessions -> Attendances link (from the stic_Sessions side) */
    protected $attendancesLink = 'stic_attendances_stic_sessions';

    /** Name of the Attendances -> Registrations link */
    protected $registrationsLink = 'stic_attendances_stic_registrations';

    /** Name of the Registrations -> Contacts link */
    protected $contactsLink = 'stic_registrations_contacts';

    /** Name of the attendances module */
    protected $moduleAttendances = 'stic_Attendances';

    /** Name of the registrations module */
    protected $moduleRegistrations = 'stic_Registrations';

    /** Name of the contacts module */
    protected $moduleContacts = 'Contacts';

    /** Contact resolution cache by attendance id (false when it cannot be resolved) */
    private $contactByAttendance = array();

    ///////////////////////////////////////////////////////////////////////////////////////////////////
    protected function SetCalcFieldsDefs()
    {
        // List of all attendees of the session (main module field)
        $this->CalcFieldsDefs['attendees_list'] = array(
            'name' => 'attendees_list',
            'vname' => 'Assistents - Llista de participants (calculat)',
            'type' => 'text',
            'help' => "Noms dels contactes assistents a la sessió (nom i llinatges), un per línia, deduplicats i ordenats. Camí calculat: Assistència -> Registre -> Contacte.",
        );

        // Per-attendance contact fields (cf_attendee_name, cf_attendee_firstname,
        // cf_attendee_lastname and cf_attendee_id): injected via BeforeMergeBlock() into
        // the relational block rows. They are not declared here because related rows do
        // not use this class. They are still available in the template.
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////
    public function CalcFields()
    {
        // Keep the common fields from the base class (current_date, currency_*, etc.)
        parent::CalcFields();

        // The attendee list is computed for any stic_Sessions bean
        // (main module or related module in another template).
        if ($this->module == 'stic_Sessions' && !empty($this->bean->id)) {
            $attendees = $this->getSessionAttendees($this->bean);
            $names = array();
            foreach ($attendees as $attendeeData) {
                $names[] = $attendeeData['name'];
            }
            // Deduplicate and sort regardless of the retrieval order
            $names = array_values(array_unique($names));
            natsort($names);

            // Line breaks for Word documents: a plain "\n" is ignored by Word, and a
            // "<w:br/>" inside "<w:t>" is invalid OOXML (Word drops it). The valid
            // structure is one run per line: "Name</w:t></w:r><w:r><w:br/><w:t>Name2".
            // The template field must merge this value with parameter 'strconv=no'
            // (otherwise TBS would escape the injected XML as plain text).
            // Names are escaped twice because DHA runs html_entity_decode() on
            // 'text' fields (FormatDocumentField) before the merge.
            $break = '</w:t></w:r><w:r><w:br/><w:t>';
            $namesXml = array_map(function ($name) {
                return htmlspecialchars(htmlspecialchars($name, ENT_QUOTES), ENT_QUOTES);
            }, $names);
            $this->SetCalcValue('attendees_list', implode($break, $namesXml));
        }
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////
    public function BeforeMergeBlock()
    {
        // Only makes sense when stic_Sessions is the main module of the template
        if (!$this->inMainModule() || $this->module != 'stic_Sessions') {
            return;
        }

        $datos =& $this->Generate_Document_Instance->datos;
        if (empty($datos) || !is_array($datos)) {
            return;
        }

        foreach ($datos as $mainKey => $mainData) {
            if (empty($datos[$mainKey][$this->attendancesLink]) || !is_array($datos[$mainKey][$this->attendancesLink])) {
                continue;
            }

            foreach ($datos[$mainKey][$this->attendancesLink] as $rowKey => $attendanceRow) {
                // Related rows always include the 'id' key (Generate_Document.php)
                $attendanceId = isset($attendanceRow['id']) ? $attendanceRow['id'] : '';
                $contactData = $this->getAttendanceContactById($attendanceId);

                // ALWAYS inject all 4 keys (empty when no contact is resolved),
                // to avoid TBS errors due to missing keys at merge time
                $datos[$mainKey][$this->attendancesLink][$rowKey]['cf_attendee_name'] = $contactData ? $contactData['name'] : '';
                $datos[$mainKey][$this->attendancesLink][$rowKey]['cf_attendee_firstname'] = $contactData ? $contactData['first_name'] : '';
                $datos[$mainKey][$this->attendancesLink][$rowKey]['cf_attendee_lastname'] = $contactData ? $contactData['last_name'] : '';
                $datos[$mainKey][$this->attendancesLink][$rowKey]['cf_attendee_id'] = $contactData ? $contactData['contact_id'] : '';
            }
        }
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Retrieves the attendees of a session by traversing the path Attendance -> Registration -> Contact.
     *
     * @param \SugarBean $sessionBean Loaded stic_Sessions bean.
     * @return array List indexed by attendance id with: name, first_name, last_name and contact_id.
     */
    protected function getSessionAttendees($sessionBean)
    {
        $attendees = array();

        if (!$sessionBean->load_relationship($this->attendancesLink)) {
            return $attendees;
        }

        $attendances = $sessionBean->get_linked_beans($this->attendancesLink, $this->moduleAttendances);
        foreach ($attendances as $attendance) {
            $attendeesContactData = $this->getAttendanceContactById($attendance->id, $attendance);
            if ($attendeesContactData !== false) {
                $attendees[$attendance->id] = $attendeesContactData;
            }
        }

        return $attendees;
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Resolves the contact of an attendance (via its linked registration) by id,
     * using the instance cache.
     *
     * @param string $attendanceId Id of the stic_Attendances record.
     * @param \SugarBean|null $attendanceBean Already loaded bean (optional, saves the retrieve call).
     * @return array|false Contact data (name, first_name, last_name, contact_id) or false when it cannot be resolved.
     */
    protected function getAttendanceContactById($attendanceId, $attendanceBean = null)
    {
        if (empty($attendanceId)) {
            return false;
        }

        // Cache by attendance id: CalcFields() and BeforeMergeBlock() share results
        if (array_key_exists($attendanceId, $this->contactByAttendance)) {
            return $this->contactByAttendance[$attendanceId];
        }

        if ($attendanceBean === null) {
            $attendanceBean = BeanFactory::getBean($this->moduleAttendances, $attendanceId);
        }
        $contactData = ($attendanceBean && !empty($attendanceBean->id))
            ? $this->getAttendanceContact($attendanceBean)
            : false;

        $this->contactByAttendance[$attendanceId] = $contactData;

        return $contactData;
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////
    /**
     * Resolves the contact of an attendance via its linked registration (one attendance per registration).
     * Does not use the cache: use getAttendanceContactById() instead.
     *
     * @param \SugarBean $attendance stic_Attendances bean.
     * @return array|false Contact data or false when it cannot be resolved.
     */
    protected function getAttendanceContact($attendance)
    {
        if (!$attendance->load_relationship($this->registrationsLink)) {
            return false;
        }

        $registrations = $attendance->get_linked_beans($this->registrationsLink, $this->moduleRegistrations);
        foreach ($registrations as $registration) {
            if (!$registration->load_relationship($this->contactsLink)) {
                continue;
            }

            $contacts = $registration->get_linked_beans($this->contactsLink, $this->moduleContacts);
            foreach ($contacts as $contact) {
                return array(
                    'name' => $contact->name,
                    'first_name' => $contact->first_name,
                    'last_name' => $contact->last_name,
                    'contact_id' => $contact->id,
                );
            }
        }

        return false;
    }
}
