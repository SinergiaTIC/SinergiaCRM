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
<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Change Password</title>
    <style>
      * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
      }

      body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: #f0f2f5;
        color: #333;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
      }

      .card {
        background: #fff;
        max-width: 400px;
        width: 100%;
        margin: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .1);
        padding: 30px;
      }

      h1 {
        font-size: 18px;
        margin-bottom: 20px;
      }

      .msg {
        padding: 10px;
        border-radius: 4px;
        margin-bottom: 15px;
        font-size: 13px;
      }

      .msg-error {
        background: #fdecea;
        color: #c62828;
        border: 1px solid #f5c6cb;
      }

      .msg-success {
        background: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #c8e6c9;
      }

      label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 5px;
        color: #555;
      }

      input[type="password"] {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        margin-bottom: 15px;
      }

      input:focus {
        outline: none;
        border-color: #1976d2;
        box-shadow: 0 0 0 2px rgba(25, 118, 210, .2);
      }

      .btn {
        display: block;
        width: 100%;
        padding: 12px;
        background: #1976d2;
        color: #fff;
        border: none;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
      }

      .btn:hover {
        background: #1565c0;
      }

      .links {
        text-align: center;
        margin-top: 15px;
        font-size: 13px;
      }

      .links a {
        color: #1976d2;
        text-decoration: none;
      }

      .help {
        font-size: 12px;
        color: #888;
        margin-bottom: 15px;
        padding: 8px;
        background: #f5f5f5;
        border-radius: 4px;
      }
    </style>
  </head>

  <body>
    <div class="card">
      <h1>Change Password</h1>
{/literal}
    {if $ERROR}<div class="msg msg-error">{$ERROR|escape}</div>{/if}
    {if $SUCCESS}
      <div class="msg msg-success">Password changed successfully.</div>
      <div class="links"><a href="index.php?entryPoint=sticPortalLogin">Back to portal</a></div>
    {else}
      <div class="help">Minimum {$PW_MIN_LENGTH} characters.{if $PW_REQUIRE_UPPER eq "1"} Must contain an uppercase
          letter.{/if}{if $PW_REQUIRE_LOWER eq "1"} Must contain a lowercase letter.{/if}{if $PW_REQUIRE_NUMBER eq "1"} Must
          contain a number.{/if}{if $PW_REQUIRE_SPECIAL eq "1"} Must contain a special character.{/if}</div>
        <form method="post">
          <input type="hidden" name="csrf_token" value="{$CSRF_TOKEN}">
          <label>Current Password</label><input type="password" name="current_password" required
            autocomplete="current-password">
          <label>New Password</label><input type="password" name="new_password" required autocomplete="new-password">
          <label>Confirm New Password</label><input type="password" name="confirm_password" required
            autocomplete="new-password">
          <button type="submit" class="btn">Change Password</button>
        </form>
      {/if}
      <div class="links"><a href="index.php?entryPoint=sticPortalLogout">Logout</a></div>
    </div>
  </body>

  </html>