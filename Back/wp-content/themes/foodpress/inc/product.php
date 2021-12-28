<?php
/**
 * FoodPress product function
 * @package FoodPress/inc
 * @author Taufik Hidayat <taufik@fiqhidayat.com>
 */

add_action('init', 'foodpress_product');
/**
 * register product post type
 * @return [type] [description]
 */
function foodpress_product()
{
	$lic = new Foodpress_License();
	$data = $lic->data();

	if( $data['status'] !== 'ACTIVE' ) return;

	register_post_type('product', // Register Custom Post Type
		array(
		'labels' => array(
			'name'               => __('Product', 'foodpress'), // Rename these to suit
			'singular_name'      => __('Product', 'foodpress'),
			'add_new'            => __('Add New', 'foodpress'),
			'add_new_item'       => __('Add New Product', 'foodpress'),
			'edit'               => __('Edit', 'foodpress'),
			'edit_item'          => __('Edit Product', 'foodpress'),
			'new_item'           => __('New Product', 'foodpress'),
			'view'               => __('View Product', 'foodpress'),
			'view_item'          => __('View Product', 'foodpress'),
			'search_items'       => __('Search Product', 'foodpress'),
			'not_found'          => __('No Products found', 'foodpress'),
			'not_found_in_trash' => __('No Products found in Trash', 'foodpress')
		),
		'public' => true,
		'hierarchical' => false,
		'has_archive' => true,
		'supports' => array(
			'title',
			'editor',
			'thumbnail',
		),
		'can_export' => true,
		'menu_icon' => 'dashicons-products',
	));
}

add_action( 'init', 'foodpress_product_category_taxonomy', 0 );
/**
 * register product category taxonomy
 * @return [type] [description]
 */
function foodpress_product_category_taxonomy() {
	$labels = array(
		'name'              => _x( 'Categories', 'foodpress'),
		'singular_name'     => _x( 'Category', 'foodpress'),
		'search_items'      => __( 'Search Categories', 'foodpress' ),
		'all_items'         => __( 'All Categories', 'foodpress'),
		'parent_item'       => __( 'Parent Category', 'foodpress'),
		'parent_item_colon' => __( 'Parent Category:', 'foodpress'),
		'edit_item'         => __( 'Edit Category', 'foodpress'),
		'update_item'       => __( 'Update Category', 'foodpress'),
		'add_new_item'      => __( 'Add New Category', 'foodpress'),
		'new_item_name'     => __( 'New Category Name', 'foodpress'),
		'menu_name'         => __( 'Categories' , 'foodpress'),
	);

	register_taxonomy('product-category',array('product'), array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'product-category' ),
	));

}

add_action( 'admin_print_scripts-post-new.php', 'foodpress_product_admin_script', 11 );
add_action( 'admin_print_scripts-post.php', 'foodpress_product_admin_script', 11 );
/**
 * tweak admin product ui
 * @return [type] [description]
 */
function foodpress_product_admin_script() {
    global $post_type;

    if( 'product' !== $post_type ) return;
    ?>
	<script type="text/javascript">
	(function(w, d){
		setTimeout(function(){
			let fpStoreAdder = d.getElementById('product-store-adder');
			fpStoreAdder.style.display = 'none';
		}, 1000);
		setTimeout(function(){
			let fpStoreSelectAll = d.getElementById('product-store-all');
			let fpStoreSelect = fpStoreSelectAll.querySelectorAll('input');
			console.log(fpStoreSelect);
			for (var i=0; i<fpStoreSelect.length; i++){
				if( fpStoreSelect[i].type == 'checkbox' ){
					fpStoreSelect[i].type = 'radio';
					fpStoreSelect[i].required = true;
				}
			}
		},1000)
	}(window, document));
	</script>
	<?php
}


add_filter( 'manage_product_posts_columns', 'foodpress_product_column' );
/**
 * register product custom column
 * @param  [type] $columns [description]
 * @return [type]          [description]
 */
function foodpress_product_column($columns) {

	$columns = array_slice($columns, 0, 1, true) + array('product_thumb' => 'Image') + array_slice($columns, 1, count($columns) - 1, true);

	$columns = array_slice($columns, 0, 3, true) + array('product_price' => 'Price') + array_slice($columns, 1, count($columns) - 1, true);

    return $columns;
}

add_action( 'manage_product_posts_custom_column' , 'foodpress_product_content_column', 10, 2 );
/**
 * product custom column value
 * @param  [type] $column  [description]
 * @param  [type] $post_id [description]
 * @return [type]          [description]
 */
