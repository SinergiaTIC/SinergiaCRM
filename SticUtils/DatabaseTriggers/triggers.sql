DROP TRIGGER IF EXISTS `contacts_before_insert`;
DROP TRIGGER IF EXISTS `leads_before_insert`;
DROP TRIGGER IF EXISTS `stic_payment_commitments_before_insert`;
DROP TRIGGER IF EXISTS `contacts_cstm_before_insert`;
DROP TRIGGER IF EXISTS `accounts_cstm_before_insert`;
DROP TRIGGER IF EXISTS `stic_payments_before_insert`;
DROP TRIGGER IF EXISTS `stic_remittances_before_insert`;
DROP TRIGGER IF EXISTS `stic_registrations_before_insert`;
DROP TRIGGER IF EXISTS `stic_events_before_insert`;
DROP TRIGGER IF EXISTS `stic_contacts_relationships_before_insert`;
DROP TRIGGER IF EXISTS `project_before_insert`;
DROP TRIGGER IF EXISTS `opportunities_cstm_before_insert`;
DROP TRIGGER IF EXISTS `leads_cstm_before_insert`;
DROP TRIGGER IF EXISTS `stic_settings_before_insert`;

DELIMITER $$
$$
CREATE TRIGGER `contacts_before_insert` BEFORE INSERT ON `contacts` FOR EACH ROW BEGIN
IF NEW.primary_address_state='99' THEN SET NEW.primary_address_state='99'; END IF;
IF NEW.primary_address_state='9' THEN SET NEW.primary_address_state='09'; END IF;
IF NEW.primary_address_state='8' THEN SET NEW.primary_address_state='08'; END IF;
IF NEW.primary_address_state='7' THEN SET NEW.primary_address_state='07'; END IF;
IF NEW.primary_address_state='6' THEN SET NEW.primary_address_state='06'; END IF;
IF NEW.primary_address_state='52' THEN SET NEW.primary_address_state='52'; END IF;
IF NEW.primary_address_state='51' THEN SET NEW.primary_address_state='51'; END IF;
IF NEW.primary_address_state='50' THEN SET NEW.primary_address_state='50'; END IF;
IF NEW.primary_address_state='5' THEN SET NEW.primary_address_state='05'; END IF;
IF NEW.primary_address_state='49' THEN SET NEW.primary_address_state='49'; END IF;
IF NEW.primary_address_state='48' THEN SET NEW.primary_address_state='48'; END IF;
IF NEW.primary_address_state='47' THEN SET NEW.primary_address_state='47'; END IF;
IF NEW.primary_address_state='46' THEN SET NEW.primary_address_state='46'; END IF;
IF NEW.primary_address_state='45' THEN SET NEW.primary_address_state='45'; END IF;
IF NEW.primary_address_state='44' THEN SET NEW.primary_address_state='44'; END IF;
IF NEW.primary_address_state='43' THEN SET NEW.primary_address_state='43'; END IF;
IF NEW.primary_address_state='42' THEN SET NEW.primary_address_state='42'; END IF;
IF NEW.primary_address_state='41' THEN SET NEW.primary_address_state='41'; END IF;
IF NEW.primary_address_state='40' THEN SET NEW.primary_address_state='40'; END IF;
IF NEW.primary_address_state='4' THEN SET NEW.primary_address_state='04'; END IF;
IF NEW.primary_address_state='39' THEN SET NEW.primary_address_state='39'; END IF;
IF NEW.primary_address_state='38' THEN SET NEW.primary_address_state='38'; END IF;
IF NEW.primary_address_state='37' THEN SET NEW.primary_address_state='37'; END IF;
IF NEW.primary_address_state='36' THEN SET NEW.primary_address_state='36'; END IF;
IF NEW.primary_address_state='35' THEN SET NEW.primary_address_state='35'; END IF;
IF NEW.primary_address_state='34' THEN SET NEW.primary_address_state='34'; END IF;
IF NEW.primary_address_state='33' THEN SET NEW.primary_address_state='33'; END IF;
IF NEW.primary_address_state='32' THEN SET NEW.primary_address_state='32'; END IF;
IF NEW.primary_address_state='31' THEN SET NEW.primary_address_state='31'; END IF;
IF NEW.primary_address_state='30' THEN SET NEW.primary_address_state='30'; END IF;
IF NEW.primary_address_state='3' THEN SET NEW.primary_address_state='03'; END IF;
IF NEW.primary_address_state='29' THEN SET NEW.primary_address_state='29'; END IF;
IF NEW.primary_address_state='28' THEN SET NEW.primary_address_state='28'; END IF;
IF NEW.primary_address_state='27' THEN SET NEW.primary_address_state='27'; END IF;
IF NEW.primary_address_state='26' THEN SET NEW.primary_address_state='26'; END IF;
IF NEW.primary_address_state='25' THEN SET NEW.primary_address_state='25'; END IF;
IF NEW.primary_address_state='24' THEN SET NEW.primary_address_state='24'; END IF;
IF NEW.primary_address_state='23' THEN SET NEW.primary_address_state='23'; END IF;
IF NEW.primary_address_state='22' THEN SET NEW.primary_address_state='22'; END IF;
IF NEW.primary_address_state='21' THEN SET NEW.primary_address_state='21'; END IF;
IF NEW.primary_address_state='20' THEN SET NEW.primary_address_state='20'; END IF;
IF NEW.primary_address_state='2' THEN SET NEW.primary_address_state='02'; END IF;
IF NEW.primary_address_state='19' THEN SET NEW.primary_address_state='19'; END IF;
IF NEW.primary_address_state='18' THEN SET NEW.primary_address_state='18'; END IF;
IF NEW.primary_address_state='17' THEN SET NEW.primary_address_state='17'; END IF;
IF NEW.primary_address_state='16' THEN SET NEW.primary_address_state='16'; END IF;
IF NEW.primary_address_state='15' THEN SET NEW.primary_address_state='15'; END IF;
IF NEW.primary_address_state='14' THEN SET NEW.primary_address_state='14'; END IF;
IF NEW.primary_address_state='13' THEN SET NEW.primary_address_state='13'; END IF;
IF NEW.primary_address_state='12' THEN SET NEW.primary_address_state='12'; END IF;
IF NEW.primary_address_state='11' THEN SET NEW.primary_address_state='11'; END IF;
IF NEW.primary_address_state='10' THEN SET NEW.primary_address_state='10'; END IF;
IF NEW.primary_address_state='1' THEN SET NEW.primary_address_state='01'; END IF;
IF NEW.alt_address_state='99' THEN SET NEW.alt_address_state='99'; END IF;
IF NEW.alt_address_state='9' THEN SET NEW.alt_address_state='09'; END IF;
IF NEW.alt_address_state='8' THEN SET NEW.alt_address_state='08'; END IF;
IF NEW.alt_address_state='7' THEN SET NEW.alt_address_state='07'; END IF;
IF NEW.alt_address_state='6' THEN SET NEW.alt_address_state='06'; END IF;
IF NEW.alt_address_state='52' THEN SET NEW.alt_address_state='52'; END IF;
IF NEW.alt_address_state='51' THEN SET NEW.alt_address_state='51'; END IF;
IF NEW.alt_address_state='50' THEN SET NEW.alt_address_state='50'; END IF;
IF NEW.alt_address_state='5' THEN SET NEW.alt_address_state='05'; END IF;
IF NEW.alt_address_state='49' THEN SET NEW.alt_address_state='49'; END IF;
IF NEW.alt_address_state='48' THEN SET NEW.alt_address_state='48'; END IF;
IF NEW.alt_address_state='47' THEN SET NEW.alt_address_state='47'; END IF;
IF NEW.alt_address_state='46' THEN SET NEW.alt_address_state='46'; END IF;
IF NEW.alt_address_state='45' THEN SET NEW.alt_address_state='45'; END IF;
IF NEW.alt_address_state='44' THEN SET NEW.alt_address_state='44'; END IF;
IF NEW.alt_address_state='43' THEN SET NEW.alt_address_state='43'; END IF;
IF NEW.alt_address_state='42' THEN SET NEW.alt_address_state='42'; END IF;
IF NEW.alt_address_state='41' THEN SET NEW.alt_address_state='41'; END IF;
IF NEW.alt_address_state='40' THEN SET NEW.alt_address_state='40'; END IF;
IF NEW.alt_address_state='4' THEN SET NEW.alt_address_state='04'; END IF;
IF NEW.alt_address_state='39' THEN SET NEW.alt_address_state='39'; END IF;
IF NEW.alt_address_state='38' THEN SET NEW.alt_address_state='38'; END IF;
IF NEW.alt_address_state='37' THEN SET NEW.alt_address_state='37'; END IF;
IF NEW.alt_address_state='36' THEN SET NEW.alt_address_state='36'; END IF;
IF NEW.alt_address_state='35' THEN SET NEW.alt_address_state='35'; END IF;
IF NEW.alt_address_state='34' THEN SET NEW.alt_address_state='34'; END IF;
IF NEW.alt_address_state='33' THEN SET NEW.alt_address_state='33'; END IF;
IF NEW.alt_address_state='32' THEN SET NEW.alt_address_state='32'; END IF;
IF NEW.alt_address_state='31' THEN SET NEW.alt_address_state='31'; END IF;
IF NEW.alt_address_state='30' THEN SET NEW.alt_address_state='30'; END IF;
IF NEW.alt_address_state='3' THEN SET NEW.alt_address_state='03'; END IF;
IF NEW.alt_address_state='29' THEN SET NEW.alt_address_state='29'; END IF;
IF NEW.alt_address_state='28' THEN SET NEW.alt_address_state='28'; END IF;
IF NEW.alt_address_state='27' THEN SET NEW.alt_address_state='27'; END IF;
IF NEW.alt_address_state='26' THEN SET NEW.alt_address_state='26'; END IF;
IF NEW.alt_address_state='25' THEN SET NEW.alt_address_state='25'; END IF;
IF NEW.alt_address_state='24' THEN SET NEW.alt_address_state='24'; END IF;
IF NEW.alt_address_state='23' THEN SET NEW.alt_address_state='23'; END IF;
IF NEW.alt_address_state='22' THEN SET NEW.alt_address_state='22'; END IF;
IF NEW.alt_address_state='21' THEN SET NEW.alt_address_state='21'; END IF;
IF NEW.alt_address_state='20' THEN SET NEW.alt_address_state='20'; END IF;
IF NEW.alt_address_state='2' THEN SET NEW.alt_address_state='02'; END IF;
IF NEW.alt_address_state='19' THEN SET NEW.alt_address_state='19'; END IF;
IF NEW.alt_address_state='18' THEN SET NEW.alt_address_state='18'; END IF;
IF NEW.alt_address_state='17' THEN SET NEW.alt_address_state='17'; END IF;
IF NEW.alt_address_state='16' THEN SET NEW.alt_address_state='16'; END IF;
IF NEW.alt_address_state='15' THEN SET NEW.alt_address_state='15'; END IF;
IF NEW.alt_address_state='14' THEN SET NEW.alt_address_state='14'; END IF;
IF NEW.alt_address_state='13' THEN SET NEW.alt_address_state='13'; END IF;
IF NEW.alt_address_state='12' THEN SET NEW.alt_address_state='12'; END IF;
IF NEW.alt_address_state='11' THEN SET NEW.alt_address_state='11'; END IF;
IF NEW.alt_address_state='10' THEN SET NEW.alt_address_state='10'; END IF;
IF NEW.alt_address_state='1' THEN SET NEW.alt_address_state='01'; END IF;
END

$$
DELIMITER ;

DELIMITER $$
$$

