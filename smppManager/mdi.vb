Public Class mdi
    Inherits System.Windows.Forms.Form

#Region " Windows Form Designer generated code "
    Public Sub New()
        MyBase.New()
        InitializeComponent()
    End Sub

    'Form overrides dispose to clean up the component list.
    Protected Overloads Overrides Sub Dispose(ByVal disposing As Boolean)
        If disposing Then
            If Not (components Is Nothing) Then
                components.Dispose()
            End If
        End If
        MyBase.Dispose(disposing)
    End Sub

    'Required by the Windows Form Designer
    Private components As System.ComponentModel.IContainer

    'NOTE: The following procedure is required by the Windows Form Designer
    'It can be modified using the Windows Form Designer.  
    'Do not modify it using the code editor.
    Friend WithEvents MainMenu1 As System.Windows.Forms.MainMenu
    Friend WithEvents MenuItem1 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem2 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem3 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem4 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem5 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem6 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem7 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem11 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem12 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem15 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem16 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem17 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem18 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem19 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem20 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem21 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem22 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem23 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem24 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem25 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem26 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem28 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem29 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem30 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem31 As System.Windows.Forms.MenuItem
    Friend WithEvents mnuConetoCola As System.Windows.Forms.MenuItem
    Friend WithEvents mnuPriorizar As System.Windows.Forms.MenuItem
    Friend WithEvents mnuConsCola As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem33 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem34 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem35 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem37 As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem8 As System.Windows.Forms.MenuItem
    Friend WithEvents mnuAgrChat As System.Windows.Forms.MenuItem
    Friend WithEvents mnuConsChats As System.Windows.Forms.MenuItem
    Friend WithEvents mnuAgrupadores As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem9 As System.Windows.Forms.MenuItem
    Friend WithEvents mnuCrearAgrupadores As System.Windows.Forms.MenuItem
    Friend WithEvents MenuItem27 As System.Windows.Forms.MenuItem
    <System.Diagnostics.DebuggerStepThrough()> Private Sub InitializeComponent()
        Me.components = New System.ComponentModel.Container()
        Me.MainMenu1 = New System.Windows.Forms.MainMenu(Me.components)
        Me.MenuItem1 = New System.Windows.Forms.MenuItem()
        Me.MenuItem5 = New System.Windows.Forms.MenuItem()
        Me.MenuItem12 = New System.Windows.Forms.MenuItem()
        Me.MenuItem6 = New System.Windows.Forms.MenuItem()
        Me.MenuItem7 = New System.Windows.Forms.MenuItem()
        Me.MenuItem31 = New System.Windows.Forms.MenuItem()
        Me.mnuAgrupadores = New System.Windows.Forms.MenuItem()
        Me.MenuItem2 = New System.Windows.Forms.MenuItem()
        Me.MenuItem11 = New System.Windows.Forms.MenuItem()
        Me.MenuItem3 = New System.Windows.Forms.MenuItem()
        Me.MenuItem4 = New System.Windows.Forms.MenuItem()
        Me.MenuItem37 = New System.Windows.Forms.MenuItem()
        Me.MenuItem20 = New System.Windows.Forms.MenuItem()
        Me.MenuItem21 = New System.Windows.Forms.MenuItem()
        Me.MenuItem22 = New System.Windows.Forms.MenuItem()
        Me.MenuItem17 = New System.Windows.Forms.MenuItem()
        Me.MenuItem18 = New System.Windows.Forms.MenuItem()
        Me.MenuItem19 = New System.Windows.Forms.MenuItem()
        Me.MenuItem23 = New System.Windows.Forms.MenuItem()
        Me.MenuItem24 = New System.Windows.Forms.MenuItem()
        Me.MenuItem25 = New System.Windows.Forms.MenuItem()
        Me.MenuItem26 = New System.Windows.Forms.MenuItem()
        Me.MenuItem28 = New System.Windows.Forms.MenuItem()
        Me.MenuItem29 = New System.Windows.Forms.MenuItem()
        Me.MenuItem30 = New System.Windows.Forms.MenuItem()
        Me.MenuItem33 = New System.Windows.Forms.MenuItem()
        Me.MenuItem34 = New System.Windows.Forms.MenuItem()
        Me.MenuItem35 = New System.Windows.Forms.MenuItem()
        Me.MenuItem8 = New System.Windows.Forms.MenuItem()
        Me.mnuAgrChat = New System.Windows.Forms.MenuItem()
        Me.mnuConsChats = New System.Windows.Forms.MenuItem()
        Me.MenuItem15 = New System.Windows.Forms.MenuItem()
        Me.MenuItem16 = New System.Windows.Forms.MenuItem()
        Me.MenuItem27 = New System.Windows.Forms.MenuItem()
        Me.mnuConetoCola = New System.Windows.Forms.MenuItem()
        Me.mnuPriorizar = New System.Windows.Forms.MenuItem()
        Me.mnuConsCola = New System.Windows.Forms.MenuItem()
        Me.MenuItem9 = New System.Windows.Forms.MenuItem()
        Me.mnuCrearAgrupadores = New System.Windows.Forms.MenuItem()
        Me.SuspendLayout()
        '
        'MainMenu1
        '
        Me.MainMenu1.MenuItems.AddRange(New System.Windows.Forms.MenuItem() {Me.MenuItem1, Me.MenuItem15})
        '
        'MenuItem1
        '
        Me.MenuItem1.Index = 0
        Me.MenuItem1.MenuItems.AddRange(New System.Windows.Forms.MenuItem() {Me.MenuItem5, Me.MenuItem2, Me.MenuItem20, Me.MenuItem17, Me.MenuItem23, Me.MenuItem28, Me.MenuItem33, Me.MenuItem8})
        Me.MenuItem1.Text = "Aplicaciones"
        '
        'MenuItem5
        '
        Me.MenuItem5.Index = 0
        Me.MenuItem5.MenuItems.AddRange(New System.Windows.Forms.MenuItem() {Me.MenuItem12, Me.MenuItem6, Me.MenuItem7, Me.MenuItem31, Me.MenuItem9, Me.mnuAgrupadores, Me.mnuCrearAgrupadores})
        Me.MenuItem5.Text = "&Suscripciones"
        '
        'MenuItem12
        '
        Me.MenuItem12.Index = 0
        Me.MenuItem12.Text = "Consultar Suscripciones"
        '
        'MenuItem6
        '
        Me.MenuItem6.Index = 1
        Me.MenuItem6.Text = "Crear Suscripcion"
        '
        'MenuItem7
        '
        Me.MenuItem7.Index = 2
        Me.MenuItem7.Text = "Manejar Suscripcion"
        '
        'MenuItem31
        '
        Me.MenuItem31.Index = 3
        Me.MenuItem31.Text = "Cargar Números por Lotes"
        '
        'mnuAgrupadores
        '
        Me.mnuAgrupadores.Index = 5
        Me.mnuAgrupadores.Text = "&Consultar Agrupadores"
        '
        'MenuItem2
        '
        Me.MenuItem2.Index = 1
        Me.MenuItem2.MenuItems.AddRange(New System.Windows.Forms.MenuItem() {Me.MenuItem11, Me.MenuItem3, Me.MenuItem4, Me.MenuItem37})
        Me.MenuItem2.Text = "&Rifa"
        '
        'MenuItem11
        '
        Me.MenuItem11.Index = 0
        Me.MenuItem11.Text = "Consultar Rifas"
        '
        'MenuItem3
        '
        Me.MenuItem3.Index = 1
        Me.MenuItem3.Text = "Crear Rifa"
        '
        'MenuItem4
        '
        Me.MenuItem4.Index = 2
        Me.MenuItem4.Text = "Manejar Rifa"
        '
        'MenuItem37
        '
        Me.MenuItem37.Index = 3
        Me.MenuItem37.Text = "C&argar Números por Lotes"
        '
        'MenuItem20
        '
        Me.MenuItem20.Index = 2
        Me.MenuItem20.MenuItems.AddRange(New System.Windows.Forms.MenuItem() {Me.MenuItem21, Me.MenuItem22})
        Me.MenuItem20.Text = "&Encuestas"
        '
        'MenuItem21
        '
        Me.MenuItem21.Index = 0
        Me.MenuItem21.Text = "Consultar Encuestas"
        '
        'MenuItem22
        '
        Me.MenuItem22.Index = 1
        Me.MenuItem22.Text = "Crear Encuesta"
        '
        'MenuItem17
        '
        Me.MenuItem17.Index = 3
        Me.MenuItem17.MenuItems.AddRange(New System.Windows.Forms.MenuItem() {Me.MenuItem18, Me.MenuItem19})
        Me.MenuItem17.Text = "&Listados"
        '
        'MenuItem18
        '
        Me.MenuItem18.Index = 0
        Me.MenuItem18.Text = "Consultar Listados"
        '
        'MenuItem19
        '
        Me.MenuItem19.Index = 1
        Me.MenuItem19.Text = "Crear Listado"
        '
        'MenuItem23
        '
        Me.MenuItem23.Index = 4
        Me.MenuItem23.MenuItems.AddRange(New System.Windows.Forms.MenuItem() {Me.MenuItem24, Me.MenuItem25, Me.MenuItem26})
        Me.MenuItem23.Text = "&Trivias"
        '
        'MenuItem24
        '
        Me.MenuItem24.Index = 0
        Me.MenuItem24.Text = "Consultar Trivias"
        '
        'MenuItem25
        '
        Me.MenuItem25.Index = 1
        Me.MenuItem25.Text = "Crear Trivia"
        '
        'MenuItem26
        '
        Me.MenuItem26.Index = 2
        Me.MenuItem26.Text = "Manejar Trivias"
        '
        'MenuItem28
        '
        Me.MenuItem28.Index = 5
        Me.MenuItem28.MenuItems.AddRange(New System.Windows.Forms.MenuItem() {Me.MenuItem29, Me.MenuItem30})
        Me.MenuItem28.Text = "&Telechat"
        '
        'MenuItem29
        '
        Me.MenuItem29.Index = 0
        Me.MenuItem29.Text = "Consultar Telechats"
        '
        'MenuItem30
        '
        Me.MenuItem30.Index = 1
        Me.MenuItem30.Text = "Crear Telechats"
        '
        'MenuItem33
        '
        Me.MenuItem33.Index = 6
        Me.MenuItem33.MenuItems.AddRange(New System.Windows.Forms.MenuItem() {Me.MenuItem34, Me.MenuItem35})
        Me.MenuItem33.Text = "&Diccionarios"
        '
        'MenuItem34
        '
        Me.MenuItem34.Index = 0
        Me.MenuItem34.Text = "&Consultar Diccionarios"
        '
        'MenuItem35
        '
        Me.MenuItem35.Index = 1
        Me.MenuItem35.Text = "C&rear Diccionario"
        '
        'MenuItem8
        '
        Me.MenuItem8.Index = 7
        Me.MenuItem8.MenuItems.AddRange(New System.Windows.Forms.MenuItem() {Me.mnuAgrChat, Me.mnuConsChats})
        Me.MenuItem8.Text = "&Chats"
        '
        'mnuAgrChat
        '
        Me.mnuAgrChat.Index = 0
        Me.mnuAgrChat.Text = "&Crear Chat"
        '
        'mnuConsChats
        '
        Me.mnuConsChats.Index = 1
        Me.mnuConsChats.Text = "C&onsultar"
        '
        'MenuItem15
        '
        Me.MenuItem15.Index = 1
        Me.MenuItem15.MenuItems.AddRange(New System.Windows.Forms.MenuItem() {Me.MenuItem16, Me.MenuItem27, Me.mnuConetoCola, Me.mnuPriorizar, Me.mnuConsCola})
        Me.MenuItem15.Text = "Recepción"
        '
        'MenuItem16
        '
        Me.MenuItem16.Index = 0
        Me.MenuItem16.Text = "Mensajes Recibidos"
        '
        'MenuItem27
        '
        Me.MenuItem27.Index = 1
        Me.MenuItem27.Text = "Mensajes No Procesados"
        '
        'mnuConetoCola
        '
        Me.mnuConetoCola.Index = 2
        Me.mnuConetoCola.Text = "Deprecado: Conteo de Cola"
        Me.mnuConetoCola.Visible = False
        '
        'mnuPriorizar
        '
        Me.mnuPriorizar.Index = 3
        Me.mnuPriorizar.Text = "Deprecado: Priorizar Mensajes"
        Me.mnuPriorizar.Visible = False
        '
        'mnuConsCola
        '
        Me.mnuConsCola.Index = 4
        Me.mnuConsCola.Text = "Deprecado: Consulta de Cola"
        Me.mnuConsCola.Visible = False
        '
        'MenuItem9
        '
        Me.MenuItem9.Index = 4
        Me.MenuItem9.Text = "-"
        '
        'mnuCrearAgrupadores
        '
        Me.mnuCrearAgrupadores.Index = 6
        Me.mnuCrearAgrupadores.Text = "C&rear Agrupadores"
        '
        'mdi
        '
        Me.AutoScaleBaseSize = New System.Drawing.Size(5, 13)
        Me.ClientSize = New System.Drawing.Size(972, 669)
        Me.IsMdiContainer = True
        Me.Menu = Me.MainMenu1
        Me.Name = "mdi"
        Me.StartPosition = System.Windows.Forms.FormStartPosition.CenterScreen
        Me.Text = "SMPP Manager"
        Me.ResumeLayout(False)

    End Sub