function foodpress_product_content_column( $column, $post_id ) {

    switch ( $column ) :

        case 'product_thumb' :
		    $thumb = get_the_post_thumbnail_url($post_id);
			echo '<img width="100" height="100" src="'.$thumb.'" alt="">';
            break;

        case 'product_price' :
            $price = intval(get_post_meta($post_id, 'product_price', true));
			$promo = get_post_meta($post_id, 'product_promo', true);
			$promo_price = intval(get_post_meta($post_id, 'product_promo_price', true));
			if( $promo == 'on' ){
				echo '<del>'.number_format($price,0,',','.').'</del><br/>';
				echo 'Promo '.number_format($promo_price,0,',','.');
			}else{
				echo number_format($price,0,',','.').'<br/>';
			}
            break;
    endswitch;
}

add_action( 'admin_footer', 'foodpress_product_admin_footer' );
/**
 * admin product footer script
 * @return [type] [description]
 */
function foodpress_product_admin_footer(){
    $current_screen = get_current_screen();

    if( $current_screen->parent_file == 'edit.php?post_type=product' && empty($current_screen->taxonomy) && !isset($_GET['page']) && !isset($_GET['action'])):
        ?>
        <script>
        jQuery(jQuery(".wrap h1")[0]).append("<a  id='export-xl' class='add-new-h2'>Export To Excel</a>");
        jQuery('#export-xl').on('click', function(){
            jQuery(this).html('Proses..');

            jQuery.ajax({
                type: "POST",
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                dataType: "json",
                data: {
                    action: 'export_products',
                    nonce: '<?php echo wp_create_nonce( 'foodpress' ); ?>',
                    status: '<?php echo isset($_GET['post_status']) ? $_GET['post_status'] : 'all'; ?>',
                },
                success: function(data){
                    jQuery('#export-xl').html('Export To Excel');
                    window.open(data.url, '_blank');
                }
            });
        })
        </script>
        <?php
    endif;
	if( isset($current_screen->taxonomy) && $current_screen->taxonomy == 'product-category' ):
		?>
		<script>
		//jQuery('.row-actions .view').hide();
		</script>
		<?php
	endif;
}

add_action( 'wp_ajax_export_products', 'foodpress_ajax_export_products');
/**
 * ajax export product
 * @return [type] [description]
 */
function foodpress_ajax_export_products(){
    $nonce = isset($_REQUEST['nonce']) ? $_REQUEST['nonce'] : '';
    if( !wp_verify_nonce($nonce, 'foodpress') ) exit;

	$args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'fields' => 'ids',
    );

    if( $_POST['status'] !== 'all' ):
        $args['post_status'] = 'new_product'; //sanitize_text_field($_POST['status']);
    endif;

    $posts = new WP_Query($args);

    $list = array();

	$header = array(
        'Product',
		'Image Url',
        'Description',
        'Price',
        'Promo Price',
		'Weight',
		'Category',
    );

    foreach ((array)$posts->posts as $id):

		$thumbnail_id = get_post_thumbnail_id( $id );

		$terms = get_the_terms($id, 'product-category');

		$categories = array();
		foreach( (array)$terms as $t ){
			$categories[] = $t->name;
		}

        $list[] = array(
            get_the_title($id),
            wp_get_attachment_url($thumbnail_id, 'full'),
            strip_tags(get_post_field('post_content', $id)),
            get_post_meta($id, 'product_price', true),
            get_post_meta($id, 'product_promo_price', true),
			get_post_meta($id, 'product_weight', true),
			implode(',', $categories),
        );
    endforeach;

	$upload_dir   = wp_upload_dir();
	$filename = date('y-m-d-H-i-s').'-product.xlsx';

	$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();

	$i = 'A';
	foreach( $header as $key=>$val ){
		$sheet->setCellValue($i.'1', $val);
		$i++;
	}

	$i = 2;
	foreach( (array)$list as $key=>$val ):
		$ii = 'A';
		foreach( (array) $val as $k=>$v){
			$sheet->setCellValue($ii.$i, $v);
			$ii++;
		}
		$i++;
	endforeach;

	$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
	$writer->save( $upload_dir['basedir'].'/'.$filename);

	$spreadsheet->disconnectWorksheets();
	unset($spreadsheet);

	$response['url'] = site_url() . '/wp-content/uploads/' . $filename;
	echo json_encode($response);
    exit;
}

