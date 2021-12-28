let FoodPress, FoodPress_Cart_Items, FoodPress_Cart_Customer, FoodPress_Cart_Detail, FoodPress_Cart;

FoodPress = {

    init: function() {
        let self = this;

        self.scrollCategory();
        self.loadMore();
        self.buttonAdd();
        self.buttonQty();
        self.loadBasket();
        self.openSupportWa();
        self.setProductImage();
    },
    setProductImage: function() {
        let $images = document.querySelectorAll('.productbox-image');
        for (var i = 0, length = $images.length; i < length; i++) {
            $images[i].style.height = $images[i].offsetWidth + 'px';
        }
    },
    scrollCategory: function() {
        let $box = document.getElementById('category-slide');
        if (typeof($box) != 'undefined' && $box != null) {
            let $left = $box.querySelector('.arrow-left'),
                $right = $box.querySelector('.arrow-right'),
                $catbox = $box.querySelector('.slide-category'),
                $scroll_width = $catbox.scrollWidth,
                $scroll_left = $catbox.scrollLeft;

            if ($scroll_width <= $catbox.clientWidth) {
                $right.style.display = 'none';
            }

            $scroll_width = parseInt($scroll_width) - parseInt($catbox.clientWidth);

            $right.addEventListener('click', function() {
                $catbox.scrollTo({
                    top: 0,
                    left: $scroll_left += 210,
                    behavior: 'smooth',
                });
                if ($scroll_left >= $scroll_width) {
                    $scroll_left = $scroll_width;
                    this.style.display = 'none';
                } else {
                    this.style.display = 'block';
                }
                $left.style.display = 'block';

            });

            $left.addEventListener('click', function() {
                $catbox.scrollTo({
                    top: 0,
                    left: $scroll_left -= 200,
                    behavior: 'smooth',
                });
                if ($scroll_left <= 0) {
                    $scroll_left = 0;
                    this.style.display = 'none';
                } else {
                    this.style.display = 'block';
                }
                $right.style.display = 'block';
            });


        }
    },
    loadMore: function() {
        let $buttons = document.querySelectorAll('.loadmore');
        for (var i = 0, length = $buttons.length; i < length; i++) {
            $buttons[i].onclick = function(element) {
                this.innerHTML = 'Loading ...';
                let $wrapper = this.parentNode,
                    $button = this,
                    ajax = new XMLHttpRequest(),
                    params = new URLSearchParams({
                        action: 'loadmore-products',
                        paged: this.getAttribute('data-paged'),
                        search: this.getAttribute('data-search'),
                        store_id: this.getAttribute('data-store-id'),
                        category_id: this.getAttribute('data-category-id'),
                        nonce: foodpress.nonce
                    });

                ajax.open('GET', foodpress.ajax_url + '?' + params.toString(), true);
                ajax.onload = function() {
                    if (ajax.status === 200) {
                        let $response = JSON.parse(ajax.responseText),
                            html = new DOMParser().parseFromString($response.products, 'text/html'),
                            products = html.querySelectorAll('.productbox');
                        products.forEach(function(product, key) {
                            $wrapper.querySelector('.products').appendChild(product);
                        });
                        new LazyLoad({ elements_selector: ".lazy" });
                        FoodPress.setProductImage();
                        if ($response.next) {
                            $button.innerHTML = 'Tampilkan lebih banyak';
                            $button.setAttribute('data-paged', $response.next);
                        } else {
                            $wrapper.removeChild($button);
                        }
                        FoodPress.init();
                    }
                }
                ajax.send();
            }
        }
    },
    buttonAdd: function() {
        let $buttons = document.querySelectorAll('.button-add');
        for (var i = 0, length = $buttons.length; i < length; i++) {
            $buttons[i].onclick = function(element) {
                let $atc = this.parentNode,
                    $qty = $atc.querySelector('.qty'),
                    $inputs = $atc.querySelectorAll('input'),
                    $item = {};

                $qty.querySelector('input').value = 1;
                this.style.display = 'none';
                $qty.style.display = 'block';

                for (var i = 0, length = $inputs.length; i < length; i++) {
                    let $name = $inputs[i].name;
                    $item[$name] = $inputs[i].value;
                }

                FoodPress_Cart_Items.insert($item);
                FoodPress.loadBasket();
            }
        }
    },
    buttonQty: function() {
        let $buttons = document.querySelectorAll('.button-qty');
        for (var i = 0, length = $buttons.length; i < length; i++) {
            $buttons[i].onclick = function() {
                let $qty = this.parentNode,
                    $add = $qty.parentNode.parentNode.querySelector('.button-add'),
                    $item_id = $qty.parentNode.querySelector('input[name=item_id]').value,
                    $qty_input = $qty.querySelector('input');

                if (this.getAttribute('data-qty-action') == 'plus') {
                    $qty_input.value++;
                } else {
                    if ($qty_input.value > 0) {
                        $qty_input.value--;
                    }
                }

                if ($qty_input.value > 0) {
                    FoodPress_Cart_Items.edit($item_id, 'qty', $qty_input.value);
                } else {
                    $qty.style.display = 'none';
                    $add.style.display = 'block';
                    FoodPress_Cart_Items.delete($item_id);
                }
                FoodPress.loadBasket();
            }
        }
    },
    loadBasket: function() {
        if (foodpress.store_open == 1) {
            let $footer = document.querySelector('footer'),
                $basketbox = document.getElementById('basketbox'),
                $total = 0,
                $cart_items = FoodPress_Cart_Items.get();

            FoodPress_Cart_Items.countSubTotal();
            FoodPress_Cart_Items.countWeight();
            FoodPress_Cart_Items.countTotal();

            $total = FoodPress_Cart_Detail.get('subtotal');

            if ($cart_items.length > 0) {
                let $atcs = document.querySelectorAll('.atc');
                for (var i = 0, length = $atcs.length; i < length; i++) {
                    let $add = $atcs[i].querySelector('.add');
                    if (typeof($add) != 'undefined' && $add != null) {
                        $add.style.display = 'block';
                        $atcs[i].querySelector('.qty').style.display = 'none';
                    }
                }
                $cart_items.forEach(function(item, i, object) {
                    let $atcs = document.querySelectorAll('.atc-item-' + item.item_id);
                    for (var i = 0, length = $atcs.length; i < length; i++) {
                        let $qty = $atcs[i].querySelector('.qty');
                        $qty.querySelector('input').value = item.qty;
                        let $add = $atcs[i].querySelector('.add');
                        if (typeof($add) != 'undefined' && $add != null) {
                            $add.style.display = 'none';
                            $qty.style.display = 'block';
                        }
                    }
                });

                $basketbox.querySelector('.basket-cart-item-count').innerHTML = $cart_items.length;
                $basketbox.querySelector('.basket-items-total').innerHTML = foodpress.currency.format($total);

                $footer.classList.add('product-footer');
                $basketbox.style.display = 'block';
            } else {
                $footer.classList.remove('product-footer');
                if (typeof($basketbox) != 'undefined' && $basketbox != null) {
                    $basketbox.style.display = 'none';
                    let $atcs = document.querySelectorAll('.atc');
                    for (var i = 0, length = $atcs.length; i < length; i++) {
                        let $add = $atcs[i].querySelector('.button-add');
                        if (typeof($add) != 'undefined' && $add != null) {
                            $add.style.display = 'block';
                        }
                        $atcs[i].querySelector('.qty').querySelector('input').value = 0;
                        $atcs[i].querySelector('.qty').style.display = 'none';
                    }
                }
            }

            $basketbox.onclick = function() {
                FoodPress_Cart.open();
            }
        }
    },
    openSupportWa: function() {
        let $toggle = document.getElementById('support-wa-toggle');

        if (typeof($toggle) != 'undefined' && $toggle != null) {
            $toggle.onclick = function() {
                let $wabox = document.getElementById('support-wa');
                $wabox.classList.add('open');

                FoodPress.supportWa();
                FoodPress.closeSupportWa();
            }
        }

    },
    closeSupportWa: function() {
        let $toggle = document.getElementById('support-wa-toggle');
        if (typeof($toggle) != 'undefined' && $toggle != null) {
            $toggle.onclick = function() {
                let $wabox = document.getElementById('support-wa');
                $wabox.classList.remove('open');

                FoodPress.openSupportWa();
            }
        }

    },
    supportWa: function() {
        let $form = document.getElementById('support-wa').querySelector('form');
        $form.onsubmit = function(e) {
            e.preventDefault();
            let $data = $form.elements,
                $inputs = {},
                $wa = '',
                $url = '';

            for (let i = 0; i < $data.length; i++) {
                let $key = $data[i].name;
                if ($key !== '') {
                    $inputs[$key] = $data[i].value;
                }
            }

            let $message = $inputs.message.replace(/\n/g, '%0A');

            $wa = '?phone=' + foodpress.store_admin_phone + '&text=%0A' + 'Saya *' + $inputs.name + '*%0A%0A ' + '💬 ' + $message + '%0A%0A ' + 'Via ' + location.href;

            if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                $url = 'whatsapp://send' + $wa;
                //$url = 'https://api.whatsapp.com/send' + $wa;
                location.href = $url;

                return false;
            } else {
                $url = 'https://web.whatsapp.com/send' + $wa;

                let w = 960,
                    h = 540,
                    left = Number((screen.width / 2) - (w / 2)),
                    top = Number((screen.height / 2) - (h / 2)),
                    popupWindow = window.open($url, '', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=1, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

                popupWindow.focus();

                return false;
            }
        }
    }
}

