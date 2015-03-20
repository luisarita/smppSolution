Imports MySql.Data.MySqlClient
Imports funciones

Public Class consRifas
    Inherits formControl.frmTemplate

#Region " Windows Form Designer generated code "

    Public Sub New()
        MyBase.New()
        InitializeComponent()
    End Sub

    'Form overrides dispose to clean up the component list.
    Protected Overloads Overrides Sub Dispose(ByVal disposing As Boolean)
        If disposing Then
            If Not (components Is Nothing) Then
                components.Dispose()
            End If
        End If
        MyBase.Dispose(disposing)
    End Sub

    'Required by the Windows Form Designer
    Private components As System.ComponentModel.IContainer

    'NOTE: The following procedure is required by the Windows Form Designer
    'It can be modified using the Windows Form Designer.  
    'Do not modify it using the code editor.
    Friend WithEvents dgDatos As System.Windows.Forms.DataGrid
    Friend WithEvents dgDatos2 As System.Windows.Forms.DataGrid
    Friend WithEvents cmdEliminar As System.Windows.Forms.Button
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.dgDatos = New System.Windows.Forms.DataGrid()
        Me.cmdEliminar = New System.Windows.Forms.Button()
        Me.dgDatos2 = New System.Windows.Forms.DataGrid()
        CType(Me.pbUpper, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.cmdPanel.SuspendLayout()
        CType(Me.imgLinea, System.ComponentModel.ISupportInitialize).BeginInit()
        CType(Me.dgDatos, System.ComponentModel.ISupportInitialize).BeginInit()
        CType(Me.dgDatos2, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'cmdPanel
        '
        Me.cmdPanel.Controls.Add(Me.cmdEliminar)
        Me.cmdPanel.Location = New System.Drawing.Point(0, 447)
        Me.cmdPanel.Size = New System.Drawing.Size(638, 37)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCerrar, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.imgLinea, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdEliminar, 0)
        '
        'cmdCerrar
        '
        Me.cmdCerrar.Location = New System.Drawing.Point(533, 9)
        '
        'imgLinea
        '
        Me.imgLinea.Size = New System.Drawing.Size(628, 8)
        '
        'dgDatos
        '
        Me.dgDatos.CaptionBackColor = System.Drawing.Color.FromArgb(CType(CType(74, Byte), Integer), CType(CType(94, Byte), Integer), CType(CType(118, Byte), Integer))
        Me.dgDatos.CaptionText = "Rifas Activas"
        Me.dgDatos.DataMember = ""
        Me.dgDatos.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.dgDatos.LinkColor = System.Drawing.Color.FromArgb(CType(CType(74, Byte), Integer), CType(CType(94, Byte), Integer), CType(CType(118, Byte), Integer))
        Me.dgDatos.Location = New System.Drawing.Point(3, 62)
        Me.dgDatos.Name = "dgDatos"
        Me.dgDatos.SelectionBackColor = System.Drawing.Color.FromArgb(CType(CType(74, Byte), Integer), CType(CType(94, Byte), Integer), CType(CType(118, Byte), Integer))
        Me.dgDatos.Size = New System.Drawing.Size(628, 186)
        Me.dgDatos.TabIndex = 28
        '
        'cmdEliminar
        '
        Me.cmdEliminar.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdEliminar.Location = New System.Drawing.Point(424, 9)
        Me.cmdEliminar.Name = "cmdEliminar"
        Me.cmdEliminar.Size = New System.Drawing.Size(100, 24)
        Me.cmdEliminar.TabIndex = 14
        Me.cmdEliminar.Text = "Eliminar"
        '
        'dgDatos2
        '
        Me.dgDatos2.CaptionBackColor = System.Drawing.Color.FromArgb(CType(CType(74, Byte), Integer), CType(CType(94, Byte), Integer), CType(CType(118, Byte), Integer))
        Me.dgDatos2.CaptionText = "Rifas Inactivas"
        Me.dgDatos2.DataMember = ""
        Me.dgDatos2.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.dgDatos2.LinkColor = System.Drawing.Color.FromArgb(CType(CType(74, Byte), Integer), CType(CType(94, Byte), Integer), CType(CType(118, Byte), Integer))
        Me.dgDatos2.Location = New System.Drawing.Point(3, 254)
        Me.dgDatos2.Name = "dgDatos2"
        Me.dgDatos2.SelectionBackColor = System.Drawing.Color.FromArgb(CType(CType(74, Byte), Integer), CType(CType(94, Byte), Integer), CType(CType(118, Byte), Integer))
        Me.dgDatos2.Size = New System.Drawing.Size(628, 186)
        Me.dgDatos2.TabIndex = 29
        '
        'consRifas
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(6, 14)
        Me.ClientSize = New System.Drawing.Size(638, 484)
        Me.Controls.Add(Me.dgDatos2)
        Me.Controls.Add(Me.dgDatos)
        Me.Name = "consRifas"
        Me.pTitle = "Consultar Rifas"
        Me.Text = "Consultar Rifas"
        Me.Controls.SetChildIndex(Me.cmdPanel, 0)
        Me.Controls.SetChildIndex(Me.pbUpper, 0)
        Me.Controls.SetChildIndex(Me.dgDatos, 0)
        Me.Controls.SetChildIndex(Me.dgDatos2, 0)
        CType(Me.pbUpper, System.ComponentModel.ISupportInitialize).EndInit()
        Me.cmdPanel.ResumeLayout(False)
        CType(Me.imgLinea, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.dgDatos, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.dgDatos2, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub

#End Region
    Private dtaAdpInicial As New MySQLDataAdapter
    Private dataSet As New dataSet
    Private dtaAdpInicial2 As New MySqlDataAdapter
    Private dataSet2 As New DataSet

    Private Sub dgDatos_DoubleClick(ByVal sender As Object, ByVal e As System.EventArgs) Handles dgDatos.DoubleClick, dgDatos2.DoubleClick
        Dim row As DataRow = row_actual(CType(sender, DataGrid))
        If row Is Nothing Then Exit Sub

        Dim a As New agrRifa(row("id"))
        a.Owner = Me
        a.MdiParent = MdiParent
        a.Show()
    End Sub

    Private Sub consRifas_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        cargar()
    End Sub
    Sub cargar()
        With dgDatos
            If Not .DataSource Is Nothing Then CType(.DataSource, DataTable).Clear()
            Dim selectCMD As New MySqlCommand("SELECT id, nombre, numero FROM rifas WHERE estado=1")
            Dim CNX2 As New MySqlConnection(cnxString)
            fncGridDataset.cargar(dtaAdpInicial, dgDatos, "DATOS", selectCMD, Nothing, Nothing, Nothing, CNX2, dataSet)
            formatear_datos(dgDatos)
        End With

        With dgDatos2
            If Not .DataSource Is Nothing Then CType(.DataSource, DataTable).Clear()
            Dim selectCMD As New MySqlCommand("SELECT id, nombre, numero FROM rifas WHERE estado=0")
            Dim CNX2 As New MySqlConnection(cnxString)
            fncGridDataset.cargar(dtaAdpInicial2, dgDatos2, "DATOS", selectCMD, Nothing, Nothing, Nothing, CNX2, dataSet2)
            formatear_datos(dgDatos2)
        End With
    End Sub
    Sub formatear_datos(dbgrid As DataGrid)
        Dim tabla As String = CType(dbgrid.DataSource, DataTable).TableName
        Dim tS As DataGridTableStyle
        Dim DataTable As DataTable = CType(dbgrid.DataSource, DataTable)

        'DataTable.Columns("id").ColumnMapping = MappingType.Hidden
        'DataTable.Columns("cantidad_ganadores").ColumnMapping = MappingType.Hidden
        'DataTable.Columns("mensaje_ganador").ColumnMapping = MappingType.Hidden
        'DataTable.Columns("mensaje_participante").ColumnMapping = MappingType.Hidden
        'DataTable.Columns("estado").ColumnMapping = MappingType.Hidden
        'DataTable.Columns("usuario").ColumnMapping = MappingType.Hidden
        'DataTable.Columns("clave").ColumnMapping = MappingType.Hidden
        tS = bindDataSet(dbgrid, tabla)

        With tS.GridColumnStyles(0)
            .NullText = "-"
            .HeaderText = "Código"
            .Alignment = HorizontalAlignment.Left
        End With
        With tS.GridColumnStyles(1)
            .NullText = "-"
            .HeaderText = "Nombre"
            .Alignment = HorizontalAlignment.Left
        End With
        With tS.GridColumnStyles(2)
            .NullText = "-"
            .HeaderText = "Número"
            .Alignment = HorizontalAlignment.Center
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
                showError("Debe seleccionar una actividad")
                Exit Sub
            End If
            If CNX.State <> ConnectionState.Open Then CNX.Open()
            Dim deleteCMD As New MySqlCommand("UPDATE rifas SET estado=0 WHERE id=" & row("id"), CNX)
            Try
                deleteCMD.ExecuteNonQuery()
                cargar()
            Catch ex As MySqlException
                showError("Error al ejecutar comando: " & ex.Message)
            End Try
        End If
    End Sub
End Class
