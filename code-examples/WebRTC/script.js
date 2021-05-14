//====================================================
// Set your credentials
//====================================================

var inoutnumber 	= 'VIRTUAL_NUMBER';
var webrtcUser 		= 'WEBRTC_USER';
var webrtcPass 		= 'WEBRTC_PASS';
var webrtcHost 		= '@voip.46elks.com'; 
var webrtcSocket 	= 'wss://voip.46elks.com/w1/websocket';

//====================================================

var session;			// Will contain the RTC Session
var localStream; 	// Audio/video stream from your client
var constraints = {video: false, audio: true}; // Promise to send to peer

// Buttons
var btnCall 		= $('#call');			// Btn to answer the call
var btnPickup 	= $('#pickup');		// Btn to answer the call
var btnHangup 	= $('#hangup');		// Btn to end the call

// ========================================
// Check if browser has audio/video support
// ========================================
function hasGetUserMedia() {
	return !!(navigator.mediaDevices && navigator.mediaDevices.getUserMedia);
}
if (!hasGetUserMedia()) {
	alert("getUserMedia() is not supported by your browser");
}

// ======================================
// Configure and activate your user agent
// ======================================
var socket = new JsSIP.WebSocketInterface(webrtcSocket);
var configuration = {
	sockets  : [ socket ],
	uri      : webrtcUser+webrtcHost,
	password : webrtcPass,
  session_timers: false // If set to true, call will end after a minute or so
};

var ua = new JsSIP.UA(configuration);
ua.start();

// ==============================================
// If user agent was not successfully registrated
// ==============================================
ua.on('registrationFailed', function(e){
	console.log("registrationFailed"); 
	console.log(e); 
});

// ========================================
// Check for incoming and outgoing sessions
// ========================================
ua.on('newRTCSession', function(e){ 
	session = e.session;

	// Bind events specific to this session
	bindSessionEvents(session);

	// Check if session is initiated by a remote or local host
	if (e.originator === "remote") {
	
		// Check if incoming call is you doing an outbound call
		if (session.remote_identity.uri.user === inoutnumber ) {
			
			// set status message
			set_status("Calling..");

			// Answer the call automatically
			answer_call();

		} else {

			// Show/hide buttons
			btnPickup.trigger("show");
			btnHangup.trigger("show");
			btnCall.trigger("hide");

			// Set status message
			set_status("Incoming call from " +session.remote_identity.uri.user);
		}

	} else if (e.originator === "local") {

			// Set status message
			set_status("Calling..");
	}
});


// =====================
// Make an outgoing call
// =====================
function make_call(){

	connectData = {"connect": $("#to").val()};

	$.post({
		url: window.location.href + "forward.php",
		method: "POST",
		data:{
			voice_start: JSON.stringify(connectData),
			to: "+"+webrtcUser,
			from: inoutnumber,
		}
	});
}

// ========
// END CALL
// ========
function end_call(){
	session.terminate();
}

// ===========
// ANSWER CALL
// ===========
function answer_call(){
	var options = {localStream, constraints}
	session.answer(options);
}

// =============================
// SET STATUS MESSAGE ON SCREEN
// =============================
function set_status(msg){
	$("#status").html(msg);
}

// ==================
// Bind button events
// ==================

// On hide buttons (show them as disabled)
btnPickup.add(btnHangup).add(btnCall).on("hide", function(){
	$(this).attr("disabled", true);
})

// On show buttons (show them as enabled)
btnPickup.add(btnHangup).add(btnCall).on("show", function(){
	$(this).attr("disabled", false);
})

// Hide these buttons by default
btnPickup.trigger("hide");
btnHangup.trigger("hide");


// ===================================
// Bind events for the current session
// ===================================
function bindSessionEvents(session){

	// When you deny premission for microphone and/or video
	session.on('getusermediafailed', function(){
		session.terminate();
		set_status("No premission to access camera or microphone");
	});


	// When call is answered
	session.on('confirmed', function(data) {

		// Show/hide buttons
		btnPickup.trigger("hide");
		btnHangup.trigger("show");
		btnCall.trigger("hide");

		// If then call is an incoming call
		if(session.remote_identity.uri.user !== inoutnumber) {
			set_status("Call answered");
		}
	});


	// When session ends after session is confirmed
	session.on('ended', function(data) {

		// Show/hide buttons
		btnPickup.trigger("hide");
		btnHangup.trigger("hide");
		btnCall.trigger("show");
		
		// Update status message
		set_status("Call is ended");

		// Hide status message after 5 seconds
		setTimeout(function() {
			set_status("");
		}, 5000);
	});


	// When session ends before session is confirmed
	session.on('failed', function(data) {

		// Show/hide buttons
		btnPickup.trigger("hide");
		btnHangup.trigger("hide");
		btnCall.trigger("show");

		// Update status message
		set_status("Call failed");

		// Hide status message after 5 seconds
		setTimeout(function() {
			set_status("");
		}, 5000);

	});


	// When connected to peer
	session.on('peerconnection', function(e) {

		// Check if an audio track is present from peer 
		e.peerconnection.ontrack = () => {

			// Create a new media stream to store audio
			var remoteStream = new MediaStream();

			// Get every audio track from peer and save it in remoteStream
			e.peerconnection.getReceivers().forEach((receiver) => {
				remoteStream.addTrack(receiver.track);
			});

			 // Get hidden audio track on the page
			var remoteAudio = document.getElementById("remoteAudio");

			// Add the media stream to the audio element
			remoteAudio.srcObject = remoteStream;

			// Press play to hear the audio
			remoteAudio.play();

		}

	});


}
