<div class="wrap">
    <?php screen_icon(); ?>
    <?php $linkedPageID = get_option('wp_cookie_banner_linked_page'); ?>
    <h2>WP Cookie Banner Settings</h2>
    
    <?php if ($linkedPageID =="0") { ?>
    <div id="message" class="error fade"><p><strong>Settings incomplete </strong> - Your Cookie banner will not appear until you select your Privacy/Cookies Policy page from the drop-down below.</p></div>
    <?php } ?>

    <form method="post" action="options.php">  
        <?php
            settings_fields( 'wp_cookie_banner_option_group' );
            do_settings_sections('wp_cookie_banner_option_group');
        ?> 
        <?php echo '<input type="hidden" id="page_id" name="page" value="' . $_REQUEST['page'] . '"/>'; ?>              
     
        <!-- Settings Meta Box -->

        <div class="metabox-holder">
            <div class="postbox">
                <div id="" style="display: block;">                      
                    <h3>Cookie Banner Settings</h3>
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row">Development &amp; Testing Mode</th>
                                <td>
                                    <label for="wp_cookie_banner_test_mode">
                                    <input type="checkbox" class="checkbox" id="wp_cookie_banner_test_mode" name="wp_cookie_banner_test_mode" value="1" <?php checked( '1', get_option( 'wp_cookie_banner_test_mode' ) ); ?>> <strong>Enable Testing/Development Mode</strong></label><br />
                                    <span class="description">Checking this box will put the plugin into development mode, preventing the cookie from being dropped that hides the banner on subsuquent vists.</span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">Cookie Duration</th>
                                <td>
                                    <select name="wp_cookie_banner_duration" id="wp_cookie_banner_duration">
                                        <option <?php if(get_option('wp_cookie_banner_duration')=="1") echo "selected"; ?> value="1">1 Day</option>
                                        <option <?php if(get_option('wp_cookie_banner_duration')=="3") echo "selected"; ?> value="3">3 Days</option>
                                        <option <?php if(get_option('wp_cookie_banner_duration')=="5") echo "selected"; ?> value="5">5 Days</option>
                                        <option <?php if(get_option('wp_cookie_banner_duration')=="7") echo "selected"; ?> value="7">7 Days</option>
                                        <option <?php if(get_option('wp_cookie_banner_duration')=="14") echo "selected"; ?> value="14">14 Days</option>
                                        <option <?php if(get_option('wp_cookie_banner_duration')=="28") echo "selected"; ?> value="28">1 Month</option>
                                        <option <?php if(get_option('wp_cookie_banner_duration')=="365") echo "selected"; ?> value="365">1 Year</option>
                                    </select>
                                    <span class="description">Select how many days to hide the cookie law banner (after it's been displayed to the user).</span>
                                </td>
                            </tr>
                                <tr valign="top">
                                <th scope="row">Banner Display Duration</th>
                                <td>
                                    <select name="wp_cookie_banner_seconds" id="wp_cookie_banner_seconds">
                                        <option <?php if(get_option('wp_cookie_banner_seconds')=="3000") echo "selected"; ?> value="3000">3 Seconds</option>
                                        <option <?php if(get_option('wp_cookie_banner_seconds')=="5000") echo "selected"; ?> value="5000">5 Seconds</option>
                                        <option <?php if(get_option('wp_cookie_banner_seconds')=="10000") echo "selected"; ?> value="10000">10 Seconds</option>
                                        <option <?php if(get_option('wp_cookie_banner_seconds')=="15000") echo "selected"; ?> value="15000">15 Seconds</option>
                                        <option <?php if(get_option('wp_cookie_banner_seconds')=="30000") echo "selected"; ?> value="30000">30 Seconds</option>
                                        <option <?php if(get_option('wp_cookie_banner_seconds')=="999999999") echo "selected"; ?> value="999999999">Stay put</option>
                                    </select>
                                    <span class="description">Please choose a time in days to hide the cookie law banner (after it's been displayed to the user).</span>

                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">Privacy/Cookies Page</th>
                                <td>
                                    <select name="wp_cookie_banner_linked_page" id="wp_cookie_banner_linked_page"> 
                                        <option value="0"><?php echo esc_attr( __( 'Select Page' ) ); ?></option> 
                                             <?php 

                                                $args = array(
                                                    'sort_order' => 'asc',
                                                    'hierarchical' => 0,
                                                    'post_type' => 'page',
                                                    'post_status' => 'publish,draft'
                                                ); 
                                              $pages = get_pages($args); 
                                              foreach ( $pages as $page ) {
                                                $option = '<option ';
                                                if ($page->ID == $linkedPageID) {
                                                    $option .= 'selected="selected"';
                                                }
                                                $option .= 'value="' . $page->ID . '">';
                                                $option .= $page->post_title;
                                                $option .= '</option>';
                                                echo $option;
                                              }
                                             ?>
                                    </select>
                                    <span class="description">Please select the page (Privacy &amp; Cookies Policy) that you would like your banner to click through to.</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <!-- Customisation Meta Box -->

        <div class="metabox-holder">
            <div class="postbox">
                <div id="" style="display: block;">                      
                    <h3>Cookie Banner Presentation</h3>
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row">Cookie Banner Message</th>
                                <td>
                                    <textarea type="text" name="wp_cookie_banner_privacy_message" rows="8" class="large-text"><?php echo get_option('wp_cookie_banner_privacy_message'); ?></textarea>
                                    <span class="description">Please enter the message that you would like to dispay to your visitors. Make sure to add the [cookiepage] tag so that your page is linked up.</span>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">Cookie Banner CSS</th>
                                <td>
                                   <textarea type="text" name="wp_cookie_banner_default_css" rows="8" class="large-text"><?php echo get_option('wp_cookie_banner_default_css'); ?></textarea>
                                   <span class="description">If you prefer to style your banner on your own stylesheet, simply empty the box.</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php submit_button('Save all cookie banner settings'); ?>
    </form>  
</div>

<?php
require('creare-footer.php');