Imports System.ServiceProcess
Imports System.Management
Imports EncryptionClassLibrary

Public Class principal
    Inherits formControl.frmTemplate

#Region " Windows Form Designer generated code "
    Public Sub New()
        MyBase.New()
        InitializeComponent()
    End Sub
    Protected Overloads Overrides Sub Dispose(ByVal disposing As Boolean)
        'deactivateListener()
        'deactivateSender()
        If disposing Then
            If Not (components Is Nothing) Then
                components.Dispose()
            End If
        End If
        MyBase.Dispose(disposing)
    End Sub

    Private components As System.ComponentModel.IContainer
    Friend WithEvents ErrorProvider As System.Windows.Forms.ErrorProvider
    Friend WithEvents cmdSalvar As System.Windows.Forms.Button
    Friend WithEvents cmdActivar As System.Windows.Forms.Button
    Friend WithEvents Label5 As System.Windows.Forms.Label
    Friend WithEvents lblActive As System.Windows.Forms.Label
    Friend WithEvents TabControl1 As System.Windows.Forms.TabControl
    Friend WithEvents TabPage1 As System.Windows.Forms.TabPage
    Friend WithEvents GroupBox4 As System.Windows.Forms.GroupBox
    Friend WithEvents cbServer As System.Windows.Forms.ComboBox
    Friend WithEvents Label1 As System.Windows.Forms.Label
    Friend WithEvents cmdTest As System.Windows.Forms.Button
    Friend WithEvents pass As System.Windows.Forms.TextBox
    Friend WithEvents user As System.Windows.Forms.TextBox
    Friend WithEvents bd As System.Windows.Forms.TextBox
    Friend WithEvents server As System.Windows.Forms.TextBox
    Friend WithEvents Label4 As System.Windows.Forms.Label
    Friend WithEvents Label3 As System.Windows.Forms.Label
    Friend WithEvents Label2 As System.Windows.Forms.Label
    Friend WithEvents GroupBox2 As System.Windows.Forms.GroupBox
    Friend WithEvents cmdBorrar As System.Windows.Forms.Button
    Friend WithEvents cmdTestInsert As System.Windows.Forms.Button
    Friend WithEvents tbProcess As System.Windows.Forms.TabPage
    Friend WithEvents GroupBox3 As System.Windows.Forms.GroupBox
    Friend WithEvents chkDiccionario As System.Windows.Forms.CheckBox
    Friend WithEvents chkMedia As System.Windows.Forms.CheckBox
    Friend WithEvents chkTelechat As System.Windows.Forms.CheckBox
    Friend WithEvents chkList As System.Windows.Forms.CheckBox
    Friend WithEvents chkSurvey As System.Windows.Forms.CheckBox
    Friend WithEvents chkTrivias As System.Windows.Forms.CheckBox
    Friend WithEvents chkRifas As System.Windows.Forms.CheckBox
    Friend WithEvents chkSuscripcion As System.Windows.Forms.CheckBox
    Friend WithEvents TabPage3 As System.Windows.Forms.TabPage
    Friend WithEvents grpMonitoreo As System.Windows.Forms.GroupBox
    Friend WithEvents Label12 As System.Windows.Forms.Label
    Friend WithEvents NOTIFYSERVER As System.Windows.Forms.TextBox
    Friend WithEvents Label11 As System.Windows.Forms.Label
    Friend WithEvents NOTIFYPASS As System.Windows.Forms.TextBox
    Friend WithEvents Label7 As System.Windows.Forms.Label
    Friend WithEvents NOTIFYFROM As System.Windows.Forms.TextBox
    Friend WithEvents Label10 As System.Windows.Forms.Label
    Friend WithEvents Label9 As System.Windows.Forms.Label
    Friend WithEvents Label8 As System.Windows.Forms.Label
    Friend WithEvents Label6 As System.Windows.Forms.Label
    Friend WithEvents PINGINTERVAL As System.Windows.Forms.TextBox
    Friend WithEvents MAX_TIMEOUT As System.Windows.Forms.TextBox
    Friend WithEvents NOTIFYCC As System.Windows.Forms.TextBox
    Friend WithEvents NOTIFYTO As System.Windows.Forms.TextBox
    Friend WithEvents GroupBox1 As System.Windows.Forms.GroupBox
    Friend WithEvents chkPruebas As System.Windows.Forms.CheckBox
    Friend WithEvents chkActivate As System.Windows.Forms.CheckBox
    Friend WithEvents chkStartup As System.Windows.Forms.CheckBox
    Friend WithEvents chkMonitoreo As System.Windows.Forms.CheckBox
    Friend WithEvents NOTIFYNUMBER As System.Windows.Forms.TextBox
    Friend WithEvents gbMonitoreo As System.Windows.Forms.GroupBox
    Friend WithEvents Label14 As System.Windows.Forms.Label
    Friend WithEvents NOTIFYNUMBEROUT As System.Windows.Forms.TextBox
    Friend WithEvents Label13 As System.Windows.Forms.Label
    Friend WithEvents TabPage2 As System.Windows.Forms.TabPage
    Friend WithEvents GroupBox5 As System.Windows.Forms.GroupBox
    Friend WithEvents Label15 As System.Windows.Forms.Label
    Friend WithEvents txtLicencia As System.Windows.Forms.TextBox
    Friend WithEvents Label16 As System.Windows.Forms.Label
    Friend WithEvents txtActivacion As System.Windows.Forms.TextBox
    Friend WithEvents cmdLicencia As System.Windows.Forms.Button
    Friend WithEvents chkRecharges As System.Windows.Forms.CheckBox
    Friend WithEvents chkTelebingo As System.Windows.Forms.CheckBox
    Friend WithEvents chkCreditCards As System.Windows.Forms.CheckBox
    Friend WithEvents chkAnniversary As System.Windows.Forms.CheckBox
    Friend WithEvents tmr As System.Windows.Forms.Timer
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.components = New System.ComponentModel.Container
        Me.cmdSalvar = New System.Windows.Forms.Button
        Me.ErrorProvider = New System.Windows.Forms.ErrorProvider(Me.components)
        Me.cmdActivar = New System.Windows.Forms.Button
        Me.Label5 = New System.Windows.Forms.Label
        Me.lblActive = New System.Windows.Forms.Label
        Me.tmr = New System.Windows.Forms.Timer(Me.components)
        Me.TabPage3 = New System.Windows.Forms.TabPage
        Me.gbMonitoreo = New System.Windows.Forms.GroupBox
        Me.Label14 = New System.Windows.Forms.Label
        Me.NOTIFYNUMBEROUT = New System.Windows.Forms.TextBox
        Me.Label13 = New System.Windows.Forms.Label
        Me.NOTIFYNUMBER = New System.Windows.Forms.TextBox
        Me.grpMonitoreo = New System.Windows.Forms.GroupBox
        Me.Label12 = New System.Windows.Forms.Label
        Me.NOTIFYSERVER = New System.Windows.Forms.TextBox
        Me.Label11 = New System.Windows.Forms.Label
        Me.NOTIFYPASS = New System.Windows.Forms.TextBox
        Me.Label7 = New System.Windows.Forms.Label
        Me.NOTIFYFROM = New System.Windows.Forms.TextBox
        Me.Label10 = New System.Windows.Forms.Label
        Me.Label9 = New System.Windows.Forms.Label
        Me.Label8 = New System.Windows.Forms.Label
        Me.Label6 = New System.Windows.Forms.Label
        Me.PINGINTERVAL = New System.Windows.Forms.TextBox
        Me.MAX_TIMEOUT = New System.Windows.Forms.TextBox
        Me.NOTIFYCC = New System.Windows.Forms.TextBox
        Me.NOTIFYTO = New System.Windows.Forms.TextBox
        Me.GroupBox1 = New System.Windows.Forms.GroupBox
        Me.chkPruebas = New System.Windows.Forms.CheckBox
        Me.chkActivate = New System.Windows.Forms.CheckBox
        Me.chkStartup = New System.Windows.Forms.CheckBox
        Me.chkMonitoreo = New System.Windows.Forms.CheckBox
        Me.tbProcess = New System.Windows.Forms.TabPage
        Me.GroupBox3 = New System.Windows.Forms.GroupBox
        Me.chkCreditCards = New System.Windows.Forms.CheckBox
        Me.chkTelebingo = New System.Windows.Forms.CheckBox
        Me.chkRecharges = New System.Windows.Forms.CheckBox
        Me.chkDiccionario = New System.Windows.Forms.CheckBox
        Me.chkMedia = New System.Windows.Forms.CheckBox
        Me.chkTelechat = New System.Windows.Forms.CheckBox
        Me.chkList = New System.Windows.Forms.CheckBox
        Me.chkSurvey = New System.Windows.Forms.CheckBox
        Me.chkTrivias = New System.Windows.Forms.CheckBox
        Me.chkRifas = New System.Windows.Forms.CheckBox
        Me.chkSuscripcion = New System.Windows.Forms.CheckBox
        Me.TabPage1 = New System.Windows.Forms.TabPage
        Me.GroupBox4 = New System.Windows.Forms.GroupBox
        Me.cbServer = New System.Windows.Forms.ComboBox
        Me.Label1 = New System.Windows.Forms.Label
        Me.cmdTest = New System.Windows.Forms.Button
        Me.pass = New System.Windows.Forms.TextBox
        Me.user = New System.Windows.Forms.TextBox
        Me.bd = New System.Windows.Forms.TextBox
        Me.server = New System.Windows.Forms.TextBox
        Me.Label4 = New System.Windows.Forms.Label
        Me.Label3 = New System.Windows.Forms.Label
        Me.Label2 = New System.Windows.Forms.Label
        Me.GroupBox2 = New System.Windows.Forms.GroupBox
        Me.cmdBorrar = New System.Windows.Forms.Button
        Me.cmdTestInsert = New System.Windows.Forms.Button
        Me.TabControl1 = New System.Windows.Forms.TabControl
        Me.TabPage2 = New System.Windows.Forms.TabPage
        Me.GroupBox5 = New System.Windows.Forms.GroupBox
        Me.cmdLicencia = New System.Windows.Forms.Button
        Me.Label15 = New System.Windows.Forms.Label
        Me.txtLicencia = New System.Windows.Forms.TextBox
        Me.Label16 = New System.Windows.Forms.Label
        Me.txtActivacion = New System.Windows.Forms.TextBox
        Me.chkAnniversary = New System.Windows.Forms.CheckBox
        CType(Me.pbUpper, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.cmdPanel.SuspendLayout()
        CType(Me.imgLinea, System.ComponentModel.ISupportInitialize).BeginInit()
        CType(Me.ErrorProvider, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.TabPage3.SuspendLayout()
        Me.gbMonitoreo.SuspendLayout()
        Me.grpMonitoreo.SuspendLayout()
        Me.GroupBox1.SuspendLayout()
        Me.tbProcess.SuspendLayout()
        Me.GroupBox3.SuspendLayout()
        Me.TabPage1.SuspendLayout()
        Me.GroupBox4.SuspendLayout()
        Me.GroupBox2.SuspendLayout()
        Me.TabControl1.SuspendLayout()
        Me.TabPage2.SuspendLayout()
        Me.GroupBox5.SuspendLayout()
        Me.SuspendLayout()
        '
        'cmdPanel
        '
        Me.cmdPanel.Controls.Add(Me.cmdActivar)
        Me.cmdPanel.Controls.Add(Me.cmdSalvar)
        Me.cmdPanel.Location = New System.Drawing.Point(0, 445)
        Me.cmdPanel.Size = New System.Drawing.Size(410, 37)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdSalvar, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCerrar, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.imgLinea, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdActivar, 0)
        '
        'cmdCerrar
        '
        Me.cmdCerrar.Location = New System.Drawing.Point(305, 9)
        '
        'imgLinea
        '
        Me.imgLinea.Size = New System.Drawing.Size(400, 8)
        '
        'cmdSalvar
        '
        Me.cmdSalvar.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdSalvar.Location = New System.Drawing.Point(200, 9)
        Me.cmdSalvar.Name = "cmdSalvar"
        Me.cmdSalvar.Size = New System.Drawing.Size(100, 24)
        Me.cmdSalvar.TabIndex = 8
        Me.cmdSalvar.Text = "&Salvar"
        '
        'ErrorProvider
        '
        Me.ErrorProvider.ContainerControl = Me
        '
        'cmdActivar
        '
        Me.cmdActivar.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdActivar.Location = New System.Drawing.Point(96, 9)
        Me.cmdActivar.Name = "cmdActivar"
        Me.cmdActivar.Size = New System.Drawing.Size(100, 24)
        Me.cmdActivar.TabIndex = 11
        Me.cmdActivar.Text = "&Activar"
        Me.cmdActivar.UseVisualStyleBackColor = True
        '
        'Label5
        '
        Me.Label5.BackColor = System.Drawing.Color.Transparent
        Me.Label5.Location = New System.Drawing.Point(280, 61)
        Me.Label5.Name = "Label5"
        Me.Label5.Size = New System.Drawing.Size(50, 20)
        Me.Label5.TabIndex = 11
        Me.Label5.Text = "Status:"
        Me.Label5.TextAlign = System.Drawing.ContentAlignment.MiddleRight
        '
        'lblActive
        '
        Me.lblActive.ForeColor = System.Drawing.Color.Red
        Me.lblActive.Location = New System.Drawing.Point(328, 61)
        Me.lblActive.Name = "lblActive"
        Me.lblActive.Size = New System.Drawing.Size(72, 20)
        Me.lblActive.TabIndex = 12
        Me.lblActive.Text = "Inactivo"
        Me.lblActive.TextAlign = System.Drawing.ContentAlignment.MiddleRight
        '
        'tmr
        '
        Me.tmr.Enabled = True
        Me.tmr.Interval = 1000
        '
        'TabPage3
        '
        Me.TabPage3.BackColor = System.Drawing.Color.White
        Me.TabPage3.Controls.Add(Me.gbMonitoreo)
        Me.TabPage3.Controls.Add(Me.grpMonitoreo)
        Me.TabPage3.Controls.Add(Me.GroupBox1)
        Me.TabPage3.Location = New System.Drawing.Point(4, 22)
        Me.TabPage3.Name = "TabPage3"
        Me.TabPage3.Size = New System.Drawing.Size(394, 350)
        Me.TabPage3.TabIndex = 2
        Me.TabPage3.Text = "Opciones"
        Me.TabPage3.UseVisualStyleBackColor = True
        '
        'gbMonitoreo
        '
        Me.gbMonitoreo.Controls.Add(Me.Label14)
        Me.gbMonitoreo.Controls.Add(Me.NOTIFYNUMBEROUT)
        Me.gbMonitoreo.Controls.Add(Me.Label13)
        Me.gbMonitoreo.Controls.Add(Me.NOTIFYNUMBER)
        Me.gbMonitoreo.Location = New System.Drawing.Point(8, 129)
        Me.gbMonitoreo.Name = "gbMonitoreo"
        Me.gbMonitoreo.Size = New System.Drawing.Size(368, 100)
        Me.gbMonitoreo.TabIndex = 3
        Me.gbMonitoreo.TabStop = False
        Me.gbMonitoreo.Text = "Monitoreo"
        '
        'Label14
        '
        Me.Label14.Location = New System.Drawing.Point(8, 40)
        Me.Label14.Name = "Label14"
        Me.Label14.Size = New System.Drawing.Size(100, 21)
        Me.Label14.TabIndex = 12
        Me.Label14.Text = "Emisor:"
        Me.Label14.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'NOTIFYNUMBEROUT
        '
        Me.NOTIFYNUMBEROUT.BackColor = System.Drawing.Color.White
        Me.NOTIFYNUMBEROUT.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.NOTIFYNUMBEROUT.Location = New System.Drawing.Point(136, 40)
        Me.NOTIFYNUMBEROUT.Name = "NOTIFYNUMBEROUT"
        Me.NOTIFYNUMBEROUT.Size = New System.Drawing.Size(224, 21)
        Me.NOTIFYNUMBEROUT.TabIndex = 11
        '
        'Label13
        '
        Me.Label13.Location = New System.Drawing.Point(8, 16)
        Me.Label13.Name = "Label13"
        Me.Label13.Size = New System.Drawing.Size(100, 21)
        Me.Label13.TabIndex = 10
        Me.Label13.Text = "Receptor:"
        Me.Label13.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'NOTIFYNUMBER
        '
        Me.NOTIFYNUMBER.BackColor = System.Drawing.Color.White
        Me.NOTIFYNUMBER.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.NOTIFYNUMBER.Location = New System.Drawing.Point(136, 16)
        Me.NOTIFYNUMBER.Name = "NOTIFYNUMBER"
        Me.NOTIFYNUMBER.Size = New System.Drawing.Size(224, 21)
        Me.NOTIFYNUMBER.TabIndex = 5
        '
        'grpMonitoreo
        '
        Me.grpMonitoreo.Controls.Add(Me.Label12)
        Me.grpMonitoreo.Controls.Add(Me.NOTIFYSERVER)
        Me.grpMonitoreo.Controls.Add(Me.Label11)
        Me.grpMonitoreo.Controls.Add(Me.NOTIFYPASS)
        Me.grpMonitoreo.Controls.Add(Me.Label7)
        Me.grpMonitoreo.Controls.Add(Me.NOTIFYFROM)
        Me.grpMonitoreo.Controls.Add(Me.Label10)
        Me.grpMonitoreo.Controls.Add(Me.Label9)
        Me.grpMonitoreo.Controls.Add(Me.Label8)
        Me.grpMonitoreo.Controls.Add(Me.Label6)
        Me.grpMonitoreo.Controls.Add(Me.PINGINTERVAL)
        Me.grpMonitoreo.Controls.Add(Me.MAX_TIMEOUT)
        Me.grpMonitoreo.Controls.Add(Me.NOTIFYCC)
        Me.grpMonitoreo.Controls.Add(Me.NOTIFYTO)
        Me.grpMonitoreo.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.grpMonitoreo.Location = New System.Drawing.Point(8, 235)
        Me.grpMonitoreo.Name = "grpMonitoreo"
        Me.grpMonitoreo.Size = New System.Drawing.Size(368, 109)
        Me.grpMonitoreo.TabIndex = 2
        Me.grpMonitoreo.TabStop = False
        Me.grpMonitoreo.Text = "Monitoreo"
        Me.grpMonitoreo.Visible = False
        '
        'Label12
        '
        Me.Label12.Location = New System.Drawing.Point(8, 112)
        Me.Label12.Name = "Label12"
        Me.Label12.Size = New System.Drawing.Size(100, 21)
        Me.Label12.TabIndex = 19
        Me.Label12.Text = "Servidor:"
        Me.Label12.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'NOTIFYSERVER
        '
        Me.NOTIFYSERVER.BackColor = System.Drawing.Color.White
        Me.NOTIFYSERVER.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.NOTIFYSERVER.Location = New System.Drawing.Point(136, 112)
        Me.NOTIFYSERVER.Name = "NOTIFYSERVER"
        Me.NOTIFYSERVER.Size = New System.Drawing.Size(224, 21)
        Me.NOTIFYSERVER.TabIndex = 8
        '
        'Label11
        '
        Me.Label11.Location = New System.Drawing.Point(8, 88)
        Me.Label11.Name = "Label11"
        Me.Label11.Size = New System.Drawing.Size(100, 21)
        Me.Label11.TabIndex = 17
        Me.Label11.Text = "Clave:"
        Me.Label11.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'NOTIFYPASS
        '
        Me.NOTIFYPASS.BackColor = System.Drawing.Color.White
        Me.NOTIFYPASS.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.NOTIFYPASS.Location = New System.Drawing.Point(136, 88)
        Me.NOTIFYPASS.Name = "NOTIFYPASS"
        Me.NOTIFYPASS.PasswordChar = Global.Microsoft.VisualBasic.ChrW(42)
        Me.NOTIFYPASS.Size = New System.Drawing.Size(224, 21)
        Me.NOTIFYPASS.TabIndex = 7
        '
        'Label7
        '
        Me.Label7.Location = New System.Drawing.Point(8, 64)
        Me.Label7.Name = "Label7"
        Me.Label7.Size = New System.Drawing.Size(100, 21)
        Me.Label7.TabIndex = 15
        Me.Label7.Text = "Emisor:"
        Me.Label7.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'NOTIFYFROM
        '
        Me.NOTIFYFROM.BackColor = System.Drawing.Color.White
        Me.NOTIFYFROM.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.NOTIFYFROM.Location = New System.Drawing.Point(136, 64)
        Me.NOTIFYFROM.Name = "NOTIFYFROM"
        Me.NOTIFYFROM.Size = New System.Drawing.Size(224, 21)
        Me.NOTIFYFROM.TabIndex = 6
        '
        'Label10
        '
        Me.Label10.Location = New System.Drawing.Point(8, 40)
        Me.Label10.Name = "Label10"
        Me.Label10.Size = New System.Drawing.Size(100, 21)
        Me.Label10.TabIndex = 13
        Me.Label10.Text = "Copia:"
        Me.Label10.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'Label9
        '
        Me.Label9.Location = New System.Drawing.Point(8, 136)
        Me.Label9.Name = "Label9"
        Me.Label9.Size = New System.Drawing.Size(128, 21)
        Me.Label9.TabIndex = 12
        Me.Label9.Text = "Revision (segundos):"
        Me.Label9.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'Label8
        '
        Me.Label8.Location = New System.Drawing.Point(8, 160)
        Me.Label8.Name = "Label8"
        Me.Label8.Size = New System.Drawing.Size(112, 21)
        Me.Label8.TabIndex = 11
        Me.Label8.Text = "Limite (segundos):"
        Me.Label8.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'Label6
        '
        Me.Label6.Location = New System.Drawing.Point(8, 16)
        Me.Label6.Name = "Label6"
        Me.Label6.Size = New System.Drawing.Size(100, 21)
        Me.Label6.TabIndex = 9
        Me.Label6.Text = "Destino:"
        Me.Label6.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'PINGINTERVAL
        '
        Me.PINGINTERVAL.BackColor = System.Drawing.Color.White
        Me.PINGINTERVAL.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.PINGINTERVAL.Location = New System.Drawing.Point(136, 160)
        Me.PINGINTERVAL.Name = "PINGINTERVAL"
        Me.PINGINTERVAL.Size = New System.Drawing.Size(224, 21)
        Me.PINGINTERVAL.TabIndex = 10
        '
        'MAX_TIMEOUT
        '
        Me.MAX_TIMEOUT.BackColor = System.Drawing.Color.White
        Me.MAX_TIMEOUT.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.MAX_TIMEOUT.Location = New System.Drawing.Point(136, 136)
        Me.MAX_TIMEOUT.Name = "MAX_TIMEOUT"
        Me.MAX_TIMEOUT.Size = New System.Drawing.Size(224, 21)
        Me.MAX_TIMEOUT.TabIndex = 9
        '
        'NOTIFYCC
        '
        Me.NOTIFYCC.BackColor = System.Drawing.Color.White
        Me.NOTIFYCC.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.NOTIFYCC.Location = New System.Drawing.Point(136, 40)
        Me.NOTIFYCC.Name = "NOTIFYCC"
        Me.NOTIFYCC.Size = New System.Drawing.Size(224, 21)
        Me.NOTIFYCC.TabIndex = 5
        '
        'NOTIFYTO
        '
        Me.NOTIFYTO.BackColor = System.Drawing.Color.White
        Me.NOTIFYTO.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.NOTIFYTO.Location = New System.Drawing.Point(136, 16)
        Me.NOTIFYTO.Name = "NOTIFYTO"
        Me.NOTIFYTO.Size = New System.Drawing.Size(224, 21)
        Me.NOTIFYTO.TabIndex = 4
        '
        'GroupBox1
        '
        Me.GroupBox1.Controls.Add(Me.chkPruebas)
        Me.GroupBox1.Controls.Add(Me.chkActivate)
        Me.GroupBox1.Controls.Add(Me.chkStartup)
        Me.GroupBox1.Controls.Add(Me.chkMonitoreo)
        Me.GroupBox1.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.GroupBox1.Location = New System.Drawing.Point(8, 8)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(368, 120)
        Me.GroupBox1.TabIndex = 1
        Me.GroupBox1.TabStop = False
        Me.GroupBox1.Text = "Inicialización"
        '
        'chkPruebas
        '
        Me.chkPruebas.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.chkPruebas.Location = New System.Drawing.Point(8, 64)
        Me.chkPruebas.Name = "chkPruebas"
        Me.chkPruebas.Size = New System.Drawing.Size(352, 24)
        Me.chkPruebas.TabIndex = 2
        Me.chkPruebas.Text = "Habilitar Pruebas"
        '
        'chkActivate
        '
        Me.chkActivate.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.chkActivate.Location = New System.Drawing.Point(8, 40)
        Me.chkActivate.Name = "chkActivate"
        Me.chkActivate.Size = New System.Drawing.Size(352, 24)
        Me.chkActivate.TabIndex = 1
        Me.chkActivate.Text = "Iniciar servicio automáticamente"
        '
        'chkStartup
        '
        Me.chkStartup.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.chkStartup.Location = New System.Drawing.Point(8, 16)
        Me.chkStartup.Name = "chkStartup"
        Me.chkStartup.Size = New System.Drawing.Size(352, 24)
        Me.chkStartup.TabIndex = 0
        Me.chkStartup.Text = "Iniciar con Sistema Operativo"
        '
        'chkMonitoreo
        '
        Me.chkMonitoreo.Checked = True
        Me.chkMonitoreo.CheckState = System.Windows.Forms.CheckState.Checked
        Me.chkMonitoreo.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.chkMonitoreo.Location = New System.Drawing.Point(8, 88)
        Me.chkMonitoreo.Name = "chkMonitoreo"
        Me.chkMonitoreo.Size = New System.Drawing.Size(352, 24)
        Me.chkMonitoreo.TabIndex = 3
        Me.chkMonitoreo.Text = "Habilitar Monitoreo"
        '
        'tbProcess
        '
        Me.tbProcess.BackColor = System.Drawing.Color.White
        Me.tbProcess.Controls.Add(Me.GroupBox3)
        Me.tbProcess.Location = New System.Drawing.Point(4, 22)
        Me.tbProcess.Name = "tbProcess"
        Me.tbProcess.Size = New System.Drawing.Size(394, 350)
        Me.tbProcess.TabIndex = 3
        Me.tbProcess.Text = "Procesos"
        Me.tbProcess.UseVisualStyleBackColor = True
        '
        'GroupBox3
        '
        Me.GroupBox3.Controls.Add(Me.chkAnniversary)
        Me.GroupBox3.Controls.Add(Me.chkCreditCards)
        Me.GroupBox3.Controls.Add(Me.chkTelebingo)
        Me.GroupBox3.Controls.Add(Me.chkRecharges)
        Me.GroupBox3.Controls.Add(Me.chkDiccionario)
        Me.GroupBox3.Controls.Add(Me.chkMedia)
        Me.GroupBox3.Controls.Add(Me.chkTelechat)
        Me.GroupBox3.Controls.Add(Me.chkList)
        Me.GroupBox3.Controls.Add(Me.chkSurvey)
        Me.GroupBox3.Controls.Add(Me.chkTrivias)
        Me.GroupBox3.Controls.Add(Me.chkRifas)
        Me.GroupBox3.Controls.Add(Me.chkSuscripcion)
        Me.GroupBox3.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.GroupBox3.Location = New System.Drawing.Point(8, 8)
        Me.GroupBox3.Name = "GroupBox3"
        Me.GroupBox3.Size = New System.Drawing.Size(368, 328)
        Me.GroupBox3.TabIndex = 0
        Me.GroupBox3.TabStop = False
        Me.GroupBox3.Text = "Procesos"
        '
        'chkCreditCards
        '
        Me.chkCreditCards.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.chkCreditCards.Location = New System.Drawing.Point(7, 264)
        Me.chkCreditCards.Name = "chkCreditCards"
        Me.chkCreditCards.Size = New System.Drawing.Size(352, 24)
        Me.chkCreditCards.TabIndex = 10
        Me.chkCreditCards.Text = "Procesar Tarjetas"
        '
        'chkTelebingo
        '
        Me.chkTelebingo.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.chkTelebingo.Location = New System.Drawing.Point(7, 240)
        Me.chkTelebingo.Name = "chkTelebingo"
        Me.chkTelebingo.Size = New System.Drawing.Size(352, 24)
        Me.chkTelebingo.TabIndex = 9
        Me.chkTelebingo.Text = "Procesar Telebingo"
        '
        'chkRecharges
        '
        Me.chkRecharges.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.chkRecharges.Location = New System.Drawing.Point(7, 216)
        Me.chkRecharges.Name = "chkRecharges"
        Me.chkRecharges.Size = New System.Drawing.Size(352, 24)
        Me.chkRecharges.TabIndex = 8
        Me.chkRecharges.Text = "Procesar Recargas"
        '
        'chkDiccionario
        '
        Me.chkDiccionario.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.chkDiccionario.Location = New System.Drawing.Point(7, 168)
        Me.chkDiccionario.Name = "chkDiccionario"
        Me.chkDiccionario.Size = New System.Drawing.Size(352, 24)
        Me.chkDiccionario.TabIndex = 7
        Me.chkDiccionario.Text = "Procesar Diccionario"
        '
        'chkMedia
        '
        Me.chkMedia.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.chkMedia.Location = New System.Drawing.Point(7, 192)
        Me.chkMedia.Name = "chkMedia"
        Me.chkMedia.Size = New System.Drawing.Size(352, 24)
        Me.chkMedia.TabIndex = 6
        Me.chkMedia.Text = "Procesar Media"
        '
        'chkTelechat
        '
        Me.chkTelechat.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.chkTelechat.Location = New System.Drawing.Point(7, 144)
        Me.chkTelechat.Name = "chkTelechat"
        Me.chkTelechat.Size = New System.Drawing.Size(352, 24)
        Me.chkTelechat.TabIndex = 5
        Me.chkTelechat.Text = "Procesar Telechat"
        '
        'chkList
        '
        Me.chkList.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.chkList.Location = New System.Drawing.Point(7, 120)
        Me.chkList.Name = "chkList"
        Me.chkList.Size = New System.Drawing.Size(352, 24)
        Me.chkList.TabIndex = 4
        Me.chkList.Text = "Procesar Listados"
        '
        'chkSurvey
        '
        Me.chkSurvey.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.chkSurvey.Location = New System.Drawing.Point(7, 96)
        Me.chkSurvey.Name = "chkSurvey"
        Me.chkSurvey.Size = New System.Drawing.Size(352, 24)
        Me.chkSurvey.TabIndex = 3
        Me.chkSurvey.Text = "Procesar Encuestas"
        '
        'chkTrivias
        '
        Me.chkTrivias.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.chkTrivias.Location = New System.Drawing.Point(7, 72)
        Me.chkTrivias.Name = "chkTrivias"
        Me.chkTrivias.Size = New System.Drawing.Size(352, 24)
        Me.chkTrivias.TabIndex = 2
        Me.chkTrivias.Text = "Procesar Trivias"
        '
        'chkRifas
        '
        Me.chkRifas.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.chkRifas.Location = New System.Drawing.Point(7, 48)
        Me.chkRifas.Name = "chkRifas"
        Me.chkRifas.Size = New System.Drawing.Size(352, 24)
        Me.chkRifas.TabIndex = 1
        Me.chkRifas.Text = "Procesar Rifas"
        '
        'chkSuscripcion
        '
        Me.chkSuscripcion.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.chkSuscripcion.Location = New System.Drawing.Point(7, 24)
        Me.chkSuscripcion.Name = "chkSuscripcion"
        Me.chkSuscripcion.Size = New System.Drawing.Size(352, 24)
        Me.chkSuscripcion.TabIndex = 0
        Me.chkSuscripcion.Text = "Procesar Suscripciones"
        '
        'TabPage1
        '
        Me.TabPage1.BackColor = System.Drawing.Color.White
        Me.TabPage1.Controls.Add(Me.GroupBox4)
        Me.TabPage1.Controls.Add(Me.GroupBox2)
        Me.TabPage1.Location = New System.Drawing.Point(4, 22)
        Me.TabPage1.Name = "TabPage1"
        Me.TabPage1.Size = New System.Drawing.Size(394, 350)
        Me.TabPage1.TabIndex = 0
        Me.TabPage1.Text = "SQL Server"
        Me.TabPage1.UseVisualStyleBackColor = True
        '
        'GroupBox4
        '
        Me.GroupBox4.Controls.Add(Me.cbServer)
        Me.GroupBox4.Controls.Add(Me.Label1)
        Me.GroupBox4.Controls.Add(Me.cmdTest)
        Me.GroupBox4.Controls.Add(Me.pass)
        Me.GroupBox4.Controls.Add(Me.user)
        Me.GroupBox4.Controls.Add(Me.bd)
        Me.GroupBox4.Controls.Add(Me.server)
        Me.GroupBox4.Controls.Add(Me.Label4)
        Me.GroupBox4.Controls.Add(Me.Label3)
        Me.GroupBox4.Controls.Add(Me.Label2)
        Me.GroupBox4.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.GroupBox4.Location = New System.Drawing.Point(8, 8)
        Me.GroupBox4.Name = "GroupBox4"
        Me.GroupBox4.Size = New System.Drawing.Size(368, 176)
        Me.GroupBox4.TabIndex = 12
        Me.GroupBox4.TabStop = False
        Me.GroupBox4.Text = "Conexión"
        '
        'cbServer
        '
        Me.cbServer.BackColor = System.Drawing.Color.White
        Me.cbServer.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cbServer.Items.AddRange(New Object() {"SQL Server 2000", "MySQL Server"})
        Me.cbServer.Location = New System.Drawing.Point(104, 120)
        Me.cbServer.Name = "cbServer"
        Me.cbServer.Size = New System.Drawing.Size(256, 21)
        Me.cbServer.TabIndex = 10
        '
        'Label1
        '
        Me.Label1.Location = New System.Drawing.Point(8, 24)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(96, 21)
        Me.Label1.TabIndex = 4
        Me.Label1.Text = "Servidor:"
        Me.Label1.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'cmdTest
        '
        Me.cmdTest.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdTest.Location = New System.Drawing.Point(260, 144)
        Me.cmdTest.Name = "cmdTest"
        Me.cmdTest.Size = New System.Drawing.Size(100, 24)
        Me.cmdTest.TabIndex = 9
        Me.cmdTest.Text = "&Probar"
        '
        'pass
        '
        Me.pass.BackColor = System.Drawing.Color.White
        Me.pass.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.pass.Location = New System.Drawing.Point(104, 96)
        Me.pass.Name = "pass"
        Me.pass.PasswordChar = Global.Microsoft.VisualBasic.ChrW(42)
        Me.pass.Size = New System.Drawing.Size(256, 21)
        Me.pass.TabIndex = 3
        '
        'user
        '
        Me.user.BackColor = System.Drawing.Color.White
        Me.user.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.user.Location = New System.Drawing.Point(104, 72)
        Me.user.Name = "user"
        Me.user.Size = New System.Drawing.Size(256, 21)
        Me.user.TabIndex = 2
        '
        'bd
        '
        Me.bd.BackColor = System.Drawing.Color.White
        Me.bd.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.bd.Location = New System.Drawing.Point(104, 48)
        Me.bd.Name = "bd"
        Me.bd.Size = New System.Drawing.Size(256, 21)
        Me.bd.TabIndex = 1
        '
        'server
        '
        Me.server.BackColor = System.Drawing.Color.White
        Me.server.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.server.Location = New System.Drawing.Point(104, 24)
        Me.server.Name = "server"
        Me.server.Size = New System.Drawing.Size(256, 21)
        Me.server.TabIndex = 0
        '
        'Label4
        '
        Me.Label4.Location = New System.Drawing.Point(8, 48)
        Me.Label4.Name = "Label4"
        Me.Label4.Size = New System.Drawing.Size(96, 21)
        Me.Label4.TabIndex = 7
        Me.Label4.Text = "Base de Datos:"
        Me.Label4.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'Label3
        '
        Me.Label3.Location = New System.Drawing.Point(8, 72)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(96, 21)
        Me.Label3.TabIndex = 6
        Me.Label3.Text = "Usuario:"
        Me.Label3.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'Label2
        '
        Me.Label2.Location = New System.Drawing.Point(8, 96)
        Me.Label2.Name = "Label2"
        Me.Label2.Size = New System.Drawing.Size(96, 21)
        Me.Label2.TabIndex = 5
        Me.Label2.Text = "Clave:"
        Me.Label2.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'GroupBox2
        '
        Me.GroupBox2.Controls.Add(Me.cmdBorrar)
        Me.GroupBox2.Controls.Add(Me.cmdTestInsert)
        Me.GroupBox2.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.GroupBox2.Location = New System.Drawing.Point(8, 288)
        Me.GroupBox2.Name = "GroupBox2"
        Me.GroupBox2.Size = New System.Drawing.Size(368, 56)
        Me.GroupBox2.TabIndex = 11
        Me.GroupBox2.TabStop = False
        Me.GroupBox2.Text = "Development Purposes Only"
        '
        'cmdBorrar
        '
        Me.cmdBorrar.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdBorrar.Location = New System.Drawing.Point(120, 24)
        Me.cmdBorrar.Name = "cmdBorrar"
        Me.cmdBorrar.Size = New System.Drawing.Size(100, 24)
        Me.cmdBorrar.TabIndex = 11
        Me.cmdBorrar.Text = "&Borrar Pruebas"
        '
        'cmdTestInsert
        '
        Me.cmdTestInsert.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdTestInsert.Location = New System.Drawing.Point(8, 24)
        Me.cmdTestInsert.Name = "cmdTestInsert"
        Me.cmdTestInsert.Size = New System.Drawing.Size(100, 24)
        Me.cmdTestInsert.TabIndex = 10
        Me.cmdTestInsert.Text = "&Probar Insert"
        '
        'TabControl1
        '
        Me.TabControl1.Controls.Add(Me.TabPage1)
        Me.TabControl1.Controls.Add(Me.tbProcess)
        Me.TabControl1.Controls.Add(Me.TabPage3)
        Me.TabControl1.Controls.Add(Me.TabPage2)
        Me.TabControl1.Location = New System.Drawing.Point(3, 63)
        Me.TabControl1.Name = "TabControl1"
        Me.TabControl1.SelectedIndex = 0
        Me.TabControl1.Size = New System.Drawing.Size(402, 376)
        Me.TabControl1.TabIndex = 10
        '
        'TabPage2
        '
        Me.TabPage2.BackColor = System.Drawing.Color.White
        Me.TabPage2.Controls.Add(Me.GroupBox5)
        Me.TabPage2.Location = New System.Drawing.Point(4, 22)
        Me.TabPage2.Name = "TabPage2"
        Me.TabPage2.Padding = New System.Windows.Forms.Padding(3)
        Me.TabPage2.Size = New System.Drawing.Size(394, 350)
        Me.TabPage2.TabIndex = 4
        Me.TabPage2.Text = "Licencia"
        '
        'GroupBox5
        '
        Me.GroupBox5.Controls.Add(Me.cmdLicencia)
        Me.GroupBox5.Controls.Add(Me.Label15)
        Me.GroupBox5.Controls.Add(Me.txtLicencia)
        Me.GroupBox5.Controls.Add(Me.Label16)
        Me.GroupBox5.Controls.Add(Me.txtActivacion)
        Me.GroupBox5.Location = New System.Drawing.Point(6, 6)
        Me.GroupBox5.Name = "GroupBox5"
        Me.GroupBox5.Size = New System.Drawing.Size(372, 104)
        Me.GroupBox5.TabIndex = 0
        Me.GroupBox5.TabStop = False
        Me.GroupBox5.Text = "Datos de Licencia:"
        '
        'cmdLicencia
        '
        Me.cmdLicencia.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdLicencia.Location = New System.Drawing.Point(266, 71)
        Me.cmdLicencia.Name = "cmdLicencia"
        Me.cmdLicencia.Size = New System.Drawing.Size(100, 24)
        Me.cmdLicencia.TabIndex = 17
        Me.cmdLicencia.Text = "A&plicar"
        Me.cmdLicencia.UseVisualStyleBackColor = True
        '
        'Label15
        '
        Me.Label15.Location = New System.Drawing.Point(9, 44)
        Me.Label15.Name = "Label15"
        Me.Label15.Size = New System.Drawing.Size(133, 21)
        Me.Label15.TabIndex = 16
        Me.Label15.Text = "Licencia:"
        Me.Label15.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'txtLicencia
        '
        Me.txtLicencia.BackColor = System.Drawing.Color.White
        Me.txtLicencia.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.txtLicencia.Location = New System.Drawing.Point(142, 44)
        Me.txtLicencia.Name = "txtLicencia"
        Me.txtLicencia.Size = New System.Drawing.Size(224, 21)
        Me.txtLicencia.TabIndex = 15
        '
        'Label16
        '
        Me.Label16.Location = New System.Drawing.Point(7, 20)
        Me.Label16.Name = "Label16"
        Me.Label16.Size = New System.Drawing.Size(135, 21)
        Me.Label16.TabIndex = 14
        Me.Label16.Text = "Número de Activación:"
        Me.Label16.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'txtActivacion
        '
        Me.txtActivacion.BackColor = System.Drawing.Color.White
        Me.txtActivacion.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.txtActivacion.Location = New System.Drawing.Point(142, 20)
        Me.txtActivacion.Name = "txtActivacion"
        Me.txtActivacion.Size = New System.Drawing.Size(224, 21)
        Me.txtActivacion.TabIndex = 13
        '
        'chkAnniversary
        '
        Me.chkAnniversary.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.chkAnniversary.Location = New System.Drawing.Point(6, 294)
        Me.chkAnniversary.Name = "chkAnniversary"
        Me.chkAnniversary.Size = New System.Drawing.Size(352, 24)
        Me.chkAnniversary.TabIndex = 11
        Me.chkAnniversary.Text = "Procesar Aniversarios"
        '
        'principal
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(6, 14)
        Me.ClientSize = New System.Drawing.Size(410, 482)
        Me.Controls.Add(Me.lblActive)
        Me.Controls.Add(Me.Label5)
        Me.Controls.Add(Me.TabControl1)
        Me.MinimizeBox = True
        Me.Name = "principal"
        Me.pTitle = "Configuración SMPP"
        Me.ShowInTaskbar = True
        Me.Text = "Configuración SMPP"
        Me.Controls.SetChildIndex(Me.TabControl1, 0)
        Me.Controls.SetChildIndex(Me.Label5, 0)
        Me.Controls.SetChildIndex(Me.lblActive, 0)
        Me.Controls.SetChildIndex(Me.cmdPanel, 0)
        Me.Controls.SetChildIndex(Me.pbUpper, 0)
        CType(Me.pbUpper, System.ComponentModel.ISupportInitialize).EndInit()
        Me.cmdPanel.ResumeLayout(False)
        CType(Me.imgLinea, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.ErrorProvider, System.ComponentModel.ISupportInitialize).EndInit()
        Me.TabPage3.ResumeLayout(False)
        Me.gbMonitoreo.ResumeLayout(False)
        Me.gbMonitoreo.PerformLayout()
        Me.grpMonitoreo.ResumeLayout(False)
        Me.grpMonitoreo.PerformLayout()
        Me.GroupBox1.ResumeLayout(False)
        Me.tbProcess.ResumeLayout(False)
        Me.GroupBox3.ResumeLayout(False)
        Me.TabPage1.ResumeLayout(False)
        Me.GroupBox4.ResumeLayout(False)
        Me.GroupBox4.PerformLayout()
        Me.GroupBox2.ResumeLayout(False)
        Me.TabControl1.ResumeLayout(False)
        Me.TabPage2.ResumeLayout(False)
        Me.GroupBox5.ResumeLayout(False)
        Me.GroupBox5.PerformLayout()
        Me.ResumeLayout(False)

    End Sub
#End Region

#Region " CONSTANTS "
    Private Const COMPLETED_SUCCESFULLY As String = "Conexión Exitosa"
    Private Const SAVED_SUCCESFULLY As String = "La licencia ha sido configurada. Favor reinicie la aplicacion para validarla"

    Private Const STR_ACTIVE As String = "Activo"
    Private Const STR_PAUSED As String = "En Pausa"
    Private Const STR_DEACTIVE As String = "Inactivo"
    Private Const STR_ACTIVATING As String = "Activando"
    Private Const STR_PAUSING As String = "Pausando"
    Private Const STR_DEACTIVATING As String = "Inactivando"

    Private Const STR_ACTIVATE As String = "Activar"
    Private Const STR_DEACTIVATE As String = "Desactivar"

#End Region
#Region " FUNCTIONS "
    Private Function checkStatus() As ServiceControllerStatus
        Dim myController As New ServiceController("SMPP Service")
        lblActive.ForeColor = Color.SteelBlue
        cmdActivar.Enabled = True
        cmdActivar.Text = STR_ACTIVATE
        Try
            If myController.Status = ServiceControllerStatus.Running Then
                lblActive.Text = STR_ACTIVE
                cmdActivar.Text = STR_DEACTIVATE
            ElseIf myController.Status = ServiceControllerStatus.ContinuePending Then
                lblActive.Text = STR_ACTIVATING
                cmdActivar.Enabled = False
                cmdActivar.Text = STR_DEACTIVATE
            ElseIf myController.Status = ServiceControllerStatus.StartPending Then
                lblActive.Text = STR_ACTIVATING
                cmdActivar.Enabled = False
                cmdActivar.Text = STR_DEACTIVATE
            ElseIf myController.Status = ServiceControllerStatus.Paused Then
                lblActive.ForeColor = Color.Red
                lblActive.Text = STR_PAUSED
            ElseIf myController.Status = ServiceControllerStatus.PausePending Then
                lblActive.ForeColor = Color.Red
                lblActive.Text = STR_PAUSING
                cmdActivar.Enabled = False
            ElseIf myController.Status = ServiceControllerStatus.Stopped Then
                lblActive.ForeColor = Color.Red
                lblActive.Text = STR_DEACTIVE
            ElseIf myController.Status = ServiceControllerStatus.StopPending Then
                lblActive.ForeColor = Color.Red
                lblActive.Text = STR_DEACTIVATING
                cmdActivar.Enabled = False
            Else
            End If
            Return myController.Status
        Catch
        End Try
    End Function
#End Region

    Private myData As library.registryVals
    Private myController As ServiceController
    Private Sub principal_Load(ByVal sender As Object, ByVal e As EventArgs) Handles MyBase.Load
        myController = New ServiceController("SMPP Service")
        myData = library.loadRegistry()
        '        server.Text = myData.server
        bd.Text = myData.bd
        user.Text = myData.user
        pass.Text = myData.pass
        cbServer.SelectedIndex = myData.serverType
        chkStartup.Checked = myData.RUN_INTERFACE
        chkActivate.Checked = myData.AUTO_START_SERVICE
        chkPruebas.Checked = myData.TESTING_ENABLED
        chkMonitoreo.Checked = myData.MONITORING_ENABLED

        PINGINTERVAL.Text = myData.PINGINTERVAL
        NOTIFYNUMBER.Text = myData.SMS_NUMBER
        NOTIFYNUMBEROUT.Text = myData.SMS_NUMBER_OUT

        If Not myData.SHOW_PROCESSING Then
            TabControl1.TabPages.Remove(tbProcess)
            cbServer.Visible = False
        End If

        chkSuscripcion.Checked = myData.PROCESS_SUSCRIPTION
        chkTelechat.Checked = myData.PROCESS_TELECHAT
        chkMedia.Checked = myData.PROCESS_MEDIA
        chkRifas.Checked = myData.PROCESS_RAFFLE
        chkTrivias.Checked = myData.PROCESS_TRIVIA
        chkSurvey.Checked = myData.PROCESS_SURVEY
        chkList.Checked = myData.PROCESS_LIST
        chkDiccionario.Checked = myData.PROCESS_DICTIONARY
        chkRecharges.Checked = myData.PROCESS_RECHARGES
        chkRecharges.Checked = myData.PROCESS_CREDITCARDS
        chkTelebingo.Checked = myData.PROCESS_TELEBINGO
        chkAnniversary.Checked = myData.PROCESS_ANNIVERSARY
        TabControl1.SelectedIndex = 0
        checkStatus()
        generarActivacion()
    End Sub
    Private Sub cmdSalvar_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdSalvar.Click
        'myData.server = server.Text
        myData.bd = bd.Text
        myData.serverType = cbServer.SelectedIndex
        myData.user = user.Text
        myData.pass = pass.Text
        myData.RUN_INTERFACE = chkStartup.Checked
        myData.AUTO_START_SERVICE = chkActivate.Checked
        myData.TESTING_ENABLED = chkPruebas.Checked
        'myData.SHOW_PROCESSING = myData.SHOW_PROCESSING
        myData.PROCESS_SUSCRIPTION = chkSuscripcion.Checked
        myData.PROCESS_TELECHAT = chkTelechat.Checked
        myData.PROCESS_MEDIA = chkMedia.Checked
        myData.PROCESS_RAFFLE = chkRifas.Checked
        myData.PROCESS_TRIVIA = chkTrivias.Checked
        myData.PROCESS_SURVEY = chkSurvey.Checked
        myData.PROCESS_LIST = chkList.Checked
        myData.PROCESS_DICTIONARY = chkDiccionario.Checked
        myData.PROCESS_RECHARGES = chkRecharges.Checked
        myData.PROCESS_CREDITCARDS = chkRecharges.Checked
        myData.PROCESS_TELEBINGO = chkTelebingo.Checked
        myData.PROCESS_ANNIVERSARY = chkAnniversary.Checked

        myData.MONITORING_ENABLED = chkMonitoreo.Checked

        myData.SMS_NUMBER = NOTIFYNUMBER.Text
        myData.SMS_NUMBER_OUT = NOTIFYNUMBEROUT.Text
        myData.PINGINTERVAL = PINGINTERVAL.Text

        library.saveRegistry(myData)
    End Sub

    Private Sub chkMonitoreo_CheckedChanged(ByVal sender As Object, ByVal e As EventArgs) Handles chkMonitoreo.CheckedChanged
        Dim iControl As Control
        For Each iControl In grpMonitoreo.Controls
            iControl.Enabled = sender.checked
        Next
    End Sub

    Private Sub cmdBorrar_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdBorrar.Click
        Dim test As New library.sqlServer(server.Text, bd.Text, user.Text, pass.Text, IIf(cbServer.SelectedIndex = 0, library.sqlServer.serverType.SQLServer, library.sqlServer.serverType.MySQL))
        MsgBox(test.deleteTests)
    End Sub
    Private Sub cmdTest_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdTest.Click
        Dim test As New library.sqlServer(server.Text, bd.Text, user.Text, pass.Text, IIf(cbServer.SelectedIndex = 0, library.sqlServer.serverType.SQLServer, library.sqlServer.serverType.MySQL))
        If test.state = ConnectionState.Open Then MsgBox(COMPLETED_SUCCESFULLY)
    End Sub
    Private Sub cmdTestInsert_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdTestInsert.Click
        Dim test As New library.sqlServer(server.Text, bd.Text, user.Text, pass.Text, IIf(cbServer.SelectedIndex = 0, library.sqlServer.serverType.SQLServer, library.sqlServer.serverType.MySQL))
        test.insertTest()
    End Sub

    Private Sub cmdActivar_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdActivar.Click
        Try
            Dim status As ServiceControllerStatus = checkStatus()
            If status = ServiceControllerStatus.Paused Or status = ServiceControllerStatus.PausePending Or status = ServiceControllerStatus.Stopped Or status = ServiceControllerStatus.StopPending Then
                myController.Start()
            Else
                myController.Stop()

                Threading.Thread.Sleep(2000)
                Dim myProcess As Process
                Dim myProcesses As Process() = Process.GetProcessesByName("java")
                For Each myProcess In myProcesses
                    myProcess.Kill()
                Next myProcess

                myProcesses = Process.GetProcessesByName("service")
                For Each myProcess In myProcesses
                    myProcess.Kill()
                Next myProcess
            End If
        Catch exp As Exception
            MsgBox(exp.InnerException.Message)
        End Try
    End Sub

    Private Sub tmr_Tick(ByVal sender As Object, ByVal e As EventArgs) Handles tmr.Tick
        checkStatus()
    End Sub
    Sub generarActivacion()
        Dim searcher As New ManagementObjectSearcher("SELECT * FROM Win32_Volume")
        Dim i As Integer = 0

        Dim wmi_HD As ManagementObject
        Dim activacion As String = ""

        For Each wmi_HD In searcher.Get()
            'get the hard drive from collection using index
            Dim serial As String

            'get the hardware serial no.
            If wmi_HD("SerialNumber") Is Nothing Then
                serial = ""
            Else
                serial = wmi_HD("SerialNumber").ToString()

                Dim d As New Encryption.Data(serial & "xcphyOK8")
                Dim hash1 As New Encryption.Hash(Encryption.Hash.Provider.SHA1)
                hash1.Calculate(d)
                activacion = hash1.Value.Hex.ToUpper.Substring(0, 16)

                txtActivacion.Text = activacion
            End If
            Exit For 'Solo para el disco primario
            i += 1
        Next
    End Sub
    Private Sub cmdLicencia_Click(ByVal sender As Object, ByVal e As EventArgs) Handles cmdLicencia.Click
        Try
            If Me.txtLicencia.Text.Length <> 16 Then
                MsgBox("La licencia debe ser de dieciseis caracteres")
                Exit Sub
            End If
            Dim test As New library.sqlServer(server.Text, bd.Text, user.Text, pass.Text, IIf(cbServer.SelectedIndex = 0, library.sqlServer.serverType.SQLServer, library.sqlServer.serverType.MySQL))
            If test.saveLicense(Me.txtLicencia.Text.ToUpper) Then
                MsgBox(SAVED_SUCCESFULLY)
            Else
                MsgBox("Error al salvar licencia!")
            End If
        Catch ex As Exception
            MsgBox("Error al salvar licencia! " & ex.Message)
        End Try
    End Sub
End Class