jQuery( function($){
    class FCWC_Cart_Actions {

        constructor() {
            this.init();
        }

        init() {
            this.debouncedUpdateCartQty = this.debounce(this.updateCartQty.bind(this), 800); // Adjust the 300ms delay as needed
            this.eventHandlers();
        }

        eventHandlers() {
            $(document.body).on( 'click', '.fcwc-remove-from-cart', this.removeFromCart.bind(this));
            $(document.body).on( 'click', '.fcwc-cart-icon, .fcwc-cart-count, .fcwc-cart-widget-close', this.toggleCart.bind(this));
            $(document.body).on( 'click', '.fcwc-offer-item-content-icon', this.toggleDescription.bind(this));
            $(document.body).on( 'click', '.fcwc-cart .fcwc-remove-coupon', this.removeCoupon.bind(this));
            $(document.body).on( 'click', '.fcwc-apply-discount', this.applyCoupon.bind(this));
            $(document.body).on( 'click', '.fcwc-simple-add-cart-button[data-type="simple"]', this.processAddToCart.bind(this));
            $(document.body).on( 'click', '.fcwc-discount-link', this.toggleDiscountCode.bind(this));
            $(document.body).on( 'keyup', '.fcwc-cart-item-qty input', this.debouncedUpdateCartQty );
        }

        debounce(func, wait) {
            let timeout;
            return function(...args) {
                const context = this;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        }

        toggleCart(e) {
            e.preventDefault();
            $("body").toggleClass('fcwc-cart-open');
            $(".fcwc-cart-widget").stop(true, true).slideToggle(300);
        }

        
        toggleDiscountCode(e) {
            e.preventDefault();
            $(e.currentTarget).parent().siblings('#fcwc-discount-code-wrap').slideToggle(300);
        }

        toggleDescription(e) {
            var __this      = $(e.currentTarget),
                popup       = __this.siblings('.fcwc-offer-item-content-description'),
                item        = popup.parents(".fcwc-offer-item-wrap"),
                popupHeight = popup.outerHeight();
        
            __this.parent().toggleClass('open');
        
            if (__this.parent().hasClass('open')) {
                popup.slideDown(300); // Slide down the content
                item.animate({ 'padding-bottom': popupHeight + 'px' }, 300); // Smooth padding-bottom transition
            } else {
                popup.slideUp(300); // Slide up the content
                item.animate({ 'padding-bottom': '20px' }, 300); // Smooth padding-bottom transition
            }
        }   

        removeCoupon(e) {
            e.preventDefault();
            var __this      = $(e.currentTarget),
                couponCode  = __this.data('coupon'),
                errorWrap   = $('#fcwc-discount-error-wrap');

            errorWrap.hide();

            $.ajax({
                url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'remove_coupon'),
                type: 'POST',
                data: {
                    coupon: couponCode,
                    security: wc_cart_params.remove_coupon_nonce || wc_checkout_params.remove_coupon_nonce
                },
                beforeSend: () => {
                    __this.addClass( 'fcwc-loading' ).prop('disabled', true);
                },
                success: (response) => {
                    $(document.body).trigger('wc_fragment_refresh');
                    $(document.body).trigger('removed_coupon', [couponCode]);
                },
                error: () => {
                    errorWrap.text('An error occurred while removing the coupon. Please try again.').show();
                },
                complete: () => {
                    __this.removeClass( 'fcwc-loading' ).prop('disabled', false);            
                }
            });
        }

        applyCoupon(e) {
            e.preventDefault();

            var __this      = $(e.currentTarget),
                form        = __this.closest('#fcwc-discount-code'),
                couponCode  = form.find('#fcwc-discount').val(),
                errorWrap   = form.find('#fcwc-discount-error-wrap');

            errorWrap.hide();

            $.ajax({
                url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'apply_coupon'),
                type: 'POST',
                data: {
                    coupon_code: couponCode,
                    security: wc_cart_params.apply_coupon_nonce || wc_checkout_params.apply_coupon_nonce
                },
                beforeSend: () => {
                    __this.addClass( 'fcwc-loading' ).prop('disabled', true);
                },
                success: (response) => {
                    $( "#fcwc-discount-error-wrap" ).html( response ).show();
                    $(document.body).trigger('wc_fragment_refresh');
                },
                error: () => {
                    errorWrap.text('An error occurred while applying the coupon. Please try again.').show();
                },
                complete: () => {
                    __this.removeClass( 'fcwc-loading' ).prop('disabled', false);
                }
            });
        }

        processAddToCart(e){
            e.preventDefault();
            var __this      = $(e.currentTarget),
                productId   = __this.data('id'),
                productType = __this.data('type');
            this.addToCart(__this, productId, productType );  
        }

        addToCart( __this, productId, productType ) {
            if ($.inArray(productType, ["external"]) === -1) {
                var ajaxData = { product_id: productId, quantity: 1 };

                // AJAX request
                $.ajax({
                    url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'add_to_cart'),
                    type: 'POST',
                    data: ajaxData,
                    beforeSend: () => {
                        __this.addClass('fcwc-loading').prop('disabled', true);
                    },
                    success: (response) => {
                        if (response.error && response.product_url) {
                            window.location = response.product_url;
                            return;
                        }
        
                        if (response && response.fragments) {
                            $.each(response.fragments, (key, value) => {
                                $(key).replaceWith(value);
                            });
                            $(document.body).trigger( 'wc_fragment_refresh' );
                        }
                    },
                    error: (response) => {
                        __this.removeClass('fcwc-loading').prop('disabled', false);
                    },
                    complete: () => {
                        __this.removeClass('fcwc-loading').prop('disabled', false);
                        if( productType !== 'grouped' ) {
                            __this.parents('.fcwc-overlay').removeClass( 'is-on' );
                        }
                    }
                });
            }
        }
        
        
        removeFromCart(e) {
            e.preventDefault();
            var __this          = $(e.currentTarget),
                cart_item_key   = __this.data('cart-item-key');

            $.ajax({
                url: wc_add_to_cart_params.wc_ajax_url.toString().replace('%%endpoint%%', 'remove_from_cart'),
                type: 'POST',
                data: { cart_item_key: cart_item_key },
                beforeSend: () => {
                    __this.addClass( 'fcwc-loading' ).prop('disabled', true);
                },
                success: (response) => {
                    if (response && response.fragments) {
                        $.each(response.fragments, (key, value) => {
                            $(key).replaceWith(value);
                        });
                    }
                    $(document.body).trigger('wc_fragment_refresh');
                },
                complete: () => {
                    __this.removeClass( 'fcwc-loading' ).prop('disabled', true);
                }
            });
        }
        
        updateCartQty(e) {
            e.preventDefault();
        
            var __this = $(e.currentTarget),
                cart = __this.closest('.fcwc-cart-list'), // Correct the selector to find the closest cart element
                cart_item_name = __this.attr('name'), // Get the name attribute of the input
                new_qty = __this.val(), // Get the updated quantity
                cart_nonce = cart.find("#woocommerce-cart-nonce").val(); // Get the nonce value for security
        
            // Ensure all necessary data is sent
            var data = {
                [cart_item_name]: new_qty, // Use dynamic key for cart item
                'update_cart': 'Update Cart',
                'woocommerce-cart-nonce': cart_nonce
            };
        
            $.ajax({
                url: wc_add_to_cart_params.cart_url, // Use the correct WooCommerce cart URL
                type: 'POST',
                data: data, // Pass the constructed data object
                dataType: 'html',
                beforeSend: () => {
                    __this.addClass('fcwc-loading').prop('disabled', true);
                },
                success: (response) => {
                    // Process WooCommerce response to update cart fragments
                    if (response && response.fragments) {
                        $.each(response.fragments, (key, value) => {
                            $(key).replaceWith(value);
                        });
                    }
                    $(document.body).trigger('wc_fragment_refresh');
                },
                complete: () => {
                    __this.removeClass('fcwc-loading').prop('disabled', false);
                },
                error: (xhr, status, error) => {
                    console.error('Error updating cart:', error);
                    alert('Failed to update the cart. Please try again.');
                }
            });
        }        
        
    }
    new FCWC_Cart_Actions();
});