add_action( 'admin_menu',  'foodpress_product_import_admin_menu' );
/**
 * add menu product import
 * @return [type] [description]
 */
function foodpress_product_import_admin_menu(){

    add_submenu_page(
        'edit.php?post_type=product',
        __('Import Product', 'foodpress'),
        __('Import Product', 'foodpress'),
        'manage_options',
        'product-import',
        'foodpress_product_import_page'
    );
}


function foodpress_product_import_page(){

    ob_start();
    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php echo esc_html( __( 'Product Import', 'foodpress' ) ); ?></h1>
        <div style="">
            <p>
                <?php
                $demo_csv_url = get_template_directory_uri() . '/data/demo-import-data.xlsx';
                ?>
                * Download example excel product format <a href="<?php echo $demo_csv_url; ?>" target="_blank">click here</a><br/>
            </p>
        </div>
        <form name="post" method="post" id="quick-press" class="initial-form hide-if-no-js ng-dirty" enctype="multipart/form-data">
            <div class="input-text-wrap" id="title-wrap">
    			<input type="file" name="excel" required>
    		</div>
    		<p class="submit">
    			<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('foodpress_nonce'); ?>">
                <button type="submit" class="button button-primary">Import Sekarang</button>
                <br class="clear">
    		</p>
        </form>
        <?php
        foodpress_product_import_action();
        ?>
    </div>
    <?php
    $html = ob_get_contents();
    ob_end_clean();
    echo $html;
}

function foodpress_product_import_action(){

    $post = isset($_POST) ? $_POST : array();

    if( isset($post['_wpnonce']) && wp_verify_nonce($post['_wpnonce'], 'foodpress_nonce') ){
        //set_time_limit(0);

        $file = $_FILES['excel'];
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$reader->setReadDataOnly(true);
		$spreadsheet = $reader->load($file['tmp_name']);
		$data = $spreadsheet->getActiveSheet()->toArray();
        //__debug($data);

		if( $data && is_array($data) ){
			$i = 0;
			foreach($data as $key=>$val ){
				if( $key == 0 ) continue;

				$arg = array(
					'post_title' => sanitize_text_field($val[0]),
					'post_name' => sanitize_title($val[0]),
					'post_type' => 'product',
					'post_status' => 'publish',
					'post_content' => sanitize_text_field($val[2]),
				);

				$pid = wp_insert_post($arg);

				if( is_wp_error($pid) ){
					echo '<br/>Error Import<br/>';
			    }else{
					foodpress_upload_product_thumbnail($val[1], $pid);
			        update_post_meta($pid, 'product_price', intval($val[3]));
					if( isset($val[4]) && intval($val[4]) > 0 ){
						update_post_meta($pid, 'product_promo', 'on');
						update_post_meta($pid, 'product_promo_price', intval($val[4]));
					}

					if( isset($val[5]) ){
						update_post_meta($pid, 'product_weight', intval($val[5]));
					}

					if( isset($val[6]) ){
						$cats = explode(',',$val[6]);

						$term_ids = array();
						foreach( (array)$cats as $key=>$val ){
				            $term = term_exists($val, 'product-category');
				            if( !$term ){
				                $term = wp_insert_term($val, 'product-category');
				            }
				            $term_ids[] = $term['term_id'];
				        }


				        if( $term_ids ){
				            wp_set_post_terms($pid, $term_ids, 'product-category');
				        }
					}

					$l = get_edit_post_link($pid);
                    echo '<a href="'.$l.'" taget="_blankk">Success imported product: '.$arg['post_title'].'</a><br/>';
				}
			}
		}
    }
}

function foodpress_upload_product_thumbnail($url, $post_id) {
    $image = '';

    if($url != '') {

        $file = array();
        $file['name'] = $url;
        $file['tmp_name'] = download_url($url);

        if (is_wp_error($file['tmp_name'])) {
            @unlink($file['tmp_name']);
            //__debug( $file['tmp_name']->get_error_messages() );
        } else {
            $attachmentId = media_handle_sideload($file, $post_id);
			
            if ( is_wp_error($attachmentId) ) {
                @unlink($file['tmp_name']);
                //var_dump( $attachmentId->get_error_messages( ) );
            } else {
                $image = wp_get_attachment_url( $attachmentId );
                set_post_thumbnail( $post_id, $attachmentId );
                @unlink($file['tmp_name']);
            }
        }
    }
    return $image;
}