CREATE TRIGGER `leads_before_insert` BEFORE INSERT ON `leads` FOR EACH ROW BEGIN
IF NEW.primary_address_state='99' THEN SET NEW.primary_address_state='99'; END IF;
IF NEW.primary_address_state='9' THEN SET NEW.primary_address_state='09'; END IF;
IF NEW.primary_address_state='8' THEN SET NEW.primary_address_state='08'; END IF;
IF NEW.primary_address_state='7' THEN SET NEW.primary_address_state='07'; END IF;
IF NEW.primary_address_state='6' THEN SET NEW.primary_address_state='06'; END IF;
IF NEW.primary_address_state='52' THEN SET NEW.primary_address_state='52'; END IF;
IF NEW.primary_address_state='51' THEN SET NEW.primary_address_state='51'; END IF;
IF NEW.primary_address_state='50' THEN SET NEW.primary_address_state='50'; END IF;
IF NEW.primary_address_state='5' THEN SET NEW.primary_address_state='05'; END IF;
IF NEW.primary_address_state='49' THEN SET NEW.primary_address_state='49'; END IF;
IF NEW.primary_address_state='48' THEN SET NEW.primary_address_state='48'; END IF;
IF NEW.primary_address_state='47' THEN SET NEW.primary_address_state='47'; END IF;
IF NEW.primary_address_state='46' THEN SET NEW.primary_address_state='46'; END IF;
IF NEW.primary_address_state='45' THEN SET NEW.primary_address_state='45'; END IF;
IF NEW.primary_address_state='44' THEN SET NEW.primary_address_state='44'; END IF;
IF NEW.primary_address_state='43' THEN SET NEW.primary_address_state='43'; END IF;
IF NEW.primary_address_state='42' THEN SET NEW.primary_address_state='42'; END IF;
IF NEW.primary_address_state='41' THEN SET NEW.primary_address_state='41'; END IF;
IF NEW.primary_address_state='40' THEN SET NEW.primary_address_state='40'; END IF;
IF NEW.primary_address_state='4' THEN SET NEW.primary_address_state='04'; END IF;
IF NEW.primary_address_state='39' THEN SET NEW.primary_address_state='39'; END IF;
IF NEW.primary_address_state='38' THEN SET NEW.primary_address_state='38'; END IF;
IF NEW.primary_address_state='37' THEN SET NEW.primary_address_state='37'; END IF;
IF NEW.primary_address_state='36' THEN SET NEW.primary_address_state='36'; END IF;
IF NEW.primary_address_state='35' THEN SET NEW.primary_address_state='35'; END IF;
IF NEW.primary_address_state='34' THEN SET NEW.primary_address_state='34'; END IF;
IF NEW.primary_address_state='33' THEN SET NEW.primary_address_state='33'; END IF;
IF NEW.primary_address_state='32' THEN SET NEW.primary_address_state='32'; END IF;
IF NEW.primary_address_state='31' THEN SET NEW.primary_address_state='31'; END IF;
IF NEW.primary_address_state='30' THEN SET NEW.primary_address_state='30'; END IF;
IF NEW.primary_address_state='3' THEN SET NEW.primary_address_state='03'; END IF;
IF NEW.primary_address_state='29' THEN SET NEW.primary_address_state='29'; END IF;
IF NEW.primary_address_state='28' THEN SET NEW.primary_address_state='28'; END IF;
IF NEW.primary_address_state='27' THEN SET NEW.primary_address_state='27'; END IF;
IF NEW.primary_address_state='26' THEN SET NEW.primary_address_state='26'; END IF;
IF NEW.primary_address_state='25' THEN SET NEW.primary_address_state='25'; END IF;
IF NEW.primary_address_state='24' THEN SET NEW.primary_address_state='24'; END IF;
IF NEW.primary_address_state='23' THEN SET NEW.primary_address_state='23'; END IF;
IF NEW.primary_address_state='22' THEN SET NEW.primary_address_state='22'; END IF;
IF NEW.primary_address_state='21' THEN SET NEW.primary_address_state='21'; END IF;
IF NEW.primary_address_state='20' THEN SET NEW.primary_address_state='20'; END IF;
IF NEW.primary_address_state='2' THEN SET NEW.primary_address_state='02'; END IF;
IF NEW.primary_address_state='19' THEN SET NEW.primary_address_state='19'; END IF;
IF NEW.primary_address_state='18' THEN SET NEW.primary_address_state='18'; END IF;
IF NEW.primary_address_state='17' THEN SET NEW.primary_address_state='17'; END IF;
IF NEW.primary_address_state='16' THEN SET NEW.primary_address_state='16'; END IF;
IF NEW.primary_address_state='15' THEN SET NEW.primary_address_state='15'; END IF;
IF NEW.primary_address_state='14' THEN SET NEW.primary_address_state='14'; END IF;
IF NEW.primary_address_state='13' THEN SET NEW.primary_address_state='13'; END IF;
IF NEW.primary_address_state='12' THEN SET NEW.primary_address_state='12'; END IF;
IF NEW.primary_address_state='11' THEN SET NEW.primary_address_state='11'; END IF;
IF NEW.primary_address_state='10' THEN SET NEW.primary_address_state='10'; END IF;
IF NEW.primary_address_state='1' THEN SET NEW.primary_address_state='01'; END IF;
IF NEW.alt_address_state='99' THEN SET NEW.alt_address_state='99'; END IF;
IF NEW.alt_address_state='9' THEN SET NEW.alt_address_state='09'; END IF;
IF NEW.alt_address_state='8' THEN SET NEW.alt_address_state='08'; END IF;
IF NEW.alt_address_state='7' THEN SET NEW.alt_address_state='07'; END IF;
IF NEW.alt_address_state='6' THEN SET NEW.alt_address_state='06'; END IF;
IF NEW.alt_address_state='52' THEN SET NEW.alt_address_state='52'; END IF;
IF NEW.alt_address_state='51' THEN SET NEW.alt_address_state='51'; END IF;
IF NEW.alt_address_state='50' THEN SET NEW.alt_address_state='50'; END IF;
IF NEW.alt_address_state='5' THEN SET NEW.alt_address_state='05'; END IF;
IF NEW.alt_address_state='49' THEN SET NEW.alt_address_state='49'; END IF;
IF NEW.alt_address_state='48' THEN SET NEW.alt_address_state='48'; END IF;
IF NEW.alt_address_state='47' THEN SET NEW.alt_address_state='47'; END IF;
IF NEW.alt_address_state='46' THEN SET NEW.alt_address_state='46'; END IF;
IF NEW.alt_address_state='45' THEN SET NEW.alt_address_state='45'; END IF;
IF NEW.alt_address_state='44' THEN SET NEW.alt_address_state='44'; END IF;
IF NEW.alt_address_state='43' THEN SET NEW.alt_address_state='43'; END IF;
IF NEW.alt_address_state='42' THEN SET NEW.alt_address_state='42'; END IF;
IF NEW.alt_address_state='41' THEN SET NEW.alt_address_state='41'; END IF;
IF NEW.alt_address_state='40' THEN SET NEW.alt_address_state='40'; END IF;
IF NEW.alt_address_state='4' THEN SET NEW.alt_address_state='04'; END IF;
IF NEW.alt_address_state='39' THEN SET NEW.alt_address_state='39'; END IF;
IF NEW.alt_address_state='38' THEN SET NEW.alt_address_state='38'; END IF;
IF NEW.alt_address_state='37' THEN SET NEW.alt_address_state='37'; END IF;
IF NEW.alt_address_state='36' THEN SET NEW.alt_address_state='36'; END IF;
IF NEW.alt_address_state='35' THEN SET NEW.alt_address_state='35'; END IF;
IF NEW.alt_address_state='34' THEN SET NEW.alt_address_state='34'; END IF;
IF NEW.alt_address_state='33' THEN SET NEW.alt_address_state='33'; END IF;
IF NEW.alt_address_state='32' THEN SET NEW.alt_address_state='32'; END IF;
IF NEW.alt_address_state='31' THEN SET NEW.alt_address_state='31'; END IF;
IF NEW.alt_address_state='30' THEN SET NEW.alt_address_state='30'; END IF;
IF NEW.alt_address_state='3' THEN SET NEW.alt_address_state='03'; END IF;
IF NEW.alt_address_state='29' THEN SET NEW.alt_address_state='29'; END IF;
IF NEW.alt_address_state='28' THEN SET NEW.alt_address_state='28'; END IF;
IF NEW.alt_address_state='27' THEN SET NEW.alt_address_state='27'; END IF;
IF NEW.alt_address_state='26' THEN SET NEW.alt_address_state='26'; END IF;
IF NEW.alt_address_state='25' THEN SET NEW.alt_address_state='25'; END IF;
IF NEW.alt_address_state='24' THEN SET NEW.alt_address_state='24'; END IF;
IF NEW.alt_address_state='23' THEN SET NEW.alt_address_state='23'; END IF;
IF NEW.alt_address_state='22' THEN SET NEW.alt_address_state='22'; END IF;
IF NEW.alt_address_state='21' THEN SET NEW.alt_address_state='21'; END IF;
IF NEW.alt_address_state='20' THEN SET NEW.alt_address_state='20'; END IF;
IF NEW.alt_address_state='2' THEN SET NEW.alt_address_state='02'; END IF;
IF NEW.alt_address_state='19' THEN SET NEW.alt_address_state='19'; END IF;
IF NEW.alt_address_state='18' THEN SET NEW.alt_address_state='18'; END IF;
IF NEW.alt_address_state='17' THEN SET NEW.alt_address_state='17'; END IF;
IF NEW.alt_address_state='16' THEN SET NEW.alt_address_state='16'; END IF;
IF NEW.alt_address_state='15' THEN SET NEW.alt_address_state='15'; END IF;
IF NEW.alt_address_state='14' THEN SET NEW.alt_address_state='14'; END IF;
IF NEW.alt_address_state='13' THEN SET NEW.alt_address_state='13'; END IF;
IF NEW.alt_address_state='12' THEN SET NEW.alt_address_state='12'; END IF;
IF NEW.alt_address_state='11' THEN SET NEW.alt_address_state='11'; END IF;
IF NEW.alt_address_state='10' THEN SET NEW.alt_address_state='10'; END IF;
IF NEW.alt_address_state='1' THEN SET NEW.alt_address_state='01'; END IF;
END

$$
DELIMITER ;

DELIMITER $$
$$

