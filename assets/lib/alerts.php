<?php

####### Default JS Alert Popup #######

//------ Alert Popup & Redirect After
function alertRedirect($message, $redirectURL)
{
  echo "<script language='JavaScript'>window.alert('$message');window.location='$redirectURL'</script>";
}

//------ Alert Popup & Redirect Using JS Replace
function alertReplace($message, $redirectURL)
{
  echo "<script language='JavaScript'>window.alert('$message');window.location.replace('$redirectURL')</script>";
}

//------ Alert Popup & Reload Page After
function alertReload($message)
{
  echo "<script language='JavaScript'>window.alert('$message'); window.location=window.location.href;</script>";
}

//------ Normal Alert Popup
function alertWindow($message)
{
  echo "<script language='JavaScript'>window.alert('" . $message . "')</script>";
}


####### SweetAlert JS Popup #######
# Note: Include SweetAlert CDN for this to work
# https://sweetalert2.github.io/#download

# $title: Title for your popup
# $msg: Message for your popup
# $type: Popup icon options - "error" (Red), "success" (Green), "info" (Blue), "warning" (Yellow)
# $duration: How long should the popup stay open? (in ms) 1000 = 1 second
# $url: Where to be redirected to?

//------ SweetAlert Popup With Confirmation Button
function sweetAlert($type, $title, $msg, $duration)
{
  echo "<script>$(function(){Swal.fire({
			title: '$title',
			text: '$msg',
			icon: '$type',
			timer: $duration,
			customClass: {
				confirmButton: 'btn btn-primary'
			},
			buttonsStyling: false
		})});</script>";
}

//------ SweetAlert Popup With Confirm Button & Reload Page After
function sweetAlertReload($type, $title, $msg, $duration)
{
  echo "<script>$(function(){Swal.fire({
			title: '$title',
			text: '$msg',
			icon: '$type',
			timer: $duration,
			customClass: {
				confirmButton: 'btn btn-primary',
			},
			buttonsStyling: false
		}).then(function() {
			window.location = window.location.href;
		})});</script>";
}

//------ SweetAlert Popup With Confirm Button & Redirect After
function sweetAlertRedirect($title, $message, $type, $redirectURL)
{
  echo "<script>$(function(){Swal.fire({
		title: '$title',
		text: '$message',
		icon: '$type',
		confirmButtonColor: '#FFD400',
		confirmButtonText: 'OK',
		customClass: 
		{
			confirmButton: 'btn btn-primary',
		}
	}).then(function() {
		window.location = '$redirectURL';
	})});</script>";
}

//------ SweetAlert Popup With Confirm Button & Redirect After Timer
function sweetAlertTimerRedirect($title, $message, $type, $redirectURL)
{
  echo "<script>$(function(){Swal.fire({
        title: '$title!',
        text: '$message',
        icon: '$type',
		confirmButtonColor: '#FFD400',
		confirmButtonText: 'OK',
        timer: 2500
        }).then(function() {
            window.location = '$redirectURL';
        })});</script>";
}


####### SweetAlert JS Popup #######
# Note: Include ToastrAlert CDN for this to work
# https://codeseven.github.io/toastr/

# $title: Title for your popup
# $msg: Message for your popup
# $type: Popup icon options - "error" (Red), "success" (Green), "info" (Blue), "warning" (Yellow)
# $duration: How long should the popup stay open? (in ms) 1000 = 1 second

//------ ToastrAlert Popup
function toastrAlert($type, $title, $msg, $duration)
{
  echo "<script>toastr.$type(
			'$msg',
			'$title',
			{
				timeOut: $duration,
				closeButton: true,
				tapToDismiss: false
			}
		);</script>";
}

function authErrorMsg($text)
{
  echo "<p class='alert alert-danger'>" . $text . "</p>";
}

function authCorrectMsg($text)
{
  echo "<p class='alert alert-success'>" . $text . "</p>";
}

function successBadge($text)
{
  echo "<span class='badge badge-success'>" . $text . "</span>";
}

function errorBadge($text)
{
  echo "<span class='badge badge-danger'>" . $text . "</span>";
}

function publishedBadge($text)
{
  echo "<span class='badge badge-warning'>" . $text . "</span>";
}
