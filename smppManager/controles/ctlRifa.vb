Imports MySql.Data.MySqlClient
Public Class ctlRifa
    Inherits formControl.frmTemplate

#Region " Windows Form Designer generated code "

    Public Sub New()
        MyBase.New()
        InitializeComponent()
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
    Friend WithEvents Label1 As System.Windows.Forms.Label
    Friend WithEvents Label2 As System.Windows.Forms.Label
    Friend WithEvents Label3 As System.Windows.Forms.Label
    Friend WithEvents Label4 As System.Windows.Forms.Label
    Friend WithEvents Label5 As System.Windows.Forms.Label
    Friend WithEvents Label6 As System.Windows.Forms.Label
    Friend WithEvents Label7 As System.Windows.Forms.Label
    Friend WithEvents cbRifas As System.Windows.Forms.ComboBox
    Friend WithEvents txtWinner As System.Windows.Forms.TextBox
    Friend WithEvents txtLooser As System.Windows.Forms.TextBox
    Friend WithEvents txtParticipantes As System.Windows.Forms.TextBox
    Friend WithEvents txtWinners As System.Windows.Forms.TextBox
    Friend WithEvents txtEstado As System.Windows.Forms.TextBox
    Friend WithEvents lbWinners As System.Windows.Forms.ListBox
    Friend WithEvents cmdCerrarRifa As System.Windows.Forms.Button
    Friend WithEvents cmdLimpiar As System.Windows.Forms.Button
    Friend WithEvents Button1 As System.Windows.Forms.Button

    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.GroupBox1 = New System.Windows.Forms.GroupBox
        Me.Label7 = New System.Windows.Forms.Label
        Me.txtEstado = New System.Windows.Forms.TextBox
        Me.lbWinners = New System.Windows.Forms.ListBox
        Me.Label6 = New System.Windows.Forms.Label
        Me.Label5 = New System.Windows.Forms.Label
        Me.txtWinners = New System.Windows.Forms.TextBox
        Me.Label4 = New System.Windows.Forms.Label
        Me.txtParticipantes = New System.Windows.Forms.TextBox
        Me.Label3 = New System.Windows.Forms.Label
        Me.Label2 = New System.Windows.Forms.Label
        Me.Label1 = New System.Windows.Forms.Label
        Me.txtLooser = New System.Windows.Forms.TextBox
        Me.cbRifas = New System.Windows.Forms.ComboBox
        Me.txtWinner = New System.Windows.Forms.TextBox
        Me.cmdCerrarRifa = New System.Windows.Forms.Button
        Me.cmdLimpiar = New System.Windows.Forms.Button
        Me.Button1 = New System.Windows.Forms.Button
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
        Me.cmdPanel.Controls.Add(Me.cmdLimpiar)
        Me.cmdPanel.Controls.Add(Me.cmdCerrarRifa)
        Me.cmdPanel.DockPadding.Left = 5
        Me.cmdPanel.DockPadding.Right = 5
        Me.cmdPanel.Location = New System.Drawing.Point(0, 351)
        Me.cmdPanel.Name = "cmdPanel"
        Me.cmdPanel.Size = New System.Drawing.Size(402, 37)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCerrarRifa, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCerrar, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.imgLinea, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdLimpiar, 0)
        '
        'cmdCerrar
        '
        Me.cmdCerrar.Location = New System.Drawing.Point(297, 9)
        Me.cmdCerrar.Name = "cmdCerrar"
        '
        'imgLinea
        '
        Me.imgLinea.Name = "imgLinea"
        Me.imgLinea.Size = New System.Drawing.Size(392, 8)
        '
        'GroupBox1
        '
        Me.GroupBox1.Controls.Add(Me.Button1)
        Me.GroupBox1.Controls.Add(Me.Label7)
        Me.GroupBox1.Controls.Add(Me.txtEstado)
        Me.GroupBox1.Controls.Add(Me.lbWinners)
        Me.GroupBox1.Controls.Add(Me.Label6)
        Me.GroupBox1.Controls.Add(Me.Label5)
        Me.GroupBox1.Controls.Add(Me.txtWinners)
        Me.GroupBox1.Controls.Add(Me.Label3)
        Me.GroupBox1.Controls.Add(Me.Label2)
        Me.GroupBox1.Controls.Add(Me.Label1)
        Me.GroupBox1.Controls.Add(Me.txtLooser)
        Me.GroupBox1.Controls.Add(Me.cbRifas)
        Me.GroupBox1.Controls.Add(Me.txtWinner)
        Me.GroupBox1.Controls.Add(Me.Label4)
        Me.GroupBox1.Controls.Add(Me.txtParticipantes)
        Me.GroupBox1.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.GroupBox1.Location = New System.Drawing.Point(4, 56)
        Me.GroupBox1.Name = "GroupBox1"
        Me.GroupBox1.Size = New System.Drawing.Size(395, 296)
        Me.GroupBox1.TabIndex = 10
        Me.GroupBox1.TabStop = False
        '
        'Label7
        '
        Me.Label7.Location = New System.Drawing.Point(9, 136)
        Me.Label7.Name = "Label7"
        Me.Label7.Size = New System.Drawing.Size(159, 21)
        Me.Label7.TabIndex = 13
        Me.Label7.Text = "Estado de Rifa:"
        Me.Label7.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'txtEstado
        '
        Me.txtEstado.BackColor = System.Drawing.Color.White
        Me.txtEstado.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.txtEstado.Location = New System.Drawing.Point(169, 136)
        Me.txtEstado.Name = "txtEstado"
        Me.txtEstado.ReadOnly = True
        Me.txtEstado.Size = New System.Drawing.Size(216, 21)
        Me.txtEstado.TabIndex = 12
        Me.txtEstado.Text = ""
        '
        'lbWinners
        '
        Me.lbWinners.BackColor = System.Drawing.Color.White
        Me.lbWinners.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.lbWinners.Location = New System.Drawing.Point(168, 160)
        Me.lbWinners.Name = "lbWinners"
        Me.lbWinners.Size = New System.Drawing.Size(216, 132)
        Me.lbWinners.TabIndex = 11
        '
        'Label6
        '
        Me.Label6.Location = New System.Drawing.Point(8, 160)
        Me.Label6.Name = "Label6"
        Me.Label6.Size = New System.Drawing.Size(159, 21)
        Me.Label6.TabIndex = 10
        Me.Label6.Text = "Ganadores:"
        Me.Label6.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'Label5
        '
        Me.Label5.Location = New System.Drawing.Point(8, 112)
        Me.Label5.Name = "Label5"
        Me.Label5.Size = New System.Drawing.Size(159, 21)
        Me.Label5.TabIndex = 9
        Me.Label5.Text = "Cantidad de Ganadores:"
        Me.Label5.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'txtWinners
        '
        Me.txtWinners.BackColor = System.Drawing.Color.White
        Me.txtWinners.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.txtWinners.Location = New System.Drawing.Point(168, 112)
        Me.txtWinners.Name = "txtWinners"
        Me.txtWinners.ReadOnly = True
        Me.txtWinners.Size = New System.Drawing.Size(216, 21)
        Me.txtWinners.TabIndex = 8
        Me.txtWinners.Text = ""
        '
        'Label4
        '
        Me.Label4.Location = New System.Drawing.Point(8, 40)
        Me.Label4.Name = "Label4"
        Me.Label4.Size = New System.Drawing.Size(159, 21)
        Me.Label4.TabIndex = 7
        Me.Label4.Text = "Cantidad de Participantes:"
        Me.Label4.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'txtParticipantes
        '
        Me.txtParticipantes.BackColor = System.Drawing.Color.White
        Me.txtParticipantes.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.txtParticipantes.Location = New System.Drawing.Point(168, 40)
        Me.txtParticipantes.Name = "txtParticipantes"
        Me.txtParticipantes.ReadOnly = True
        Me.txtParticipantes.Size = New System.Drawing.Size(112, 21)
        Me.txtParticipantes.TabIndex = 6
        Me.txtParticipantes.Text = ""
        Me.txtParticipantes.TextAlign = System.Windows.Forms.HorizontalAlignment.Right
        '
        'Label3
        '
        Me.Label3.Location = New System.Drawing.Point(8, 64)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(144, 21)
        Me.Label3.TabIndex = 5
        Me.Label3.Text = "Mensaje a Ganador:"
        Me.Label3.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'Label2
        '
        Me.Label2.Location = New System.Drawing.Point(8, 88)
        Me.Label2.Name = "Label2"
        Me.Label2.Size = New System.Drawing.Size(144, 21)
        Me.Label2.TabIndex = 4
        Me.Label2.Text = "Mensaje a Participantes:"
        Me.Label2.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'Label1
        '
        Me.Label1.Location = New System.Drawing.Point(8, 17)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(144, 21)
        Me.Label1.TabIndex = 3
        Me.Label1.Text = "Rifa:"
        Me.Label1.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'txtLooser
        '
        Me.txtLooser.BackColor = System.Drawing.Color.White
        Me.txtLooser.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.txtLooser.Location = New System.Drawing.Point(168, 88)
        Me.txtLooser.Name = "txtLooser"
        Me.txtLooser.ReadOnly = True
        Me.txtLooser.Size = New System.Drawing.Size(216, 21)
        Me.txtLooser.TabIndex = 2
        Me.txtLooser.Text = ""
        '
        'cbRifas
        '
        Me.cbRifas.BackColor = System.Drawing.Color.White
        Me.cbRifas.DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList
        Me.cbRifas.Location = New System.Drawing.Point(168, 17)
        Me.cbRifas.Name = "cbRifas"
        Me.cbRifas.Size = New System.Drawing.Size(216, 21)
        Me.cbRifas.TabIndex = 1
        '
        'txtWinner
        '
        Me.txtWinner.BackColor = System.Drawing.Color.White
        Me.txtWinner.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.txtWinner.Location = New System.Drawing.Point(168, 64)
        Me.txtWinner.Name = "txtWinner"
        Me.txtWinner.ReadOnly = True
        Me.txtWinner.Size = New System.Drawing.Size(216, 21)
        Me.txtWinner.TabIndex = 0
        Me.txtWinner.Text = ""
        '
        'cmdCerrarRifa
        '
        Me.cmdCerrarRifa.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdCerrarRifa.Location = New System.Drawing.Point(192, 9)
        Me.cmdCerrarRifa.Name = "cmdCerrarRifa"
        Me.cmdCerrarRifa.Size = New System.Drawing.Size(100, 24)
        Me.cmdCerrarRifa.TabIndex = 11
        Me.cmdCerrarRifa.Text = "Cerrar Rifa"
        '
        'cmdLimpiar
        '
        Me.cmdLimpiar.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdLimpiar.Location = New System.Drawing.Point(4, 9)
        Me.cmdLimpiar.Name = "cmdLimpiar"
        Me.cmdLimpiar.Size = New System.Drawing.Size(100, 24)
        Me.cmdLimpiar.TabIndex = 12
        Me.cmdLimpiar.Text = "Limpiar"
        '
        'Button1
        '
        Me.Button1.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.Button1.Location = New System.Drawing.Point(284, 40)
        Me.Button1.Name = "Button1"
        Me.Button1.Size = New System.Drawing.Size(100, 21)
        Me.Button1.TabIndex = 14
        Me.Button1.Text = "&Manejar"
        '
        'ctlRifa
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(6, 14)
        Me.ClientSize = New System.Drawing.Size(402, 388)
        Me.Controls.Add(Me.GroupBox1)
        Me.Name = "ctlRifa"
        Me.pTitle = "Control de Rifa"
        Me.Text = "Control de Rifa"
        Me.Controls.SetChildIndex(Me.GroupBox1, 0)
        Me.Controls.SetChildIndex(Me.cmdPanel, 0)
        Me.Controls.SetChildIndex(Me.pbUpper, 0)
        Me.cmdPanel.ResumeLayout(False)
        Me.GroupBox1.ResumeLayout(False)
        Me.ResumeLayout(False)

    End Sub

