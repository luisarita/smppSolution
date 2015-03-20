Imports MySql.Data.MySqlClient
Imports System.IO

Public Class agrTrivia
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
        pTitle = "Modificar Trivia"
        cmdCrear.Text = "Modificar"
        If CNX.State <> ConnectionState.Open Then CNX.Open()
        Dim dr As MySqlDataReader = New MySqlCommand("SELECT nombre, usuario, clave, numero, estado FROM TRIVIAS WHERE id=" & id, CNX).ExecuteReader
        If dr.Read Then
            nombre.Text = dr!nombre
            usuario.Text = dr!usuario
            clave.Text = dr!clave
            chkActiva.Checked = IIf(dr!estado = 1, True, False)
            seleccionar(cbNumeros, dr!numero)
            dr.Close()
            fncListboxMySQL.popularMySQL(CObj(lbclaves), "claves", "id", "clave", CNX, "idTrivia=" & vID)
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
    Friend WithEvents chkActiva As System.Windows.Forms.CheckBox
    Friend WithEvents Label19 As System.Windows.Forms.Label
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
        Me.chkActiva = New System.Windows.Forms.CheckBox()
        Me.Label19 = New System.Windows.Forms.Label()
        CType(Me.pbUpper, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.cmdPanel.SuspendLayout()
        CType(Me.imgLinea, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.GroupBox1.SuspendLayout()
        CType(Me.ErrorProvider, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.GroupBox2.SuspendLayout()
        Me.SuspendLayout()
        '
        'cmdPanel
        '
        Me.cmdPanel.Controls.Add(Me.cmdCrear)
        Me.cmdPanel.Location = New System.Drawing.Point(0, 376)
        Me.cmdPanel.Size = New System.Drawing.Size(426, 37)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCrear, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCerrar, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.imgLinea, 0)
        '
        'cmdCerrar
        '
        Me.cmdCerrar.Location = New System.Drawing.Point(321, 9)
        '
        'imgLinea
        '
        Me.imgLinea.Size = New System.Drawing.Size(416, 8)
        '
        'GroupBox1
        '
        Me.GroupBox1.Controls.Add(Me.chkActiva)
        Me.GroupBox1.Controls.Add(Me.Label19)
        Me.GroupBox1.Controls.Add(Me.cmdEli)
        Me.GroupBox1.Controls.Add(Me.cmdAgr)
        Me.GroupBox1.Controls.Add(Me.lbclaves)
        Me.GroupBox1.Controls.Add(Me.Label1)
        Me.GroupBox1.Controls.Add(Me.Label6)
        Me.GroupBox1.Controls.Add(Me.cbNumeros)
        Me.GroupBox1.Controls.Add(Me.Label3)
        Me.GroupBox1.Controls.Add(Me.nombre)
        Me.GroupBox1.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.GroupBox1.Location = New System.Drawing.Point(9, 64)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(407, 200)
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
        'Label3
        '
        Me.Label3.Location = New System.Drawing.Point(8, 40)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(120, 21)
        Me.Label3.TabIndex = 7
        Me.Label3.Text = "Nombre de la Trivia:"
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
        Me.cmdCrear.Location = New System.Drawing.Point(216, 9)
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
        Me.GroupBox2.Location = New System.Drawing.Point(9, 270)
        Me.GroupBox2.Name = "GroupBox2"
        Me.GroupBox2.Size = New System.Drawing.Size(407, 96)
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
        'chkActiva
        '
        Me.chkActiva.AutoSize = True
        Me.chkActiva.Location = New System.Drawing.Point(167, 176)
        Me.chkActiva.Name = "chkActiva"
        Me.chkActiva.Size = New System.Drawing.Size(14, 13)
        Me.chkActiva.TabIndex = 39
        Me.chkActiva.UseVisualStyleBackColor = True
        '
        'Label19
        '
        Me.Label19.Location = New System.Drawing.Point(8, 173)
        Me.Label19.Name = "Label19"
        Me.Label19.Size = New System.Drawing.Size(153, 21)
        Me.Label19.TabIndex = 38
        Me.Label19.Text = "Activa:"
        Me.Label19.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'agrTrivia
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(6, 14)
        Me.ClientSize = New System.Drawing.Size(426, 413)
        Me.Controls.Add(Me.GroupBox2)
        Me.Controls.Add(Me.GroupBox1)
        Me.Name = "agrTrivia"
        Me.pTitle = "Crear Trivia"
        Me.Text = "Crear Trivia"
        Me.Controls.SetChildIndex(Me.pbUpper, 0)
        Me.Controls.SetChildIndex(Me.GroupBox1, 0)
        Me.Controls.SetChildIndex(Me.cmdPanel, 0)
        Me.Controls.SetChildIndex(Me.GroupBox2, 0)
        CType(Me.pbUpper, System.ComponentModel.ISupportInitialize).EndInit()
        Me.cmdPanel.ResumeLayout(False)
        CType(Me.imgLinea, System.ComponentModel.ISupportInitialize).EndInit()
        Me.GroupBox1.ResumeLayout(False)
        Me.GroupBox1.PerformLayout()
        CType(Me.ErrorProvider, System.ComponentModel.ISupportInitialize).EndInit()
        Me.GroupBox2.ResumeLayout(False)
        Me.GroupBox2.PerformLayout()
        Me.ResumeLayout(False)

    End Sub
#End Region
    Private vID As Integer = -1
    Private eliminados() As Integer

    Private Sub cmdCrear_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdCrear.Click
        If validar() Then If agregar() Then Dispose()
    End Sub
    Private Sub cmdAgr_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdAgr.Click
        Dim nvaClave As String = InputBox("Ingrese una clave: ")
        If nvaClave.Length = 0 Then
            MsgBox("No se permiten claves nulas")
        Else
            lbclaves.Items.Add(New listitem(nvaClave, -1))
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
        ErrorProvider.SetError(nombre, "")
        ErrorProvider.SetError(usuario, "")
        ErrorProvider.SetError(clave, "")
        ErrorProvider.SetError(logo, "")

        validar = False
        If cbNumeros.SelectedIndex = -1 Then
            ErrorProvider.SetError(cbNumeros, "Debe escoger un numero")
        ElseIf nombre.Text = "" Then
            ErrorProvider.SetError(nombre, "Debe ingresar un nombre para identificar la trivia")
        ElseIf lbclaves.Items.Count = 0 Then
            ErrorProvider.SetError(lbclaves, "Debe haber al menos una clave")
        ElseIf usuario.Text = "" Then
            ErrorProvider.SetError(usuario, "Debe ingresar un usuario")
        ElseIf clave.Text = "" Then
            ErrorProvider.SetError(clave, "Debe ingresar una clave")
        ElseIf Not validarPass(clave.Text) Then
            ErrorProvider.SetError(clave, "La clave debe incluir una mayúscula, un numero y tener logitud de al menos 8 caracteres")
        ElseIf Not existeArchivo(logo.Text) Then
            'If logo.Text.Length = 0 Then
            '    validar = True
            'Else
            ErrorProvider.SetError(logo, "Debe escoger un archivo valido")
            'End If
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
            strCMD = "INSERT INTO trivias (numero, nombre, usuario, clave, estado, logo_tipo, logo_archivo) VALUES ('" & numero & "','" & nombre.Text & "','" & usuario.Text & "','" & clave.Text & "'," & IIf(chkActiva.Checked, 1, 0) & ",'" & tipo & "',@logo_archivo)"
        Else
            strCMD = "UPDATE trivias SET numero='" & numero & "', nombre='" & nombre.Text & "', usuario='" & usuario.Text & "', clave='" & clave.Text & "', estado=" & IIf(chkActiva.Checked, 1, 0)
            If useLogo Then strCMD &= ", logo_tipo='" & tipo & "', logo_archivo=@logo_archivo"
            strCMD &= " WHERE id = " & vID
        End If
        Dim strCMD2 As String = "INSERT INTO claves (clave,idTrivia) VALUES"
        Dim strCMD3 As String = "DELETE FROM claves WHERE id="
        Dim cmd As New MySqlCommand(strCMD, connectionOne)
        If connectionOne.State <> ConnectionState.Open Then connectionOne.Open()
        If useLogo Then cmd.Parameters.AddWithValue("@logo_archivo", archivo)
        Try
            cmd.ExecuteNonQuery()
            If vID = -1 Then
                Dim dr As Integer = New MySQLCommand("SELECT id FROM TRIVIAS WHERE nombre='" & nombre.Text & "'", CNX).ExecuteScalar
                'If dr.Read Then
                vID = dr
                'End If
                'dr.Close()
            End If
            Dim i As Integer
            For i = 0 To lbclaves.Items.Count - 1
                If valor(lbclaves, i, False) = -1 Then
                    Dim clave As String = getNombre(lbclaves, i, False)
                    cmd.CommandText = strCMD2 & "('" & clave & "'," & vID & ")"
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
        Catch ex As MySQLException
            MsgBox("Error al ejecutar comando: " & ex.Message)
        End Try
    End Function
End Class