<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()> _
Partial Class agrAgrupadores
    Inherits formControl.frmTemplate

    'Form overrides dispose to clean up the component list.
    <System.Diagnostics.DebuggerNonUserCode()> _
    Protected Overrides Sub Dispose(ByVal disposing As Boolean)
        Try
            If disposing AndAlso components IsNot Nothing Then
                components.Dispose()
            End If
        Finally
            MyBase.Dispose(disposing)
        End Try
    End Sub

    'Required by the Windows Form Designer
    Private components As System.ComponentModel.IContainer

    'NOTE: The following procedure is required by the Windows Form Designer
    'It can be modified using the Windows Form Designer.  
    'Do not modify it using the code editor.
    <System.Diagnostics.DebuggerStepThrough()> _
    Private Sub InitializeComponent()
        Me.components = New System.ComponentModel.Container()
        Me.Label3 = New System.Windows.Forms.Label()
        Me.clave = New System.Windows.Forms.TextBox()
        Me.cmdCrear = New System.Windows.Forms.Button()
        Me.GroupBox6 = New System.Windows.Forms.GroupBox()
        Me.Label23 = New System.Windows.Forms.Label()
        Me.cbSuscripciones = New System.Windows.Forms.ComboBox()
        Me.cmdEliSuscripcon = New System.Windows.Forms.Button()
        Me.cmdAgrSuscripcion = New System.Windows.Forms.Button()
        Me.lbSuscripciones = New System.Windows.Forms.ListBox()
        Me.lblSuscripcion = New System.Windows.Forms.Label()
        Me.ErrorProvider = New System.Windows.Forms.ErrorProvider(Me.components)
        CType(Me.pbUpper, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.cmdPanel.SuspendLayout()
        CType(Me.imgLinea, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.GroupBox6.SuspendLayout()
        CType(Me.ErrorProvider, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'cmdPanel
        '
        Me.cmdPanel.Controls.Add(Me.cmdCrear)
        Me.cmdPanel.Location = New System.Drawing.Point(0, 211)
        Me.cmdPanel.Size = New System.Drawing.Size(404, 37)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCerrar, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.imgLinea, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCrear, 0)
        '
        'cmdCerrar
        '
        Me.cmdCerrar.Location = New System.Drawing.Point(299, 10)
        '
        'imgLinea
        '
        Me.imgLinea.Size = New System.Drawing.Size(394, 8)
        '
        'Label3
        '
        Me.Label3.Location = New System.Drawing.Point(14, 63)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(93, 21)
        Me.Label3.TabIndex = 11
        Me.Label3.Text = "Clave:"
        Me.Label3.TextAlign = System.Drawing.ContentAlignment.MiddleLeft
        '
        'clave
        '
        Me.clave.BackColor = System.Drawing.Color.White
        Me.clave.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.clave.Location = New System.Drawing.Point(172, 65)
        Me.clave.Name = "clave"
        Me.clave.Size = New System.Drawing.Size(208, 21)
        Me.clave.TabIndex = 10
        '
        'cmdCrear
        '
        Me.cmdCrear.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdCrear.Location = New System.Drawing.Point(185, 10)
        Me.cmdCrear.Name = "cmdCrear"
        Me.cmdCrear.Size = New System.Drawing.Size(100, 24)
        Me.cmdCrear.TabIndex = 15
        Me.cmdCrear.Text = "&Salvar"
        '
        'GroupBox6
        '
        Me.GroupBox6.Controls.Add(Me.Label23)
        Me.GroupBox6.Controls.Add(Me.cbSuscripciones)
        Me.GroupBox6.Controls.Add(Me.cmdEliSuscripcon)
        Me.GroupBox6.Controls.Add(Me.cmdAgrSuscripcion)
        Me.GroupBox6.Controls.Add(Me.lbSuscripciones)
        Me.GroupBox6.Controls.Add(Me.lblSuscripcion)
        Me.GroupBox6.Location = New System.Drawing.Point(5, 90)
        Me.GroupBox6.Name = "GroupBox6"
        Me.GroupBox6.Size = New System.Drawing.Size(386, 104)
        Me.GroupBox6.TabIndex = 18
        Me.GroupBox6.TabStop = False
        Me.GroupBox6.Text = "Actividades"
        '
        'Label23
        '
        Me.Label23.Location = New System.Drawing.Point(8, 42)
        Me.Label23.Name = "Label23"
        Me.Label23.Size = New System.Drawing.Size(158, 33)
        Me.Label23.TabIndex = 35
        Me.Label23.Text = "Actividad(es) Seleccionadas:"
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
        'cmdEliSuscripcon
        '
        Me.cmdEliSuscripcon.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdEliSuscripcon.Location = New System.Drawing.Point(350, 74)
        Me.cmdEliSuscripcon.Name = "cmdEliSuscripcon"
        Me.cmdEliSuscripcon.Size = New System.Drawing.Size(25, 24)
        Me.cmdEliSuscripcon.TabIndex = 3
        Me.cmdEliSuscripcon.Text = "-"
        '
        'cmdAgrSuscripcion
        '
        Me.cmdAgrSuscripcion.FlatStyle = System.Windows.Forms.FlatStyle.System
        Me.cmdAgrSuscripcion.Location = New System.Drawing.Point(351, 13)
        Me.cmdAgrSuscripcion.Name = "cmdAgrSuscripcion"
        Me.cmdAgrSuscripcion.Size = New System.Drawing.Size(25, 24)
        Me.cmdAgrSuscripcion.TabIndex = 2
        Me.cmdAgrSuscripcion.Text = "+"
        '
        'lbSuscripciones
        '
        Me.lbSuscripciones.FormattingEnabled = True
        Me.lbSuscripciones.Location = New System.Drawing.Point(167, 42)
        Me.lbSuscripciones.Name = "lbSuscripciones"
        Me.lbSuscripciones.Size = New System.Drawing.Size(181, 56)
        Me.lbSuscripciones.TabIndex = 1
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
        'ErrorProvider
        '
        Me.ErrorProvider.ContainerControl = Me
        '
        'agrAgrupadores
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(7.0!, 13.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
        Me.ClientSize = New System.Drawing.Size(404, 248)
        Me.Controls.Add(Me.GroupBox6)
        Me.Controls.Add(Me.Label3)
        Me.Controls.Add(Me.clave)
        Me.Name = "agrAgrupadores"
        Me.pTitle = "Agrupador"
        Me.Text = "Agrupador"
        Me.Controls.SetChildIndex(Me.cmdPanel, 0)
        Me.Controls.SetChildIndex(Me.pbUpper, 0)
        Me.Controls.SetChildIndex(Me.clave, 0)
        Me.Controls.SetChildIndex(Me.Label3, 0)
        Me.Controls.SetChildIndex(Me.GroupBox6, 0)
        CType(Me.pbUpper, System.ComponentModel.ISupportInitialize).EndInit()
        Me.cmdPanel.ResumeLayout(False)
        CType(Me.imgLinea, System.ComponentModel.ISupportInitialize).EndInit()
        Me.GroupBox6.ResumeLayout(False)
        CType(Me.ErrorProvider, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)
        Me.PerformLayout()

    End Sub
    Friend WithEvents Label3 As System.Windows.Forms.Label
    Friend WithEvents clave As System.Windows.Forms.TextBox
    Friend WithEvents cmdCrear As System.Windows.Forms.Button
    Friend WithEvents GroupBox6 As System.Windows.Forms.GroupBox
    Friend WithEvents Label23 As System.Windows.Forms.Label
    Friend WithEvents cbSuscripciones As System.Windows.Forms.ComboBox
    Friend WithEvents cmdEliSuscripcon As System.Windows.Forms.Button
    Friend WithEvents cmdAgrSuscripcion As System.Windows.Forms.Button
    Friend WithEvents lbSuscripciones As System.Windows.Forms.ListBox
    Friend WithEvents lblSuscripcion As System.Windows.Forms.Label
    Friend WithEvents ErrorProvider As System.Windows.Forms.ErrorProvider
End Class