FoodPress_Cart_Items = {
    storage: 'foodpress_cart_' + foodpress.store_id,
    get: function() {
        let $return = [];
        if (localStorage.getItem(this.storage)) {
            $return = JSON.parse(localStorage.getItem(this.storage));
        }

        return $return;
    },
    empty: function() {
        localStorage.removeItem(this.storage);
    },
    insert: function($item) {
        let $items = this.get();

        $valid = true;

        if ('item_id' in $item === false) {
            $valid = false;
        }
        if ('item_image' in $item === false) {
            $valid = false;
        }
        if ('item_name' in $item === false) {
            $valid = false;
        }
        if ('item_price' in $item === false) {
            $valid = false;
        }
        if ('item_price_slik' in $item === false) {
            $valid = false;
        }
        if ('item_weight' in $item === false) {
            $valid = false;
        }
        if ('note' in $item === false) {
            $valid = false;
        }
        if ('qty' in $item === false) {
            $valid = false;
        }
        if ('store_id' in $item === false) {
            $valid = false;
        }

        if ($valid === true) {
            $items.push($item);
            localStorage.setItem(this.storage, JSON.stringify($items));
        }
    },
    edit: function($item_id, $key, $value) {
        let $items = this.get();
        $items.forEach(function(item, i, object) {

            if ($item_id == item.item_id) {

                if ('qty' == $key) {
                    item.qty = $value;
                }
            }

        });

        localStorage.setItem(this.storage, JSON.stringify($items));
    },
    delete: function($item_id) {
        let $items = this.get();
        $items.forEach(function(item, i, object) {

            if ($item_id == item.item_id) {

                object.splice(i, 1);
            }

        });

        localStorage.setItem(this.storage, JSON.stringify($items));
    },
    countSubTotal: function() {
        let $subtotal = 0,
            $items = [],
            $subsubtotal = 0;

        $items = this.get();
        $items.forEach(function(item, i, object) {
            $subsubtotal = parseInt(item.item_price) * parseInt(item.qty);
            $subtotal = $subtotal + $subsubtotal;
        });

        FoodPress_Cart_Detail.set('subtotal', $subtotal);
        FoodPress_Cart_Detail.set('total', $subtotal);


    },
    countWeight: function() {
        let $weight = 0,
            $items = [];

        $items = this.get();
        $items.forEach(function(item, i, object) {
            let $subweight = parseInt(item.item_weight) * parseInt(item.qty);
            $weight = $weight + $subweight;
        });

        FoodPress_Cart_Detail.set('weight', $weight);
    },
    countTotal: function() {
        let $total = FoodPress_Cart_Detail.get('subtotal'),
            $discount = FoodPress_Cart_Detail.get('discount_amount'),
            $shipping = FoodPress_Cart_Detail.get('shipping_cost');

        if ($shipping) {
            $total = parseInt($total) + parseInt($shipping);
        }
        if ($discount) {
            $total = parseInt($total) - parseInt($discount);
        }
        FoodPress_Cart_Detail.set('total', $total);
    }
}