CREATE TRIGGER `stic_payment_commitments_before_insert` BEFORE INSERT ON `stic_payment_commitments` FOR EACH ROW BEGIN
IF NEW.segmentation='veinteycinco_a_treinta' THEN SET NEW.segmentation='sample_value_2'; END IF;
IF NEW.segmentation='uno_a_cinco' THEN SET NEW.segmentation='sample_value_1'; END IF;
IF NEW.periodicity='trimestral' THEN SET NEW.periodicity='quarterly'; END IF;
IF NEW.periodicity='semestral' THEN SET NEW.periodicity='half_yearly'; END IF;
IF NEW.periodicity='puntual' THEN SET NEW.periodicity='punctual'; END IF;
IF NEW.periodicity='mensual' THEN SET NEW.periodicity='monthly'; END IF;
IF NEW.periodicity='cuatrimestral' THEN SET NEW.periodicity='four_monthly'; END IF;
IF NEW.periodicity='bimestral' THEN SET NEW.periodicity='bimonthly'; END IF;
IF NEW.periodicity='anual' THEN SET NEW.periodicity='annual'; END IF;
IF NEW.payment_type='especie' THEN SET NEW.payment_type='kind'; END IF;
IF NEW.payment_type='donativo' THEN SET NEW.payment_type='donation'; END IF;
IF NEW.payment_type='cuota' THEN SET NEW.payment_type='fee'; END IF;
IF NEW.payment_method='transferencia' THEN SET NEW.payment_method='transfer_received'; END IF;
IF NEW.payment_method='transferencia_emitida' THEN SET NEW.payment_method='transfer_issued'; END IF;
IF NEW.payment_method='tarjeta' THEN SET NEW.payment_method='card'; END IF;
IF NEW.payment_method='talon' THEN SET NEW.payment_method='check'; END IF;
IF NEW.payment_method='paypal' THEN SET NEW.payment_method='paypal'; END IF;
IF NEW.payment_method='especie' THEN SET NEW.payment_method='kind'; END IF;
IF NEW.payment_method='efectivo' THEN SET NEW.payment_method='cash'; END IF;
IF NEW.payment_method='domiciliacion' THEN SET NEW.payment_method='direct_debit'; END IF;
IF NEW.destination='usuario' THEN SET NEW.destination='individual'; END IF;
IF NEW.destination='proyecto' THEN SET NEW.destination='project'; END IF;
IF NEW.destination='otro' THEN SET NEW.destination='other'; END IF;
IF NEW.destination='organizacion' THEN SET NEW.destination='organization'; END IF;
IF NEW.destination='entidad' THEN SET NEW.destination='general'; END IF;
IF NEW.channel='web' THEN SET NEW.channel='web'; END IF;
IF NEW.channel='telemarketing' THEN SET NEW.channel='telemarketing'; END IF;
IF NEW.channel='postal' THEN SET NEW.channel='postal_mail'; END IF;
IF NEW.channel='otros' THEN SET NEW.channel='other'; END IF;
IF NEW.channel='movil' THEN SET NEW.channel='mobile'; END IF;
IF NEW.channel='mail' THEN SET NEW.channel='email'; END IF;
IF NEW.channel='f2f' THEN SET NEW.channel='face_to_face'; END IF;
IF NEW.channel='evento' THEN SET NEW.channel='event'; END IF;
END
$$
DELIMITER ;

