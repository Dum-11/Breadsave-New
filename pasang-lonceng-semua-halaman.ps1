# Jalankan dari root proyek BreadSaver di PowerShell: .\pasang-lonceng-semua-halaman.ps1
# File yang sudah punya @include tidak akan diubah. Tiap file di-backup dahulu.
$ErrorActionPreference = 'Stop'
$project = (Get-Location).Path
$viewRoot = Join-Path $project 'resources\views'
$component = Join-Path $viewRoot 'components\notifikasi-panel.blade.php'

if (-not (Test-Path $component)) {
    throw 'Komponen resources/views/components/notifikasi-panel.blade.php belum ada.'
}

$include = "@include('components.notifikasi-panel')"
$roles = @('pembeli', 'pegawai', 'admin')
$backupRoot = Join-Path $project 'storage\app\notifikasi-views-backup'

foreach ($role in $roles) {
    $folder = Join-Path $viewRoot $role
    if (-not (Test-Path $folder)) { continue }

    Get-ChildItem -Path $folder -Filter '*.blade.php' -File | ForEach-Object {
        $path = $_.FullName
        $content = [System.IO.File]::ReadAllText($path)

        if ($content.Contains($include)) {
            Write-Host "Sudah ada: $role/$($_.Name)"
            return
        }

        if ($content -notmatch '(?i)</body\s*>') {
            Write-Warning "Tidak ada tag </body>: $role/$($_.Name)"
            return
        }

        $regex = [System.Text.RegularExpressions.Regex]::new('(?i)</body\s*>')
        $newContent = $regex.Replace($content, "`r`n$include`r`n</body>", 1)

        $roleBackup = Join-Path $backupRoot $role
        [System.IO.Directory]::CreateDirectory($roleBackup) | Out-Null
        [System.IO.File]::WriteAllText((Join-Path $roleBackup $_.Name), $content)
        [System.IO.File]::WriteAllText($path, $newContent, [System.Text.UTF8Encoding]::new($false))
        Write-Host "Ditambahkan: $role/$($_.Name)"
    }
}
Write-Host 'Selesai. Ikon lonceng sekarang tersedia di semua halaman peran tersebut.'
