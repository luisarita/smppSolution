Imports MySql.Data.MySqlClient
Imports funciones

Public Class consEncuestas
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
    Friend WithEvents dgDatos As System.Windows.Forms.DataGrid
    Friend WithEvents cmdEliminar As System.Windows.Forms.Button
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.dgDatos = New System.Windows.Forms.DataGrid
        Me.cmdEliminar = New System.Windows.Forms.Button
        Me.cmdPanel.SuspendLayout()
        CType(Me.dgDatos, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'pbUpper
        '
        Me.pbUpper.Name = "pbUpper"
        '
        'cmdPanel
        '
        Me.cmdPanel.Controls.Add(Me.cmdEliminar)
        Me.cmdPanel.DockPadding.Left = 5
        Me.cmdPanel.DockPadding.Right = 5
        Me.cmdPanel.Location = New System.Drawing.Point(0, 255)
        Me.cmdPanel.Name = "cmdPanel"
        Me.cmdPanel.Size = New System.Drawing.Size(634, 37)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCerrar, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.imgLinea, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdEliminar, 0)
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
        'dgDatos
        '
        Me.dgDatos.CaptionBackColor = System.Drawing.Color.FromArgb(CType(74, Byte), CType(94, Byte), CType(118, Byte))
        Me.dgDatos.CaptionText = "Encuestas Existentes"
        Me.dgDatos.DataMember = ""
        Me.dgDatos.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.dgDatos.LinkColor = System.Drawing.Color.FromArgb(CType(74, Byte), CType(94, Byte), CType(118, Byte))
        Me.dgDatos.Location = New System.Drawing.Point(3, 62)
        Me.dgDatos.Name = "dgDatos"
        Me.dgDatos.SelectionBackColor = System.Drawing.Color.FromArgb(CType(74, Byte), CType(94, Byte), CType(118, Byte))
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
        'consEncuestas
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(6, 14)
        Me.ClientSize = New System.Drawing.Size(634, 292)
        Me.Controls.Add(Me.dgDatos)
        Me.Name = "consEncuestas"
        Me.pTitle = "Consultar Encuestas"
        Me.Text = "Consultar Encuestas"
        Me.Controls.SetChildIndex(Me.cmdPanel, 0)
        Me.Controls.SetChildIndex(Me.pbUpper, 0)
        Me.Controls.SetChildIndex(Me.dgDatos, 0)
        Me.cmdPanel.ResumeLayout(False)
        CType(Me.dgDatos, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub
#End Region
    Private dtaAdpInicial As New MySqlDataAdapter
    Private dataSet As New DataSet

    Private Sub dgDatos_DoubleClick(ByVal sender As Object, ByVal e As System.EventArgs) Handles dgDatos.DoubleClick
        Dim row As DataRow = row_actual(dgDatos)
        If row Is Nothing Then Exit Sub

        Dim a As New agrEncuesta(row("id"))
        a.Owner = Me
        a.MdiParent = MdiParent
        a.Show()
    End Sub
    Private Sub consEncuestas_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        cargar()
    End Sub
    Sub cargar()
        If Not dgDatos.DataSource Is Nothing Then CType(dgDatos.DataSource, DataTable).Clear()
        Dim selectCMD As New MySqlCommand("SELECT id, nombre, pregunta, numero FROM encuestas")
        Dim CNX2 As New MySqlConnection(cnxString)
        fncGridDataset.cargar(dtaAdpInicial, dgDatos, "DATOS", selectCMD, Nothing, Nothing, Nothing, CNX2, dataSet)
        formatear_datos()
    End Sub
    Sub formatear_datos()
        Dim dbgrid As DataGrid = dgDatos
        Dim tabla As String = CType(dbgrid.DataSource, DataTable).TableName
        Dim tS As DataGridTableStyle
        Dim DataTable As DataTable = CType(dbgrid.DataSource, DataTable)

        'DataTable.Columns("id").ColumnMapping = MappingType.Hidden
        tS = bindDataSet(dbgrid, tabla)

        With tS.GridColumnStyles(0)
            .NullText = "-"
            .HeaderText = "C�digo"
            .Alignment = HorizontalAlignment.Left
        End With
        With tS.GridColumnStyles(1)
            .NullText = "-"
            .HeaderText = "Nombre"
            .Alignment = HorizontalAlignment.Left
        End With
        With tS.GridColumnStyles(2)
            .NullText = "-"
            .HeaderText = "Pregunta"
            .Alignment = HorizontalAlignment.Left
        End With
        With tS.GridColumnStyles(3)
            .NullText = "-"
            .HeaderText = "N�mero"
            .Alignment = HorizontalAlignment.Center
        End With

        fixColumnsWidth(DataTable, dbgrid, tS)

        DataTable.DefaultView.AllowEdit = False
        DataTable.DefaultView.AllowNew = False
        DataTable.DefaultView.AllowDelete = False
    End Sub

    Private Sub cmdEliminar_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdEliminar.Click
        If MsgBox("�Seguro que desea eliminar esta encuesta?", MsgBoxStyle.YesNo) = MsgBoxResult.Yes Then
            Dim row As DataRow = row_actual(dgDatos)
            If row Is Nothing Then
                MsgBox("Seleccione una encuesta")
                Exit Sub
            End If
            If CNX.State <> ConnectionState.Open Then CNX.Open()
            Dim deleteCMD As New MySqlCommand("DELETE FROM encuestas WHERE id=" & row("id"), CNX)
            Try
                deleteCMD.ExecuteNonQuery()
                cargar()
            Catch ex As MySqlException
                MsgBox("Error al ejecutar comando: " & ex.Message)
            End Try
        End If
    End Sub
End Class