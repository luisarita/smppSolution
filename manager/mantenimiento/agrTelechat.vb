Imports MySql.Data.MySqlClient
Imports System.IO

Public Class agrTelechat
    Inherits formControl.frmTemplate

    Private vID As Integer = -1
    Private eliminados() As Integer
    Private eliminados2() As Integer

    Private Sub cmdCrear_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdCrear.Click
        If validar() Then If agregar() Then Dispose()
    End Sub
    Function validar() As Boolean
        ErrorProvider.SetError(cbNumeros, "")
        ErrorProvider.SetError(nombre, "")
        ErrorProvider.SetError(mensaje_participantes, "")
        ErrorProvider.SetError(usuario, "")
        ErrorProvider.SetError(clave, "")
        ErrorProvider.SetError(logo, "")

        validar = False
        If cbNumeros.SelectedIndex = -1 Then
            ErrorProvider.SetError(cbNumeros, "Debe escoger un numero")
        ElseIf nombre.Text = "" Then
            ErrorProvider.SetError(nombre, "Debe ingresar un nombre para identificar el telechat")
        ElseIf lbclaves.Items.Count = 0 Then
            ErrorProvider.SetError(lbclaves, "Debe haber al menos una clave")
        ElseIf mensaje_participantes.Text = "" Then
            ErrorProvider.SetError(mensaje_participantes, "Debe ingresar el mensaje para los participantes")
        ElseIf usuario.Text = "" Then
            ErrorProvider.SetError(usuario, "Debe ingresar un usuario")
        ElseIf clave.Text = "" Then
            ErrorProvider.SetError(clave, "Debe ingresar una clave")
        ElseIf Not validarPass(clave.Text) Then
            ErrorProvider.SetError(clave, "La clave debe incluir una mayúscula, un numero y tener logitud de al menos 8 caracteres")
        ElseIf Not existeArchivo(logo.Text) Then
            If logo.Text.Length = 0 Then
                validar = True
            Else
                ErrorProvider.SetError(logo, "Debe escoger un archivo valido")
            End If
        Else
            validar = True
        End If
    End Function

    Function agregar() As Boolean
        agregar = False
        If CNX.State <> ConnectionState.Open Then CNX.Open()
        Dim numero As String = valor(cbNumeros)

        Dim useLogo As Boolean = existeArchivo(logo.Text)
        Dim tipo As String = "", archivo As Byte() = {}
        If useLogo Then
            Dim fs As FileStream = New FileStream(logo.Text, FileMode.Open, FileAccess.Read)
            Dim FileSize As Integer = fs.Length

            archivo = New Byte(FileSize) {}
            fs.Read(archivo, 0, FileSize)
            fs.Close()

            tipo = "jpeg"
        End If

        Dim strCMD As String
        If vID = -1 Then
            strCMD = "INSERT INTO telechats (numero, nombre, mensaje_participante, usuario, clave, logo_tipo, logo_archivo) VALUES ('" & numero & "','" & nombre.Text & "','" & mensaje_participantes.Text & "','" & usuario.Text & "','" & clave.Text & "','" & tipo & "',@logo_archivo)"
        Else
            strCMD = "UPDATE telechats SET numero='" & numero & "', nombre='" & nombre.Text & "', mensaje_participante='" & mensaje_participantes.Text & "',usuario='" & usuario.Text & "',clave='" & clave.Text & "'"
            If useLogo Then strCMD &= ",logo_tipo='" & tipo & "',logo_archivo=@logo_archivo"
            strCMD &= " WHERE id = " & vID
        End If
        Dim strCMD2 As String = "INSERT INTO claves (clave,idTelechat) VALUES"
        Dim strCMD3 As String = "DELETE FROM claves WHERE id="
        Dim strCMD4 As String = "INSERT INTO telechats_bloqueados (palabra, idTeleChat) VALUES"
        Dim strCMD5 As String = "DELETE FROM telechats_bloqueados WHERE id="

        Dim cmd As New MySqlCommand(strCMD, connectionOne)
        If connectionOne.State <> ConnectionState.Open Then connectionOne.Open()
        If useLogo Then cmd.Parameters.AddWithValue("@logo_archivo", archivo)
        Try
            cmd.ExecuteNonQuery()
            If vID = -1 Then
                Dim dr As Integer = New MySqlCommand("SELECT id FROM telechats WHERE nombre='" & nombre.Text & "'", CNX).ExecuteScalar
                vID = dr
            End If
            Dim i As Integer
            For i = 0 To lbclaves.Items.Count - 1
                If valor(lbclaves, i, False) = -1 Then
                    Dim clave As String = getNombre(lbclaves, i, False)
                    cmd.CommandText = strCMD2 & "('" & clave & "'," & vID & ")"
                    cmd.ExecuteNonQuery()
                End If
            Next
            With lbBloqueos
                For i = 0 To .Items.Count - 1
                    If valor(lbBloqueos, i, False) = -1 Then
                        Dim clave As String = getNombre(lbBloqueos, i, False)
                        cmd.CommandText = strCMD4 & "('" & clave & "'," & vID & ")"
                        cmd.ExecuteNonQuery()
                    End If
                Next
            End With

            If Not eliminados Is Nothing Then
                For i = 0 To eliminados.Length - 1
                    cmd.CommandText = strCMD3 & eliminados(i)
                    cmd.ExecuteNonQuery()
                Next
            End If
            If Not eliminados2 Is Nothing Then
                For i = 0 To eliminados2.Length - 1
                    cmd.CommandText = strCMD5 & eliminados2(i)
                    cmd.ExecuteNonQuery()
                Next
            End If
            agregar = True
        Catch ex As MySQLException
            MsgBox("Error al ejecutar comando: " & ex.Message)
        End Try
    End Function
    Private Sub agrRifa_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        'fncListbox.popular(cbNumeros, "numeros", "numero", "numero", CNX)
    End Sub
    Private Sub cmdAgr_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdAgr.Click
        Dim nvaClave As String = InputBox("Ingrese una clave: ")
        If nvaClave.Length = 0 Then
            MsgBox("No se permiten claves nulas")
        Else
            lbclaves.Items.Add(New listitem(nvaClave, -1))
        End If
    End Sub
    Private Sub cmdEli_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdEli.Click
        If lbclaves.SelectedIndex = -1 Then
            MsgBox("Debe seleccionar una clave")
        Else
            If valor(lbclaves, lbclaves.SelectedIndex, False) <> -1 Then
                If eliminados Is Nothing Then
                    ReDim eliminados(0)
                Else
                    ReDim Preserve eliminados(eliminados.Length)
                End If
                eliminados(eliminados.Length - 1) = valor(lbclaves)
            End If
            lbclaves.Items.RemoveAt(lbclaves.SelectedIndex)
        End If
    End Sub

    Private Sub Button1_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles Button1.Click
        Dim o As New OpenFileDialog
        o.Filter = "Archivos de Imagen|*.jpg"
        o.ShowDialog()
        logo.Text = o.FileName
    End Sub

    Private Sub cmdAgrB_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdAgrB.Click
        Dim nvaClave As String = InputBox("Ingrese una clave: ")
        If nvaClave.Length = 0 Then
            MsgBox("No se permiten claves nulas")
        Else
            lbBloqueos.Items.Add(New listitem(nvaClave, -1))
        End If
    End Sub

    Private Sub cmdEliB_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdEliB.Click
        With lbBloqueos
            If .SelectedIndex = -1 Then
                MsgBox("Debe seleccionar una clave")
            Else
                If valor(lbBloqueos, .SelectedIndex, False) <> -1 Then
                    If eliminados2 Is Nothing Then
                        ReDim eliminados2(0)
                    Else
                        ReDim Preserve eliminados2(eliminados2.Length)
                    End If
                    eliminados2(eliminados2.Length - 1) = valor(lbBloqueos)
                End If
                .Items.RemoveAt(.SelectedIndex)
            End If
        End With
    End Sub
End Class
