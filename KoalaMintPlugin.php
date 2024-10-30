<?php
/**
 * Plugin Name: KoalaMint
 * Plugin URI: https://koalamint.com/
 * Description: Launch a Generative NFT Collection on Your Wordpress Site Without Code.       
 * Version: 2.0
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * Author: KoalaMint
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: koalamint
 */ 

/*
KoalaMint is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
KoalaMint is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with KoalaMint. If not, see https://koalamint.com/.
*/

/**
 * Adding Submenu under Settings Tab
 *
 * @since 1.0
 */
function KoalaMintAddMenu() {
    add_menu_page("KoalaMint", "KoalaMint", "manage_options", "KoalaMintPlugin", "KoalaMintPluginPage", "dashicons-admin-plugins");
}
add_action("admin_menu", "KoalaMintAddMenu");


add_action('wp_enqueue_scripts', 'KoalaMintAddJSLibrary');
function KoalaMintAddJSLibrary() {
    wp_enqueue_script("koalamintJSLibrary", "https://cdn.koalamint.com/koalamint.js");
}

function KoalaMintButton($params) { 
    add_action('wp_footer', 'KoalaMintSetupScript');

    $text = "";

    if (isset($params['text'])){
        $text = $params['text'];
    }

    $message .= '<div id="koalamint_div" data-button-text="' . $text . '"></div>';
    
    return $message;
} 
add_shortcode('KoalaMintButton', 'KoalaMintButton'); 

function KoalaMintSetupScript() {
    $key = esc_attr(get_option('KoalaMintAPIKey'));
    echo '<script type="text/javascript">' . esc_js('KoalaMint.setup(`' . $key . '`);') . '</script>';
}

/**
 * Setting Page Options
 * - add setting page
 * - save setting page
 *
 * @since 1.0
 */
function KoalaMintPluginPage() {
    ?>
    <style type="text/css">

    #koala_div ol li p{
        margin: 5px 0 10px 0;
    }
    
    #koala_div h4{
        margin: 5px 0px 15px 0px;
        font-weight: 300;
    }
    #koala_div #koala_help{
        margin-top: 40px;
        background: #fff;
    }

    #koala_div #koala_help{
        padding: 5px 15px;
    }
</style>
<div id="koala_div" class="wrap">
    <h1>
        KoalaMint Plugin
    </h1>
    <h4>The no-code solution to launch a generative NFT solution on your WordPress website.</h4>

    <div>
        <h3>How It Works</h3>
        Follow these steps to show a mint button of your NFT collection on your WordPress website.
        <ol>
            <li>
                <b>Create your Account</b>
                <p>Use Metamask to create your KoalaMint account in our platform <a target="_blank" href="https://koalamint.com/?utm_campaign=wordpress&utm_medium=plugin&utm_source=link">koalamint.com</a>.</p>
            </li>
            <li>
                <b>Upload NFT Collection</b>
                <p>Seamlessly integration with Google Drive to create your NFT collection.</p>
            </li>
            <li>
                <b>Create Smart Contract</b>
                <p>Deploy your own ERC-721 NFT smart contract without technical knowledge.</p>
            </li>
            <li>
                <b>Copy and paste your API Key</b>
                <p>Complete with your API Key below and Save changes</p>
            </li>
            <li>
                <b>Add the Mint Button on a Page/Post</b>
                <p>Add the following shortcode [KoalaMintButton] on any page/post to add the mint button.</p>
            </li>
        </ol>
    </div>

    <form method="post" action="options.php">
        <?php
        settings_fields("KoalaMintConfig");
        do_settings_sections("KoalaMintPlugin");
        submit_button ();
        ?>
    </form>

    <section id="koala_help">
        <h2>Need support?</h2>
        <ul>
            <li><a href="http://support.koalamint.com/" target="_blank">Help Center →</a></li>
        </ul>

    </section>
</div>
<?php
}

function KoalaMintRemovePluginSettings() {
    delete_option("KoalaMintAPIKey");
    unregister_setting("KoalaMintConfig", "KoalaMintAPIKey");
}

register_deactivation_hook(__FILE__, 'KoalaMintRemovePluginSettings');
register_uninstall_hook(__FILE__, 'KoalaMintRemovePluginSettings');

/**
 * Init setting section, Init setting field and register settings page
 *
 * @since 1.0
 */
function KoalaMintSettings() {
    add_settings_section("KoalaMintConfig", "", null, "KoalaMintPlugin");
    add_settings_field("KoalaMintAPIKey", "API KEY", "KoalaMintOptions", "KoalaMintPlugin", "KoalaMintConfig");
    register_setting("KoalaMintConfig", "KoalaMintAPIKey");
}
add_action("admin_init", "KoalaMintSettings");

/**
 * Add simple textfield value to setting page
 *
 * @since 1.0
 */
function KoalaMintOptions() {
    ?>
    <div class="postbox" style="width: 65%; padding: 30px;">
        <input 
        style="width: 100%" 
        type="text" 
        name="KoalaMintAPIKey" 
        value="<?php echo stripslashes_deep(esc_attr(get_option('KoalaMintAPIKey')));?>" /> 
        <br><br>
    </div>
    <?php
}
