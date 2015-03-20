Imports System.Net.Sockets
Imports System.Threading
Imports System.Text
Imports System.Windows.Forms
Imports vnframework

Public Class listener

#Region " CONSTANTS        "
    Private Const PORT As Integer = 1000
    Private Const SEPARATOR As String = "$@#"
    Private Const NEWLINE As String = "#@$"
    Private Const CONFINDICATOR As String = "^&%"
#End Region
#Region " PROPERTIES       "
    Public ReadOnly Property active() As Boolean
        Get
            Return valActive
        End Get
    End Property
#End Region
#Region " PRIVATE ELEMENTS "
    Private tcpListener As New tcpListener(Net.IPAddress.Parse("127.0.0.1"), PORT)
    Private valActive As Boolean = False
    Private mySQLServer As sqlServer
#End Region

#Region " FUNCTIONS "
    Protected Overloads Sub Dispose()
        deactivate()
    End Sub
    Function activate(ByVal server As String, ByVal db As String, ByVal user As String, ByVal pass As String) As Boolean
        mySQLServer = New sqlServer(server, db, user, pass, sqlServer.source.listener)
        listenThread = New Thread(AddressOf listen)
        valActive = True
        listenThread.Start()
        Return valActive
    End Function

    Function deactivate() As Boolean
        If Not listenThread Is Nothing Then
            If listenThread.IsAlive Then
                listenThread.Abort()
                tcpListener.Stop()
            End If
        End If
        If Not valActive Then Return True
        valActive = False
        Return Not valActive
    End Function
    Private Sub listen()
        Thread.Sleep(1000) 'Initial wait for all connections to be established
        Try
            tcpListener.Start()

            While valActive
                Dim TcpClient As TcpClient = tcpListener.AcceptTcpClient()
                Dim networkStream As NetworkStream = TcpClient.GetStream()
                Dim bytes(TcpClient.ReceiveBufferSize) As Byte
                networkStream.Read(bytes, 0, CInt(TcpClient.ReceiveBufferSize))
                Dim clientData As String = Encoding.ASCII.GetString(bytes)

                Dim p As New processClass(clientData, mySQLServer)
                Dim t As Thread = New Thread(AddressOf p.process)
                t.Start()

                networkStream.Close()
                TcpClient.Close()
            End While

            tcpListener.Stop()
        Catch e As Exception
            Try
                tcpListener.Stop()
            Catch ex As Exception
            End Try

            If e.Message.ToLower.Contains(CStr("System.OutOfMemoryException").ToLower) Then
                If valActive Then _
                    listen()
            Else
                eventLogWriter.Write("LISTEN: " & e.Message, Application.ProductName)
                valActive = False
            End If
        End Try
    End Sub
#End Region

    Class processClass
        Private data As String
        Private mySQLServer As sqlServer
        'Dim strFile As String = "yourfile.txt"

        Sub New(ByVal vData As String, ByVal vMySQLServer As sqlServer)
            data = vData
            mySQLServer = vMySQLServer
        End Sub

        Private Sub processConfirmation()
            Try
                data = data.Substring(CONFINDICATOR.Length)
                Dim parts() As String = data.Replace(vbNewLine, "").Replace(CONFINDICATOR, "@").Split("@")
                'Normal message
                Dim message As String = parts(2).Trim().Substring(0, 1)
                Try
                    Dim result As Integer = 0
                    If Integer.TryParse(parts(2).Trim().Substring(0, 2), result) Then message = result
                    If Integer.TryParse(parts(2).Trim().Substring(0, 3), result) Then message = result
                    If Integer.TryParse(parts(2).Trim().Substring(0, 4), result) Then message = result
                    If Integer.TryParse(parts(2).Trim().Substring(0, 5), result) Then message = result
                Catch
                End Try
                mySQLServer.confirm(id:=parts(0).Trim(), status:=parts(1).Trim(), esmeResponse:=message, esmeId:=parts(3))
            Catch e As Exception
                eventLogWriter.write("Process ESME CONFIRMATION: " & e.Message & "", Application.ProductName)
            End Try
        End Sub
        Private Sub processInboundMessage()
            Try
                Dim lines() As String = data.Split({NEWLINE}, StringSplitOptions.RemoveEmptyEntries)
                For Each thisline As String In lines
                    thisline = thisline.Trim()
                    If thisline.Length > 0 Then
                        If thisline.StartsWith(SEPARATOR) And MONITORING_ENABLED Then
                            eventLogWriter.write("MONITORING: " & data, Application.ProductName)
                            Dim int As Integer = thisline.Substring(SEPARATOR.Length).Substring(0, thisline.Length - NEWLINE.Length)
                        Else
                            Dim lineParts() As String = thisline.Split({SEPARATOR}, StringSplitOptions.None)
                            If lineParts.Length = 0 Or lineParts.Length = 1 Then
                                'We ignore incomplete messages
                            ElseIf lineParts.Length >= 4 Then
                                If lineParts.Length >= 5 AndAlso lineParts(4) > 1 Then
                                    'Not identified message, please ignore
                                ElseIf lineParts.Length >= 5 AndAlso lineParts(4) = 1 Then
                                    'Delivery Receipt
                                    'mySQLServer.notify(String.Format("DLR CALL4 {0}", lineParts(4)))
                                    'mySQLServer.notify(String.Format("DLR CALL3 {0}", lineParts(3)))
                                    'mySQLServer.notify(String.Format("DLR CALL {0}", data))
                                    'mySQLServer.deliveryReceipt(id:=lineParts(0).Trim(), status:=lineParts(1).Trim(), esmeResponse:=lineParts(2).Trim(), esmeId:=lineParts(3).Trim())
                                Else
                                    Dim data As String = mySQLServer.sanitizeString(lineParts(2).Trim)
                                    Dim service_type As String = mySQLServer.sanitizeString(lineParts(3).Trim)
                                    If service_type.Length > 5 Then service_type = service_type.Substring(0, 5)

                                    mySQLServer.insert(number:=lineParts(0), source:=lineParts(1), data:=data, service_type:=service_type)
                                End If
                            End If
                        End If
                    End If
                Next
            Catch e As Exception
                eventLogWriter.write("Process ESME: " & e.Message & " / " & data, Application.ProductName)
            End Try
        End Sub
        Public Sub process()
            If data.StartsWith(CONFINDICATOR) Then  'Si es una confirmacion de enviados
                processConfirmation()
            Else
                processInboundMessage()
            End If
        End Sub
    End Class
End Class
