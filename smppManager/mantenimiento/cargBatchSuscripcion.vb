Imports MySql.Data.MySqlClient
Imports funciones
Imports System.IO

Public Class cargBatchSuscripcion
    Inherits formControl.frmTemplate

#Region " Windows Form Designer generated code "
    Public Sub New()
        MyBase.New()
        InitializeComponent()
        fncListboxMySQL.popularMySQL(CObj(cbSuscripciones), "suscripciones", "id", "nombre", CNX)
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
    Friend WithEvents cmdCargar As System.Windows.Forms.Button
    Friend WithEvents numeros As System.Windows.Forms.TextBox
    Friend WithEvents Label2 As System.Windows.Forms.Label
    Friend WithEvents GroupBox2 As System.Windows.Forms.GroupBox
    Friend WithEvents Label1 As System.Windows.Forms.Label
    Friend WithEvents cbSuscripciones As System.Windows.Forms.ComboBox
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.components = New System.ComponentModel.Container
        Me.GroupBox1 = New System.Windows.Forms.GroupBox
        Me.cbSuscripciones = New System.Windows.Forms.ComboBox
        Me.Label2 = New System.Windows.Forms.Label
        Me.Label3 = New System.Windows.Forms.Label
        Me.numeros = New System.Windows.Forms.TextBox
        Me.ErrorProvider = New System.Windows.Forms.ErrorProvider(Me.components)
        Me.cmdCargar = New System.Windows.Forms.Button
        Me.GroupBox2 = New System.Windows.Forms.GroupBox
        Me.Label1 = New System.Windows.Forms.Label
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
        Me.cmdPanel.Controls.Add(Me.cmdCargar)
        Me.cmdPanel.Location = New System.Drawing.Point(0, 414)
        Me.cmdPanel.Size = New System.Drawing.Size(434, 37)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCargar, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCerrar, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.imgLinea, 0)
        '
        'cmdCerrar
        '
        Me.cmdCerrar.Location = New System.Drawing.Point(329, 9)
        '
        'imgLinea
        '
        Me.imgLinea.Size = New System.Drawing.Size(424, 8)
        '
        'GroupBox1
        '
        Me.GroupBox1.Controls.Add(Me.cbSuscripciones)
        Me.GroupBox1.Controls.Add(Me.Label2)
        Me.GroupBox1.Controls.Add(Me.Label3)
        Me.GroupBox1.Controls.Add(Me.numeros)
        Me.GroupBox1.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.GroupBox1.Location = New System.Drawing.Point(10, 64)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(415, 256)
        Me.GroupBox1.TabIndex = 10
        Me.GroupBox1.TabStop = False
        Me.GroupBox1.Text = "Datos"
        '
        'cbSuscripciones
        '
        Me.cbSuscripciones.BackColor = System.Drawing.Color.White
        Me.cbSuscripciones.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cbSuscripciones.Location = New System.Drawing.Point(89, 16)
        Me.cbSuscripciones.Name = "cbSuscripciones"
        Me.cbSuscripciones.Size = New System.Drawing.Size(287, 21)
        Me.cbSuscripciones.TabIndex = 1
        '
        'Label2
        '
        Me.Label2.Location = New System.Drawing.Point(8, 16)
        Me.Label2.Name = "Label2"
        Me.Label2.Size = New System.Drawing.Size(160, 21)
        Me.Label2.TabIndex = 10
        Me.Label2.Text = "Suscripción:"
        Me.Label2.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'Label3
        '
        Me.Label3.Location = New System.Drawing.Point(8, 40)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(75, 21)
        Me.Label3.TabIndex = 7
        Me.Label3.Text = "Número:"
        Me.Label3.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'numeros
        '
        Me.numeros.BackColor = System.Drawing.Color.White
        Me.numeros.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.numeros.Location = New System.Drawing.Point(89, 40)
        Me.numeros.Multiline = True
        Me.numeros.Name = "numeros"
        Me.numeros.Size = New System.Drawing.Size(287, 199)
        Me.numeros.TabIndex = 2
        '
        'ErrorProvider
        '
        Me.ErrorProvider.ContainerControl = Me
        '
        'cmdCargar
        '
        Me.cmdCargar.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdCargar.Location = New System.Drawing.Point(224, 9)
        Me.cmdCargar.Name = "cmdCargar"
        Me.cmdCargar.Size = New System.Drawing.Size(100, 24)
        Me.cmdCargar.TabIndex = 12
        Me.cmdCargar.Text = "Cargar"
        '
        'GroupBox2
        '
        Me.GroupBox2.Controls.Add(Me.Label1)
        Me.GroupBox2.Location = New System.Drawing.Point(10, 327)
        Me.GroupBox2.Name = "GroupBox2"
        Me.GroupBox2.Size = New System.Drawing.Size(415, 75)
        Me.GroupBox2.TabIndex = 11
        Me.GroupBox2.TabStop = False
        Me.GroupBox2.Text = "Instrucciones"
        '
        'Label1
        '
        Me.Label1.Location = New System.Drawing.Point(8, 26)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(401, 36)
        Me.Label1.TabIndex = 0
        Me.Label1.Text = "Ingrese una lista de números, (uno por línea), sin dejar líneas vacías. Debe incl" & _
            "uir el código de área en cada uno."
        '
        'cargBatchSuscripcion
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(6, 14)
        Me.ClientSize = New System.Drawing.Size(434, 451)
        Me.Controls.Add(Me.GroupBox1)
        Me.Controls.Add(Me.GroupBox2)
        Me.Name = "cargBatchSuscripcion"
        Me.pTitle = "Suscripción por Lote"
        Me.Text = "Suscripción por Lote"
        Me.Controls.SetChildIndex(Me.GroupBox2, 0)
        Me.Controls.SetChildIndex(Me.GroupBox1, 0)
        Me.Controls.SetChildIndex(Me.cmdPanel, 0)
        Me.Controls.SetChildIndex(Me.pbUpper, 0)
        CType(Me.pbUpper, System.ComponentModel.ISupportInitialize).EndInit()
        Me.cmdPanel.ResumeLayout(False)
        CType(Me.imgLinea, System.ComponentModel.ISupportInitialize).EndInit()
        Me.GroupBox1.ResumeLayout(False)
        Me.GroupBox1.PerformLayout()
        CType(Me.ErrorProvider, System.ComponentModel.ISupportInitialize).EndInit()
        Me.GroupBox2.ResumeLayout(False)
        Me.ResumeLayout(False)

    End Sub
