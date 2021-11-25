Call RunProcess()
	Sub RunProcess()
	On Error Resume Next
	Dim URL, objRequest
		Set objRequest = CreateObject("Microsoft.XMLHTTP")
		URL = "http://localhost/gs-system/mail/mail_tempo.php"
		objRequest.open "POST", URL , false
		objRequest.Send
	Set objRequest = Nothing 'clean up memory and thanks for playing

End Sub
