<?php

/**
 * FoodPress metabox function
 * @package FoodPress/inc
 * @author Taufik Hidayat <taufik@fiqhidayat.com>
 */

function foodpress_customizer_library_options()
{

    $lic = new Foodpress_License();
    $data = $lic->data();

    if ($data['status'] !== 'ACTIVE') return;

    // Stores all the controls that will be added
    $options = array();
    // Stores all the sections to be added
    $sections = array();
    // Stores all the panels to be added
    $panels = array();
    // Adds the sections to the $options array
    $options['sections'] = $sections;

    $panels[] = array(
        'id' => 'general',
        'title' => __('General', 'foodpress'),
        'priority' => '30'
    );

    $sections[] = array(
        'id' => 'layout',
        'title' => __('Layout', 'foodpress'),
        'priority' => '10',
        'panel' => 'general'
    );
    $options['layout_width'] = array(
        'id' => 'layout_width',
        'label'   => __('Page Width (px)', 'foodpress'),
        'section' => 'layout',
        'type'    => 'range-value',
        'input_attrs' => array(
            'min'   => 768,
            'max'   => 1280,
            'step'  => 1,
        ),
        'default' => 1280,
    );

    $options['layout_line1'] = array(
        'id' => 'layout_line1',
        'section' => 'layout',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );

    $options['layout_list_product'] = array(
        'id' => 'layout_list_product',
        'label'   => __('List Product Style', 'foodpress'),
        'section' => 'layout',
        'type'    => 'radio',
        'choices' => array(
            'list' => 'List',
            'grid'  => 'Grid 2 column'
        ),
        'default' => 'list',
    );

    $sections[] = array(
        'id' => 'default',
        'title' => __('Font', 'foodpress'),
        'priority' => '10',
        'panel' => 'general'
    );
    $options['default_font'] = array(
        'id' => 'default_font',
        'label'   => __('Font', 'foodpress'),
        'section' => 'default',
        'type'    => 'select',
        'choices' => customizer_library_get_font_choices(),
        'default' => 0
    );
    $options['default_line1'] = array(
        'id' => 'default_line1',
        'section' => 'default',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );
    $options['default_color'] = array(
        'id' => 'default_color',
        'label'   => __('Color', 'foodpress'),
        'section' => 'default',
        'type'    => 'color',
        'default' => '#404040',
    );
    $options['default_line2'] = array(
        'id' => 'default_line2',
        'section' => 'default',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );
    $options['default_size'] = array(
        'id' => 'default_size',
        'label'   => __('Font Size (px)', 'foodpress'),
        'section' => 'default',
        'type'    => 'range-value',
        'input_attrs' => array(
            'min'   => 12,
            'max'   => 20,
            'step'  => 1,
        ),
        'default' => 16,
    );
    $options['default_line3'] = array(
        'id' => 'default_line3',
        'section' => 'default',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );
    $options['default_line_heightt'] = array(
        'id' => 'default_line_heightt',
        'label'   => __('Line Height (px)', 'foodpress'),
        'section' => 'default',
        'type'    => 'range-value',
        'input_attrs' => array(
            'min'   => 12,
            'max'   => 50,
            'step'  => 1,
        ),
        'default' => 24,
    );

    $sections[] = array(
        'id' => 'links',
        'title' => __('Link', 'foodpress'),
        'priority' => '10',
        'panel' => 'general'
    );
    $options['link_color'] = array(
        'id' => 'link_color',
        'label'   => __('Color', 'foodpress'),
        'section' => 'links',
        'type'    => 'color',
        'default' => '#43AB4A',
    );
    $options['link_line1'] = array(
        'id' => 'link_line1',
        'section' => 'links',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );
    $options['link_hover_color'] = array(
        'id' => 'link_hover_color',
        'label'   => __('Hover Color', 'foodpress'),
        'section' => 'links',
        'type'    => 'color',
        'default' => '#191970',
    );
    $options['link_line2'] = array(
        'id' => 'link_line2',
        'section' => 'links',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );
    $options['link_visited_color'] = array(
        'id' => 'link_visited_color',
        'label'   => __('Visited Color', 'foodpress'),
        'section' => 'links',
        'type'    => 'color',
        'default' => '#521989',
    );
    $options['link_line3'] = array(
        'id' => 'link_line3',
        'section' => 'links',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );
    $options['color_gradient'] = array(
        'id' => 'color_gradient',
        'label'   => __('Button Gradient', 'foodpress'),
        'section' => 'links',
        'type'    => 'color',
        'default' => '#50EB2C',
    );

    $sections[] = array(
        'id' => 'fb_pixel',
        'title' => __('Facebook Pixel', 'foodpress'),
        'priority' => '10',
        'panel' => 'general'
    );
    $options['fb_pixel_id_1'] = array(
        'id' => 'fb_pixel_id_1',
        'label'   => __('Facebook Pixel ID #1', 'foodpress'),
        'section' => 'fb_pixel',
        'type'    => 'text',
    );
    $options['fb_pixel_line1'] = array(
        'id' => 'fb_pixel_line1',
        'section' => 'fb_pixel',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );
    $options['fb_pixel_id_2'] = array(
        'id' => 'fb_pixel_id_2',
        'label'   => __('Facebook Pixel ID #2', 'foodpress'),
        'section' => 'fb_pixel',
        'type'    => 'text',
    );
    $options['fb_pixel_line2'] = array(
        'id' => 'fb_pixel_line2',
        'section' => 'fb_pixel',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );
    $options['fb_pixel_id_3'] = array(
        'id' => 'fb_pixel_id_3',
        'label'   => __('Facebook Pixel ID #3', 'foodpress'),
        'section' => 'fb_pixel',
        'type'    => 'text',
    );
    $options['fb_pixel_line3'] = array(
        'id' => 'fb_pixel_line3',
        'section' => 'fb_pixel',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );
    $options['fb_pixel_id_4'] = array(
        'id' => 'fb_pixel_id_4',
        'label'   => __('Facebook Pixel ID #4', 'foodpress'),
        'section' => 'fb_pixel',
        'type'    => 'text',
    );
    $options['fb_pixel_line4'] = array(
        'id' => 'fb_pixel_line4',
        'section' => 'fb_pixel',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );
    $options['fb_pixel_id_5'] = array(
        'id' => 'fb_pixel_id_5',
        'label'   => __('Facebook Pixel ID #5', 'foodpress'),
        'section' => 'fb_pixel',
        'type'    => 'text',
    );

    $options['fb_pixel_line5'] = array(
        'id' => 'fb_pixel_line5',
        'section' => 'fb_pixel',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );

    $options['fbpixel_button_atc_event'] = array(
        'id' => 'fbpixel_button_atc_event',
        'label'   => __('Enable Add To Cart Facebook Pixel Event', 'foodpress'),
        'section' => 'fb_pixel',
        'type'    => 'select',
        'default'    => 0,
        'choices' => array(
            0 => 'Disable',
            1 => 'Enable',
        )
    );

    $options['fb_pixel_line6'] = array(
        'id' => 'fb_pixel_line6',
        'section' => 'fb_pixel',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );

    $options['fbpixel_button_order_event'] = array(
        'id' => 'fbpixel_button_order_event',
        'label'   => __('On Order Button Facebook Pixel Event', 'foodpress'),
        'section' => 'fb_pixel',
        'type'    => 'select',
        'default'    => 0,
        'choices' => array(
            0 => 'Disable',
            'InitiateCheckout' => 'Initiate Checkout',
            'Purchase' => 'Purchase',
            'Lead' => 'Lead',
        )
    );

    $sections[] = array(
        'id' => 'custom_script',
        'title' => __('Custom Script', 'foodpress'),
        'priority' => '50',
        'panel' => 'general'
    );
    $options['custom_script_head'] = array(
        'id' => 'custom_script_head',
        'label'   => __('Head Custom Script', 'foodpress'),
        'section' => 'custom_script',
        'type'    => 'textarea',
        'default' => '',
        'description' => 'Insert script to Head',
        'sanitize_callback' => false,
    );
    $options['custom_script_line4'] = array(
        'id' => 'custom_script_line4',
        'section' => 'custom_script',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );
    $options['custom_script_footer'] = array(
        'id' => 'custom_script_footer',
        'label'   => __('Footer Custom Script', 'foodpress'),
        'section' => 'custom_script',
        'type'    => 'textarea',
        'default' => '',
        'description' => 'Insert script to Footer',
        'sanitize_callback' => false,
    );

    $sections[] = array(
        'id' => 'store',
        'title' => __('Store', 'foodpress'),
        'priority' => '10',
        'panel' => 'general'
    );
    $options['store_name'] = array(
        'id' => 'store_name',
        'label'   => __('Store Name', 'foodpress'),
        'section' => 'store',
        'type'    => 'text',
        'default' => 'FoodPress',
    );
    $options['store_line1'] = array(
        'id' => 'store_line1',
        'section' => 'store',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );
    $options['store_line2'] = array(
        'id' => 'store_line2',
        'section' => 'store',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );
    $options['store_admin_phone'] = array(
        'id' => 'store_admin_phone',
        'description' => 'sparate phone number by comma',
        'label'   => __('Admin WA Number', 'foodpress'),
        'section' => 'store',
        'type'    => 'textarea',
        'default' => '628123456789'
    );
    $options['store_line3'] = array(
        'id' => 'store_line3',
        'section' => 'store',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );
    $options['store_opened_message'] = array(
        'id' => 'store_opened_message',
        'label'   => __('Store Opened Message', 'foodpress'),
        'section' => 'store',
        'type'    => 'text',
        'default' => 'Halo Kak, saya mau order'
    );

    $sections[] = array(
        'id' => 'open_hours',
        'title' => __('Open Hours', 'foodpress'),
        'priority' => '10',
        'panel' => 'general'
    );

    $select_hours = array();
    $range = range(1, 24);

    foreach ($range as $key => $val) {
        if (intval($val) < 10) {
            $hour = '0' . $val . ':00';
            $hour_minute = '0' . $val . ':30';
        } else {
            $hour = $val . ':00';
            $hour_minute = $val . ':30';
        }

        $select_hours[$hour] = $hour;
        $select_hours[$hour_minute] = $hour_minute;
    }

    $options['closed_message'] = array(
        'id' => 'closed_message',
        'label'   => __('Closed Message', 'foodpress'),
        'description' => 'Shortcode [next-open-hours] will replaced with next open hour',
        'section' => 'open_hours',
        'type'    => 'textarea',
        'default' => 'Mohon maaf saat ini kami tutup',
    );

    $options['day_monday'] = array(
        'id' => 'day_monday',
        'label'   => __('Senin', 'foodpress'),
        'section' => 'open_hours',
        'type'    => 'checkbox',
        'default' => 1,
    );

    $options['day_monday_hour_start'] = array(
        'id' => 'day_monday_hour_start',
        'label'   => __('Hour Open', 'foodpress'),
        'section' => 'open_hours',
        'type'    => 'select',
        'choices' => $select_hours,
        'default' => '08:00',
    );

    $options['day_monday_hour_end'] = array(
        'id' => 'day_monday_hour_end',
        'label'   => __('Hour Close', 'foodpress'),
        'section' => 'open_hours',
        'type'    => 'select',
        'choices' => $select_hours,
        'default' => '17:00',
    );

    $options['open_hours_line1'] = array(
        'id' => 'open_hours_line1',
        'section' => 'open_hours',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );

    $options['day_tuesday'] = array(
        'id' => 'day_tuesday',
        'label'   => __('Selasa', 'foodpress'),
        'section' => 'open_hours',
        'type'    => 'checkbox',
        'default' => 1,
    );

    $options['day_tuesday_hour_start'] = array(
        'id' => 'day_tuesday_hour_start',
        'label'   => __('Hour Open', 'foodpress'),
        'section' => 'open_hours',
        'type'    => 'select',
        'choices' => $select_hours,
        'default' => '08:00',
    );

    $options['day_tuesday_hour_end'] = array(
        'id' => 'day_tuesday_hour_end',
        'label'   => __('Hour Close', 'foodpress'),
        'section' => 'open_hours',
        'type'    => 'select',
        'choices' => $select_hours,
        'default' => '17:00',
    );

    $options['open_hours_line2'] = array(
        'id' => 'open_hours_line2',
        'section' => 'open_hours',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );

    $options['day_wednesday'] = array(
        'id' => 'day_wednesday',
        'label'   => __('Rabu', 'foodpress'),
        'section' => 'open_hours',
        'type'    => 'checkbox',
        'default' => 1,
    );

    $options['day_wednesday_hour_start'] = array(
        'id' => 'day_wednesday_hour_start',
        'label'   => __('Hour Open', 'foodpress'),
        'section' => 'open_hours',
        'type'    => 'select',
        'choices' => $select_hours,
        'default' => '08:00',
    );

    $options['day_wednesday_hour_end'] = array(
        'id' => 'day_wednesday_hour_end',
        'label'   => __('Hour Close', 'foodpress'),
        'section' => 'open_hours',
        'type'    => 'select',
        'choices' => $select_hours,
        'default' => '17:00',
    );

    $options['open_hours_line3'] = array(
        'id' => 'open_hours_line3',
        'section' => 'open_hours',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );

    $options['day_thursday'] = array(
        'id' => 'day_thursday',
        'label'   => __('Kamis', 'foodpress'),
        'section' => 'open_hours',
        'type'    => 'checkbox',
        'default' => 1,
    );

    $options['day_thursday_hour_start'] = array(
        'id' => 'day_thursday_hour_start',
        'label'   => __('Hour Open', 'foodpress'),
        'section' => 'open_hours',
        'type'    => 'select',
        'choices' => $select_hours,
        'default' => '08:00',
    );

    $options['day_thursday_hour_end'] = array(
        'id' => 'day_thursday_hour_end',
        'label'   => __('Hour Close', 'foodpress'),
        'section' => 'open_hours',
        'type'    => 'select',
        'choices' => $select_hours,
        'default' => '17:00',
    );

    $options['open_hours_line4'] = array(
        'id' => 'open_hours_line4',
        'section' => 'open_hours',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );

    $options['day_friday'] = array(
        'id' => 'day_friday',
        'label'   => __('Jum at', 'foodpress'),
        'section' => 'open_hours',
        'type'    => 'checkbox',
        'default' => 1,
    );

    $options['day_friday_hour_start'] = array(
        'id' => 'day_friday_hour_start',
        'label'   => __('Hour Open', 'foodpress'),
        'section' => 'open_hours',
        'type'    => 'select',
        'choices' => $select_hours,
        'default' => '08:00',
    );

    $options['day_friday_hour_end'] = array(
        'id' => 'day_friday_hour_end',
        'label'   => __('Hour Close', 'foodpress'),
        'section' => 'open_hours',
        'type'    => 'select',
        'choices' => $select_hours,
        'default' => '17:00',
    );

    $options['open_hours_line5'] = array(
        'id' => 'open_hours_line5',
        'section' => 'open_hours',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );

    $options['day_saturday'] = array(
        'id' => 'day_saturday',
        'label'   => __('Sabtu', 'foodpress'),
        'section' => 'open_hours',
        'type'    => 'checkbox',
        'default' => 1,
    );

    $options['day_saturday_hour_start'] = array(
        'id' => 'day_saturday_hour_start',
        'label'   => __('Hour Open', 'foodpress'),
        'section' => 'open_hours',
        'type'    => 'select',
        'choices' => $select_hours,
        'default' => '08:00',
    );

    $options['day_saturday_hour_end'] = array(
        'id' => 'day_saturday_hour_end',
        'label'   => __('Hour Close', 'foodpress'),
        'section' => 'open_hours',
        'type'    => 'select',
        'choices' => $select_hours,
        'default' => '17:00',
    );

    $options['open_hours_line6'] = array(
        'id' => 'open_hours_line6',
        'section' => 'open_hours',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );


    $options['day_sunday'] = array(
        'id' => 'day_sunday',
        'label'   => __('Minggu', 'foodpress'),
        'section' => 'open_hours',
        'type'    => 'checkbox',
        'default' => 1,
    );

    $options['day_sunday_hour_start'] = array(
        'id' => 'day_sunday_hour_start',
        'label'   => __('Hour Open', 'foodpress'),
        'section' => 'open_hours',
        'type'    => 'select',
        'choices' => $select_hours,
        'default' => '08:00',
    );

    $options['day_sunday_hour_end'] = array(
        'id' => 'day_sunday_hour_end',
        'label'   => __('Hour Close', 'foodpress'),
        'section' => 'open_hours',
        'type'    => 'select',
        'choices' => $select_hours,
        'default' => '17:00',
    );

    $options['open_hours_line7'] = array(
        'id' => 'open_hours_line7',
        'section' => 'open_hours',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );


    $sections[] = array(
        'id' => 'cart',
        'title' => __('Cart', 'foodpress'),
        'priority' => '10',
        'panel' => 'general'
    );
    $options['cart_customer_form_title'] = array(
        'id' => 'cart_customer_form_title',
        'label'   => __('Customer Form Title', 'foodpress'),
        'section' => 'cart',
        'type'    => 'text',
        'default' => 'Data Pemesan',
    );
    $options['cart_line1'] = array(
        'id' => 'cart_line1',
        'section' => 'cart',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );

    $sections[] = array(
        'id' => 'help',
        'title' => __('Chat Help', 'foodpress'),
        'priority' => '10',
        'panel' => 'general'
    );
    $options['help_neable'] = array(
        'id' => 'help_enable',
        'label'   => __('Enable Help Chat', 'foodpress'),
        'section' => 'help',
        'type'    => 'checkbox',
        'default' => 1,
    );
    $options['help_line1'] = array(
        'id' => 'help_line1',
        'section' => 'help',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );


    $panels[] = array(
        'id' => 'header',
        'title' => __('Header', 'foodpress'),
        'priority' => '30'
    );

    $sections[] = array(
        'id' => 'header_style',
        'title' => __('Header Style', 'foodpress'),
        'priority' => '10',
        'panel' => 'header'
    );

    $options['header_bg'] = array(
        'id' => 'header_bg',
        'label'   => __('Background', 'foodpress'),
        'section' => 'header_style',
        'type'    => 'color',
        'default' => '#ffffff'
    );
    $options['header_style_line1'] = array(
        'id' => 'header_style_line1',
        'section' => 'header_style',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );
    $options['header_color'] = array(
        'id' => 'header_color',
        'label'   => __('Color', 'foodpress'),
        'section' => 'header_style',
        'type'    => 'color',
        'default' => '#404040'
    );

    //Logo
    $sections[] = array(
        'id' => 'logo',
        'title' => __('Branding Logo', 'foodpress'),
        'priority' => '10',
        'panel' => 'header'
    );
    $options['logo'] = array(
        'id' => 'logo',
        'label'   => __('Logo', 'foodpress'),
        'section' => 'logo',
        'type'    => 'image',
        'default' => '',
        'description' => 'Upload your site logo'
    );
    $options['logo_line1'] = array(
        'id' => 'logo_line1',
        'section' => 'logo',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );
    $options['logo_text_color'] = array(
        'id' => 'logo_text_color',
        'label'   => __('Logo Text Color', 'foodpress'),
        'section' => 'logo',
        'type'    => 'color',
        'default' => '#222222'
    );
    $options['logo_line2'] = array(
        'id' => 'logo_line2',
        'section' => 'logo',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );
    $options['logo_text_size'] = array(
        'id' => 'logo_text_size',
        'label'   => __('Font Size (px)', 'foodpress'),
        'section' => 'logo',
        'type'    => 'range-value',
        'input_attrs' => array(
            'min'   => 10,
            'max'   => 70,
            'step'  => 1,
        ),
        'default' => 36,
    );

    $sections[] = array(
        'id' => 'homepage',
        'title' => __('Homepage', 'foodpress'),
        'priority' => '80'
    );

    $terms = get_terms(array(
        'taxonomy' => 'product-category',
        'hide_empty' => false,
    ));

    $choiches = array(
        'all' => 'Semua Produk',
    );

    foreach ((array)$terms as $term) {
        $choiches[$term->term_id] = $term->name;
    }

    $options['homepage_category_list'] = array(
        'id'          => 'homepage_category_list',
        'label'       => __('Category List', 'foodpress'),
        'description' => 'Check categories bellow to show in homepage',
        'section'     => 'homepage',
        'type'        => 'shortable',
        'choices'     => $choiches,
        'default'     => 'all:1',
    );

    $sections[] = array(
        'id' => 'catslide',
        'title' => __('Category Slider', 'foodpress'),
        'priority' => '81'
    );

    $terms = get_terms(array(
        'taxonomy' => 'product-category',
        'hide_empty' => false,
    ));

    $choiches = array();

    foreach ((array)$terms as $term) {
        $choiches[$term->term_id] = $term->name;
    }

    $options['slide_category_list'] = array(
        'id'          => 'slide_category_list',
        'label'       => __('Category List', 'foodpress'),
        'description' => 'Check categories bellow to show in slide category',
        'section'     => 'catslide',
        'type'        => 'shortable',
        'choices'     => $choiches,
    );

    $options['catslide_line1'] = array(
        'id' => 'catslide_line1',
        'section' => 'catslide',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );

    $options['slide_category_on_home'] = array(
        'id'          => 'slide_category_on_home',
        'label'       => __('Category List On Homepage', 'foodpress'),
        'description' => 'Check this show slide category on Homepage',
        'section'     => 'catslide',
        'type'        => 'checkbox',
        'default' => 1,
    );

    $options['catslide_line2'] = array(
        'id' => 'catslide_line2',
        'section' => 'catslide',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );

    $options['slide_category_on_category'] = array(
        'id'          => 'slide_category_on_category',
        'label'       => __('Category List On Category Page', 'foodpress'),
        'description' => 'Check this show slide category on Category Page',
        'section'     => 'catslide',
        'type'        => 'checkbox',
        'default' => 1,
    );

    $options['catslide_line3'] = array(
        'id' => 'catslide_line3',
        'section' => 'catslide',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );

    $options['slide_category_on_search'] = array(
        'id'          => 'slide_category_on_search',
        'label'       => __('Category List On Search page', 'foodpress'),
        'description' => 'Check this show slide category on Search Page',
        'section'     => 'catslide',
        'type'        => 'checkbox',
        'default' => 1,
    );


    /**
     * footer panel
     * @var [type]
     */
    $panels[] = array(
        'id' => 'footer',
        'title' => __('Footer', 'foodpress'),
        'priority' => '999'
    );

    $sections[] = array(
        'id' => 'address',
        'title' => __('Footer Address', 'foodpress'),
        'priority' => '20',
        'panel' => 'footer'
    );
    $options['address_text'] = array(
        'id' => 'address_text',
        'label'   => __('Footer Adress Text', 'foodpress'),
        'section' => 'address',
        'type'    => 'textarea',
        'default' => 'Jl. HM Ardans No. 6, Satimpo
Bontang Selatang, Ka. Bontang, Kaltim
Telp: +62 811 545 114',
        'sanitize_callback' => false,
    );
    $options['address_text_color'] = array(
        'id' => 'address_text_color',
        'label'   => __('Footer Text Color', 'foodpress'),
        'section' => 'address',
        'type'    => 'color',
        'default' => '#000000',
    );
    $options['address_text_size'] = array(
        'id' => 'address_text_size',
        'label'   => __('Footer Address Text Size (px)', 'foodpress'),
        'section' => 'address',
        'type'    => 'range-value',
        'input_attrs' => array(
            'min'   => 9,
            'max'   => 50,
            'step'  => 1,
        ),
        'default' => 16,
    );

    $sections[] = array(
        'id' => 'copyright',
        'title' => __('Footer Copyright', 'foodpress'),
        'priority' => '20',
        'panel' => 'footer'
    );
    $options['copyright_text'] = array(
        'id' => 'copyright_text',
        'label'   => __('Copyright Text', 'foodpress'),
        'section' => 'copyright',
        'type'    => 'textarea',
        'default' => 'Copyright @ 2019 foodpress.com',
        'sanitize_callback' => false,
    );
    $options['copyright_text_color'] = array(
        'id' => 'copyright_text_color',
        'label'   => __('Footer Text Color', 'foodpress'),
        'section' => 'copyright',
        'type'    => 'color',
        'default' => '#444444',
    );
    $options['copyright_text_size'] = array(
        'id' => 'copyright_text_size',
        'label'   => __('Footer Text Size (px)', 'foodpress'),
        'section' => 'copyright',
        'type'    => 'range-value',
        'input_attrs' => array(
            'min'   => 9,
            'max'   => 50,
            'step'  => 1,
        ),
        'default' => 12,
    );

    $sections[] = array(
        'id' => 'social',
        'title' => __('Social', 'foodpress'),
        'priority' => '50',
        'panel' => 'footer'
    );
    $options['social_instagram_link'] = array(
        'id' => 'social_instagram_link',
        'label'   => __('Instagram Link', 'foodpress'),
        'section' => 'social',
        'type'    => 'text',
    );
    $options['social_line4'] = array(
        'id' => 'social_line1',
        'section' => 'social',
        'type'    => 'content',
        'content' => '<p>' . __('<hr/>', 'foodpress') . '</p>',
    );
    $options['social_facebook_link'] = array(
        'id' => 'social_facebook_link',
        'label'   => __('Facebook Link', 'foodpress'),
        'section' => 'social',
        'type'    => 'text',
    );

    // Adds the sections to the $options array
    $options['sections'] = $sections;
    // Adds the panels to the $options array
    $options['panels'] = $panels;
    $customizer_library = Customizer_Library::Instance();
    $customizer_library->add_options($options);
    // To delete custom mods use: customizer_library_remove_theme_mods();
}
add_action('init', 'foodpress_customizer_library_options');


add_action("customize_register", "foodpress_customize_register");
function foodpress_customize_register($wp_customize)
{
    //$wp_customize->remove_control("header_image");
    // $wp_customize->remove_panel("widgets");

    //$wp_customize->remove_section("colors");
    //$wp_customize->remove_section("background_image");
    $wp_customize->remove_section("static_front_page");
}