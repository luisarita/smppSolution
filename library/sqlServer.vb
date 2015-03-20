Imports System.Data.Odbc
Imports IST.DataHash
Imports vnframework
Imports System.Windows.Forms
Imports System.Text.RegularExpressions
Imports System.Text.ASCIIEncoding
Imports System.Text.UTF8Encoding

Public Class sqlServer
    Private Const NOT_CONNECTED As String = "No se puede conectar a base de datos"
    Private Const COMPLETED_SUCCESFULLY As String = "Operacion completada"

    Private Const STARTUP As String = "Inicialización: "
    Private Const CONFIRMATION As String = "Confirmación: "
    Private Const MSGOUTPUT As String = "Salida de Mensajes: "
    Private Const ACTIVITY As String = "Actividad: "
    Private Const INSERTING As String = "Adición: "
    Private Const HOURSTEPS As String = "Proceso Horario: "

    Private Const RESETFREQUENCY As Integer = 6 'in hours
    Private Const SLEEPRESET As Integer = 1000 * 20

#Region " PROPERTIES "
    Public ReadOnly Property state() As System.Data.ConnectionState
        Get
            Return connectionOne.State
        End Get
    End Property
#End Region

    Private connectionOne As New OdbcConnection
    Private connectionTwo As New OdbcConnection
    Private commandOne As New OdbcCommand
    Private commandTwo As New OdbcCommand

    Private drOne As OdbcDataReader
    Private drTwo As OdbcDataReader

    Private firstScan As Boolean = True
    Private vUseServiceType As Boolean = False
    Private vBlockSize As Integer = 150
    Private thisHour As Long

    Private thisHourReset As Integer
    Private monitorThread As Threading.Thread

    Private confirmIds As ArrayList() = {New ArrayList, New ArrayList, New ArrayList, New ArrayList}

    Private myMD5 As New MD5

    Enum source
        listener = 1
        sender = 2
    End Enum

    Sub New(ByVal server As String, ByVal bd As String, ByVal user As String, ByVal pass As String, ByVal mySource As source)
        Try
            Dim ConnectionString As String = String.Format("DRIVER={{MySQL ODBC 3.51 Driver}};SERVER={0};DATABASE={1};UID={2};PASSWORD={3}", server, bd, user, pass)

            connectionOne.ConnectionString = ConnectionString
            connectionTwo.ConnectionString = ConnectionString
            connectionOne.ConnectionTimeout = 65535
            connectionTwo.ConnectionTimeout = 65535

            connectionOne.Open()
            connectionTwo.Open()
            commandOne.Connection = connectionOne
            commandTwo.Connection = connectionTwo

            monitorThread = New Threading.Thread(AddressOf monitor)
            monitorThread.Start()
        Catch thisError As Exception
            notify(String.Format("{0}{1}: {2}", STARTUP, NOT_CONNECTED, thisError.Message))
        End Try
    End Sub
    Protected Overloads Sub Dispose(ByVal disposing As Boolean)
        disconnect()
    End Sub
    Sub disconnect()
        If Not monitorThread Is Nothing Then monitorThread.Abort()
        If Not connectionOne Is Nothing Then If connectionOne.State <> ConnectionState.Closed Then connectionOne.Close()
        If Not connectionTwo Is Nothing Then If connectionTwo.State <> ConnectionState.Closed Then connectionTwo.Close()
    End Sub
    Public Sub notify(ByVal message As String, Optional ByVal sms As Boolean = False, Optional ByVal ToEventLog As Boolean = True)
        If ToEventLog Then eventLogWriter.write(message, Application.ProductName)
        If sms And MONITORING_SMS Then
            Try
                SyncLock connectionTwo
                    If connectionTwo.State <> ConnectionState.Open Then connectionTwo.Open()
                    Dim SMS_NUMBER_ARR() As String = SMS_NUMBER.Split(",")
                    For i As Integer = 0 To SMS_NUMBER_ARR.Length - 1
                        If SMS_NUMBER_ARR(i).Length > 0 Then
                            commandTwo.CommandText = String.Format("INSERT INTO mensajes_pendientes (numero, numero_salida, mensaje, fecha_salida, prioridad, tipo_servicio) VALUES ({0},'{1}','{2}', NOW(), 0, '')", SMS_NUMBER_ARR(i), SMS_NUMBER_OUT, message)
                            commandTwo.ExecuteNonQuery()
                        End If
                    Next
                End SyncLock
            Catch ex As OdbcException
                notify(String.Format("COULD NOT SMS: {0} {1}", ex.Message, commandTwo.CommandText), False, True)
            End Try
        End If
    End Sub

    Public Sub monitor()
        thisHour = Now.AddHours(1).Hour
        thisHourReset = Now.AddHours(RESETFREQUENCY).Hour

        While True
            If Now.Hour = thisHourReset And Now.Minute = 0 Then
                resetConnections()
                thisHourReset = Now.AddHours(RESETFREQUENCY).Hour
            End If
            Threading.Thread.Sleep(SLEEPRESET)
        End While
    End Sub
    Public Sub resetConnections()
        Try
            SyncLock connectionOne
                If connectionOne.State <> ConnectionState.Closed Then connectionOne.Close()
                If connectionOne.State <> ConnectionState.Open Then connectionOne.Open()
            End SyncLock
            SyncLock connectionTwo
                If connectionTwo.State <> ConnectionState.Closed Then connectionTwo.Close()
                If connectionTwo.State <> ConnectionState.Open Then connectionTwo.Open()
            End SyncLock
        Catch ex1 As Exception
            notify("RESETING CONNECTIONS " & ex1.Message)
        End Try
    End Sub
    Public Function confirm(ByVal id As Integer, ByVal status As String, message As String) As String
        SyncLock connectionOne
            Dim SQL As String = ""
            Try
                If connectionOne.State <> ConnectionState.Open Then connectionOne.Open()
                SQL = String.Format("UPDATE mensajes_pendientes SET confirmado={0}, respuesta_esme='{2}', fecha_confirmacion=NOW() WHERE id={1}", status, id, message)
                Dim updateCMD As New OdbcCommand(SQL, connectionOne)
                updateCMD.ExecuteNonQuery()
                Return COMPLETED_SUCCESFULLY
            Catch thiserror As OdbcException
                notify(CONFIRMATION & thiserror.Message & SQL)
                Return CONFIRMATION & thiserror.Message & SQL
            End Try
        End SyncLock
    End Function
    Public Function sanitizeString(originalString As String) As String
        sanitizeString = ""
        For i As Integer = 0 To originalString.Length - 1
            If InStr("abcdefghijklmnopqrstuvwxyz1234567890 .,;!", LCase(originalString(i))) Then sanitizeString &= originalString(i)
        Next
    End Function
    Public Sub insert(ByVal number As String, ByVal source As String, ByVal data As String, service_type As String)
        Dim SQL As String = ""

        If data <> "" Then
            SyncLock connectionOne
                If connectionOne.State <> ConnectionState.Open Then connectionOne.Open()
                Try
                    commandOne.CommandText = String.Format("SELECT p.valor - COUNT(*) AS conteo FROM comunicacion_recibida_tabla crt, mantenimiento_parametros p WHERE p.id=7 AND crt.telefon_destino_de_respu='{0}' AND crt.telefono_origen_de_respue='{1}' AND crt.datos_recibidos='{2}'", number, source, data)
                    Dim conteo As Integer = commandOne.ExecuteScalar()
                    If conteo > 0 Then
                        SQL = String.Format("INSERT INTO comunicacion_recibida_tabla (FECHA_DE_RECEPCION, DATOS_RECIBIDOS, ORIGEN_DE_RESPUESTA_RECIB, TELEFONO_ORIGEN_DE_RESPUE, PROCESADO, TELEFON_DESTINO_DE_RESPU, TIPO_SERVICIO) VALUES (NOW(),'{0}','3','{1}','N','{2}','{3}')", data, source, number, service_type)
                        commandOne.CommandText = SQL
                        commandOne.ExecuteNonQuery()
                    End If
                Catch thiserror1 As OdbcException
                    Dim errMsg As String = thiserror1.Message.Replace("[MySQL]", "").Replace("[ODBC 3.51 Driver]", "").Replace("ERROR", "").Replace("[42000]", "").Replace("[mysqld-5.1.56-community-log]", "").Trim()
                    notify(String.Format("{0}{1} al insertar {2} en comando: {3}", INSERTING, errMsg, data, commandOne.CommandText))
                End Try
            End SyncLock
            Dim a As Integer = addReceived()
        End If
    End Sub

    Public Function search(ByVal SEPARATOR As String, ByVal NEWLINE As String) As String
        SyncLock connectionOne
            If connectionOne.State <> ConnectionState.Open Then connectionOne.Open()
            Try
                If firstScan Then
                    commandOne.CommandText = "SELECT COUNT(*) FROM mensajes_pendientes WHERE estado=0"
                    Dim cola As Integer = commandOne.ExecuteScalar

                    notify(String.Format("Inicio de Proceso: {0} Cola: ({1})", Now(), cola), True, False)
                    msgCountIn = 0
                    msgCountOut = 0
                    firstScan = False
                    vUseServiceType = useServiceType()
                    vBlockSize = getBlockSize()
                End If
                If (Now.Minute = 0 And Now.Hour = thisHour) Then
                    commandOne.CommandText = "SELECT COUNT(*) FROM mensajes_pendientes WHERE estado=0"
                    Dim cola As Long = commandOne.ExecuteScalar

                    commandOne.CommandText = "SELECT COUNT(*) FROM ac_recargas_participantes p, ac_recargas r WHERE p.estado=4 AND p.idrecarga=r.id AND r.activa=1 AND monto>0"
                    Dim recargas As Long = commandOne.ExecuteScalar

                    commandOne.CommandText = String.Format("INSERT INTO mantenimiento_corridas (fecha,entrada,salida,incremento_entrada, incremento_salida, cola) VALUES (NOW(),{0},{1},{0}-{3},{1}-{4},{2})", msgCountIn, msgCountOut, cola, lastCountIn, lastCountOut)
                    commandOne.ExecuteNonQuery()

                    notify(String.Format("Confirmación de Corrida: {0} Entrada: {1}(+{4}) Salida: {2}(+{5}) Cola: ({3}) Recargas: ({6})", thisHour, msgCountIn, msgCountOut, cola, msgCountIn - lastCountIn, msgCountOut - lastCountOut, recargas), True, False)
                    processHourSteps(thisHour)
                    thisHour = Now.AddHours(1).Hour
                    lastCountIn = msgCountIn
                    lastCountOut = msgCountOut
                End If
            Catch ex As Exception
                notify("SEARCHMAINT: " & ex.Message & ex.StackTrace)
            End Try

            search = ""
            Dim ids As String = ""
            Try
                Dim strUseServiceType As String = IIf(vuseservicetype, "", "NULL AS ")
                commandOne.CommandText = "SELECT id, numero, REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(mensaje,'ú','u'),'ó','o'),'í','i'),'é','e'),'á','a'),'ñ','n'),'à','a'),'\n',' '),'\r',' '),'&nbsp;',' ') AS mensaje, numero_salida, " & strUseServiceType & " tipo_servicio FROM mensajes_pendientes WHERE estado=0 AND fecha_salida<=NOW() ORDER BY prioridad, fecha_salida, id LIMIT " & vBlockSize
                drOne = commandOne.ExecuteReader()
                While drOne.Read()
                    If Len(CStr(drOne!mensaje)) > 1 Then _
                        search &= drOne!id & SEPARATOR & drOne!numero & SEPARATOR & drOne!mensaje & Signature & SEPARATOR & drOne!numero_salida & SEPARATOR & drOne!tipo_servicio & NEWLINE
                    ids &= "," & drOne!id
                    addSent()
                End While
                drOne.Close()
                Try
                    commandOne.CommandText = String.Format("UPDATE mensajes_pendientes SET estado=1, fecha_envio=NOW() WHERE id IN (-1{0});", ids)
                    If ids <> "" Then commandOne.ExecuteNonQuery()
                Catch thiserror As Exception
                    notify(MSGOUTPUT & thiserror.Message)
                End Try
            Catch ex As Exception
                notify("SEARCHSEND: " & ex.Message)
            End Try
        End SyncLock
    End Function

    Public Sub processActivities()
        SyncLock connectionOne
            If connectionOne.State <> ConnectionState.Open Then connectionOne.Open()

            Dim SQL As New ArrayList
            SQL.Add("CALL sp_aniversarios_procesamiento()")
            SQL.Add("CALL sp_chats_procesamiento()")
            SQL.Add("CALL sp_diccionarios_procesamiento()")
            SQL.Add("CALL sp_encuestas_procesamiento()")
            SQL.Add("CALL sp_listados_procesamiento()")
            SQL.Add("CALL sp_media_procesamiento()")
            SQL.Add("CALL sp_media_suscripciones_procesamiento()")
            SQL.Add("CALL sp_recargas_procesamiento()")
            SQL.Add("CALL sp_recordatorios_distribucion()")
            SQL.Add("CALL sp_recordatorios_eventos_distribucion()")
            SQL.Add("CALL sp_rifas_procesamiento()")
            SQL.Add("CALL sp_suscripciones_adiciones()")
            SQL.Add("CALL sp_suscripciones_busqueda()")
            SQL.Add("CALL sp_suscripciones_cancelacion()")
            SQL.Add("CALL sp_suscripciones_distribucion()")
            SQL.Add("CALL sp_suscripciones_preguntas_procesamiento()")
            SQL.Add("CALL sp_suscripciones_preguntas_distribucion()")
            SQL.Add("CALL sp_tarjetas_adiciones()")
            SQL.Add("CALL sp_tarjetas_procesamiento()")
            SQL.Add("CALL sp_telebingo_procesamiento()")
            SQL.Add("CALL sp_telechats_procesamiento()")
            SQL.Add("CALL sp_trivias_distribucion()")
            SQL.Add("CALL sp_trivias_resultados()")
            SQL.Add("CALL sp_trivias_adiciones()")
            SQL.Add("CALL sp_trivias_actualizacion_estados()")
            SQL.Add("CALL sp_conexiones_procesamiento()")

            SQL.Add("CALL sp_no_procesados_procesamiento()")

            For Each query As String In SQL
                Try
                    commandOne.CommandText = query
                    commandOne.ExecuteNonQuery()
                Catch thiserror As OdbcException
                    notify(ACTIVITY & thiserror.Message)
                End Try
            Next

        End SyncLock
    End Sub

    Public Sub processHourSteps(ByVal hour As Integer)
        Dim dateAddParameters As String = ""

        'Suscripciones: Enviar mensaje de renovacion
        Try
            Dim indices() As Integer = {0, 1, 2}
            For i As Integer = 0 To 2
                commandOne.CommandText = String.Format("CALL sp_suscripciones_renovaciones({0})", i)
                commandOne.ExecuteNonQuery()
            Next i
            commandOne.CommandText = String.Format("CALL sp_suscripciones_escalonamiento")
            commandOne.ExecuteNonQuery()
            If hour = 0 Then
                commandOne.CommandText = "CALL sp_proceso_nocturno()"
                commandOne.ExecuteNonQuery()
            End If
        Catch ex As Exception
            notify(HOURSTEPS & ex.Message)
        End Try
    End Sub

    Public Function saveLicense(ByVal valor As String) As Boolean
        Try
            commandOne.CommandText = String.Format("UPDATE mantenimiento_parametros SET valor='{0}' WHERE id=1", valor)
            commandOne.ExecuteNonQuery()
            Return True
        Catch ex As Exception
            notify("saveLicense: " & ex.Message)
            Return False
        End Try
    End Function

