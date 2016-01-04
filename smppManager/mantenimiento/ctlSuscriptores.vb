Imports MySql.Data.MySqlClient
Imports funciones
Public Class ctlSuscriptores
    Inherits formControl.frmTemplate

#Region " Windows Form Designer generated code "
    Public Sub New()
        MyBase.New()
        InitializeComponent()
    End Sub
    Public Sub New(ByVal id As Integer, ByVal activo As Boolean)
        MyBase.New()
        InitializeComponent()
        vId = id
        vActivo = activo
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
    Friend WithEvents cmdEliminar As System.Windows.Forms.Button
    Friend WithEvents cmdFiltrar As System.Windows.Forms.Button
    Friend WithEvents cmdAgregar As System.Windows.Forms.Button
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.dgDatos = New System.Windows.Forms.DataGrid
        Me.cmdEliminar = New System.Windows.Forms.Button
        Me.cmdAgregar = New System.Windows.Forms.Button
        Me.cmdFiltrar = New System.Windows.Forms.Button
        CType(Me.pbUpper, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.cmdPanel.SuspendLayout()
        CType(Me.imgLinea, System.ComponentModel.ISupportInitialize).BeginInit()
        CType(Me.dgDatos, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'cmdPanel
        '
        Me.cmdPanel.Controls.Add(Me.cmdFiltrar)
        Me.cmdPanel.Controls.Add(Me.cmdAgregar)
        Me.cmdPanel.Controls.Add(Me.cmdEliminar)
        Me.cmdPanel.Location = New System.Drawing.Point(0, 415)
        Me.cmdPanel.Size = New System.Drawing.Size(634, 37)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCerrar, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.imgLinea, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdEliminar, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdAgregar, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdFiltrar, 0)
        '
        'cmdCerrar
        '
        Me.cmdCerrar.Location = New System.Drawing.Point(529, 9)
        '
        'imgLinea
        '
        Me.imgLinea.Size = New System.Drawing.Size(624, 8)
        '
        'dgDatos
        '
        Me.dgDatos.CaptionBackColor = System.Drawing.Color.FromArgb(CType(CType(74, Byte), Integer), CType(CType(94, Byte), Integer), CType(CType(118, Byte), Integer))
        Me.dgDatos.CaptionText = "Suscriptores"
        Me.dgDatos.DataMember = ""
        Me.dgDatos.HeaderForeColor = System.Drawing.SystemColors.ControlText
        Me.dgDatos.LinkColor = System.Drawing.Color.FromArgb(CType(CType(74, Byte), Integer), CType(CType(94, Byte), Integer), CType(CType(118, Byte), Integer))
        Me.dgDatos.Location = New System.Drawing.Point(3, 64)
        Me.dgDatos.Name = "dgDatos"
        Me.dgDatos.SelectionBackColor = System.Drawing.Color.FromArgb(CType(CType(74, Byte), Integer), CType(CType(94, Byte), Integer), CType(CType(118, Byte), Integer))
        Me.dgDatos.Size = New System.Drawing.Size(628, 344)
        Me.dgDatos.TabIndex = 28
        '
        'cmdEliminar
        '
        Me.cmdEliminar.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdEliminar.Location = New System.Drawing.Point(424, 9)
        Me.cmdEliminar.Name = "cmdEliminar"
        Me.cmdEliminar.Size = New System.Drawing.Size(100, 24)
        Me.cmdEliminar.TabIndex = 15
        Me.cmdEliminar.Text = "Eliminar"
        Me.cmdEliminar.Visible = False
        '
        'cmdAgregar
        '
        Me.cmdAgregar.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdAgregar.Location = New System.Drawing.Point(3, 9)
        Me.cmdAgregar.Name = "cmdAgregar"
        Me.cmdAgregar.Size = New System.Drawing.Size(100, 24)
        Me.cmdAgregar.TabIndex = 16
        Me.cmdAgregar.Text = "Agregar"
        '
        'cmdFiltrar
        '
        Me.cmdFiltrar.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdFiltrar.Location = New System.Drawing.Point(109, 9)
        Me.cmdFiltrar.Name = "cmdFiltrar"
        Me.cmdFiltrar.Size = New System.Drawing.Size(100, 24)
        Me.cmdFiltrar.TabIndex = 18
        Me.cmdFiltrar.Text = "&Filtrar"
        '
        'ctlSuscriptores
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(6, 14)
        Me.ClientSize = New System.Drawing.Size(634, 452)
        Me.Controls.Add(Me.dgDatos)
        Me.Name = "ctlSuscriptores"
        Me.pTitle = "Suscriptores"
        Me.Text = "Suscriptores"
        Me.Controls.SetChildIndex(Me.cmdPanel, 0)
        Me.Controls.SetChildIndex(Me.pbUpper, 0)
        Me.Controls.SetChildIndex(Me.dgDatos, 0)
        CType(Me.pbUpper, System.ComponentModel.ISupportInitialize).EndInit()
        Me.cmdPanel.ResumeLayout(False)
        CType(Me.imgLinea, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.dgDatos, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub
#End Region

    Private dtaAdpInicial As New MySqlDataAdapter
    Private dataSet As New DataSet
    Private vId As Integer = -1
    Private vActivo As Integer

    Shared Sub formatear_datos(ByVal dbgrid As DataGrid)
        Dim tabla As String = CType(dbgrid.DataSource, DataTable).TableName
        Dim tS As DataGridTableStyle
        Dim DataTable As DataTable = CType(dbgrid.DataSource, DataTable)

        DataTable.Columns("id").ColumnMapping = MappingType.Hidden
        DataTable.Columns("idSuscripcion").ColumnMapping = MappingType.Hidden
        tS = bindDataSet(dbgrid, tabla)

        With tS.GridColumnStyles(0)
            .NullText = "-"
            .HeaderText = "Número"
            .Alignment = HorizontalAlignment.Left
            .ReadOnly = False
        End With
        With tS.GridColumnStyles(1)
            .NullText = "-"
            .HeaderText = "Fechas"
            .Alignment = HorizontalAlignment.Center
            .ReadOnly = False
        End With
        With tS.GridColumnStyles(2)
            .NullText = "-"
            .HeaderText = "Renovaciones"
            .Alignment = HorizontalAlignment.Center
            .ReadOnly = False
        End With

        fixColumnsWidth(DataTable, dbgrid, tS)

        DataTable.DefaultView.AllowEdit = False
        DataTable.DefaultView.AllowNew = False
        DataTable.DefaultView.AllowDelete = False
    End Sub
    Sub cargar(Optional ByVal condition As String = "")
        If Not dgDatos.DataSource Is Nothing Then CType(dgDatos.DataSource, DataTable).Clear()
        If condition <> "" Then condition = " AND numero LIKE '%" & condition & "%'"
        If vActivo Then condition &= " AND estado=1 "
        Dim selectCMD As New MySqlCommand("SELECT id, idSuscripcion, numero, fecha, cantidad FROM suscripciones_participantes WHERE idSuscripcion=" & vId & condition & " AND estado=1 AND notificado=0 ORDER BY cantidad DESC")
        fncGridDataset.cargar(dtaAdpInicial, dgDatos, "DATOS", selectCMD, Nothing, Nothing, Nothing, connectionOne, dataSet)
        formatear_datos(dgDatos)
    End Sub
    Private Sub cmdEliminar_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdEliminar.Click
        If True Then Exit Sub
        '2010-0-23: Se deshabilito esta opción. Se debe utilizar la opción desde el modulo web administrativo para deshabilitar

        Dim row As DataRow = row_actual(dgDatos)
        If row Is Nothing Then
            MsgBox("Seleccione una suscripcion")
            Exit Sub
        End If
        If connectionOne.State <> ConnectionState.Open Then connectionOne.Open()
        Dim deleteCMD As New MySqlCommand("DELETE FROM suscripciones_participantes WHERE id=" & row("id"), connectionOne)
        Try
            deleteCMD.ExecuteNonQuery()
            cargar()
        Catch ex As MySqlException
            MsgBox("Error al ejecutar comando: " & ex.Message)
        End Try
    End Sub

    Private Sub cmdAgregar_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdAgregar.Click
        Dim numero As String = InputBox("Ingrese el número")
        Dim fecha As String = getDate(Now)
        If numero.Length >= NUMBERLENGTH And IsNumeric(numero) Then
            Dim cmd As New MySqlCommand("", connectionOne)
            If connectionOne.State <> ConnectionState.Open Then connectionOne.Open()
            Dim dr As MySqlDataReader = New MySqlCommand("SELECT id FROM suscripciones_participantes WHERE numero=" & numero & " AND idSuscripcion=" & vId, connectionOne).ExecuteReader
            If dr.Read Then
                dr.Close()
                cmd.CommandText = "UPDATE suscripciones_participantes SET fecha='" & fecha & "', cantidad=cantidad+1 WHERE numero=" & numero & " AND idSuscripcion=" & vId
                cmd.ExecuteNonQuery()
            Else
                dr.Close()
                cmd.CommandText = "INSERT INTO suscripciones_participantes (idsuscripcion,numero,fecha) VALUES (" & vId & ",'" & numero & "','" & fecha & "')"
                cmd.ExecuteNonQuery()
            End If
            Dispose()
        Else
            MsgBox("Ingreso un número invalido, utilizando codigo de area")
        End If
    End Sub

    Private Sub cmdFiltrar_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdFiltrar.Click
        cargar(InputBox("Ingrese un número a filtrar: "))
    End Sub
End Class