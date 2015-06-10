$(function() {
	$(".spotlight").on('click', function() {
		$("#signal-modal").modal('show');
	});
	$(".send-sam-signal").on("click", function() {
		var message = $("#signal-message").val();
		var pin = $("#signal-pin").val();
		TextSam(pin, message);
	});
	$("#signal-pin").on("keydown", function(e) {
		if (e.which === 13) {
			$(".send-sam-signal").click();
		}
	});
});

function TextSam(pin, message) {
	var error = $("#signal-modal").find(".error");
	error.html('').hide();
	if (message.trim().length === 0) {
		error.html("Message cannot be empty!").show();
		return;
	}
	$(".send-sam-signal").attr("disabled", true);
	$.get("/api/twilio.php?textSam=1&pin=" + pin + "&message=" + message, function(result) {
		$(".send-sam-signal").attr("disabled", false);
		if (JSON.parse(result)) {
			$("#signal-modal").modal("hide");
			$(".sam-texted").addClass("animate").show();
			setTimeout(function() {
				$(".sam-texted").removeClass("animate").hide();
			}, 5000);
		} else {
			//Did not send. Probably have to wait until you can send again.
			error.html("The Sam Signal failed! Double check the PIN. If you sent the Sam Signal recently, you'll need to wait 5 minutes before sending it again.").show();
		}
	});
}
