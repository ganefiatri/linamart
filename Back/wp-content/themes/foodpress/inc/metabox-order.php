<?php

/**
 * FoodPress metabox function
 * @package FoodPress/inc
 * @author Taufik Hidayat <taufik@fiqhidayat.com>
 */


add_action('cmb2_admin_init', 'foodpress_order_customer_metabox');
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function foodpress_order_customer_metabox()
{
	$post_id = null;
	if (isset($_GET['post'])) {
		$post_id = $_GET['post'];
	} else if (isset($_POST['post_ID'])) {
		$post_id = $_POST['post_ID'];
	}

	$version = get_post_meta($post_id, 'version', true);

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$cmb = new_cmb2_box(array(
		'id'            => 'foodpress_order_customer_metabox',
		'title'         => esc_html__('Customer', 'foodpress'),
		'object_types'  => array('order'),
		'context'    => 'side',
	));

	$cmb->add_field(array(
		'name'       => esc_html__('Name', 'foodpress'),
		'desc'       => '',
		'id'         => 'customer_name',
		'type'       => 'text',
	));

	$cmb->add_field(array(
		'name'       => esc_html__('Phone', 'foodpress'),
		'desc'       => '',
		'id'         => 'customer_phone',
		'type'       => 'text',
	));

	$cmb->add_field(array(
		'name'       => esc_html__('Address', 'foodpress'),
		'desc'       => '',
		'id'         => 'customer_address',
		'type'       => 'textarea_small',
	));

	if ($version == '2') {
		$cmb->add_field(array(
			'name'       => esc_html__('Subdistrict', 'foodpress'),
			'desc'       => '',
			'id'         => 'customer_region_name',
			'type'       => 'text',
		));


		$cmb->add_field(array(
			'name'       => esc_html__('Shipping Location', 'foodpress'),
			'desc'       => '',
			'id'         => 'customer_shipping_location',
			'type'       => 'text',
		));
	} else {
		$cmb->add_field(array(
			'name'       => esc_html__('Subdistrict', 'foodpress'),
			'desc'       => '',
			'id'         => 'customer_district',
			'type'       => 'text',
		));
	}

	$phone = get_post_meta($post_id, 'customer_phone', true);
	$phone = preg_replace('/[^0-9]/', '', $phone);
	$phone = preg_replace('/^620/', '62', $phone);
	$phone = preg_replace('/^0/', '62', $phone);

	$cmb->add_field(array(
		'name'       => '<button type="button" style="width: 100%;" class="button button-primary" onclick="customerFollowUp(\'' . $phone . '\');"> Follow Up</button>',
		'desc'       => '',
		'id'         => 'submit',
		'type'       => 'title',
	));
}

add_action('admin_menu', function () {
	remove_meta_box('submitdiv', 'order', 'side');
});

add_action('cmb2_admin_init', 'foodpress_order_status_metabox');
function foodpress_order_status_metabox()
{
	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$cmb = new_cmb2_box(array(
		'id'            => 'foodpress_order_status_metabox',
		'title'         => esc_html__('Status', 'foodpress'),
		'object_types'  => array('order'),
		'context'    => 'side',
	));

	$cmb->add_field(array(
		'name'       => esc_html__('Status', 'foodpress'),
		'desc'       => '',
		'id'         => 'status',
		'type'       => 'select',
		'options' => array(
			'new_order'   => 'New Order',
			'on_hold'     => 'On Hold',
			'on_shipping' => 'On Shipping',
			'completed'   => 'Completed',
			'cancelled'   => 'Cancelled',
			'refunded'    => 'Refunded',
		),
	));

	$cmb->add_field(array(
		'name'       => '<button type="submit" style="width: 100%;" class="button button-primary button-hero">Simpan Order</button>',
		'desc'       => '',
		'id'         => 'submit',
		'type'       => 'title',
	));
}

add_action('cmb2_save_field', 'foodpress_save_order_status', 10, 4);
function foodpress_save_order_status($field_id, $updated, $action, $ini)
{
	global $wpdb;

	if ($field_id == 'status') :

		$post_id = intval($ini->data_to_save['post_ID']);

		$wpdb->update($wpdb->posts, array('post_status' => sanitize_text_field($ini->value)), array('ID' => $post_id));

		clean_post_cache($post_id);
	endif;
}


