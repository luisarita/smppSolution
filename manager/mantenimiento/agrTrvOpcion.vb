Imports MySql.Data.MySqlClient

Public Class agrTrvOpcion
    Inherits formControl.frmTemplate

#Region " Windows Form Designer generated code "
    Public Sub New()
        MyBase.New()
        InitializeComponent()
    End Sub
    Sub New(ByVal id As Integer)
        MyBase.New()
        InitializeComponent()
        vID = id
        pTitle = "Modificar Opci�n"
        cmdCrear.Text = "Modificar"
        If CNX.State <> ConnectionState.Open Then CNX.Open()
        Dim dr As MySQLDataReader = New MySQLCommand("SELECT * FROM TRIVIAS_OPCIONES WHERE id=" & id, CNX).ExecuteReader
        If dr.Read Then
            contenido.Text = dr!mensaje
            valor.Text = dr!valor
            opcion.Text = dr!opcion
        Else
            dr.Close()
            MsgBox("Registro no encontrado")
            Dispose()
        End If
        dr.Close()
    End Sub
    Sub New(ByVal idS As Integer, ByVal dummy As Boolean)
        MyBase.New()
        InitializeComponent()
        vIdS = idS
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
    Friend WithEvents Label1 As System.Windows.Forms.Label
    Friend WithEvents ErrorProvider As System.Windows.Forms.ErrorProvider
    Friend WithEvents cmdCrear As System.Windows.Forms.Button
    Friend WithEvents contenido As System.Windows.Forms.TextBox
    Friend WithEvents Label2 As System.Windows.Forms.Label
    Friend WithEvents opcion As System.Windows.Forms.TextBox
    Friend WithEvents valor As System.Windows.Forms.TextBox
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox
        Me.Label3 = New System.Windows.Forms.Label
        Me.contenido = New System.Windows.Forms.TextBox
        Me.Label1 = New System.Windows.Forms.Label
        Me.ErrorProvider = New System.Windows.Forms.ErrorProvider
        Me.cmdCrear = New System.Windows.Forms.Button
        Me.opcion = New System.Windows.Forms.TextBox
        Me.valor = New System.Windows.Forms.TextBox
        Me.Label2 = New System.Windows.Forms.Label
        Me.cmdPanel.SuspendLayout()
        Me.GroupBox1.SuspendLayout()
        Me.SuspendLayout()
        '
        'pbUpper
        '
        Me.pbUpper.Name = "pbUpper"
        '
        'cmdPanel
        '
        Me.cmdPanel.Controls.Add(Me.cmdCrear)
        Me.cmdPanel.DockPadding.Left = 5
        Me.cmdPanel.DockPadding.Right = 5
        Me.cmdPanel.Location = New System.Drawing.Point(0, 207)
        Me.cmdPanel.Name = "cmdPanel"
        Me.cmdPanel.Size = New System.Drawing.Size(418, 37)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCrear, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCerrar, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.imgLinea, 0)
        '
        'cmdCerrar
        '
        Me.cmdCerrar.Location = New System.Drawing.Point(313, 9)
        Me.cmdCerrar.Name = "cmdCerrar"
        '
        'imgLinea
        '
        Me.imgLinea.Name = "imgLinea"
        Me.imgLinea.Size = New System.Drawing.Size(408, 8)
        '
        'GroupBox1
        '
        Me.GroupBox1.Controls.Add(Me.valor)
        Me.GroupBox1.Controls.Add(Me.Label2)
        Me.GroupBox1.Controls.Add(Me.opcion)
        Me.GroupBox1.Controls.Add(Me.Label3)
        Me.GroupBox1.Controls.Add(Me.contenido)
        Me.GroupBox1.Controls.Add(Me.Label1)
        Me.GroupBox1.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.GroupBox1.Location = New System.Drawing.Point(6, 56)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(407, 144)
        Me.GroupBox1.TabIndex = 10
        Me.GroupBox1.TabStop = False
        '
        'Label3
        '
        Me.Label3.Location = New System.Drawing.Point(8, 40)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(72, 21)
        Me.Label3.TabIndex = 7
        Me.Label3.Text = "Contenido:"
        Me.Label3.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'contenido
        '
        Me.contenido.BackColor = System.Drawing.Color.White
        Me.contenido.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.contenido.Location = New System.Drawing.Point(80, 40)
        Me.contenido.MaxLength = 254
        Me.contenido.Multiline = True
        Me.contenido.Name = "contenido"
        Me.contenido.Size = New System.Drawing.Size(296, 72)
        Me.contenido.TabIndex = 6
        Me.contenido.Text = ""
        '
        'Label1
        '
        Me.Label1.Location = New System.Drawing.Point(8, 16)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(72, 21)
        Me.Label1.TabIndex = 9
        Me.Label1.Text = "Opci�n:"
        Me.Label1.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'ErrorProvider
        '
        Me.ErrorProvider.ContainerControl = Me
        '
        'cmdCrear
        '
        Me.cmdCrear.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdCrear.Location = New System.Drawing.Point(208, 9)
        Me.cmdCrear.Name = "cmdCrear"
        Me.cmdCrear.Size = New System.Drawing.Size(100, 24)
        Me.cmdCrear.TabIndex = 12
        Me.cmdCrear.Text = "Crear"
        '
        'opcion
        '
        Me.opcion.BackColor = System.Drawing.Color.White
        Me.opcion.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.opcion.Location = New System.Drawing.Point(80, 16)
        Me.opcion.MaxLength = 254
        Me.opcion.Name = "opcion"
        Me.opcion.Size = New System.Drawing.Size(296, 21)
        Me.opcion.TabIndex = 10
        Me.opcion.Text = ""
        '
        'valor
        '
        Me.valor.BackColor = System.Drawing.Color.White
        Me.valor.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.valor.Location = New System.Drawing.Point(80, 116)
        Me.valor.MaxLength = 254
        Me.valor.Name = "valor"
        Me.valor.Size = New System.Drawing.Size(296, 21)
        Me.valor.TabIndex = 12
        Me.valor.Text = ""
        '
        'Label2
        '
        Me.Label2.Location = New System.Drawing.Point(8, 116)
        Me.Label2.Name = "Label2"
        Me.Label2.Size = New System.Drawing.Size(72, 21)
        Me.Label2.TabIndex = 11
        Me.Label2.Text = "Valor:"
        Me.Label2.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'agrTrvOpcion
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(6, 14)
        Me.ClientSize = New System.Drawing.Size(418, 244)
        Me.Controls.Add(Me.GroupBox1)
        Me.Name = "agrTrvOpcion"
        Me.pTitle = "Agregar Opci�n"
        Me.Text = "Agregar Opci�n"
        Me.Controls.SetChildIndex(Me.GroupBox1, 0)
        Me.Controls.SetChildIndex(Me.cmdPanel, 0)
        Me.Controls.SetChildIndex(Me.pbUpper, 0)
        Me.cmdPanel.ResumeLayout(False)
        Me.GroupBox1.ResumeLayout(False)
        Me.ResumeLayout(False)

    End Sub
