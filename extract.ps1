$logPath = "C:\Users\HI\.gemini\antigravity\brain\e8cc3f8b-8d68-461b-a928-d019ddce64bb\.system_generated\logs\overview.txt"
$content = [System.IO.File]::ReadAllText($logPath, [System.Text.Encoding]::UTF8)
$startIndex = $content.LastIndexOf("<!DOCTYPE html>")
$endIndex = $content.LastIndexOf("</html>")
if ($startIndex -ge 0 -and $endIndex -gt $startIndex) {
    $endIndex += 7
    $htmlContent = $content.Substring($startIndex, $endIndex - $startIndex)
    $outDir = "c:\xampp\htdocs\knowlege\wp-content\themes\docy\assets\html"
    New-Item -ItemType Directory -Force -Path $outDir | Out-Null
    $outPath = "$outDir\lex-content.html"
    [System.IO.File]::WriteAllText($outPath, $htmlContent, [System.Text.Encoding]::UTF8)
    Write-Output "Written to $outPath"
} else {
    Write-Output "Could not find HTML tags"
}
