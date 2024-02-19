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
<div id='grid_Div'>
    <table width="100%">
        <tr>
            <td>
                <h2>{$MOD.LBL_MASS_UPDATE_DATES_TITTLE}</h2>
            </td>
        </tr>
	</table>
	
    <form id="MassUpdateDates" name="MassUpdateDates" method="POST">
		<input type="hidden" id="module" name="module" value="stic_Work_Calendar">
		<input type="hidden" id="action" name="action" value="runMassUpdateDates">
		<input type="hidden" id="selectedIDs" name="selectedIDs" value="{$selectedIDs}">
        
        <table  style='width:40%; margin:1%; padding:5%;'>
            <tr>
                <th>
                    {$MOD.LBL_MASS_UPDATE_DATES_FIELD}
                </th>
                <th>
                    {$MOD.LBL_MASS_UPDATE_DATES_OPERADOR}
                </th>
                <th>
                    {$MOD.LBL_MASS_UPDATE_DATES_HORAS}
                </th>
                <th>
                    {$MOD.LBL_MASS_UPDATE_DATES_MINUTES}
                </th>
           </tr>        
            <tr>
                <td>
                    <h3>{$MOD.LBL_START_DATE}:</h3>
                </td>                    
                <td>
                    <select name="startDateOperator">
                        <option label="" value="" selected> </option>
                        <option label="=" value="=">=</option>
                        <option label="+" value="+">+</option>
                        <option label="-" value="-">-</option>
                    </select>
                </td>        
                <td>
                    <select name="startDateHours">
                        <option label="00" value="1">00</option>            
                        <option label="01" value="1">01</option>
                        <option label="02" value="2">02</option>
                        <option label="03" value="3">03</option>
                        <option label="04" value="4">04</option>
                        <option label="05" value="5">05</option>
                        <option label="06" value="6">06</option>
                        <option label="07" value="7">07</option>
                        <option label="08" value="8">08</option>
                        <option label="09" value="9">09</option>
                        <option label="10" value="10">10</option>
                        <option label="11" value="11">11</option>
                        <option label="12" value="12">12</option>
                        <option label="13" value="13">13</option>
                        <option label="14" value="14">14</option>
                        <option label="15" value="15">15</option>
                        <option label="16" value="16">16</option>
                        <option label="17" value="17">17</option>
                        <option label="18" value="18">18</option>
                        <option label="19" value="19">19</option>
                        <option label="20" value="20">20</option>
                        <option label="21" value="21">21</option>
                        <option label="22" value="22">22</option>
                        <option label="23" value="23">23</option>
                    </select>
                </td>
                <td>
                    <select name="startDateMinutes">
                        <option label="00" value="1">00</option>                    
                        <option label="01" value="1">01</option>
                        <option label="02" value="2">02</option>
                        <option label="03" value="3">03</option>
                        <option label="04" value="4">04</option>
                        <option label="05" value="5">05</option>
                        <option label="06" value="6">06</option>
                        <option label="07" value="7">07</option>
                        <option label="08" value="8">08</option>
                        <option label="09" value="9">09</option>
                        <option label="10" value="10">10</option>
                        <option label="11" value="11">11</option>
                        <option label="12" value="12">12</option>
                        <option label="13" value="13">13</option>
                        <option label="14" value="14">14</option>
                        <option label="15" value="15">15</option>
                        <option label="16" value="16">16</option>
                        <option label="17" value="17">17</option>
                        <option label="18" value="18">18</option>
                        <option label="19" value="19">19</option>
                        <option label="20" value="20">20</option>
                        <option label="21" value="21">21</option>
                        <option label="22" value="22">22</option>
                        <option label="23" value="23">23</option>
                        <option label="24" value="24">24</option>
                        <option label="25" value="25">25</option>
                        <option label="26" value="26">26</option>
                        <option label="27" value="27">27</option>
                        <option label="28" value="28">28</option>
                        <option label="29" value="29">29</option>
                        <option label="30" value="30">30</option>
                        <option label="31" value="31">31</option>
                        <option label="32" value="32">32</option>
                        <option label="33" value="33">33</option>
                        <option label="34" value="34">34</option>
                        <option label="35" value="35">35</option>
                        <option label="36" value="36">36</option>
                        <option label="37" value="37">37</option>
                        <option label="38" value="38">38</option>
                        <option label="39" value="39">39</option>
                        <option label="40" value="40">40</option>
                        <option label="41" value="41">41</option>
                        <option label="42" value="42">42</option>
                        <option label="43" value="43">43</option>
                        <option label="44" value="44">44</option>
                        <option label="45" value="45">45</option>
                        <option label="46" value="46">46</option>
                        <option label="47" value="47">47</option>
                        <option label="48" value="48">48</option>
                        <option label="49" value="49">49</option>
                        <option label="50" value="50">50</option>
                        <option label="51" value="51">51</option>
                        <option label="52" value="52">52</option>
                        <option label="53" value="53">53</option>
                        <option label="54" value="54">54</option>
                        <option label="55" value="55">55</option>
                        <option label="56" value="56">56</option>
                        <option label="57" value="57">57</option>
                        <option label="58" value="58">58</option>
                        <option label="59" value="59">59</option>
                    </select>
                </td>            
            </tr>

            <tr>
                <td>
                    <h3>{$MOD.LBL_END_DATE}:</h3>
                </td>        
                <td>
                    <select name="endDateOperator">
                        <option label="" value="" selected> </option>                
                        <option label="=" value="=">=</option>
                        <option label="+" value="+">+</option>
                        <option label="-" value="-">-</option>
                    </select>
                </td>        
                <td>
                    <select name="endDateHours">
                        <option label="00" value="1">00</option>                    
                        <option label="01" value="1">01</option>
                        <option label="02" value="2">02</option>
                        <option label="03" value="3">03</option>
                        <option label="04" value="4">04</option>
                        <option label="05" value="5">05</option>
                        <option label="06" value="6">06</option>
                        <option label="07" value="7">07</option>
                        <option label="08" value="8">08</option>
                        <option label="09" value="9">09</option>
                        <option label="10" value="10">10</option>
                        <option label="11" value="11">11</option>
                        <option label="12" value="12">12</option>
                        <option label="13" value="13">13</option>
                        <option label="14" value="14">14</option>
                        <option label="15" value="15">15</option>
                        <option label="16" value="16">16</option>
                        <option label="17" value="17">17</option>
                        <option label="18" value="18">18</option>
                        <option label="19" value="19">19</option>
                        <option label="20" value="20">20</option>
                        <option label="21" value="21">21</option>
                        <option label="22" value="22">22</option>
                        <option label="23" value="23">23</option>
                    </select>
                </td>
                <td>
                    <select name="endDateMinutes">
                        <option label="00" value="1">00</option>                    
                        <option label="01" value="1">01</option>
                        <option label="02" value="2">02</option>
                        <option label="03" value="3">03</option>
                        <option label="04" value="4">04</option>
                        <option label="05" value="5">05</option>
                        <option label="06" value="6">06</option>
                        <option label="07" value="7">07</option>
                        <option label="08" value="8">08</option>
                        <option label="09" value="9">09</option>
                        <option label="10" value="10">10</option>
                        <option label="11" value="11">11</option>
                        <option label="12" value="12">12</option>
                        <option label="13" value="13">13</option>
                        <option label="14" value="14">14</option>
                        <option label="15" value="15">15</option>
                        <option label="16" value="16">16</option>
                        <option label="17" value="17">17</option>
                        <option label="18" value="18">18</option>
                        <option label="19" value="19">19</option>
                        <option label="20" value="20">20</option>
                        <option label="21" value="21">21</option>
                        <option label="22" value="22">22</option>
                        <option label="23" value="23">23</option>
                        <option label="24" value="24">24</option>
                        <option label="25" value="25">25</option>
                        <option label="26" value="26">26</option>
                        <option label="27" value="27">27</option>
                        <option label="28" value="28">28</option>
                        <option label="29" value="29">29</option>
                        <option label="30" value="30">30</option>
                        <option label="31" value="31">31</option>
                        <option label="32" value="32">32</option>
                        <option label="33" value="33">33</option>
                        <option label="34" value="34">34</option>
                        <option label="35" value="35">35</option>
                        <option label="36" value="36">36</option>
                        <option label="37" value="37">37</option>
                        <option label="38" value="38">38</option>
                        <option label="39" value="39">39</option>
                        <option label="40" value="40">40</option>
                        <option label="41" value="41">41</option>
                        <option label="42" value="42">42</option>
                        <option label="43" value="43">43</option>
                        <option label="44" value="44">44</option>
                        <option label="45" value="45">45</option>
                        <option label="46" value="46">46</option>
                        <option label="47" value="47">47</option>
                        <option label="48" value="48">48</option>
                        <option label="49" value="49">49</option>
                        <option label="50" value="50">50</option>
                        <option label="51" value="51">51</option>
                        <option label="52" value="52">52</option>
                        <option label="53" value="53">53</option>
                        <option label="54" value="54">54</option>
                        <option label="55" value="55">55</option>
                        <option label="56" value="56">56</option>
                        <option label="57" value="57">57</option>
                        <option label="58" value="58">58</option>
                        <option label="59" value="59">59</option>
                    </select>
                </td>
            </tr>            
        </table>
		<div id="cal-edit-buttons" class="ft">
			<input title="Guardar" class="button" type="submit" name="button" value="{$MOD.LBL_UPDATE_BUTTON}">
			<input title="Cancelar" class="button"
				onclick="SUGAR.ajaxUI.loadContent('index.php?action=index&module=stic_Work_Calendar'); return false;"
				type="submit" name="button" value="{$MOD.LBL_CANCEL_BUTTON}">
		</div>        
    </form>
</div>