Imports System.Threading
Public Module globalVariables
    Public MONITORING_ENABLED As Boolean = True
    Public MONITORING_SMS As Boolean = True

    Public PINGINTERVAL As Integer
    Public SMS_NUMBER As String
    Public SMS_NUMBER_OUT As String
    Public SLEEPSCAN As Integer

    Public listenThread As Thread
    Public scanThread As Thread
    Public pingThread As Thread
    Public processThread As Thread
    Public msgCountIn As Long = 0
    Public msgCountOut As Long = 0
    Public lastCountIn As Long = 0
    Public lastCountOut As Long = 0
    Public signature As String
End Module