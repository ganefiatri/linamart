function addToCart(dt, btnType) {
    var id = $(dt).attr('attr-id');
    var qty = $(dt).parent().find('#qty').text();
    var oldQty = qty;
    if (qty.length > 0) {
        qty = parseInt(qty);
    }
    if (btnType == '+') {
        qty = qty + 1;
    } else {
        qty = (qty > 0) ? (qty - 1) : 0;
    }

    $(dt).parent().find('#qty').html(qty);
    $.ajax({
        method: "POST",
        url: $(dt).parent().attr('attr-href'),
        data: { qty: qty, _token: $('meta[name="csrf-token"]').attr('content') }
    }).done(function(msg) {
        var _toast = $('#toastprouctaddedtiny');
        if (msg.success) {
            _toast.removeClass('bg-warning').addClass('bg-success');
        } else {
            _toast.addClass('bg-warning').removeClass('bg-success');
            $(dt).parent().find('#qty').html(oldQty);
        }
        _toast.find('#toast-msg').html(msg.message);
        _toast.toast('show');
        reloadCartInfo(msg, id, qty);
    });
    return false;
}

function deleteCart(dt) {
    if (confirm('Are you sure to delete this?')) {
        var id = $(dt).attr('attr-id');
        $.ajax({
            method: "DELETE",
            url: $(dt).attr('href'),
            data: { id: id, _token: $('meta[name="csrf-token"]').attr('content') }
        }).done(function(msg) {
            var _toast = $('#toastprouctaddedtiny');
            if (msg.success) {
                _toast.removeClass('bg-warning').addClass('bg-success');
            } else {
                _toast.addClass('bg-warning').removeClass('bg-success');
            }
            _toast.find('#toast-msg').html(msg.message);
            _toast.toast('show');
            reloadCartInfo(msg, id, 0);
        });
    }

    return false;
}

function reloadCartInfo(msg, id = 0, qty) {
    if ($('#cart-menu').length > 0) {
        $('#cart-menu').find('.countercart').html(msg.total_cart_qty);
        if (msg.total_cart_qty > 0) {
            $('#cart-menu').removeClass('d-none');
            $('#notif-menu').addClass('d-none');
        } else {
            $('#cart-menu').addClass('d-none');
            $('#notif-menu').removeClass('d-none');
        }
    }
    if (qty <= 0) {
        if ($('#cart-items-' + id).length > 0) {
            $('#cart-items-' + id).remove();
        }
    }
    if ($('.tot-cart-items').length > 0) {
        $('.tot-cart-items').html(msg.total_cart_items);
    }
    if ($('.cart-total-price').length > 0) {
        $('.cart-total-price').html(msg.cart_total.formated.total_price);
    }
    if ($('.cart-total-discount').length > 0) {
        $('.cart-total-discount').html(msg.cart_total.formated.total_discount);
    }
    if ($('.cart-subtotal').length > 0) {
        $('.cart-subtotal').html(msg.cart_total.formated.sub_total);
    }
    if ($('.cart-shipping-fee').length > 0) {
        $('.cart-shipping-fee').html(msg.cart_total.formated.shipping_fee);
    }
    if ($('.cart-total').length > 0) {
        $('.cart-total').html(msg.cart_total.formated.total);
    }
}