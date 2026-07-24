$path = 'LIST TABLE ERP CWA.md'
$text = Get-Content -Path $path -Raw

# Convert literal escape sequences to real newlines
$text = $text -replace '\\r\\n', [Environment]::NewLine

# Remove malformed duplicate section header and whitespace artifacts
$text = $text -replace '(?m)## Skema Database Modul Sales & Distribution \(SD\) ERP CWA\s*', ''
$text = $text -replace '(?m)\s*### `customer_groups`\\r\\n\\r\\n- \*\*Deskripsi:\*\*', '### `customer_groups`\n\n- **Deskripsi:**'

# Remove separator lines made of = or long dashes
$text = $text -replace '(?m)^[=]+\s*$', ''
$text = $text -replace '(?m)^[-]{10,}\s*$', ''

# Normalize section titles
$replacements = @{
    'SKEMA DATABASE MODUL SALES & DISTRIBUTION (SD) ERP CWA' = '## Skema Database Modul Sales & Distribution (SD) ERP CWA'
    '1. MASTER DATA PELANGGAN & WILAYAH' = '## 1. Master Data Pelanggan & Wilayah'
    '2. LOYALTY POINT & REWARD PELANGGAN' = '## 2. Loyalty Point & Reward Pelanggan'
    '3. PESANAN PENJUALAN & VERIFIKASI PO (SALES ORDER)' = '## 3. Pesanan Penjualan & Verifikasi PO (Sales Order)'
    '4. PACKING, PENGIRIMAN & LOGISTIK' = '## 4. Packing, Pengiriman & Logistik'
    '5. FAKTUR PENJUALAN, TTI, & TTP (SALES INVOICING)' = '## 5. Faktur Penjualan, TTI, & TTP (Sales Invoicing)'
    '6. PENERIMAAN PEMBAYARAN & PIUTANG (AR PAYMENT)' = '## 6. Penerimaan Pembayaran & Piutang (AR Payment)'
    '7. RETUR PENJUALAN, KOMISI SALES & PAJAK' = '## 7. Retur Penjualan, Komisi Sales & Pajak'
}
foreach ($key in $replacements.Keys) {
    $text = $text -replace [regex]::Escape($key), $replacements[$key]
}

# Normalize table headings and descriptions
$text = $text -replace '(?m)^Tabel:\s*(.+)$', '### `$1`'
$text = $text -replace '(?m)^Deskripsi:\s*(.+)$', '- **Deskripsi:** $1'

# Ensure blank line after headings
$text = $text -replace '(?m)^(## .+)$\r?\n(?!\r?\n)', '$1\r\n\r\n'
$text = $text -replace '(?m)^(### `.+`)$\r?\n(?!\r?\n)', '$1\r\n\r\n'

# Collapse multiple blank lines to two
$text = $text -replace '(?m)(\r?\n){3,}', '\r\n\r\n'

Set-Content -Path $path -Value $text -Encoding utf8
Write-Output 'Formatted LIST TABLE ERP CWA.md'