add_action('cmb2_admin_init', 'foodpress_order_detail_metabox');
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function foodpress_order_detail_metabox()
{
	$post_id = null;
	if (isset($_GET['post'])) {
		$post_id = $_GET['post'];
	} else if (isset($_POST['post_ID'])) {
		$post_id = $_POST['post_ID'];
	}

	$items = get_post_meta($post_id, 'order_items', true);
	$version = get_post_meta($post_id, 'version', true);

	ob_start();
?>
<table>
    <thead>
        <tr>
            <th>Produk</th>
            <th>Qty</th>
            <th style="text-align: right">Harga (@)</th>
            <th style="text-align: right">Sub Total</th>
        </tr>
        </tehad>
    <tbody>
        <?php foreach ((array)$items as $i) : ?>
        <tr>
            <td><?php echo $i['item_name']; ?></td>
            <td><?php echo $i['qty']; ?></td>
            <td style="text-align: right"><?php echo number_format($i['item_price'], 0, '.', '.'); ?></td>
            <td style="text-align: right">
                <?php
						$subtotal = intval($i['item_price']) * intval($i['qty']);
						echo number_format($subtotal, 0, '.', '.');
						?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php
	$d = ob_get_contents();
	ob_end_clean();

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$cmb = new_cmb2_box(array(
		'id'            => 'foodpress_order_detail_metabox',
		'title'         => esc_html__('Detail', 'foodpress'),
		'object_types'  => array('order'),
	));

	function foodpress_metabox_number_format($value, $field_args, $field)
	{
		$value = intval($value);
		return number_format($value, 0, '.', '.');
	}

	$cmb->add_field(array(
		'name'       => esc_html__('Items', 'foodpress'),
		'desc'       => '',
		'id'         => 'test',
		'before'     => '<p>' . $d . '</p>',
		'type'       => 'text',
		'save_field' => false,
		'default' => 'manual',
		'attributes' => array(
			'readonly' => 'readonly',
			'disabled' => 'disabled',
			'style' => 'display:none'
		)
	));

	if ($version == '2') {
		$cmb->add_field(array(
			'name'       => esc_html__('Catatan untuk penjual', 'foodpress'),
			'desc'       => '',
			'id'         => 'note',
			'type'       => 'textarea',
			'save_field' => false,
			'attributes' => array(
				'readonly' => 'readonly',
			),
		));

		$cmb->add_field(array(
			'name'       => esc_html__('Total berat (gram)', 'foodpress'),
			'desc'       => '',
			'id'         => 'weight',
			'type'       => 'text',
			'save_field' => false,
			'attributes' => array(
				'readonly' => 'readonly',
			)
		));

		$cmb->add_field(array(
			'name'       => esc_html__('Sub Total', 'foodpress'),
			'desc'       => '',
			'id'         => 'subtotal',
			'type'       => 'text',
			'save_field' => false,
			'attributes' => array(
				'readonly' => 'readonly',
			),
			'escape_cb' => 'foodpress_metabox_number_format',
		));

		$cmb->add_field(array(
			'name'       => esc_html__('Shipping Cost', 'foodpress'),
			'desc'       => '',
			'id'         => 'shipping_cost',
			'type'       => 'text',
			'save_field' => false,
			'attributes' => array(
				'readonly' => 'readonly',
			),
			'escape_cb' => 'foodpress_metabox_number_format',
		));

		$cmb->add_field(array(
			'name'       => esc_html__('Shipping Service', 'foodpress'),
			'desc'       => '',
			'id'         => 'shipping_name',
			'type'       => 'text',
			'save_field' => false,
			'attributes' => array(
				'readonly' => 'readonly',
			),
		));

		$cmb->add_field(array(
			'name'       => esc_html__('Delivery Hour', 'foodpress'),
			'desc'       => '',
			'id'         => 'delivery_hour',
			'type'       => 'text',
			'save_field' => false,
			'attributes' => array(
				'readonly' => 'readonly',
			),
		));

		$cmb->add_field(array(
			'name'       => esc_html__('Discount Amount', 'foodpress'),
			'desc'       => '',
			'id'         => 'discount_amount',
			'type'       => 'text',
			'save_field' => false,
			'attributes' => array(
				'readonly' => 'readonly',
			),
			'escape_cb' => 'foodpress_metabox_number_format',
		));

		$cmb->add_field(array(
			'name'       => esc_html__('Coupon Code', 'foodpress'),
			'desc'       => '',
			'id'         => 'discount_code',
			'type'       => 'text',
			'save_field' => false,
			'attributes' => array(
				'readonly' => 'readonly',
			),
		));

		$cmb->add_field(array(
			'name'       => esc_html__('Total', 'foodpress'),
			'desc'       => '',
			'id'         => 'total',
			'type'       => 'text',
			'save_field' => false,
			'attributes' => array(
				'readonly' => 'readonly',
			),
			'escape_cb' => 'foodpress_metabox_number_format',
		));
	} else {
		$cmb->add_field(array(
			'name'       => esc_html__('Catatan untuk penjual', 'foodpress'),
			'desc'       => '',
			'id'         => 'customer_note',
			'type'       => 'textarea',
			'save_field' => false,
			'attributes' => array(
				'readonly' => 'readonly',
			),
		));

		$cmb->add_field(array(
			'name'       => esc_html__('Sub Total', 'foodpress'),
			'desc'       => '',
			'id'         => 'order_subtotal',
			'type'       => 'text',
			'save_field' => false,
			'attributes' => array(
				'readonly' => 'readonly',
			),
			'escape_cb' => 'foodpress_metabox_number_format',
		));

		$cmb->add_field(array(
			'name'       => esc_html__('Ongkir', 'foodpress'),
			'desc'       => '',
			'id'         => 'order_ongkir',
			'type'       => 'text',
			'save_field' => false,
			'attributes' => array(
				'readonly' => 'readonly',
			),
			'escape_cb' => 'foodpress_metabox_number_format',
		));

		$cmb->add_field(array(
			'name'       => esc_html__('Ongkir Service', 'foodpress'),
			'desc'       => '',
			'id'         => 'order_ongkir_name',
			'type'       => 'text',
			'save_field' => false,
			'attributes' => array(
				'readonly' => 'readonly',
			),
			'default' => 'Flat Ongkir',
		));

		$cmb->add_field(array(
			'name'       => esc_html__('Total', 'foodpress'),
			'desc'       => '',
			'id'         => 'order_total',
			'type'       => 'text',
			'save_field' => false,
			'attributes' => array(
				'readonly' => 'readonly',
			),
			'escape_cb' => 'foodpress_metabox_number_format',
		));
	}
}