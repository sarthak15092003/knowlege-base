$logPath = "C:\Users\HI\.gemini\antigravity\brain\e8cc3f8b-8d68-461b-a928-d019ddce64bb\.system_generated\logs\overview.txt"
$content = Get-Content -Raw -Path $logPath -Encoding UTF8
$start = $content.IndexOf("<!DOCTYPE html>")
if ($start -ge 0) {
    $end = $content.IndexOf("</html>", $start)
    if ($end -gt $start) {
        $html = $content.Substring($start, $end - $start + 7)
        $dir = "c:\xampp\htdocs\knowlege\wp-content\themes\docy\assets\html"
        New-Item -ItemType Directory -Force -Path $dir | Out-Null
        Set-Content -Path "$dir\lex-content.html" -Value $html -Encoding UTF8
        Write-Output "Successfully extracted HTML!"
    } else {
        Write-Output "</html> not found after <!DOCTYPE html>"
    }
} else {
    Write-Output "<!DOCTYPE html> not found"
}
