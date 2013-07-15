jQuery(document).ready(function() {
jQuery(function() {
  jQuery('#select_all_rm').click(function() {
            jQuery('.rm').attr('checked', 'checked');
        });
});
});

jQuery(document).ready(function() {
jQuery(function() {
  jQuery('#select_all_rm_s').click(function() {
            jQuery('.rm_s').attr('checked', 'checked');
        });
});
});

jQuery(document).ready(function() {
jQuery(function() {
  jQuery('#select_all_rq').click(function() {
            jQuery('.rq').attr('checked', 'checked');
        });
});
});

jQuery(document).ready(function() {
jQuery(function() {
  jQuery('#select_all_rq_s').click(function() {
            jQuery('.rq_s').attr('checked', 'checked');
        });
});
});

jQuery(document).ready(function() {
jQuery(function() {
  jQuery('#deselect_all_rm').click(function() {
            jQuery('.rm,#select_all_rm').attr('checked', false);
        });
});
});

jQuery(document).ready(function() {
jQuery(function() {
  jQuery('#deselect_all_rm_s').click(function() {
            jQuery('.rm_s,#select_all_rm_s').attr('checked', false);
        });
});
});

jQuery(document).ready(function() {
jQuery(function() {
  jQuery('#deselect_all_rq').click(function() {
            jQuery('.rq,#select_all_rq').attr('checked', false);
        });
});
});

jQuery(document).ready(function() {
jQuery(function() {
  jQuery('#deselect_all_rq_s').click(function() {
            jQuery('.rq_s,#select_all_rq_s').attr('checked', false);
        });
});
});

// Javascript for adding new field
jQuery(document).ready( function($) {

	/**
	 * Credits to the Advanced Custom Fields plugin for this code
	 */

	// Update Order Numbers
	function update_order_numbers(div) {
		div.children('tbody').children('tr.wccs-row').each(function(i) {
			$(this).children('td.wccs-order').html(i+1);
		});
	}
	
	// Make Sortable
	function make_sortable(div){
		var fixHelper = function(e, ui) {
			ui.children().each(function() {
				$(this).width($(this).width());
			});
			return ui;
		};

		div.children('tbody').unbind('sortable').sortable({
			update: function(event, ui){
				update_order_numbers(div);
			},
			handle: 'td.wccs-order',
			helper: fixHelper
		});
	}

	var div = $('.wccs-table'),
		row_count = div.children('tbody').children('tr.wccs-row').length;

	// Make the table sortable
	make_sortable(div);
	
	// Add button
	$('#wccs-add-button').live('click', function(){

		var div = $('.wccs-table'),			
			row_count = div.children('tbody').children('tr.wccs-row').length,
			new_field = div.children('tbody').children('tr.wccs-clone').clone(false); // Create and add the new field

		new_field.attr( 'class', 'wccs-row' );

		// Update names
		new_field.find('[name]').each(function(){
			var count = parseInt(row_count);
			var name = $(this).attr('name').replace('[999]','[' + count + ']');
			$(this).attr('name', name);
		});

		// Add row
		div.children('tbody').append(new_field); 
		update_order_numbers(div);

		// There is now 1 more row
		row_count ++;

		return false;	
	});

	// Remove button
	$('.wccs-table .wccs-remove-button').live('click', function(){
		var div = $('.wccs-table'),
			tr = $(this).closest('tr');

		tr.animate({'left' : '50px', 'opacity' : 0}, 250, function(){
			tr.remove();
			update_order_numbers(div);
		});

		return false;
	});
});