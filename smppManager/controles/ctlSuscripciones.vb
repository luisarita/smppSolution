Imports MySql.Data.MySqlClient
Imports funciones

Public Class ctlSuscripciones
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
    Friend WithEvents GroupBox1 As System.Windows.Forms.GroupBox
    Friend WithEvents Label4 As System.Windows.Forms.Label
    Friend WithEvents Label1 As System.Windows.Forms.Label
    Friend WithEvents dgDatos As System.Windows.Forms.DataGrid
    Friend WithEvents cbSuscripciones As System.Windows.Forms.ComboBox
    Friend WithEvents Button1 As System.Windows.Forms.Button
    Friend WithEvents dgDatos2 As System.Windows.Forms.DataGrid
    Friend WithEvents cmdAgregar As System.Windows.Forms.Button
    Friend WithEvents cmdActivos As System.Windows.Forms.Button
    Friend WithEvents cantidad As System.Windows.Forms.TextBox
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox
        Me.Button1 = New System.Windows.Forms.Button
        Me.Label4 = New System.Windows.Forms.Label
        Me.cantidad = New System.Windows.Forms.TextBox
        Me.Label1 = New System.Windows.Forms.Label
        Me.cbSuscripciones = New System.Windows.Forms.ComboBox
        Me.dgDatos = New System.Windows.Forms.DataGrid
        Me.dgDatos2 = New System.Windows.Forms.DataGrid
        Me.cmdAgregar = New System.Windows.Forms.Button
        Me.cmdActivos = New System.Windows.Forms.Button
        CType(Me.pbUpper, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.cmdPanel.SuspendLayout()
        CType(Me.imgLinea, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.GroupBox1.SuspendLayout()
        CType(Me.dgDatos, System.ComponentModel.ISupportInitialize).BeginInit()
        CType(Me.dgDatos2, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'cmdPanel
        '
        Me.cmdPanel.Controls.Add(Me.cmdAgregar)
        Me.cmdPanel.Location = New System.Drawing.Point(0, 415)
        Me.cmdPanel.Size = New System.Drawing.Size(634, 37)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCerrar, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.imgLinea, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdAgregar, 0)
        '
        'cmdCerrar
        '
        Me.cmdCerrar.Location = New System.Drawing.Point(529, 9)
        '
        'imgLinea
        '
        Me.imgLinea.Size = New System.Drawing.Size(624, 8)
        '
        'GroupBox1
        '
        Me.GroupBox1.Controls.Add(Me.cmdActivos)
        Me.GroupBox1.Controls.Add(Me.Button1)
        Me.GroupBox1.Controls.Add(Me.Label4)
        Me.GroupBox1.Controls.Add(Me.cantidad)
        Me.GroupBox1.Controls.Add(Me.Label1)
        Me.GroupBox1.Controls.Add(Me.cbSuscripciones)
        Me.GroupBox1.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.GroupBox1.Location = New System.Drawing.Point(3, 56)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(628, 72)
        Me.GroupBox1.TabIndex = 11
        Me.GroupBox1.TabStop = False
        '
        'Button1
        '
        Me.Button1.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.Button1.Location = New System.Drawing.Point(372, 40)
        Me.Button1.Name = "Button1"
        Me.Button1.Size = New System.Drawing.Size(119, 21)
        Me.Button1.TabIndex = 13
        Me.Button1.Text = "&Manejar"
        '
        'Label4
        '
        Me.Label4.Location = New System.Drawing.Point(8, 40)
        Me.Label4.Name = "Label4"
        Me.Label4.Size = New System.Drawing.Size(159, 21)
        Me.Label4.TabIndex = 7
        Me.Label4.Text = "Cantidad de Suscriptores:"
        Me.Label4.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'cantidad
        '
        Me.cantidad.BackColor = System.Drawing.Color.White
        Me.cantidad.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.cantidad.Location = New System.Drawing.Point(168, 40)
        Me.cantidad.Name = "cantidad"
        Me.cantidad.ReadOnly = True
        Me.cantidad.Size = New System.Drawing.Size(198, 21)
        Me.cantidad.TabIndex = 6
        Me.cantidad.Text = "0"
        Me.cantidad.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'Label1
        '
        Me.Label1.Location = New System.Drawing.Point(8, 17)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(144, 21)
        Me.Label1.TabIndex = 3
        Me.Label1.Text = "Suscripción:"
        Me.Label1.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'cbSuscripciones
        '
        Me.cbSuscripciones.BackColor = System.Drawing.Color.White
        Me.cbSuscripciones.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cbSuscripciones.Location = New System.Drawing.Point(168, 17)
        Me.cbSuscripciones.Name = "cbSuscripciones"
        Me.cbSuscripciones.Size = New System.Drawing.Size(448, 21)
        Me.cbSuscripciones.TabIndex = 1
        '
        'dgDatos
        '
        Me.dgDatos.CaptionBackColor = System.Drawing.Color.FromArgb(CType(CType(74, Byte), Integer), CType(CType(94, Byte), Integer), CType(CType(118, Byte), Integer))
        Me.dgDatos.CaptionText = "Mensajes Enviados"
        Me.dgDatos.DataMember = ""
        Me.dgDatos.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.dgDatos.LinkColor = System.Drawing.Color.FromArgb(CType(CType(74, Byte), Integer), CType(CType(94, Byte), Integer), CType(CType(118, Byte), Integer))
        Me.dgDatos.Location = New System.Drawing.Point(3, 136)
        Me.dgDatos.Name = "dgDatos"
        Me.dgDatos.SelectionBackColor = System.Drawing.Color.FromArgb(CType(CType(74, Byte), Integer), CType(CType(94, Byte), Integer), CType(CType(118, Byte), Integer))
        Me.dgDatos.Size = New System.Drawing.Size(628, 128)
        Me.dgDatos.TabIndex = 27
        '
        'dgDatos2
        '
        Me.dgDatos2.CaptionBackColor = System.Drawing.Color.FromArgb(CType(CType(74, Byte), Integer), CType(CType(94, Byte), Integer), CType(CType(118, Byte), Integer))
        Me.dgDatos2.CaptionText = "Mensajes Pendientes"
        Me.dgDatos2.DataMember = ""
        Me.dgDatos2.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.dgDatos2.LinkColor = System.Drawing.Color.FromArgb(CType(CType(74, Byte), Integer), CType(CType(94, Byte), Integer), CType(CType(118, Byte), Integer))
        Me.dgDatos2.Location = New System.Drawing.Point(3, 272)
        Me.dgDatos2.Name = "dgDatos2"
        Me.dgDatos2.SelectionBackColor = System.Drawing.Color.FromArgb(CType(CType(74, Byte), Integer), CType(CType(94, Byte), Integer), CType(CType(118, Byte), Integer))
        Me.dgDatos2.Size = New System.Drawing.Size(628, 136)
        Me.dgDatos2.TabIndex = 28
        '
        'cmdAgregar
        '
        Me.cmdAgregar.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdAgregar.Location = New System.Drawing.Point(424, 9)
        Me.cmdAgregar.Name = "cmdAgregar"
        Me.cmdAgregar.Size = New System.Drawing.Size(100, 24)
        Me.cmdAgregar.TabIndex = 12
        Me.cmdAgregar.Text = "&Agregar"
        '
        'cmdActivos
        '
        Me.cmdActivos.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdActivos.Location = New System.Drawing.Point(497, 40)
        Me.cmdActivos.Name = "cmdActivos"
        Me.cmdActivos.Size = New System.Drawing.Size(119, 21)
        Me.cmdActivos.TabIndex = 14
        Me.cmdActivos.Text = "Manejar &Activos"
        '
        'ctlSuscripciones
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(6, 14)
        Me.ClientSize = New System.Drawing.Size(634, 452)
        Me.Controls.Add(Me.dgDatos2)
        Me.Controls.Add(Me.dgDatos)
        Me.Controls.Add(Me.GroupBox1)
        Me.Name = "ctlSuscripciones"
        Me.pTitle = "Control de Suscripciones"
        Me.Text = "Control de Suscripciones"
        Me.Controls.SetChildIndex(Me.cmdPanel, 0)
        Me.Controls.SetChildIndex(Me.pbUpper, 0)
        Me.Controls.SetChildIndex(Me.GroupBox1, 0)
        Me.Controls.SetChildIndex(Me.dgDatos, 0)
        Me.Controls.SetChildIndex(Me.dgDatos2, 0)
        CType(Me.pbUpper, System.ComponentModel.ISupportInitialize).EndInit()
        Me.cmdPanel.ResumeLayout(False)
        CType(Me.imgLinea, System.ComponentModel.ISupportInitialize).EndInit()
        Me.GroupBox1.ResumeLayout(False)
        Me.GroupBox1.PerformLayout()
        CType(Me.dgDatos, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.dgDatos2, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub
#End Region

    Private vID As Integer
    Private dtaAdpInicial As New MySQLDataAdapter
    Private dataSet As New DataSet

    Private Sub ctlSuscripciones_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        fncListboxMySQL.popularMySQL(CObj(cbSuscripciones), "suscripciones", "id", "nombre", CNX)
    End Sub

    Private Sub cbSuscripciones_SelectedIndexChanged(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cbSuscripciones.SelectedIndexChanged
        vID = valor(cbSuscripciones)
        If Not connectionOne.State = ConnectionState.Open Then connectionOne.Open()
        Dim dr As MySQLDataReader = New MySQLCommand("SELECT COUNT(*) AS cantidad FROM suscripciones, suscripciones_participantes WHERE suscripciones_participantes.fecha - Now() <= suscripciones.duracion AND suscripciones.id=suscripciones_participantes.idsuscripcion AND suscripciones_participantes.notificado=0 AND suscripciones_participantes.idsuscripcion=" & vID, connectionOne).ExecuteReader(CommandBehavior.SingleRow)
        If dr.Read Then cantidad.Text = dr!cantidad
        dr.Close()
        Dim SQL As String = "SELECT id, idsuscripcion, estado, mensaje, fecha FROM suscripciones_mensajes WHERE estado=1 AND idSuscripcion=" & vID
        Dim selectCMD As New MySqlCommand(SQL & " UNION " & SQL.Replace("suscripciones_mensajes", "suscripciones_mensajes_bck") & " ORDER BY id DESC")

        If Not dgDatos2.DataSource Is Nothing Then CType(dgDatos2.DataSource, DataTable).Clear()
        If Not dgDatos.DataSource Is Nothing Then CType(dgDatos.DataSource, DataTable).Clear()
        Dim CNX2 As New MySqlConnection(cnxString)
        Try
            fncGridDataset.cargar(dtaAdpInicial, dgDatos, "DATOS1", selectCMD, Nothing, Nothing, Nothing, CNX2, dataSet)
        Catch
            selectCMD.CommandText = SQL
            fncGridDataset.cargar(dtaAdpInicial, dgDatos, "DATOS1", selectCMD, Nothing, Nothing, Nothing, CNX2, dataSet)
        End Try

        SQL = "SELECT * FROM suscripciones_mensajes WHERE estado=0 AND idSuscripcion=" & vID
        fncGridDataset.cargar(dtaAdpInicial, dgDatos2, "DATOS2", selectCMD, Nothing, Nothing, Nothing, CNX2, dataSet)
        formatear_datos(dgDatos)
        formatear_datos(dgDatos2)
    End Sub
    Shared Sub formatear_datos(ByVal dbgrid As DataGrid)
        Dim tabla As String = CType(dbgrid.DataSource, DataTable).TableName
        Dim tS As DataGridTableStyle
        Dim DataTable As DataTable = CType(dbgrid.DataSource, DataTable)

        DataTable.Columns("id").ColumnMapping = MappingType.Hidden
        DataTable.Columns("idSuscripcion").ColumnMapping = MappingType.Hidden
        DataTable.Columns("estado").ColumnMapping = MappingType.Hidden
        tS = bindDataSet(dbgrid, tabla)

        With tS.GridColumnStyles(0)
            .NullText = "-"
            .HeaderText = "Mensaje"
            .Alignment = HorizontalAlignment.Left
            .ReadOnly = False
        End With
        With tS.GridColumnStyles(1)
            .NullText = "-"
            .HeaderText = "Fecha"
            .Alignment = HorizontalAlignment.Left
        End With

        fixColumnsWidth(DataTable, dbgrid, tS)

        DataTable.DefaultView.AllowEdit = False
        DataTable.DefaultView.AllowNew = False
        DataTable.DefaultView.AllowDelete = False
    End Sub
    Private Sub Button1_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles Button1.Click
        Dim c As New ctlSuscriptores(vID, False)
        c.MdiParent = MdiParent
        c.Show()
    End Sub
    Private Sub cmdCerrarRifa_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdAgregar.Click
        Dim a As New agrMsgSuscripcion(vID, True)
        a.MdiParent = MdiParent
        a.Show()
    End Sub
    Private Sub dgDatos_DoubleClick(ByVal sender As Object, ByVal e As System.EventArgs) Handles dgDatos.DoubleClick
        Dim row As DataRow = row_actual(dgDatos)
        If row Is Nothing Then Exit Sub

        Dim cliente As New agrMsgSuscripcion(row("id"))
        cliente.MdiParent = MdiParent
        cliente.Show()
    End Sub
    Private Sub dgDatos2_DoubleClick(ByVal sender As Object, ByVal e As System.EventArgs) Handles dgDatos2.DoubleClick
        Dim row As DataRow = row_actual(dgDatos2)
        If row Is Nothing Then Exit Sub

        Dim cliente As New agrMsgSuscripcion(row("id"))
        cliente.MdiParent = MdiParent
        cliente.Show()
    End Sub

    Private Sub cmdActivos_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdActivos.Click
        Dim c As New ctlSuscriptores(vID, True)
        c.MdiParent = MdiParent
        c.Show()
    End Sub
End Class
