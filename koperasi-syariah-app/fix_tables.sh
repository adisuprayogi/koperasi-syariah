#!/bin/bash

echo "Fixing table layouts to fit content container..."

# List of important table files to fix
TABLE_FILES=(
    "resources/views/pengurus/simpanan/index.blade.php"
    "resources/views/pengurus/anggota/index.blade.php"
    "resources/views/pengurus/pengajuan/index.blade.php"
    "resources/views/pengurus/pembiayaan/index.blade.php"
    "resources/views/anggota/pengajuan/index.blade.php"
    "resources/views/admin/jenis-simpanan/index.blade.php"
    "resources/views/admin/jenis-pembiayaan/index.blade.php"
    "resources/views/admin/pengurus/index.blade.php"
)

for file in "${TABLE_FILES[@]}"; do
    echo "Processing $file..."
    
    # Replace px-6 py-4 with px-3 py-3 in td elements
    sed -i '' 's/px-6 py-4/px-3 py-3/g' "$file"
    
    # Add truncate class to td elements
    sed -i '' 's/whitespace-nowrap>/whitespace-nowrap truncate/g' "$file"
    
    # Remove double truncate if exists
    sed -i '' 's/whitespace-nowrap truncate truncate/whitespace-nowrap truncate/g' "$file"
    
    # For right-aligned th/td, make them center-aligned for better space usage
    sed -i '' 's/text-right text-/text-center text-/g' "$file"
    
    echo "Fixed $file"
done

echo "Table layout optimization completed!"
