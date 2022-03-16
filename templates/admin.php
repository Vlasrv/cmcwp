<h1 class="wp-heading-inline">CMC Convert Tool</h1> 
<div>
    <p>Some cool plugin description here</p>
</div>
<hr />
<div>
    <form method="POST" action="?cmcaction=updatesettings">
        <table class="form-table" role="presentation">
            <tbody>
                <tr>
                    <th><label for="cmc_api_key">CMC API Key</label></th>
                    <td>
                        <input type="text" name="cmc_api_key" id="cmc_api_key" value="<?php echo $cmc_api_key ?>" class="regular-text"><br />
                        <span class="description">You can get it from CMC site. 
                            <a href="https://pro.coinmarketcap.com/account" target="_blank">Click here</a> to login on CMC dashboard.
                        </span>
                    </td>
                </tr>
                <tr class="user-display-name-wrap">
                    <td colspan="2">
                        <input type="submit" value="Save Settings" class="button button-primary" />
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
</div>