FoodPress_Cart_Detail = {
    storage: 'foodpress_cart_detail',
    empty: function() {
        localStorage.removeItem(this.storage);
    },
    get: function($key) {
        let $detail = {},
            $return = false;

        if (localStorage.getItem(this.storage)) {
            $detail = JSON.parse(localStorage.getItem(this.storage));
        }

        if ($key == 'all') {
            $return = $detail;
        }

        if ($key == 'subtotal' && 'subtotal' in $detail) {
            $return = $detail.subtotal;
        }
        if ($key == 'weight' && 'weight' in $detail) {
            $return = $detail.weight;
        }
        if ($key == 'shipping_cost' && 'shipping_cost' in $detail) {
            $return = $detail.shipping_cost;
        }
        if ($key == 'shipping_name' && 'shipping_name' in $detail) {
            $return = $detail.shipping_name;
        }
        if ($key == 'shipping_etd' && 'shipping_etd' in $detail) {
            $return = $detail.shipping_etd;
        }
        if ($key == 'discount_amount' && 'discount_amount' in $detail) {
            $return = $detail.discount_amount;
        }
        if ($key == 'discount_code' && 'discount_code' in $detail) {
            $return = $detail.discount_code;
        }
        if ($key == 'delivery_hour' && 'delivery_hour' in $detail) {
            $return = $detail.delivery_hour;
        }
        if ($key == 'total' && 'total' in $detail) {
            $return = $detail.total;
        }
        if ($key == 'note' && 'note' in $detail) {
            $return = $detail.note;
        }

        return $return;
    },
    set: function($key, $value) {
        let $detail = {};

        if (localStorage.getItem(this.storage)) {
            $detail = JSON.parse(localStorage.getItem(this.storage));
        }

        if ($key == 'subtotal') {
            $detail.subtotal = parseInt($value);
        }
        if ($key == 'weight') {
            $detail.weight = parseInt($value);
        }
        if ($key == 'shipping_cost') {
            $detail.shipping_cost = parseInt($value);
        }
        if ($key == 'shipping_name') {
            $detail.shipping_name = $value;
        }
        if ($key == 'shipping_etd') {
            $detail.shipping_etd = $value;
        }
        if ($key == 'discount_amount') {
            $detail.discount_amount = parseInt($value);
        }
        if ($key == 'discount_code') {
            $detail.discount_code = $value;
        }
        if ($key == 'delivery_hour') {
            $detail.delivery_hour = $value;
        }
        if ($key == 'total') {
            $detail.total = parseInt($value);
        }
        if ($key == 'note') {
            $detail.note = $value;
        }

        localStorage.setItem(this.storage, JSON.stringify($detail));
    }
}