#End Region
    Private vId As Integer = -1
    Private vIdS As Integer = -1
    Private Sub cmdCrear_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdCrear.Click
        If validar() Then If agregar() Then Dispose()
    End Sub
    Function validar() As Boolean
        ErrorProvider.SetError(contenido, "")
        ErrorProvider.SetError(valor, "")
        ErrorProvider.SetError(opcion, "")

        validar = False
        If contenido.Text = "" Then
            ErrorProvider.SetError(contenido, "Debe ingresar el contenido de la opcion")
        ElseIf valor.Text = "" Then
            ErrorProvider.SetError(valor, "Debe ingresar el valor para la opcion")
        ElseIf opcion.Text = "" Then
            ErrorProvider.SetError(opcion, "Debe ingresar la opcion del mensaje")
        Else
            validar = True
        End If
    End Function
    Function agregar() As Boolean
        agregar = False
        If CNX.State <> ConnectionState.Open Then CNX.Open()
        Dim strCMD As String = IIf(vId = -1, "INSERT INTO TRIVIAS_OPCIONES (valor,opcion,mensaje,idPregunta) VALUES ('" & valor.Text & "','" & opcion.Text & "','" & contenido.Text & "'," & vIdS & ")", "UPDATE TRIVIAS_OPCIONES SET valor=" & valor.Text & ",opcion='" & opcion.Text & "',mensaje='" & contenido.Text & "' WHERE id=" & vId)
        Dim cmd As New MySQLCommand(strCMD, CNX)
        Try
            cmd.ExecuteNonQuery()
            agregar = True
        Catch ex As MySQLException
            MsgBox("Error al ejecutar comando: " & ex.Message)
        End Try
    End Function
    Private Sub agrTrvOpcion_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        campoNumerico(valor)
    End Sub
End Class