#End Region

    Private Sub MenuItem4_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MenuItem4.Click
        Dim ctl As New ctlRifa
        ctl.MdiParent = Me
        ctl.Show()
    End Sub
    Private Sub MenuItem7_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MenuItem7.Click
        Dim ctl As New ctlSuscripciones
        ctl.MdiParent = Me
        ctl.Show()
    End Sub

    Private Sub MenuItem3_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MenuItem3.Click
        Dim a As New agrRifa
        a.MdiParent = Me
        a.Show()
    End Sub

    Private Sub MenuItem6_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MenuItem6.Click
        Dim a As New agrSuscripcion
        a.MdiParent = Me
        a.Show()
    End Sub

    'Private Sub MenuItem8_Click(ByVal sender As System.Object, ByVal e As System.EventArgs)
    '    Dim ctl As New ctlSuscriptores
    '    ctl.MdiParent = Me
    '    ctl.Show()
    'End Sub

    Private Sub MenuItem11_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MenuItem11.Click
        Dim c As New consRifas
        c.MdiParent = Me
        c.Show()
    End Sub
    Private Sub MenuItem12_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MenuItem12.Click
        Dim c As New consSuscripciones
        c.MdiParent = Me
        c.Show()
    End Sub

    Private Sub MenuItem16_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MenuItem16.Click
        Dim a As New ctlRecepcion
        a.MdiParent = Me
        a.Show()
    End Sub

    Private Sub MenuItem18_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MenuItem18.Click
        Dim c As New consListados
        c.MdiParent = Me
        c.Show()
    End Sub

    Private Sub MenuItem19_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MenuItem19.Click
        Dim a As New agrListado
        a.MdiParent = Me
        a.Show()
    End Sub

    Private Sub MenuItem21_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MenuItem21.Click
        Dim c As New consEncuestas
        c.MdiParent = Me
        c.Show()
    End Sub

    Private Sub MenuItem22_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MenuItem22.Click
        Dim a As New agrEncuesta
        a.MdiParent = Me
        a.Show()
    End Sub

    Private Sub MenuItem24_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MenuItem24.Click
        Dim c As New consTrivias
        c.MdiParent = Me
        c.Show()
    End Sub

    Private Sub MenuItem25_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MenuItem25.Click
        Dim a As New agrTrivia
        a.MdiParent = Me
        a.Show()
    End Sub

    Private Sub MenuItem26_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MenuItem26.Click
        Dim ctl As New ctlTrivia
        ctl.MdiParent = Me
        ctl.Show()
    End Sub

    Private Sub MenuItem27_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MenuItem27.Click
        Dim a As New ctlNoProcesados
        a.MdiParent = Me
        a.Show()
    End Sub

    Private Sub MenuItem29_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MenuItem29.Click
        Dim c As New consTelechats
        c.MdiParent = Me
        c.Show()
    End Sub

    Private Sub MenuItem30_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MenuItem30.Click
        Dim a As New agrTelechat
        a.MdiParent = Me
        a.Show()
    End Sub

    Private Sub MenuItem31_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MenuItem31.Click
        Dim a As New cargBatchSuscripcion
        a.MdiParent = Me
        a.Show()
    End Sub



    Private Sub MenuItem35_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MenuItem35.Click
        Dim a As New agrDiccionario
        a.MdiParent = Me
        a.Show()
    End Sub

    Private Sub MenuItem34_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MenuItem34.Click
        Dim c As New consDiccionarios
        c.MdiParent = Me
        c.Show()
    End Sub

    Private Sub MenuItem37_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles MenuItem37.Click
        Dim a As New cargBatchRifa
        a.MdiParent = Me
        a.Show()
    End Sub

    Private Sub mnuAgrChat_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles mnuAgrChat.Click
        Dim c As New agrChat
        c.MdiParent = Me
        c.Show()
    End Sub

    Private Sub mnuConsChats_Click(ByVal sender As System.Object, ByVal e As System.EventArgs) Handles mnuConsChats.Click
        Dim c As New consChats With {.MdiParent = Me}
        c.Show()
    End Sub

    Private Sub mnuAgrupadores_Click(sender As Object, e As EventArgs) Handles mnuAgrupadores.Click
        Dim c As New consAgrupador With {.MdiParent = Me}
        c.Show()
    End Sub

    Private Sub mnuCrearAgrupadores_Click(sender As Object, e As EventArgs) Handles mnuCrearAgrupadores.Click
        Dim c As New agrAgrupadores With {.MdiParent = Me}
        c.Show()
    End Sub
End Class
