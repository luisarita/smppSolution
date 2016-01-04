Imports MySql.Data.MySqlClient

Public Class agrTrvResultado
    Inherits formControl.frmTemplate

#Region " Windows Form Designer generated code "
    Public Sub New()
        MyBase.New()
        InitializeComponent()
    End Sub
    Sub New(ByVal id As Integer)
        MyBase.New()
        InitializeComponent()
        vId = id
        pTitle = "Modificar Resultado"
        cmdCrear.Text = "Modificar"
        If CNX.State <> ConnectionState.Open Then CNX.Open()
        Dim dr As MySqlDataReader = New MySqlCommand("SELECT * FROM TRIVIAS_RESULTADOS WHERE id=" & id, CNX).ExecuteReader
        If dr.Read Then
            contenido.Text = dr!mensaje
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
    Friend WithEvents ErrorProvider As System.Windows.Forms.ErrorProvider
    Friend WithEvents cmdCrear As System.Windows.Forms.Button
    Friend WithEvents contenido As System.Windows.Forms.TextBox
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox
        Me.Label3 = New System.Windows.Forms.Label
        Me.contenido = New System.Windows.Forms.TextBox
        Me.ErrorProvider = New System.Windows.Forms.ErrorProvider
        Me.cmdCrear = New System.Windows.Forms.Button
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
        Me.cmdPanel.Location = New System.Drawing.Point(0, 183)
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
        Me.GroupBox1.Controls.Add(Me.Label3)
        Me.GroupBox1.Controls.Add(Me.contenido)
        Me.GroupBox1.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.GroupBox1.Location = New System.Drawing.Point(6, 56)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(407, 120)
        Me.GroupBox1.TabIndex = 10
        Me.GroupBox1.TabStop = False
        '
        'Label3
        '
        Me.Label3.Location = New System.Drawing.Point(8, 16)
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
        Me.contenido.Location = New System.Drawing.Point(80, 16)
        Me.contenido.MaxLength = 254
        Me.contenido.Multiline = True
        Me.contenido.Name = "contenido"
        Me.contenido.Size = New System.Drawing.Size(296, 96)
        Me.contenido.TabIndex = 6
        Me.contenido.Text = ""
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
        'agrTrvResultado
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(6, 14)
        Me.ClientSize = New System.Drawing.Size(418, 220)
        Me.Controls.Add(Me.GroupBox1)
        Me.Name = "agrTrvResultado"
        Me.pTitle = "Agregar Resultado"
        Me.Text = "Agregar Resultado"
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

        validar = False
        If contenido.Text = "" Then
            ErrorProvider.SetError(contenido, "Debe ingresar el contenido del mensaje")
        Else
            validar = True
        End If
    End Function
    Function agregar() As Boolean
        agregar = False

        If CNX.State <> ConnectionState.Open Then CNX.Open()
        Dim strCMD As String
        If vId = -1 Then
            Dim cantidad As Integer = 0
            Dim puntos As Integer = 0
            Try
                cantidad = New MySqlCommand("SELECT COUNT(id) AS id FROM TRIVIAS_RESULTADOS WHERE idTrivia=" & vIdS, CNX).ExecuteScalar
            Catch ex As MySqlException
            End Try
            Try
                puntos = New MySqlCommand("SELECT SUM(valor) AS puntos FROM (SELECT MAX(valor) AS valor FROM TRIVIAS_OPCIONES INNER JOIN TRIVIAS_PREGUNTAS ON TRIVIAS_PREGUNTAS.id = TRIVIAS_OPCIONES.idPregunta WHERE idTrivia=" & vIdS & " GROUP BY idPregunta) AS DRVTBL", CNX).ExecuteScalar
            Catch es As MySqlException
                MsgBox("No existen opciones para esta trivia. No se puede calcular puntajes")
                Return False
            End Try
            Dim minimo As Integer = 0
            Dim maximo As Integer = 0
            cantidad += 1
            Dim dr As MySqlDataReader = New MySqlCommand("SELECT id FROM TRIVIAS_RESULTADOS WHERE idTrivia=" & vIdS, CNX).ExecuteReader
            Dim CNX2 As New MySqlConnection(cnxString)
            CNX2.Open()
            While dr.Read
                maximo = maximo + puntos / cantidad
                Dim CMD1 As New MySqlCommand("UPDATE TRIVIAS_RESULTADOS SET minimo=" & minimo & ", maximo=" & maximo & " WHERE id=" & dr!id, CNX2)
                Try
                    CMD1.ExecuteNonQuery()
                    minimo = maximo + 1
                Catch ex As MySqlException
                    MsgBox("Error al ejecutar comando: " & ex.Message)
                End Try
            End While
            maximo = puntos
            dr.Close()
            CNX2.Close()
            strCMD = "INSERT INTO TRIVIAS_RESULTADOS (mensaje,minimo,maximo,idTrivia) VALUES ('" & contenido.Text & "'," & minimo & "," & maximo & "," & vIdS & ")"
        Else
            strCMD = "UPDATE TRIVIAS_RESULTADOS SET mensaje='" & contenido.Text & "' WHERE id=" & vId
        End If
        Dim CMD2 As New MySqlCommand(strCMD, CNX)
        Try
            CMD2.ExecuteNonQuery()
            agregar = True
        Catch ex As MySqlException
            MsgBox("Error al ejecutar comando: " & ex.Message)
        End Try
    End Function

End Class