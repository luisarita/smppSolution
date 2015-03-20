<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()> _
Partial Class ingreso
    Inherits formControl.frmTemplate

    'Form overrides dispose to clean up the component list.
    <System.Diagnostics.DebuggerNonUserCode()> _
    Protected Overrides Sub Dispose(ByVal disposing As Boolean)
        If disposing AndAlso components IsNot Nothing Then
            components.Dispose()
        End If
        MyBase.Dispose(disposing)
    End Sub

    'Required by the Windows Form Designer
    Private components As System.ComponentModel.IContainer

    'NOTE: The following procedure is required by the Windows Form Designer
    'It can be modified using the Windows Form Designer.  
    'Do not modify it using the code editor.
    <System.Diagnostics.DebuggerStepThrough()> _
    Private Sub InitializeComponent()
        Me.txtUsuario = New System.Windows.Forms.TextBox()
        Me.txtClave = New System.Windows.Forms.TextBox()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.Label2 = New System.Windows.Forms.Label()
        Me.cmdIngresar = New System.Windows.Forms.Button()
        Me.version = New System.Windows.Forms.Label()
        Me.Label3 = New System.Windows.Forms.Label()
        Me.Label4 = New System.Windows.Forms.Label()
        Me.txtServidor = New System.Windows.Forms.TextBox()
        Me.txtDatabase = New System.Windows.Forms.TextBox()
        CType(Me.pbUpper,System.ComponentModel.ISupportInitialize).BeginInit
        Me.cmdPanel.SuspendLayout
        CType(Me.imgLinea,System.ComponentModel.ISupportInitialize).BeginInit
        Me.SuspendLayout
        '
        'pbUpper
        '
        Me.pbUpper.Size = New System.Drawing.Size(574, 57)
        '
        'cmdPanel
        '
        Me.cmdPanel.Controls.Add(Me.version)
        Me.cmdPanel.Controls.Add(Me.cmdIngresar)
        Me.cmdPanel.Location = New System.Drawing.Point(0, 178)
        Me.cmdPanel.Padding = New System.Windows.Forms.Padding(6, 0, 6, 0)
        Me.cmdPanel.Size = New System.Drawing.Size(574, 37)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdCerrar, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.cmdIngresar, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.version, 0)
        Me.cmdPanel.Controls.SetChildIndex(Me.imgLinea, 0)
        '
        'cmdCerrar
        '
        Me.cmdCerrar.Location = New System.Drawing.Point(451, 9)
        Me.cmdCerrar.Size = New System.Drawing.Size(117, 24)
        Me.cmdCerrar.Visible = false
        '
        'imgLinea
        '
        Me.imgLinea.Location = New System.Drawing.Point(6, 0)
        Me.imgLinea.Size = New System.Drawing.Size(562, 8)
        '
        'txtUsuario
        '
        Me.txtUsuario.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.txtUsuario.Location = New System.Drawing.Point(111, 116)
        Me.txtUsuario.Name = "txtUsuario"
        Me.txtUsuario.Size = New System.Drawing.Size(451, 21)
        Me.txtUsuario.TabIndex = 1
        '
        'txtClave
        '
        Me.txtClave.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.txtClave.Location = New System.Drawing.Point(111, 143)
        Me.txtClave.Name = "txtClave"
        Me.txtClave.Size = New System.Drawing.Size(451, 21)
        Me.txtClave.TabIndex = 2
        Me.txtClave.UseSystemPasswordChar = true
        '
        'Label1
        '
        Me.Label1.AutoSize = true
        Me.Label1.Location = New System.Drawing.Point(3, 118)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(53, 13)
        Me.Label1.TabIndex = 13
        Me.Label1.Text = "Usuario:"
        '
        'Label2
        '
        Me.Label2.AutoSize = true
        Me.Label2.Location = New System.Drawing.Point(3, 145)
        Me.Label2.Name = "Label2"
        Me.Label2.Size = New System.Drawing.Size(75, 13)
        Me.Label2.TabIndex = 14
        Me.Label2.Text = "Contraseña:"
        '
        'cmdIngresar
        '
        Me.cmdIngresar.Location = New System.Drawing.Point(451, 9)
        Me.cmdIngresar.Name = "cmdIngresar"
        Me.cmdIngresar.Size = New System.Drawing.Size(117, 24)
        Me.cmdIngresar.TabIndex = 11
        Me.cmdIngresar.Text = "&Ingresar"
        Me.cmdIngresar.UseVisualStyleBackColor = true
        '
        'version
        '
        Me.version.AutoSize = true
        Me.version.Location = New System.Drawing.Point(9, 9)
        Me.version.Name = "version"
        Me.version.Size = New System.Drawing.Size(49, 13)
        Me.version.TabIndex = 12
        Me.version.Text = "version"
        '
        'Label3
        '
        Me.Label3.AutoSize = true
        Me.Label3.Location = New System.Drawing.Point(3, 92)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(90, 13)
        Me.Label3.TabIndex = 18
        Me.Label3.Text = "Base de Datos:"
        '
        'Label4
        '
        Me.Label4.AutoSize = true
        Me.Label4.Location = New System.Drawing.Point(3, 65)
        Me.Label4.Name = "Label4"
        Me.Label4.Size = New System.Drawing.Size(58, 13)
        Me.Label4.TabIndex = 17
        Me.Label4.Text = "Servidor:"
        '
        'txtServidor
        '
        Me.txtServidor.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.txtServidor.Location = New System.Drawing.Point(111, 63)
        Me.txtServidor.Name = "txtServidor"
        Me.txtServidor.Size = New System.Drawing.Size(451, 21)
        Me.txtServidor.TabIndex = 3
        '
        'txtDatabase
        '
        Me.txtDatabase.BorderStyle = System.Windows.Forms.BorderStyle.FixedSingle
        Me.txtDatabase.Location = New System.Drawing.Point(111, 90)
        Me.txtDatabase.Name = "txtDatabase"
        Me.txtDatabase.Size = New System.Drawing.Size(451, 21)
        Me.txtDatabase.TabIndex = 4
        '
        'ingreso
        '
        Me.AcceptButton = Me.cmdIngresar
        Me.AutoScaleDimensions = New System.Drawing.SizeF(7!, 13!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
        Me.AutoScroll = true
        Me.ClientSize = New System.Drawing.Size(574, 215)
        Me.Controls.Add(Me.txtDatabase)
        Me.Controls.Add(Me.Label3)
        Me.Controls.Add(Me.Label4)
        Me.Controls.Add(Me.txtServidor)
        Me.Controls.Add(Me.Label2)
        Me.Controls.Add(Me.Label1)
        Me.Controls.Add(Me.txtClave)
        Me.Controls.Add(Me.txtUsuario)
        Me.Name = "ingreso"
        Me.pTitle = "Ingreso"
        Me.Text = "Ingreso"
        Me.Controls.SetChildIndex(Me.pbUpper, 0)
        Me.Controls.SetChildIndex(Me.txtUsuario, 0)
        Me.Controls.SetChildIndex(Me.txtClave, 0)
        Me.Controls.SetChildIndex(Me.Label1, 0)
        Me.Controls.SetChildIndex(Me.Label2, 0)
        Me.Controls.SetChildIndex(Me.cmdPanel, 0)
        Me.Controls.SetChildIndex(Me.txtServidor, 0)
        Me.Controls.SetChildIndex(Me.Label4, 0)
        Me.Controls.SetChildIndex(Me.Label3, 0)
        Me.Controls.SetChildIndex(Me.txtDatabase, 0)
        CType(Me.pbUpper,System.ComponentModel.ISupportInitialize).EndInit
        Me.cmdPanel.ResumeLayout(false)
        Me.cmdPanel.PerformLayout
        CType(Me.imgLinea,System.ComponentModel.ISupportInitialize).EndInit
        Me.ResumeLayout(false)
        Me.PerformLayout

End Sub
    Friend WithEvents txtUsuario As System.Windows.Forms.TextBox
    Friend WithEvents txtClave As System.Windows.Forms.TextBox
    Friend WithEvents Label1 As System.Windows.Forms.Label
    Friend WithEvents Label2 As System.Windows.Forms.Label
    Friend WithEvents cmdIngresar As System.Windows.Forms.Button
    Friend WithEvents version As System.Windows.Forms.Label
    Friend WithEvents Label3 As System.Windows.Forms.Label
    Friend WithEvents Label4 As System.Windows.Forms.Label
    Friend WithEvents txtServidor As System.Windows.Forms.TextBox
    Friend WithEvents txtDatabase As System.Windows.Forms.TextBox
End Class
