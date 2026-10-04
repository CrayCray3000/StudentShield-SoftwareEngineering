$ErrorActionPreference = 'Stop'
$wordApp = New-Object -ComObject Word.Application
$wordApp.Visible = $false
$wordApp.DisplayAlerts = 0
try {
    $workingDoc = $wordApp.Documents.Open('D:\laragon\www\student shield\StudentShield Capstone.docx', $false, $false)
    $workingDoc.Repaginate()
    $workingDoc.Fields.Update() | Out-Null
    foreach ($toc in $workingDoc.TablesOfContents) { $toc.Update() }
    $workingDoc.Repaginate()
    $workingDoc.Fields.Update() | Out-Null
    $workingDoc.Save()
    $workingDoc.ExportAsFixedFormat('D:\laragon\www\student shield\outputs\capstone-compliance\revised-review.pdf', 17)
    Write-Output ('Pages: ' + $workingDoc.ComputeStatistics(2))
    $workingDoc.Close(0)
} finally {
    $wordApp.Quit()
}
