Public Module fncTextBox
    Private Sub validarNumeros(ByVal sender As System.Object, ByVal e As System.Windows.Forms.KeyPressEventArgs)
        If e.KeyChar.IsNumber(e.KeyChar) = False And e.KeyChar.IsControl(e.KeyChar) = False Then e.Handled = True
    End Sub
    Private Sub validarCodigoBarra(ByVal sender As System.Object, ByVal e As System.Windows.Forms.KeyPressEventArgs)
        If e.KeyChar.IsNumber(e.KeyChar) = False And e.KeyChar.IsControl(e.KeyChar) = False Then e.Handled = True
        If CType(sender, TextBox).TextLength >= 12 Then e.Handled = True
    End Sub
    Private Sub validarDecimales(ByVal sender As System.Object, ByVal e As System.Windows.Forms.KeyPressEventArgs)
        If Not e.KeyChar.IsNumber(e.KeyChar) And Not e.KeyChar.IsControl(e.KeyChar) Then
            If Not (e.KeyChar = "." And InStr(sender.text, ".") = 0) Then e.Handled = True
        Else
            If Not (e.KeyChar.IsControl(e.KeyChar) Or InStr(sender.text, ".") = 0 Or (e.KeyChar.IsNumber(e.KeyChar) And InStr(sender.text, ".") + 2 > Len(sender.text))) Then e.Handled = True
        End If
    End Sub
    Private Sub validarDecimales2(ByVal sender As System.Object, ByVal e As System.Windows.Forms.KeyPressEventArgs)
        If Not e.KeyChar.IsNumber(e.KeyChar) And Not e.KeyChar.IsControl(e.KeyChar) Then
            If Not (e.KeyChar = "." And InStr(sender.text, ".") = 0) Then e.Handled = True
        Else
            If Not (e.KeyChar.IsControl(e.KeyChar) Or InStr(sender.text, ".") = 0 Or (e.KeyChar.IsNumber(e.KeyChar) And InStr(sender.text, ".") + 2 > Len(sender.text))) Then e.Handled = True
        End If
    End Sub
    Private Sub validarDecimales3(ByVal sender As System.Object, ByVal e As System.Windows.Forms.KeyPressEventArgs)
        If Not e.KeyChar.IsNumber(e.KeyChar) And Not e.KeyChar.IsControl(e.KeyChar) Then
            If Not (e.KeyChar = "." And InStr(sender.text, ".") = 0) Then e.Handled = True
        Else
            If Not (e.KeyChar.IsControl(e.KeyChar) Or InStr(sender.text, ".") = 0 Or (e.KeyChar.IsNumber(e.KeyChar) And InStr(sender.text, ".") + 3 > Len(sender.text))) Then e.Handled = True
        End If
    End Sub
    Public Sub formatNumeros(ByVal sender As Object, ByVal e As System.EventArgs)
        'sender = FormatNumber(CType(sender, TextBox).Text, 0, TriState.True, TriState.False, TriState.True)
    End Sub
    Public Sub formatdecimales(ByVal sender As Object, ByVal e As System.EventArgs)
        'sender.text = FormatNumber(CType(sender, TextBox).Text, 2, TriState.True, TriState.False, TriState.True)
    End Sub

    Public Sub campoNumerico(ByVal textControl As TextBox, Optional ByVal entero As Boolean = True)
        If entero Then
            AddHandler textControl.KeyPress, AddressOf validarNumeros
            AddHandler textControl.TextChanged, AddressOf formatNumeros
        Else
            AddHandler textControl.KeyPress, AddressOf validarDecimales
            AddHandler textControl.TextChanged, AddressOf formatdecimales
        End If
    End Sub
    Public Sub campoNumerico(ByVal textControl As TextBox, ByVal decimales As Integer)
        If decimales = 2 Then
            AddHandler textControl.KeyPress, AddressOf validarDecimales2
        ElseIf decimales = 3 Then
            AddHandler textControl.KeyPress, AddressOf validarDecimales3
        End If
        AddHandler textControl.TextChanged, AddressOf formatdecimales
    End Sub
    Public Sub campoCodBarra(ByVal textControl As TextBox)
        AddHandler textControl.KeyPress, AddressOf validarCodigoBarra
    End Sub
End Module
