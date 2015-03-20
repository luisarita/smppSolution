Imports System.ServiceProcess

Public Class smppService
    Inherits ServiceBase

#Region " Component Designer generated code "
    Public Sub New()
        MyBase.New()
        InitializeComponent()
    End Sub
    Protected Overloads Overrides Sub Dispose(ByVal disposing As Boolean)
        If disposing Then
            If Not (components Is Nothing) Then
                components.Dispose()
            End If
        End If
        MyBase.Dispose(disposing)
    End Sub
    <MTAThread()> _
    Shared Sub Main()
        Dim ServicesToRun() As ServiceBase
        ServicesToRun = New ServiceBase() {New smppService}
        ServiceBase.Run(ServicesToRun)
    End Sub

    Private components As ComponentModel.IContainer

    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.ServiceName = "SMPP Server"
    End Sub
#End Region

    Private Const DEBUGSLEEP As Integer = 10000
    Protected Overrides Sub OnStart(ByVal args() As String)
        myData = library.registry.loadRegistry()
        library.MONITORING_ENABLED = myData.MONITORING_ENABLED
        library.MONITORING_SMS = myData.MONITORING_SMS
        library.SMS_NUMBER = myData.SMS_NUMBER
        library.SMS_NUMBER_OUT = myData.SMS_NUMBER_OUT
        library.SLEEPSCAN = myData.SLEEPSCAN

        If My.Settings.debug Then Threading.Thread.Sleep(DEBUGSLEEP)
        If My.Settings.autostart Then If Not activateThreads() Then Me.Stop()
    End Sub
    Protected Overrides Sub OnStop()
        If myListener.active Or mySender.active Then activateThreads()

        Dim myProcesses As Process() = Process.GetProcessesByName("java")
        For Each myProcess As Process In myProcesses
            If myProcess.MainWindowTitle = "" Then myProcess.Kill()
        Next myProcess
    End Sub

#Region " PRIVATE ELEMENTS "
    Private myListener As New library.listener
    Private mySender As New library.sender
    Private myData As library.registryVals
    Private myServer As String = library.GetFromRegistry("server", "")
#End Region
#Region " FUNCTIONS "
    Private Function activateThreads() As Boolean
        If myListener.active Or mySender.active Then
            deactivateListener()
            deactivateSender()
        Else
            If activateSender() Then
                Return activateListener()
            Else
                Return False
            End If
        End If
    End Function
    Private Function activateListener() As Boolean
        Return myListener.activate(myServer, myData.bd, myData.user, myData.pass)
    End Function
    Private Function activateSender() As Boolean
        Return mySender.activate(myServer, myData.bd, myData.user, myData.pass)
    End Function
    Private Function deactivateListener() As Boolean
        If myListener.deactivate() Then Return True
    End Function
    Private Function deactivateSender() As Boolean
        If mySender.deactivate() Then Return True
    End Function
#End Region

End Class