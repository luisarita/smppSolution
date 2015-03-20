Public Module globalFunctions
    Function listenThreadStatus() As Threading.ThreadState
        Return listenThread.ThreadState
    End Function
    Function scanThreadStatus() As Threading.ThreadState
        Return scanThread.ThreadState
    End Function
    Function pingThreadStatus() As Threading.ThreadState
        Return pingThread.ThreadState
    End Function
    Function addReceived() As Integer
        msgCountIn += 1
        Return msgCountIn
    End Function
    Function addSent() As Integer
        msgCountOut += 1
        Return msgCountOut
    End Function
End Module