FoodPress_Cart_Customer = {
    storage: 'foodpress_cart_customer',
    get: function($key) {
        let $customer = {},
            $return = '';

        if (localStorage.getItem(this.storage)) {
            $customer = JSON.parse(localStorage.getItem(this.storage));
        }

        if ($key == 'all') {
            $return = $customer;
        }

        if ($key == 'name' && 'name' in $customer) {
            $return = $customer.name
        }
        if ($key == 'phone' && 'phone' in $customer) {
            $return = $customer.phone;
        }
        if ($key == 'address' && 'address' in $customer) {
            $return = $customer.address;
        }
        if ($key == 'region_id' && 'region_id' in $customer) {
            $return = $customer.region_id;
        }
        if ($key == 'region_name' && 'region_name' in $customer) {
            $return = $customer.region_name;
        }
        if ($key == 'shipping_location' && 'shipping_location' in $customer) {
            $return = $customer.shipping_location;
        }

        return $return;
    },
    set: function($key, $value) {
        let $customer = {};
        if (localStorage.getItem(this.storage)) {
            $customer = JSON.parse(localStorage.getItem(this.storage));
        }
        if ($key == 'name') {
            $customer.name = $value;
        }
        if ($key == 'phone') {
            $customer.phone = $value;
        }
        if ($key == 'address') {
            $customer.address = $value;
        }
        if ($key == 'region_name') {
            $customer.region_name = $value;
        }
        if ($key == 'region_id') {
            $customer.region_id = $value;
        }
        if ($key == 'shipping_location') {
            $customer.shipping_location = $value;
        }
        localStorage.setItem(this.storage, JSON.stringify($customer));
    }
}

