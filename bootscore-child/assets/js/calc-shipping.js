(function($){
    $(document).ready(function(){
        var $btn = $('#wc_cp_calc_btn');
        var $input = $('#wc_cp_input');
        var $result = $('#wc_cp_result');

        $btn.on('click', function(e){
            e.preventDefault();
            var cp = $input.val().trim();
            if (!cp) {
                $result.text('Ingresá un código postal.');
                return;
            }
            $result.text( wcCpShipping.strings.calculating );
            $btn.prop('disabled', true);

            // optionally, send product ID or province if you want
            var product_id = $('input[name="product_id"]').val() || 0;
            var data = {
                action: 'wc_cp_calculate',
                nonce: wcCpShipping.nonce,
                cp: cp,
                product_id: product_id
            };

            $.post( wcCpShipping.ajax_url, data )
                .done(function(res){
                    if ( res.success ) {
                        var html = 'Tarifa: ' + res.data.rate;
                        if ( res.data.label ) html += ' (' + res.data.label + ')';
                        $result.html( html );
                    } else {
                        if ( res.data && res.data.message === 'no_match' ) {
                            $result.html( wcCpShipping.strings.no_result );
                        } else {
                            $result.html( wcCpShipping.strings.error );
                        }
                    }
                })
                .fail(function(){
                    $result.text( wcCpShipping.strings.error );
                })
                .always(function(){
                    $btn.prop('disabled', false);
                });
        });
    });
})(jQuery);
