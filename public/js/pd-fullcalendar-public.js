(function( $ ) {
	'use strict';

	// console.log(fc.events);

	 document.addEventListener('DOMContentLoaded', function() {

        var calendarEl = document.getElementById('calendar');
		var start_str = document.getElementById('start_str');
		var end_str = document.getElementById('end_str');

        var calendar = new FullCalendar.Calendar(calendarEl, {

			selectable: true,
			initialView: 'timeGridWeek',
			allDaySlot: false,
			// slotMinTime: '09:00:00',
			slotDuration: '00:60:00',
			// slotMaxTime: '18:00:00',

			headerToolbar: {
			  left: 'prev,next today',
			  center: 'title',
			  right: 'dayGridMonth,timeGridWeek,timeGridDay'
			},

		  	events: fc.events,
			eventClick: function(info) {
				var event_info = document.getElementById('event_info');
				event_info.style.display = 'flex';
				// console.log(info);
				var html = 
				html = '<div>';
				html += '<h4><a href="'+ info.event.extendedProps.url +'">'+ info.event.title +'</a></h4>';
				html += '<p><b>Subject :</b> '+ info.event.extendedProps.subject +'</p>';
				html += '<p><b>Field of Study :</b> '+ info.event.extendedProps.fieldOfStudy +'</p>';
				html += '<p><b>Grade :</b> '+ info.event.extendedProps.grade +'</p>';
				html += '<p><b>Start :</b> '+ moment(info.event.start).format('YYYY-MM-DD') + ' at ' + moment(info.event.start).format('hh:mm A') + '</p>';
				html += '<p><b>End :</b> '+ moment(info.event.start).format('YYYY-MM-DD')+ ' at ' + moment(info.event.start).add(1, 'hours').format('hh:mm A')  +'</p>';
				// if(info.event.extendedProps.join && info.event.extendedProps.reason == 'join'){
				html += '<ul><li><a href="'+ info.event.extendedProps.join +'" class="event_button">Start Session</a></li>';
				// }else{
				// 	html += '<p><b>'+ info.event.extendedProps.reason +'</b></p>';
				// }
				html += '<li><a href="'+ info.event.extendedProps.url +'" class="event_button">View Tutoring Request</a></li></ul>';
				html += '</div>';
				event_info.innerHTML = html;
            },

		  	select: function(info) {
			// alert('selected ' + info.startStr + ' to ' + info.endStr);
			var form = document.getElementById('booking_form_wrapper');
			form.style.display = 'flex';

			// set date and time fields value
			start_str.value = info.startStr;
			end_str.value = info.endStr;

			// set date in select calendar
			fp.setDate(info.start, false, 'YYYY-MM-DD');
		  }
        });
        calendar.render();

		// set date and time fields value on change date and time
		const fp = flatpickr("#booking_date", {
			enableTime: true,
    		dateFormat: "Y-m-d h:i",
			onChange: function(selectedDates, dateStr, instance){
				start_str.value = moment(dateStr).format();
				end_str.value = moment(dateStr).format();
			}
		});

      });


	  $('#booking_form_wrapper, #event_info').click(function(event){
		event.stopPropagation();
		if(event.target.id == 'booking_form_wrapper')
			$('#booking_form_wrapper').hide();
		if(event.target.id == 'event_info')
			$('#event_info').hide();
	  });

	//   $(".pay").attr("disabled", true);

	  $(".agree_to_pay").change(function() {
		  var target = $(this).data('target');
		  console.log('Done');
		  console.log(target);
		if(this.checked) {
			$('.msg-' + target).css('display', 'none');
			$("#" + target).removeClass('pay-disabled');
			// $("." + target).removeAttr("disabled");
			$("#" + target).addClass('pay-enabled');
		}else{
			$('.msg-' + target).css('display', 'block');
			$("#" + target).removeClass('pay-enabled');
			// $("." + target).attr("disabled", true);
			$("#" + target).addClass('pay-disabled');
		}
	});

	$("a.pay").hover(
		function() {
			var msg_pay = $(this).attr('id');
			$( '.msg-' + msg_pay ).removeClass( "disabled" );
			$( '.msg-' + msg_pay ).addClass( "enabled" );
		}, function() {
			var msg_pay = $(this).attr('id');
			$( '.msg-' + msg_pay ).removeClass( "enabled" );
			$( '.msg-' + msg_pay ).addClass( "disabled" );
		}
	  );

	$('a.pay').click(function(event){
		event.preventDefault();
		var form = $(this).data('form');
		var agree = $(this).data('agree');
		var msg_pay = $(this).attr('id');
		if ( $("#" + agree).is(':checked') ) {
			$("#" + form).submit();
		}else{
			$( '.msg-' + msg_pay ).addClass( "enabled" );
		}
	})

	$('.decline_booking').click(function(e){

		e.preventDefault();
		if(confirm("Are you sure you want to cancel booking?")){

			const button = $(this);
			button.text = 'wait...';
			var cancel_reason = $('#cancel_reason').val();
			var bookingStatus = $(this).data('bookingstatus');
			var requestId = $(this).data('requestid');
			var tutorId = $(this).data('tutorid');
			var bookingId = $(this).data('bookingid');

			console.log(bookingStatus + ' ' + requestId);
			var data = {
			//   '_ajax_nonce': fastgrade_custom._ajax_nonce,
			'action' : 'cancel_booking',
			'booking_status' : bookingStatus,
			'request_id' : requestId,
			'tutor_id' : tutorId,
			'booking_id' : bookingId,
			'cancel_reason' : cancel_reason
			}
			jQuery.post(fastgrade_custom.ajax_url, data, function(response) {
				console.log(response);
				console.log('.msg-pay-'+ response.data.booking_id);

				if(response.data.success == 1){
					// alert('booking has been canceled');
					$('a[data-bookingid="'+ response.data.booking_id +'"]').text('Cancelled');
					$("#pop-container").fadeOut();
					$("#pop-modal").fadeOut();
					
					if(response.data.booking_status == 3){
						$('.msg-pay-'+ response.data.booking_id).text('Booking has been cancelled.');
					}
					
					if(response.data.booking_status == 4){
						$('.msg-pay-'+ response.data.booking_id).text('Booking has been cancelled and refund has intiated.');
					}
					location.reload(true);
				}
			});

		}else{
			return false;
		}

	})
	$(".sb-open").click(function(){
		$("#pop-container").css("display","block");
		$("#pop-modal").css("display","block");
	});

	$(".cancel").click(function(){
		$("#pop-container").fadeOut();
		$("#pop-modal").fadeOut();
	});

})( jQuery );
