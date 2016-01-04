Imports MySql.Data.MySqlClient
Imports System.IO

Public Class agrRifa
    Inherits formControl.frmTemplate

#Region " Windows Form Designer generated code "
    Public Sub New()
        MyBase.New()
        InitializeComponent()
        fncListboxMySQL.popularMySQL(CObj(cbNumeros), "numeros", "numero", "numero", CNX)
        fncListboxMySQL.popularMySQL(CObj(cbRifas), "rifas", "id", "nombre", CNX, "estado=1")
        estado.Checked = True
    End Sub
    Sub New(ByVal id As Integer)
        MyBase.New()
        InitializeComponent()
        fncListboxMySQL.popularMySQL(CObj(cbNumeros), "numeros", "numero", "numero", CNX)
        fncListboxMySQL.popularMySQL(CObj(cbRifas), "rifas", "id", "nombre", CNX, "estado=1")
        vID = id
        pTitle = "Modificar Rifa"
        cmdCrear.Text = "Modificar"
        If CNX.State <> ConnectionState.Open Then CNX.Open()
        Dim dr As MySqlDataReader = New MySqlCommand("SELECT nombre, cantidad_ganadores, mensaje_participante_a, mensaje_participante_b, mensaje_adicional, limite_participante, mensaje_ganador, chat, selfservice, soloLectura, sonido, estado, usuario, clave, claveAdmin, numero, autoRefresh FROM rifas WHERE id=" & id, CNX).ExecuteReader
        If dr.Read Then
            nombre.Text = dr!nombre
            ganadores.Text = dr!cantidad_ganadores
            mensaje_participantes_a.Text = dr!mensaje_participante_a
            mensaje_participantes_b.Text = dr!mensaje_participante_b
            mensaje_adicional.Text = dr!mensaje_adicional
            limite_participante.Text = dr!limite_participante
            mensaje_ganador.Text = dr!mensaje_ganador
            usuario.Text = dr!usuario
            clave.Text = dr!clave
            claveAdmin.Text = dr!claveAdmin
            chat.Checked = IIf(dr!chat = 1, True, False)
            selfservice.Checked = IIf(dr!selfservice = 1, True, False)
            soloLectura.Checked = IIf(dr!soloLectura = 1, True, False)
            sonido.Checked = IIf(dr!sonido = 1, True, False)
            estado.Checked = IIf(dr!estado = 1, True, False)
            autoRefresh.Checked = IIf(dr!autoRefresh = 1, True, False)
            seleccionar(cbNumeros, dr!numero)
            dr.Close()
            fncListboxMySQL.popularMySQL(CObj(lbclaves), "claves", "id", "clave", CNX, "idRifa=" & vID)
            fncListboxMySQL.popularMySQL(CObj(lbBloqueos), "rifas_bloqueados", "id", "palabra", CNX, "idRifa=" & vID)
            fncListboxMySQL.popularMySQL(CObj(lbReplicacion), "rifas", "id", "nombre", CNX, String.Format("id IN (SELECT hijo FROM rifas_replicaciones WHERE padre={0})", vID))
        Else
            dr.Close()
            MsgBox("Registro no encontrado")
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
    Friend WithEvents ErrorProvider As ErrorProvider
    Friend WithEvents cmdCrear As Button
    Friend WithEvents nombre As TextBox
    Friend WithEvents Label2 As Label
    Friend WithEvents ganadores As TextBox
    Friend WithEvents Label4 As Label
    Friend WithEvents mensaje_ganador As TextBox
    Friend WithEvents Label5 As Label
    Friend WithEvents mensaje_participantes_a As TextBox
    Friend WithEvents cbNumeros As ComboBox
    Friend WithEvents Label6 As Label
    Friend WithEvents cmdEli As Button
    Friend WithEvents cmdAgr As Button
    Friend WithEvents lbclaves As ListBox
    Friend WithEvents Label1 As Label
    Friend WithEvents GroupBox2 As GroupBox
    Friend WithEvents Label7 As Label
    Friend WithEvents usuario As TextBox
    Friend WithEvents Label8 As Label
    Friend WithEvents clave As TextBox
    Friend WithEvents Button1 As Button
    Friend WithEvents Label9 As Label
    Friend WithEvents Label10 As Label
    Friend WithEvents claveAdmin As TextBox
    Friend WithEvents Label11 As Label
    Friend WithEvents chat As CheckBox
    Friend WithEvents Label12 As Label
    Friend WithEvents selfservice As CheckBox
    Friend WithEvents limite_participante As TextBox
    Friend WithEvents Label14 As Label
    Friend WithEvents Label13 As Label
    Friend WithEvents mensaje_participantes_b As TextBox
    Friend WithEvents Label15 As Label
    Friend WithEvents mensaje_adicional As TextBox
    Friend WithEvents GroupBox3 As GroupBox
    Friend WithEvents Label16 As Label
    Friend WithEvents estado As CheckBox
    Friend WithEvents gbBloqueos As GroupBox
    Friend WithEvents cmdEliB As Button
    Friend WithEvents cmdAgrB As Button
    Friend WithEvents lbBloqueos As ListBox
    Friend WithEvents Label17 As Label
    Friend WithEvents GroupBox6 As GroupBox
    Friend WithEvents Label23 As Label
    Friend WithEvents cbRifas As ComboBox
    Friend WithEvents cmdEliReplicacion As Button
    Friend WithEvents cmdAgrReplicacion As Button
    Friend WithEvents lbReplicacion As ListBox
    Friend WithEvents lblSuscripcion As Label
    Friend WithEvents Label18 As Label
    Friend WithEvents sonido As CheckBox
    Friend WithEvents soloLectura As CheckBox
    Friend WithEvents Label19 As Label
    Friend WithEvents autoRefresh As CheckBox
    Friend WithEvents lblAutoRefresh As Label
    Friend WithEvents logo As TextBox
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.components = New System.ComponentModel.Container()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.Label16 = New System.Windows.Forms.Label()
        Me.estado = New System.Windows.Forms.CheckBox()
        Me.Label15 = New System.Windows.Forms.Label()
        Me.mensaje_adicional = New System.Windows.Forms.TextBox()
        Me.limite_participante = New System.Windows.Forms.TextBox()
        Me.Label14 = New System.Windows.Forms.Label()
        Me.Label13 = New System.Windows.Forms.Label()
        Me.mensaje_participantes_b = New System.Windows.Forms.TextBox()
        Me.cmdEli = New System.Windows.Forms.Button()
        Me.cmdAgr = New System.Windows.Forms.Button()
        Me.lbclaves = New System.Windows.Forms.ListBox()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.Label6 = New System.Windows.Forms.Label()
        Me.cbNumeros = New System.Windows.Forms.ComboBox()
        Me.Label5 = New System.Windows.Forms.Label()
        Me.mensaje_participantes_a = New System.Windows.Forms.TextBox()
        Me.Label4 = New System.Windows.Forms.Label()
        Me.mensaje_ganador = New System.Windows.Forms.TextBox()
        Me.Label2 = New System.Windows.Forms.Label()
        Me.ganadores = New System.Windows.Forms.TextBox()
        Me.Label3 = New System.Windows.Forms.Label()
        Me.nombre = New System.Windows.Forms.TextBox()
        Me.Label12 = New System.Windows.Forms.Label()
        Me.selfservice = New System.Windows.Forms.CheckBox()
        Me.Label11 = New System.Windows.Forms.Label()
        Me.chat = New System.Windows.Forms.CheckBox()
        Me.ErrorProvider = New System.Windows.Forms.ErrorProvider(Me.components)
        Me.cmdCrear = New System.Windows.Forms.Button()
        Me.GroupBox2 = New System.Windows.Forms.GroupBox()
        Me.Label10 = New System.Windows.Forms.Label()
        Me.claveAdmin = New System.Windows.Forms.TextBox()
        Me.Button1 = New System.Windows.Forms.Button()
        Me.Label9 = New System.Windows.Forms.Label()
        Me.logo = New System.Windows.Forms.TextBox()
        Me.usuario = New System.Windows.Forms.TextBox()
        Me.Label8 = New System.Windows.Forms.Label()
        Me.clave = New System.Windows.Forms.TextBox()
        Me.Label7 = New System.Windows.Forms.Label()
        Me.GroupBox3 = New System.Windows.Forms.GroupBox()
        Me.soloLectura = New System.Windows.Forms.CheckBox()
        Me.Label19 = New System.Windows.Forms.Label()
        Me.Label18 = New System.Windows.Forms.Label()
        Me.sonido = New System.Windows.Forms.CheckBox()
        Me.gbBloqueos = New System.Windows.Forms.GroupBox()
        Me.cmdEliB = New System.Windows.Forms.Button()
        Me.cmdAgrB = New System.Windows.Forms.Button()
        Me.lbBloqueos = New System.Windows.Forms.ListBox()
        Me.Label17 = New System.Windows.Forms.Label()
        Me.GroupBox6 = New System.Windows.Forms.GroupBox()
        Me.Label23 = New System.Windows.Forms.Label()
        Me.cbRifas = New System.Windows.Forms.ComboBox()
        Me.cmdEliReplicacion = New System.Windows.Forms.Button()
        Me.cmdAgrReplicacion = New System.Windows.Forms.Button()
        Me.lbReplicacion = New System.Windows.Forms.ListBox()
        Me.lblSuscripcion = New System.Windows.Forms.Label()
        Me.autoRefresh = New System.Windows.Forms.CheckBox()
        Me.lblAutoRefresh = New System.Windows.Forms.Label()
        CType(Me.pbUpper, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.cmdPanel.SuspendLayout()
        CType(Me.imgLinea, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.GroupBox1.SuspendLayout()
        CType(Me.ErrorProvider, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.GroupBox2.SuspendLayout()
        Me.GroupBox3.SuspendLayout()
        Me.gbBloqueos.SuspendLayout()
        Me.GroupBox6.SuspendLayout()
        Me.SuspendLayout()
        '
        'cmdPanel
        '
        Me.cmdPanel.Controls.Add(Me.cmdCrear)
        Me.cmdPanel.Location = New System.Drawing.Point(0, 559)
        Me.cmdPanel.Size = New System.Drawing.Size(919, 37)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCrear, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCerrar, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.imgLinea, 0)
        '
        'cmdCerrar
        '
        Me.cmdCerrar.DialogResult = System.Windows.Forms.DialogResult.Cancel
        Me.cmdCerrar.Location = New System.Drawing.Point(814, 9)
        '
        'imgLinea
        '
        Me.imgLinea.Size = New System.Drawing.Size(909, 8)
        '
        'GroupBox1
        '
        Me.GroupBox1.Controls.Add(Me.Label16)
        Me.GroupBox1.Controls.Add(Me.estado)
        Me.GroupBox1.Controls.Add(Me.Label15)
        Me.GroupBox1.Controls.Add(Me.mensaje_adicional)
        Me.GroupBox1.Controls.Add(Me.limite_participante)
        Me.GroupBox1.Controls.Add(Me.Label14)
        Me.GroupBox1.Controls.Add(Me.Label13)
        Me.GroupBox1.Controls.Add(Me.mensaje_participantes_b)
        Me.GroupBox1.Controls.Add(Me.cmdEli)
        Me.GroupBox1.Controls.Add(Me.cmdAgr)
        Me.GroupBox1.Controls.Add(Me.lbclaves)
        Me.GroupBox1.Controls.Add(Me.Label1)
        Me.GroupBox1.Controls.Add(Me.Label6)
        Me.GroupBox1.Controls.Add(Me.cbNumeros)
        Me.GroupBox1.Controls.Add(Me.Label5)
        Me.GroupBox1.Controls.Add(Me.mensaje_participantes_a)
        Me.GroupBox1.Controls.Add(Me.Label4)
        Me.GroupBox1.Controls.Add(Me.mensaje_ganador)
        Me.GroupBox1.Controls.Add(Me.Label2)
        Me.GroupBox1.Controls.Add(Me.ganadores)
        Me.GroupBox1.Controls.Add(Me.Label3)
        Me.GroupBox1.Controls.Add(Me.nombre)
        Me.GroupBox1.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.GroupBox1.Location = New System.Drawing.Point(9, 64)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(449, 365)
        Me.GroupBox1.TabIndex = 10
        Me.GroupBox1.TabStop = False
        '
        'Label16
        '
        Me.Label16.Location = New System.Drawing.Point(8, 329)
        Me.Label16.Name = "Label16"
        Me.Label16.Size = New System.Drawing.Size(168, 21)
        Me.Label16.TabIndex = 41
        Me.Label16.Text = "Activa:"
        Me.Label16.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'estado
        '
        Me.estado.AutoSize = True
        Me.estado.Location = New System.Drawing.Point(202, 337)
        Me.estado.Name = "estado"
        Me.estado.Size = New System.Drawing.Size(14, 13)
        Me.estado.TabIndex = 40
        Me.estado.UseVisualStyleBackColor = True
        '
        'Label15
        '
        Me.Label15.Location = New System.Drawing.Point(8, 301)
        Me.Label15.Name = "Label15"
        Me.Label15.Size = New System.Drawing.Size(168, 21)
        Me.Label15.TabIndex = 39
        Me.Label15.Text = "Mensaje Adicional"
        Me.Label15.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'mensaje_adicional
        '
        Me.mensaje_adicional.BackColor = System.Drawing.Color.White
        Me.mensaje_adicional.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.mensaje_adicional.Location = New System.Drawing.Point(202, 301)
        Me.mensaje_adicional.MaxLength = 254
        Me.mensaje_adicional.Name = "mensaje_adicional"
        Me.mensaje_adicional.Size = New System.Drawing.Size(208, 21)
        Me.mensaje_adicional.TabIndex = 38
        '
        'limite_participante
        '
        Me.limite_participante.BackColor = System.Drawing.Color.White
        Me.limite_participante.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.limite_participante.Location = New System.Drawing.Point(202, 249)
        Me.limite_participante.MaxLength = 254
        Me.limite_participante.Name = "limite_participante"
        Me.limite_participante.Size = New System.Drawing.Size(208, 21)
        Me.limite_participante.TabIndex = 37
        '
        'Label14
        '
        Me.Label14.Location = New System.Drawing.Point(8, 250)
        Me.Label14.Name = "Label14"
        Me.Label14.Size = New System.Drawing.Size(168, 21)
        Me.Label14.TabIndex = 36
        Me.Label14.Text = "Limite de Grupo #1:"
        Me.Label14.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'Label13
        '
        Me.Label13.Location = New System.Drawing.Point(8, 274)
        Me.Label13.Name = "Label13"
        Me.Label13.Size = New System.Drawing.Size(168, 21)
        Me.Label13.TabIndex = 34
        Me.Label13.Text = "Mensaje Participantes #2:"
        Me.Label13.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'mensaje_participantes_b
        '
        Me.mensaje_participantes_b.BackColor = System.Drawing.Color.White
        Me.mensaje_participantes_b.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.mensaje_participantes_b.Location = New System.Drawing.Point(202, 274)
        Me.mensaje_participantes_b.MaxLength = 254
        Me.mensaje_participantes_b.Name = "mensaje_participantes_b"
        Me.mensaje_participantes_b.Size = New System.Drawing.Size(208, 21)
        Me.mensaje_participantes_b.TabIndex = 33
        '
        'cmdEli
        '
        Me.cmdEli.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdEli.Location = New System.Drawing.Point(418, 144)
        Me.cmdEli.Name = "cmdEli"
        Me.cmdEli.Size = New System.Drawing.Size(25, 24)
        Me.cmdEli.TabIndex = 28
        Me.cmdEli.Text = "-"
        '
        'cmdAgr
        '
        Me.cmdAgr.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdAgr.Location = New System.Drawing.Point(418, 112)
        Me.cmdAgr.Name = "cmdAgr"
        Me.cmdAgr.Size = New System.Drawing.Size(25, 24)
        Me.cmdAgr.TabIndex = 27
        Me.cmdAgr.Text = "+"
        '
        'lbclaves
        '
        Me.lbclaves.BackColor = System.Drawing.Color.White
        Me.lbclaves.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.lbclaves.Location = New System.Drawing.Point(202, 64)
        Me.lbclaves.Name = "lbclaves"
        Me.lbclaves.Size = New System.Drawing.Size(208, 106)
        Me.lbclaves.TabIndex = 26
        '
        'Label1
        '
        Me.Label1.Location = New System.Drawing.Point(8, 64)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(168, 21)
        Me.Label1.TabIndex = 25
        Me.Label1.Text = "Clave(s):"
        Me.Label1.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'Label6
        '
        Me.Label6.Location = New System.Drawing.Point(8, 16)
        Me.Label6.Name = "Label6"
        Me.Label6.Size = New System.Drawing.Size(168, 21)
        Me.Label6.TabIndex = 17
        Me.Label6.Text = "Número:"
        Me.Label6.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'cbNumeros
        '
        Me.cbNumeros.BackColor = System.Drawing.Color.White
        Me.cbNumeros.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cbNumeros.Location = New System.Drawing.Point(202, 16)
        Me.cbNumeros.Name = "cbNumeros"
        Me.cbNumeros.Size = New System.Drawing.Size(208, 21)
        Me.cbNumeros.TabIndex = 16
        '
        'Label5
        '
        Me.Label5.Location = New System.Drawing.Point(8, 224)
        Me.Label5.Name = "Label5"
        Me.Label5.Size = New System.Drawing.Size(168, 21)
        Me.Label5.TabIndex = 15
        Me.Label5.Text = "Mensaje Participantes #1:"
        Me.Label5.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'mensaje_participantes_a
        '
        Me.mensaje_participantes_a.BackColor = System.Drawing.Color.White
        Me.mensaje_participantes_a.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.mensaje_participantes_a.Location = New System.Drawing.Point(202, 224)
        Me.mensaje_participantes_a.MaxLength = 254
        Me.mensaje_participantes_a.Name = "mensaje_participantes_a"
        Me.mensaje_participantes_a.Size = New System.Drawing.Size(208, 21)
        Me.mensaje_participantes_a.TabIndex = 14
        '
        'Label4
        '
        Me.Label4.Location = New System.Drawing.Point(8, 200)
        Me.Label4.Name = "Label4"
        Me.Label4.Size = New System.Drawing.Size(168, 21)
        Me.Label4.TabIndex = 13
        Me.Label4.Text = "Mensaje a Ganadores:"
        Me.Label4.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'mensaje_ganador
        '
        Me.mensaje_ganador.BackColor = System.Drawing.Color.White
        Me.mensaje_ganador.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.mensaje_ganador.Location = New System.Drawing.Point(202, 200)
        Me.mensaje_ganador.MaxLength = 254
        Me.mensaje_ganador.Name = "mensaje_ganador"
        Me.mensaje_ganador.Size = New System.Drawing.Size(208, 21)
        Me.mensaje_ganador.TabIndex = 12
        '
        'Label2
        '
        Me.Label2.Location = New System.Drawing.Point(8, 176)
        Me.Label2.Name = "Label2"
        Me.Label2.Size = New System.Drawing.Size(168, 21)
        Me.Label2.TabIndex = 11
        Me.Label2.Text = "Cantidad de Ganadores:"
        Me.Label2.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'ganadores
        '
        Me.ganadores.BackColor = System.Drawing.Color.White
        Me.ganadores.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.ganadores.Location = New System.Drawing.Point(202, 176)
        Me.ganadores.Name = "ganadores"
        Me.ganadores.Size = New System.Drawing.Size(208, 21)
        Me.ganadores.TabIndex = 10
        '
        'Label3
        '
        Me.Label3.Location = New System.Drawing.Point(8, 40)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(168, 21)
        Me.Label3.TabIndex = 7
        Me.Label3.Text = "Nombre de la Rifa:"
        Me.Label3.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'nombre
        '
        Me.nombre.BackColor = System.Drawing.Color.White
        Me.nombre.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.nombre.Location = New System.Drawing.Point(202, 40)
        Me.nombre.Name = "nombre"
        Me.nombre.Size = New System.Drawing.Size(208, 21)
        Me.nombre.TabIndex = 6
        '
        'Label12
        '
        Me.Label12.Location = New System.Drawing.Point(8, 40)
        Me.Label12.Name = "Label12"
        Me.Label12.Size = New System.Drawing.Size(168, 21)
        Me.Label12.TabIndex = 32
        Me.Label12.Text = "Habilitar Self-Service:"
        Me.Label12.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'selfservice
        '
        Me.selfservice.AutoSize = True
        Me.selfservice.Location = New System.Drawing.Point(202, 43)
        Me.selfservice.Name = "selfservice"
        Me.selfservice.Size = New System.Drawing.Size(14, 13)
        Me.selfservice.TabIndex = 31
        Me.selfservice.UseVisualStyleBackColor = True
        '
        'Label11
        '
        Me.Label11.Location = New System.Drawing.Point(8, 17)
        Me.Label11.Name = "Label11"
        Me.Label11.Size = New System.Drawing.Size(168, 21)
        Me.Label11.TabIndex = 30
        Me.Label11.Text = "Habilitar Chat:"
        Me.Label11.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'chat
        '
        Me.chat.AutoSize = True
        Me.chat.Location = New System.Drawing.Point(202, 20)
        Me.chat.Name = "chat"
        Me.chat.Size = New System.Drawing.Size(14, 13)
        Me.chat.TabIndex = 29
        Me.chat.UseVisualStyleBackColor = True
        '
        'ErrorProvider
        '
        Me.ErrorProvider.ContainerControl = Me
        '
        'cmdCrear
        '
        Me.cmdCrear.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdCrear.Location = New System.Drawing.Point(706, 9)
        Me.cmdCrear.Name = "cmdCrear"
        Me.cmdCrear.Size = New System.Drawing.Size(100, 24)
        Me.cmdCrear.TabIndex = 12
        Me.cmdCrear.Text = "Crear"
        '
        'GroupBox2
        '
        Me.GroupBox2.Controls.Add(Me.Label10)
        Me.GroupBox2.Controls.Add(Me.claveAdmin)
        Me.GroupBox2.Controls.Add(Me.Button1)
        Me.GroupBox2.Controls.Add(Me.Label9)
        Me.GroupBox2.Controls.Add(Me.logo)
        Me.GroupBox2.Controls.Add(Me.usuario)
        Me.GroupBox2.Controls.Add(Me.Label8)
        Me.GroupBox2.Controls.Add(Me.clave)
        Me.GroupBox2.Controls.Add(Me.Label7)
        Me.GroupBox2.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.GroupBox2.Location = New System.Drawing.Point(464, 206)
        Me.GroupBox2.Name = "GroupBox2"
        Me.GroupBox2.Size = New System.Drawing.Size(447, 125)
        Me.GroupBox2.TabIndex = 12
        Me.GroupBox2.TabStop = False
        Me.GroupBox2.Text = "Acceso Web"
        '
        'Label10
        '
        Me.Label10.Location = New System.Drawing.Point(8, 63)
        Me.Label10.Name = "Label10"
        Me.Label10.Size = New System.Drawing.Size(168, 21)
        Me.Label10.TabIndex = 33
        Me.Label10.Text = "Clave Administrativa:"
        Me.Label10.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'claveAdmin
        '
        Me.claveAdmin.BackColor = System.Drawing.Color.White
        Me.claveAdmin.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.claveAdmin.Location = New System.Drawing.Point(181, 64)
        Me.claveAdmin.Name = "claveAdmin"
        Me.claveAdmin.Size = New System.Drawing.Size(229, 21)
        Me.claveAdmin.TabIndex = 32
        '
        'Button1
        '
        Me.Button1.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.Button1.Location = New System.Drawing.Point(416, 87)
        Me.Button1.Name = "Button1"
        Me.Button1.Size = New System.Drawing.Size(25, 24)
        Me.Button1.TabIndex = 31
        Me.Button1.Text = "..."
        '
        'Label9
        '
        Me.Label9.Location = New System.Drawing.Point(8, 89)
        Me.Label9.Name = "Label9"
        Me.Label9.Size = New System.Drawing.Size(168, 21)
        Me.Label9.TabIndex = 30
        Me.Label9.Text = "Logotipo:"
        Me.Label9.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'logo
        '
        Me.logo.BackColor = System.Drawing.Color.White
        Me.logo.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.logo.Location = New System.Drawing.Point(181, 89)
        Me.logo.Name = "logo"
        Me.logo.ReadOnly = True
        Me.logo.Size = New System.Drawing.Size(229, 21)
        Me.logo.TabIndex = 29
        '
        'usuario
        '
        Me.usuario.BackColor = System.Drawing.Color.White
        Me.usuario.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.usuario.Location = New System.Drawing.Point(181, 16)
        Me.usuario.Name = "usuario"
        Me.usuario.Size = New System.Drawing.Size(229, 21)
        Me.usuario.TabIndex = 22
        '
        'Label8
        '
        Me.Label8.Location = New System.Drawing.Point(8, 40)
        Me.Label8.Name = "Label8"
        Me.Label8.Size = New System.Drawing.Size(168, 21)
        Me.Label8.TabIndex = 24
        Me.Label8.Text = "Clave:"
        Me.Label8.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'clave
        '
        Me.clave.BackColor = System.Drawing.Color.White
        Me.clave.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.clave.Location = New System.Drawing.Point(181, 40)
        Me.clave.Name = "clave"
        Me.clave.Size = New System.Drawing.Size(229, 21)
        Me.clave.TabIndex = 23
        '
        'Label7
        '
        Me.Label7.Location = New System.Drawing.Point(8, 16)
        Me.Label7.Name = "Label7"
        Me.Label7.Size = New System.Drawing.Size(168, 21)
        Me.Label7.TabIndex = 25
        Me.Label7.Text = "Usuario:"
        Me.Label7.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'GroupBox3
        '
        Me.GroupBox3.Controls.Add(Me.autoRefresh)
        Me.GroupBox3.Controls.Add(Me.lblAutoRefresh)
        Me.GroupBox3.Controls.Add(Me.soloLectura)
        Me.GroupBox3.Controls.Add(Me.Label19)
        Me.GroupBox3.Controls.Add(Me.Label18)
        Me.GroupBox3.Controls.Add(Me.sonido)
        Me.GroupBox3.Controls.Add(Me.Label11)
        Me.GroupBox3.Controls.Add(Me.chat)
        Me.GroupBox3.Controls.Add(Me.selfservice)
        Me.GroupBox3.Controls.Add(Me.Label12)
        Me.GroupBox3.Location = New System.Drawing.Point(9, 435)
        Me.GroupBox3.Name = "GroupBox3"
        Me.GroupBox3.Size = New System.Drawing.Size(447, 106)
        Me.GroupBox3.TabIndex = 13
        Me.GroupBox3.TabStop = False
        Me.GroupBox3.Text = "Servicios"
        '
        'soloLectura
        '
        Me.soloLectura.AutoSize = True
        Me.soloLectura.Location = New System.Drawing.Point(427, 43)
        Me.soloLectura.Name = "soloLectura"
        Me.soloLectura.Size = New System.Drawing.Size(14, 13)
        Me.soloLectura.TabIndex = 35
        Me.soloLectura.UseVisualStyleBackColor = True
        '
        'Label19
        '
        Me.Label19.Location = New System.Drawing.Point(233, 40)
        Me.Label19.Name = "Label19"
        Me.Label19.Size = New System.Drawing.Size(168, 21)
        Me.Label19.TabIndex = 36
        Me.Label19.Text = "Solo Lectura:"
        Me.Label19.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'Label18
        '
        Me.Label18.Location = New System.Drawing.Point(233, 15)
        Me.Label18.Name = "Label18"
        Me.Label18.Size = New System.Drawing.Size(188, 21)
        Me.Label18.TabIndex = 34
        Me.Label18.Text = "Habilitar Sonido en Nuevos SMS:"
        Me.Label18.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'sonido
        '
        Me.sonido.AutoSize = True
        Me.sonido.Location = New System.Drawing.Point(427, 20)
        Me.sonido.Name = "sonido"
        Me.sonido.Size = New System.Drawing.Size(14, 13)
        Me.sonido.TabIndex = 33
        Me.sonido.UseVisualStyleBackColor = True
        '
        'gbBloqueos
        '
        Me.gbBloqueos.Controls.Add(Me.cmdEliB)
        Me.gbBloqueos.Controls.Add(Me.cmdAgrB)
        Me.gbBloqueos.Controls.Add(Me.lbBloqueos)
        Me.gbBloqueos.Controls.Add(Me.Label17)
        Me.gbBloqueos.Location = New System.Drawing.Point(464, 64)
        Me.gbBloqueos.Name = "gbBloqueos"
        Me.gbBloqueos.Size = New System.Drawing.Size(447, 136)
        Me.gbBloqueos.TabIndex = 14
        Me.gbBloqueos.TabStop = False
        Me.gbBloqueos.Text = "Bloqueos"
        '
        'cmdEliB
        '
        Me.cmdEliB.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdEliB.Location = New System.Drawing.Point(419, 100)
        Me.cmdEliB.Name = "cmdEliB"
        Me.cmdEliB.Size = New System.Drawing.Size(25, 24)
        Me.cmdEliB.TabIndex = 32
        Me.cmdEliB.Text = "-"
        '
        'cmdAgrB
        '
        Me.cmdAgrB.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdAgrB.Location = New System.Drawing.Point(419, 68)
        Me.cmdAgrB.Name = "cmdAgrB"
        Me.cmdAgrB.Size = New System.Drawing.Size(25, 24)
        Me.cmdAgrB.TabIndex = 31
        Me.cmdAgrB.Text = "+"
        '
        'lbBloqueos
        '
        Me.lbBloqueos.BackColor = System.Drawing.Color.White
        Me.lbBloqueos.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.lbBloqueos.Location = New System.Drawing.Point(181, 20)
        Me.lbBloqueos.Name = "lbBloqueos"
        Me.lbBloqueos.Size = New System.Drawing.Size(232, 106)
        Me.lbBloqueos.TabIndex = 30
        '
        'Label17
        '
        Me.Label17.Location = New System.Drawing.Point(11, 20)
        Me.Label17.Name = "Label17"
        Me.Label17.Size = New System.Drawing.Size(168, 21)
        Me.Label17.TabIndex = 29
        Me.Label17.Text = "Clave(s):"
        Me.Label17.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'GroupBox6
        '
        Me.GroupBox6.Controls.Add(Me.Label23)
        Me.GroupBox6.Controls.Add(Me.cbRifas)
        Me.GroupBox6.Controls.Add(Me.cmdEliReplicacion)
        Me.GroupBox6.Controls.Add(Me.cmdAgrReplicacion)
        Me.GroupBox6.Controls.Add(Me.lbReplicacion)
        Me.GroupBox6.Controls.Add(Me.lblSuscripcion)
        Me.GroupBox6.Location = New System.Drawing.Point(464, 339)
        Me.GroupBox6.Name = "GroupBox6"
        Me.GroupBox6.Size = New System.Drawing.Size(447, 202)
        Me.GroupBox6.TabIndex = 18
        Me.GroupBox6.TabStop = False
        Me.GroupBox6.Text = "Replicación"
        '
        'Label23
        '
        Me.Label23.Location = New System.Drawing.Point(8, 42)
        Me.Label23.Name = "Label23"
        Me.Label23.Size = New System.Drawing.Size(158, 33)
        Me.Label23.TabIndex = 35
        Me.Label23.Text = "Rifa(s) Replicadas:"
        Me.Label23.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'cbRifas
        '
        Me.cbRifas.BackColor = System.Drawing.Color.White
        Me.cbRifas.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cbRifas.Location = New System.Drawing.Point(181, 11)
        Me.cbRifas.Name = "cbRifas"
        Me.cbRifas.Size = New System.Drawing.Size(229, 21)
        Me.cbRifas.TabIndex = 34
        '
        'cmdEliReplicacion
        '
        Me.cmdEliReplicacion.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdEliReplicacion.Location = New System.Drawing.Point(416, 74)
        Me.cmdEliReplicacion.Name = "cmdEliReplicacion"
        Me.cmdEliReplicacion.Size = New System.Drawing.Size(25, 24)
        Me.cmdEliReplicacion.TabIndex = 33
        Me.cmdEliReplicacion.Text = "-"
        '
        'cmdAgrReplicacion
        '
        Me.cmdAgrReplicacion.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdAgrReplicacion.Location = New System.Drawing.Point(416, 11)
        Me.cmdAgrReplicacion.Name = "cmdAgrReplicacion"
        Me.cmdAgrReplicacion.Size = New System.Drawing.Size(25, 24)
        Me.cmdAgrReplicacion.TabIndex = 32
        Me.cmdAgrReplicacion.Text = "+"
        '
        'lbReplicacion
        '
        Me.lbReplicacion.FormattingEnabled = True
        Me.lbReplicacion.Location = New System.Drawing.Point(181, 42)
        Me.lbReplicacion.Name = "lbReplicacion"
        Me.lbReplicacion.Size = New System.Drawing.Size(229, 56)
        Me.lbReplicacion.TabIndex = 31
        '
        'lblSuscripcion
        '
        Me.lblSuscripcion.Location = New System.Drawing.Point(6, 13)
        Me.lblSuscripcion.Name = "lblSuscripcion"
        Me.lblSuscripcion.Size = New System.Drawing.Size(160, 21)
        Me.lblSuscripcion.TabIndex = 28
        Me.lblSuscripcion.Text = "Rifa:"
        Me.lblSuscripcion.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'autoRefresh
        '
        Me.autoRefresh.AutoSize = True
        Me.autoRefresh.Location = New System.Drawing.Point(202, 64)
        Me.autoRefresh.Name = "autoRefresh"
        Me.autoRefresh.Size = New System.Drawing.Size(14, 13)
        Me.autoRefresh.TabIndex = 37
        Me.autoRefresh.UseVisualStyleBackColor = True
        '
        'lblAutoRefresh
        '
        Me.lblAutoRefresh.Location = New System.Drawing.Point(8, 61)
        Me.lblAutoRefresh.Name = "lblAutoRefresh"
        Me.lblAutoRefresh.Size = New System.Drawing.Size(168, 21)
        Me.lblAutoRefresh.TabIndex = 38
        Me.lblAutoRefresh.Text = "Habilitar Auto-Refrescar:"
        Me.lblAutoRefresh.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'agrRifa
        '
        Me.AcceptButton = Me.cmdCrear
        Me.AutoScaleBaseSize = New System.Drawing.Size(6, 14)
        Me.CancelButton = Me.cmdCerrar
        Me.ClientSize = New System.Drawing.Size(919, 596)
        Me.Controls.Add(Me.GroupBox6)
        Me.Controls.Add(Me.GroupBox1)
        Me.Controls.Add(Me.gbBloqueos)
        Me.Controls.Add(Me.GroupBox3)
        Me.Controls.Add(Me.GroupBox2)
        Me.Name = "agrRifa"
        Me.pTitle = "Crear Rifa"
        Me.Text = "Crear Rifa"
        Me.Controls.SetChildIndex(Me.pbUpper, 0)
        Me.Controls.SetChildIndex(Me.GroupBox2, 0)
        Me.Controls.SetChildIndex(Me.GroupBox3, 0)
        Me.Controls.SetChildIndex(Me.gbBloqueos, 0)
        Me.Controls.SetChildIndex(Me.GroupBox1, 0)
        Me.Controls.SetChildIndex(Me.cmdPanel, 0)
        Me.Controls.SetChildIndex(Me.GroupBox6, 0)
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
        Me.gbBloqueos.ResumeLayout(False)
        Me.GroupBox6.ResumeLayout(False)
        Me.ResumeLayout(False)

    End Sub
#End Region
    Private vID As Integer = -1
    Private eliminados() As Integer
    Private eliminados2() As Integer
    Private eliminadosReplicacion() As Integer

    Private Sub cmdCrear_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdCrear.Click
        If validar() Then If agregar() Then Dispose()
    End Sub
    Function validar() As Boolean
        ErrorProvider.SetError(cbNumeros, "")
        ErrorProvider.SetError(nombre, "")
        ErrorProvider.SetError(mensaje_ganador, "")
        ErrorProvider.SetError(mensaje_participantes_a, "")
        ErrorProvider.SetError(mensaje_participantes_b, "")
        ErrorProvider.SetError(limite_participante, "")
        ErrorProvider.SetError(ganadores, "")
        ErrorProvider.SetError(usuario, "")
        ErrorProvider.SetError(clave, "")
        ErrorProvider.SetError(claveAdmin, "")
        ErrorProvider.SetError(logo, "")

        validar = False
        If cbNumeros.SelectedIndex = -1 Then
            ErrorProvider.SetError(cbNumeros, "Debe escoger un numero")
        ElseIf nombre.Text = "" Then
            ErrorProvider.SetError(nombre, "Debe ingresar un nombre para identificar la rifa")
        ElseIf lbclaves.Items.Count = 0 Then
            ErrorProvider.SetError(lbclaves, "Debe haber al menos una clave")
        ElseIf ganadores.Text = "" Then
            ErrorProvider.SetError(ganadores, "Debe ingresar una cantidad de ganadores")
        ElseIf mensaje_ganador.Text = "" Then
            ErrorProvider.SetError(mensaje_ganador, "Debe ingresar el mensaje para el ganador")
        ElseIf mensaje_participantes_a.Text = "" Then
            ErrorProvider.SetError(mensaje_participantes_a, "Debe ingresar el mensaje para los demas participantes")
        ElseIf mensaje_participantes_b.Text = "" Then
            ErrorProvider.SetError(mensaje_participantes_b, "Debe ingresar el mensaje para los demas participantes")
        ElseIf limite_participante.Text = "" Or Not IsNumeric(limite_participante.Text) Then
            ErrorProvider.SetError(limite_participante, "Debe ingresar la cantidad de participantes que recibiran el primer mensaje")
        ElseIf usuario.Text = "" Then
            ErrorProvider.SetError(usuario, "Debe ingresar un usuario")
        ElseIf clave.Text = "" Then
            ErrorProvider.SetError(clave, "Debe ingresar una clave")
        ElseIf claveAdmin.Text = "" Then
            ErrorProvider.SetError(claveAdmin, "Debe ingresar una clave administrativa")
        ElseIf Not validarPass(clave.Text) Then
            ErrorProvider.SetError(clave, "La clave debe incluir una mayúscula, un numero y tener logitud de al menos 8 caracteres")
        ElseIf Not validarPass(claveAdmin.Text) Then
            ErrorProvider.SetError(claveAdmin, "La clave debe incluir una mayúscula, un numero y tener logitud de al menos 8 caracteres")
        ElseIf Not existeArchivo(logo.Text) Then
            If logo.Text.Length = 0 Then
                validar = True
            Else
                ErrorProvider.SetError(logo, "Debe escoger un archivo válido")
            End If
        Else
            validar = True
        End If
    End Function

    Function agregar() As Boolean
        agregar = False
        If CNX.State <> ConnectionState.Open Then CNX.Open()
        Dim numero As String = valor(cbNumeros)

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
            strCMD = String.Format("INSERT INTO rifas (numero, nombre, cantidad_ganadores, mensaje_ganador, mensaje_participante_a, mensaje_participante_b, mensaje_adicional, limite_participante, chat, selfservice, soloLectura, autoRefresh, sonido, estado, usuario, clave, claveAdmin, logo_tipo, logo_archivo) VALUES ('{0}','{1}',{2},'{3}','{4}','{5}','{6}',{7}, {8}, {9}, {10}, {11}, {17}, {12},'{13}','{14}','{15}','{16}', @logo_archivo)", numero, nombre.Text, ganadores.Text, mensaje_ganador.Text, mensaje_participantes_a.Text, mensaje_participantes_b.Text, mensaje_adicional.Text, CInt(limite_participante.Text), IIf(chat.Checked, 1, 0), IIf(selfservice.Checked, 1, 0), IIf(soloLectura.Checked, 1, 0), IIf(sonido.Checked, 1, 0), IIf(estado.Checked, 1, 0), usuario.Text, clave.Text, claveAdmin.Text, tipo, IIf(autoRefresh.Checked, 1, 0))
        Else
            strCMD = String.Format("UPDATE rifas SET numero='{0}', nombre='{1}', cantidad_ganadores={2}, mensaje_ganador='{3}',mensaje_participante_a='{4}', mensaje_participante_b='{5}', mensaje_adicional='{6}', limite_participante={7}, chat={8}, selfservice={9}, soloLectura={10}, sonido={11}, estado={12}, usuario='{13}', clave='{14}', claveAdmin='{15}', autoRefresh={16}", numero, nombre.Text, ganadores.Text, mensaje_ganador.Text, mensaje_participantes_a.Text, mensaje_participantes_b.Text, mensaje_adicional.Text, CInt(limite_participante.Text), IIf(chat.Checked, 1, 0), IIf(selfservice.Checked, 1, 0), IIf(soloLectura.Checked, 1, 0), IIf(sonido.Checked, 1, 0), IIf(estado.Checked, 1, 0), usuario.Text, clave.Text, claveAdmin.Text, IIf(autoRefresh.Checked, 1, 0))
            If useLogo Then strCMD &= String.Format(", logo_tipo='{0}', logo_archivo=@logo_archivo", tipo)
            strCMD &= " WHERE id = " & vID
        End If
        Const strCMD2 As String = "INSERT INTO claves (clave,idRifa) VALUES"
        Const strCMD3 As String = "DELETE FROM claves WHERE id="
        Const strCMD4 As String = "INSERT INTO rifas_bloqueados (palabra, idRifa) VALUES"
        Const strCMD5 As String = "DELETE FROM rifas_bloqueados WHERE id="
        Const strCMD6 As String = "REPLACE INTO rifas_replicaciones (padre, hijo) VALUES"
        Const strCMD7 As String = "DELETE FROM rifas_replicaciones WHERE padre="

        Dim cmd As New MySqlCommand(strCMD, connectionOne)
        If connectionOne.State <> ConnectionState.Open Then connectionOne.Open()
        If useLogo Then cmd.Parameters.AddWithValue("@logo_archivo", archivo)
        Try
            cmd.ExecuteNonQuery()
            If vID = -1 Then
                Dim dr As Integer = New MySqlCommand(String.Format("SELECT id FROM rifas WHERE nombre='{0}'", nombre.Text), CNX).ExecuteScalar
                vID = dr
            End If
            For i As Integer = 0 To lbclaves.Items.Count - 1
                If valor(lbclaves, i, False) = -1 Then
                    Dim clave As String = getNombre(lbclaves, i, False)
                    cmd.CommandText = String.Format("{0}('{1}',{2})", strCMD2, clave, vID)
                    cmd.ExecuteNonQuery()
                End If
            Next
            With lbBloqueos
                For i As Integer = 0 To .Items.Count - 1
                    If valor(lbBloqueos, i, False) = -1 Then
                        Dim clave As String = getNombre(lbBloqueos, i, False)
                        cmd.CommandText = String.Format("{0}('{1}',{2})", strCMD4, clave, vID)
                        cmd.ExecuteNonQuery()
                    End If
                Next
            End With
            For i As Integer = 0 To lbReplicacion.Items.Count - 1
                Dim clave As Integer = valor(lbReplicacion, i, False)
                cmd.CommandText = String.Format("{0}('{1}',{2})", strCMD6, vID, clave)
                cmd.ExecuteNonQuery()
            Next

            If Not eliminados Is Nothing Then
                For i As Integer = 0 To eliminados.Length - 1
                    cmd.CommandText = strCMD3 & eliminados(i)
                    cmd.ExecuteNonQuery()
                Next
            End If
            If Not eliminados2 Is Nothing Then
                For i As Integer = 0 To eliminados2.Length - 1
                    cmd.CommandText = strCMD5 & eliminados2(i)
                    cmd.ExecuteNonQuery()
                Next
            End If

            If Not eliminadosReplicacion Is Nothing Then
                For i As Integer = 0 To eliminadosReplicacion.Length - 1
                    cmd.CommandText = String.Format("{0}{1} AND hijo={2}", strCMD7, vID, eliminadosReplicacion(i))
                    cmd.ExecuteNonQuery()
                Next
            End If

            agregar = True
        Catch ex As MySqlException
            MsgBox("Error al ejecutar comando: " & ex.Message)
        End Try
    End Function
    Private Sub agrRifa_Load(ByVal sender As Object, ByVal e As EventArgs) Handles MyBase.Load
        'fncListbox.popular(cbNumeros, "numeros", "numero", "numero", CNX)
        campoNumerico(ganadores)
    End Sub
    Private Sub cmdAgr_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdAgr.Click
        Dim nvaClave As String = InputBox("Ingrese una clave: ")
        If nvaClave.Length = 0 Then
            MsgBox("No se permiten claves nulas")
        Else
            lbclaves.Items.Add(New listitem(nvaClave, -1))
        End If
    End Sub
    Private Sub cmdEli_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdEli.Click
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

    Private Sub cmdAgrB_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdAgrB.Click
        Dim nvaClave As String = InputBox("Ingrese una clave: ")
        If nvaClave.Length = 0 Then
            MsgBox("No se permiten claves nulas")
        Else
            lbBloqueos.Items.Add(New listitem(nvaClave, -1))
        End If
    End Sub

    Private Sub cmdEliB_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdEliB.Click
        With lbBloqueos
            If .SelectedIndex = -1 Then
                MsgBox("Debe seleccionar una clave")
            Else
                If valor(lbBloqueos, .SelectedIndex, False) <> -1 Then
                    If eliminados2 Is Nothing Then
                        ReDim eliminados2(0)
                    Else
                        ReDim Preserve eliminados2(eliminados2.Length)
                    End If
                    eliminados2(eliminados2.Length - 1) = valor(lbBloqueos)
                End If
                .Items.RemoveAt(.SelectedIndex)
            End If
        End With
    End Sub

    Private Sub cmdEliReplicacion_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdEliReplicacion.Click
        If lbReplicacion.SelectedIndex = -1 Then
            MsgBox("Debe seleccionar una clave")
        Else
            If valor(lbReplicacion, lbReplicacion.SelectedIndex, False) <> -1 Then
                If eliminadosReplicacion Is Nothing Then
                    ReDim eliminadosReplicacion(0)
                Else
                    ReDim Preserve eliminadosReplicacion(eliminados.Length)
                End If
                eliminadosReplicacion(eliminadosReplicacion.Length - 1) = valor(lbReplicacion)
            End If
            lbReplicacion.Items.RemoveAt(lbReplicacion.SelectedIndex)
        End If
    End Sub
    Private Sub cmdAgrReplicacion_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdAgrReplicacion.Click
        With cbRifas
            If .SelectedIndex >= 0 Then
                Dim myitem As listitem = CType(.SelectedItem, listitem)
                lbReplicacion.Items.Add(New listitem(myitem.Name, myitem.id))
            End If
        End With
    End Sub
End Class