Imports System.Data.Odbc
Imports System.Data.OleDb
Imports vnframework

Public Module fncListBox

    Public Sub popular(ByRef lb As Object, ByRef tabla As String, ByVal bound As String, ByVal text As String, ByVal STRCONN As String, Optional ByVal WHERE As String = "", Optional ByVal ORDER As String = "")
        lb.Items.Clear()
        If Len(WHERE) > 0 Then WHERE = " WHERE " + WHERE
        If Len(ORDER) > 0 Then ORDER = " ORDER BY " + ORDER
        Try
            Dim cmd As New OleDbCommand("SELECT " & bound & "," & text & " FROM " + tabla + WHERE + ORDER, New OleDbConnection(STRCONN))
            If cmd.Connection.State <> ConnectionState.Open Then cmd.Connection.Open()
            Dim rs As OleDbDataReader = cmd.ExecuteReader
            While rs.Read
                lb.Items.Add(New listitem(rs(1), rs(0)))
            End While
            rs.Close()
            cmd.Connection.Close()
        Catch thisError As OleDbException
            showError(thisError.Message)
        End Try
        If lb.Items.Count > 0 Then lb.SelectedIndex = 0
    End Sub

    Public Sub popular(ByRef lb As Object, ByRef tabla As String, ByVal bound As String, ByVal text As String, ByVal CNX As OdbcConnection, Optional ByVal WHERE As String = "", Optional ByVal ORDER As String = "")
        lb.Items.Clear()
        If Len(WHERE) > 0 Then WHERE = " WHERE " + WHERE
        If Len(ORDER) > 0 Then ORDER = " ORDER BY " + ORDER
        Try
            Dim cmd As New OdbcCommand("SELECT " & bound & "," & text & " FROM " + tabla + WHERE + ORDER, CNX)
            If CNX.State <> ConnectionState.Open Then CNX.Open()
            Dim rs As OdbcDataReader = cmd.ExecuteReader
            While rs.Read
                lb.Items.Add(New listitem(rs(1), rs(0)))
            End While
            rs.Close()
            cmd.Connection.Close()
        Catch thisError As OdbcException
            showError(thisError.Message)
        End Try
        If lb.Items.Count > 0 Then lb.SelectedIndex = 0
    End Sub
    Public Function valor(ByVal lb As ListBox, Optional ByVal index As Integer = -1, Optional ByVal fromSelection As Boolean = True) As String
        If fromSelection Then
            If lb.SelectedIndex = -1 Then Return -1
            Dim mlist As listitem
            If index <> -1 Then
                mlist = lb.SelectedItems.Item(index)
            Else
                mlist = lb.Items(lb.SelectedIndex)
            End If
            Return mlist.id
        Else
            If index = -1 Then Return -1
            Dim mlist As listitem
            If index > lb.Items.Count Then Return -1
            mlist = lb.Items(index)
            Return mlist.id
        End If
    End Function
    Public Function getNombre(ByVal lb As ListBox, Optional ByVal index As Integer = -1, Optional ByVal fromSelection As Boolean = True) As String
        If fromSelection Then
            If lb.SelectedIndex = -1 Then Return ""
            Dim mlist As listitem
            If index <> -1 Then
                mlist = lb.SelectedItems.Item(index)
            Else
                mlist = lb.Items(lb.SelectedIndex)
            End If
            Return mlist.Name
        Else
            If index = -1 Then Return ""
            Dim mlist As listitem
            If index > lb.Items.Count Then Return ""
            mlist = lb.Items(index)
            Return mlist.Name
        End If
    End Function
    Sub seleccionarTodos(ByVal lb As ListBox)
        Dim i As Integer
        For i = 0 To lb.Items.Count - 1
            lb.SetSelected(i, True)
        Next i
    End Sub
    Public Function valor(ByVal cb As Object) As String
        If cb.SelectedIndex = -1 Then Return -1
        Dim mlist As listitem
        mlist = cb.Items(cb.SelectedIndex)
        Return mlist.id
    End Function
    Public Function valor(ByVal dg As DataGrid, Optional ByVal colNumber As Integer = 0) As String
        Dim dt As DataTable = dg.DataSource
        Return 1
gotoError: Return -1
    End Function
    Public Function seleccionar(ByVal cb As ComboBox, ByVal valor As String) As Boolean
        With cb
            For i As Integer = 0 To .Items.Count - 1
                Dim mlist As listitem
                mlist = .Items(i)
                Dim id As String = mlist.id
                If id = valor Then
                    .SelectedIndex = i
                    Return True
                End If
            Next i
        End With
        Return False
    End Function
    Sub llenarCB(ByVal comboBox As ComboBox, ByVal ts As DataGridTableStyle)
        comboBox.Items.Clear()
        With ts
            Dim GridColumnStyle As DataGridTextBoxColumn
            For Each GridColumnStyle In .GridColumnStyles
                comboBox.Items.Add(New listitem(GridColumnStyle.HeaderText, GridColumnStyle.MappingName))
            Next
        End With
        If comboBox.Items.Count > 0 Then comboBox.SelectedIndex = 0
    End Sub
End Module