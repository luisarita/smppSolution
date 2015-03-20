Imports MySql.Data.MySqlClient
Imports funciones

Public Class consSuscripciones
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
    Private ReadOnly components As System.ComponentModel.IContainer

    'NOTE: The following procedure is required by the Windows Form Designer
    'It can be modified using the Windows Form Designer.  
    'Do not modify it using the code editor.
    Friend WithEvents dgDatos As DataGrid
    Friend WithEvents dgDatos2 As DataGrid
    Friend WithEvents txtNombre As System.Windows.Forms.TextBox
    Friend WithEvents Label1 As System.Windows.Forms.Label
    Friend WithEvents cmdBuscar As System.Windows.Forms.Button
    Friend WithEvents cmdEliminar As Button
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.dgDatos = New System.Windows.Forms.DataGrid()
        Me.cmdEliminar = New System.Windows.Forms.Button()
        Me.dgDatos2 = New System.Windows.Forms.DataGrid()
        Me.txtNombre = New System.Windows.Forms.TextBox()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.cmdBuscar = New System.Windows.Forms.Button()
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
        Me.cmdPanel.Location = New System.Drawing.Point(0, 537)
        Me.cmdPanel.Size = New System.Drawing.Size(740, 37)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCerrar, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.imgLinea, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdEliminar, 0)
        '
        'cmdCerrar
        '
        Me.cmdCerrar.Location = New System.Drawing.Point(635, 9)
        '
        'imgLinea
        '
        Me.imgLinea.Size = New System.Drawing.Size(730, 8)
        '
        'dgDatos
        '
        Me.dgDatos.CaptionBackColor = System.Drawing.Color.FromArgb(CType(CType(74, Byte), Integer), CType(CType(94, Byte), Integer), CType(CType(118, Byte), Integer))
        Me.dgDatos.CaptionText = "Suscripciones Activas"
        Me.dgDatos.DataMember = ""
        Me.dgDatos.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.dgDatos.LinkColor = System.Drawing.Color.FromArgb(CType(CType(74, Byte), Integer), CType(CType(94, Byte), Integer), CType(CType(118, Byte), Integer))
        Me.dgDatos.Location = New System.Drawing.Point(5, 94)
        Me.dgDatos.Name = "dgDatos"
        Me.dgDatos.SelectionBackColor = System.Drawing.Color.FromArgb(CType(CType(74, Byte), Integer), CType(CType(94, Byte), Integer), CType(CType(118, Byte), Integer))
        Me.dgDatos.Size = New System.Drawing.Size(731, 214)
        Me.dgDatos.TabIndex = 28
        '
        'cmdEliminar
        '
        Me.cmdEliminar.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdEliminar.Location = New System.Drawing.Point(530, 9)
        Me.cmdEliminar.Name = "cmdEliminar"
        Me.cmdEliminar.Size = New System.Drawing.Size(100, 24)
        Me.cmdEliminar.TabIndex = 13
        Me.cmdEliminar.Text = "Eliminar"
        '
        'dgDatos2
        '
        Me.dgDatos2.CaptionBackColor = System.Drawing.Color.FromArgb(CType(CType(74, Byte), Integer), CType(CType(94, Byte), Integer), CType(CType(118, Byte), Integer))
        Me.dgDatos2.CaptionText = "Suscripciones Inactivas"
        Me.dgDatos2.DataMember = ""
        Me.dgDatos2.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.dgDatos2.LinkColor = System.Drawing.Color.FromArgb(CType(CType(74, Byte), Integer), CType(CType(94, Byte), Integer), CType(CType(118, Byte), Integer))
        Me.dgDatos2.Location = New System.Drawing.Point(5, 314)
        Me.dgDatos2.Name = "dgDatos2"
        Me.dgDatos2.SelectionBackColor = System.Drawing.Color.FromArgb(CType(CType(74, Byte), Integer), CType(CType(94, Byte), Integer), CType(CType(118, Byte), Integer))
        Me.dgDatos2.Size = New System.Drawing.Size(731, 214)
        Me.dgDatos2.TabIndex = 29
        '
        'txtNombre
        '
        Me.txtNombre.Location = New System.Drawing.Point(70, 65)
        Me.txtNombre.Name = "txtNombre"
        Me.txtNombre.Size = New System.Drawing.Size(546, 21)
        Me.txtNombre.TabIndex = 30
        '
        'Label1
        '
        Me.Label1.AutoSize = True
        Me.Label1.Location = New System.Drawing.Point(8, 68)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(54, 13)
        Me.Label1.TabIndex = 31
        Me.Label1.Text = "Nombre:"
        '
        'cmdBuscar
        '
        Me.cmdBuscar.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdBuscar.Location = New System.Drawing.Point(636, 62)
        Me.cmdBuscar.Name = "cmdBuscar"
        Me.cmdBuscar.Size = New System.Drawing.Size(100, 24)
        Me.cmdBuscar.TabIndex = 14
        Me.cmdBuscar.Text = "&Buscar"
        '
        'consSuscripciones
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(6, 14)
        Me.ClientSize = New System.Drawing.Size(740, 574)
        Me.Controls.Add(Me.Label1)
        Me.Controls.Add(Me.txtNombre)
        Me.Controls.Add(Me.dgDatos)
        Me.Controls.Add(Me.dgDatos2)
        Me.Controls.Add(Me.cmdBuscar)
        Me.Name = "consSuscripciones"
        Me.pTitle = "Consultar Suscripciones"
        Me.Text = "Consultar Suscripciones"
        Me.Controls.SetChildIndex(Me.cmdBuscar, 0)
        Me.Controls.SetChildIndex(Me.dgDatos2, 0)
        Me.Controls.SetChildIndex(Me.dgDatos, 0)
        Me.Controls.SetChildIndex(Me.txtNombre, 0)
        Me.Controls.SetChildIndex(Me.Label1, 0)
        Me.Controls.SetChildIndex(Me.pbUpper, 0)
        Me.Controls.SetChildIndex(Me.cmdPanel, 0)
        CType(Me.pbUpper, System.ComponentModel.ISupportInitialize).EndInit()
        Me.cmdPanel.ResumeLayout(False)
        CType(Me.imgLinea, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.dgDatos, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.dgDatos2, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)
        Me.PerformLayout()

    End Sub

