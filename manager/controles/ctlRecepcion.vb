Imports MySql.Data.MySqlClient
Imports funciones
Public Class ctlRecepcion
    Inherits formControl.frmTemplate

#Region " Windows Form Designer generated code "
    Public Sub New()
        MyBase.New()
        InitializeComponent()
    End Sub
    Public Sub New(ByVal id As Integer)
        MyBase.New()
        InitializeComponent()
        'vId = id
        cargar()
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
    Friend WithEvents Label1 As System.Windows.Forms.Label
    Friend WithEvents Label2 As System.Windows.Forms.Label
    Friend WithEvents Label6 As System.Windows.Forms.Label
    Friend WithEvents cbNumeros As System.Windows.Forms.ComboBox
    Friend WithEvents cmdCerrarRifa As System.Windows.Forms.Button
    Friend WithEvents desde As System.Windows.Forms.DateTimePicker
    Friend WithEvents hasta As System.Windows.Forms.DateTimePicker
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.dgDatos = New System.Windows.Forms.DataGrid
        Me.desde = New System.Windows.Forms.DateTimePicker
        Me.Label1 = New System.Windows.Forms.Label
        Me.hasta = New System.Windows.Forms.DateTimePicker
        Me.Label2 = New System.Windows.Forms.Label
        Me.Label6 = New System.Windows.Forms.Label
        Me.cbNumeros = New System.Windows.Forms.ComboBox
        Me.cmdCerrarRifa = New System.Windows.Forms.Button
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
        Me.cmdPanel.Controls.Add(Me.cmdCerrarRifa)
        Me.cmdPanel.DockPadding.Left = 5
        Me.cmdPanel.DockPadding.Right = 5
        Me.cmdPanel.Location = New System.Drawing.Point(0, 415)
        Me.cmdPanel.Name = "cmdPanel"
        Me.cmdPanel.Size = New System.Drawing.Size(634, 37)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCerrar, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.imgLinea, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCerrarRifa, 0)
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
        Me.dgDatos.CaptionText = "Mensajes Recibidos"
        Me.dgDatos.DataMember = ""
        Me.dgDatos.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.dgDatos.LinkColor = System.Drawing.Color.FromArgb(CType(74, Byte), CType(94, Byte), CType(118, Byte))
        Me.dgDatos.Location = New System.Drawing.Point(3, 112)
        Me.dgDatos.Name = "dgDatos"
        Me.dgDatos.SelectionBackColor = System.Drawing.Color.FromArgb(CType(74, Byte), CType(94, Byte), CType(118, Byte))
        Me.dgDatos.Size = New System.Drawing.Size(628, 296)
        Me.dgDatos.TabIndex = 28
        '
        'desde
        '
        Me.desde.CalendarMonthBackground = System.Drawing.Color.White
        Me.desde.CustomFormat = "dd/MM/yyyy h:mm:ss tt"
        Me.desde.Format = System.Windows.Forms.DateTimePickerFormat.Custom
        Me.desde.Location = New System.Drawing.Point(80, 64)
        Me.desde.Name = "desde"
        Me.desde.Size = New System.Drawing.Size(184, 21)
        Me.desde.TabIndex = 30
        '
        'Label1
        '
        Me.Label1.Location = New System.Drawing.Point(8, 64)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(72, 21)
        Me.Label1.TabIndex = 29
        Me.Label1.Text = "Desde:"
        Me.Label1.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'hasta
        '
        Me.hasta.CalendarMonthBackground = System.Drawing.Color.White
        Me.hasta.CustomFormat = "dd/MM/yyyy h:mm:ss tt"
        Me.hasta.Format = System.Windows.Forms.DateTimePickerFormat.Custom
        Me.hasta.Location = New System.Drawing.Point(80, 88)
        Me.hasta.Name = "hasta"
        Me.hasta.Size = New System.Drawing.Size(184, 21)
        Me.hasta.TabIndex = 32
        '
        'Label2
        '
        Me.Label2.Location = New System.Drawing.Point(8, 88)
        Me.Label2.Name = "Label2"
        Me.Label2.Size = New System.Drawing.Size(72, 21)
        Me.Label2.TabIndex = 31
        Me.Label2.Text = "Hasta:"
        Me.Label2.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'Label6
        '
        Me.Label6.Location = New System.Drawing.Point(328, 64)
        Me.Label6.Name = "Label6"
        Me.Label6.Size = New System.Drawing.Size(80, 21)
        Me.Label6.TabIndex = 34
        Me.Label6.Text = "Número:"
        Me.Label6.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'cbNumeros
        '
        Me.cbNumeros.BackColor = System.Drawing.Color.White
        Me.cbNumeros.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cbNumeros.Location = New System.Drawing.Point(416, 64)
        Me.cbNumeros.Name = "cbNumeros"
        Me.cbNumeros.Size = New System.Drawing.Size(208, 21)
        Me.cbNumeros.TabIndex = 33
        '
        'cmdCerrarRifa
        '
        Me.cmdCerrarRifa.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdCerrarRifa.Location = New System.Drawing.Point(424, 9)
        Me.cmdCerrarRifa.Name = "cmdCerrarRifa"
        Me.cmdCerrarRifa.Size = New System.Drawing.Size(100, 24)
        Me.cmdCerrarRifa.TabIndex = 12
        Me.cmdCerrarRifa.Text = "Cargar"
        '
        'ctlRecepcion
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(6, 14)
        Me.ClientSize = New System.Drawing.Size(634, 452)
        Me.Controls.Add(Me.Label6)
        Me.Controls.Add(Me.cbNumeros)
        Me.Controls.Add(Me.hasta)
        Me.Controls.Add(Me.Label2)
        Me.Controls.Add(Me.desde)
        Me.Controls.Add(Me.Label1)
        Me.Controls.Add(Me.dgDatos)
        Me.Name = "ctlRecepcion"
        Me.pTitle = "Mensajes Recibidos"
        Me.Text = "Mensajes Recibidos"
        Me.Controls.SetChildIndex(Me.cmdPanel, 0)
        Me.Controls.SetChildIndex(Me.pbUpper, 0)
        Me.Controls.SetChildIndex(Me.dgDatos, 0)
        Me.Controls.SetChildIndex(Me.Label1, 0)
        Me.Controls.SetChildIndex(Me.desde, 0)
        Me.Controls.SetChildIndex(Me.Label2, 0)
        Me.Controls.SetChildIndex(Me.hasta, 0)
        Me.Controls.SetChildIndex(Me.cbNumeros, 0)
        Me.Controls.SetChildIndex(Me.Label6, 0)
        Me.cmdPanel.ResumeLayout(False)
        CType(Me.dgDatos, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub
#End Region

    Private dtaAdpInicial As New MySQLDataAdapter
    Private dataSet As New DataSet
    'Private vId As Integer = -1
    Shared Sub formatear_datos(ByVal dbgrid As DataGrid)
        Dim tabla As String = CType(dbgrid.DataSource, DataTable).TableName
        Dim tS As DataGridTableStyle
        Dim DataTable As DataTable = CType(dbgrid.DataSource, DataTable)

        tS = bindDataSet(dbgrid, tabla)

        With tS.GridColumnStyles(0)
            .NullText = "-"
            .HeaderText = "Número"
            .Alignment = HorizontalAlignment.Left
            .ReadOnly = False
        End With
        With tS.GridColumnStyles(1)
            .NullText = "-"
            .HeaderText = "Fecha"
            .Alignment = HorizontalAlignment.Center
            .ReadOnly = False
        End With
        With tS.GridColumnStyles(2)
            .NullText = "-"
            .HeaderText = "Mensaje"
            .Alignment = HorizontalAlignment.Center
            .ReadOnly = False
        End With

        fixColumnsWidth(DataTable, dbgrid, tS)

        DataTable.DefaultView.AllowEdit = False
        DataTable.DefaultView.AllowNew = False
        DataTable.DefaultView.AllowDelete = False
    End Sub
    Sub cargar()
        Dim sNumero As String = valor(cbNumeros)
        Dim sDesde As String = getDate(desde.Value)
        Dim sHasta As String = getDate(hasta.Value)
        If Not dgDatos.DataSource Is Nothing Then CType(dgDatos.DataSource, DataTable).Clear()

        Dim SQL As String = "SELECT TELEFONO_ORIGEN_DE_RESPUE,FECHA_DE_RECEPCION,DATOS_RECIBIDOS FROM comunicacion_recibida_tabla WHERE TELEFON_DESTINO_DE_RESPU=" & sNumero & " AND FECHA_DE_RECEPCION >='" & sDesde & "' AND FECHA_DE_RECEPCION < '" & sHasta & "'"
        SQL = SQL & " UNION " & SQL.Replace("comunicacion_recibida_tabla", "comunicacion_recibida_tabla_bck") '& " ORDER BY FECHA_DE_RECEPCION DESC"
        Dim selectCMD As New MySQLCommand(SQL)
        fncGridDataset.cargar(dtaAdpInicial, dgDatos, "DATOS", selectCMD, Nothing, Nothing, Nothing, connectionOne, dataSet)
        formatear_datos(dgDatos)
    End Sub
    Private Sub cmdCerrarRifa_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdCerrarRifa.Click
        cargar()
    End Sub

    Private Sub ctlRecepcion_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        fncListboxMySQL.popularMySQL(CObj(cbNumeros), "numeros", "numero", "numero", CNX)
        desde.Value = Now.AddDays(-30)
    End Sub
End Class
