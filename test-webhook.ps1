# PowerShell 测试 OpenClaw Webhook API

$token = "openclaw-ai-fetcher-2026"
$url = "http://localhost:8081/api/openclaw/webhook"

# 测试数据
$body = @{
    type = "articles"
    items = @(
        @{
            title = "GPT-5 正式发布"
            summary = "OpenAI 发布 GPT-5，性能提升 10 倍"
            content = "OpenAI 今日正式发布 GPT-5，相比 GPT-4 性能提升 10 倍..."
            url = "https://example.com/gpt5"
        }
    )
} | ConvertTo-Json -Depth 10

Write-Host "=== 测试 OpenClaw Webhook API ===" -ForegroundColor Cyan
Write-Host ""
Write-Host "URL: $url" -ForegroundColor Yellow
Write-Host "Token: $token" -ForegroundColor Yellow
Write-Host ""
Write-Host "发送请求..." -ForegroundColor Green

# 发送 POST 请求
try {
    $response = Invoke-RestMethod -Uri $url -Method Post -Headers @{
        "Content-Type" = "application/json"
        "X-API-Token" = $token
    } -Body $body
    
    Write-Host ""
    Write-Host "✅ 响应成功！" -ForegroundColor Green
    Write-Host ""
    Write-Host "响应内容:" -ForegroundColor Cyan
    $response | ConvertTo-Json -Depth 5 | Write-Host -ForegroundColor White
    
} catch {
    Write-Host ""
    Write-Host "❌ 请求失败！" -ForegroundColor Red
    Write-Host ""
    Write-Host "错误信息:" -ForegroundColor Red
    Write-Host $_.Exception.Message -ForegroundColor Red
    Write-Host ""
    Write-Host "详细信息:" -ForegroundColor Red
    Write-Host $_.ErrorDetails.Message -ForegroundColor Red
}

Write-Host ""
Write-Host "=== 测试完成 ===" -ForegroundColor Cyan
