Imports MySql.Data.MySqlClient
Imports funciones

Public Class consAgrupador
    Private dtaAdpInicial As New MySqlDataAdapter
    Private dataSet As New DataSet

    Private Sub dgDatos_DoubleClick(ByVal sender As Object, ByVal e As System.EventArgs) Handles dgDatos.DoubleClick
        Dim row As DataRow = row_actual(dgDatos)
        If row Is Nothing Then Exit Sub

        Dim a As New agrAgrupadores(row("id"))
        a.Owner = Me
        a.MdiParent = MdiParent
        a.Show()
    End Sub

    Private Sub cons_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        cargar()
    End Sub
    Sub cargar()
        If Not dgDatos.DataSource Is Nothing Then CType(dgDatos.DataSource, DataTable).Clear()
        Dim selectCMD As New MySqlCommand("SELECT id, clave FROM agrupadores WHERE estado=1")
        Dim CNX2 As New MySqlConnection(cnxString)
        fncGridDataset.cargar(dtaAdpInicial, dgDatos, "DATOS", selectCMD, Nothing, Nothing, Nothing, CNX2, dataSet)
        formatear_datos()
    End Sub
    Sub formatear_datos()
        Dim dbgrid As DataGrid = dgDatos
        Dim tabla As String = CType(dbgrid.DataSource, DataTable).TableName
        Dim tS As DataGridTableStyle
        Dim DataTable As DataTable = CType(dbgrid.DataSource, DataTable)

        tS = bindDataSet(dbgrid, tabla)

        With tS.GridColumnStyles(1)
            .NullText = "-"
            .HeaderText = "Código"
            .Alignment = HorizontalAlignment.Left
        End With

        With tS.GridColumnStyles(1)
            .NullText = "-"
            .HeaderText = "Clave"
            .Alignment = HorizontalAlignment.Left
        End With

        fixColumnsWidth(DataTable, dbgrid, tS)

        DataTable.DefaultView.AllowEdit = False
        DataTable.DefaultView.AllowNew = False
        DataTable.DefaultView.AllowDelete = False
    End Sub

    Private Sub cmdEliminar_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdEliminar.Click
        If MsgBox("¿Seguro que desea eliminar esta actividad?", MsgBoxStyle.YesNo) = MsgBoxResult.Yes Then
            Dim row As DataRow = row_actual(dgDatos)
            If row Is Nothing Then
                MsgBox("Seleccione una actividad")
                Exit Sub
            End If
            If CNX.State <> ConnectionState.Open Then CNX.Open()
            Dim deleteCMD As New MySqlCommand("UPDATE agrupadores SET estado=0 WHERE id=" & row("id"), CNX)
            Try
                deleteCMD.ExecuteNonQuery()
                cargar()
            Catch ex As MySqlException
                MsgBox("Error al ejecutar comando: " & ex.Message)
            End Try
        End If
    End Sub
End Class