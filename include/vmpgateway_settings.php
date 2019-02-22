// Elementos del menu de configuracion de la pasarela

		function __construct(){

			$this->auth_expires = 20;
			$this->adjust_delay = 5;

			$this->log_errors = true;
			$this->log_errors_file = dirname(__FILE__) . '/failed_transactions';

			$this->id = 'mwc_gateway';
			$this->icon = MWC_INDEX . 'src/ccards_logos.png';
			$this->has_fields = true;
			$this->method_title = __('VegnuX Metropago Gateway', MWC_TXTDOM );
			$this->method_description = __('Direct payments with VegnuX Metropago Gateway. User will be asked to enter credit card details on the checkout page.', MWC_TXTDOM);

			$this->init_form_fields();
			$this->init_settings();

			$this->title = $this->get_option('title');

			add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
		}

		function init_form_fields(){
			$this->form_fields = array(
				'enabled' => array(
					'title' => __('Enable Matropago', MWC_TXTDOM),
					'type' => 'checkbox',
					'label' => __('Enable', MWC_TXTDOM),
					'default' => 'yes'
				),
				'title' => array(
					'title' => __('Method name', MWC_TXTDOM),
					'type' => 'text',
					'default' => __('VegnuX Metropago Gateway', MWC_TXTDOM),
					'desc_tip' => true
				),
				'description' => array(
					'title' => __('Method description', MWC_TXTDOM),
					'type' => 'textarea',
					'default' =>  __('Use this method to pay with your credit card securely.', MWC_TXTDOM)
				),
				'acc_code' => array(
					'title' => __('AccCode', MWC_TXTDOM),
					'type' => 'text',
					'default' => '123123',
				),
				'merchant_id' => array(
					'title' => __('Merchant', MWC_TXTDOM),
					'type' => 'text',
					'default' => 'DEMO0001',
				),
				'terminal_id' => array(
					'title' => __('Terminal', MWC_TXTDOM),
					'type' => 'text',
					'default' => 'DEMO0001',
				),
				'transtype' => array(
					'title' => __('Transaction type', MWC_TXTDOM),
					'type' => 'select',
					'default' => 'sale',
					'options' => array(
						'sale' => __('Sale', MWC_TXTDOM),
						'preauth' => __('PreAuthorization', MWC_TXTDOM)
						)
				),
				'sandbox' => array(
					'title' => __('Sandbox mode', MWC_TXTDOM),
					'type' => 'checkbox',
					'label' => __('Enable', MWC_TXTDOM),
					'default' => 'no'
				)
			);
		}
