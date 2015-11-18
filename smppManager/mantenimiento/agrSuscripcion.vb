Imports MySql.Data.MySqlClient
Imports System.IO

Public Class agrSuscripcion
    Inherits formControl.frmTemplate

#Region " Windows Form Designer generated code "
    Public Sub New()
        MyBase.New()
        InitializeComponent()
        fncListboxMySQL.popularMySQL(CObj(cbNumeros), "numeros", "numero", "numero", CNX)
        fncListboxMySQL.popularMySQL(CObj(cbNumerosSalida), "numeros", "numero", "numero", CNX)
        fncListboxMySQL.popularMySQL(CObj(cbNumeroEscalonado), "numeros", "numero", "numero", CNX)
        cbNumeroEscalonado.SelectedIndex = -1
        fncListboxMySQL.popularMySQL(CObj(cbNumeroAdicional), "numeros", "numero", "numero", CNX)
        fncListboxMySQL.popularMySQL(CObj(cbNumeroRecepcion), "numeros", "numero", "numero", CNX)
        fncListboxMySQL.popularMySQL(CObj(cbSuscripciones), "suscripciones", "id", "nombre", CNX, "activa=1")
        chkActiva.Checked = True
    End Sub
    Sub New(ByVal id As Integer)
        MyBase.New()
        InitializeComponent()
        fncListboxMySQL.popularMySQL(CObj(cbNumeros), "numeros", "numero", "numero", CNX)
        fncListboxMySQL.popularMySQL(CObj(cbNumerosSalida), "numeros", "numero", "numero", CNX)
        fncListboxMySQL.popularMySQL(CObj(cbNumeroEscalonado), "numeros", "numero", "numero", CNX)
        fncListboxMySQL.popularMySQL(CObj(cbNumeroAdicional), "numeros", "numero", "numero", CNX)
        fncListboxMySQL.popularMySQL(CObj(cbNumeroRecepcion), "numeros", "numero", "numero", CNX)
        fncListboxMySQL.popularMySQL(CObj(cbSuscripciones), "suscripciones", "id", "nombre", CNX, "activa=1")
        vID = id
        pTitle = "Modificar Suscripción"
        cmdCrear.Text = "Modificar"
        If CNX.State <> ConnectionState.Open Then CNX.Open()
        Dim dr As MySqlDataReader = New MySqlCommand("SELECT s.nombre, s.nombreComercial, s.numero, s.numeroAdicional, s.duracion, s.respuesta, s.respuestaAdicional, s.renovacionA, s.renovacionB, s.renovacionC, s.usuario, s.clave, s.numeroRecepcion, s.numeroUsuario, c.clave AS claveRecepcion, s.numeroSalida, s.numeroEscalonado, s.respuestaCancelacion, s.rutaShell, s.priorizado, s.activa, s.habilitar_media, s.aplicarHorario, s.aplicarLlenadoVariables, s.monitoreable, s.requiereAprobacion, s.correoAprobacion, s.limiteMensajesDiarios, s.costo, s.claveServicio FROM suscripciones s LEFT OUTER JOIN claves c ON s.id=c.idSuscripcionRecepcion WHERE s.id=" & id, CNX).ExecuteReader
        If dr.Read Then
            nombre.Text = dr!nombre
            nombreComercial.Text = dr!nombreComercial
            seleccionar(cbNumeros, dr!numero)
            seleccionar(cbNumerosSalida, dr!numeroSalida)
            If Not IsDBNull(dr!numeroEscalonado) Then
                seleccionar(cbNumeroEscalonado, dr!numeroEscalonado)
            Else
                cbNumeroEscalonado.SelectedIndex = -1
            End If
            seleccionar(cbNumeroAdicional, dr!numeroAdicional)
            duracion.Text = dr!duracion
            respuesta.Text = dr!respuesta
            respuestaAdicional.Text = dr!respuestaAdicional
            renovacionA.Text = dr!renovacionA
            renovacionB.Text = dr!renovacionB
            renovacionC.Text = dr!renovacionC
            respuestaCancelacion.Text = dr!respuestaCancelacion
            usuario.Text = dr!usuario
            clave.Text = dr!clave
            limiteMensajesDiario.Text = dr!limiteMensajesDiarios
            costo.Text = dr!costo
            seleccionar(cbNumeroRecepcion, dr!numeroRecepcion)
            If Not dr!claveRecepcion Is NULL Then claveRecepcion.Text = dr!claveRecepcion
            If Not dr!rutaShell Is NULL Then rutaShell.Text = dr!rutaShell
            chkPriorizado.Checked = IIf(dr!priorizado = 1, True, False)
            chkActiva.Checked = IIf(dr!activa = 1, True, False)
            chkMedia.Checked = IIf(dr!habilitar_media = 1, True, False)
            chkAplicarHorario.Checked = IIf(dr!aplicarHorario = 1, True, False)
            chkAplicarLlenadoVariables.Checked = IIf(dr!aplicarLlenadoVariables = 1, True, False)
            chkMonitoreable.Checked = IIf(dr!monitoreable = 1, True, False)
            chkRequiereAprobacion.Checked = IIf(dr!requiereAprobacion = 1, True, False)
            txtCorreoAprobacion.Text = dr!correoAprobacion
            numeroUsuario.Text = dr!numeroUsuario
            claveServicio.Text = dr!claveServicio
            dr.Close()
            fncListboxMySQL.popularMySQL(CObj(lbclaves), "claves", "id", "clave", CNX, "idSuscripcion=" & vID)
            fncListboxMySQL.popularMySQL(CObj(lbClavesCancelacion), "claves", "id", "clave", CNX, "idSuscripcionCancelacion=" & vID)
            fncListboxMySQL.popularMySQL(CObj(lbReplicacion), "suscripciones", "id", "nombre", CNX, String.Format("id IN (SELECT hijo FROM suscripciones_replicaciones WHERE padre={0})", vID))
        Else
                dr.Close()
            showError("Registro no encontrado")
            Dispose()
        End If
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
    Friend WithEvents Label3 As Label
    Friend WithEvents Label1 As Label
    Friend WithEvents ErrorProvider As ErrorProvider
    Friend WithEvents cmdCrear As Button
    Friend WithEvents nombre As TextBox
    Friend WithEvents Label2 As Label
    Friend WithEvents cbNumeros As ComboBox
    Friend WithEvents Label4 As Label
    Friend WithEvents duracion As TextBox
    Friend WithEvents Label5 As Label
    Friend WithEvents respuesta As TextBox
    Friend WithEvents lbclaves As ListBox
    Friend WithEvents cmdAgr As Button
    Friend WithEvents cmdDel As Button
    Friend WithEvents GroupBox2 As GroupBox
    Friend WithEvents Label6 As Label
    Friend WithEvents Label7 As Label
    Friend WithEvents usuario As TextBox
    Friend WithEvents clave As TextBox
    Friend WithEvents Label9 As Label
    Friend WithEvents logo As TextBox
    Friend WithEvents GroupBox3 As GroupBox
    Friend WithEvents numeroUsuario As TextBox
    Friend WithEvents Label12 As Label
    Friend WithEvents claveRecepcion As TextBox
    Friend WithEvents Label10 As Label
    Friend WithEvents Label11 As Label
    Friend WithEvents cbNumeroRecepcion As ComboBox
    Friend WithEvents GroupBox4 As GroupBox
    Friend WithEvents cbNumerosSalida As ComboBox
    Friend WithEvents Label17 As Label
    Friend WithEvents Label15 As Label
    Friend WithEvents respuestaCancelacion As TextBox
    Friend WithEvents Label16 As Label
    Friend WithEvents GroupBox5 As GroupBox
    Friend WithEvents Label20 As Label
    Friend WithEvents rutaShell As TextBox
    Friend WithEvents cmdDelCancel As Button
    Friend WithEvents cmdAgrCancel As Button
    Friend WithEvents lbClavesCancelacion As ListBox
    Friend WithEvents GroupBox6 As GroupBox
    Friend WithEvents Label23 As Label
    Friend WithEvents cbSuscripciones As ComboBox
    Friend WithEvents cmdEliReplicacion As Button
    Friend WithEvents cmdAgrReplicacion As Button
    Friend WithEvents lbReplicacion As ListBox
    Friend WithEvents lblSuscripcion As Label
    Friend WithEvents Label24 As Label
    Friend WithEvents nombreComercial As TextBox
    Friend WithEvents Label25 As Label
    Friend WithEvents respuestaAdicional As TextBox
    Friend WithEvents Label27 As System.Windows.Forms.Label
    Friend WithEvents limiteMensajesDiario As System.Windows.Forms.TextBox
    Friend WithEvents chkRequiereAprobacion As System.Windows.Forms.CheckBox
    Friend WithEvents Label28 As System.Windows.Forms.Label
    Friend WithEvents GroupBox7 As System.Windows.Forms.GroupBox
    Friend WithEvents Label29 As System.Windows.Forms.Label
    Friend WithEvents txtCorreoAprobacion As System.Windows.Forms.TextBox
    Friend WithEvents cbNumeroAdicional As System.Windows.Forms.ComboBox
    Friend WithEvents Label30 As System.Windows.Forms.Label
    Friend WithEvents cbNumeroEscalonado As System.Windows.Forms.ComboBox
    Friend WithEvents Label31 As System.Windows.Forms.Label
    Friend WithEvents Label32 As System.Windows.Forms.Label
    Friend WithEvents claveServicio As System.Windows.Forms.TextBox
    Friend WithEvents Label33 As Label
    Friend WithEvents costo As TextBox
    Friend WithEvents GroupBox9 As GroupBox
    Friend WithEvents Label14 As Label
    Friend WithEvents renovacionC As TextBox
    Friend WithEvents Label13 As Label
    Friend WithEvents renovacionB As TextBox
    Friend WithEvents Label8 As Label
    Friend WithEvents renovacionA As TextBox
    Friend WithEvents GroupBox8 As GroupBox
    Friend WithEvents chkMonitoreable As CheckBox
    Friend WithEvents Label26 As Label
    Friend WithEvents chkAplicarLlenadoVariables As CheckBox
    Friend WithEvents lblAplicarLlenadoVariables As Label
    Friend WithEvents chkAplicarHorario As CheckBox
    Friend WithEvents Label22 As Label
    Friend WithEvents chkMedia As CheckBox
    Friend WithEvents Label21 As Label
    Friend WithEvents chkActiva As CheckBox
    Friend WithEvents Label19 As Label
    Friend WithEvents chkPriorizado As CheckBox
    Friend WithEvents Label18 As Label
    Friend WithEvents Button1 As Button
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.components = New System.ComponentModel.Container()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.Label32 = New System.Windows.Forms.Label()
        Me.claveServicio = New System.Windows.Forms.TextBox()
        Me.cbNumeroEscalonado = New System.Windows.Forms.ComboBox()
        Me.Label31 = New System.Windows.Forms.Label()
        Me.cbNumeroAdicional = New System.Windows.Forms.ComboBox()
        Me.Label30 = New System.Windows.Forms.Label()
        Me.Label27 = New System.Windows.Forms.Label()
        Me.limiteMensajesDiario = New System.Windows.Forms.TextBox()
        Me.Label25 = New System.Windows.Forms.Label()
        Me.respuestaAdicional = New System.Windows.Forms.TextBox()
        Me.Label24 = New System.Windows.Forms.Label()
        Me.nombreComercial = New System.Windows.Forms.TextBox()
        Me.cbNumerosSalida = New System.Windows.Forms.ComboBox()
        Me.Label17 = New System.Windows.Forms.Label()
        Me.cmdDel = New System.Windows.Forms.Button()
        Me.cmdAgr = New System.Windows.Forms.Button()
        Me.lbclaves = New System.Windows.Forms.ListBox()
        Me.Label5 = New System.Windows.Forms.Label()
        Me.respuesta = New System.Windows.Forms.TextBox()
        Me.Label4 = New System.Windows.Forms.Label()
        Me.duracion = New System.Windows.Forms.TextBox()
        Me.cbNumeros = New System.Windows.Forms.ComboBox()
        Me.Label2 = New System.Windows.Forms.Label()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.Label3 = New System.Windows.Forms.Label()
        Me.nombre = New System.Windows.Forms.TextBox()
        Me.ErrorProvider = New System.Windows.Forms.ErrorProvider(Me.components)
        Me.cmdCrear = New System.Windows.Forms.Button()
        Me.GroupBox2 = New System.Windows.Forms.GroupBox()
        Me.Button1 = New System.Windows.Forms.Button()
        Me.Label9 = New System.Windows.Forms.Label()
        Me.logo = New System.Windows.Forms.TextBox()
        Me.Label6 = New System.Windows.Forms.Label()
        Me.usuario = New System.Windows.Forms.TextBox()
        Me.Label7 = New System.Windows.Forms.Label()
        Me.clave = New System.Windows.Forms.TextBox()
        Me.GroupBox3 = New System.Windows.Forms.GroupBox()
        Me.numeroUsuario = New System.Windows.Forms.TextBox()
        Me.Label12 = New System.Windows.Forms.Label()
        Me.claveRecepcion = New System.Windows.Forms.TextBox()
        Me.Label10 = New System.Windows.Forms.Label()
        Me.Label11 = New System.Windows.Forms.Label()
        Me.cbNumeroRecepcion = New System.Windows.Forms.ComboBox()
        Me.GroupBox4 = New System.Windows.Forms.GroupBox()
        Me.cmdDelCancel = New System.Windows.Forms.Button()
        Me.cmdAgrCancel = New System.Windows.Forms.Button()
        Me.lbClavesCancelacion = New System.Windows.Forms.ListBox()
        Me.Label15 = New System.Windows.Forms.Label()
        Me.respuestaCancelacion = New System.Windows.Forms.TextBox()
        Me.Label16 = New System.Windows.Forms.Label()
        Me.GroupBox5 = New System.Windows.Forms.GroupBox()
        Me.Label20 = New System.Windows.Forms.Label()
        Me.rutaShell = New System.Windows.Forms.TextBox()
        Me.GroupBox6 = New System.Windows.Forms.GroupBox()
        Me.Label23 = New System.Windows.Forms.Label()
        Me.cbSuscripciones = New System.Windows.Forms.ComboBox()
        Me.cmdEliReplicacion = New System.Windows.Forms.Button()
        Me.cmdAgrReplicacion = New System.Windows.Forms.Button()
        Me.lbReplicacion = New System.Windows.Forms.ListBox()
        Me.lblSuscripcion = New System.Windows.Forms.Label()
        Me.chkRequiereAprobacion = New System.Windows.Forms.CheckBox()
        Me.Label28 = New System.Windows.Forms.Label()
        Me.GroupBox7 = New System.Windows.Forms.GroupBox()
        Me.Label29 = New System.Windows.Forms.Label()
        Me.txtCorreoAprobacion = New System.Windows.Forms.TextBox()
        Me.GroupBox8 = New System.Windows.Forms.GroupBox()
        Me.chkMonitoreable = New System.Windows.Forms.CheckBox()
        Me.Label26 = New System.Windows.Forms.Label()
        Me.chkAplicarLlenadoVariables = New System.Windows.Forms.CheckBox()
        Me.lblAplicarLlenadoVariables = New System.Windows.Forms.Label()
        Me.chkAplicarHorario = New System.Windows.Forms.CheckBox()
        Me.Label22 = New System.Windows.Forms.Label()
        Me.chkMedia = New System.Windows.Forms.CheckBox()
        Me.Label21 = New System.Windows.Forms.Label()
        Me.chkActiva = New System.Windows.Forms.CheckBox()
        Me.Label19 = New System.Windows.Forms.Label()
        Me.chkPriorizado = New System.Windows.Forms.CheckBox()
        Me.Label18 = New System.Windows.Forms.Label()
        Me.GroupBox9 = New System.Windows.Forms.GroupBox()
        Me.Label14 = New System.Windows.Forms.Label()
        Me.renovacionC = New System.Windows.Forms.TextBox()
        Me.Label13 = New System.Windows.Forms.Label()
        Me.renovacionB = New System.Windows.Forms.TextBox()
        Me.Label8 = New System.Windows.Forms.Label()
        Me.renovacionA = New System.Windows.Forms.TextBox()
        Me.Label33 = New System.Windows.Forms.Label()
        Me.costo = New System.Windows.Forms.TextBox()
        CType(Me.pbUpper, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.cmdPanel.SuspendLayout()
        CType(Me.imgLinea, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.GroupBox1.SuspendLayout()
        CType(Me.ErrorProvider, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.GroupBox2.SuspendLayout()
        Me.GroupBox3.SuspendLayout()
        Me.GroupBox4.SuspendLayout()
        Me.GroupBox5.SuspendLayout()
        Me.GroupBox6.SuspendLayout()
        Me.GroupBox7.SuspendLayout()
        Me.GroupBox8.SuspendLayout()
        Me.GroupBox9.SuspendLayout()
        Me.SuspendLayout()
        '
        'cmdPanel
        '
        Me.cmdPanel.Controls.Add(Me.cmdCrear)
        Me.cmdPanel.Location = New System.Drawing.Point(0, 471)
        Me.cmdPanel.Size = New System.Drawing.Size(1195, 37)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCrear, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCerrar, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.imgLinea, 0)
        '
        'cmdCerrar
        '
        Me.cmdCerrar.Location = New System.Drawing.Point(1090, 9)
        Me.cmdCerrar.TabIndex = 1
        '
        'imgLinea
        '
        Me.imgLinea.Size = New System.Drawing.Size(1185, 8)
        '
        'GroupBox1
        '
        Me.GroupBox1.Controls.Add(Me.Label33)
        Me.GroupBox1.Controls.Add(Me.costo)
        Me.GroupBox1.Controls.Add(Me.Label32)
        Me.GroupBox1.Controls.Add(Me.claveServicio)
        Me.GroupBox1.Controls.Add(Me.cbNumeroEscalonado)
        Me.GroupBox1.Controls.Add(Me.Label31)
        Me.GroupBox1.Controls.Add(Me.cbNumeroAdicional)
        Me.GroupBox1.Controls.Add(Me.Label30)
        Me.GroupBox1.Controls.Add(Me.Label27)
        Me.GroupBox1.Controls.Add(Me.limiteMensajesDiario)
        Me.GroupBox1.Controls.Add(Me.Label25)
        Me.GroupBox1.Controls.Add(Me.respuestaAdicional)
        Me.GroupBox1.Controls.Add(Me.Label24)
        Me.GroupBox1.Controls.Add(Me.nombreComercial)
        Me.GroupBox1.Controls.Add(Me.cbNumerosSalida)
        Me.GroupBox1.Controls.Add(Me.Label17)
        Me.GroupBox1.Controls.Add(Me.cmdDel)
        Me.GroupBox1.Controls.Add(Me.cmdAgr)
        Me.GroupBox1.Controls.Add(Me.lbclaves)
        Me.GroupBox1.Controls.Add(Me.Label5)
        Me.GroupBox1.Controls.Add(Me.respuesta)
        Me.GroupBox1.Controls.Add(Me.Label4)
        Me.GroupBox1.Controls.Add(Me.duracion)
        Me.GroupBox1.Controls.Add(Me.cbNumeros)
        Me.GroupBox1.Controls.Add(Me.Label2)
        Me.GroupBox1.Controls.Add(Me.Label1)
        Me.GroupBox1.Controls.Add(Me.Label3)
        Me.GroupBox1.Controls.Add(Me.nombre)
        Me.GroupBox1.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.GroupBox1.Location = New System.Drawing.Point(3, 59)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(387, 402)
        Me.GroupBox1.TabIndex = 10
        Me.GroupBox1.TabStop = False
        Me.GroupBox1.Text = "General"
        '
        'Label32
        '
        Me.Label32.Location = New System.Drawing.Point(9, 166)
        Me.Label32.Name = "Label32"
        Me.Label32.Size = New System.Drawing.Size(159, 21)
        Me.Label32.TabIndex = 57
        Me.Label32.Text = "Clave Servicio:"
        Me.Label32.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'claveServicio
        '
        Me.claveServicio.BackColor = System.Drawing.Color.White
        Me.claveServicio.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.claveServicio.Location = New System.Drawing.Point(168, 166)
        Me.claveServicio.Name = "claveServicio"
        Me.claveServicio.Size = New System.Drawing.Size(208, 21)
        Me.claveServicio.TabIndex = 6
        '
        'cbNumeroEscalonado
        '
        Me.cbNumeroEscalonado.BackColor = System.Drawing.Color.White
        Me.cbNumeroEscalonado.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cbNumeroEscalonado.Location = New System.Drawing.Point(168, 61)
        Me.cbNumeroEscalonado.Name = "cbNumeroEscalonado"
        Me.cbNumeroEscalonado.Size = New System.Drawing.Size(208, 21)
        Me.cbNumeroEscalonado.TabIndex = 2
        '
        'Label31
        '
        Me.Label31.Location = New System.Drawing.Point(9, 61)
        Me.Label31.Name = "Label31"
        Me.Label31.Size = New System.Drawing.Size(160, 21)
        Me.Label31.TabIndex = 55
        Me.Label31.Text = "Número Escalonado:"
        Me.Label31.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'cbNumeroAdicional
        '
        Me.cbNumeroAdicional.BackColor = System.Drawing.Color.White
        Me.cbNumeroAdicional.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cbNumeroAdicional.Location = New System.Drawing.Point(168, 87)
        Me.cbNumeroAdicional.Name = "cbNumeroAdicional"
        Me.cbNumeroAdicional.Size = New System.Drawing.Size(208, 21)
        Me.cbNumeroAdicional.TabIndex = 3
        '
        'Label30
        '
        Me.Label30.Location = New System.Drawing.Point(9, 87)
        Me.Label30.Name = "Label30"
        Me.Label30.Size = New System.Drawing.Size(160, 21)
        Me.Label30.TabIndex = 53
        Me.Label30.Text = "Número Adicionales:"
        Me.Label30.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'Label27
        '
        Me.Label27.Location = New System.Drawing.Point(9, 334)
        Me.Label27.Name = "Label27"
        Me.Label27.Size = New System.Drawing.Size(153, 21)
        Me.Label27.TabIndex = 51
        Me.Label27.Text = "Límite de Envío (Diario):"
        Me.Label27.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'limiteMensajesDiario
        '
        Me.limiteMensajesDiario.BackColor = System.Drawing.Color.White
        Me.limiteMensajesDiario.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.limiteMensajesDiario.Location = New System.Drawing.Point(168, 334)
        Me.limiteMensajesDiario.Name = "limiteMensajesDiario"
        Me.limiteMensajesDiario.Size = New System.Drawing.Size(208, 21)
        Me.limiteMensajesDiario.TabIndex = 15
        Me.limiteMensajesDiario.Text = "5"
        Me.limiteMensajesDiario.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'Label25
        '
        Me.Label25.Location = New System.Drawing.Point(8, 280)
        Me.Label25.Name = "Label25"
        Me.Label25.Size = New System.Drawing.Size(160, 21)
        Me.Label25.TabIndex = 47
        Me.Label25.Text = "Mensaje de Adicional:"
        Me.Label25.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'respuestaAdicional
        '
        Me.respuestaAdicional.BackColor = System.Drawing.Color.White
        Me.respuestaAdicional.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.respuestaAdicional.Location = New System.Drawing.Point(168, 280)
        Me.respuestaAdicional.MaxLength = 254
        Me.respuestaAdicional.Name = "respuestaAdicional"
        Me.respuestaAdicional.Size = New System.Drawing.Size(208, 21)
        Me.respuestaAdicional.TabIndex = 10
        '
        'Label24
        '
        Me.Label24.Location = New System.Drawing.Point(9, 141)
        Me.Label24.Name = "Label24"
        Me.Label24.Size = New System.Drawing.Size(159, 21)
        Me.Label24.TabIndex = 43
        Me.Label24.Text = "Nombre Comercial:"
        Me.Label24.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'nombreComercial
        '
        Me.nombreComercial.BackColor = System.Drawing.Color.White
        Me.nombreComercial.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.nombreComercial.Location = New System.Drawing.Point(168, 141)
        Me.nombreComercial.Name = "nombreComercial"
        Me.nombreComercial.Size = New System.Drawing.Size(208, 21)
        Me.nombreComercial.TabIndex = 5
        '
        'cbNumerosSalida
        '
        Me.cbNumerosSalida.BackColor = System.Drawing.Color.White
        Me.cbNumerosSalida.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cbNumerosSalida.Location = New System.Drawing.Point(168, 36)
        Me.cbNumerosSalida.Name = "cbNumerosSalida"
        Me.cbNumerosSalida.Size = New System.Drawing.Size(208, 21)
        Me.cbNumerosSalida.TabIndex = 1
        '
        'Label17
        '
        Me.Label17.Location = New System.Drawing.Point(9, 36)
        Me.Label17.Name = "Label17"
        Me.Label17.Size = New System.Drawing.Size(160, 21)
        Me.Label17.TabIndex = 32
        Me.Label17.Text = "Número de Salida:"
        Me.Label17.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'cmdDel
        '
        Me.cmdDel.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdDel.Location = New System.Drawing.Point(351, 228)
        Me.cmdDel.Name = "cmdDel"
        Me.cmdDel.Size = New System.Drawing.Size(25, 20)
        Me.cmdDel.TabIndex = 8
        Me.cmdDel.Text = "-"
        '
        'cmdAgr
        '
        Me.cmdAgr.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdAgr.Location = New System.Drawing.Point(351, 205)
        Me.cmdAgr.Name = "cmdAgr"
        Me.cmdAgr.Size = New System.Drawing.Size(25, 20)
        Me.cmdAgr.TabIndex = 7
        Me.cmdAgr.Text = "+"
        '
        'lbclaves
        '
        Me.lbclaves.BackColor = System.Drawing.Color.White
        Me.lbclaves.Location = New System.Drawing.Point(168, 205)
        Me.lbclaves.Name = "lbclaves"
        Me.lbclaves.Size = New System.Drawing.Size(177, 43)
        Me.lbclaves.TabIndex = 4
        '
        'Label5
        '
        Me.Label5.Location = New System.Drawing.Point(8, 257)
        Me.Label5.Name = "Label5"
        Me.Label5.Size = New System.Drawing.Size(160, 21)
        Me.Label5.TabIndex = 21
        Me.Label5.Text = "Mensaje de Respuesta:"
        Me.Label5.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'respuesta
        '
        Me.respuesta.BackColor = System.Drawing.Color.White
        Me.respuesta.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.respuesta.Location = New System.Drawing.Point(168, 257)
        Me.respuesta.MaxLength = 254
        Me.respuesta.Name = "respuesta"
        Me.respuesta.Size = New System.Drawing.Size(208, 21)
        Me.respuesta.TabIndex = 9
        '
        'Label4
        '
        Me.Label4.Location = New System.Drawing.Point(9, 307)
        Me.Label4.Name = "Label4"
        Me.Label4.Size = New System.Drawing.Size(112, 21)
        Me.Label4.TabIndex = 19
        Me.Label4.Text = "Duración (Días):"
        Me.Label4.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'duracion
        '
        Me.duracion.BackColor = System.Drawing.Color.White
        Me.duracion.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.duracion.Location = New System.Drawing.Point(168, 307)
        Me.duracion.Name = "duracion"
        Me.duracion.Size = New System.Drawing.Size(208, 21)
        Me.duracion.TabIndex = 14
        Me.duracion.Text = "30"
        Me.duracion.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'cbNumeros
        '
        Me.cbNumeros.BackColor = System.Drawing.Color.White
        Me.cbNumeros.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cbNumeros.Location = New System.Drawing.Point(168, 12)
        Me.cbNumeros.Name = "cbNumeros"
        Me.cbNumeros.Size = New System.Drawing.Size(208, 21)
        Me.cbNumeros.TabIndex = 0
        '
        'Label2
        '
        Me.Label2.Location = New System.Drawing.Point(9, 12)
        Me.Label2.Name = "Label2"
        Me.Label2.Size = New System.Drawing.Size(160, 21)
        Me.Label2.TabIndex = 10
        Me.Label2.Text = "Número:"
        Me.Label2.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'Label1
        '
        Me.Label1.Location = New System.Drawing.Point(9, 199)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(112, 21)
        Me.Label1.TabIndex = 9
        Me.Label1.Text = "Clave(s):"
        Me.Label1.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'Label3
        '
        Me.Label3.Location = New System.Drawing.Point(9, 118)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(159, 21)
        Me.Label3.TabIndex = 7
        Me.Label3.Text = "Nombre de la Suscripción:"
        Me.Label3.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'nombre
        '
        Me.nombre.BackColor = System.Drawing.Color.White
        Me.nombre.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.nombre.Location = New System.Drawing.Point(168, 118)
        Me.nombre.Name = "nombre"
        Me.nombre.Size = New System.Drawing.Size(208, 21)
        Me.nombre.TabIndex = 4
        '
        'ErrorProvider
        '
        Me.ErrorProvider.ContainerControl = Me
        '
        'cmdCrear
        '
        Me.cmdCrear.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdCrear.Location = New System.Drawing.Point(565, 9)
        Me.cmdCrear.Name = "cmdCrear"
        Me.cmdCrear.Size = New System.Drawing.Size(100, 24)
        Me.cmdCrear.TabIndex = 0
        Me.cmdCrear.Text = "&Salvar"
        '
        'GroupBox2
        '
        Me.GroupBox2.Controls.Add(Me.Button1)
        Me.GroupBox2.Controls.Add(Me.Label9)
        Me.GroupBox2.Controls.Add(Me.logo)
        Me.GroupBox2.Controls.Add(Me.Label6)
        Me.GroupBox2.Controls.Add(Me.usuario)
        Me.GroupBox2.Controls.Add(Me.Label7)
        Me.GroupBox2.Controls.Add(Me.clave)
        Me.GroupBox2.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.GroupBox2.Location = New System.Drawing.Point(395, 60)
        Me.GroupBox2.Name = "GroupBox2"
        Me.GroupBox2.Size = New System.Drawing.Size(387, 92)
        Me.GroupBox2.TabIndex = 11
        Me.GroupBox2.TabStop = False
        Me.GroupBox2.Text = "Acceso Web"
        '
        'Button1
        '
        Me.Button1.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.Button1.Location = New System.Drawing.Point(351, 58)
        Me.Button1.Name = "Button1"
        Me.Button1.Size = New System.Drawing.Size(25, 24)
        Me.Button1.TabIndex = 2
        Me.Button1.Text = "..."
        '
        'Label9
        '
        Me.Label9.Location = New System.Drawing.Point(8, 60)
        Me.Label9.Name = "Label9"
        Me.Label9.Size = New System.Drawing.Size(112, 21)
        Me.Label9.TabIndex = 27
        Me.Label9.Text = "Logotipo:"
        Me.Label9.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'logo
        '
        Me.logo.BackColor = System.Drawing.Color.White
        Me.logo.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.logo.Location = New System.Drawing.Point(168, 60)
        Me.logo.Name = "logo"
        Me.logo.ReadOnly = True
        Me.logo.Size = New System.Drawing.Size(181, 21)
        Me.logo.TabIndex = 2
        '
        'Label6
        '
        Me.Label6.Location = New System.Drawing.Point(8, 12)
        Me.Label6.Name = "Label6"
        Me.Label6.Size = New System.Drawing.Size(160, 21)
        Me.Label6.TabIndex = 25
        Me.Label6.Text = "Usuario:"
        Me.Label6.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'usuario
        '
        Me.usuario.BackColor = System.Drawing.Color.White
        Me.usuario.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.usuario.Location = New System.Drawing.Point(168, 12)
        Me.usuario.Name = "usuario"
        Me.usuario.Size = New System.Drawing.Size(208, 21)
        Me.usuario.TabIndex = 0
        '
        'Label7
        '
        Me.Label7.Location = New System.Drawing.Point(8, 36)
        Me.Label7.Name = "Label7"
        Me.Label7.Size = New System.Drawing.Size(112, 21)
        Me.Label7.TabIndex = 24
        Me.Label7.Text = "Clave:"
        Me.Label7.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'clave
        '
        Me.clave.BackColor = System.Drawing.Color.White
        Me.clave.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.clave.Location = New System.Drawing.Point(168, 36)
        Me.clave.Name = "clave"
        Me.clave.Size = New System.Drawing.Size(208, 21)
        Me.clave.TabIndex = 1
        '
        'GroupBox3
        '
        Me.GroupBox3.Controls.Add(Me.numeroUsuario)
        Me.GroupBox3.Controls.Add(Me.Label12)
        Me.GroupBox3.Controls.Add(Me.claveRecepcion)
        Me.GroupBox3.Controls.Add(Me.Label10)
        Me.GroupBox3.Controls.Add(Me.Label11)
        Me.GroupBox3.Controls.Add(Me.cbNumeroRecepcion)
        Me.GroupBox3.Location = New System.Drawing.Point(788, 60)
        Me.GroupBox3.Name = "GroupBox3"
        Me.GroupBox3.Size = New System.Drawing.Size(387, 92)
        Me.GroupBox3.TabIndex = 14
        Me.GroupBox3.TabStop = False
        Me.GroupBox3.Text = "Acceso SMS"
        '
        'numeroUsuario
        '
        Me.numeroUsuario.BackColor = System.Drawing.Color.White
        Me.numeroUsuario.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.numeroUsuario.Location = New System.Drawing.Point(168, 61)
        Me.numeroUsuario.Name = "numeroUsuario"
        Me.numeroUsuario.Size = New System.Drawing.Size(208, 21)
        Me.numeroUsuario.TabIndex = 2
        '
        'Label12
        '
        Me.Label12.Location = New System.Drawing.Point(8, 59)
        Me.Label12.Name = "Label12"
        Me.Label12.Size = New System.Drawing.Size(112, 21)
        Me.Label12.TabIndex = 30
        Me.Label12.Text = "Número Usuario:"
        Me.Label12.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'claveRecepcion
        '
        Me.claveRecepcion.BackColor = System.Drawing.Color.White
        Me.claveRecepcion.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.claveRecepcion.Location = New System.Drawing.Point(168, 37)
        Me.claveRecepcion.Name = "claveRecepcion"
        Me.claveRecepcion.Size = New System.Drawing.Size(208, 21)
        Me.claveRecepcion.TabIndex = 1
        '
        'Label10
        '
        Me.Label10.Location = New System.Drawing.Point(8, 36)
        Me.Label10.Name = "Label10"
        Me.Label10.Size = New System.Drawing.Size(112, 21)
        Me.Label10.TabIndex = 28
        Me.Label10.Text = "Clave:"
        Me.Label10.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'Label11
        '
        Me.Label11.Location = New System.Drawing.Point(8, 14)
        Me.Label11.Name = "Label11"
        Me.Label11.Size = New System.Drawing.Size(146, 21)
        Me.Label11.TabIndex = 27
        Me.Label11.Text = "Número Recepción:"
        Me.Label11.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'cbNumeroRecepcion
        '
        Me.cbNumeroRecepcion.BackColor = System.Drawing.Color.White
        Me.cbNumeroRecepcion.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cbNumeroRecepcion.Location = New System.Drawing.Point(168, 14)
        Me.cbNumeroRecepcion.Name = "cbNumeroRecepcion"
        Me.cbNumeroRecepcion.Size = New System.Drawing.Size(208, 21)
        Me.cbNumeroRecepcion.TabIndex = 0
        '
        'GroupBox4
        '
        Me.GroupBox4.Controls.Add(Me.cmdDelCancel)
        Me.GroupBox4.Controls.Add(Me.cmdAgrCancel)
        Me.GroupBox4.Controls.Add(Me.lbClavesCancelacion)
        Me.GroupBox4.Controls.Add(Me.Label15)
        Me.GroupBox4.Controls.Add(Me.respuestaCancelacion)
        Me.GroupBox4.Controls.Add(Me.Label16)
        Me.GroupBox4.Location = New System.Drawing.Point(395, 158)
        Me.GroupBox4.Name = "GroupBox4"
        Me.GroupBox4.Size = New System.Drawing.Size(386, 104)
        Me.GroupBox4.TabIndex = 15
        Me.GroupBox4.TabStop = False
        Me.GroupBox4.Text = "Cancelación"
        '
        'cmdDelCancel
        '
        Me.cmdDelCancel.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdDelCancel.Location = New System.Drawing.Point(350, 45)
        Me.cmdDelCancel.Name = "cmdDelCancel"
        Me.cmdDelCancel.Size = New System.Drawing.Size(25, 24)
        Me.cmdDelCancel.TabIndex = 1
        Me.cmdDelCancel.Text = "-"
        '
        'cmdAgrCancel
        '
        Me.cmdAgrCancel.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdAgrCancel.Location = New System.Drawing.Point(350, 17)
        Me.cmdAgrCancel.Name = "cmdAgrCancel"
        Me.cmdAgrCancel.Size = New System.Drawing.Size(25, 24)
        Me.cmdAgrCancel.TabIndex = 0
        Me.cmdAgrCancel.Text = "+"
        '
        'lbClavesCancelacion
        '
        Me.lbClavesCancelacion.FormattingEnabled = True
        Me.lbClavesCancelacion.Location = New System.Drawing.Point(167, 13)
        Me.lbClavesCancelacion.Name = "lbClavesCancelacion"
        Me.lbClavesCancelacion.Size = New System.Drawing.Size(181, 56)
        Me.lbClavesCancelacion.TabIndex = 0
        '
        'Label15
        '
        Me.Label15.Location = New System.Drawing.Point(6, 75)
        Me.Label15.Name = "Label15"
        Me.Label15.Size = New System.Drawing.Size(160, 21)
        Me.Label15.TabIndex = 30
        Me.Label15.Text = "Respuesta de Cancelación:"
        Me.Label15.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'respuestaCancelacion
        '
        Me.respuestaCancelacion.BackColor = System.Drawing.Color.White
        Me.respuestaCancelacion.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.respuestaCancelacion.Location = New System.Drawing.Point(168, 75)
        Me.respuestaCancelacion.MaxLength = 254
        Me.respuestaCancelacion.Name = "respuestaCancelacion"
        Me.respuestaCancelacion.Size = New System.Drawing.Size(208, 21)
        Me.respuestaCancelacion.TabIndex = 2
        '
        'Label16
        '
        Me.Label16.Location = New System.Drawing.Point(6, 13)
        Me.Label16.Name = "Label16"
        Me.Label16.Size = New System.Drawing.Size(160, 21)
        Me.Label16.TabIndex = 28
        Me.Label16.Text = "Frase Cancelación:"
        Me.Label16.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'GroupBox5
        '
        Me.GroupBox5.Controls.Add(Me.Label20)
        Me.GroupBox5.Controls.Add(Me.rutaShell)
        Me.GroupBox5.Location = New System.Drawing.Point(789, 268)
        Me.GroupBox5.Name = "GroupBox5"
        Me.GroupBox5.Size = New System.Drawing.Size(386, 69)
        Me.GroupBox5.TabIndex = 16
        Me.GroupBox5.TabStop = False
        Me.GroupBox5.Text = "Aplicación"
        '
        'Label20
        '
        Me.Label20.Location = New System.Drawing.Point(6, 12)
        Me.Label20.Name = "Label20"
        Me.Label20.Size = New System.Drawing.Size(160, 21)
        Me.Label20.TabIndex = 28
        Me.Label20.Text = "Ruta Aplicación:"
        Me.Label20.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'rutaShell
        '
        Me.rutaShell.BackColor = System.Drawing.Color.White
        Me.rutaShell.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.rutaShell.Location = New System.Drawing.Point(168, 14)
        Me.rutaShell.MaxLength = 254
        Me.rutaShell.Name = "rutaShell"
        Me.rutaShell.Size = New System.Drawing.Size(208, 21)
        Me.rutaShell.TabIndex = 0
        '
        'GroupBox6
        '
        Me.GroupBox6.Controls.Add(Me.Label23)
        Me.GroupBox6.Controls.Add(Me.cbSuscripciones)
        Me.GroupBox6.Controls.Add(Me.cmdEliReplicacion)
        Me.GroupBox6.Controls.Add(Me.cmdAgrReplicacion)
        Me.GroupBox6.Controls.Add(Me.lbReplicacion)
        Me.GroupBox6.Controls.Add(Me.lblSuscripcion)
        Me.GroupBox6.Location = New System.Drawing.Point(788, 158)
        Me.GroupBox6.Name = "GroupBox6"
        Me.GroupBox6.Size = New System.Drawing.Size(386, 104)
        Me.GroupBox6.TabIndex = 17
        Me.GroupBox6.TabStop = False
        Me.GroupBox6.Text = "Replicación"
        '
        'Label23
        '
        Me.Label23.Location = New System.Drawing.Point(8, 42)
        Me.Label23.Name = "Label23"
        Me.Label23.Size = New System.Drawing.Size(158, 33)
        Me.Label23.TabIndex = 35
        Me.Label23.Text = "Suscripcion(es) Replicadas:"
        Me.Label23.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'cbSuscripciones
        '
        Me.cbSuscripciones.BackColor = System.Drawing.Color.White
        Me.cbSuscripciones.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cbSuscripciones.Location = New System.Drawing.Point(167, 13)
        Me.cbSuscripciones.Name = "cbSuscripciones"
        Me.cbSuscripciones.Size = New System.Drawing.Size(181, 21)
        Me.cbSuscripciones.TabIndex = 0
        '
        'cmdEliReplicacion
        '
        Me.cmdEliReplicacion.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdEliReplicacion.Location = New System.Drawing.Point(350, 74)
        Me.cmdEliReplicacion.Name = "cmdEliReplicacion"
        Me.cmdEliReplicacion.Size = New System.Drawing.Size(25, 24)
        Me.cmdEliReplicacion.TabIndex = 2
        Me.cmdEliReplicacion.Text = "-"
        '
        'cmdAgrReplicacion
        '
        Me.cmdAgrReplicacion.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdAgrReplicacion.Location = New System.Drawing.Point(351, 13)
        Me.cmdAgrReplicacion.Name = "cmdAgrReplicacion"
        Me.cmdAgrReplicacion.Size = New System.Drawing.Size(25, 24)
        Me.cmdAgrReplicacion.TabIndex = 1
        Me.cmdAgrReplicacion.Text = "+"
        '
        'lbReplicacion
        '
        Me.lbReplicacion.FormattingEnabled = True
        Me.lbReplicacion.Location = New System.Drawing.Point(167, 42)
        Me.lbReplicacion.Name = "lbReplicacion"
        Me.lbReplicacion.Size = New System.Drawing.Size(181, 56)
        Me.lbReplicacion.TabIndex = 1
        '
        'lblSuscripcion
        '
        Me.lblSuscripcion.Location = New System.Drawing.Point(6, 13)
        Me.lblSuscripcion.Name = "lblSuscripcion"
        Me.lblSuscripcion.Size = New System.Drawing.Size(160, 21)
        Me.lblSuscripcion.TabIndex = 28
        Me.lblSuscripcion.Text = "Suscripción:"
        Me.lblSuscripcion.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'chkRequiereAprobacion
        '
        Me.chkRequiereAprobacion.AutoSize = True
        Me.chkRequiereAprobacion.Location = New System.Drawing.Point(167, 20)
        Me.chkRequiereAprobacion.Name = "chkRequiereAprobacion"
        Me.chkRequiereAprobacion.Size = New System.Drawing.Size(15, 14)
        Me.chkRequiereAprobacion.TabIndex = 1
        Me.chkRequiereAprobacion.UseVisualStyleBackColor = True
        '
        'Label28
        '
        Me.Label28.Location = New System.Drawing.Point(5, 15)
        Me.Label28.Name = "Label28"
        Me.Label28.Size = New System.Drawing.Size(142, 21)
        Me.Label28.TabIndex = 53
        Me.Label28.Text = "Requiere Aprobación:"
        Me.Label28.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'GroupBox7
        '
        Me.GroupBox7.Controls.Add(Me.chkRequiereAprobacion)
        Me.GroupBox7.Controls.Add(Me.Label28)
        Me.GroupBox7.Controls.Add(Me.Label29)
        Me.GroupBox7.Controls.Add(Me.txtCorreoAprobacion)
        Me.GroupBox7.Location = New System.Drawing.Point(395, 267)
        Me.GroupBox7.Name = "GroupBox7"
        Me.GroupBox7.Size = New System.Drawing.Size(386, 70)
        Me.GroupBox7.TabIndex = 29
        Me.GroupBox7.TabStop = False
        Me.GroupBox7.Text = "Aprobación"
        '
        'Label29
        '
        Me.Label29.Location = New System.Drawing.Point(5, 40)
        Me.Label29.Name = "Label29"
        Me.Label29.Size = New System.Drawing.Size(160, 21)
        Me.Label29.TabIndex = 28
        Me.Label29.Text = "Correo Aprobación:"
        Me.Label29.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'txtCorreoAprobacion
        '
        Me.txtCorreoAprobacion.BackColor = System.Drawing.Color.White
        Me.txtCorreoAprobacion.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.txtCorreoAprobacion.Location = New System.Drawing.Point(167, 39)
        Me.txtCorreoAprobacion.MaxLength = 254
        Me.txtCorreoAprobacion.Name = "txtCorreoAprobacion"
        Me.txtCorreoAprobacion.Size = New System.Drawing.Size(208, 21)
        Me.txtCorreoAprobacion.TabIndex = 2
        '
        'GroupBox8
        '
        Me.GroupBox8.Controls.Add(Me.chkMonitoreable)
        Me.GroupBox8.Controls.Add(Me.Label26)
        Me.GroupBox8.Controls.Add(Me.chkAplicarLlenadoVariables)
        Me.GroupBox8.Controls.Add(Me.lblAplicarLlenadoVariables)
        Me.GroupBox8.Controls.Add(Me.chkAplicarHorario)
        Me.GroupBox8.Controls.Add(Me.Label22)
        Me.GroupBox8.Controls.Add(Me.chkMedia)
        Me.GroupBox8.Controls.Add(Me.Label21)
        Me.GroupBox8.Controls.Add(Me.chkActiva)
        Me.GroupBox8.Controls.Add(Me.Label19)
        Me.GroupBox8.Controls.Add(Me.chkPriorizado)
        Me.GroupBox8.Controls.Add(Me.Label18)
        Me.GroupBox8.Location = New System.Drawing.Point(395, 343)
        Me.GroupBox8.Name = "GroupBox8"
        Me.GroupBox8.Size = New System.Drawing.Size(386, 118)
        Me.GroupBox8.TabIndex = 54
        Me.GroupBox8.TabStop = False
        Me.GroupBox8.Text = "Comportamiento"
        '
        'chkMonitoreable
        '
        Me.chkMonitoreable.AutoSize = True
        Me.chkMonitoreable.Location = New System.Drawing.Point(361, 62)
        Me.chkMonitoreable.Name = "chkMonitoreable"
        Me.chkMonitoreable.Size = New System.Drawing.Size(15, 14)
        Me.chkMonitoreable.TabIndex = 55
        Me.chkMonitoreable.UseVisualStyleBackColor = True
        '
        'Label26
        '
        Me.Label26.Location = New System.Drawing.Point(202, 59)
        Me.Label26.Name = "Label26"
        Me.Label26.Size = New System.Drawing.Size(112, 21)
        Me.Label26.TabIndex = 61
        Me.Label26.Text = "Monitoreable:"
        Me.Label26.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'chkAplicarLlenadoVariables
        '
        Me.chkAplicarLlenadoVariables.AutoSize = True
        Me.chkAplicarLlenadoVariables.Location = New System.Drawing.Point(167, 63)
        Me.chkAplicarLlenadoVariables.Name = "chkAplicarLlenadoVariables"
        Me.chkAplicarLlenadoVariables.Size = New System.Drawing.Size(15, 14)
        Me.chkAplicarLlenadoVariables.TabIndex = 52
        Me.chkAplicarLlenadoVariables.UseVisualStyleBackColor = True
        '
        'lblAplicarLlenadoVariables
        '
        Me.lblAplicarLlenadoVariables.Location = New System.Drawing.Point(8, 60)
        Me.lblAplicarLlenadoVariables.Name = "lblAplicarLlenadoVariables"
        Me.lblAplicarLlenadoVariables.Size = New System.Drawing.Size(153, 21)
        Me.lblAplicarLlenadoVariables.TabIndex = 60
        Me.lblAplicarLlenadoVariables.Text = "Aplicar Llenado Variables:"
        Me.lblAplicarLlenadoVariables.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'chkAplicarHorario
        '
        Me.chkAplicarHorario.AutoSize = True
        Me.chkAplicarHorario.Location = New System.Drawing.Point(167, 42)
        Me.chkAplicarHorario.Name = "chkAplicarHorario"
        Me.chkAplicarHorario.Size = New System.Drawing.Size(15, 14)
        Me.chkAplicarHorario.TabIndex = 51
        Me.chkAplicarHorario.UseVisualStyleBackColor = True
        '
        'Label22
        '
        Me.Label22.Location = New System.Drawing.Point(8, 39)
        Me.Label22.Name = "Label22"
        Me.Label22.Size = New System.Drawing.Size(112, 21)
        Me.Label22.TabIndex = 59
        Me.Label22.Text = "Aplicar Horario:"
        Me.Label22.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'chkMedia
        '
        Me.chkMedia.AutoSize = True
        Me.chkMedia.Location = New System.Drawing.Point(167, 21)
        Me.chkMedia.Name = "chkMedia"
        Me.chkMedia.Size = New System.Drawing.Size(15, 14)
        Me.chkMedia.TabIndex = 50
        Me.chkMedia.UseVisualStyleBackColor = True
        '
        'Label21
        '
        Me.Label21.Location = New System.Drawing.Point(8, 18)
        Me.Label21.Name = "Label21"
        Me.Label21.Size = New System.Drawing.Size(112, 21)
        Me.Label21.TabIndex = 58
        Me.Label21.Text = "Habilitar Media:"
        Me.Label21.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'chkActiva
        '
        Me.chkActiva.AutoSize = True
        Me.chkActiva.Location = New System.Drawing.Point(361, 41)
        Me.chkActiva.Name = "chkActiva"
        Me.chkActiva.Size = New System.Drawing.Size(15, 14)
        Me.chkActiva.TabIndex = 54
        Me.chkActiva.UseVisualStyleBackColor = True
        '
        'Label19
        '
        Me.Label19.Location = New System.Drawing.Point(202, 38)
        Me.Label19.Name = "Label19"
        Me.Label19.Size = New System.Drawing.Size(153, 21)
        Me.Label19.TabIndex = 57
        Me.Label19.Text = "Activa:"
        Me.Label19.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'chkPriorizado
        '
        Me.chkPriorizado.AutoSize = True
        Me.chkPriorizado.Location = New System.Drawing.Point(361, 19)
        Me.chkPriorizado.Name = "chkPriorizado"
        Me.chkPriorizado.Size = New System.Drawing.Size(15, 14)
        Me.chkPriorizado.TabIndex = 53
        Me.chkPriorizado.UseVisualStyleBackColor = True
        '
        'Label18
        '
        Me.Label18.Location = New System.Drawing.Point(202, 16)
        Me.Label18.Name = "Label18"
        Me.Label18.Size = New System.Drawing.Size(112, 21)
        Me.Label18.TabIndex = 56
        Me.Label18.Text = "Priorizado:"
        Me.Label18.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'GroupBox9
        '
        Me.GroupBox9.Controls.Add(Me.Label14)
        Me.GroupBox9.Controls.Add(Me.renovacionC)
        Me.GroupBox9.Controls.Add(Me.Label13)
        Me.GroupBox9.Controls.Add(Me.renovacionB)
        Me.GroupBox9.Controls.Add(Me.Label8)
        Me.GroupBox9.Controls.Add(Me.renovacionA)
        Me.GroupBox9.Location = New System.Drawing.Point(788, 343)
        Me.GroupBox9.Name = "GroupBox9"
        Me.GroupBox9.Size = New System.Drawing.Size(386, 118)
        Me.GroupBox9.TabIndex = 62
        Me.GroupBox9.TabStop = False
        Me.GroupBox9.Text = "Renovacion"
        '
        'Label14
        '
        Me.Label14.Location = New System.Drawing.Point(9, 66)
        Me.Label14.Name = "Label14"
        Me.Label14.Size = New System.Drawing.Size(160, 21)
        Me.Label14.TabIndex = 36
        Me.Label14.Text = "Mensaje de Renovación C:"
        Me.Label14.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'renovacionC
        '
        Me.renovacionC.BackColor = System.Drawing.Color.White
        Me.renovacionC.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.renovacionC.Location = New System.Drawing.Point(169, 66)
        Me.renovacionC.MaxLength = 254
        Me.renovacionC.Name = "renovacionC"
        Me.renovacionC.Size = New System.Drawing.Size(208, 21)
        Me.renovacionC.TabIndex = 33
        '
        'Label13
        '
        Me.Label13.Location = New System.Drawing.Point(9, 42)
        Me.Label13.Name = "Label13"
        Me.Label13.Size = New System.Drawing.Size(160, 21)
        Me.Label13.TabIndex = 35
        Me.Label13.Text = "Mensaje de Renovación B:"
        Me.Label13.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'renovacionB
        '
        Me.renovacionB.BackColor = System.Drawing.Color.White
        Me.renovacionB.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.renovacionB.Location = New System.Drawing.Point(169, 42)
        Me.renovacionB.MaxLength = 254
        Me.renovacionB.Name = "renovacionB"
        Me.renovacionB.Size = New System.Drawing.Size(208, 21)
        Me.renovacionB.TabIndex = 32
        '
        'Label8
        '
        Me.Label8.Location = New System.Drawing.Point(9, 18)
        Me.Label8.Name = "Label8"
        Me.Label8.Size = New System.Drawing.Size(160, 21)
        Me.Label8.TabIndex = 34
        Me.Label8.Text = "Mensaje de Renovación A:"
        Me.Label8.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'renovacionA
        '
        Me.renovacionA.BackColor = System.Drawing.Color.White
        Me.renovacionA.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.renovacionA.Location = New System.Drawing.Point(169, 18)
        Me.renovacionA.MaxLength = 254
        Me.renovacionA.Name = "renovacionA"
        Me.renovacionA.Size = New System.Drawing.Size(208, 21)
        Me.renovacionA.TabIndex = 31
        '
        'Label33
        '
        Me.Label33.Location = New System.Drawing.Point(9, 361)
        Me.Label33.Name = "Label33"
        Me.Label33.Size = New System.Drawing.Size(153, 21)
        Me.Label33.TabIndex = 59
        Me.Label33.Text = "Costo:"
        Me.Label33.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'costo
        '
        Me.costo.BackColor = System.Drawing.Color.White
        Me.costo.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.costo.Location = New System.Drawing.Point(168, 361)
        Me.costo.Name = "costo"
        Me.costo.Size = New System.Drawing.Size(208, 21)
        Me.costo.TabIndex = 58
        Me.costo.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'agrSuscripcion
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(6, 14)
        Me.ClientSize = New System.Drawing.Size(1195, 508)
        Me.Controls.Add(Me.GroupBox9)
        Me.Controls.Add(Me.GroupBox8)
        Me.Controls.Add(Me.GroupBox6)
        Me.Controls.Add(Me.GroupBox7)
        Me.Controls.Add(Me.GroupBox1)
        Me.Controls.Add(Me.GroupBox2)
        Me.Controls.Add(Me.GroupBox3)
        Me.Controls.Add(Me.GroupBox4)
        Me.Controls.Add(Me.GroupBox5)
        Me.Name = "agrSuscripcion"
        Me.pTitle = "Crear Suscripción"
        Me.Text = "Crear Suscripción"
        Me.Controls.SetChildIndex(Me.GroupBox5, 0)
        Me.Controls.SetChildIndex(Me.pbUpper, 0)
        Me.Controls.SetChildIndex(Me.GroupBox4, 0)
        Me.Controls.SetChildIndex(Me.GroupBox3, 0)
        Me.Controls.SetChildIndex(Me.GroupBox2, 0)
        Me.Controls.SetChildIndex(Me.GroupBox1, 0)
        Me.Controls.SetChildIndex(Me.GroupBox7, 0)
        Me.Controls.SetChildIndex(Me.cmdPanel, 0)
        Me.Controls.SetChildIndex(Me.GroupBox6, 0)
        Me.Controls.SetChildIndex(Me.GroupBox8, 0)
        Me.Controls.SetChildIndex(Me.GroupBox9, 0)
        CType(Me.pbUpper, System.ComponentModel.ISupportInitialize).EndInit()
        Me.cmdPanel.ResumeLayout(False)
        CType(Me.imgLinea, System.ComponentModel.ISupportInitialize).EndInit()
        Me.GroupBox1.ResumeLayout(False)
        Me.GroupBox1.PerformLayout()
        CType(Me.ErrorProvider, System.ComponentModel.ISupportInitialize).EndInit()
        Me.GroupBox2.ResumeLayout(False)
        Me.GroupBox2.PerformLayout()
        Me.GroupBox3.ResumeLayout(False)
        Me.GroupBox3.PerformLayout()
        Me.GroupBox4.ResumeLayout(False)
        Me.GroupBox4.PerformLayout()
        Me.GroupBox5.ResumeLayout(False)
        Me.GroupBox5.PerformLayout()
        Me.GroupBox6.ResumeLayout(False)
        Me.GroupBox7.ResumeLayout(False)
        Me.GroupBox7.PerformLayout()
        Me.GroupBox8.ResumeLayout(False)
        Me.GroupBox8.PerformLayout()
        Me.GroupBox9.ResumeLayout(False)
        Me.GroupBox9.PerformLayout()
        Me.ResumeLayout(False)

    End Sub
#End Region
    Private vID As Integer = -1
    Private eliminados() As Integer
    Private eliminadosReplicacion() As Integer

    Private Sub cmdCrear_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdCrear.Click
        If validar() Then If agregar() Then Dispose()
    End Sub
    Function validar() As Boolean
        ErrorProvider.SetError(cbNumeros, "")
        ErrorProvider.SetError(nombre, "")
        ErrorProvider.SetError(nombreComercial, "")
        ErrorProvider.SetError(duracion, "")
        ErrorProvider.SetError(respuesta, "")
        ErrorProvider.SetError(lbclaves, "")
        ErrorProvider.SetError(usuario, "")
        ErrorProvider.SetError(respuestaAdicional, "")
        ErrorProvider.SetError(renovacionA, "")
        ErrorProvider.SetError(renovacionB, "")
        ErrorProvider.SetError(renovacionC, "")
        ErrorProvider.SetError(clave, "")
        ErrorProvider.SetError(logo, "")

        ErrorProvider.SetError(cbNumeroRecepcion, "")
        ErrorProvider.SetError(claveRecepcion, "")
        ErrorProvider.SetError(numeroUsuario, "")
        ErrorProvider.SetError(cbNumerosSalida, "")
        ErrorProvider.SetError(cbNumeroEscalonado, "")
        ErrorProvider.SetError(cbNumeroAdicional, "")
        ErrorProvider.SetError(respuestaCancelacion, "")

        validar = False
        If cbNumeros.SelectedIndex = -1 Then
            ErrorProvider.SetError(cbNumeros, "Debe escoger un número")
        ElseIf cbNumerosSalida.SelectedIndex = -1 Then
            ErrorProvider.SetError(cbNumerosSalida, "Debe escoger un número de salida")
        ElseIf cbNumeroAdicional.SelectedIndex = -1 Then
            ErrorProvider.SetError(cbNumeroAdicional, "Debe escoger un número de salida")
        ElseIf nombre.Text = "" Then
            ErrorProvider.SetError(nombre, "Debe ingresar un nombre")
        ElseIf claveServicio.Text = "" Then
            ErrorProvider.SetError(claveServicio, "Debe escoger una clave de servicio")
        ElseIf nombreComercial.Text = "" Then
            ErrorProvider.SetError(nombre, "Debe ingresar un nombre comercial")
        ElseIf duracion.Text = "" Then
            ErrorProvider.SetError(duracion, "Debe ingresar una duracion")
        ElseIf lbclaves.Items.Count = 0 Then
            ErrorProvider.SetError(lbclaves, "Debe haber al menos una clave")
        ElseIf respuesta.Text = "" Then
            ErrorProvider.SetError(respuesta, "Debe ingresar un mensaje de respuesta")
            'ElseIf respuestaAdicional.Text = "" Then
            '    ErrorProvider.SetError(respuestaAdicional, "Debe ingresar un mensaje de renovacion")
        ElseIf renovacionA.Text = "" Then
            ErrorProvider.SetError(renovacionA, "Debe ingresar un mensaje de renovacion")
        ElseIf renovacionB.Text = "" Then
            ErrorProvider.SetError(renovacionB, "Debe ingresar un mensaje de renovacion")
        ElseIf renovacionC.Text = "" Then
            ErrorProvider.SetError(renovacionC, "Debe ingresar un mensaje de renovacion")
        ElseIf usuario.Text = "" Then
            ErrorProvider.SetError(usuario, "Debe ingresar un usuario")
        ElseIf clave.Text = "" Then
            ErrorProvider.SetError(clave, "Debe ingresar una clave")
        ElseIf Not validarPass(clave.Text) Then
            ErrorProvider.SetError(clave, "La clave debe incluir una mayúscula, un numero y tener logitud de al menos 8 caracteres")
        ElseIf cbNumeroRecepcion.SelectedIndex = -1 Then
            ErrorProvider.SetError(cbNumeroRecepcion, "Debe escoger un número de recepción")
        ElseIf claveRecepcion.Text = "" Then
            ErrorProvider.SetError(claveRecepcion, "Debe ingresar una clave de recepción")
        ElseIf Len(numeroUsuario.Text) <> 11 Then
            ErrorProvider.SetError(numeroUsuario, "Debe ingresar una número de usuario de 11 dígitos")
        ElseIf respuestaCancelacion.Text = "" Then
            ErrorProvider.SetError(respuestaCancelacion, "Debe ingresar una respuesta de cancelación")
        ElseIf Not existeArchivo(logo.Text) Then
            If logo.Text.Length = 0 Then
                validar = True
            Else
                ErrorProvider.SetError(logo, "Debe escoger un archivo valido")
            End If
        Else
            validar = True
        End If
    End Function

    Function agregar() As Boolean
        agregar = False
        If CNX.State <> ConnectionState.Open Then CNX.Open()
        Dim numero As String = valor(cbNumeros)
        Dim v_numeroRecepcion As String = valor(cbNumeroRecepcion)
        Dim v_numeroSalida As String = valor(cbNumerosSalida)
        Dim v_numeroEscalonado As String = "NULL"
        If cbNumeroEscalonado.SelectedIndex > -1 Then v_numeroEscalonado = "'" & valor(cbNumeroEscalonado) & "'"
        Dim v_numeroAdicional As String = valor(cbNumeroAdicional)

        Dim useLogo As Boolean = existeArchivo(logo.Text)
        Dim tipo As String = "", archivo As Byte() = {}
        If useLogo Then
            Dim fs As FileStream = New FileStream(logo.Text, FileMode.Open, FileAccess.Read)
            Dim FileSize As Integer = fs.Length

            archivo = New Byte(FileSize) {}
            fs.Read(archivo, 0, FileSize)
            fs.Close()

            tipo = "jpeg"
        End If

        Dim strCMD As String
        If vID = -1 Then
            strCMD = String.Format("INSERT INTO suscripciones (numero, nombre, nombreComercial, respuesta, respuestaAdicional, renovacionA, renovacionB, renovacionC, duracion, usuario, clave, numeroUsuario, numeroRecepcion, numeroSalida, respuestaCancelacion, priorizado, activa, habilitar_media, monitoreable, aplicarHorario, aplicarLlenadoVariables, limiteMensajesDiarios, costo, requiereAprobacion, correoAprobacion, numeroAdicional, numeroEscalonado, claveServicio, rutaShell, logo_tipo, logo_archivo) VALUES ('{0}','{1}','{2}','{3}','{4}','{5}','{6}','{7}',{8},'{9}','{10}','{11}','{12}','{13}','{14}',{15},{16},{17},{18},{19},{20},'{21}','{22}','{23}','{24}',{25},'{26}','{27}','{28}','{29}',@logo_archivo)", numero, nombre.Text, nombreComercial.Text, respuesta.Text, respuestaAdicional.Text, renovacionA.Text, renovacionB.Text, renovacionC.Text, duracion.Text, usuario.Text, clave.Text, numeroUsuario.Text, v_numeroRecepcion, v_numeroSalida, respuestaCancelacion.Text, IIf(chkPriorizado.Checked, 1, 0), IIf(chkActiva.Checked, 1, 0), IIf(chkMedia.Checked, 1, 0), IIf(chkMonitoreable.Checked, 1, 0), IIf(chkAplicarHorario.Checked, 1, 0), IIf(chkAplicarLlenadoVariables.Checked, 1, 0), CInt(limiteMensajesDiario.Text), costo.Text, IIf(chkRequiereAprobacion.Checked, 1, 0), txtCorreoAprobacion.Text, v_numeroAdicional, v_numeroEscalonado, claveServicio.Text, rutaShell.Text, tipo)
        Else
            strCMD = String.Format("UPDATE suscripciones SET numero='{0}', nombre='{1}', nombreComercial='{2}', duracion={3}, respuesta='{4}', respuestaAdicional='{5}', renovacionA='{6}', renovacionB='{7}', renovacionC='{8}', usuario='{9}', clave='{10}', numeroUsuario='{11}', numeroRecepcion='{12}', numeroSalida='{13}', respuestaCancelacion='{14}', priorizado={15}, activa={16}, habilitar_media={17}, monitoreable={18}, aplicarHorario={19}, aplicarLlenadoVariables={20}, limiteMensajesDiarios={21}, costo='{22}', requiereAprobacion={23}, correoAprobacion='{24}', numeroAdicional='{25}', numeroEscalonado={26}, claveServicio='{27}', rutaShell='{28}'", numero, nombre.Text, nombreComercial.Text, duracion.Text, respuesta.Text, respuestaAdicional.Text, renovacionA.Text, renovacionB.Text, renovacionC.Text, usuario.Text, clave.Text, numeroUsuario.Text, v_numeroRecepcion, v_numeroSalida, respuestaCancelacion.Text, IIf(chkPriorizado.Checked, 1, 0), IIf(chkActiva.Checked, 1, 0), IIf(chkMedia.Checked, 1, 0), IIf(chkMonitoreable.Checked, 1, 0), IIf(chkAplicarHorario.Checked, 1, 0), IIf(chkAplicarLlenadoVariables.Checked, 1, 0), CInt(limiteMensajesDiario.Text), costo.Text, IIf(chkRequiereAprobacion.Checked, 1, 0), txtCorreoAprobacion.Text, v_numeroAdicional, v_numeroEscalonado, claveServicio.Text, rutaShell.Text)
            If useLogo Then strCMD &= String.Format(",logo_tipo='{0}', logo_archivo=@logo_archivo", tipo)
            strCMD &= " WHERE id = " & vID
        End If
        Const strCMD1 As String = "INSERT INTO claves (clave, idSuscripcionCancelacion) VALUES "
        Const strCMD2 As String = "INSERT INTO claves (clave, idSuscripcion) VALUES"
        Const strCMD3 As String = "DELETE FROM claves WHERE id="
        Const strCMD4 As String = "REPLACE INTO suscripciones_replicaciones (padre, hijo) VALUES "
        Const strCMD5 As String = "DELETE FROM suscripciones_replicaciones WHERE padre="

        Dim cmd As New MySqlCommand(strCMD, connectionOne)
        If connectionOne.State <> ConnectionState.Open Then connectionOne.Open()
        If useLogo Then cmd.Parameters.AddWithValue("@logo_archivo", archivo)
        Try
            cmd.ExecuteNonQuery()

            cmd.CommandText = "DELETE FROM claves WHERE idSuscripcionRecepcion=" & vID
            cmd.ExecuteNonQuery()

            'cmd.CommandText = "DELETE FROM claves WHERE idSuscripcionCancelacion=" & vID
            'cmd.ExecuteNonQuery()

            If vID = -1 Then
                Dim dr As Integer = New MySqlCommand(String.Format("SELECT id FROM suscripciones WHERE nombre='{0}'", nombre.Text), CNX).ExecuteScalar
                vID = dr
            End If

            cmd.CommandText = String.Format("INSERT INTO claves (clave, idSuscripcionRecepcion) VALUES ('{0}', '{1}')", claveRecepcion.Text, vID)
            cmd.ExecuteNonQuery()

            Dim i As Integer
            For i = 0 To lbclaves.Items.Count - 1
                If valor(lbclaves, i, False) = -1 Then
                    Dim clave As String = getNombre(lbclaves, i, False)
                    cmd.CommandText = String.Format("{0}('{1}',{2})", strCMD2, clave, vID)
                    cmd.ExecuteNonQuery()
                End If
            Next
            For i = 0 To lbClavesCancelacion.Items.Count - 1
                If valor(lbClavesCancelacion, i, False) = -1 Then
                    Dim clave As String = getNombre(lbClavesCancelacion, i, False)
                    cmd.CommandText = String.Format("{0} ('{1}',{2})", strCMD1, clave, vID)
                    cmd.ExecuteNonQuery()
                End If
            Next
            For i = 0 To lbReplicacion.Items.Count - 1
                Dim clave As Integer = valor(lbReplicacion, i, False)
                cmd.CommandText = String.Format("{0}('{1}',{2})", strCMD4, vID, clave)
                cmd.ExecuteNonQuery()
            Next
            If Not eliminados Is Nothing Then
                For i = 0 To eliminados.Length - 1
                    cmd.CommandText = strCMD3 & eliminados(i)
                    cmd.ExecuteNonQuery()
                Next
            End If
            If Not eliminadosReplicacion Is Nothing Then
                For i = 0 To eliminadosReplicacion.Length - 1
                    cmd.CommandText = String.Format("{0}{1} AND hijo={2}", strCMD5, vID, eliminadosReplicacion(i))
                    cmd.ExecuteNonQuery()
                Next
            End If
            agregar = True
        Catch ex As MySqlException
            MsgBox(String.Format("Error al ejecutar comando: {0}: {1}", cmd.CommandText, ex.Message))
        End Try
    End Function

    Private Sub agrSuscripcion_Load(ByVal sender As Object, ByVal e As EventArgs) Handles MyBase.Load
        campoNumerico(duracion)
        campoNumerico(numeroUsuario)
    End Sub
    Private Sub cmdAgr_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdAgr.Click
        Dim nvaClave As String = InputBox("Ingrese una clave: ")
        If nvaClave.Length = 0 Then
            MsgBox("No se permiten claves nulas")
        Else
            lbclaves.Items.Add(New listitem(nvaClave, -1))
        End If
    End Sub
    Private Sub cmdEli_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdDel.Click
        If lbclaves.SelectedIndex = -1 Then
            MsgBox("Debe seleccionar una clave")
        Else
            If valor(lbclaves, lbclaves.SelectedIndex, False) <> -1 Then
                If eliminados Is Nothing Then
                    ReDim eliminados(0)
                Else
                    ReDim Preserve eliminados(eliminados.Length)
                End If
                eliminados(eliminados.Length - 1) = valor(lbclaves)
            End If
            lbclaves.Items.RemoveAt(lbclaves.SelectedIndex)
        End If
    End Sub

    Private Sub Button1_Click(ByVal sender As Object, ByVal e As EventArgs) Handles Button1.Click
        Dim o As New OpenFileDialog() With {.Filter = "Archivos de Imagen|*.jpg"}
        o.ShowDialog()
        logo.Text = o.FileName
    End Sub

    Private Sub cmdAgrCancel_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdAgrCancel.Click
        Dim nvaClave As String = InputBox("Ingrese una clave: ")
        If nvaClave.Length = 0 Then
            MsgBox("No se permiten claves nulas")
        Else
            lbClavesCancelacion.Items.Add(New listitem(nvaClave, -1))
        End If
    End Sub
    Private Sub cmdDelCancel_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdDelCancel.Click
        With lbClavesCancelacion
            If .SelectedIndex = -1 Then
                showError("Debe seleccionar una clave")
            Else
                If valor(lbClavesCancelacion, .SelectedIndex, False) <> -1 Then
                    If eliminados Is Nothing Then
                        ReDim eliminados(0)
                    Else
                        ReDim Preserve eliminados(eliminados.Length)
                    End If
                    eliminados(eliminados.Length - 1) = valor(lbClavesCancelacion)
                End If
                .Items.RemoveAt(.SelectedIndex)
            End If
        End With
    End Sub

    Private Sub cmdEliReplicacion_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdEliReplicacion.Click
        If lbReplicacion.SelectedIndex = -1 Then
            showError("Debe seleccionar una clave")
        Else
            If valor(lbReplicacion, lbReplicacion.SelectedIndex, False) <> -1 Then
                If eliminadosReplicacion Is Nothing Then
                    ReDim eliminadosReplicacion(0)
                Else
                    ReDim Preserve eliminadosReplicacion(eliminadosReplicacion.Length)
                End If
                eliminadosReplicacion(eliminadosReplicacion.Length - 1) = valor(lbReplicacion)
            End If
            lbReplicacion.Items.RemoveAt(lbReplicacion.SelectedIndex)
        End If
    End Sub
    Private Sub cmdAgrReplicacion_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdAgrReplicacion.Click
        With cbSuscripciones
            If .SelectedIndex >= 0 Then
                Dim myitem As listitem = CType(cbSuscripciones.SelectedItem, listitem)
                lbReplicacion.Items.Add(New listitem(myitem.name, myitem.id))
            End If
        End With
    End Sub
End Class
