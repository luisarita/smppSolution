Imports MySql.Data.MySqlClient
Imports System.IO

Public Class agrAgrupadores
    Private vID As Integer = -1
    Private eliminados() As Integer
    Private eliminadosReplicacion() As Integer

    Public Sub New()
        MyBase.New()
        InitializeComponent()
        fncListboxMySQL.popularMySQL(CObj(cbSuscripciones), "suscripciones", "id", "nombre", CNX, "activa=1", "nombre")
    End Sub
    Sub New(ByVal id As Integer)
        MyBase.New()
        InitializeComponent()
        fncListboxMySQL.popularMySQL(CObj(cbSuscripciones), "suscripciones", "id", "nombre", CNX, "activa=1", "nombre")
        vID = id
        pTitle = "Modificar Suscripción"
        cmdCrear.Text = "Modificar"
        If CNX.State <> ConnectionState.Open Then CNX.Open()
        Dim dr As MySqlDataReader = New MySqlCommand("SELECT clave FROM agrupadores a WHERE a.id=" & id, CNX).ExecuteReader
        If dr.Read Then
            clave.Text = dr!clave
            dr.Close()
            fncListboxMySQL.popularMySQL(CObj(lbSuscripciones), "suscripciones", "id", "nombre", CNX, String.Format("id IN (SELECT idSuscripcion FROM agrupadores_detalles WHERE idAgrupador={0})", vID))
        Else
            dr.Close()
            showError("Registro no encontrado")
            Dispose()
        End If
    End Sub

    Private Sub cmdCrear_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdCrear.Click
        If validar() Then If agregar() Then Dispose()
    End Sub
    Function validar() As Boolean
        ErrorProvider.SetError(clave, "")
        validar = False

        If clave.Text = "" Then
            ErrorProvider.SetError(clave, "Debe ingresar una clave")
        Else
            validar = True
        End If
    End Function

    Function agregar() As Boolean
        agregar = False
        If CNX.State <> ConnectionState.Open Then CNX.Open()

        Dim strCMD As String
        If vID = -1 Then
            strCMD = String.Format("INSERT INTO agrupadores (clave) VALUES ('{0}')", clave.Text)
        Else
            strCMD = String.Format("UPDATE agrupadores SET clave='{0}' WHERE id={1}", clave.Text, vID)
        End If

        Const strCMD4 As String = "REPLACE INTO agrupadores_detalles (idAgrupador, idSuscripcion) VALUES"
        Const strCMD5 As String = "DELETE FROM agrupadores_detalles WHERE idAgrupador="

        Dim cmd As New MySqlCommand(strCMD, connectionOne)
        If connectionOne.State <> ConnectionState.Open Then connectionOne.Open()
        Try
            cmd.ExecuteNonQuery()

            cmd.CommandText = strCMD5 & vID
            cmd.ExecuteNonQuery()

            If vID = -1 Then
                Dim dr As Integer = New MySqlCommand(String.Format("SELECT id FROM agrupadores WHERE clave='{0}'", clave.Text), CNX).ExecuteScalar
                vID = dr
            End If

            For i As Integer = 0 To lbSuscripciones.Items.Count - 1
                Dim clave As Integer = valor(lbSuscripciones, i, False)
                cmd.CommandText = String.Format("{0}('{1}',{2})", strCMD4, vID, clave)
                cmd.ExecuteNonQuery()
            Next
            agregar = True
        Catch ex As MySqlException
            MsgBox(String.Format("Error al ejecutar comando: {0}: {1}", cmd.CommandText, ex.Message))
        End Try
    End Function

    Private Sub cmdEliReplicacion_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdEliSuscripcon.Click
        If lbSuscripciones.SelectedIndex = -1 Then
            showError("Debe seleccionar una actividad")
        Else
            If valor(lbSuscripciones, lbSuscripciones.SelectedIndex, False) <> -1 Then
                If eliminadosReplicacion Is Nothing Then
                    ReDim eliminadosReplicacion(0)
                Else
                    ReDim Preserve eliminadosReplicacion(eliminados.Length)
                End If
                eliminadosReplicacion(eliminadosReplicacion.Length - 1) = valor(lbSuscripciones)
            End If
            lbSuscripciones.Items.RemoveAt(lbSuscripciones.SelectedIndex)
        End If
    End Sub
    Private Sub cmdAgrReplicacion_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdAgrSuscripcion.Click
        With cbSuscripciones
            If .SelectedIndex >= 0 Then
                Dim myitem As listitem = CType(cbSuscripciones.SelectedItem, listitem)
                lbSuscripciones.Items.Add(New listitem(myitem.Name, myitem.id))
            End If
        End With
    End Sub
End Class