#End Region
    Private dtaAdpInicial As New MySqlDataAdapter
    Private ReadOnly dataSet As New DataSet()

    Private Sub dgDatos_DoubleClick(ByVal sender As Object, ByVal e As EventArgs) Handles dgDatos.DoubleClick, dgDatos2.DoubleClick
        Dim row As DataRow = row_actual(CType(sender, DataGrid))
        If row Is Nothing Then Exit Sub

        Dim a As New agrSuscripcion(row("id")) With {.Owner = Me, .MdiParent = MdiParent}
        a.Show()
    End Sub

    Private Sub consRifas_Load(ByVal sender As Object, ByVal e As EventArgs) Handles MyBase.Load
        cargar()
    End Sub
    Shared Sub formatear_datos(dbgrid As DataGrid)
        Dim tabla As String = CType(dbgrid.DataSource, DataTable).TableName
        Dim tS As DataGridTableStyle
        Dim DataTable As DataTable = CType(dbgrid.DataSource, DataTable)

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
            .Alignment = HorizontalAlignment.Left
        End With
        With tS.GridColumnStyles(3)
            .NullText = "-"
            .HeaderText = "Número Salida"
            .Alignment = HorizontalAlignment.Left
        End With
        With tS.GridColumnStyles(4)
            .NullText = "-"
            .HeaderText = "Duración"
            .Alignment = HorizontalAlignment.Left
        End With
        fixColumnsWidth(DataTable, dbgrid, tS)

        DataTable.DefaultView.AllowEdit = False
        DataTable.DefaultView.AllowNew = False
        DataTable.DefaultView.AllowDelete = False
    End Sub
    Sub cargar(Optional nombre As String = "")
        If Not dgDatos.DataSource Is Nothing Then CType(dgDatos.DataSource, DataTable).Clear()
        Using CNX2 As New MySqlConnection(cnxString)
            Using selectCMD As New MySqlCommand(String.Format("SELECT id, nombre, numero, numeroSalida, duracion FROM suscripciones WHERE activa=1 AND nombre LIKE '%{0}%' ORDER BY activa DESC, nombre ASC", nombre))
                fncGridDataset.cargar(dtaAdpInicial, dgDatos, "DATOS", selectCMD, Nothing, Nothing, Nothing, CNX2, dataSet)
            End Using
            formatear_datos(dgDatos)
            Using selectCMD As New MySqlCommand(String.Format("SELECT id, nombre, numero, numeroSalida, duracion FROM suscripciones WHERE activa=0 AND nombre LIKE '%{0}%' ORDER BY activa DESC, nombre ASC", nombre))
                fncGridDataset.cargar(dtaAdpInicial, dgDatos2, "DATOS2", selectCMD, Nothing, Nothing, Nothing, CNX2, dataSet)
            End Using
            formatear_datos(dgDatos2)
        End Using
    End Sub
    Private Sub cmdEliminar_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdEliminar.Click
        If MsgBox("¿Seguro que desea eliminar esta suscripcion?", MsgBoxStyle.YesNo) = MsgBoxResult.Yes Then
            Dim row As DataRow = row_actual(dgDatos)
            If row Is Nothing Then
                MsgBox("Seleccione una suscripcion")
                Exit Sub
            End If
            If CNX.State <> ConnectionState.Open Then CNX.Open()
            Using deleteCMD As New MySqlCommand("UPDATE suscripciones SET activa=0 WHERE id=" & row("id"), CNX)
                Try
                    deleteCMD.ExecuteNonQuery()
                    cargar()
                Catch ex As MySqlException
                    MsgBox("Error al ejecutar comando: " & ex.Message)
                End Try
            End Using
        End If
    End Sub

    Private Sub cmdBuscar_Click(sender As System.Object, e As System.EventArgs) Handles cmdBuscar.Click
        cargar(txtNombre.Text)
    End Sub
End Class