DELIMITER $$
$$
CREATE TRIGGER `contacts_cstm_before_insert` BEFORE INSERT ON `contacts_cstm` FOR EACH ROW BEGIN
IF NEW.stic_relationship_type_c='voluntarios' THEN SET NEW.stic_relationship_type_c='volunteer'; END IF;
IF NEW.stic_relationship_type_c='usuario' THEN SET NEW.stic_relationship_type_c='beneficiary'; END IF;
IF NEW.stic_relationship_type_c='trabajador' THEN SET NEW.stic_relationship_type_c='employee'; END IF;
IF NEW.stic_relationship_type_c='socio' THEN SET NEW.stic_relationship_type_c='member'; END IF;
IF NEW.stic_relationship_type_c='patronatoJunta' THEN SET NEW.stic_relationship_type_c='board_directors'; END IF;
IF NEW.stic_relationship_type_c='donante' THEN SET NEW.stic_relationship_type_c='donor'; END IF;
IF NEW.stic_referral_agent_c='serviciosSociales' THEN SET NEW.stic_referral_agent_c='social_services'; END IF;
IF NEW.stic_referral_agent_c='serviciosSanitarios' THEN SET NEW.stic_referral_agent_c='health_services'; END IF;
IF NEW.stic_referral_agent_c='propiaIniciativa' THEN SET NEW.stic_referral_agent_c='own_initiative'; END IF;
IF NEW.stic_referral_agent_c='familia' THEN SET NEW.stic_referral_agent_c='family'; END IF;
IF NEW.stic_primary_address_type_c='trabajo' THEN SET NEW.stic_primary_address_type_c='workplace'; END IF;
IF NEW.stic_primary_address_type_c='trabajo' THEN SET NEW.stic_primary_address_type_c='workplace'; END IF;
IF NEW.stic_primary_address_type_c='residencia' THEN SET NEW.stic_primary_address_type_c='residence'; END IF;
IF NEW.stic_primary_address_type_c='residencia' THEN SET NEW.stic_primary_address_type_c='residence'; END IF;
IF NEW.stic_primary_address_type_c='particular' THEN SET NEW.stic_primary_address_type_c='home'; END IF;
IF NEW.stic_primary_address_type_c='particular' THEN SET NEW.stic_primary_address_type_c='home'; END IF;
IF NEW.stic_primary_address_type_c='otros' THEN SET NEW.stic_primary_address_type_c='other'; END IF;
IF NEW.stic_primary_address_type_c='otros' THEN SET NEW.stic_primary_address_type_c='other'; END IF;
IF NEW.stic_primary_address_region_c='regionMurcia' THEN SET NEW.stic_primary_address_region_c='murcia'; END IF;
IF NEW.stic_primary_address_region_c='principadoAsturias' THEN SET NEW.stic_primary_address_region_c='asturias'; END IF;
IF NEW.stic_primary_address_region_c='paisVasco' THEN SET NEW.stic_primary_address_region_c='pais_vasco'; END IF;
IF NEW.stic_primary_address_region_c='navarra' THEN SET NEW.stic_primary_address_region_c='navarra'; END IF;
IF NEW.stic_primary_address_region_c='melilla' THEN SET NEW.stic_primary_address_region_c='melilla'; END IF;
IF NEW.stic_primary_address_region_c='laRioja' THEN SET NEW.stic_primary_address_region_c='rioja'; END IF;
IF NEW.stic_primary_address_region_c='islasCanarias' THEN SET NEW.stic_primary_address_region_c='canarias'; END IF;
IF NEW.stic_primary_address_region_c='islasBaleares' THEN SET NEW.stic_primary_address_region_c='baleares'; END IF;
IF NEW.stic_primary_address_region_c='galicia' THEN SET NEW.stic_primary_address_region_c='galicia'; END IF;
IF NEW.stic_primary_address_region_c='extremadra' THEN SET NEW.stic_primary_address_region_c='extremadra'; END IF;
IF NEW.stic_primary_address_region_c='comunidadValenciana' THEN SET NEW.stic_primary_address_region_c='valencia'; END IF;
IF NEW.stic_primary_address_region_c='comunidadMadrid' THEN SET NEW.stic_primary_address_region_c='madrid'; END IF;
IF NEW.stic_primary_address_region_c='ceuta' THEN SET NEW.stic_primary_address_region_c='ceuta'; END IF;
IF NEW.stic_primary_address_region_c='cataluna' THEN SET NEW.stic_primary_address_region_c='catalunya'; END IF;
IF NEW.stic_primary_address_region_c='castillaLeon' THEN SET NEW.stic_primary_address_region_c='castilla_leon'; END IF;
IF NEW.stic_primary_address_region_c='castillaLaMancha' THEN SET NEW.stic_primary_address_region_c='castilla_mancha'; END IF;
IF NEW.stic_primary_address_region_c='cantabria' THEN SET NEW.stic_primary_address_region_c='cantabria'; END IF;
IF NEW.stic_primary_address_region_c='aragon' THEN SET NEW.stic_primary_address_region_c='aragon'; END IF;
IF NEW.stic_primary_address_region_c='andalucia' THEN SET NEW.stic_primary_address_region_c='andalucia'; END IF;
IF NEW.stic_primary_address_county_c='vallesOriental' THEN SET NEW.stic_primary_address_county_c='valles_oriental'; END IF;
IF NEW.stic_primary_address_county_c='vallesOccidental' THEN SET NEW.stic_primary_address_county_c='valles_occidental'; END IF;
IF NEW.stic_primary_address_county_c='valdAran' THEN SET NEW.stic_primary_address_county_c='val_aran'; END IF;
IF NEW.stic_primary_address_county_c='urgell' THEN SET NEW.stic_primary_address_county_c='urgell'; END IF;
IF NEW.stic_primary_address_county_c='terraAlta' THEN SET NEW.stic_primary_address_county_c='terra_alta'; END IF;
IF NEW.stic_primary_address_county_c='tarragones' THEN SET NEW.stic_primary_address_county_c='tarragones'; END IF;
IF NEW.stic_primary_address_county_c='solsones' THEN SET NEW.stic_primary_address_county_c='solsones'; END IF;
IF NEW.stic_primary_address_county_c='selva' THEN SET NEW.stic_primary_address_county_c='selva'; END IF;
IF NEW.stic_primary_address_county_c='segria' THEN SET NEW.stic_primary_address_county_c='segria'; END IF;
IF NEW.stic_primary_address_county_c='segarra' THEN SET NEW.stic_primary_address_county_c='segarra'; END IF;
IF NEW.stic_primary_address_county_c='ripolle' THEN SET NEW.stic_primary_address_county_c='ripolles'; END IF;
IF NEW.stic_primary_address_county_c='riberaEbre' THEN SET NEW.stic_primary_address_county_c='ribera_ebre'; END IF;
IF NEW.stic_primary_address_county_c='priorat' THEN SET NEW.stic_primary_address_county_c='priorat'; END IF;
IF NEW.stic_primary_address_county_c='pladUrgell' THEN SET NEW.stic_primary_address_county_c='pla_urgell'; END IF;
IF NEW.stic_primary_address_county_c='pladEstany' THEN SET NEW.stic_primary_address_county_c='pla_estany'; END IF;
IF NEW.stic_primary_address_county_c='pallarsSobira' THEN SET NEW.stic_primary_address_county_c='pallars_sobira'; END IF;
IF NEW.stic_primary_address_county_c='pallarsJussa' THEN SET NEW.stic_primary_address_county_c='pallars_jussa'; END IF;
IF NEW.stic_primary_address_county_c='osona' THEN SET NEW.stic_primary_address_county_c='osona'; END IF;
IF NEW.stic_primary_address_county_c='noguera' THEN SET NEW.stic_primary_address_county_c='noguera'; END IF;
IF NEW.stic_primary_address_county_c='montsia' THEN SET NEW.stic_primary_address_county_c='montsia'; END IF;
IF NEW.stic_primary_address_county_c='maresme' THEN SET NEW.stic_primary_address_county_c='maresme'; END IF;
IF NEW.stic_primary_address_county_c='girones' THEN SET NEW.stic_primary_address_county_c='girones'; END IF;
IF NEW.stic_primary_address_county_c='garrotxa' THEN SET NEW.stic_primary_address_county_c='garrotxa'; END IF;
IF NEW.stic_primary_address_county_c='garrigues' THEN SET NEW.stic_primary_address_county_c='garrigues'; END IF;
IF NEW.stic_primary_address_county_c='garraf' THEN SET NEW.stic_primary_address_county_c='garraf'; END IF;
IF NEW.stic_primary_address_county_c='concaBarbera' THEN SET NEW.stic_primary_address_county_c='conca_barbera'; END IF;
IF NEW.stic_primary_address_county_c='cerdanya' THEN SET NEW.stic_primary_address_county_c='cerdanya'; END IF;
IF NEW.stic_primary_address_county_c='bergueda' THEN SET NEW.stic_primary_address_county_c='bergueda'; END IF;
IF NEW.stic_primary_address_county_c='barcelones' THEN SET NEW.stic_primary_address_county_c='barcelones'; END IF;
IF NEW.stic_primary_address_county_c='baixPenedes' THEN SET NEW.stic_primary_address_county_c='baix_penedes'; END IF;
IF NEW.stic_primary_address_county_c='baixLlobregat' THEN SET NEW.stic_primary_address_county_c='baix_llobregat'; END IF;
IF NEW.stic_primary_address_county_c='baixEmporda' THEN SET NEW.stic_primary_address_county_c='baix_emporda'; END IF;
IF NEW.stic_primary_address_county_c='baixEbre' THEN SET NEW.stic_primary_address_county_c='baix_ebre'; END IF;
IF NEW.stic_primary_address_county_c='baixCamp' THEN SET NEW.stic_primary_address_county_c='baix_camp'; END IF;
IF NEW.stic_primary_address_county_c='bages' THEN SET NEW.stic_primary_address_county_c='bages'; END IF;
IF NEW.stic_primary_address_county_c='anoia' THEN SET NEW.stic_primary_address_county_c='anoia'; END IF;
IF NEW.stic_primary_address_county_c='altUrgell' THEN SET NEW.stic_primary_address_county_c='alt_urgell'; END IF;
IF NEW.stic_primary_address_county_c='altPenedes' THEN SET NEW.stic_primary_address_county_c='alt_penedes'; END IF;
IF NEW.stic_primary_address_county_c='altEmporda' THEN SET NEW.stic_primary_address_county_c='alt_emporda'; END IF;
IF NEW.stic_primary_address_county_c='altCamp' THEN SET NEW.stic_primary_address_county_c='alt_camp'; END IF;
IF NEW.stic_primary_address_county_c='altaRibagorca' THEN SET NEW.stic_primary_address_county_c='alta_ribagorca'; END IF;
IF NEW.stic_preferred_contact_channel_c='telefonoMovil' THEN SET NEW.stic_preferred_contact_channel_c='mobile_phone'; END IF;
IF NEW.stic_preferred_contact_channel_c='telefonoFijo' THEN SET NEW.stic_preferred_contact_channel_c='landline_phone'; END IF;
IF NEW.stic_preferred_contact_channel_c='correoPostal' THEN SET NEW.stic_preferred_contact_channel_c='postal_mail'; END IF;
IF NEW.stic_preferred_contact_channel_c='correoElectronico' THEN SET NEW.stic_preferred_contact_channel_c='email'; END IF;
IF NEW.stic_postal_mail_return_reason_c='rechazado' THEN SET NEW.stic_postal_mail_return_reason_c='rejected'; END IF;
IF NEW.stic_postal_mail_return_reason_c='fallecido' THEN SET NEW.stic_postal_mail_return_reason_c='deceased'; END IF;
IF NEW.stic_postal_mail_return_reason_c='direccionIncorrecta' THEN SET NEW.stic_postal_mail_return_reason_c='wrong_address'; END IF;
IF NEW.stic_postal_mail_return_reason_c='desconocido' THEN SET NEW.stic_postal_mail_return_reason_c='unknown'; END IF;
IF NEW.stic_postal_mail_return_reason_c='ausente' THEN SET NEW.stic_postal_mail_return_reason_c='missing'; END IF;
IF NEW.stic_language_c='catala' THEN SET NEW.stic_language_c='catalan'; END IF;
IF NEW.stic_language_c='castella' THEN SET NEW.stic_language_c='spanish'; END IF;
IF NEW.stic_identification_type_c='pasaporte' THEN SET NEW.stic_identification_type_c='passport'; END IF;
IF NEW.stic_identification_type_c='nif' THEN SET NEW.stic_identification_type_c='nif'; END IF;
IF NEW.stic_identification_type_c='nie' THEN SET NEW.stic_identification_type_c='nie'; END IF;
IF NEW.stic_gender_c='mujer' THEN SET NEW.stic_gender_c='female'; END IF;
IF NEW.stic_gender_c='hombre' THEN SET NEW.stic_gender_c='male'; END IF;
IF NEW.stic_employment_status_c='porCuentaAjena' THEN SET NEW.stic_employment_status_c='employee'; END IF;
IF NEW.stic_employment_status_c='parado' THEN SET NEW.stic_employment_status_c='unemployed'; END IF;
IF NEW.stic_employment_status_c='jubilado' THEN SET NEW.stic_employment_status_c='retired'; END IF;
IF NEW.stic_employment_status_c='estudiante' THEN SET NEW.stic_employment_status_c='student'; END IF;
IF NEW.stic_employment_status_c='autonoma' THEN SET NEW.stic_employment_status_c='freelance'; END IF;
IF NEW.stic_alt_address_region_c='regionMurcia' THEN SET NEW.stic_alt_address_region_c='murcia'; END IF;
IF NEW.stic_alt_address_region_c='principadoAsturias' THEN SET NEW.stic_alt_address_region_c='asturias'; END IF;
IF NEW.stic_alt_address_region_c='paisVasco' THEN SET NEW.stic_alt_address_region_c='pais_vasco'; END IF;
IF NEW.stic_alt_address_region_c='navarra' THEN SET NEW.stic_alt_address_region_c='navarra'; END IF;
IF NEW.stic_alt_address_region_c='melilla' THEN SET NEW.stic_alt_address_region_c='melilla'; END IF;
IF NEW.stic_alt_address_region_c='laRioja' THEN SET NEW.stic_alt_address_region_c='rioja'; END IF;
IF NEW.stic_alt_address_region_c='islasCanarias' THEN SET NEW.stic_alt_address_region_c='canarias'; END IF;
IF NEW.stic_alt_address_region_c='islasBaleares' THEN SET NEW.stic_alt_address_region_c='baleares'; END IF;
IF NEW.stic_alt_address_region_c='galicia' THEN SET NEW.stic_alt_address_region_c='galicia'; END IF;
IF NEW.stic_alt_address_region_c='extremadra' THEN SET NEW.stic_alt_address_region_c='extremadra'; END IF;
IF NEW.stic_alt_address_region_c='comunidadValenciana' THEN SET NEW.stic_alt_address_region_c='valencia'; END IF;
IF NEW.stic_alt_address_region_c='comunidadMadrid' THEN SET NEW.stic_alt_address_region_c='madrid'; END IF;
IF NEW.stic_alt_address_region_c='ceuta' THEN SET NEW.stic_alt_address_region_c='ceuta'; END IF;
IF NEW.stic_alt_address_region_c='cataluna' THEN SET NEW.stic_alt_address_region_c='catalunya'; END IF;
IF NEW.stic_alt_address_region_c='castillaLeon' THEN SET NEW.stic_alt_address_region_c='castilla_leon'; END IF;
IF NEW.stic_alt_address_region_c='castillaLaMancha' THEN SET NEW.stic_alt_address_region_c='castilla_mancha'; END IF;
IF NEW.stic_alt_address_region_c='cantabria' THEN SET NEW.stic_alt_address_region_c='cantabria'; END IF;
IF NEW.stic_alt_address_region_c='aragon' THEN SET NEW.stic_alt_address_region_c='aragon'; END IF;
IF NEW.stic_alt_address_region_c='andalucia' THEN SET NEW.stic_alt_address_region_c='andalucia'; END IF;
IF NEW.stic_alt_address_county_c='vallesOriental' THEN SET NEW.stic_alt_address_county_c='valles_oriental'; END IF;
IF NEW.stic_alt_address_county_c='vallesOccidental' THEN SET NEW.stic_alt_address_county_c='valles_occidental'; END IF;
IF NEW.stic_alt_address_county_c='valdAran' THEN SET NEW.stic_alt_address_county_c='val_aran'; END IF;
IF NEW.stic_alt_address_county_c='urgell' THEN SET NEW.stic_alt_address_county_c='urgell'; END IF;
IF NEW.stic_alt_address_county_c='terraAlta' THEN SET NEW.stic_alt_address_county_c='terra_alta'; END IF;
IF NEW.stic_alt_address_county_c='tarragones' THEN SET NEW.stic_alt_address_county_c='tarragones'; END IF;
IF NEW.stic_alt_address_county_c='solsones' THEN SET NEW.stic_alt_address_county_c='solsones'; END IF;
IF NEW.stic_alt_address_county_c='selva' THEN SET NEW.stic_alt_address_county_c='selva'; END IF;
IF NEW.stic_alt_address_county_c='segria' THEN SET NEW.stic_alt_address_county_c='segria'; END IF;
IF NEW.stic_alt_address_county_c='segarra' THEN SET NEW.stic_alt_address_county_c='segarra'; END IF;
IF NEW.stic_alt_address_county_c='ripolle' THEN SET NEW.stic_alt_address_county_c='ripolles'; END IF;
IF NEW.stic_alt_address_county_c='riberaEbre' THEN SET NEW.stic_alt_address_county_c='ribera_ebre'; END IF;
IF NEW.stic_alt_address_county_c='priorat' THEN SET NEW.stic_alt_address_county_c='priorat'; END IF;
IF NEW.stic_alt_address_county_c='pladUrgell' THEN SET NEW.stic_alt_address_county_c='pla_urgell'; END IF;
IF NEW.stic_alt_address_county_c='pladEstany' THEN SET NEW.stic_alt_address_county_c='pla_estany'; END IF;
IF NEW.stic_alt_address_county_c='pallarsSobira' THEN SET NEW.stic_alt_address_county_c='pallars_sobira'; END IF;
IF NEW.stic_alt_address_county_c='pallarsJussa' THEN SET NEW.stic_alt_address_county_c='pallars_jussa'; END IF;
IF NEW.stic_alt_address_county_c='osona' THEN SET NEW.stic_alt_address_county_c='osona'; END IF;
IF NEW.stic_alt_address_county_c='noguera' THEN SET NEW.stic_alt_address_county_c='noguera'; END IF;
IF NEW.stic_alt_address_county_c='montsia' THEN SET NEW.stic_alt_address_county_c='montsia'; END IF;
IF NEW.stic_alt_address_county_c='maresme' THEN SET NEW.stic_alt_address_county_c='maresme'; END IF;
IF NEW.stic_alt_address_county_c='girones' THEN SET NEW.stic_alt_address_county_c='girones'; END IF;
IF NEW.stic_alt_address_county_c='garrotxa' THEN SET NEW.stic_alt_address_county_c='garrotxa'; END IF;
IF NEW.stic_alt_address_county_c='garrigues' THEN SET NEW.stic_alt_address_county_c='garrigues'; END IF;
IF NEW.stic_alt_address_county_c='garraf' THEN SET NEW.stic_alt_address_county_c='garraf'; END IF;
IF NEW.stic_alt_address_county_c='concaBarbera' THEN SET NEW.stic_alt_address_county_c='conca_barbera'; END IF;
IF NEW.stic_alt_address_county_c='cerdanya' THEN SET NEW.stic_alt_address_county_c='cerdanya'; END IF;
IF NEW.stic_alt_address_county_c='bergueda' THEN SET NEW.stic_alt_address_county_c='bergueda'; END IF;
IF NEW.stic_alt_address_county_c='barcelones' THEN SET NEW.stic_alt_address_county_c='barcelones'; END IF;
IF NEW.stic_alt_address_county_c='baixPenedes' THEN SET NEW.stic_alt_address_county_c='baix_penedes'; END IF;
IF NEW.stic_alt_address_county_c='baixLlobregat' THEN SET NEW.stic_alt_address_county_c='baix_llobregat'; END IF;
IF NEW.stic_alt_address_county_c='baixEmporda' THEN SET NEW.stic_alt_address_county_c='baix_emporda'; END IF;
IF NEW.stic_alt_address_county_c='baixEbre' THEN SET NEW.stic_alt_address_county_c='baix_ebre'; END IF;
IF NEW.stic_alt_address_county_c='baixCamp' THEN SET NEW.stic_alt_address_county_c='baix_camp'; END IF;
IF NEW.stic_alt_address_county_c='bages' THEN SET NEW.stic_alt_address_county_c='bages'; END IF;
IF NEW.stic_alt_address_county_c='anoia' THEN SET NEW.stic_alt_address_county_c='anoia'; END IF;
IF NEW.stic_alt_address_county_c='altUrgell' THEN SET NEW.stic_alt_address_county_c='alt_urgell'; END IF;
IF NEW.stic_alt_address_county_c='altPenedes' THEN SET NEW.stic_alt_address_county_c='alt_penedes'; END IF;
IF NEW.stic_alt_address_county_c='altEmporda' THEN SET NEW.stic_alt_address_county_c='alt_emporda'; END IF;
IF NEW.stic_alt_address_county_c='altCamp' THEN SET NEW.stic_alt_address_county_c='alt_camp'; END IF;
IF NEW.stic_alt_address_county_c='altaRibagorca' THEN SET NEW.stic_alt_address_county_c='alta_ribagorca'; END IF;
IF NEW.stic_acquisition_channel_c='web' THEN SET NEW.stic_acquisition_channel_c='web'; END IF;
IF NEW.stic_acquisition_channel_c='telemarketing' THEN SET NEW.stic_acquisition_channel_c='telemarketing'; END IF;
IF NEW.stic_acquisition_channel_c='postal' THEN SET NEW.stic_acquisition_channel_c='postal_mail'; END IF;
IF NEW.stic_acquisition_channel_c='otros' THEN SET NEW.stic_acquisition_channel_c='other'; END IF;
IF NEW.stic_acquisition_channel_c='movil' THEN SET NEW.stic_acquisition_channel_c='mobile'; END IF;
IF NEW.stic_acquisition_channel_c='mail' THEN SET NEW.stic_acquisition_channel_c='email'; END IF;
IF NEW.stic_acquisition_channel_c='f2f' THEN SET NEW.stic_acquisition_channel_c='face_to_face'; END IF;
IF NEW.stic_acquisition_channel_c='evento' THEN SET NEW.stic_acquisition_channel_c='event'; END IF;
END
$$
DELIMITER ;

