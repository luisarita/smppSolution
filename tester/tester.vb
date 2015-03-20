Public Class tester
#Region " PRIVATE ELEMENTS "
    Private myListener As New library.listener
    Private mySender As New library.sender
    Private myData As library.registryVals
    Private myServer As String = library.GetFromRegistry("server", "")
#End Region

    Private Sub Form1_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        myData = library.registry.loadRegistry()
        library.MONITORING_ENABLED = myData.MONITORING_ENABLED
        library.MONITORING_SMS = myData.MONITORING_SMS
        library.SMS_NUMBER = myData.SMS_NUMBER
        library.SMS_NUMBER_OUT = myData.SMS_NUMBER_OUT
        library.SLEEPSCAN = myData.SLEEPSCAN

        If mySender.activate(myServer, myData.bd, myData.user, myData.pass) Then
            myListener.activate(myServer, myData.bd, myData.user, myData.pass)
        End If
    End Sub
End Class
