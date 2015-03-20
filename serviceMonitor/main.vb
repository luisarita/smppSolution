Imports System.Data.Odbc
Imports System.Threading
Imports System.ServiceProcess

Public Class main
    Inherits ServiceBase

#Region " Component Designer generated code "
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
        Dim myData As library.registryVals = library.registry.loadRegistry()
        Dim ServicesToRun() As ServiceBase
        ServicesToRun = New ServiceBase() {New main("127.0.0.1", myData.bd, myData.user, myData.pass)}
        ServiceBase.Run(ServicesToRun)
    End Sub

    Private components As System.ComponentModel.IContainer

    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.ServiceName = "SMPP Monitor"
    End Sub
#End Region

    Private t1 As New Thread(AddressOf checkReceivedMessages)
    Private t2 As New Thread(AddressOf nextRestartCheck)
    Private ConnectionString As String

    Public Sub New(ByVal server As String, ByVal bd As String, ByVal user As String, ByVal pass As String)
        MyBase.New()
        InitializeComponent()
        ConnectionString = "DRIVER={MySQL ODBC 3.51 Driver};SERVER=" & server & ";DATABASE=" & bd & ";UID=" & user & ";PASSWORD=" & pass
    End Sub

    Protected Overrides Sub OnStart(ByVal args() As String)
        myController = New ServiceController()
        myController.MachineName = "."
        myController.ServiceName = "SMPP Service"
        If Not activateThreads() Then Me.Stop()
    End Sub

    Private Function activateThreads() As Boolean
        If activateCheck() Then Return activateRestart()
        Return False
    End Function

    Private Function activateCheck() As Boolean
        t1.Start()
        Return True
    End Function
    Private Function activateRestart() As Boolean
        t2.Start()
        Return True
    End Function

    Private hourToCheck As Integer = -1
    Private minuteToCheck As Integer = -1
    Private id As Long = -1

    Sub nextRestartCheck()
        While True
            If hourToCheck = -1 Then hourToCheck = Now.Hour
            If minuteToCheck = -1 Then minuteToCheck = Now.Minute
            Dim SQL As String = "SELECT hora, minuto FROM mantenimiento_reinicios WHERE NOT (hora=24 AND minuto=0) AND hora*60+minuto > " & (hourToCheck * 60 + minuteToCheck) & " ORDER BY hora*60+minuto LIMIT 1"

            Dim cnx As New OdbcConnection(ConnectionString)
            Try
                If cnx.State <> ConnectionState.Open Then cnx.Open()
                Dim dr As OdbcDataReader = New OdbcCommand(SQL, cnx).ExecuteReader
                If Not dr.Read Then
                    dr.Close()
                    SQL = "SELECT hora, minuto FROM mantenimiento_reinicios WHERE NOT (hora=24 AND minuto=0) GROUP BY hora, minuto HAVING hora*60+minuto = MIN(hora*60+minuto) LIMIT 1"
                    dr = New OdbcCommand(SQL, cnx).ExecuteReader
                    If dr.Read Then
                        hourToCheck = dr!hora
                        minuteToCheck = dr!minuto
                    End If
                    dr.Close()
                Else
                    hourToCheck = dr!hora
                    minuteToCheck = dr!minuto
                    dr.Close()
                End If
                cnx.Close()

                Dim milisegundos As Integer = ((hourToCheck - Now.Hour) * 60 + (minuteToCheck - Now.Minute))
                If milisegundos < 0 Then milisegundos += 24 * 60
                milisegundos *= 60 * 1000

                Thread.Sleep(milisegundos)
                EventLog.WriteEntry("Restarting Service at " & hourToCheck & " : " & minuteToCheck)
                restart()
            Catch ex As OdbcException
                EventLog.WriteEntry("No connection to database could be established: " & ex.Message)
            End Try
        End While
    End Sub
    Sub checkReceivedMessages()
        While True
            Dim SQL As String = "SELECT CAST(MOD(IFNULL(MAX(numero_de_comunicacion_re),0),1000) AS DECIMAL(18,2)) AS id FROM comunicacion_recibida_tabla;"
            Dim cnx As New OdbcConnection(ConnectionString)
            Try
                If cnx.State <> ConnectionState.Open Then cnx.Open()
                Dim dr As OdbcDataReader = New OdbcCommand(SQL, cnx).ExecuteReader
                If dr.Read Then
                    If id = dr!id Then
                        EventLog.WriteEntry("Restarting Service: " & id & " vs. " & dr!id)
                        restart()
                    End If
                    id = dr!id
                End If
                dr.Close()
            Catch ex As OdbcException
                EventLog.WriteEntry("No connection to database could be established: " & ex.Message)
            End Try
            Thread.Sleep(30 * 60 * 1000)
        End While
    End Sub

    Private myController As ServiceController
    Sub restart()
        'Stop service
        If myController.Status <> ServiceControllerStatus.Stopped Then myController.Stop()

        'Wait for stop
        Thread.Sleep(5000)

        Dim found As Boolean = False
        For i As Integer = 1 To 11
            Try
                'We are killing all java instances; this is dirty as we are asuming only our processes are up
                Dim pProcess() As Process = Process.GetProcessesByName("java")
                For Each p As Process In pProcess
                    p.Kill()
                    found = True
                Next
            Catch
            End Try
            Thread.Sleep(1000)
        Next i
        Try
            If myController.Status = ServiceControllerStatus.Running Then
                myController.Stop()
                Thread.Sleep(5 * 1000)
                myController.WaitForStatus(ServiceControllerStatus.Stopped)
            End If
        Catch ex As Exception
            EventLog.WriteEntry("Can't stop " & myController.DisplayName & "; will force it: " & ex.Message)
            For i As Integer = 1 To 1
                Try
                    Dim pProcess() As Process = Process.GetProcessesByName("service")
                    For Each p As Process In pProcess
                        p.Kill()
                        found = True
                    Next
                Catch
                End Try
            Next i
            Thread.Sleep(10000)
        End Try

        myController.Refresh()
        If myController.Status <> ServiceControllerStatus.Running Then myController.Start()
    End Sub

    Protected Overrides Sub OnStop()
        If t1.IsAlive Then t1.Abort()
        If t2.IsAlive Then t2.Abort()
    End Sub
End Class
