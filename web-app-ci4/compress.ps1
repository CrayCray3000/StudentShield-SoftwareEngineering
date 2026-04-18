Add-Type -AssemblyName System.Drawing
$img = [System.Drawing.Image]::FromFile('public\images\logo.png')
$bmp = New-Object System.Drawing.Bitmap 300, 300
$g = [System.Drawing.Graphics]::FromImage($bmp)
$g.InterpolationMode = [System.Drawing.Drawing2D.InterpolationMode]::HighQualityBicubic
$g.DrawImage($img, 0, 0, 300, 300)
$bmp.Save('public\images\logo-optimized.png', [System.Drawing.Imaging.ImageFormat]::Png)
$g.Dispose()
$bmp.Dispose()
$img.Dispose()