#Region " PARAMETER FUNCTIONS "
    Public Function getHash() As String
        Try
            commandOne.CommandText = "SELECT valor FROM mantenimiento_parametros WHERE id=1"
            getHash = commandOne.ExecuteScalar
        Catch ex As Exception
            notify("getHash: " & ex.Message)
            getHash = ""
        End Try
    End Function
    Public Function getSignature() As String
        Try
            commandOne.CommandText = "SELECT valor FROM mantenimiento_parametros WHERE id=13"
            getSignature = commandOne.ExecuteScalar
        Catch ex As Exception
            notify("getSignature: " & ex.Message)
            getSignature = ""
        End Try
    End Function
    Public Function getURLROOT() As String
        Try
            commandOne.CommandText = "SELECT valor FROM mantenimiento_parametros WHERE id=3"
            getURLROOT = commandOne.ExecuteScalar
        Catch ex As Exception
            notify("getURLROOT: " & ex.Message)
            getURLROOT = ""
        End Try
    End Function
    Public Function useServiceType() As Boolean
        Try
            commandOne.CommandText = "SELECT COUNT(*) FROM mantenimiento_parametros WHERE id=11 AND valor=1"
            useServiceType = CBool(commandOne.ExecuteScalar)
        Catch ex As Exception
            notify("useServiceType: " & ex.Message)
            useServiceType = False
        End Try
    End Function
    Public Function getBlockSize() As Integer
        Try
            commandOne.CommandText = "SELECT valor FROM mantenimiento_parametros WHERE id=12"
            getBlockSize = CInt(commandOne.ExecuteScalar)
        Catch ex As Exception
            notify("getBlockSize: " & ex.Message)
            getBlockSize = 150
        End Try
    End Function
#End Region

End Class