#End Region
    'Private vID As Integer = -1
    'Private eliminados() As Integer

    Private Sub cmdCargar_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdCargar.Click
        If validar() Then If agregar() Then Dispose()
    End Sub
    Function validar() As Boolean
        ErrorProvider.SetError(cbSuscripciones, "")
        ErrorProvider.SetError(numeros, "")

        validar = False
        If cbSuscripciones.SelectedIndex = -1 Then
            ErrorProvider.SetError(cbSuscripciones, "Debe escoger una suscripción")
        ElseIf numeros.Text = "" Then
            ErrorProvider.SetError(numeros, "Debe ingresar al menos un número")
        Else
            validar = validarNumeros()
        End If
    End Function
    Function validarNumeros() As Boolean
        Dim strNumeros As String() = numeros.Text.Split(vbNewLine)
        Dim i As Integer
        For i = 0 To strNumeros.Length - 1
            strNumeros(i) = strNumeros(i).Trim()
            If Not IsNumeric(strNumeros(i)) Then GoTo ShowError
            If Not strNumeros(i).Length = 3 + 8 Then GoTo ShowError
            Continue For

ShowError:  ErrorProvider.SetError(numeros, "El item " & i + 1 & ": " & strNumeros(i) & " no es válido")
            Return False
        Next
        Return True
    End Function
    Function agregar() As Boolean
        agregar = False

        If CNX.State <> ConnectionState.Open Then CNX.Open()
        Dim trans As MySqlTransaction = CNX.BeginTransaction
        Dim cmd As New MySQLCommand("", CNX, trans)
        Dim strNumeros As String() = numeros.Text.Split(vbNewLine)
        Dim suscripcion As Integer = valor(cbSuscripciones)

        Dim i As Integer
        For i = 0 To strNumeros.Length - 1
            strNumeros(i) = strNumeros(i).Trim()
            Try
                cmd.CommandText = "SELECT COUNT(*) FROM suscripciones_participantes WHERE idSuscripcion=" & suscripcion & " AND numero=" & strNumeros(i)
                Dim conteo As Integer = cmd.ExecuteScalar()
                If conteo = 0 Then
                    cmd.CommandText = "INSERT INTO suscripciones_participantes (idSuscripcion,numero,fecha) VALUES (" & suscripcion & "," & strNumeros(i) & ",NOW())"
                    cmd.ExecuteNonQuery()
                End If
            Catch ex As MySQLException
                trans.Rollback()
                MsgBox("Error! No se pudo ingresar el item " & i + 1 & ": " & strNumeros(i) & " / " & ex.Message, MsgBoxStyle.SystemModal)
            End Try
        Next
        Try
            trans.Commit()
        Catch ex As MySQLException
            MsgBox("Error! No se pudo completar la transacción", MsgBoxStyle.SystemModal)
            Return False
        End Try
        Return True
    End Function

    Private Sub agrSuscripcion_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
    End Sub
End Class