FoodPress_Cart = {
    el: document.getElementById('cart'),
    open: function() {
        if (typeof(this.el) != 'undefined' && this.el != null) {
            this.loadItems();
            this.loadCustomer();
            this.loadShipping();
            this.loadCoupon();
            this.loadNote();

            FoodPress_Cart_Items.countSubTotal();
            FoodPress_Cart_Items.countWeight();
            FoodPress_Cart_Items.countTotal();

            this.loadDetail();


            this.el.style.display = 'block';
            document.body.style.overflow = 'hidden';
            this.el.querySelector('.back').onclick = function() {
                FoodPress_Cart.close();
            }
        }
    },
    close: function() {
        this.el.style.display = 'none';
        document.body.style.overflow = 'auto';
        FoodPress.loadBasket();
    },
    loadItems: function() {
        let $items_list = '',
            $item_template = document.getElementById('template-productcart').innerHTML,
            $cart_items = FoodPress_Cart_Items.get();

        if ($cart_items.length > 0) {
            this.el.querySelector('.cart-content-items-head-add').innerHTML = '<a href="' + foodpress.store_link + '">+ Tambah</a>';

            $cart_items.forEach(function(item, i, object) {
                let $template = $item_template,
                    $price = '';
                $template = $template.replace('{item_image}', item.item_image);
                $template = $template.replace('{item_name}', item.item_name);
                if (item.item_price == item.item_price_slik) {
                    $price = '<span class="price">' + foodpress.currency.format(item.item_price) + '</span>';
                } else {
                    $price = '<span class="price">' + foodpress.currency.format(item.item_price) + '</span> <span class="price-slik">' + foodpress.currency.format(item.item_price_slik) + '</span>';
                }
                $template = $template.replace('{item_price}', $price);
                $template = $template.replace('{item_qty}', item.qty);
                $template = $template.replace('{item_id}', item.item_id);
                $items_list += $template;
            })
            this.el.querySelector('.cart-content-items-list').innerHTML = $items_list;
            this.itemChangeQty();
        } else {
            this.close();
        }
    },
    itemChangeQty: function() {
        let $buttons = this.el.querySelectorAll('.cart-button-qty');
        for (var i = 0, length = $buttons.length; i < length; i++) {
            let $qty = $buttons[i].parentNode,
                $qty_input = $qty.querySelector('input');

            $buttons[i].onclick = function() {
                let $item_id = $qty.getAttribute('data-item-id');

                if (this.getAttribute('data-qty-action') == 'plus') {
                    $qty_input.value++;
                } else {
                    if ($qty_input.value > 0) {
                        $qty_input.value--;
                    }
                }

                if ($qty_input.value > 0) {
                    FoodPress_Cart_Items.edit($item_id, 'qty', $qty_input.value);
                } else {
                    FoodPress_Cart_Items.delete($item_id);
                }

                FoodPress_Cart_Items.countSubTotal();
                FoodPress_Cart_Items.countWeight();
                FoodPress_Cart_Items.countTotal();
                FoodPress_Cart.loadItems();
                FoodPress_Cart.checkCouponCode();
                FoodPress_Cart.loadShipping();
                FoodPress_Cart.loadDetail();
            }
        }
    },
    loadCustomer: function() {
        let $customer = this.el.querySelector('.customer');
        $customer.querySelector('input[name=name]').value = FoodPress_Cart_Customer.get('name');
        $customer.querySelector('input[name=phone]').value = FoodPress_Cart_Customer.get('phone');
        $customer.querySelector('textarea[name=address]').value = FoodPress_Cart_Customer.get('address');

        $customer.querySelector('input[name=name]').oninput = function() {
            FoodPress_Cart_Customer.set('name', this.value);
        }

        $customer.querySelector('input[name=phone]').oninput = function() {
            FoodPress_Cart_Customer.set('phone', this.value);
        }

        $customer.querySelector('textarea[name=address]').oninput = function() {
            FoodPress_Cart_Customer.set('address', this.value);
        }

        let $region_field = $customer.querySelector('#autoComplete');
        if (typeof($region_field) != 'undefined' && $region_field != null) {

            $region_field.value = FoodPress_Cart_Customer.get('region_name');

            new autoComplete({
                data: {
                    src: async() => {
                        const source = await fetch(foodpress.regions_source);
                        const data = await source.json();
                        return data;
                    },
                    key: ["text"],
                    cache: false
                },
                sort: (a, b) => {
                    if (a.match < b.match) return -1;
                    if (a.match > b.match) return 1;
                    return 0;
                },
                placeHolder: "Cari Kecamatan",
                selector: "#autoComplete",
                threshold: 1,
                debounce: 300,
                searchEngine: "strict",
                resultsList: {
                    render: true,
                    container: source => {
                        source.setAttribute('class', 'region_list');
                    },
                    destination: document.querySelector("#autoComplete"),
                    position: "afterend",
                    element: "ul"
                },
                maxResults: 10,
                highlight: true,
                resultItem: {
                    content: (data, source) => {
                        source.innerHTML = data.match;
                    },
                    element: "li"
                },
                noResults: () => {
                    const result = document.createElement("li");
                    result.setAttribute("class", "autoComplete_result");
                    result.setAttribute("tabindex", "1");
                    result.innerHTML = "Tidak di temukan hasil";
                    document.querySelector("#autoComplete_list").appendChild(result);
                },
                onSelection: feedback => {
                    FoodPress_Cart_Customer.set('region_id', feedback.selection.value.id);
                    FoodPress_Cart_Customer.set('region_name', feedback.selection.value.text);
                    document.querySelector("#autoComplete").value = feedback.selection.value.text;
                    FoodPress_Cart.loadShipping();
                }
            });
        }
    },
    loadShipping: function() {
        FoodPress_Cart_Detail.set('shipping_cost', '');
        FoodPress_Cart_Detail.set('shipping_name', '');
        FoodPress_Cart_Detail.set('shipping_etd', '');

        if (foodpress.store_ongkir_enable == true && foodpress.store_ongkir_provider == 'flatongkir') {
            FoodPress_Cart_Detail.set('shipping_cost', foodpress.store_ongkir);
            FoodPress_Cart_Detail.set('shipping_name', foodpress.store_ongkir_name);

            FoodPress_Cart_Items.countTotal();
            FoodPress_Cart.loadDetail();
        }

        if (foodpress.store_ongkir_enable == true && foodpress.store_ongkir_provider == 'rajaongkir') {
            let $el = this.el.querySelector('.shipping-method-rajaongkir'),
                $region = FoodPress_Cart_Customer.get('region_id'),
                $weight = FoodPress_Cart_Detail.get('weight'),
                $url = foodpress.ajax_url + '?action=get_ongkir&nonce=' + foodpress.nonce + '&destination=' + $region + '&weight=' + $weight,
                $shippingServiceTemplate = document.getElementById('shipping-rajaongkir-service-template').innerHTML,
                $shippingService = '',
                $etd = '';

            $el.querySelector('.shipping-loader').style.display = 'block';

            if ($region) {

                let $shippingCount = function() {
                    let $cost = FoodPress_Cart_Detail.get('shipping_cost'),
                        $etd = FoodPress_Cart_Detail.get('shipping_etd'),
                        $name = FoodPress_Cart_Detail.get('shipping_name');

                    if ($cost) {
                        $el.querySelector('.shipping-show-name').innerHTML = $name;
                        $el.querySelector('.shipping-show-etd').innerHTML = $etd;
                        $el.querySelector('.shipping-show-cost').innerHTML = foodpress.currency.format($cost);

                        FoodPress_Cart_Items.countTotal();
                        FoodPress_Cart.loadDetail();
                    }
                }

                fetch($url)
                    .then((respons) => respons.json())
                    .then(function(json) {
                        if (json == '404') {
                            alert('Gagal mendapatkan data ongkir, silahkan hubungi admin');
                        } else {
                            for (let i = 0; i < json.length; i++) {
                                let $templ = $shippingServiceTemplate;

                                $templ = $templ.replace('{{name}}', json[i].courier + ' ' + json[i].service);
                                $templ = $templ.replace('{{data-name}}', json[i].courier + ' ' + json[i].service);

                                $etd = '';
                                if (json[i].etd) {
                                    $etd = 'Estimasi Hari : ' + json[i].etd;
                                }

                                $templ = $templ.replace('{{etd}}', $etd);
                                $templ = $templ.replace('{{data-etd}}', $etd);

                                $templ = $templ.replace('{{cost}}', foodpress.currency.format(json[i].value));
                                $templ = $templ.replace('{{data-cost}}', json[i].value);

                                $shippingService += $templ;
                            }

                            if (json.length > 0) {
                                $etd = '';
                                if (json[0].etd) {
                                    $etd = 'Estimasi Hari : ' + json[0].etd;
                                }

                                FoodPress_Cart_Detail.set('shipping_cost', json[0].value);
                                FoodPress_Cart_Detail.set('shipping_etd', $etd);
                                FoodPress_Cart_Detail.set('shipping_name', json[0].courier + ' ' + json[0].service);
                                $el.querySelector('.rajaongkir-list').innerHTML = $shippingService;

                                $shippingCount();

                                let $services = $el.querySelectorAll('.rajaongkir-list-box');

                                for (let i = 0; i < $services.length; i++) {
                                    $services[i].onclick = function() {
                                        let $shipping_name = this.getAttribute('data-name'),
                                            $shipping_etd = this.getAttribute('data-etd'),
                                            $shipping_cost = this.getAttribute('data-cost');

                                        FoodPress_Cart_Detail.set('shipping_cost', $shipping_cost);
                                        FoodPress_Cart_Detail.set('shipping_name', $shipping_name);
                                        FoodPress_Cart_Detail.set('shipping_etd', $shipping_etd);

                                        $shippingCount();
                                    }
                                }

                                $el.querySelector('.shipping-loader').style.display = 'none';
                            } else {
                                alert('Tidak di temukan data ongkir yang tersedia, silahkan hubungi admin');
                            }
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            }
        }

        if (foodpress.store_ongkir_enable == true && foodpress.store_ongkir_provider == 'custom') {
            let $el = this.el.querySelector('.choose-shipping-location'),
                $location,
                $cost,
                $options = $el.options,
                $info = this.el.querySelector('.shipping-method-customongkir');

            if ($options.length > 0) {
                $cost = $options[0].getAttribute('data-cost');
                $location = $options[0].getAttribute('data-location');

                FoodPress_Cart_Detail.set('shipping_cost', $cost);
                FoodPress_Cart_Detail.set('shipping_name', foodpress.store_ongkir_name);
                FoodPress_Cart_Customer.set('shipping_location', $location);

                $info.querySelector('#custom-ongkir-location').innerHTML = $location;
                $info.querySelector('#custom-ongkir-cost').innerHTML = foodpress.currency.format($cost);

                FoodPress_Cart_Items.countTotal();
                FoodPress_Cart.loadDetail();
            }

            $el.onchange = function() {
                $location = this.options[this.selectedIndex].getAttribute('data-location');
                $cost = this.options[this.selectedIndex].getAttribute('data-cost');

                FoodPress_Cart_Detail.set('shipping_cost', $cost);
                FoodPress_Cart_Detail.set('shipping_name', foodpress.store_ongkir_name);
                FoodPress_Cart_Customer.set('shipping_location', $location);

                $info.querySelector('#custom-ongkir-location').innerHTML = $location;
                $info.querySelector('#custom-ongkir-cost').innerHTML = foodpress.currency.format($cost);

                FoodPress_Cart_Items.countTotal();
                FoodPress_Cart.loadDetail();
            }
        }

        if (foodpress.store_delivery_hour === true) {
            let $el = this.el.querySelector('.shipping-delivery-hours'),
                $options = $el.querySelectorAll('.shipping-delivery-hour-option'),
                $radios = $el.querySelectorAll('.radios');

            $radios[0].classList.add('chooser');
            let $delivery_hour_value = $options[0].querySelector('.labele').innerText;
            FoodPress_Cart_Detail.set('delivery_hour', $delivery_hour_value);

            for (let i = 0; i < $options.length; i++) {
                $options[i].onclick = function() {
                    for (var ii = 0, length = $radios.length; ii < length; ii++) {
                        $radios[ii].classList.remove('chooser');
                    }
                    $delivery_hour_value = this.querySelector('.labele').innerText;
                    FoodPress_Cart_Detail.set('delivery_hour', $delivery_hour_value);
                    this.querySelector('.radios').classList.add('chooser');
                }
            }
        }
    },
    loadCoupon: function() {
        let $el = this.el.querySelector('.cart-content-coupon-list'),
            $button = $el.querySelector('.coupon-box').querySelector('button'),
            $input = $el.querySelector('.coupon-box').querySelector('input');

        this.checkCouponCode();

        $button.onclick = function() {
            $code = $input.value;
            this.innerHTML = 'Checking ....';
            this.disabled = true;
            $input.disabled = true;
            FoodPress_Cart_Detail.set('discount_code', $code);
            FoodPress_Cart.checkCouponCode();

        }
    },
    checkCouponCode: function() {
        let $el = this.el.querySelector('.cart-content-coupon-list'),
            $code = FoodPress_Cart_Detail.get('discount_code'),
            $input = $el.querySelector('.coupon-box').querySelector('input'),
            $button = $el.querySelector('.coupon-box').querySelector('button'),
            $data = {};

        if ($code) {
            $el.querySelector('.coupon-box').querySelector('input').value = $code;
        }

        $data.nonce = foodpress.nonce;
        $data.code = $code;
        $data.cart = FoodPress_Cart_Items.get();

        if ($code) {
            fetch(
                    foodpress.ajax_url + '?action=apply_coupon', {
                        method: 'POST',
                        body: JSON.stringify($data)
                    })
                .then((respons) => respons.json())
                .then(function(json) {
                    if ('status' in json && json.status == 'valid') {
                        $el.querySelector('.coupon-box-notice').innerHTML = json.message;
                        $el.querySelector('.coupon-box-notice').style.display = 'block';
                        FoodPress_Cart_Detail.set('discount_amount', json.discount);
                        FoodPress_Cart_Detail.set('discount_code', $data.code);
                        $el.querySelector('.coupon-box-notice').classList.remove('error');
                    } else {
                        FoodPress_Cart_Detail.set('discount_amount', '');
                        FoodPress_Cart_Detail.set('discount_code', '');
                        let $message = 'Error, please Contact this site owner';
                        if ('message' in json) {
                            $message = json.message;
                        }
                        $el.querySelector('.coupon-box-notice').innerHTML = $message;
                        $el.querySelector('.coupon-box-notice').classList.add('error');
                    }

                    FoodPress_Cart_Items.countTotal();
                    FoodPress_Cart.loadDetail();

                    $input.disabled = false;
                    $button.disabled = false;
                    $button.innerHTML = 'Gunakan kode voucher';
                });
        }
    },
    loadNote: function() {
        let $el = this.el.querySelector('.note-box'),
            $val = FoodPress_Cart_Detail.get('note');

        if ($val) {
            $el.querySelector('textarea').value = $val;
        }
        $el.querySelector('textarea').oninput = function() {
            FoodPress_Cart_Detail.set('note', this.value);
        }
    },
    loadDetail: function() {
        let $el = this.el.querySelector('.cart-content-detail-list'),
            $subtotal = FoodPress_Cart_Detail.get('subtotal'),
            $total = FoodPress_Cart_Detail.get('total'),
            $ongkir = FoodPress_Cart_Detail.get('shipping_cost'),
            $discount = FoodPress_Cart_Detail.get('discount_amount');

        $templ = '<table>';
        $templ += '<tr><td>Sub Total</td><td class="value">' + foodpress.currency.format($subtotal) + '</td></tr>';
        if ($ongkir) {
            $templ += '<tr><td>Ongkir</td><td class="value">' + foodpress.currency.format($ongkir) + '</td></tr>';
        }
        if ($discount) {
            $templ += '<tr><td>Diskon</td><td class="value" style="color:green">-' + foodpress.currency.format($discount) + '</td></tr>';
        }
        $templ += '<tr><td style="font-weight: bold">Total</td><td class="value" style="font-weight: bold">' + foodpress.currency.format($total) + '</td></tr>';
        $templ += '</table>';

        $el.innerHTML = $templ;

        this.loadSAubmit();
    },
    loadSAubmit: function() {
        $submit = this.el.querySelector('#submit');
        $submit.onclick = function() {

            let $invalid = [],
                $customer = [
                    { key: 'name', message: 'Mohon masukan nama Anda' },
                    { key: 'phone', message: 'Mohon masukan nomor hp Anda' },
                    { key: 'address', message: 'Mohon masukan alamat Anda' },
                ],
                $detail = [];
            if (foodpress.store_ongkir_enable && foodpress.store_ongkir_provider !== 'byadmin') {
                $detail.push({ key: 'shipping_cost', message: 'Mohon pilih kurir pengiriman' });
            }
            if (foodpress.store_ongkir_enable && foodpress.store_ongkir_provider == 'rajaongkir') {
                $customer.push({ key: 'region_id', message: 'Mohon masukan kecamatan Anda' });
            }

            $customer.forEach(function(val, key) {
                if (!FoodPress_Cart_Customer.get(val.key)) {
                    $invalid.push(val.message);
                }
            });

            $detail.forEach(function(val, key) {
                if (!FoodPress_Cart_Detail.get(val.key)) {
                    $invalid.push(val.message);
                }
            });

            if ($invalid.length > 0) {
                FoodPress_Cart.errorNotice($invalid[0]);
            } else {
                this.innerHTML = 'Processing ....';
                this.onclick = false;
                FoodPress_Cart.insertOrder();
            }

        }
    },
    errorNotice: function($message) {
        let $templ = document.getElementById('error-field-template').content.cloneNode(true);
        document.body.appendChild($templ);

        let $el = document.querySelector('.error-field');

        $el.innerHTML = $message;

        setTimeout(function() {
            $el.style.top = '110px';
        }, 100);

        setTimeout(function() {
            $el.style.top = '-110px';
        }, 1500);

        setTimeout(function() {
            $el.parentNode.removeChild($el);
        }, 1600);
    },
    insertOrder: function() {
        let $cartItems = FoodPress_Cart_Items.get();

        if ($cartItems.length > 0) {
            let $data = {},
                $number = 0,
                $items = null,
                $detail = null,
                $customer = null,
                $order = '',
                $price = 0,
                $item_total = 0,
                $url = '';

            $data.nonce = foodpress.nonce;
            $data.items = $cartItems;
            $data.detail = FoodPress_Cart_Detail.get('all');
            $data.customer = FoodPress_Cart_Customer.get('all');

            fetch(foodpress.ajax_url + '?action=order_create', {
                    method: 'POST',
                    body: JSON.stringify($data)
                }).then((respons) => respons.json())
                .then(function(json) {
                    console.log(json);
                    if (json.status == 'success') {
                        $items = json.data.items;
                        $detail = json.data.detail;
                        $customer = json.data.customer;

                        $items.forEach(function(item, i) {
                            $number = i + 1;
                            $number = $number + '. ';

                            $price = parseInt(item.item_price);
                            $item_total = $price * parseInt(item.qty);

                            $order += '%0A*' + $number + item.item_name + '* %0A';

                            $order += '  Quantity: ' + item.qty + ' %0A';
                            $order += '  Harga (@): ' + foodpress.currency.format($price) + ' %0A';
                            $order += '  Total Harga: ' + foodpress.currency.format($item_total) + ' %0A';
                        });

                        $order += '%0ASub Total : *' + foodpress.currency.format($detail.subtotal) + '*%0A';

                        if (foodpress.store_ongkir_enable) {
                            let $shipping_location = '';
                            if (foodpress.store_ongkir_provider == 'custom') {
                                $shipping_location = ' ' + $customer.shipping_location;
                            }

                            if (foodpress.store_ongkir_provider == 'byadmin') {
                                $order += 'Ongkir : *By Admin* %0A';
                            } else {
                                $order += 'Ongkir : *' + foodpress.currency.format($detail.shipping_cost) + '* (' + $detail.shipping_name + ')' + $shipping_location + '%0A';
                            }
                        }
                        if ($detail.discount_amount) {
                            $order += 'Diskon : *-' + foodpress.currency.format($detail.discount_amount) + '* (' + $detail.discount_code + ')%0A';
                        }
                        $order += 'Total : *' + foodpress.currency.format($detail.total) + '*%0A%0A';

                        if (foodpress.store_delivery_hour) {
                            $order += 'Pengiriman : *' + $detail.delivery_hour + '*%0A%0A';
                        }

                        if (typeof $detail.note !== 'undefined') {
                            $order += '*Catatan :* %0A' + $detail.note + '%0A%0A';
                        }

                        let $push = '?phone=' + foodpress.store_admin_phone + '&text=' + foodpress.store_opened_message + ' .%0A' + $order + '--------------------------------%0A*Nama :*%0A' + $customer.name + ' ( ' + $customer.phone + ' ) %0A%0A*Alamat :*%0A' + $customer.address.replace(/(\r\n|\n|\r)/gm, '%0A') + '%0A%0A' + 'Via ' + location.href;

                        if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                            $url = 'whatsapp://send' + $push;

                            FoodPress_Cart_Detail.empty();
                            FoodPress_Cart_Items.empty();
                            FoodPress_Cart.el.querySelector('#submit').innerHTML = 'Pesan';
                            FoodPress_Cart.close();
                            location.href = $url;

                            return false;
                        } else {
                            $url = 'https://web.whatsapp.com/send' + $push;

                            let w = 960,
                                h = 540,
                                left = Number((screen.width / 2) - (w / 2)),
                                top = Number((screen.height / 2) - (h / 2)),
                                popupWindow = window.open($url, '', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=1, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

                            popupWindow.focus();

                            FoodPress_Cart_Items.empty();
                            FoodPress_Cart_Detail.empty();
                            window.location.href = foodpress.store_link;

                            return false;
                        }

                    } else {
                        console.log(json);
                    }
                })
                .catch(function(error) {
                    console.log(error);
                });
        }
    }
}

FoodPress.init();