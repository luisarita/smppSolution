Imports MySql.Data.MySqlClient
Public Class ingreso
    Private Sub cmdIngresar_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdIngresar.Click
        cnxString = "server=" & txtServidor.Text & ";User Id=" & txtUsuario.Text & ";database=" & txtDatabase.Text & ";pwd=" & txtClave.Text & seed & ";Allow User Variables=True"

        CNX = New MySqlConnection(cnxString)
        connectionOne = New MySqlConnection(cnxString)

        Try
            CNX.Open()
        Catch ex As MySqlException
            MsgBox("No se pudo abrir la conexión a la base de datos: " & ex.Message)
            Exit Sub
        End Try

        Hide()
        My.Settings.myDatabase = txtDatabase.Text
        My.Settings.myServer = txtServidor.Text
        My.Settings.Save()

        Dim m As New mdi
        m.ShowDialog()
        Dispose()
    End Sub

    Private Sub ingreso_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        txtDatabase.Text = My.Settings.myDatabase
        txtServidor.Text = My.Settings.myServer
        version.Text = CType(GetType(mdi), Type).Assembly.GetName.Version.ToString()
        txtUsuario.Focus()
    End Sub
End Class