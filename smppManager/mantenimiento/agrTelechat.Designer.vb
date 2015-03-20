Imports MySql.Data.MySqlClient

<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()> _
Partial Class agrTelechat
    Inherits formControl.frmTemplate

#Region " Windows Form Designer generated code "
    Public Sub New()
        MyBase.New()
        InitializeComponent()
        fncListboxMySQL.popularMySQL(CObj(cbNumeros), "numeros", "numero", "numero", CNX)
    End Sub
    Sub New(ByVal id As Integer)
        MyBase.New()
        InitializeComponent()
        fncListboxMySQL.popularMySQL(CObj(cbNumeros), "numeros", "numero", "numero", CNX)
        vID = id
        pTitle = "Modificar Telechat"
        cmdCrear.Text = "Modificar"
        If CNX.State <> ConnectionState.Open Then CNX.Open()
        Dim dr As MySqlDataReader = New MySqlCommand("SELECT nombre, mensaje_participante, usuario, clave, numero FROM telechats WHERE id=" & id, CNX).ExecuteReader
        If dr.Read Then
            nombre.Text = dr!nombre
            mensaje_participantes.Text = dr!mensaje_participante
            usuario.Text = dr!usuario
            clave.Text = dr!clave
            seleccionar(cbNumeros, dr!numero)
            dr.Close()
            fncListboxMySQL.popularMySQL(CObj(lbclaves), "claves", "id", "clave", CNX, "idTelechat=" & vID)
            fncListboxMySQL.popularMySQL(CObj(lbBloqueos), "telechats_bloqueados", "id", "palabra", CNX, "idTeleChat=" & vID)
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
    Friend WithEvents GroupBox1 As System.Windows.Forms.GroupBox
    Friend WithEvents Label3 As System.Windows.Forms.Label
    Friend WithEvents ErrorProvider As System.Windows.Forms.ErrorProvider
    Friend WithEvents cmdCrear As System.Windows.Forms.Button
    Friend WithEvents nombre As System.Windows.Forms.TextBox
    Friend WithEvents mensaje_participantes As System.Windows.Forms.TextBox
    Friend WithEvents cbNumeros As System.Windows.Forms.ComboBox
    Friend WithEvents Label6 As System.Windows.Forms.Label
    Friend WithEvents cmdEli As System.Windows.Forms.Button
    Friend WithEvents cmdAgr As System.Windows.Forms.Button
    Friend WithEvents lbclaves As System.Windows.Forms.ListBox
    Friend WithEvents Label1 As System.Windows.Forms.Label
    Friend WithEvents GroupBox2 As System.Windows.Forms.GroupBox
    Friend WithEvents Label7 As System.Windows.Forms.Label
    Friend WithEvents usuario As System.Windows.Forms.TextBox
    Friend WithEvents Label8 As System.Windows.Forms.Label
    Friend WithEvents clave As System.Windows.Forms.TextBox
    Friend WithEvents Button1 As System.Windows.Forms.Button
    Friend WithEvents Label9 As System.Windows.Forms.Label
    Friend WithEvents logo As System.Windows.Forms.TextBox
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.components = New System.ComponentModel.Container()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.cmdEli = New System.Windows.Forms.Button()
        Me.cmdAgr = New System.Windows.Forms.Button()
        Me.lbclaves = New System.Windows.Forms.ListBox()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.Label6 = New System.Windows.Forms.Label()
        Me.cbNumeros = New System.Windows.Forms.ComboBox()
        Me.Label5 = New System.Windows.Forms.Label()
        Me.mensaje_participantes = New System.Windows.Forms.TextBox()
        Me.Label3 = New System.Windows.Forms.Label()
        Me.nombre = New System.Windows.Forms.TextBox()
        Me.ErrorProvider = New System.Windows.Forms.ErrorProvider(Me.components)
        Me.cmdCrear = New System.Windows.Forms.Button()
        Me.GroupBox2 = New System.Windows.Forms.GroupBox()
        Me.Button1 = New System.Windows.Forms.Button()
        Me.Label9 = New System.Windows.Forms.Label()
        Me.logo = New System.Windows.Forms.TextBox()
        Me.usuario = New System.Windows.Forms.TextBox()
        Me.Label8 = New System.Windows.Forms.Label()
        Me.clave = New System.Windows.Forms.TextBox()
        Me.Label7 = New System.Windows.Forms.Label()
        Me.gbBloqueos = New System.Windows.Forms.GroupBox()
        Me.cmdEliB = New System.Windows.Forms.Button()
        Me.cmdAgrB = New System.Windows.Forms.Button()
        Me.lbBloqueos = New System.Windows.Forms.ListBox()
        Me.Label17 = New System.Windows.Forms.Label()
        CType(Me.pbUpper, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.cmdPanel.SuspendLayout()
        CType(Me.imgLinea, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.GroupBox1.SuspendLayout()
        CType(Me.ErrorProvider, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.GroupBox2.SuspendLayout()
        Me.gbBloqueos.SuspendLayout()
        Me.SuspendLayout()
        '
        'cmdPanel
        '
        Me.cmdPanel.Controls.Add(Me.cmdCrear)
        Me.cmdPanel.Location = New System.Drawing.Point(0, 325)
        Me.cmdPanel.Size = New System.Drawing.Size(919, 37)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCrear, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCerrar, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.imgLinea, 0)
        '
        'cmdCerrar
        '
        Me.cmdCerrar.Location = New System.Drawing.Point(814, 9)
        '
        'imgLinea
        '
        Me.imgLinea.Size = New System.Drawing.Size(909, 8)
        '
        'GroupBox1
        '
        Me.GroupBox1.Controls.Add(Me.cmdEli)
        Me.GroupBox1.Controls.Add(Me.cmdAgr)
        Me.GroupBox1.Controls.Add(Me.lbclaves)
        Me.GroupBox1.Controls.Add(Me.Label1)
        Me.GroupBox1.Controls.Add(Me.Label6)
        Me.GroupBox1.Controls.Add(Me.cbNumeros)
        Me.GroupBox1.Controls.Add(Me.Label5)
        Me.GroupBox1.Controls.Add(Me.mensaje_participantes)
        Me.GroupBox1.Controls.Add(Me.Label3)
        Me.GroupBox1.Controls.Add(Me.nombre)
        Me.GroupBox1.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.GroupBox1.Location = New System.Drawing.Point(9, 64)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(447, 244)
        Me.GroupBox1.TabIndex = 10
        Me.GroupBox1.TabStop = False
        Me.GroupBox1.Text = "Datos Generales"
        '
        'cmdEli
        '
        Me.cmdEli.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdEli.Location = New System.Drawing.Point(376, 144)
        Me.cmdEli.Name = "cmdEli"
        Me.cmdEli.Size = New System.Drawing.Size(25, 24)
        Me.cmdEli.TabIndex = 28
        Me.cmdEli.Text = "-"
        '
        'cmdAgr
        '
        Me.cmdAgr.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdAgr.Location = New System.Drawing.Point(376, 112)
        Me.cmdAgr.Name = "cmdAgr"
        Me.cmdAgr.Size = New System.Drawing.Size(25, 24)
        Me.cmdAgr.TabIndex = 27
        Me.cmdAgr.Text = "+"
        '
        'lbclaves
        '
        Me.lbclaves.BackColor = System.Drawing.Color.White
        Me.lbclaves.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.lbclaves.Location = New System.Drawing.Point(160, 64)
        Me.lbclaves.Name = "lbclaves"
        Me.lbclaves.Size = New System.Drawing.Size(208, 106)
        Me.lbclaves.TabIndex = 26
        '
        'Label1
        '
        Me.Label1.Location = New System.Drawing.Point(8, 64)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(112, 21)
        Me.Label1.TabIndex = 25
        Me.Label1.Text = "Clave(s):"
        Me.Label1.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'Label6
        '
        Me.Label6.Location = New System.Drawing.Point(8, 16)
        Me.Label6.Name = "Label6"
        Me.Label6.Size = New System.Drawing.Size(112, 21)
        Me.Label6.TabIndex = 17
        Me.Label6.Text = "Número:"
        Me.Label6.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'cbNumeros
        '
        Me.cbNumeros.BackColor = System.Drawing.Color.White
        Me.cbNumeros.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cbNumeros.Location = New System.Drawing.Point(160, 16)
        Me.cbNumeros.Name = "cbNumeros"
        Me.cbNumeros.Size = New System.Drawing.Size(208, 21)
        Me.cbNumeros.TabIndex = 16
        '
        'Label5
        '
        Me.Label5.Location = New System.Drawing.Point(8, 175)
        Me.Label5.Name = "Label5"
        Me.Label5.Size = New System.Drawing.Size(152, 21)
        Me.Label5.TabIndex = 15
        Me.Label5.Text = "Mensaje a Participantes:"
        Me.Label5.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'mensaje_participantes
        '
        Me.mensaje_participantes.BackColor = System.Drawing.Color.White
        Me.mensaje_participantes.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.mensaje_participantes.Location = New System.Drawing.Point(160, 175)
        Me.mensaje_participantes.MaxLength = 254
        Me.mensaje_participantes.Name = "mensaje_participantes"
        Me.mensaje_participantes.Size = New System.Drawing.Size(208, 21)
        Me.mensaje_participantes.TabIndex = 14
        '
        'Label3
        '
        Me.Label3.Location = New System.Drawing.Point(8, 40)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(146, 21)
        Me.Label3.TabIndex = 7
        Me.Label3.Text = "Nombre del Telechat:"
        Me.Label3.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'nombre
        '
        Me.nombre.BackColor = System.Drawing.Color.White
        Me.nombre.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.nombre.Location = New System.Drawing.Point(160, 40)
        Me.nombre.Name = "nombre"
        Me.nombre.Size = New System.Drawing.Size(208, 21)
        Me.nombre.TabIndex = 6
        '
        'ErrorProvider
        '
        Me.ErrorProvider.ContainerControl = Me
        '
        'cmdCrear
        '
        Me.cmdCrear.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdCrear.Location = New System.Drawing.Point(708, 9)
        Me.cmdCrear.Name = "cmdCrear"
        Me.cmdCrear.Size = New System.Drawing.Size(100, 24)
        Me.cmdCrear.TabIndex = 12
        Me.cmdCrear.Text = "Crear"
        '
        'GroupBox2
        '
        Me.GroupBox2.Controls.Add(Me.Button1)
        Me.GroupBox2.Controls.Add(Me.Label9)
        Me.GroupBox2.Controls.Add(Me.logo)
        Me.GroupBox2.Controls.Add(Me.usuario)
        Me.GroupBox2.Controls.Add(Me.Label8)
        Me.GroupBox2.Controls.Add(Me.clave)
        Me.GroupBox2.Controls.Add(Me.Label7)
        Me.GroupBox2.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.GroupBox2.Location = New System.Drawing.Point(467, 212)
        Me.GroupBox2.Name = "GroupBox2"
        Me.GroupBox2.Size = New System.Drawing.Size(447, 96)
        Me.GroupBox2.TabIndex = 12
        Me.GroupBox2.TabStop = False
        Me.GroupBox2.Text = "Acceso Web"
        '
        'Button1
        '
        Me.Button1.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.Button1.Location = New System.Drawing.Point(376, 62)
        Me.Button1.Name = "Button1"
        Me.Button1.Size = New System.Drawing.Size(25, 24)
        Me.Button1.TabIndex = 31
        Me.Button1.Text = "..."
        '
        'Label9
        '
        Me.Label9.Location = New System.Drawing.Point(8, 64)
        Me.Label9.Name = "Label9"
        Me.Label9.Size = New System.Drawing.Size(112, 21)
        Me.Label9.TabIndex = 30
        Me.Label9.Text = "Logotipo:"
        Me.Label9.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'logo
        '
        Me.logo.BackColor = System.Drawing.Color.White
        Me.logo.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.logo.Location = New System.Drawing.Point(160, 64)
        Me.logo.Name = "logo"
        Me.logo.ReadOnly = True
        Me.logo.Size = New System.Drawing.Size(208, 21)
        Me.logo.TabIndex = 29
        '
        'usuario
        '
        Me.usuario.BackColor = System.Drawing.Color.White
        Me.usuario.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.usuario.Location = New System.Drawing.Point(160, 16)
        Me.usuario.Name = "usuario"
        Me.usuario.Size = New System.Drawing.Size(208, 21)
        Me.usuario.TabIndex = 22
        '
        'Label8
        '
        Me.Label8.Location = New System.Drawing.Point(8, 40)
        Me.Label8.Name = "Label8"
        Me.Label8.Size = New System.Drawing.Size(112, 21)
        Me.Label8.TabIndex = 24
        Me.Label8.Text = "Clave:"
        Me.Label8.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'clave
        '
        Me.clave.BackColor = System.Drawing.Color.White
        Me.clave.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.clave.Location = New System.Drawing.Point(160, 40)
        Me.clave.Name = "clave"
        Me.clave.Size = New System.Drawing.Size(208, 21)
        Me.clave.TabIndex = 23
        '
        'Label7
        '
        Me.Label7.Location = New System.Drawing.Point(8, 16)
        Me.Label7.Name = "Label7"
        Me.Label7.Size = New System.Drawing.Size(160, 21)
        Me.Label7.TabIndex = 25
        Me.Label7.Text = "Usuario:"
        Me.Label7.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'gbBloqueos
        '
        Me.gbBloqueos.Controls.Add(Me.cmdEliB)
        Me.gbBloqueos.Controls.Add(Me.cmdAgrB)
        Me.gbBloqueos.Controls.Add(Me.lbBloqueos)
        Me.gbBloqueos.Controls.Add(Me.Label17)
        Me.gbBloqueos.Location = New System.Drawing.Point(467, 70)
        Me.gbBloqueos.Name = "gbBloqueos"
        Me.gbBloqueos.Size = New System.Drawing.Size(447, 136)
        Me.gbBloqueos.TabIndex = 15
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
        Me.lbBloqueos.Location = New System.Drawing.Point(205, 20)
        Me.lbBloqueos.Name = "lbBloqueos"
        Me.lbBloqueos.Size = New System.Drawing.Size(208, 106)
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
        'agrTelechat
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(6, 14)
        Me.ClientSize = New System.Drawing.Size(919, 362)
        Me.Controls.Add(Me.gbBloqueos)
        Me.Controls.Add(Me.GroupBox1)
        Me.Controls.Add(Me.GroupBox2)
        Me.Name = "agrTelechat"
        Me.pTitle = "Crear Telechat"
        Me.Text = "Crear Telechat"
        Me.Controls.SetChildIndex(Me.GroupBox2, 0)
        Me.Controls.SetChildIndex(Me.pbUpper, 0)
        Me.Controls.SetChildIndex(Me.GroupBox1, 0)
        Me.Controls.SetChildIndex(Me.cmdPanel, 0)
        Me.Controls.SetChildIndex(Me.gbBloqueos, 0)
        CType(Me.pbUpper, System.ComponentModel.ISupportInitialize).EndInit()
        Me.cmdPanel.ResumeLayout(False)
        CType(Me.imgLinea, System.ComponentModel.ISupportInitialize).EndInit()
        Me.GroupBox1.ResumeLayout(False)
        Me.GroupBox1.PerformLayout()
        CType(Me.ErrorProvider, System.ComponentModel.ISupportInitialize).EndInit()
        Me.GroupBox2.ResumeLayout(False)
        Me.GroupBox2.PerformLayout()
        Me.gbBloqueos.ResumeLayout(False)
        Me.ResumeLayout(False)

    End Sub
    Friend WithEvents Label5 As System.Windows.Forms.Label
    Friend WithEvents gbBloqueos As System.Windows.Forms.GroupBox
    Friend WithEvents cmdEliB As System.Windows.Forms.Button
    Friend WithEvents cmdAgrB As System.Windows.Forms.Button
    Friend WithEvents lbBloqueos As System.Windows.Forms.ListBox
    Friend WithEvents Label17 As System.Windows.Forms.Label
#End Region
End Class
