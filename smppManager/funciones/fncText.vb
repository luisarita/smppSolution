Imports System.Text.RegularExpressions
Module fncText
    Function validarPass(ByVal texto As String) As Boolean
        Dim resultado As Boolean = False
        If Regex.IsMatch(texto, "^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$") Then resultado = True
        Return resultado
    End Function
End Module