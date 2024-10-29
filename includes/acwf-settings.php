<?php
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

if(!class_exists('Acwpf_Register_Settings')){
	class Acwpf_Register_Settings {

		public function __construct() {

			// Let's make some menus.
			add_filter( 'wpforms_settings_tabs', array( $this, 'add_ac_setting_tab' ), 9 );

			add_filter( 'wpforms_settings_defaults', array( $this, 'add_fields' ), 9 );
            
            add_filter( 'wpforms_builder_settings_sections', array( $this, 'assign_ac_fields_menu' ), 9 );

            add_action('wpforms_form_settings_panel_content',array($this,'assign_ac_fields'),10);

		}

		public function add_ac_setting_tab($tabs){
			$tabs['ac-integration'] = array(
				'name'   => esc_html__( 'Active Campaign Integration', 'active-campaign-wpforms' ),
				'form'   => true,
				'submit' => esc_html__( 'Save Settings', 'active-campaign-wpforms' ),
			);

			return $tabs;
		}

		public function add_fields($defaults){
			$defaults['ac-integration'] = array(
				'ac-heading' => array(
					'id'       => 'ac-heading',
					'content'  => '<h4>' . esc_html__( 'Active Campaign Settings', 'active-campaign-wpforms' ) . '</h4><p>' . esc_html__( 'Add your Active Campaign API credentials here.This is a global setting area,you can also add API keys seperately for each forms.', 'active-campaign-wpforms' ) . '</p>',
					'type'     => 'content',
					'no_label' => true,
					'class'    => array( 'section-heading' ),
				),
				'ac-url'              => array(
					'id'      => 'ac-url',
					'name'    => esc_html__( 'Active Campaign URL', 'active-campaign-wpforms' ),
					'type'    => 'text',
					'desc'    => sprintf(esc_html__( 'You can get url from %s.', 'ac-wpforms-pro' ),'<a href="https://help.activecampaign.com/hc/en-us/articles/207317590-Getting-started-with-the-API" target="_blank">here</a>'),
				),
				'ac-apikey'            => array(
					'id'      => 'ac-apikey',
					'name'    => esc_html__( 'API Key', 'active-campaign-wpforms' ),
					'type'    => 'text',
					'desc' => sprintf(esc_html__( 'You can get API key from %s.', 'ac-wpforms-pro' ),'<a href="https://help.activecampaign.com/hc/en-us/articles/207317590-Getting-started-with-the-API" target="_blank">here</a>'),
				),
				'ac-listid'            => array(
					'id'      => 'ac-listid',
					'name'    => esc_html__( 'List ID', 'active-campaign-wpforms' ),
					'type'    => 'text',
					'desc'    => sprintf(esc_html__( 'You can get API key from %s.', 'ac-wpforms-pro' ),'<a href="http://support.exitbee.com/email-marketing-crm-integrations/how-to-find-your-activecampaign-list-id" target="_blank">here</a>'),
				),
			);

			return $defaults;

		}

		public function assign_ac_fields_menu($sections){
			$sections['ac-integration'] = 'Active Campaign Integration';
			$sections['assign-acfields'] = 'Assign Form Fields to AC';

			return $sections;
		}

		public function assign_ac_fields($settings){

			//AC Integration Tab
			echo '<div class="wpforms-panel-content-section wpforms-panel-content-section-ac-integration">';
			echo '<div class="wpforms-panel-content-section-title">';
				esc_html_e( 'Active Campaign Integration', 'ac-wpforms-pro' );
			echo '</div>';
			echo '<br><em class="field-desc">This will replace the global settings.</em>';	

			echo '<div class="wpforms-builder-settings-block-content">';
				wpforms_panel_field(
					'checkbox',
					'ac-integration',
					'enable-ac',
					$settings->form_data,
					esc_html__( 'Enable Active Campaign', 'ac-wpforms-pro' ),
					array(
						'default'    => '0',
						'parent'     => 'settings',
						'class'      => 'email-recipient',
					)
				);
				wpforms_panel_field(
					'text',
					'ac-integration',
					'ac-url',
					$settings->form_data,
					esc_html__( 'Active Campaign URL', 'ac-wpforms-pro' ),
					array(
						'default'    => '',
						'tooltip'    => sprintf(esc_html__( 'You can get url from %s.', 'ac-wpforms-pro' ),'<a href="https://help.activecampaign.com/hc/en-us/articles/207317590-Getting-started-with-the-API" target="_blank">here</a>'),
						'parent'     => 'settings',
						'class'      => 'email-recipient',
					)
				);
				wpforms_panel_field(
					'text',
					'ac-integration',
					'ac-apikey',
					$settings->form_data,
					esc_html__( 'Active Campaign API Key', 'ac-wpforms-pro' ),
					array(
						'default'    => '',
						'tooltip'    => sprintf(esc_html__( 'You can get API key from %s.', 'ac-wpforms-pro' ),'<a href="https://help.activecampaign.com/hc/en-us/articles/207317590-Getting-started-with-the-API" target="_blank">here</a>'),
						'parent'     => 'settings',
						'class'      => 'email-recipient',
					)
				);
				?>
				<div id="wpforms-panel-field-ac-integration-list_ids-wrap" class="wpforms-panel-field email-recipient wpforms-panel-field-text">
	            <label for="acwf_list_id"><?php echo __("Active Campaign Email List IDs","ac-wpforms-pro-pro"); ?></label>
			    <em style="padding:0"><?php echo sprintf(__("You Must Add List Id to add contacts in your Active Campiagn Lists. You can get List ID from %s.","ac-wpforms-pro-pro"),'<a href="http://support.exitbee.com/email-marketing-crm-integrations/how-to-find-your-activecampaign-list-id" target="_blank">here</a>'); ?></em>
			    <?php 
			    $data = $settings->form_data;
			    $list_id = isset($data['settings']['ac-integration']['list-id']) ? $data['settings']['ac-integration']['list-id'] : '';
			    ?>
		        <input type="number" min="0" name="settings[ac-integration][list-id]" value="<?php echo $list_id; ?>" />
		        <span class="add-button table-contacts"><a href="javascript:void(0)" class="docopy-table-list button"><?php esc_html_e('Add List','active-campaign-wpforms'); ?></a></span>
			    </div>
		    	<em class="field-desc" style="color:red"><?php echo __("Available in Premium Version.","active-campaign-wpforms"); ?>
		    		<a href="https://wpoperation.com/plugins/active-campaign-wpforms-pro/" target="_blank"><?php esc_html_e('Get Pro Version','active-campaign-wpforms'); ?></a>
		    	</em>
				<?php
			echo '</div>';
			echo '</div>';

			//AC Field Assign Tab
			echo '<div class="wpforms-panel-content-section wpforms-panel-content-section-assign-acfields">';

			echo '<div class="wpforms-panel-content-section-title">';
				esc_html_e( 'Assign Form Fields', 'active-campaign-wpforms' );
			echo '</div>';
            $id = 1;
			echo '<div class="wpforms-builder-settings-block-content">';
				wpforms_panel_field(
					'text',
					'assign-acfields',
					'email',
					$settings->form_data,
					esc_html__( 'Email Address*', 'active-campaign-wpforms' ),
					array(
						'default'    => '',
						'tooltip'    => esc_html__( 'Enter the email address to save in your Active Campaign Contact Lists.You can also use the smart tags from your form fields.', 'active-campaign-wpforms' ),
						'smarttags'  => array(
							'type'   => 'fields',
							'fields' => 'email',
						),
						'parent'     => 'settings',
						'class'      => 'email-recipient',
					)
				);
			echo '</div>';

			echo '<span><b>';
			echo esc_html__('Following fields are optional select if available in form, otherwise leave unselected. Only email field is required.','active-campaign-wpforms');
			echo '</b></span><br>';

			echo '<div class="wpforms-builder-settings-block-content">';
				wpforms_panel_field(
					'text',
					'assign-acfields',
					'first-name',
					$settings->form_data,
					esc_html__( 'First Name', 'active-campaign-wpforms' ),
					array(
						'default'    => '',
						'tooltip'    => esc_html__( 'You can use the smart tags from your form fields.', 'active-campaign-wpforms' ),
						'smarttags'  => array(
							'type' => 'all',
						),
						'parent'     => 'settings',
						'class'      => 'email-recipient',
					)
				);
				wpforms_panel_field(
					'text',
					'assign-acfields',
					'last-name',
					$settings->form_data,
					esc_html__( 'Last Name', 'active-campaign-wpforms' ),
					array(
						'default'    => '',
						'tooltip'    => esc_html__( 'You can use the smart tags from your form fields.', 'active-campaign-wpforms' ),
						'smarttags'  => array(
							'type' => 'all',
						),
						'parent'     => 'settings',
						'class'      => 'email-recipient',
					)
				);

				echo '<em class="field-desc">';
				echo esc_html__('Note:If you have Name field containing first and last name then you can skip this Last Name field','active-campaign-wpforms');
				echo'</em><br><br>';

				wpforms_panel_field(
					'text',
					'assign-acfields',
					'phone',
					$settings->form_data,
					esc_html__( 'Phone', 'active-campaign-wpforms' ),
					array(
						'default'    => '',
						'tooltip'    => esc_html__( 'You can use the smart tags from your form fields.', 'active-campaign-wpforms' ),
						'smarttags'  => array(
							'type' => 'all',
						),
						'parent'     => 'settings',
						'class'      => 'email-recipient',
					)
				);

				wpforms_panel_field(
					'text',
					'assign-acfields',
					'organization',
					$settings->form_data,
					esc_html__( 'Organization', 'active-campaign-wpforms' ),
					array(
						'default'    => '',
						'tooltip'    => esc_html__( 'You can use the smart tags from your form fields.', 'active-campaign-wpforms' ),
						'smarttags'  => array(
							'type' => 'all',
						),
						'parent'     => 'settings',
						'class'      => 'email-recipient',
					)
				);

				wpforms_panel_field(
					'textarea',
					'assign-acfields',
					'tags',
					$settings->form_data,
					esc_html__( 'Tags', 'active-campaign-wpforms' ),
					array(
						'default'    => '',
						'tooltip'    => esc_html__( 'You can use the smart tags from your form fields.', 'active-campaign-wpforms' ),
						'smarttags'  => array(
							'type' => 'all',
						),
						'parent'     => 'settings',
						'class'      => 'email-recipient pro-fields',
					)
				);
				?>
				<em class="field-desc" style="color:red">
					<?php esc_html_e('Available in Pro Version.','active-campaign-wpforms'); ?>
					<a href="https://wpoperation.com/plugins/active-campaign-wpforms-pro/" class="pro-link" data-name="test">
						<?php esc_html_e('Get Pro Version','active-campaign-wpforms'); ?>
					</a></em>
               


                <div class="wpforms-panel-field" style="margin-top:40px;">
                <label><?php echo __("Add Custom Fields.","active-campaign-wpforms"); ?></label>
			    <div class="contacts-meta-section-wrapper">
			    	<span class="add-button table-contacts"><a href="javascript:void(0)" class="docopy-table-contact button"><?php esc_html_e('Add Field','active-campaign-wpforms'); ?></a></span>
			    </div>
			    </div>
		    	<em class="field-desc" style="color:red"><?php echo __("Available in Premium Version.","active-campaign-wpforms"); ?>
		    		<a href="https://wpoperation.com/plugins/active-campaign-wpforms-pro/" target="_blank"><?php esc_html_e('Get Pro Version','active-campaign-wpforms'); ?></a>
		    	</em>
			    <?php	    
			echo '</div>';

			echo '</div>';
		}
	}
} 

new Acwpf_Register_Settings();