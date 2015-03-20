Imports System.Data.OleDb
Imports System.Data.Odbc
Imports vnframework

Public Module fncGridDataSetOle
    Public Sub cargar_grid(ByRef dataAdp As OleDbDataAdapter, ByVal dbgrid As DataGrid, ByVal table As String, ByVal selectCMD As OleDbCommand, ByVal insertCMD As OleDbCommand, ByVal deleteCMD As OleDbCommand, ByVal updateCMD As OleDbCommand, ByVal STRCONN As String, ByVal pDataSet As DataSet)
        Dim CNX As New OleDbConnection(STRCONN)
        CNX.Open()
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
        Catch thiserror As OleDbException
            showError(thiserror.Message)
        End Try
        CNX.Close()

        dbgrid.DataSource = pDataSet.Tables(table)
    End Sub
    Public Sub cargar_grid(ByRef dataAdp As OdbcDataAdapter, ByVal dbgrid As DataGrid, ByVal table As String, ByVal selectCMD As OdbcCommand, ByVal insertCMD As OdbcCommand, ByVal deleteCMD As OdbcCommand, ByVal updateCMD As OdbcCommand, ByVal CNX As OdbcConnection, ByVal pDataSet As DataSet)
        If CNX.State <> ConnectionState.Open Then CNX.Open()
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
        Catch thiserror As OleDbException
            showError(thiserror.Message)
        End Try
        CNX.Close()

        dbgrid.DataSource = pDataSet.Tables(table)
    End Sub
    Public Function row_actual(ByVal dbgrid As DataGrid) As DataRow
        Try
            Dim xCM As CurrencyManager = CType(dbgrid.BindingContext(dbgrid.DataSource, dbgrid.DataMember), CurrencyManager)
            Dim xDRV As DataRowView = CType(xCM.Current, DataRowView)
            Return xDRV.Row
        Catch
            Return Nothing
        End Try
    End Function
    Public Function actualizar(ByVal dataAdp As OleDbDataAdapter, ByVal table As String, ByVal pDataSet As DataSet) As Boolean
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
    Public Function actualizar(ByVal dataAdp As OdbcDataAdapter, ByVal table As String, ByVal pDataSet As DataSet) As Boolean
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
    Public Function actualizado(ByVal pDataSet As DataSet) As Boolean
        Return Not pDataSet.HasChanges
    End Function
End Module
Public Module fncGridWidth
    Public Function fixColumnsWidth(ByVal dataTable As DataTable, ByVal DBGrid As DataGrid) As DataGridTableStyle
        Dim tableStyle As New DataGridTableStyle
        tableStyle.MappingName = dataTable.TableName
        DBGrid.TableStyles.Clear()
        DBGrid.TableStyles.Add(tableStyle)
        fixColumnsWidth(dataTable, DBGrid, tableStyle)
        Return tableStyle
    End Function
    Public Function fixColumnsWidth(ByVal dataTable As DataTable, ByVal DBGrid As DataGrid, ByVal tableStyle As DataGridTableStyle)
        Dim i As Integer, j As Integer = 0
        Dim numRows As Integer = dataTable.Rows.Count
        Dim totalWidth As Integer = 0, difWidth As Integer, scrollWidth = 60
        For i = 0 To dataTable.Columns.Count - 1
            If dataTable.Columns(i).ColumnMapping <> MappingType.Hidden Then
                totalWidth += fixColumnWidth(j, numRows, dataTable.Columns(i).ColumnName, DBGrid, tableStyle)
                j += 1
            Else
            End If
        Next i
        If totalWidth < DBGrid.Width - scrollWidth Then
            difWidth = CInt((DBGrid.Width - scrollWidth - totalWidth))
            j = 0
            For i = 0 To dataTable.Columns.Count - 1
                If dataTable.Columns(i).ColumnMapping <> MappingType.Hidden Then
                    With tableStyle.GridColumnStyles(j)
                        .Width += .Width / totalWidth * difWidth
                    End With
                    j += 1
                End If
            Next i
        End If
    End Function
    Public Function fixColumnWidth(ByVal col As Integer, ByVal numrows As Integer, ByVal columnName As String, ByVal DBGrid As DataGrid, ByVal tableStyle As DataGridTableStyle) As Integer
        Dim width As Single = 0
        Dim g As Graphics = Graphics.FromHwnd(DBGrid.Handle)
        Dim sf As StringFormat = New StringFormat(StringFormat.GenericTypographic)
        Dim size As SizeF
        size = g.MeasureString(columnName, DBGrid.Font, 500, sf)
        width = size.Width
        Dim i As Integer
        For i = 0 To numrows - 1
            Try
                size = g.MeasureString(DBGrid(i, col).ToString, DBGrid.Font, 500, sf)
                If (size.Width > width) Then
                    width = size.Width
                End If
            Catch
            End Try
        Next i
        g.Dispose()
        tableStyle.GridColumnStyles(col).Width = CInt(width + 20)
        Return CInt(width + 20)
    End Function
