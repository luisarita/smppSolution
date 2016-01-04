Imports MySql.Data.MySqlClient
Imports funciones

Public Class ctlTrivia
    Inherits formControl.frmTemplate

#Region " Windows Form Designer generated code "
    Public Sub New()
        MyBase.New()
        InitializeComponent()
    End Sub
    Protected Overloads Overrides Sub Dispose(ByVal disposing As Boolean)
        If disposing Then
            If Not (components Is Nothing) Then
                components.Dispose()
            End If
        End If
        MyBase.Dispose(disposing)
    End Sub
    Private components As System.ComponentModel.IContainer
    Friend WithEvents GroupBox1 As GroupBox
    Friend WithEvents Label1 As Label
    Friend WithEvents dgDatos As DataGrid
    Friend WithEvents dgDatos2 As DataGrid
    Friend WithEvents cbTrivias As ComboBox
    Friend WithEvents cmdPEliminar As Button
    Friend WithEvents cmdPAgregar As Button
    Friend WithEvents cmdOEliminar As Button
    Friend WithEvents cmdOAgregar As Button
    Friend WithEvents cmdREliminar As Button
    Friend WithEvents cmdRAgregar As Button
    Friend WithEvents dgDatos3 As DataGrid
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox
        Me.Label1 = New System.Windows.Forms.Label
        Me.cbTrivias = New System.Windows.Forms.ComboBox
        Me.dgDatos = New System.Windows.Forms.DataGrid
        Me.dgDatos2 = New System.Windows.Forms.DataGrid
        Me.dgDatos3 = New System.Windows.Forms.DataGrid
        Me.cmdPEliminar = New System.Windows.Forms.Button
        Me.cmdPAgregar = New System.Windows.Forms.Button
        Me.cmdOEliminar = New System.Windows.Forms.Button
        Me.cmdOAgregar = New System.Windows.Forms.Button
        Me.cmdREliminar = New System.Windows.Forms.Button
        Me.cmdRAgregar = New System.Windows.Forms.Button
        Me.cmdPanel.SuspendLayout()
        Me.GroupBox1.SuspendLayout()
        CType(Me.dgDatos, System.ComponentModel.ISupportInitialize).BeginInit()
        CType(Me.dgDatos2, System.ComponentModel.ISupportInitialize).BeginInit()
        CType(Me.dgDatos3, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'pbUpper
        '
        Me.pbUpper.Name = "pbUpper"
        '
        'cmdPanel
        '
        Me.cmdPanel.DockPadding.Left = 5
        Me.cmdPanel.DockPadding.Right = 5
        Me.cmdPanel.Location = New System.Drawing.Point(0, 423)
        Me.cmdPanel.Name = "cmdPanel"
        Me.cmdPanel.Size = New System.Drawing.Size(634, 37)
        '
        'cmdCerrar
        '
        Me.cmdCerrar.Location = New System.Drawing.Point(529, 9)
        Me.cmdCerrar.Name = "cmdCerrar"
        '
        'imgLinea
        '
        Me.imgLinea.Name = "imgLinea"
        Me.imgLinea.Size = New System.Drawing.Size(624, 8)
        '
        'GroupBox1
        '
        Me.GroupBox1.Controls.Add(Me.Label1)
        Me.GroupBox1.Controls.Add(Me.cbTrivias)
        Me.GroupBox1.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.GroupBox1.Location = New System.Drawing.Point(3, 56)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(628, 48)
        Me.GroupBox1.TabIndex = 11
        Me.GroupBox1.TabStop = False
        '
        'Label1
        '
        Me.Label1.Location = New System.Drawing.Point(8, 17)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(144, 21)
        Me.Label1.TabIndex = 3
        Me.Label1.Text = "Trivia:"
        Me.Label1.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'cbTrivias
        '
        Me.cbTrivias.BackColor = System.Drawing.Color.White
        Me.cbTrivias.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cbTrivias.Location = New System.Drawing.Point(168, 17)
        Me.cbTrivias.Name = "cbTrivias"
        Me.cbTrivias.Size = New System.Drawing.Size(448, 21)
        Me.cbTrivias.TabIndex = 1
        '
        'dgDatos
        '
        Me.dgDatos.CaptionBackColor = System.Drawing.Color.FromArgb(CType(74, Byte), CType(94, Byte), CType(118, Byte))
        Me.dgDatos.CaptionText = "Preguntas"
        Me.dgDatos.DataMember = ""
        Me.dgDatos.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.dgDatos.LinkColor = System.Drawing.Color.FromArgb(CType(74, Byte), CType(94, Byte), CType(118, Byte))
        Me.dgDatos.Location = New System.Drawing.Point(3, 112)
        Me.dgDatos.Name = "dgDatos"
        Me.dgDatos.SelectionBackColor = System.Drawing.Color.FromArgb(CType(74, Byte), CType(94, Byte), CType(118, Byte))
        Me.dgDatos.Size = New System.Drawing.Size(525, 96)
        Me.dgDatos.TabIndex = 27
        '
        'dgDatos2
        '
        Me.dgDatos2.CaptionBackColor = System.Drawing.Color.FromArgb(CType(74, Byte), CType(94, Byte), CType(118, Byte))
        Me.dgDatos2.CaptionText = "Opciones"
        Me.dgDatos2.DataMember = ""
        Me.dgDatos2.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.dgDatos2.LinkColor = System.Drawing.Color.FromArgb(CType(74, Byte), CType(94, Byte), CType(118, Byte))
        Me.dgDatos2.Location = New System.Drawing.Point(3, 216)
        Me.dgDatos2.Name = "dgDatos2"
        Me.dgDatos2.SelectionBackColor = System.Drawing.Color.FromArgb(CType(74, Byte), CType(94, Byte), CType(118, Byte))
        Me.dgDatos2.Size = New System.Drawing.Size(525, 96)
        Me.dgDatos2.TabIndex = 28
        '
        'dgDatos3
        '
        Me.dgDatos3.CaptionBackColor = System.Drawing.Color.FromArgb(CType(74, Byte), CType(94, Byte), CType(118, Byte))
        Me.dgDatos3.CaptionText = "Resultados"
        Me.dgDatos3.DataMember = ""
        Me.dgDatos3.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.dgDatos3.LinkColor = System.Drawing.Color.FromArgb(CType(74, Byte), CType(94, Byte), CType(118, Byte))
        Me.dgDatos3.Location = New System.Drawing.Point(3, 320)
        Me.dgDatos3.Name = "dgDatos3"
        Me.dgDatos3.SelectionBackColor = System.Drawing.Color.FromArgb(CType(74, Byte), CType(94, Byte), CType(118, Byte))
        Me.dgDatos3.Size = New System.Drawing.Size(525, 96)
        Me.dgDatos3.TabIndex = 29
        '
        'cmdPEliminar
        '
        Me.cmdPEliminar.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdPEliminar.Location = New System.Drawing.Point(531, 144)
        Me.cmdPEliminar.Name = "cmdPEliminar"
        Me.cmdPEliminar.Size = New System.Drawing.Size(100, 24)
        Me.cmdPEliminar.TabIndex = 32
        Me.cmdPEliminar.Text = "&Eliminar"
        '
        'cmdPAgregar
        '
        Me.cmdPAgregar.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdPAgregar.Location = New System.Drawing.Point(531, 112)
        Me.cmdPAgregar.Name = "cmdPAgregar"
        Me.cmdPAgregar.Size = New System.Drawing.Size(100, 24)
        Me.cmdPAgregar.TabIndex = 31
        Me.cmdPAgregar.Text = "&Agregar"
        '
        'cmdOEliminar
        '
        Me.cmdOEliminar.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdOEliminar.Location = New System.Drawing.Point(531, 248)
        Me.cmdOEliminar.Name = "cmdOEliminar"
        Me.cmdOEliminar.Size = New System.Drawing.Size(100, 24)
        Me.cmdOEliminar.TabIndex = 34
        Me.cmdOEliminar.Text = "&Eliminar"
        '
        'cmdOAgregar
        '
        Me.cmdOAgregar.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdOAgregar.Location = New System.Drawing.Point(531, 216)
        Me.cmdOAgregar.Name = "cmdOAgregar"
        Me.cmdOAgregar.Size = New System.Drawing.Size(100, 24)
        Me.cmdOAgregar.TabIndex = 33
        Me.cmdOAgregar.Text = "&Agregar"
        '
        'cmdREliminar
        '
        Me.cmdREliminar.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdREliminar.Location = New System.Drawing.Point(531, 352)
        Me.cmdREliminar.Name = "cmdREliminar"
        Me.cmdREliminar.Size = New System.Drawing.Size(100, 24)
        Me.cmdREliminar.TabIndex = 36
        Me.cmdREliminar.Text = "&Eliminar"
        '
        'cmdRAgregar
        '
        Me.cmdRAgregar.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdRAgregar.Location = New System.Drawing.Point(531, 320)
        Me.cmdRAgregar.Name = "cmdRAgregar"
        Me.cmdRAgregar.Size = New System.Drawing.Size(100, 24)
        Me.cmdRAgregar.TabIndex = 35
        Me.cmdRAgregar.Text = "&Agregar"
        '
        'ctlTrivia
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(6, 14)
        Me.ClientSize = New System.Drawing.Size(634, 460)
        Me.Controls.Add(Me.cmdREliminar)
        Me.Controls.Add(Me.cmdRAgregar)
        Me.Controls.Add(Me.cmdOEliminar)
        Me.Controls.Add(Me.cmdOAgregar)
        Me.Controls.Add(Me.cmdPEliminar)
        Me.Controls.Add(Me.cmdPAgregar)
        Me.Controls.Add(Me.dgDatos3)
        Me.Controls.Add(Me.dgDatos2)
        Me.Controls.Add(Me.dgDatos)
        Me.Controls.Add(Me.GroupBox1)
        Me.Name = "ctlTrivia"
        Me.pTitle = "Control de Trivias"
        Me.Text = "Control de Trivias"
        Me.Controls.SetChildIndex(Me.cmdPanel, 0)
        Me.Controls.SetChildIndex(Me.pbUpper, 0)
        Me.Controls.SetChildIndex(Me.GroupBox1, 0)
        Me.Controls.SetChildIndex(Me.dgDatos, 0)
        Me.Controls.SetChildIndex(Me.dgDatos2, 0)
        Me.Controls.SetChildIndex(Me.dgDatos3, 0)
        Me.Controls.SetChildIndex(Me.cmdPAgregar, 0)
        Me.Controls.SetChildIndex(Me.cmdPEliminar, 0)
        Me.Controls.SetChildIndex(Me.cmdOAgregar, 0)
        Me.Controls.SetChildIndex(Me.cmdOEliminar, 0)
        Me.Controls.SetChildIndex(Me.cmdRAgregar, 0)
        Me.Controls.SetChildIndex(Me.cmdREliminar, 0)
        Me.cmdPanel.ResumeLayout(False)
        Me.GroupBox1.ResumeLayout(False)
        CType(Me.dgDatos, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.dgDatos2, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.dgDatos3, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub
#End Region

    Private vID As Integer
    Private dtaAdpInicial As New MySqlDataAdapter
    Private dataSet As New DataSet

    Private Sub ctlSuscripciones_Load(ByVal sender As Object, ByVal e As EventArgs) Handles MyBase.Load
        fncListboxMySQL.popularMySQL(CObj(cbTrivias), "TRIVIAS", "id", "nombre", CNX)
    End Sub

    Private Sub cbSuscripciones_SelectedIndexChanged(ByVal sender As Object, ByVal e As EventArgs) Handles cbTrivias.SelectedIndexChanged
        cargar()
    End Sub

    Sub cargar()
        vID = valor(cbTrivias)
        If Not connectionOne.State = ConnectionState.Open Then connectionOne.Open()
        If Not dgDatos3.DataSource Is Nothing Then CType(dgDatos3.DataSource, DataTable).Clear()
        If Not dgDatos2.DataSource Is Nothing Then CType(dgDatos2.DataSource, DataTable).Clear()
        If Not dgDatos.DataSource Is Nothing Then CType(dgDatos.DataSource, DataTable).Clear()
        Dim CNX2 As New MySqlConnection(cnxString)

        Dim selectCMD As New MySqlCommand("SELECT id,idPadre,pregunta FROM TRIVIAS_PREGUNTAS WHERE idTrivia=" & vID)
        fncGridDataset.cargar(dtaAdpInicial, dgDatos, "DATOS1", selectCMD, Nothing, Nothing, Nothing, CNX2, dataSet)
        formatear_datos(dgDatos)
        checkOpciones()

        selectCMD = New MySqlCommand("SELECT id,minimo,maximo,mensaje FROM TRIVIAS_RESULTADOS WHERE idTrivia=" & vID)
        fncGridDataset.cargar(dtaAdpInicial, dgDatos3, "DATOS3", selectCMD, Nothing, Nothing, Nothing, CNX2, dataSet)
        formatear_datos3(dgDatos3)
    End Sub
    Shared Sub formatear_datos(ByVal dbgrid As DataGrid)
        Dim tabla As String = CType(dbgrid.DataSource, DataTable).TableName
        Dim tS As DataGridTableStyle
        Dim DataTable As DataTable = CType(dbgrid.DataSource, DataTable)

        DataTable.Columns("id").ColumnMapping = MappingType.Hidden
        'DataTable.Columns("idTrivia").ColumnMapping = MappingType.Hidden
        DataTable.Columns("idPadre").ColumnMapping = MappingType.Hidden
        tS = bindDataSet(dbgrid, tabla)

        With tS.GridColumnStyles(0)
            .NullText = "-"
            .HeaderText = "Pregunta"
            .Alignment = HorizontalAlignment.Left
            .ReadOnly = False
        End With
        fixColumnsWidth(DataTable, dbgrid, tS)

        DataTable.DefaultView.AllowEdit = False
        DataTable.DefaultView.AllowNew = False
        DataTable.DefaultView.AllowDelete = False
    End Sub
    Shared Sub formatear_datos2(ByVal dbgrid As DataGrid)
        Dim tabla As String = CType(dbgrid.DataSource, DataTable).TableName
        Dim tS As DataGridTableStyle
        Dim DataTable As DataTable = CType(dbgrid.DataSource, DataTable)

        DataTable.Columns("id").ColumnMapping = MappingType.Hidden
        DataTable.Columns("idPregunta").ColumnMapping = MappingType.Hidden
        tS = bindDataSet(dbgrid, tabla)

        With tS.GridColumnStyles(0)
            .NullText = "-"
            .HeaderText = "Opción"
            .Alignment = HorizontalAlignment.Left
            .ReadOnly = False
        End With
        With tS.GridColumnStyles(1)
            .NullText = "-"
            .HeaderText = "Mensaje"
            .Alignment = HorizontalAlignment.Left
            .ReadOnly = False
        End With
        With tS.GridColumnStyles(2)
            .NullText = "-"
            .HeaderText = "Valor"
            .Alignment = HorizontalAlignment.Left
            .ReadOnly = False
        End With
        fixColumnsWidth(DataTable, dbgrid, tS)

        DataTable.DefaultView.AllowEdit = False
        DataTable.DefaultView.AllowNew = False
        DataTable.DefaultView.AllowDelete = False
    End Sub
    Shared Sub formatear_datos3(ByVal dbgrid As DataGrid)
        Dim tabla As String = CType(dbgrid.DataSource, DataTable).TableName
        Dim tS As DataGridTableStyle
        Dim DataTable As DataTable = CType(dbgrid.DataSource, DataTable)

        DataTable.Columns("id").ColumnMapping = MappingType.Hidden
        tS = bindDataSet(dbgrid, tabla)

        With tS.GridColumnStyles(0)
            .NullText = "-"
            .HeaderText = "Minimo"
            .Alignment = HorizontalAlignment.Left
            .ReadOnly = False
        End With
        With tS.GridColumnStyles(1)
            .NullText = "-"
            .HeaderText = "Maximo"
            .Alignment = HorizontalAlignment.Left
        End With
        With tS.GridColumnStyles(2)
            .NullText = "-"
            .HeaderText = "Mensaje"
            .Alignment = HorizontalAlignment.Left
        End With

        fixColumnsWidth(DataTable, dbgrid, tS)

        DataTable.DefaultView.AllowEdit = False
        DataTable.DefaultView.AllowNew = False
        DataTable.DefaultView.AllowDelete = False
    End Sub

    Private Sub dgDatos_DoubleClick(ByVal sender As Object, ByVal e As EventArgs) Handles dgDatos.DoubleClick
        Dim row As DataRow = row_actual(dgDatos)
        If row Is Nothing Then Exit Sub

        Dim cliente As New agrTrvPregunta(row("id"))
        cliente.ShowDialog()
        cargar()
    End Sub
    Private Sub dgDatos2_DoubleClick(ByVal sender As Object, ByVal e As EventArgs) Handles dgDatos2.DoubleClick
        Dim row As DataRow = row_actual(dgDatos2)
        If row Is Nothing Then Exit Sub

        Dim cliente As New agrTrvOpcion(row("id"))
        cliente.ShowDialog()
        cargar()
    End Sub
    Private Sub dgDatos3_DoubleClick(ByVal sender As Object, ByVal e As EventArgs) Handles dgDatos3.DoubleClick
        Dim row As DataRow = row_actual(dgDatos3)
        If row Is Nothing Then Exit Sub

        Dim cliente As New agrTrvResultado(row("id"))
        cliente.ShowDialog()
        cargar()
    End Sub

    Private Sub cmdPEliminar_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdPEliminar.Click
        If MsgBox("¿Seguro que desea eliminar esta pregunta?", MsgBoxStyle.YesNo) = MsgBoxResult.Yes Then
            Dim row As DataRow = row_actual(dgDatos)
            If row Is Nothing Then
                MsgBox("Seleccione una pregunta")
                Exit Sub
            End If
            If CNX.State <> ConnectionState.Open Then CNX.Open()
            Dim deleteCMD As New MySqlCommand("DELETE FROM TRIVIAS_PREGUNTAS WHERE id=" & row("id"), CNX)
            Try
                deleteCMD.ExecuteNonQuery()
                recalcularOrden()
                recalcularResultados()
                cargar()
            Catch ex As MySqlException
                MsgBox("Error al ejecutar comando: " & ex.Message)
            End Try
        End If
    End Sub
    Private Sub cmdOEliminar_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdOEliminar.Click
        If MsgBox("¿Seguro que desea eliminar esta opcion?", MsgBoxStyle.YesNo) = MsgBoxResult.Yes Then
            Dim row As DataRow = row_actual(dgDatos2)
            If row Is Nothing Then
                MsgBox("Seleccione una opcion")
                Exit Sub
            End If
            If CNX.State <> ConnectionState.Open Then CNX.Open()
            Dim deleteCMD As New MySqlCommand("DELETE FROM TRIVIAS_OPCIONES WHERE id=" & row("id"), CNX)
            Try
                deleteCMD.ExecuteNonQuery()
                recalcularResultados()
                cargar()
            Catch ex As MySqlException
                MsgBox("Error al ejecutar comando: " & ex.Message)
            End Try
        End If
    End Sub
    Private Sub cmdREliminar_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdREliminar.Click
        If MsgBox("¿Seguro que desea eliminar este resultado?", MsgBoxStyle.YesNo) = MsgBoxResult.Yes Then
            Dim row As DataRow = row_actual(dgDatos3)
            If row Is Nothing Then
                MsgBox("Seleccione un resultado")
                Exit Sub
            End If
            If CNX.State <> ConnectionState.Open Then CNX.Open()
            Dim deleteCMD As New MySqlCommand("DELETE FROM TRIVIAS_RESULTADOS WHERE id=" & row("id"), CNX)
            Try
                deleteCMD.ExecuteNonQuery()
                recalcularResultados()
                cargar()
            Catch ex As MySqlException
                MsgBox("Error al ejecutar comando: " & ex.Message)
            End Try
        End If
    End Sub

    Private Sub cmdPAgregar_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdPAgregar.Click
        Dim cliente As New agrTrvPregunta(vID, 0)
        'cliente.MdiParent = MdiParent
        cliente.ShowDialog()
        cargar()
    End Sub
    Private Sub cmdOAgregar_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdOAgregar.Click
        Dim row As DataRow = row_actual(dgDatos)
        If row Is Nothing Then
            MsgBox("Seleccione un pregunta")
            Exit Sub
        End If

        Dim cliente As New agrTrvOpcion(row("id"), 0)
        'cliente.MdiParent = MdiParent
        cliente.ShowDialog()
        cargar()
    End Sub
    Private Sub cmdRAgregar_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdRAgregar.Click
        Dim cliente As New agrTrvResultado(vID, 0)
        'cliente.MdiParent = MdiParent
        cliente.ShowDialog()
        cargar()
    End Sub

    Private Sub dgDatos_CurrentCellChanged(ByVal sender As Object, ByVal e As EventArgs) Handles dgDatos.CurrentCellChanged
        checkOpciones()
    End Sub
    Sub checkOpciones()
        Dim row As DataRow = row_actual(dgDatos)
        If row Is Nothing Then
            MsgBox("Seleccione un pregunta")
            Exit Sub
        End If

        Dim CNX2 As New MySqlConnection(cnxString)
        If Not dgDatos2.DataSource Is Nothing Then CType(dgDatos2.DataSource, DataTable).Clear()

        Dim selectCMD As New MySqlCommand("SELECT * FROM TRIVIAS_OPCIONES WHERE idPregunta=" & row("id"))
        fncGridDataset.cargar(dtaAdpInicial, dgDatos2, "DATOS2", selectCMD, Nothing, Nothing, Nothing, CNX2, dataSet)
        formatear_datos2(dgDatos2)
    End Sub

    Function recalcularResultados() As Boolean
        Dim cantidad As Integer = 0
        Dim puntos As Integer = 0
        Try
            cantidad = New MySqlCommand("SELECT COUNT(id) AS id FROM TRIVIAS_RESULTADOS WHERE idTrivia=" & vID, CNX).ExecuteScalar
            If cantidad = 0 Then Exit Function
        Catch ex As MySqlException
            Exit Function
        End Try
        Try
            puntos = New MySqlCommand(String.Format("SELECT SUM(valor) AS puntos FROM (SELECT MAX(valor) AS valor FROM TRIVIAS_OPCIONES INNER JOIN TRIVIAS_PREGUNTAS ON TRIVIAS_PREGUNTAS.id = TRIVIAS_OPCIONES.idPregunta WHERE idTrivia={0} GROUP BY idPregunta) AS DRVTBL", vID), CNX).ExecuteScalar
        Catch ex As Exception
            MsgBox("No existen opciones para esta trivia. No se puede recalcular puntaje de resultados")
            Return False
        End Try
        Dim minimo As Integer = 0
        Dim maximo As Integer = 0
        Dim dr As MySqlDataReader = New MySqlCommand("SELECT id FROM TRIVIAS_RESULTADOS WHERE idTrivia=" & vID, CNX).ExecuteReader
        Dim CNX2 As New MySqlConnection(cnxString)
        CNX2.Open()
        While dr.Read
            maximo = maximo + puntos / cantidad
            Dim CMD1 As New MySqlCommand(String.Format("UPDATE TRIVIAS_RESULTADOS SET minimo={0}, maximo={1} WHERE id={2}", minimo, maximo, dr!id), CNX2)
            Try
                CMD1.ExecuteNonQuery()
                minimo = maximo + 1
            Catch ex As MySqlException
                MsgBox("Error al ejecutar comando: " & ex.Message)
            End Try
        End While
        maximo = puntos
        dr.Close()
        CNX2.Close()
    End Function
    Sub recalcularOrden()
        If CNX.State <> ConnectionState.Open Then CNX.Open()

        Dim updateCMD As New MySqlCommand("UPDATE TRIVIAS_PREGUNTAS SET idPadre = NULL WHERE idTrivia=" & vID, CNX)
        Try
            updateCMD.ExecuteNonQuery()
        Catch ex As MySqlException
            MsgBox("Error al ejecutar comando: " & ex.Message)
            Exit Sub
        End Try

        Dim id As Integer = -1

        Dim CNX2 As New MySqlConnection(cnxString)
        CNX2.Open()
        Dim dr As MySqlDataReader = New MySqlCommand(String.Format("SELECT id FROM TRIVIAS_PREGUNTAS WHERE idTrivia = {0} ORDER BY id", vID), CNX2).ExecuteReader
        While dr.Read
            If id <> -1 Then
                Try
                    updateCMD.CommandText = String.Format("UPDATE TRIVIAS_PREGUNTAS SET idPadre = {0}  WHERE id={1}", id, dr!id)
                    updateCMD.ExecuteNonQuery()
                Catch ex As MySqlException
                    MsgBox("Error al ejecutar comando: " & ex.Message)
                    Exit Sub
                End Try
            End If
            id = dr!id
        End While
        dr.Close()
        CNX2.Close()
    End Sub
End Class