DELIMITER $$
$$
CREATE TRIGGER `accounts_cstm_before_insert` BEFORE INSERT ON `accounts_cstm` FOR EACH ROW BEGIN
IF NEW.stic_subcategory_c='onl_fundacion' THEN SET NEW.stic_subcategory_c='npo_foundation'; END IF;
IF NEW.stic_subcategory_c='onl_cooperativa' THEN SET NEW.stic_subcategory_c='npo_cooperative'; END IF;
IF NEW.stic_subcategory_c='onl_asociacion' THEN SET NEW.stic_subcategory_c='npo_association'; END IF;
IF NEW.stic_subcategory_c='mediosComunicacion_television' THEN SET NEW.stic_subcategory_c='media_television'; END IF;
IF NEW.stic_subcategory_c='mediosComunicacion_radio' THEN SET NEW.stic_subcategory_c='media_radio'; END IF;
IF NEW.stic_subcategory_c='mediosComunicacion_prensa' THEN SET NEW.stic_subcategory_c='media_press'; END IF;
IF NEW.stic_subcategory_c='mediosComunicacion_digital' THEN SET NEW.stic_subcategory_c='media_digital'; END IF;
IF NEW.stic_subcategory_c='administracionesPublicas_diputacion' THEN SET NEW.stic_subcategory_c='administration_province'; END IF;
IF NEW.stic_subcategory_c='administracionesPublicas_consorcio' THEN SET NEW.stic_subcategory_c='administration_consortium'; END IF;
IF NEW.stic_subcategory_c='administracionesPublicas_consejoComarcal' THEN SET NEW.stic_subcategory_c='administration_county'; END IF;
IF NEW.stic_subcategory_c='administracionesPublicas_comunidadAutonoma' THEN SET NEW.stic_subcategory_c='administration_region'; END IF;
IF NEW.stic_subcategory_c='administracionesPublicas_ayuntamiento' THEN SET NEW.stic_subcategory_c='administration_city'; END IF;
IF NEW.stic_subcategory_c='administracionesPublicas_administracionEstado' THEN SET NEW.stic_subcategory_c='administration_country'; END IF;
IF NEW.stic_relationship_type_c='socio' THEN SET NEW.stic_relationship_type_c='member'; END IF;
IF NEW.stic_relationship_type_c='proveedor' THEN SET NEW.stic_relationship_type_c='supplier'; END IF;
IF NEW.stic_relationship_type_c='financiador' THEN SET NEW.stic_relationship_type_c='funder'; END IF;
IF NEW.stic_relationship_type_c='donante' THEN SET NEW.stic_relationship_type_c='donor'; END IF;
IF NEW.stic_relationship_type_c='cliente' THEN SET NEW.stic_relationship_type_c='customer'; END IF;
IF NEW.stic_billing_address_type_c='sede' THEN SET NEW.stic_billing_address_type_c='headquarters'; END IF;
IF NEW.stic_billing_address_type_c='otra' THEN SET NEW.stic_billing_address_type_c='other'; END IF;
IF NEW.stic_billing_address_type_c='facturacion' THEN SET NEW.stic_billing_address_type_c='billing'; END IF;
IF NEW.stic_billing_address_type_c='delegacion' THEN SET NEW.stic_billing_address_type_c='delegation'; END IF;
IF NEW.stic_category_c='universidad' THEN SET NEW.stic_category_c='university'; END IF;
IF NEW.stic_category_c='onl' THEN SET NEW.stic_category_c='npo'; END IF;
IF NEW.stic_category_c='mediosComunicacion' THEN SET NEW.stic_category_c='media'; END IF;
IF NEW.stic_category_c='escuela' THEN SET NEW.stic_category_c='school'; END IF;
IF NEW.stic_category_c='empresas' THEN SET NEW.stic_category_c='company'; END IF;
IF NEW.stic_category_c='administracionesPublicas' THEN SET NEW.stic_category_c='administration'; END IF;
IF NEW.stic_shipping_address_type_c='sede' THEN SET NEW.stic_shipping_address_type_c='headquarters'; END IF;
IF NEW.stic_shipping_address_type_c='otra' THEN SET NEW.stic_shipping_address_type_c='other'; END IF;
IF NEW.stic_shipping_address_type_c='facturacion' THEN SET NEW.stic_shipping_address_type_c='billing'; END IF;
IF NEW.stic_shipping_address_type_c='delegacion' THEN SET NEW.stic_shipping_address_type_c='delegation'; END IF;
END
$$
DELIMITER ;

DELIMITER $$
$$
CREATE TRIGGER `stic_payments_before_insert` BEFORE INSERT ON `stic_payments` FOR EACH ROW BEGIN
IF NEW.status='remesada' THEN SET NEW.status='remitted'; END IF;
IF NEW.status='recobro' THEN SET NEW.status='recharge'; END IF;
IF NEW.status='rechazada_tpv' THEN SET NEW.status='rejected_gateway'; END IF;
IF NEW.status='pendiente' THEN SET NEW.status='pending'; END IF;
IF NEW.status='pagada' THEN SET NEW.status='paid'; END IF;
IF NEW.status='noRemesada' THEN SET NEW.status='not_remitted'; END IF;
IF NEW.status='impagada' THEN SET NEW.status='unpaid'; END IF;
IF NEW.status='duplicada' THEN SET NEW.status='duplicate'; END IF;
IF NEW.status='anulada' THEN SET NEW.status='cancelled'; END IF;
IF NEW.segmentation='veinteycinco_a_treinta' THEN SET NEW.segmentation='sample_value_2'; END IF;
IF NEW.segmentation='uno_a_cinco' THEN SET NEW.segmentation='sample_value_1'; END IF;
IF NEW.payment_type='especie' THEN SET NEW.payment_type='kind'; END IF;
IF NEW.payment_type='donativo' THEN SET NEW.payment_type='donation'; END IF;
IF NEW.payment_type='cuota' THEN SET NEW.payment_type='fee'; END IF;
IF NEW.payment_method='transferencia' THEN SET NEW.payment_method='transfer_received'; END IF;
IF NEW.payment_method='transferencia_emitida' THEN SET NEW.payment_method='transfer_issued'; END IF;
IF NEW.payment_method='tarjeta' THEN SET NEW.payment_method='card'; END IF;
IF NEW.payment_method='talon' THEN SET NEW.payment_method='check'; END IF;
IF NEW.payment_method='paypal' THEN SET NEW.payment_method='paypal'; END IF;
IF NEW.payment_method='especie' THEN SET NEW.payment_method='kind'; END IF;
IF NEW.payment_method='efectivo' THEN SET NEW.payment_method='cash'; END IF;
IF NEW.payment_method='domiciliacion' THEN SET NEW.payment_method='direct_debit'; END IF;
END
$$
DELIMITER ;

DELIMITER $$
$$
CREATE TRIGGER `stic_remittances_before_insert` BEFORE INSERT ON `stic_remittances` FOR EACH ROW BEGIN
IF NEW.type='transferencias_emitidas' THEN SET NEW.type='transfers'; END IF;
IF NEW.type='domiciliaciones' THEN SET NEW.type='direct_debits'; END IF;
IF NEW.status='generada' THEN SET NEW.status='generated'; END IF;
IF NEW.status='enviada' THEN SET NEW.status='sent'; END IF;
IF NEW.status='abierta' THEN SET NEW.status='open'; END IF;
END
$$
DELIMITER ;

DELIMITER $$
$$
CREATE TRIGGER `stic_registrations_before_insert` BEFORE INSERT ON `stic_registrations` FOR EACH ROW BEGIN
IF NEW.status='rechazado' THEN SET NEW.status='rejected'; END IF;
IF NEW.status='quizas' THEN SET NEW.status='maybe'; END IF;
IF NEW.status='participa' THEN SET NEW.status='participates'; END IF;
IF NEW.status='noParticipa' THEN SET NEW.status='not_participate'; END IF;
IF NEW.status='noInvitado' THEN SET NEW.status='uninvited'; END IF;
IF NEW.status='invitado' THEN SET NEW.status='invited'; END IF;
IF NEW.status='confirmado' THEN SET NEW.status='confirmed'; END IF;
IF NEW.rejection_reason='precio' THEN SET NEW.rejection_reason='price'; END IF;
IF NEW.rejection_reason='noInteresa' THEN SET NEW.rejection_reason='not_interested'; END IF;
IF NEW.rejection_reason='agenda' THEN SET NEW.rejection_reason='agenda'; END IF;
IF NEW.participation_type='vip' THEN SET NEW.participation_type='vip'; END IF;
IF NEW.participation_type='ponente' THEN SET NEW.participation_type='speaker'; END IF;
IF NEW.participation_type='organizador' THEN SET NEW.participation_type='organizer'; END IF;
IF NEW.participation_type='asistente' THEN SET NEW.participation_type='attendant'; END IF;
IF NEW.not_participating_reason='olvido' THEN SET NEW.not_participating_reason='forgotten'; END IF;
IF NEW.not_participating_reason='enfermedad' THEN SET NEW.not_participating_reason='sickness'; END IF;
IF NEW.not_participating_reason='agenda' THEN SET NEW.not_participating_reason='agenda'; END IF;
END
$$
DELIMITER ;

