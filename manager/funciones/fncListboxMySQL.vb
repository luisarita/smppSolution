Imports MySql.Data.MySqlClient
Public Class fncListboxMySQL
    Public Shared Sub popularMySQL(ByRef lb As Object, ByRef tabla As String, ByVal bound As String, ByVal text As String, ByVal CNX As MySqlConnection, Optional ByVal WHERE As String = "", Optional ByVal ORDER As String = "")
        lb.Items.Clear()
        If Len(WHERE) > 0 Then WHERE = " WHERE " + WHERE
        If Len(ORDER) > 0 Then ORDER = " ORDER BY " + ORDER
        Try
            Dim cmd As New MySqlCommand(String.Format("SELECT {0},{1} FROM {2}{3}{4}", bound, text, tabla, WHERE, ORDER), CNX)
            If CNX.State <> ConnectionState.Open Then CNX.Open()
            Dim rs As MySqlDataReader = cmd.ExecuteReader
            While rs.Read
                lb.Items.Add(New listitem(rs(1), rs(0)))
            End While
            rs.Close()
            cmd.Connection.Close()
        Catch thisError As MySqlException
            showError(thisError.Message)
        End Try
        If lb.Items.Count > 0 Then lb.SelectedIndex = 0
    End Sub
End Class