#End Region

    Private Sub ctlRifa_Load(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MyBase.Load
        fncListboxMySQL.popularMySQL(CObj(cbRifas), "rifas", "id", "nombre", CNX)
    End Sub

    Private Sub cbRifas_SelectedIndexChanged(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cbRifas.SelectedIndexChanged
        txtWinner.Text = ""
        txtLooser.Text = ""
        txtParticipantes.Text = "0"
        txtWinners.Text = ""
        txtEstado.Text = ""

        If CNX.State <> ConnectionState.Open Then CNX.Open()

        Dim id As Integer = valor(cbRifas)
        Dim dr As MySqlDataReader = New MySqlCommand("SELECT * FROM rifas WHERE id=" & id, CNX).ExecuteReader
        If dr.Read Then
            txtWinner.Text = dr("mensaje_ganador")
            txtLooser.Text = dr("mensaje_participante_a")
            txtWinners.Text = dr("cantidad_ganadores")
            txtEstado.Text = IIf(dr("estado") = 1, "Abierta", "Cerrada")
        End If
        dr.Close()

        lbWinners.Items.Clear()
        Dim dr2 As MySqlDataReader = New MySqlCommand("SELECT idRifa FROM rifas_participantes WHERE idRifa=" & id, CNX).ExecuteReader
        While dr2.Read
            txtParticipantes.Text += 1
        End While
        dr2.Close()

        If txtEstado.Text = "Cerrada" Then
            dr = New MySqlCommand("SELECT numero FROM rifas_ganadores WHERE idRifa=" & id, CNX).ExecuteReader
            While dr.Read
                lbWinners.Items.Add(dr!numero)
            End While
            dr.Close()
            cmdCerrarRifa.Enabled = False
            cmdLimpiar.Enabled = True
        Else
            cmdCerrarRifa.Enabled = True
            cmdLimpiar.Enabled = False
        End If
    End Sub

    Private Sub cmdCerrarRifa_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdCerrarRifa.Click
        If CNX.State <> ConnectionState.Open Then CNX.Open()

        Dim id As Integer = valor(cbRifas)
        Dim dr As MySqlDataReader = New MySqlCommand("SELECT estado FROM rifas WHERE id=" & id, CNX).ExecuteReader
        If dr.Read Then
            If dr(0) = 0 Then
                MsgBox("Esta rifa ya fue cerrada")
            Else
                dr.Close()
                GoTo cerrar
            End If
        End If
        dr.Close()
        Exit Sub

cerrar: Dim updateCMD As New MySqlCommand("UPDATE rifas SET estado=0 WHERE id=" & id, CNX)
        updateCMD.ExecuteNonQuery()
        Dim cantidad_ganadores As Integer, cantidad_jugadores As Integer
        dr = New MySqlCommand("SELECT cantidad_ganadores FROM rifas WHERE id=" & id, CNX).ExecuteReader
        If dr.Read Then cantidad_ganadores = dr(0)
        dr.Close()

        'Si solo dispone de un chance
        'Dim uniqueSelect As String = "SELECT MIN(id) AS id,numero FROM rifas_participantes WHERE idRifa = " & id & " GROUP BY numero"
        'dr = New odbc.MySQLCommand("SELECT COUNT(*) FROM (" & uniqueSelect & ") DERIVEDTBL", CNX).ExecuteReader
        'If dr.Read Then cantidad_jugadores = dr(0)
        'dr.Close()

        cantidad_jugadores = 0
        Dim selectQuery As String = "SELECT id,numero FROM rifas_participantes WHERE idRifa=" & id
        Dim dr2 As MySqlDataReader = New MySqlCommand(selectQuery, CNX).ExecuteReader
        While dr2.Read
            cantidad_jugadores += 1
        End While
        dr2.Close()

        If cantidad_jugadores = 0 Then
            MsgBox("No hay jugadores para esta rifa")
        Else
            If cantidad_ganadores > cantidad_jugadores Then MsgBox("Hay mas ganadores que jugadores")
            Dim CNX2 As New MySqlConnection(cnxString)
            CNX2.Open()

            While cantidad_ganadores > 0
                dr = New MySqlCommand(selectQuery, CNX).ExecuteReader
                Dim rand As Integer = cantidad_jugadores * Rnd()
                While rand > 0
                    dr.Read()
                    rand -= 1
                End While
                Dim insertCMD As New MySqlCommand("INSERT INTO rifas_ganadores (idRifa,numero) VALUES (" & id & "," & dr(1) & ")", CNX2)
                insertCMD.ExecuteNonQuery()
                cantidad_ganadores -= 1

                dr.Close()
            End While
        End If
        cmdLimpiar.Enabled = True
        cmdCerrarRifa.Enabled = False
        txtEstado.Text = "Cerrada"
    End Sub

    Private Sub cmdLimpiar_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles cmdLimpiar.Click
        If MsgBox("¿Seguro que desea limpiar resultados?", MsgBoxStyle.YesNo) = MsgBoxResult.Yes Then
            Dim id As Integer = valor(cbRifas)
            Try
                Dim cmd As New MySqlCommand("DELETE FROM rifas_ganadores WHERE idRifa=" & id, CNX)
                cmd.ExecuteNonQuery()
                cmd.CommandText = "DELETE FROM rifas_participantes WHERE idRifa=" & id
                cmd.ExecuteNonQuery()
                cmd.CommandText = "UPDATE rifas SET estado=1 WHERE id=" & id
                cmd.ExecuteNonQuery()
                cmdLimpiar.Enabled = False
                cmdCerrarRifa.Enabled = True
                txtEstado.Text = "Abierta"
            Catch thisError As MySqlException
                MsgBox("No se pudo limpiar: " & thisError.Message)
            End Try
        End If
    End Sub

    Private Sub Button1_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles Button1.Click
        Dim vID As Integer = valor(cbRifas)
        Dim c As New ctlParticipantes(vID)
        c.MdiParent = MdiParent
        c.Show()
    End Sub
End Class