DELIMITER $$
$$
CREATE TRIGGER `stic_events_before_insert` BEFORE INSERT ON `stic_events` FOR EACH ROW BEGIN
IF NEW.type='taller' THEN SET NEW.type='workshop'; END IF;
IF NEW.type='seminario' THEN SET NEW.type='seminar'; END IF;
IF NEW.type='jornada' THEN SET NEW.type='working_day'; END IF;
IF NEW.type='curso' THEN SET NEW.type='course'; END IF;
IF NEW.type='congreso' THEN SET NEW.type='congress'; END IF;
IF NEW.type='conferencia' THEN SET NEW.type='conference'; END IF;
IF NEW.status='realizado' THEN SET NEW.status='done'; END IF;
IF NEW.status='preparacion' THEN SET NEW.status='preparation'; END IF;
IF NEW.status='inscripcion' THEN SET NEW.status='registration'; END IF;
IF NEW.status='idea' THEN SET NEW.status='idea'; END IF;
IF NEW.status='descartado' THEN SET NEW.status='discarded'; END IF;
IF NEW.status='activo' THEN SET NEW.status='active'; END IF;
IF NEW.discard_reason='otros' THEN SET NEW.discard_reason='other'; END IF;
IF NEW.discard_reason='interes' THEN SET NEW.discard_reason='disinterest'; END IF;
IF NEW.discard_reason='economico' THEN SET NEW.discard_reason='economic'; END IF;
IF NEW.discard_reason='calendario' THEN SET NEW.discard_reason='calendar'; END IF;
IF NEW.county='vallesOriental' THEN SET NEW.county='valles_oriental'; END IF;
IF NEW.county='vallesOccidental' THEN SET NEW.county='valles_occidental'; END IF;
IF NEW.county='valdAran' THEN SET NEW.county='val_aran'; END IF;
IF NEW.county='urgell' THEN SET NEW.county='urgell'; END IF;
IF NEW.county='terraAlta' THEN SET NEW.county='terra_alta'; END IF;
IF NEW.county='tarragones' THEN SET NEW.county='tarragones'; END IF;
IF NEW.county='solsones' THEN SET NEW.county='solsones'; END IF;
IF NEW.county='selva' THEN SET NEW.county='selva'; END IF;
IF NEW.county='segria' THEN SET NEW.county='segria'; END IF;
IF NEW.county='segarra' THEN SET NEW.county='segarra'; END IF;
IF NEW.county='ripolle' THEN SET NEW.county='ripolles'; END IF;
IF NEW.county='riberaEbre' THEN SET NEW.county='ribera_ebre'; END IF;
IF NEW.county='priorat' THEN SET NEW.county='priorat'; END IF;
IF NEW.county='pladUrgell' THEN SET NEW.county='pla_urgell'; END IF;
IF NEW.county='pladEstany' THEN SET NEW.county='pla_estany'; END IF;
IF NEW.county='pallarsSobira' THEN SET NEW.county='pallars_sobira'; END IF;
IF NEW.county='pallarsJussa' THEN SET NEW.county='pallars_jussa'; END IF;
IF NEW.county='osona' THEN SET NEW.county='osona'; END IF;
IF NEW.county='noguera' THEN SET NEW.county='noguera'; END IF;
IF NEW.county='montsia' THEN SET NEW.county='montsia'; END IF;
IF NEW.county='maresme' THEN SET NEW.county='maresme'; END IF;
IF NEW.county='girones' THEN SET NEW.county='girones'; END IF;
IF NEW.county='garrotxa' THEN SET NEW.county='garrotxa'; END IF;
IF NEW.county='garrigues' THEN SET NEW.county='garrigues'; END IF;
IF NEW.county='garraf' THEN SET NEW.county='garraf'; END IF;
IF NEW.county='concaBarbera' THEN SET NEW.county='conca_barbera'; END IF;
IF NEW.county='cerdanya' THEN SET NEW.county='cerdanya'; END IF;
IF NEW.county='bergueda' THEN SET NEW.county='bergueda'; END IF;
IF NEW.county='barcelones' THEN SET NEW.county='barcelones'; END IF;
IF NEW.county='baixPenedes' THEN SET NEW.county='baix_penedes'; END IF;
IF NEW.county='baixLlobregat' THEN SET NEW.county='baix_llobregat'; END IF;
IF NEW.county='baixEmporda' THEN SET NEW.county='baix_emporda'; END IF;
IF NEW.county='baixEbre' THEN SET NEW.county='baix_ebre'; END IF;
IF NEW.county='baixCamp' THEN SET NEW.county='baix_camp'; END IF;
IF NEW.county='bages' THEN SET NEW.county='bages'; END IF;
IF NEW.county='anoia' THEN SET NEW.county='anoia'; END IF;
IF NEW.county='altUrgell' THEN SET NEW.county='alt_urgell'; END IF;
IF NEW.county='altPenedes' THEN SET NEW.county='alt_penedes'; END IF;
IF NEW.county='altEmporda' THEN SET NEW.county='alt_emporda'; END IF;
IF NEW.county='altCamp' THEN SET NEW.county='alt_camp'; END IF;
IF NEW.county='altaRibagorca' THEN SET NEW.county='alta_ribagorca'; END IF;
END
$$
DELIMITER ;

DELIMITER $$
$$
CREATE TRIGGER `stic_contacts_relationships_before_insert` BEFORE INSERT ON `stic_contacts_relationships` FOR EACH ROW BEGIN
IF NEW.role='voluntario' THEN SET NEW.role='volunteer'; END IF;
IF NEW.role='usuario' THEN SET NEW.role='user'; END IF;
IF NEW.role='tecnico' THEN SET NEW.role='technician'; END IF;
IF NEW.role='coordinador' THEN SET NEW.role='coordinator'; END IF;
IF NEW.relationship_type='voluntarios' THEN SET NEW.relationship_type='volunteer'; END IF;
IF NEW.relationship_type='usuario' THEN SET NEW.relationship_type='beneficiary'; END IF;
IF NEW.relationship_type='trabajador' THEN SET NEW.relationship_type='employee'; END IF;
IF NEW.relationship_type='socio' THEN SET NEW.relationship_type='member'; END IF;
IF NEW.relationship_type='patronatoJunta' THEN SET NEW.relationship_type='board_directors'; END IF;
IF NEW.relationship_type='donante' THEN SET NEW.relationship_type='donor'; END IF;
IF NEW.end_reason='otrosMotivos' THEN SET NEW.end_reason='other'; END IF;
IF NEW.end_reason='motivosPersonales' THEN SET NEW.end_reason='personal'; END IF;
IF NEW.end_reason='motivosEconomicos' THEN SET NEW.end_reason='economy'; END IF;
IF NEW.end_reason='desacuerdoEntidad' THEN SET NEW.end_reason='disagreement'; END IF;
IF NEW.end_reason='defunciones' THEN SET NEW.end_reason='decease'; END IF;
END
$$
DELIMITER ;

DELIMITER $$
$$
CREATE TRIGGER `project_before_insert` BEFORE INSERT ON `project` FOR EACH ROW BEGIN
IF NEW.status='planificacion' THEN SET NEW.status='planning'; END IF;
IF NEW.status='idea' THEN SET NEW.status='idea'; END IF;
IF NEW.status='descartado' THEN SET NEW.status='cancelled'; END IF;
IF NEW.status='cerrado' THEN SET NEW.status='closed'; END IF;
IF NEW.status='activo' THEN SET NEW.status='active'; END IF;
END
$$
DELIMITER ;

DELIMITER $$
$$
CREATE TRIGGER `opportunities_cstm_before_insert` BEFORE INSERT ON `opportunities_cstm` FOR EACH ROW BEGIN
IF NEW.stic_type_c='subvencion' THEN SET NEW.stic_type_c='grant'; END IF;
IF NEW.stic_type_c='convenioEmpresa' THEN SET NEW.stic_type_c='agreement_company'; END IF;
IF NEW.stic_type_c='convenioAdministracion' THEN SET NEW.stic_type_c='agreement_administration'; END IF;
IF NEW.stic_type_c='concurso' THEN SET NEW.stic_type_c='public_tender'; END IF;
IF NEW.stic_status_c='redaccion' THEN SET NEW.stic_status_c='preparation'; END IF;
IF NEW.stic_status_c='presentada' THEN SET NEW.stic_status_c='submitted'; END IF;
IF NEW.stic_status_c='nueva' THEN SET NEW.stic_status_c='new'; END IF;
IF NEW.stic_status_c='modificacionPropuesta' THEN SET NEW.stic_status_c='requested_changes'; END IF;
IF NEW.stic_status_c='justificada' THEN SET NEW.stic_status_c='justified'; END IF;
IF NEW.stic_status_c='descartada' THEN SET NEW.stic_status_c='cancelled'; END IF;
IF NEW.stic_status_c='denegada' THEN SET NEW.stic_status_c='denied'; END IF;
IF NEW.stic_status_c='concedida' THEN SET NEW.stic_status_c='granted'; END IF;
IF NEW.stic_documentation_to_deliver_c='memorias' THEN SET NEW.stic_documentation_to_deliver_c='annual_report'; END IF;
IF NEW.stic_documentation_to_deliver_c='estatutos' THEN SET NEW.stic_documentation_to_deliver_c='estatutes'; END IF;
IF NEW.stic_documentation_to_deliver_c='documentoDeProyecto' THEN SET NEW.stic_documentation_to_deliver_c='project'; END IF;
IF NEW.stic_documentation_to_deliver_c='certificadoCorriente' THEN SET NEW.stic_documentation_to_deliver_c='tax_obligations'; END IF;
IF NEW.stic_target_c='usuario' THEN SET NEW.stic_target_c='individual'; END IF;
IF NEW.stic_target_c='proyecto' THEN SET NEW.stic_target_c='project'; END IF;
IF NEW.stic_target_c='otro' THEN SET NEW.stic_target_c='other'; END IF;
IF NEW.stic_target_c='organizacion' THEN SET NEW.stic_target_c='organization'; END IF;
IF NEW.stic_target_c='entidad' THEN SET NEW.stic_target_c='general'; END IF;
END
$$
DELIMITER ;

