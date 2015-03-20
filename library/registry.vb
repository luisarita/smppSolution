Imports Microsoft.Win32

Public Module registry
    Public reg As New vnframework.windowsRegistry("SOFTWARE\SMPPAPP\")

    Class registryVals
        Public bd As String
        Public user As String
        Public pass As String
        Public MONITORING_ENABLED As Boolean
        Public MONITORING_SMS As Boolean

        Public PINGINTERVAL As Integer
        Public SMS_NUMBER As String
        Public SMS_NUMBER_OUT As String
        Public SLEEPSCAN As Integer
    End Class
    Public Function loadRegistry() As registryVals
        Dim myData As New registryVals
        reg.hive = RegistryHive.LocalMachine
        myData.bd = reg.GetFromRegistry("bd", "")
        myData.user = reg.GetFromRegistry("user", "")
        myData.pass = reg.GetFromRegistry("pass", "")

        myData.PINGINTERVAL = reg.GetFromRegistry("PINGINTERVAL", 60)
        myData.SMS_NUMBER = reg.GetFromRegistry("SMS_NUMBER", "")
        myData.SMS_NUMBER_OUT = reg.GetFromRegistry("SMS_NUMBER_OUT", "9999")

        myData.MONITORING_ENABLED = CBool(reg.GetFromRegistry("MONITORING_ENABLED", False))
        myData.MONITORING_SMS = CBool(reg.GetFromRegistry("MONITORING_SMS", False))
        myData.SLEEPSCAN = CInt(reg.GetFromRegistry("SLEEPSCAN", 200))
        Return myData
    End Function
    Public Function saveRegistry(ByVal myData As registryVals) As Boolean
        reg.hive = RegistryHive.LocalMachine
        reg.WriteToRegistry("bd", myData.bd)
        reg.WriteToRegistry("user", myData.user)
        reg.WriteToRegistry("pass", myData.pass)
        reg.WriteToRegistry("MONITORING_ENABLED", myData.MONITORING_ENABLED)
        reg.WriteToRegistry("MONITORING_SMS", myData.MONITORING_SMS)
        reg.WriteToRegistry("PINGINTERVAL", myData.PINGINTERVAL)
        reg.WriteToRegistry("SMS_NUMBER", myData.SMS_NUMBER)
        reg.WriteToRegistry("SMS_NUMBER_OUT", myData.SMS_NUMBER_OUT)
        reg.WriteToRegistry("SLEEPSCAN", myData.SLEEPSCAN)
        Return True
    End Function
    Public Function GetFromRegistry(ByVal valueName As String, Optional ByRef errInfo As String = "") As String
        reg.hive = RegistryHive.LocalMachine
        Return reg.GetFromRegistry(valueName, errInfo)
    End Function
End Module