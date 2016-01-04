Imports MySql.Data.MySqlClient
Class fncGridDataset
    Public Shared Sub cargar(ByRef dataAdp As MySqlDataAdapter, ByVal dbgrid As DataGrid, ByVal table As String, ByVal selectCMD As MySqlCommand, ByVal insertCMD As MySqlCommand, ByVal deleteCMD As MySqlCommand, ByVal updateCMD As MySqlCommand, ByVal CNX As MySqlConnection, ByVal pDataSet As DataSet)
        If Not selectCMD Is Nothing Then
            dataAdp.SelectCommand = selectCMD
            If dataAdp.SelectCommand.Connection Is Nothing Then dataAdp.SelectCommand.Connection = CNX
        End If
        If Not insertCMD Is Nothing Then
            dataAdp.InsertCommand = insertCMD
            If dataAdp.InsertCommand.Connection Is Nothing Then dataAdp.InsertCommand.Connection = CNX
        End If
        If Not updateCMD Is Nothing Then
            dataAdp.UpdateCommand = updateCMD
            If dataAdp.UpdateCommand.Connection Is Nothing Then dataAdp.UpdateCommand.Connection = CNX
        End If
        If Not deleteCMD Is Nothing Then
            dataAdp.DeleteCommand = deleteCMD
            If dataAdp.DeleteCommand.Connection Is Nothing Then dataAdp.DeleteCommand.Connection = CNX
        End If
        Try
            dataAdp.Fill(pDataSet, table)
        Catch thiserror As MySqlException
            showError(thiserror.Message)
        End Try

        dbgrid.DataSource = pDataSet.Tables(table)
    End Sub
    Public Shared Function actualizar(ByVal dataAdp As MySqlDataAdapter, ByVal table As String, ByVal pDataSet As DataSet) As Boolean
        Try
            dataAdp.Update(pDataSet, table)
            pDataSet.Tables(table).Clear()
            dataAdp.Fill(pDataSet, table)
            Return True
        Catch thiserror As Exception
            showError(thiserror.Message)
            Return False
        End Try
    End Function

    Private Sub New()

    End Sub
End Class