DELIMITER $$
$$
CREATE TRIGGER `leads_cstm_before_insert` BEFORE INSERT ON `leads_cstm` FOR EACH ROW BEGIN
IF NEW.stic_referral_agent_c='serviciosSociales' THEN SET NEW.stic_referral_agent_c='social_services'; END IF;
IF NEW.stic_referral_agent_c='serviciosSanitarios' THEN SET NEW.stic_referral_agent_c='health_services'; END IF;
IF NEW.stic_referral_agent_c='propiaIniciativa' THEN SET NEW.stic_referral_agent_c='own_initiative'; END IF;
IF NEW.stic_referral_agent_c='familia' THEN SET NEW.stic_referral_agent_c='family'; END IF;
IF NEW.stic_primary_address_region_c='regionMurcia' THEN SET NEW.stic_primary_address_region_c='murcia'; END IF;
IF NEW.stic_primary_address_region_c='principadoAsturias' THEN SET NEW.stic_primary_address_region_c='asturias'; END IF;
IF NEW.stic_primary_address_region_c='paisVasco' THEN SET NEW.stic_primary_address_region_c='pais_vasco'; END IF;
IF NEW.stic_primary_address_region_c='navarra' THEN SET NEW.stic_primary_address_region_c='navarra'; END IF;
IF NEW.stic_primary_address_region_c='melilla' THEN SET NEW.stic_primary_address_region_c='melilla'; END IF;
IF NEW.stic_primary_address_region_c='laRioja' THEN SET NEW.stic_primary_address_region_c='rioja'; END IF;
IF NEW.stic_primary_address_region_c='islasCanarias' THEN SET NEW.stic_primary_address_region_c='canarias'; END IF;
IF NEW.stic_primary_address_region_c='islasBaleares' THEN SET NEW.stic_primary_address_region_c='baleares'; END IF;
IF NEW.stic_primary_address_region_c='galicia' THEN SET NEW.stic_primary_address_region_c='galicia'; END IF;
IF NEW.stic_primary_address_region_c='extremadra' THEN SET NEW.stic_primary_address_region_c='extremadra'; END IF;
IF NEW.stic_primary_address_region_c='comunidadValenciana' THEN SET NEW.stic_primary_address_region_c='valencia'; END IF;
IF NEW.stic_primary_address_region_c='comunidadMadrid' THEN SET NEW.stic_primary_address_region_c='madrid'; END IF;
IF NEW.stic_primary_address_region_c='ceuta' THEN SET NEW.stic_primary_address_region_c='ceuta'; END IF;
IF NEW.stic_primary_address_region_c='cataluna' THEN SET NEW.stic_primary_address_region_c='catalunya'; END IF;
IF NEW.stic_primary_address_region_c='castillaLeon' THEN SET NEW.stic_primary_address_region_c='castilla_leon'; END IF;
IF NEW.stic_primary_address_region_c='castillaLaMancha' THEN SET NEW.stic_primary_address_region_c='castilla_mancha'; END IF;
IF NEW.stic_primary_address_region_c='cantabria' THEN SET NEW.stic_primary_address_region_c='cantabria'; END IF;
IF NEW.stic_primary_address_region_c='aragon' THEN SET NEW.stic_primary_address_region_c='aragon'; END IF;
IF NEW.stic_primary_address_region_c='andalucia' THEN SET NEW.stic_primary_address_region_c='andalucia'; END IF;
IF NEW.stic_primary_address_county_c='vallesOriental' THEN SET NEW.stic_primary_address_county_c='valles_oriental'; END IF;
IF NEW.stic_primary_address_county_c='vallesOccidental' THEN SET NEW.stic_primary_address_county_c='valles_occidental'; END IF;
IF NEW.stic_primary_address_county_c='valdAran' THEN SET NEW.stic_primary_address_county_c='val_aran'; END IF;
IF NEW.stic_primary_address_county_c='urgell' THEN SET NEW.stic_primary_address_county_c='urgell'; END IF;
IF NEW.stic_primary_address_county_c='terraAlta' THEN SET NEW.stic_primary_address_county_c='terra_alta'; END IF;
IF NEW.stic_primary_address_county_c='tarragones' THEN SET NEW.stic_primary_address_county_c='tarragones'; END IF;
IF NEW.stic_primary_address_county_c='solsones' THEN SET NEW.stic_primary_address_county_c='solsones'; END IF;
IF NEW.stic_primary_address_county_c='selva' THEN SET NEW.stic_primary_address_county_c='selva'; END IF;
IF NEW.stic_primary_address_county_c='segria' THEN SET NEW.stic_primary_address_county_c='segria'; END IF;
IF NEW.stic_primary_address_county_c='segarra' THEN SET NEW.stic_primary_address_county_c='segarra'; END IF;
IF NEW.stic_primary_address_county_c='ripolle' THEN SET NEW.stic_primary_address_county_c='ripolles'; END IF;
IF NEW.stic_primary_address_county_c='riberaEbre' THEN SET NEW.stic_primary_address_county_c='ribera_ebre'; END IF;
IF NEW.stic_primary_address_county_c='priorat' THEN SET NEW.stic_primary_address_county_c='priorat'; END IF;
IF NEW.stic_primary_address_county_c='pladUrgell' THEN SET NEW.stic_primary_address_county_c='pla_urgell'; END IF;
IF NEW.stic_primary_address_county_c='pladEstany' THEN SET NEW.stic_primary_address_county_c='pla_estany'; END IF;
IF NEW.stic_primary_address_county_c='pallarsSobira' THEN SET NEW.stic_primary_address_county_c='pallars_sobira'; END IF;
IF NEW.stic_primary_address_county_c='pallarsJussa' THEN SET NEW.stic_primary_address_county_c='pallars_jussa'; END IF;
IF NEW.stic_primary_address_county_c='osona' THEN SET NEW.stic_primary_address_county_c='osona'; END IF;
IF NEW.stic_primary_address_county_c='noguera' THEN SET NEW.stic_primary_address_county_c='noguera'; END IF;
IF NEW.stic_primary_address_county_c='montsia' THEN SET NEW.stic_primary_address_county_c='montsia'; END IF;
IF NEW.stic_primary_address_county_c='maresme' THEN SET NEW.stic_primary_address_county_c='maresme'; END IF;
IF NEW.stic_primary_address_county_c='girones' THEN SET NEW.stic_primary_address_county_c='girones'; END IF;
IF NEW.stic_primary_address_county_c='garrotxa' THEN SET NEW.stic_primary_address_county_c='garrotxa'; END IF;
IF NEW.stic_primary_address_county_c='garrigues' THEN SET NEW.stic_primary_address_county_c='garrigues'; END IF;
IF NEW.stic_primary_address_county_c='garraf' THEN SET NEW.stic_primary_address_county_c='garraf'; END IF;
IF NEW.stic_primary_address_county_c='concaBarbera' THEN SET NEW.stic_primary_address_county_c='conca_barbera'; END IF;
IF NEW.stic_primary_address_county_c='cerdanya' THEN SET NEW.stic_primary_address_county_c='cerdanya'; END IF;
IF NEW.stic_primary_address_county_c='bergueda' THEN SET NEW.stic_primary_address_county_c='bergueda'; END IF;
IF NEW.stic_primary_address_county_c='barcelones' THEN SET NEW.stic_primary_address_county_c='barcelones'; END IF;
IF NEW.stic_primary_address_county_c='baixPenedes' THEN SET NEW.stic_primary_address_county_c='baix_penedes'; END IF;
IF NEW.stic_primary_address_county_c='baixLlobregat' THEN SET NEW.stic_primary_address_county_c='baix_llobregat'; END IF;
IF NEW.stic_primary_address_county_c='baixEmporda' THEN SET NEW.stic_primary_address_county_c='baix_emporda'; END IF;
IF NEW.stic_primary_address_county_c='baixEbre' THEN SET NEW.stic_primary_address_county_c='baix_ebre'; END IF;
IF NEW.stic_primary_address_county_c='baixCamp' THEN SET NEW.stic_primary_address_county_c='baix_camp'; END IF;
IF NEW.stic_primary_address_county_c='bages' THEN SET NEW.stic_primary_address_county_c='bages'; END IF;
IF NEW.stic_primary_address_county_c='anoia' THEN SET NEW.stic_primary_address_county_c='anoia'; END IF;
IF NEW.stic_primary_address_county_c='altUrgell' THEN SET NEW.stic_primary_address_county_c='alt_urgell'; END IF;
IF NEW.stic_primary_address_county_c='altPenedes' THEN SET NEW.stic_primary_address_county_c='alt_penedes'; END IF;
IF NEW.stic_primary_address_county_c='altEmporda' THEN SET NEW.stic_primary_address_county_c='alt_emporda'; END IF;
IF NEW.stic_primary_address_county_c='altCamp' THEN SET NEW.stic_primary_address_county_c='alt_camp'; END IF;
IF NEW.stic_primary_address_county_c='altaRibagorca' THEN SET NEW.stic_primary_address_county_c='alta_ribagorca'; END IF;
IF NEW.stic_postal_mail_return_reason_c='rechazado' THEN SET NEW.stic_postal_mail_return_reason_c='rejected'; END IF;
IF NEW.stic_postal_mail_return_reason_c='fallecido' THEN SET NEW.stic_postal_mail_return_reason_c='deceased'; END IF;
IF NEW.stic_postal_mail_return_reason_c='direccionIncorrecta' THEN SET NEW.stic_postal_mail_return_reason_c='wrong_address'; END IF;
IF NEW.stic_postal_mail_return_reason_c='desconocido' THEN SET NEW.stic_postal_mail_return_reason_c='unknown'; END IF;
IF NEW.stic_postal_mail_return_reason_c='ausente' THEN SET NEW.stic_postal_mail_return_reason_c='missing'; END IF;
IF NEW.stic_language_c='catala' THEN SET NEW.stic_language_c='catalan'; END IF;
IF NEW.stic_language_c='castella' THEN SET NEW.stic_language_c='spanish'; END IF;
IF NEW.stic_identification_type_c='pasaporte' THEN SET NEW.stic_identification_type_c='passport'; END IF;
IF NEW.stic_identification_type_c='nif' THEN SET NEW.stic_identification_type_c='nif'; END IF;
IF NEW.stic_identification_type_c='nie' THEN SET NEW.stic_identification_type_c='nie'; END IF;
IF NEW.stic_gender_c='mujer' THEN SET NEW.stic_gender_c='female'; END IF;
IF NEW.stic_gender_c='hombre' THEN SET NEW.stic_gender_c='male'; END IF;
IF NEW.stic_employment_status_c='porCuentaAjena' THEN SET NEW.stic_employment_status_c='employee'; END IF;
IF NEW.stic_employment_status_c='parado' THEN SET NEW.stic_employment_status_c='unemployed'; END IF;
IF NEW.stic_employment_status_c='jubilado' THEN SET NEW.stic_employment_status_c='retired'; END IF;
IF NEW.stic_employment_status_c='estudiante' THEN SET NEW.stic_employment_status_c='student'; END IF;
IF NEW.stic_employment_status_c='autonoma' THEN SET NEW.stic_employment_status_c='freelance'; END IF;
IF NEW.stic_alt_address_type_c='trabajo' THEN SET NEW.stic_alt_address_type_c='workplace'; END IF;
IF NEW.stic_alt_address_type_c='trabajo' THEN SET NEW.stic_alt_address_type_c='workplace'; END IF;
IF NEW.stic_alt_address_type_c='residencia' THEN SET NEW.stic_alt_address_type_c='residence'; END IF;
IF NEW.stic_alt_address_type_c='residencia' THEN SET NEW.stic_alt_address_type_c='residence'; END IF;
IF NEW.stic_alt_address_type_c='particular' THEN SET NEW.stic_alt_address_type_c='home'; END IF;
IF NEW.stic_alt_address_type_c='particular' THEN SET NEW.stic_alt_address_type_c='home'; END IF;
IF NEW.stic_alt_address_type_c='otros' THEN SET NEW.stic_alt_address_type_c='other'; END IF;
IF NEW.stic_alt_address_type_c='otros' THEN SET NEW.stic_alt_address_type_c='other'; END IF;
IF NEW.stic_alt_address_region_c='regionMurcia' THEN SET NEW.stic_alt_address_region_c='murcia'; END IF;
IF NEW.stic_alt_address_region_c='principadoAsturias' THEN SET NEW.stic_alt_address_region_c='asturias'; END IF;
IF NEW.stic_alt_address_region_c='paisVasco' THEN SET NEW.stic_alt_address_region_c='pais_vasco'; END IF;
IF NEW.stic_alt_address_region_c='navarra' THEN SET NEW.stic_alt_address_region_c='navarra'; END IF;
IF NEW.stic_alt_address_region_c='melilla' THEN SET NEW.stic_alt_address_region_c='melilla'; END IF;
IF NEW.stic_alt_address_region_c='laRioja' THEN SET NEW.stic_alt_address_region_c='rioja'; END IF;
IF NEW.stic_alt_address_region_c='islasCanarias' THEN SET NEW.stic_alt_address_region_c='canarias'; END IF;
IF NEW.stic_alt_address_region_c='islasBaleares' THEN SET NEW.stic_alt_address_region_c='baleares'; END IF;
IF NEW.stic_alt_address_region_c='galicia' THEN SET NEW.stic_alt_address_region_c='galicia'; END IF;
IF NEW.stic_alt_address_region_c='extremadra' THEN SET NEW.stic_alt_address_region_c='extremadra'; END IF;
IF NEW.stic_alt_address_region_c='comunidadValenciana' THEN SET NEW.stic_alt_address_region_c='valencia'; END IF;
IF NEW.stic_alt_address_region_c='comunidadMadrid' THEN SET NEW.stic_alt_address_region_c='madrid'; END IF;
IF NEW.stic_alt_address_region_c='ceuta' THEN SET NEW.stic_alt_address_region_c='ceuta'; END IF;
IF NEW.stic_alt_address_region_c='cataluna' THEN SET NEW.stic_alt_address_region_c='catalunya'; END IF;
IF NEW.stic_alt_address_region_c='castillaLeon' THEN SET NEW.stic_alt_address_region_c='castilla_leon'; END IF;
IF NEW.stic_alt_address_region_c='castillaLaMancha' THEN SET NEW.stic_alt_address_region_c='castilla_mancha'; END IF;
IF NEW.stic_alt_address_region_c='cantabria' THEN SET NEW.stic_alt_address_region_c='cantabria'; END IF;
IF NEW.stic_alt_address_region_c='aragon' THEN SET NEW.stic_alt_address_region_c='aragon'; END IF;
IF NEW.stic_alt_address_region_c='andalucia' THEN SET NEW.stic_alt_address_region_c='andalucia'; END IF;
IF NEW.stic_alt_address_county_c='vallesOriental' THEN SET NEW.stic_alt_address_county_c='valles_oriental'; END IF;
IF NEW.stic_alt_address_county_c='vallesOccidental' THEN SET NEW.stic_alt_address_county_c='valles_occidental'; END IF;
IF NEW.stic_alt_address_county_c='valdAran' THEN SET NEW.stic_alt_address_county_c='val_aran'; END IF;
IF NEW.stic_alt_address_county_c='urgell' THEN SET NEW.stic_alt_address_county_c='urgell'; END IF;
IF NEW.stic_alt_address_county_c='terraAlta' THEN SET NEW.stic_alt_address_county_c='terra_alta'; END IF;
IF NEW.stic_alt_address_county_c='tarragones' THEN SET NEW.stic_alt_address_county_c='tarragones'; END IF;
IF NEW.stic_alt_address_county_c='solsones' THEN SET NEW.stic_alt_address_county_c='solsones'; END IF;
IF NEW.stic_alt_address_county_c='selva' THEN SET NEW.stic_alt_address_county_c='selva'; END IF;
IF NEW.stic_alt_address_county_c='segria' THEN SET NEW.stic_alt_address_county_c='segria'; END IF;
IF NEW.stic_alt_address_county_c='segarra' THEN SET NEW.stic_alt_address_county_c='segarra'; END IF;
IF NEW.stic_alt_address_county_c='ripolle' THEN SET NEW.stic_alt_address_county_c='ripolles'; END IF;
IF NEW.stic_alt_address_county_c='riberaEbre' THEN SET NEW.stic_alt_address_county_c='ribera_ebre'; END IF;
IF NEW.stic_alt_address_county_c='priorat' THEN SET NEW.stic_alt_address_county_c='priorat'; END IF;
IF NEW.stic_alt_address_county_c='pladUrgell' THEN SET NEW.stic_alt_address_county_c='pla_urgell'; END IF;
IF NEW.stic_alt_address_county_c='pladEstany' THEN SET NEW.stic_alt_address_county_c='pla_estany'; END IF;
IF NEW.stic_alt_address_county_c='pallarsSobira' THEN SET NEW.stic_alt_address_county_c='pallars_sobira'; END IF;
IF NEW.stic_alt_address_county_c='pallarsJussa' THEN SET NEW.stic_alt_address_county_c='pallars_jussa'; END IF;
IF NEW.stic_alt_address_county_c='osona' THEN SET NEW.stic_alt_address_county_c='osona'; END IF;
IF NEW.stic_alt_address_county_c='noguera' THEN SET NEW.stic_alt_address_county_c='noguera'; END IF;
IF NEW.stic_alt_address_county_c='montsia' THEN SET NEW.stic_alt_address_county_c='montsia'; END IF;
IF NEW.stic_alt_address_county_c='maresme' THEN SET NEW.stic_alt_address_county_c='maresme'; END IF;
IF NEW.stic_alt_address_county_c='girones' THEN SET NEW.stic_alt_address_county_c='girones'; END IF;
IF NEW.stic_alt_address_county_c='garrotxa' THEN SET NEW.stic_alt_address_county_c='garrotxa'; END IF;
IF NEW.stic_alt_address_county_c='garrigues' THEN SET NEW.stic_alt_address_county_c='garrigues'; END IF;
IF NEW.stic_alt_address_county_c='garraf' THEN SET NEW.stic_alt_address_county_c='garraf'; END IF;
IF NEW.stic_alt_address_county_c='concaBarbera' THEN SET NEW.stic_alt_address_county_c='conca_barbera'; END IF;
IF NEW.stic_alt_address_county_c='cerdanya' THEN SET NEW.stic_alt_address_county_c='cerdanya'; END IF;
IF NEW.stic_alt_address_county_c='bergueda' THEN SET NEW.stic_alt_address_county_c='bergueda'; END IF;
IF NEW.stic_alt_address_county_c='barcelones' THEN SET NEW.stic_alt_address_county_c='barcelones'; END IF;
IF NEW.stic_alt_address_county_c='baixPenedes' THEN SET NEW.stic_alt_address_county_c='baix_penedes'; END IF;
IF NEW.stic_alt_address_county_c='baixLlobregat' THEN SET NEW.stic_alt_address_county_c='baix_llobregat'; END IF;
IF NEW.stic_alt_address_county_c='baixEmporda' THEN SET NEW.stic_alt_address_county_c='baix_emporda'; END IF;
IF NEW.stic_alt_address_county_c='baixEbre' THEN SET NEW.stic_alt_address_county_c='baix_ebre'; END IF;
IF NEW.stic_alt_address_county_c='baixCamp' THEN SET NEW.stic_alt_address_county_c='baix_camp'; END IF;
IF NEW.stic_alt_address_county_c='bages' THEN SET NEW.stic_alt_address_county_c='bages'; END IF;
IF NEW.stic_alt_address_county_c='anoia' THEN SET NEW.stic_alt_address_county_c='anoia'; END IF;
IF NEW.stic_alt_address_county_c='altUrgell' THEN SET NEW.stic_alt_address_county_c='alt_urgell'; END IF;
IF NEW.stic_alt_address_county_c='altPenedes' THEN SET NEW.stic_alt_address_county_c='alt_penedes'; END IF;
IF NEW.stic_alt_address_county_c='altEmporda' THEN SET NEW.stic_alt_address_county_c='alt_emporda'; END IF;
IF NEW.stic_alt_address_county_c='altCamp' THEN SET NEW.stic_alt_address_county_c='alt_camp'; END IF;
IF NEW.stic_alt_address_county_c='altaRibagorca' THEN SET NEW.stic_alt_address_county_c='alta_ribagorca'; END IF;
IF NEW.stic_acquisition_channel_c='web' THEN SET NEW.stic_acquisition_channel_c='web'; END IF;
IF NEW.stic_acquisition_channel_c='telemarketing' THEN SET NEW.stic_acquisition_channel_c='telemarketing'; END IF;
IF NEW.stic_acquisition_channel_c='postal' THEN SET NEW.stic_acquisition_channel_c='postal_mail'; END IF;
IF NEW.stic_acquisition_channel_c='otros' THEN SET NEW.stic_acquisition_channel_c='other'; END IF;
IF NEW.stic_acquisition_channel_c='movil' THEN SET NEW.stic_acquisition_channel_c='mobile'; END IF;
IF NEW.stic_acquisition_channel_c='mail' THEN SET NEW.stic_acquisition_channel_c='email'; END IF;
IF NEW.stic_acquisition_channel_c='f2f' THEN SET NEW.stic_acquisition_channel_c='face_to_face'; END IF;
IF NEW.stic_acquisition_channel_c='evento' THEN SET NEW.stic_acquisition_channel_c='event'; END IF;
END
$$
DELIMITER ;

