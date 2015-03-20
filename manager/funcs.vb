Module funcs
    Public Function getDate(ByVal myDate As Date) As String
        getDate = myDate.Year & "-" & myDate.Month & "-" & myDate.Day & " " & myDate.Hour & ":" & myDate.Minute & ":" & myDate.Second
    End Function

    Public Function existeArchivo(ByVal fileName As String) As Boolean
        If fileName.Length = 0 Then Return False
        Dim f As New IO.FileInfo(fileName)
        Return f.Exists
    End Function

    Public Function showError(ByVal msg As String) As DialogResult
        Return MessageBox.Show(msg, Application.ProductName, MessageBoxButtons.OK, MessageBoxIcon.Error)
    End Function
End Module
