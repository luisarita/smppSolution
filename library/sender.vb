Imports System.Net.Sockets
Imports System.Text
Imports System.Threading
Imports System.Management
Imports EncryptionClassLibrary
Imports vnframework
Imports System.Windows.Forms

Public Class sender

#Region " CONSTANTS        "
    Private Const PORT As Integer = 8080
    Private Const IP As String = "127.0.0.1"

    Private Const SEPARATOR As String = "$@#"
    Private Const NEWLINE As String = vbNewLine

    Private Const LASTPING As String = "lastping"
    Private Const QUIT As String = "quit"

    Private Const SLEEPPING As Integer = 60000
    Private Const SENDING As String = "Enviando: "
    Private Const HASHSALT As String = "xcphyOK8"

#End Region
#Region " PROPERTIES       "
    Public ReadOnly Property active() As Boolean
        Get
            Return valActive
        End Get
    End Property
#End Region
#Region " PRIVATE ELEMENTS "
    Private mySQLServerScan As sqlServer
    Private mySQLServerProcess As sqlServer
    Private valActive As Boolean = False
#End Region

#Region " FUNCTIONS "

    Private Function checkLicense(ByVal vServer As String, ByVal vDB As String, ByVal vUser As String, ByVal vPass As String, salt As String) As Boolean
        Dim searcher As New ManagementObjectSearcher("SELECT * FROM Win32_Volume")
        Dim i As Integer = 0

        Dim wmi_HD As ManagementObject
        Dim activacion As String = ""


        For Each wmi_HD In searcher.Get()
            'get the hard drive from collection using index
            Dim serial As String

            'get the hardware serial no.
            If wmi_HD("SerialNumber") Is Nothing Then
                serial = ""
            Else
                serial = wmi_HD("SerialNumber").ToString()

                Dim d As New Encryption.Data(serial & salt)
                Dim hash1 As New Encryption.Hash(Encryption.Hash.Provider.SHA1)
                hash1.Calculate(d)
                activacion = hash1.Value.Hex.ToUpper.Substring(0, 16)

                Dim a As New Encryption.Data(activacion & salt)
                Dim hash2 As New Encryption.Hash(Encryption.Hash.Provider.SHA1)
                hash2.Calculate(a)
                Dim md5hash As String = hash2.Value.Hex.ToUpper.Substring(0, 16)

                Dim expiracion As New Date(2099, 12, 31)
                If md5hash = mySQLServerScan.getHash() And Now <= expiracion Then
                    Return True
                End If
            End If
            Exit For 'Solo para el disco primario
            i += 1
        Next

        eventLogWriter.write("La licencia configurada no es valida: " & activacion, Application.ProductName)
        Return False
    End Function

    Protected Overloads Sub Dispose()
        deactivate()
    End Sub
    Function activate(ByVal vServer As String, ByVal vDB As String, ByVal vUser As String, ByVal vPass As String) As Boolean
        mySQLServerScan = New sqlServer(vServer, vDB, vUser, vPass, sqlServer.source.sender)
        If Not checkLicense(vServer, vDB, vUser, vPass, HASHSALT) Then
            mySQLServerScan.disconnect()
            valActive = False
            Return False
        End If
        signature = mySQLServerScan.getSignature()
        mySQLServerScan.disconnect()

        Dim esmePath As String = IO.Path.GetDirectoryName(System.Reflection.Assembly.GetExecutingAssembly().GetName().CodeBase)
        esmePath = esmePath & "\esme.bat"


        System.Diagnostics.Process.Start(New System.Diagnostics.ProcessStartInfo(esmePath))

        mySQLServerScan = New sqlServer(vServer, vDB, vUser, vPass, sqlServer.source.sender)
        mySQLServerProcess = New sqlServer(vServer, vDB, vUser, vPass, sqlServer.source.sender)

        valActive = True
        scanThread = New Thread(AddressOf scan)
        scanThread.Start()

        processThread = New Thread(AddressOf process)
        processThread.Start()

        pingThread = New Thread(AddressOf ping)
        pingThread.Start()

        Return valActive
    End Function
    Function deactivate() As Boolean
        If Not valActive Then Return True
        quitApp()
        If scanThread.IsAlive Then scanThread.Abort()
        If pingThread.IsAlive Then pingThread.Abort()
        valActive = False
        Return Not valActive
    End Function
    Private Sub ping()
        While True
            Try
                Thread.Sleep(SLEEPPING)
                Dim s As New sendingClass(LASTPING)
                Dim t As Thread = New Thread(AddressOf s.send)
                t.Start()
            Catch e As Exception
                eventLogWriter.write("PING: " & e.Message, Application.ProductName)
            End Try
        End While
    End Sub
    Private Sub scan()
        While True
            Try
                Thread.Sleep(SLEEPSCAN)
                If Not valActive Then Exit While
                Dim text As String = mySQLServerScan.search(SEPARATOR, NEWLINE)
                If text <> "" Then
                    Dim s1 As New sendingClass(text)
                    Dim t As Thread = New Thread(AddressOf s1.send)
                    t.Start()
                End If
            Catch e As Exception
                If e.Message.ToLower.Contains(CStr("System.OutOfMemoryException").ToLower) Then
                    If valActive Then _
                        scan()
                Else
                    eventLogWriter.write("SCAN: " & e.Message, Application.ProductName)
                    valActive = False
                End If
            End Try
        End While
    End Sub
    Private Sub process()
        While True
            Thread.Sleep(SLEEPSCAN)
            mySQLServerProcess.processActivities()
        End While
    End Sub
    Private Sub quitApp()
        If Not valActive Then Exit Sub
        Dim s As New sendingClass(QUIT)
        Dim t As Thread = New Thread(AddressOf s.send)
        t.Start()
    End Sub

    Class sendingClass
        Private data As String
        Private tcpClient As New tcpClient
        Sub New(ByVal vData As String)
            data = vData
        End Sub
        Sub send()
            Try
                tcpClient.Connect(IP, PORT)
                Dim NWStream As NetworkStream = tcpClient.GetStream
                Dim bytesToSend As Byte() = Encoding.ASCII.GetBytes(data)
                NWStream.Write(bytesToSend, 0, bytesToSend.Length)
                NWStream.Close()
                tcpClient.Close()
            Catch ex As Net.Sockets.SocketException
                'Si no se puede enviar el QUIT se asume que el proceso java no se levanto
                If data <> QUIT Then eventLogWriter.write(SENDING & ex.Message, Application.ProductName)
            End Try
        End Sub
    End Class
#End Region

End Class
