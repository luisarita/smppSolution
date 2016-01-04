'larita 2008-01-20
' - Se agrego una clave administrativa para distintos accesos en la sección web

Imports MySql.Data.MySqlClient
Imports System.IO

Public Class agrEncuesta
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
        pTitle = "Modificar Encuesta"
        cmdCrear.Text = "Modificar"
        If CNX.State <> ConnectionState.Open Then CNX.Open()
        Dim dr As MySqlDataReader = New MySqlCommand("SELECT nombre, pregunta, usuario, clave, numero, estado, claveAdmin FROM encuestas WHERE id=" & id, CNX).ExecuteReader
        If dr.Read Then
            nombre.Text = dr!nombre
            usuario.Text = dr!usuario
            clave.Text = dr!clave
            claveAdmin.Text = dr!claveAdmin
            pregunta.Text = dr!pregunta
            estado.Checked = IIf(dr!estado = 1, True, False)
            seleccionar(cbNumeros, dr!numero)
            dr.Close()
            fncListboxMySQL.popularMySQL(CObj(lbclaves), "encuestas_opciones", "id", "CONCAT(descripcion,CONCAT('" & SEPARADOR & "',CONCAT(CONCAT(clave,'" & SEPARADOR & "'),respuesta))) AS descripcion", CNX, "idEncuesta=" & vID)
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
    Friend WithEvents cmdEli As System.Windows.Forms.Button
    Friend WithEvents cmdAgr As System.Windows.Forms.Button
    Friend WithEvents lbclaves As System.Windows.Forms.ListBox
    Friend WithEvents Label1 As System.Windows.Forms.Label
    Friend WithEvents Label6 As System.Windows.Forms.Label
    Friend WithEvents cbNumeros As System.Windows.Forms.ComboBox
    Friend WithEvents Label2 As System.Windows.Forms.Label
    Friend WithEvents Label3 As System.Windows.Forms.Label
    Friend WithEvents nombre As System.Windows.Forms.TextBox
    Friend WithEvents GroupBox2 As System.Windows.Forms.GroupBox
    Friend WithEvents usuario As System.Windows.Forms.TextBox
    Friend WithEvents Label8 As System.Windows.Forms.Label
    Friend WithEvents clave As System.Windows.Forms.TextBox
    Friend WithEvents Label7 As System.Windows.Forms.Label
    Friend WithEvents cmdCrear As System.Windows.Forms.Button
    Friend WithEvents Button1 As System.Windows.Forms.Button
    Friend WithEvents Label9 As System.Windows.Forms.Label
    Friend WithEvents logo As System.Windows.Forms.TextBox
    Friend WithEvents pregunta As System.Windows.Forms.TextBox
    Friend WithEvents Label10 As System.Windows.Forms.Label
    Friend WithEvents claveAdmin As System.Windows.Forms.TextBox
    Friend WithEvents cmdModificar As System.Windows.Forms.Button
    Friend WithEvents Label16 As System.Windows.Forms.Label
    Friend WithEvents estado As System.Windows.Forms.CheckBox
    Friend WithEvents gbBloqueos As System.Windows.Forms.GroupBox
    Friend WithEvents cmdEliB As System.Windows.Forms.Button
    Friend WithEvents cmdAgrB As System.Windows.Forms.Button
    Friend WithEvents lbBloqueos As System.Windows.Forms.ListBox
    Friend WithEvents Label17 As System.Windows.Forms.Label
    Friend WithEvents ErrorProvider As System.Windows.Forms.ErrorProvider
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.components = New System.ComponentModel.Container()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox()
        Me.Label16 = New System.Windows.Forms.Label()
        Me.estado = New System.Windows.Forms.CheckBox()
        Me.cmdModificar = New System.Windows.Forms.Button()
        Me.cmdEli = New System.Windows.Forms.Button()
        Me.cmdAgr = New System.Windows.Forms.Button()
        Me.Label6 = New System.Windows.Forms.Label()
        Me.cbNumeros = New System.Windows.Forms.ComboBox()
        Me.Label2 = New System.Windows.Forms.Label()
        Me.pregunta = New System.Windows.Forms.TextBox()
        Me.Label3 = New System.Windows.Forms.Label()
        Me.nombre = New System.Windows.Forms.TextBox()
        Me.lbclaves = New System.Windows.Forms.ListBox()
        Me.Label1 = New System.Windows.Forms.Label()
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
        Me.cmdCrear = New System.Windows.Forms.Button()
        Me.ErrorProvider = New System.Windows.Forms.ErrorProvider(Me.components)
        Me.gbBloqueos = New System.Windows.Forms.GroupBox()
        Me.cmdEliB = New System.Windows.Forms.Button()
        Me.cmdAgrB = New System.Windows.Forms.Button()
        Me.lbBloqueos = New System.Windows.Forms.ListBox()
        Me.Label17 = New System.Windows.Forms.Label()
        CType(Me.pbUpper, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.cmdPanel.SuspendLayout()
        CType(Me.imgLinea, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.GroupBox1.SuspendLayout()
        Me.GroupBox2.SuspendLayout()
        CType(Me.ErrorProvider, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.gbBloqueos.SuspendLayout()
        Me.SuspendLayout()
        '
        'cmdPanel
        '
        Me.cmdPanel.Controls.Add(Me.cmdCrear)
        Me.cmdPanel.Location = New System.Drawing.Point(0, 338)
        Me.cmdPanel.Size = New System.Drawing.Size(919, 37)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCerrar, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.imgLinea, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCrear, 0)
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
        Me.GroupBox1.Controls.Add(Me.Label16)
        Me.GroupBox1.Controls.Add(Me.estado)
        Me.GroupBox1.Controls.Add(Me.cmdModificar)
        Me.GroupBox1.Controls.Add(Me.cmdEli)
        Me.GroupBox1.Controls.Add(Me.cmdAgr)
        Me.GroupBox1.Controls.Add(Me.Label6)
        Me.GroupBox1.Controls.Add(Me.cbNumeros)
        Me.GroupBox1.Controls.Add(Me.Label2)
        Me.GroupBox1.Controls.Add(Me.pregunta)
        Me.GroupBox1.Controls.Add(Me.Label3)
        Me.GroupBox1.Controls.Add(Me.nombre)
        Me.GroupBox1.Controls.Add(Me.lbclaves)
        Me.GroupBox1.Controls.Add(Me.Label1)
        Me.GroupBox1.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.GroupBox1.Location = New System.Drawing.Point(5, 59)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(447, 268)
        Me.GroupBox1.TabIndex = 11
        Me.GroupBox1.TabStop = False
        Me.GroupBox1.Text = "Datos Generales"
        '
        'Label16
        '
        Me.Label16.Location = New System.Drawing.Point(8, 207)
        Me.Label16.Name = "Label16"
        Me.Label16.Size = New System.Drawing.Size(146, 21)
        Me.Label16.TabIndex = 43
        Me.Label16.Text = "Activa:"
        Me.Label16.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'estado
        '
        Me.estado.AutoSize = True
        Me.estado.Location = New System.Drawing.Point(159, 211)
        Me.estado.Name = "estado"
        Me.estado.Size = New System.Drawing.Size(14, 13)
        Me.estado.TabIndex = 42
        Me.estado.UseVisualStyleBackColor = True
        '
        'cmdModificar
        '
        Me.cmdModificar.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdModificar.Location = New System.Drawing.Point(376, 88)
        Me.cmdModificar.Name = "cmdModificar"
        Me.cmdModificar.Size = New System.Drawing.Size(25, 24)
        Me.cmdModificar.TabIndex = 29
        Me.cmdModificar.Text = "*"
        '
        'cmdEli
        '
        Me.cmdEli.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdEli.Location = New System.Drawing.Point(376, 168)
        Me.cmdEli.Name = "cmdEli"
        Me.cmdEli.Size = New System.Drawing.Size(25, 24)
        Me.cmdEli.TabIndex = 28
        Me.cmdEli.Text = "-"
        '
        'cmdAgr
        '
        Me.cmdAgr.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdAgr.Location = New System.Drawing.Point(376, 138)
        Me.cmdAgr.Name = "cmdAgr"
        Me.cmdAgr.Size = New System.Drawing.Size(25, 24)
        Me.cmdAgr.TabIndex = 27
        Me.cmdAgr.Text = "+"
        '
        'Label6
        '
        Me.Label6.Location = New System.Drawing.Point(8, 16)
        Me.Label6.Name = "Label6"
        Me.Label6.Size = New System.Drawing.Size(152, 21)
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
        'Label2
        '
        Me.Label2.Location = New System.Drawing.Point(8, 64)
        Me.Label2.Name = "Label2"
        Me.Label2.Size = New System.Drawing.Size(152, 21)
        Me.Label2.TabIndex = 11
        Me.Label2.Text = "Pregunta:"
        Me.Label2.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'pregunta
        '
        Me.pregunta.BackColor = System.Drawing.Color.White
        Me.pregunta.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.pregunta.Location = New System.Drawing.Point(160, 64)
        Me.pregunta.Name = "pregunta"
        Me.pregunta.Size = New System.Drawing.Size(208, 21)
        Me.pregunta.TabIndex = 10
        '
        'Label3
        '
        Me.Label3.Location = New System.Drawing.Point(8, 40)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(152, 21)
        Me.Label3.TabIndex = 7
        Me.Label3.Text = "Nombre de la Encuesta:"
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
        'lbclaves
        '
        Me.lbclaves.BackColor = System.Drawing.Color.White
        Me.lbclaves.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.lbclaves.Location = New System.Drawing.Point(160, 88)
        Me.lbclaves.Name = "lbclaves"
        Me.lbclaves.Size = New System.Drawing.Size(208, 106)
        Me.lbclaves.TabIndex = 26
        '
        'Label1
        '
        Me.Label1.Location = New System.Drawing.Point(8, 88)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(112, 21)
        Me.Label1.TabIndex = 25
        Me.Label1.Text = "Opcion(es):"
        Me.Label1.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
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
        Me.GroupBox2.Location = New System.Drawing.Point(467, 205)
        Me.GroupBox2.Name = "GroupBox2"
        Me.GroupBox2.Size = New System.Drawing.Size(447, 122)
        Me.GroupBox2.TabIndex = 13
        Me.GroupBox2.TabStop = False
        Me.GroupBox2.Text = "Acceso Web"
        '
        'Label10
        '
        Me.Label10.Location = New System.Drawing.Point(8, 63)
        Me.Label10.Name = "Label10"
        Me.Label10.Size = New System.Drawing.Size(146, 21)
        Me.Label10.TabIndex = 36
        Me.Label10.Text = "Clave Administrativa:"
        Me.Label10.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'claveAdmin
        '
        Me.claveAdmin.BackColor = System.Drawing.Color.White
        Me.claveAdmin.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.claveAdmin.Location = New System.Drawing.Point(181, 64)
        Me.claveAdmin.Name = "claveAdmin"
        Me.claveAdmin.Size = New System.Drawing.Size(232, 21)
        Me.claveAdmin.TabIndex = 35
        '
        'Button1
        '
        Me.Button1.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.Button1.Location = New System.Drawing.Point(419, 88)
        Me.Button1.Name = "Button1"
        Me.Button1.Size = New System.Drawing.Size(25, 24)
        Me.Button1.TabIndex = 34
        Me.Button1.Text = "..."
        '
        'Label9
        '
        Me.Label9.Location = New System.Drawing.Point(7, 88)
        Me.Label9.Name = "Label9"
        Me.Label9.Size = New System.Drawing.Size(112, 21)
        Me.Label9.TabIndex = 33
        Me.Label9.Text = "Logotipo:"
        Me.Label9.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'logo
        '
        Me.logo.BackColor = System.Drawing.Color.White
        Me.logo.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.logo.Location = New System.Drawing.Point(180, 88)
        Me.logo.Name = "logo"
        Me.logo.ReadOnly = True
        Me.logo.Size = New System.Drawing.Size(232, 21)
        Me.logo.TabIndex = 32
        '
        'usuario
        '
        Me.usuario.BackColor = System.Drawing.Color.White
        Me.usuario.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.usuario.Location = New System.Drawing.Point(181, 16)
        Me.usuario.Name = "usuario"
        Me.usuario.Size = New System.Drawing.Size(232, 21)
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
        Me.clave.Location = New System.Drawing.Point(181, 40)
        Me.clave.Name = "clave"
        Me.clave.Size = New System.Drawing.Size(232, 21)
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
        'cmdCrear
        '
        Me.cmdCrear.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdCrear.Location = New System.Drawing.Point(708, 9)
        Me.cmdCrear.Name = "cmdCrear"
        Me.cmdCrear.Size = New System.Drawing.Size(100, 24)
        Me.cmdCrear.TabIndex = 13
        Me.cmdCrear.Text = "&Crear"
        '
        'ErrorProvider
        '
        Me.ErrorProvider.ContainerControl = Me
        '
        'gbBloqueos
        '
        Me.gbBloqueos.Controls.Add(Me.cmdEliB)
        Me.gbBloqueos.Controls.Add(Me.cmdAgrB)
        Me.gbBloqueos.Controls.Add(Me.lbBloqueos)
        Me.gbBloqueos.Controls.Add(Me.Label17)
        Me.gbBloqueos.Location = New System.Drawing.Point(467, 63)
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
        'agrEncuesta
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(6, 14)
        Me.ClientSize = New System.Drawing.Size(919, 375)
        Me.Controls.Add(Me.gbBloqueos)
        Me.Controls.Add(Me.GroupBox1)
        Me.Controls.Add(Me.GroupBox2)
        Me.Name = "agrEncuesta"
        Me.pTitle = "Crear Encuesta"
        Me.Text = "Crear Encuesta"
        Me.Controls.SetChildIndex(Me.pbUpper, 0)
        Me.Controls.SetChildIndex(Me.GroupBox2, 0)
        Me.Controls.SetChildIndex(Me.GroupBox1, 0)
        Me.Controls.SetChildIndex(Me.cmdPanel, 0)
        Me.Controls.SetChildIndex(Me.gbBloqueos, 0)
        CType(Me.pbUpper, System.ComponentModel.ISupportInitialize).EndInit()
        Me.cmdPanel.ResumeLayout(False)
        CType(Me.imgLinea, System.ComponentModel.ISupportInitialize).EndInit()
        Me.GroupBox1.ResumeLayout(False)
        Me.GroupBox1.PerformLayout()
        Me.GroupBox2.ResumeLayout(False)
        Me.GroupBox2.PerformLayout()
        CType(Me.ErrorProvider, System.ComponentModel.ISupportInitialize).EndInit()
        Me.gbBloqueos.ResumeLayout(False)
        Me.ResumeLayout(False)

    End Sub
#End Region
    Private vID As Integer = -1
    Private eliminados() As Integer
    Private eliminados2() As Integer
    Private Const SEPARADOR As String = ":"

    Private Sub cmdCrear_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdCrear.Click
        If validar() Then If agregar() Then Dispose()
    End Sub
    Private Sub cmdAgr_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdAgr.Click
        Dim nvaDescripcion As String = InputBox("Ingrese una descripción: ")
        Dim nvaClave As String = InputBox("Ingrese una clave: ")
        Dim nvaRespuesta As String = InputBox("Ingrese un mensaje de respuesta: ")
        If nvaClave.Length = 0 Or nvaDescripcion.Length = 0 Or nvaRespuesta.Length = 0 Then
            MsgBox("No se permiten valores nulos")
        Else
            lbclaves.Items.Add(New listitem(nvaClave & SEPARADOR & nvaDescripcion & SEPARADOR & nvaRespuesta, -1))
        End If
    End Sub
    Private Sub cmdEli_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdEli.Click
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
    Private Sub Button1_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles Button1.Click
        Dim o As New OpenFileDialog
        o.Filter = "Archivos de Imagen|*.jpg"
        o.ShowDialog()
        logo.Text = o.FileName
    End Sub
    Function validar() As Boolean
        ErrorProvider.SetError(cbNumeros, "")
        ErrorProvider.SetError(pregunta, "")
        ErrorProvider.SetError(lbclaves, "")
        ErrorProvider.SetError(nombre, "")
        ErrorProvider.SetError(usuario, "")
        ErrorProvider.SetError(clave, "")
        ErrorProvider.SetError(claveAdmin, "")
        ErrorProvider.SetError(logo, "")

        validar = False
        If cbNumeros.SelectedIndex = -1 Then
            ErrorProvider.SetError(cbNumeros, "Debe escoger un numero")
        ElseIf nombre.Text = "" Then
            ErrorProvider.SetError(nombre, "Debe ingresar un nombre para identificar la encuesta")
        ElseIf lbclaves.Items.Count = 0 Then
            ErrorProvider.SetError(lbclaves, "Debe haber al menos una clave")
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
            strCMD = "INSERT INTO encuestas (numero,pregunta,nombre,estado,usuario,clave,claveadmin,logo_tipo,logo_archivo) VALUES ('" & numero & "','" & pregunta.Text & "','" & nombre.Text & "'," & IIf(estado.Checked, 1, 0) & ",'" & usuario.Text & "','" & clave.Text & "','" & claveAdmin.Text & "','" & tipo & "',@logo_archivo)"
        Else
            strCMD = "UPDATE encuestas SET numero='" & numero & "', pregunta='" & pregunta.Text & "', nombre='" & nombre.Text & "', estado=" & IIf(estado.Checked, 1, 0) & ", usuario='" & usuario.Text & "', clave='" & clave.Text & "', claveAdmin='" & claveAdmin.Text & "'"
            If useLogo Then strCMD &= ",logo_tipo='" & tipo & "',logo_archivo=@logo_archivo"
            strCMD &= " WHERE id = " & vID
        End If
        Dim strCMD2 As String = "INSERT INTO encuestas_opciones (clave,descripcion,respuesta,idEncuesta) VALUES"
        Dim strCMD3 As String = "DELETE FROM encuestas_opciones WHERE id="
        Dim strCMD4 As String = "UPDATE encuestas_opciones SET "
        Dim cmd As New MySqlCommand(strCMD, connectionOne)
        If connectionOne.State <> ConnectionState.Open Then connectionOne.Open()
        If useLogo Then cmd.Parameters.AddWithValue("@logo_archivo", archivo)
        Try
            cmd.ExecuteNonQuery()
            If vID = -1 Then
                Dim dr As Integer = New MySqlCommand("SELECT id FROM encuestas WHERE nombre='" & nombre.Text & "'", CNX).ExecuteScalar
                vID = dr
            End If
            Dim i As Integer
            For i = 0 To lbclaves.Items.Count - 1
                Dim clave As String = getNombre(lbclaves, i, False)
                Dim claves As String() = clave.Split(SEPARADOR)

                If valor(lbclaves, i, False) = -1 Then
                    cmd.CommandText = strCMD2 & "('" & claves(0) & "','" & claves(1) & "','" & claves(2) & "'," & vID & ")"
                    cmd.ExecuteNonQuery()
                Else
                    cmd.CommandText = strCMD4 & "clave='" & claves(0) & "',descripcion='" & claves(1) & "',respuesta='" & claves(2) & "' WHERE id=" & valor(lbclaves, i, False)
                    cmd.ExecuteNonQuery()
                End If
            Next
            If Not eliminados Is Nothing Then
                For i = 0 To eliminados.Length - 1
                    cmd.CommandText = strCMD3 & eliminados(i)
                    cmd.ExecuteNonQuery()
                Next
            End If
            agregar = True
        Catch ex As MySqlException
            MsgBox("Error al ejecutar comando: " & ex.Message)
        End Try
    End Function

    Private Sub cmdModificar_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdModificar.Click
        If lbclaves.SelectedIndex = -1 Then
            MsgBox("Debe seleccionar una clave")
            Exit Sub
        End If
        If valor(lbclaves, lbclaves.SelectedIndex, False) <> -1 Then
            Dim miItem As listitem = lbclaves.Items.Item(lbclaves.SelectedIndex)
            Dim partes() As String = miItem.Name.Split(SEPARADOR)
            Dim nvaDescripcion As String = InputBox("Ingrese una descripción: ", "", partes(0))
            Dim nvaClave As String = InputBox("Ingrese una clave: ", "", partes(1))
            Dim nvaRespuesta As String = InputBox("Ingrese un mensaje de respuesta: ", "", partes(2))
            miItem.Name = nvaClave & SEPARADOR & nvaDescripcion & SEPARADOR & nvaRespuesta
            lbclaves.Items.Item(lbclaves.SelectedIndex) = miItem
        End If
    End Sub

    Private Sub cmdAgrB_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdAgrB.Click
        Dim nvaClave As String = InputBox("Ingrese una clave: ")
        If nvaClave.Length = 0 Then
            MsgBox("No se permiten claves nulas")
        Else
            lbBloqueos.Items.Add(New listitem(nvaClave, -1))
        End If
    End Sub

    Private Sub cmdEliB_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdEliB.Click
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
End Class