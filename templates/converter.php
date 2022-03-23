<div class="container">
    <div class="row">
        <div class="col">
            <div class="input-group mb-3">
                <input type="number" id="amount" class="form-control" />
                <select id="convert_from" class="form-select" aria-label="Default select example">
                    <?php foreach ($currencies as $currency) { ?>
                    <option value="<?php echo $currency->ticker ?>"><?php echo $currency->name ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="col">
            <div class="input-group mb-3">
                <input type="text" id="converted_amount" class="form-control" readonly />
                <select id="convert_to" class="form-select" aria-label="Default select example">
                    <?php foreach ($currencies as $currency) { ?>
                    <option value="<?php echo $currency->ticker ?>"<?php echo $currency->ticker=='USDT' ? ' selected="selected"' : '' ?>><?php echo $currency->name ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <h4>Recently converted</h4>
    <div class="container">
        <div class="row">
            <div class="col" id="left_recently_converted">
            </div>
            <div class="col" id="right_recently_converted">
            </div>
        </div>
    </div>
</div>
 
<script>
jQuery(document).ready(function(){
    jQuery('#amount').on('change', function(){
        getExchange();
    });
    jQuery('#convert_from').on('change', function(){
        getExchange();
    });
    jQuery('#convert_to').on('change', function(){
        getExchange();
    });
    
    function getExchange() {
        let amount = jQuery('#amount').val();
        let convert_from = jQuery('#convert_from').val();
        let convert_to = jQuery('#convert_to').val();
        let converted_amount = jQuery('#converted_amount');
        jQuery.get('<?php echo get_permalink() ?>', {
            cmcaction: 'convert',
            convert_from: convert_from,
            convert_to: convert_to,
            amount: amount
        }, function(response) {
            converted_amount.val(response);
        }, 'json');
    }
    
    function getHistory() {
        jQuery.get('<?php echo get_permalink() ?>', {cmcaction: 'get_history'}, function(response) {
            jQuery('#left_recently_converted').html('<ul></ul>');
            jQuery('#right_recently_converted').html('<ul></ul>');
            console.log(response);
            for(i=0; i<response.length; i++) {
                let history_item = response[i];
                let item = '<li>'+history_item.amount+' '+history_item.convert_from+' to '+history_item.convert_to+'</li>';
                if (i<5) {
                    jQuery('#left_recently_converted ul').append(item);
                } else {
                    jQuery('#right_recently_converted ul').append(item);
                }
            }
        }, 'json');
    }
    
    setInterval(() => {
        getHistory(); 
    }, 3000);
});
</script>