End Module

Public Module fncGrid
    Public Function bindDataSet(ByVal dbgrid As DataGrid, ByVal tableName As String) As DataGridTableStyle
        Dim tableStyle As New DataGridTableStyle
        tableStyle.MappingName = tableName
        dbgrid.TableStyles.Clear()
        dbgrid.TableStyles.Add(tableStyle)
        Return tableStyle
    End Function
    Public Function bindDataSet(ByVal dataTable As dataTable, ByVal dbgrid As DataGrid) As DataGridTableStyle
        Dim tableStyle As DataGridTableStyle
        tableStyle = New DataGridTableStyle
        tableStyle.MappingName = dataTable.TableName
        dbgrid.TableStyles.Clear()
        dbgrid.TableStyles.Add(tableStyle)
        dbgrid.DataSource = dataTable
        Return tableStyle
    End Function

    'Private dataAdapter As New OleDb.OleDbDataAdapter
    'Private dataTable As New dataTable
    'Private dataSet As New dataSet
    ''Private objCNX As New ADODB.Connection

    'Public Sub allowNew(Optional ByVal table As String = "", Optional ByVal value As Boolean = True)
    '    dataTable = getDataTable(table)
    'End Sub
    'Public Function getDataSet() As dataSet
    '    Return dataSet
    'End Function
    'Public Function getDataTable(Optional ByVal table As String = "") As dataTable
    '    If Len(table) = 0 Then Return dataTable
    '    Return dataSet.Tables(table)
    'End Function
    ''Public Sub setConnection(ByVal STRCONN As String)
    ''    objCNX.ConnectionString = STRCONN
    ''End Sub
    'Public Sub loadGrid(ByVal dbgrid As DataGrid, ByVal tabla As String, ByVal comando As String)
    '    If dataAdapter.TableMappings.IndexOf(tabla & "Mapping") <> -1 Then dataAdapter.TableMappings.RemoveAt(dataAdapter.TableMappings.IndexOf(tabla & "Mapping"))
    '    dataAdapter.TableMappings.Add(New System.Data.Common.DataTableMapping(tabla & "Mapping", tabla))
    '    Dim cmd As New System.Data.OleDb.OleDbCommand
    '    cmd.CommandText = comando
    '    cmd.Connection = New System.Data.OleDb.OleDbConnection
    '    cmd.Connection.ConnectionString = objCNX.ConnectionString
    '    dataAdapter.SelectCommand = cmd
    '    Try
    '        dataSet.Tables(tabla).Clear()
    '    Catch
    '    End Try
    '    Try
    '        dataAdapter.Fill(dataSet, tabla & "Mapping")
    '        dbgrid.SetDataBinding(dataSet, tabla)
    '    Catch
    '        MsgBox("Error en llenado de DataAdapter/Dataset")
    '    End Try
    '    dataTable = dataSet.Tables(tabla)
    'End Sub
    'Sub assignKeyPressFunction(ByVal dbgrid As DataGrid, ByVal myFunction As System.Delegate, ByVal ParamArray parms() As Object)
    '    With dbgrid
    '        Dim ts As DataGridTableStyle = .TableStyles(0)
    '        Dim columnStyle As DataGridColumnStyle
    '        Dim txtBoxColumn As DataGridTextBoxColumn
    '        Dim txtBox As DataGridTextBox
    '        For Each columnStyle In ts.GridColumnStyles
    '            txtBoxColumn = columnStyle
    '            txtBox = CType(txtBoxColumn.TextBox, DataGridTextBox)
    '            AddHandler txtBox.KeyPress, myFunction
    '        Next
    '    End With
    'End Sub   
End Module