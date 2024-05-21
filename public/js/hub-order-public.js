(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	
	$(function() {
		$('#myTable').DataTable({
			"order": [[5, "desc"]],
		});
        
		$('#hub_order_add_note_button').on('click',function(e){
			e.preventDefault();
			// let p_html='<p>'+$('#hub_order_add_note_content').val()+'</p>';
			// $('#hub_note_section').append(p_html)
			if($("#hub_order_add_note_content").val()=="")
			{
				Swal.fire("Please add some note!");
					return;
			}
			else
			{
				swal.showLoading();
                    var formdata = new FormData();
                        formdata.append('action','add_order_notes');
                        //formdata.append('nonce',action_url_ajax.nonce)
                        formdata.append('post_order_id',$(this).attr('post_order_id'))
                        formdata.append('notes',$('#hub_order_add_note_content').val())
                        jQuery.ajax({
                        url: action_url_ajax.ajax_url,
                        type: 'post',
                        processData: false,
                        contentType: false,
                        processData: false,
                        data: formdata,
						dataType: "json",
                        success: function(data) {
							swal.close();
							Swal.fire(data.message);
							
							if(data.success == "success")
								{
									let p_html='<p>'+$('#hub_order_add_note_content').val()+'</p>';
									$('#hub_note_section').append(p_html)
									
								}
						  $('#hub_order_add_note_content').val('');
                        }
                        });
			}
		})

        $("#order_status").on('change',function(e){
			e.preventDefault();
			swal.showLoading();
			var formdata = new FormData();
            formdata.append('action','change_order_status');
            //formdata.append('nonce',action_url_ajax.nonce)
            formdata.append('post_order_id',$("#hub_order_add_note_button").attr('post_order_id'))
            formdata.append('order_status',$(this).val())
            jQuery.ajax({
				url: action_url_ajax.ajax_url,
				type: 'post',
				processData: false,
				contentType: false,
				processData: false,
				data: formdata,
				dataType: "json",
				success: function(data) {
					swal.close();
					Swal.fire(data.message);
					location.reload();
				}
				});             
		})
		$("#hub_order_refresh_data").on('click',function(e){
			//alert("ada");
			e.preventDefault();
			swal.showLoading();
			var formdata = new FormData();
            formdata.append('action','order_json_update');
            jQuery.ajax({
				url: action_url_ajax.ajax_url,
				type: 'post',
				processData: false,
				contentType: false,
				processData: false,
				data: formdata,
				dataType: "json",
				success: function(data) {
					swal.close();
					location.reload();
					
				}
				}); 
		})
		

	});

})( jQuery );