DELIMITER $$
$$
CREATE TRIGGER `stic_settings_before_insert` BEFORE UPDATE ON `stic_settings` FOR EACH ROW BEGIN
IF NEW.name='GENERAL_PAYMENT_GENERATION_MONTH' AND NEW.value='X' THEN SET NEW.value='A'; END IF;
IF NEW.name='GENERAL_ORGANIZATION_NAME' AND NEW.value='FUNDACION PRIVADA XXXX' THEN SET NEW.value=''; END IF;
IF NEW.name='GENERAL_ORGANIZATION_NAME' AND NEW.value='Fundació XXX' THEN SET NEW.value=''; END IF;
IF NEW.name='GENERAL_ORGANIZATION_NAME' AND NEW.value='xxxxxxx' THEN SET NEW.value=''; END IF;
IF NEW.name='M182_NUMERO_JUSTIFICANTE' AND NEW.value='XXXXXXXXXXXXX' THEN SET NEW.value=''; END IF;
IF NEW.name='GENERAL_ORGANIZATION_ID' AND NEW.value='G60070099' THEN SET NEW.value=''; END IF;
IF NEW.name='GENERAL_ORGANIZATION_ID' AND NEW.value='G12345678' THEN SET NEW.value=''; END IF;
IF NEW.name='M182_PORCENTAJE_DEDUCCION_AUTONOMICA_XX' AND NEW.value='0' THEN SET NEW.value=''; END IF;
IF NEW.name='M182_PERSONA_CONTACTO_APELLIDO_1' AND NEW.value='FERNÁNDEZ' THEN SET NEW.value=''; END IF;
IF NEW.name='M182_PERSONA_CONTACTO_APELLIDO_2' AND NEW.value='GARCÍA' THEN SET NEW.value=''; END IF;
IF NEW.name='M182_PERSONA_CONTACTO_NOMBRE' AND NEW.value='RODOLFO' THEN SET NEW.value=''; END IF;
IF NEW.name='M182_PERSONA_CONTACTO_TELEFONO' AND NEW.value='933450011' THEN SET NEW.value=''; END IF;
IF NEW.name='SEPA_BIC_CODE' AND NEW.value='CAIXESBBXXX' THEN SET NEW.value=''; END IF;
IF NEW.name='SEPA_BIC_CODE' AND NEW.value='AAAABBCCXXX' THEN SET NEW.value=''; END IF;
IF NEW.name='SEPA_DEBIT_DEFAULT_REMITTANCE_INFO' AND NEW.value='Quota/Donatiu' THEN SET NEW.value=''; END IF;
IF NEW.name='SEPA_DEBIT_DEFAULT_REMITTANCE_INFO' AND NEW.value='Cuota/Donativo' THEN SET NEW.value=''; END IF;
IF NEW.name='SEPA_TRANSFER_DEFAULT_REMITTANCE_INFO' AND NEW.value='XXXXX' THEN SET NEW.value=''; END IF;
IF NEW.name='SEPA_TRANSFER_DEFAULT_REMITTANCE_INFO' AND NEW.value='Pagament' THEN SET NEW.value=''; END IF;
IF NEW.name='SEPA_DEBIT_CREDITOR_IDENTIFIER' AND NEW.value='ESZZXXXAAAAAAAAA' THEN SET NEW.value=''; END IF;
IF NEW.name='SEPA_DEBIT_CREDITOR_IDENTIFIER' AND NEW.value='xxxxxxx' THEN SET NEW.value=''; END IF;
IF NEW.name='TPV_MERCHANT_CODE' AND NEW.value='000000000' THEN SET NEW.value=''; END IF;
IF NEW.name='TPV_MERCHANT_NAME' AND NEW.value='Prueba' THEN SET NEW.value=''; END IF;
IF NEW.name='TPV_MERCHANT_NAME' AND NEW.value='Fundació XXX' THEN SET NEW.value=''; END IF;
IF NEW.name='TPV_PASSWORD' AND NEW.value='qwertyasdf0123456789' THEN SET NEW.value=''; END IF;
IF NEW.name='TPV_PASSWORD_TEST' AND NEW.value='qwertyasdf0123456789' THEN SET NEW.value=''; END IF;
IF NEW.name='PAYPAL_ID' AND NEW.value='XXXXXXXXXXXX' THEN SET NEW.value=''; END IF;
IF NEW.name='PAYPAL_ID_TEST' AND NEW.value='XXXXXXXXXXXX' THEN SET NEW.value=''; END IF;

END
$$
DELIMITER ;